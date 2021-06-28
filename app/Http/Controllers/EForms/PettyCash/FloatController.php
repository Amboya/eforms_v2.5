<?php

namespace App\Http\Controllers\EForms\PettyCash;

use App\Http\Controllers\Controller;
use App\Models\EForms\PettyCash\PettyCashFloat;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\EForms\PettyCash\PettyCashReimbursement;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class FloatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $myUnits = ConfigWorkFlow:: Where(Auth::user()->code_column, Auth::user()->profile_job_code)
            ->Where(Auth::user()->unit_column, Auth::user()->profile_unit_code)
            ->get();

        $total_float = 0 ;
        $total_utilised = 0 ;
        $total_cash = 0 ;

        foreach ($myUnits as $unit){
            $model = PettyCashFloat::firstOrCreate(
                [
                    'user_unit_id'=> $unit->id,
                    'user_unit_code'=> $unit->user_unit_code,
                ],
                [
                    'user_unit_id'=> $unit->id,
                    'user_unit_code'=> $unit->user_unit_code,
                    'float' => 0 ,
                    'utilised' => 0 ,
                    'cash' => 0 ,
                    'percentage'  => config('constants.percentage_reimbursement'),
                    'created_by'  => Auth::user()->id,
                    'created_by_name'  => Auth::user()->name,
                ]
            );
            $total_float =+ $model->float ;
            $total_utilised =+ $model->utilised ;
            $total_cash =+ $model->cash ;

            $units[] = $model ;
        }

        //add to totals
        $totals['total_units'] = sizeof($units);
        $totals['total_float'] = $total_float;
        $totals['total_utilised'] = $total_utilised;
        $totals['total_cash'] = $total_cash;

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $units,
            'totals' => $totals,
            'units' => $units,
        ];

        //return view
        return view('eforms.petty-cash.float-management.dashboard')->with($params);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $model = PettyCashFloat::find($request->user_unit_field);
        $model->float = $request->new_float ;
        //IDEAL - CASH HAS TO INCREASE
        $model->cash = ($request->new_float) -  ($model->utilised ?? 0) ;
        $model->save();
        return Redirect::back()->with('message', 'Submitted Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }




    public function reimbursementShow($id)
    {
        //get the details
        $model = PettyCashFloat::find($id);
        $myUnits = PettyCashModel:: Where('user_unit_code', $model->user_unit_code);

        $total_payment = $myUnits->sum('total_payment');
        $total_change = $myUnits->sum('change');
        $total_amount = $total_payment - $total_change ;

        //add to totals
        $totals['total_units'] = sizeof($myUnits->get());
        $totals['total_float'] = $model->float ;
        $totals['total_utilised'] = $model->utilised ;;
        $totals['total_cash'] = $model->cash ;;

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $myUnits->get(),
            'totals' => $totals,
            'model' => $model,
            'total_amount' =>$total_amount
        ];

        //return view
        return view('eforms.petty-cash.float-management.list')->with($params);
    }

    public function reimbursementStore(Request $request){

        $float_model =PettyCashFloat::find($request->user_unit_field);
        $percentage = ($float_model->cash * 100) / $float_model->float ;

        dd($request->date_from );
        $user = Auth::user();
        $reimbursement_float = PettyCashReimbursement::updateOrCreate(
            [
                'user_unit_id' => $float_model->user_unit_id ,
                'user_unit_code' => $float_model->user_unit_code ,
                'from' => $request->date_from ,
                'to' => $request->date_to ,
                'amount' => $request->float_given ,
                'reason' => $request->reason ,
                'name' => $user->name ,
                'title' => $user->job_code ,
                'profile' => $user->profile_id ,
                'created_by' => $user->id,
                'cash_percentage' => $percentage,
            ],
            [
                'user_unit_id' => $float_model->user_unit_id  ,
                'user_unit_code' => $float_model->user_unit_code ,
                'from' => $request->date_from ,
                'to' => $request->date_to ,
                'amount' => $request->float_given ,
                'reason' => $request->reason ,
                'name' => $user->name ,
                'title' => $user->job_code ,
                'profile' => $user->profile_id ,
                'created_by' => $user->id,
                'cash_percentage' => $percentage,
            ]
        );

        //IDEAL - CASH HAS TO INCREASE  AND UTILISED GOES DOWN
        $float_model->cash = ($float_model->cash) +  ($request->float_given ?? 0) ;
        $float_model->utilised = ($float_model->utilised) -  ($request->float_given ?? 0) ;
        $float_model->save();

        //FINALLY MOVE THE FORMS TO A NEW STATUS
        PettyCashModel::where('user_unit_code',$float_model->user_unit_code )
            ->update(
                [
//                    'config_status_id' => 1
                    'region' => 1
                ]
            );

        return Redirect::back()->with('message', 'Submitted Successfully');
    }



}
