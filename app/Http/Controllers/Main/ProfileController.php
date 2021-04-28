<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\ProfilePermissionsModel;
use App\Models\Main\ProfileAssigmentModel ;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
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
    public function index()
    {
        //get all the categories
        $list = ProfileModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
        ];

        //get all status
        return view('main.profile.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createAssignment()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProfileRequest $request)
    {
        $user = Auth::user();
        $model = ProfileModel::firstOrCreate(
            [
                'name' => $request->name,
                'code' => $request->code,
            ],

            [
                'name' =>  $request->name,
                'code' =>  $request->code,
                'description'=>  $request->description,
                'created_by'=> $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of System Profile","update", " system profile created", json_encode( $model));

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
        $model = ProfileModel::find($request->profile_id);
        $model->name = $request->name ;
        $model->code = $request->code ;
        $model->description = $request->description ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request,"Updating of System Profile","update", " system profile updated", json_encode( $model));
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
        $model = ProfileModel::find($id);
        ProfileModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request,"Deleting of System Profile ","delete", " system profile deleted", json_encode( $model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' has been Deleted successfully');

    }

    public function assignmentCreate(){
        //get all the categories
        $profiles = ProfilePermissionsModel::all();
        $eforms = EFormModel::all();
        $users = User::orderBy('name')->get();

        //data to send to the view
        $params = [
            'profiles' => $profiles,
            'eforms' => $eforms,
            'users' => $users,
        ];

        return view('main.profile.assignment')->with($params);
    }

    public function assignmentStore(Request $request)
    {
        $user = Auth::user();
        $model = ProfileAssigmentModel::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'eform_id' => $request->eform_id
            ],
            [
                'user_id' => $request->user_id,
                'eform_id' => $request->eform_id,
                'profile' => $request->profile,
                'created_by'=> $user->id
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of Profile Assignment","update", " system profile assignment created", json_encode( $model));

        return Redirect::back()->with('message', 'Profile for ' . $model->profile . ' has been Assigned successfully');

    }


    public function delegationCreate(){

        //get all the categories
        $eforms = EFormModel::all();
        $users = User::orderBy('name')->get();
        $mine = Auth::user();

        //data to send to the view
        $params = [
            'profiles' => Auth::user()->user_profile,
            'eforms' => $eforms,
            'users' => $users,
            'mine' => $mine,
        ];

        //  dd($mine->user_profile);

        return view('main.profile.delegation')->with($params);
    }

    public function delegationStore(Request $request)
    {
        //get logged in user
        $user = Auth::user();
        //get eform
        $eform = EFormModel::find($request->eform_id);

        $oldDate = strtotime($request->delegation_end_date);

        //create model
        $model = ProfileDelegatedModel::firstOrCreate(
            [
                'eform_id' =>  $eform->id,
                'eform_code' =>  $eform->name ,

                'delegated_to' => $request->user_id ,
                'delegated_profile' => $request->profile,
                'delegated_user_unit' => $user->user_unit_code,
                'delegated_job_code' => $user->job_code,
                'delegation_end' => $request->delegation_end_date,
                'config_status_id' => config('constants.one') ,
                'created_by'=> $user->id
            ],
            [
                'eform_id' =>  $eform->id,
                'eform_code' =>  $eform->name ,
                'delegated_to' => $request->user_id ,
                'delegated_profile' => $request->profile,
                'delegated_user_unit' => $user->user_unit_code,
                'delegated_job_code' => $user->job_code,
                'delegation_end' => $request->delegation_end_date,
                'config_status_id' => config('constants.one') ,
                'created_by'=> $user->id
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of Profile Delegation","update", " system profile delegation created", json_encode( $model));

        return Redirect::back()->with('message', 'Profile has been Delegated successfully');

    }


}
