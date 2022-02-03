<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\TotalsRequest;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\EForms\KilometerAllowance\KilometerAllowanceModel;
use App\Models\Main\EFormModel;
use App\Models\Main\TotalsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TotalsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Store a piece of data in the session...
        session(['eform_id' => config('constants.eforms_id.main_dashboard') ]);
        session(['eform_value'=> config('constants.eforms_name.main_dashboard')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //get all the Totals
        $list = TotalsModel::all();
        $eforms = EFormModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
            'eforms' => $eforms,
        ];

        //return with the data
        return view('main.totals.index')->with($params);
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
    public function store(TotalsRequest $request)
    {
        $user = Auth::user();
        $model = TotalsModel::firstOrCreate(
            [
                'name' => $request->name,
                'eform_id' => $request->eform_id,
            ],
            [
                'name' =>  $request->name,
                'value' =>  0 ,
                'url' =>  $request->url,
                'eform_id'=>  $request->eform_id,
                'color'=>  $request->color,
                'icon'=>  $request->icon,
                'created_by'=> $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of Totals","update", " totals created", json_encode( $model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Created successfully');


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
        $model = TotalsModel::find($request->totals_id);
        $model->name = $request->name ;
        $model->eform_id = $request->eform_id ;
        $model->color = $request->color ;
        $model->icon = $request->icon ;
        $model->url = $request->url ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request,"Updating of Totals","update", " totals updated", json_encode( $model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Created successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $model = TotalsModel::find($id);
        TotalsModel::destroy($id);

        //log the activity
        ActivityLogsController::store($request,"Deleting of Totals ","delete", " totals deleted", json_encode( $model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');

    }

    /**
     *
     */
    public function sync(){
        /**
         * Sync main dashboard total users
         */
        //count the total users from the users table
        $count_main_dashboard_users = User::all()->count();
        //get the total users total
        $totals= TotalsModel::where('eform_id', config('constants.eforms_id.main_dashboard'))
            ->where('name', 'Total Users')
            ->get()->first();
        //make update
        $totals->value = $count_main_dashboard_users ;
        $totals->save();

        //count the total users from the users table
        $count_main_dashboard_users = User::all()->count();
        //get the total users total
        $totals= TotalsModel::where('eform_id', config('constants.eforms_id.main_dashboard'))
            ->where('name', 'Total Users')
            ->get()->first();
        //make update
        $totals->value = $count_main_dashboard_users ;
        $totals->save();



        //PETTY CASH
        $eform_pettycahs = EFormModel::find( config('constants.eforms_id.petty_cash'));
        //[1]count pending applications
        $petty_cash_open = PettyCashModel::where('config_status_id', '>', config('constants.petty_cash_status.new_application'))
            ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
            ->count();
        $eform_pettycahs->total_pending = $petty_cash_open ;
        //update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('id', config('constants.totals.petty_cash_open'))
            ->first();
        $totals->value = $petty_cash_open;
        $totals->save();

        //[2]count pending new
        $petty_cash_new= PettyCashModel::where('config_status_id',  config('constants.petty_cash_status.new_application'))
            ->count();
        $eform_pettycahs->total_new = $petty_cash_new ;
        //update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('id', config('constants.totals.petty_cash_new'))
            ->first();
        $totals->value = $petty_cash_new;
        $totals->save();

        //[3]count pending closed
        $petty_cash_closed = PettyCashModel::where('config_status_id',  config('constants.petty_cash_status.closed'))
            ->count();
        $eform_pettycahs->total_closed = $petty_cash_closed ;
        //update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('id', config('constants.totals.petty_cash_closed'))
            ->first();
        $totals->value = $petty_cash_closed;
        $totals->save();

        //[4]count pending rejected
        $petty_cash_rejected = PettyCashModel::where('config_status_id',config('constants.petty_cash_status.rejected'))
            ->count();
        $eform_pettycahs->total_rejected = $petty_cash_rejected ;
        //update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('id', config('constants.totals.petty_cash_reject'))
            ->first();
        $totals->value = $petty_cash_rejected;
        $totals->save();

        //save petty cash
        $eform_pettycahs->save();


//        'petty_cash_new' => "5",
//        'petty_cash_open' => "6",
//        'petty_cash_closed' => "7",
//        'petty_cash_reject' => "8",



//KILOMETER ALLOWANCE
        $eform_kilometer = EFormModel::find( config('constants.eforms_id.kilometer_allowance'));
//[1]count pending applications
        $kilometer_open = KilometerAllowanceModel::where('config_status_id', '>', config('constants.kilometer_allowance_status.new_application'))
            ->where('config_status_id', '<', config('constants.kilometer_allowance_status.closed'))
            ->count();
        $eform_kilometer->total_pending = $kilometer_open ;
//update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('id', config('constants.totals.kilometer_allowance_open'))
            ->first();
        $totals->value = $kilometer_open;
        $totals->save();

//[2]count pending new
        $kilometer_allowance_new= KilometerAllowanceModel::where('config_status_id',  config('constants.kilometer_allowance_status.new_application'))
            ->count();
        $eform_kilometer->total_new = $kilometer_allowance_new ;
//update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('id', config('constants.totals.kilometer_allowance_new'))
            ->first();
        $totals->value = $kilometer_allowance_new;
        $totals->save();

//[3]count pending closed
        $kilometer_allowance_closed = KilometerAllowanceModel::where('config_status_id',  config('constants.kilometer_allowance_status.closed'))
            ->count();
        $eform_kilometer->total_closed = $kilometer_allowance_closed ;
//update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('id', config('constants.totals.kilometer_allowance_closed'))
            ->first();
        $totals->value = $kilometer_allowance_closed;
        $totals->save();

//[4]count pending rejected
        $kilometer_allowance_rejected = KilometerAllowanceModel::where('config_status_id',config('constants.kilometer_allowance_status.rejected'))
            ->count();
        $eform_kilometer->total_rejected = $kilometer_allowance_rejected ;
//update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('id', config('constants.totals.kilometer_allowance_reject'))
            ->first();
        $totals->value = $kilometer_allowance_rejected;
        $totals->save();

//save petty cash
        $eform_kilometer->save();


//        'kilometer_allowance_new' => "21",
//        'kilometer_allowance_open' => "22",
//        'kilometer_allowance_closed' => "23",
//        'kilometer_allowance_reject' => "24",


        //return back
        return Redirect::back()->with('message', 'Totals have been Synced successfully');

    }


}
