<?php

namespace App\Http\Controllers\EForms\PettyCash;

use App\Http\Controllers\Controller;
use App\Models\EForms\PettyCash\PettyCashFloat;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FloatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //count new forms
        $new_forms = PettyCashFloat:: count();

        $myUnits = ConfigWorkFlow:: Where(Auth::user()->code_column, Auth::user()->profile_job_code)
            ->Where(Auth::user()->unit_column, Auth::user()->profile_unit_code)
            ->get();

        foreach ($myUnits as $unit){

            $units[] = PettyCashFloat::firstOrUpdate(
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
                    'percentage'  => 80,
                    'created_by'  => Auth::user()->id,
                    'created_by_name'  => Auth::user()->name,
                ]
            );
        }

        dd($units);


        //

        //count pending forms
        $pending_forms = PettyCashFloat::where('config_status_id', '>', config('constants.petty_cash_status.new_application'))
            ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
            ->count();
        //count closed forms
        $closed_forms = PettyCashFloat::where('config_status_id', config('constants.petty_cash_status.closed'))
            ->count();
        //count rejected forms
        $rejected_forms = PettyCashFloat::where('config_status_id', config('constants.petty_cash_status.rejected'))
            ->count();



        //add to totals
        $totals['total_units'] = $new_forms;
        $totals['total_float'] = $pending_forms;
        $totals['total_utilised'] = $closed_forms;
        $totals['total_cash'] = $rejected_forms;

        //list all that needs me
        $get_profile = HomeController::getMyProfile();

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();

            $fromDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $tillDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();

        $list = PettyCashModel::
        where('config_status_id', config('constants.petty_cash_status.closed'))
            ->where('created_at', '>=', $fromDate)
            ->orWhere('created_at', '<=', $tillDate)
            ->paginate(5);

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list,
            'totals' => $totals,
            'pending' => $pending,
            'fromDate' => $fromDate,
            'tillDate' => $tillDate,
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
    public function update(Request $request, $id)
    {
        //
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





}
