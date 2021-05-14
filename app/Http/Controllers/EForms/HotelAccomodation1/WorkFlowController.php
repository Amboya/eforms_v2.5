<?php

namespace App\Http\Controllers\EForms\HotelAccomodation;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EForms\PettyCash\HomeController;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DepartmentModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class WorkFlowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Store a piece of data in the session...
        session(['eform_id' => config('constants.eforms_id.hotel_accommodation')]);
        session(['eform_code' => config('constants.eforms_name.hotel_accommodation')]);

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all the categories
        $list = ConfigWorkFlow::all();
        $users = User::orderBy('name')->get();

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me ,
            'users' => $users,
            'list' => $list,
        ];

        return view('eforms.hotel-accommodation.workflow')->with($params);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {

        $user = Auth::user();
        $model = DepartmentModel::firstOrCreate(
            [
                'name' => $request->name,
                'code' => $request->code,
            ],
            [
                'name' => $request->name,
                'code' => $request->code,
                'business_unit_code' => $request->business_unit_code,
                'cost_center_code' => $request->cost_center_code,
                'code_unit_superior' => $request->code_unit_superior,
                'created_by' => $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request, "Creating of User Unit", "update", " user unit created", json_encode($model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Created successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $model = ConfigWorkFlow::find($request->workflow_id);

        dd($request->all());

        $model->hod_code = $request->hod_code ??  "" ;
        $model->hod_unit = $request->hod_unit ??  "" ;
        $model->ca_code = $request->ca_code ??    "" ;
        $model->ca_unit = $request->ca_unit ??    "" ;
        $model->hrm_code = $request->hrm_code ??  "" ;
        $model->hrm_unit = $request->hrm_unit ??  "" ;
        $model->dir_code = $request->dir_code ??  "" ;
        $model->dir_unit = $request->dir_unit ??  "" ;
        $model->save();

        //log the activity
        //  ActivityLogsController::store($request, "Updating of Petty Cash User Unit Workflow", "update", "petty cash unit user workflow updated", $model->id);
        return Redirect::back()->with('message', 'Work Flow for ' . $model->name . ' have been Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
//        $model = DepartmentModel::find($id);
//        DepartmentModel::destroy($id);
//        //log the activity
//        ActivityLogsController::store($request, "Deleting of User Unit ", "delete", " user unit deleted", json_encode($model));
//        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');

    }
}
