<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\OperatingUnitsRequest;
use App\Models\Main\OperatingUnits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class OperatingUnitsController extends Controller
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
        session(['eform_code'=> config('constants.eforms_name.main_dashboard')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //get all the Location
        $list = OperatingUnits::all();

        //data to send to the view
        $params = [
            'list' => $list,
        ];

        //return with the data
        return view('main.operating_units.index')->with($params);
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
    public function store(OperatingUnitsRequest $request)
    {


        $user = Auth::user();
        $model = OperatingUnits::firstOrCreate(
            [
                'org_id' => $request->org_id,
                'ou_desc'=> $request->ou_desc,
                'bu_code'=> $request->bu_code,
                'bu_desc'=> $request->bu_desc,
            ],
            [
                'org_id' => $request->org_id,
                'ou_desc'=> $request->ou_desc,
                'bu_code'=> $request->bu_code,
                'bu_desc'=> $request->bu_desc,
                'created_by'=> $user->id,
            ]);

        //mark as as updated
        $affected_workflow = DB::table('config_system_work_flow' )
            ->where('user_unit_bc_code', $request->bu_code )
            ->update( ['org_id' => $request->org_id ] );

        //mark as as updated
        $affected_accounts = DB::table('eform_petty_cash_account' )
            ->where('business_unit_code', $request->bu_code )
            ->update( ['org_id' => $request->org_id ] );

        //log the activity
      //  ActivityLogsController::store($request,"Creating of Location","update", " location created", json_encode( $model));
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
    public function update(LocationRequest $request)
    {
        $model = LocationModel::find($request->directorate_id);
        $model->name = $request->name ;
        $model->code = $request->code ;
        $model->user_unit_id = $request->user_unit_id ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request,"Updating of Location","update", " location updated", json_encode( $model));
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
        $model = LocationModel::find($id);
        LocationModel::destroy($id);

        //log the activity
        ActivityLogsController::store($request,"Deleting of Location ","delete", " location deleted", json_encode( $model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');

    }


}
