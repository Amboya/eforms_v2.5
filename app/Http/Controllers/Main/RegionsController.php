<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegionsRequest;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\DivisionsModel;
use App\Models\Main\RegionsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class RegionsController extends Controller
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
        //get all the Directorates
        $list = RegionsModel::all();
        $regions  = DivisionsModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
            'regions' => $regions,
        ];
        //return with the data
        return view('main.regions.index')->with($params);
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
    public function store(RegionsRequest $request)
    {

        $user = Auth::user();
        $model = RegionsModel::firstOrCreate(
            [
                'name' => $request->name,
                'code' => $request->code,
            ],
            [
                'name' =>  $request->name,
                'code' =>  $request->code,
                'division_id'=>  $request->division_id,
                'created_by'=> $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of Region","update", " region created", json_encode( $model));
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
        $model = RegionsModel::find($request->region_id);
        $model->name = $request->name ;
        $model->code = $request->code ;
        $model->division_id = $request->division_id ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request,"Updating of Region","update", " region updated", json_encode( $model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $model = RegionsModel::find($id);
        RegionsModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request,"Deleting of Region","delete", " region deleted", json_encode( $model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');

    }

}
