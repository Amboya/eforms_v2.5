<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\ProfilePermissionsModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        session(['eform_id' => config('constants.eforms_id.main_dashboard')]);
        session(['eform_code' => config('constants.eforms_name.main_dashboard')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //get all the categories
        $list = ProfileModel::all();
        $delegation = ProfileDelegatedModel::orderBy('created_at')->get();

        //data to send to the view
        $params = [
            'delegation' => $delegation,
            'list' => $list,
        ];

        //get all status
        return view('main.profile.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function createAssignment()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
                'name' => $request->name,
                'code' => $request->code,
                'unit_column' =>  $request->unit_column,
                'code_column' =>  $request->code_column,
                'description' => $request->description,
                'created_by' => $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request, "Creating of System Profile", "update", " system profile created", json_encode($model->id));

        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $model = ProfileModel::find($request->profile_id);
        $model->name = $request->name;
        $model->code = $request->code;

        $model->unit_column = $request->unit_column ;
        $model->code_column = $request->code_column ;
        $model->description = $request->description;
        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of System Profile", "update", " system profile updated", json_encode($model->id));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Created successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $model = ProfileModel::find($id);
        ProfileModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request, "Deleting of System Profile ", "delete", " system profile deleted", json_encode($model->id));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' has been Deleted successfully');

    }

    public function assignmentCreate()
    {
        //get all the categories
        $profiles = ProfilePermissionsModel::all();
        $eforms = EFormModel::all();
        $users = User::orderBy('name')->get();
        $user_units = ConfigWorkFlow::select('id',  'user_unit_code', 'user_unit_description', 'user_unit_bc_code', 'user_unit_cc_code')->orderBy('user_unit_code', 'ASC')->get();

        //data to send to the view
        return view('main.profile.assignment')->with(compact('users', 'eforms', 'profiles', 'user_units'));
    }

    public function assignmentStore(Request $request)
    {
        if($request->units == null){
            return Redirect::back()->with('error', 'Yangutata!, Sorry,assignment failed to complete because no User-Units have been selected');
        }

        //get request user and profile
        $user = Auth::user();
        $profile_modal = ProfileModel::where('code', $request->profile)->first();
        //the users to swap
        $new_user_details = User::find( $request->user_id);
        //the columns to affect
        $code_column = $profile_modal->code_column ;
        $unit_column = $profile_modal->unit_column ;
        //make the update on config workflow
        if($code_column != "" && $unit_column != "" ) {
            //update the workflow
            $units = $request->units ;
            $work_flow = ConfigWorkFlow::whereIn('id', $units )
                ->update([
                    $code_column => $new_user_details->job_code,
                    $unit_column => $new_user_details->user_unit_code
                ]);
            //save the assignment
            $model = ProfileAssigmentModel::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'eform_id' => $request->eform_id
                ],
                [
                    'user_id' => $request->user_id,
                    'eform_id' => $request->eform_id,
                    'profile' => $request->profile,
                    'created_by' => $user->id
                ]);
            //log the activity
            ActivityLogsController::store($request, "Creating of Profile Assignment", "create profile assignment", " system profile assignment created", json_encode($model->id));
            //return
            return Redirect::back()->with('message', 'Profile ' . $profile_modal->name . ' has been Assigned successfully to '.$new_user_details->name);
        }
        else{
            return Redirect::back()->with('error', 'Assignment failed because profile unit_code and code_column are empty for the selected profile : '.$profile_modal->name);

        }


    }

    public function assignmentStoreSingle(Request $request)
    {
        //get request user and profile
        $user = Auth::user();
        $profile_modal = ProfileModel::where('code', $request->profile)->first();
        //the users to swap
        $new_user_details = User::find( $request->user_id);

            //save the assignment
            $model = ProfileAssigmentModel::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'eform_id' => $request->eform_id
                ],
                [
                    'user_id' => $request->user_id,
                    'eform_id' => $request->eform_id,
                    'profile' => $request->profile,
                    'created_by' => $user->id
                ]);
            //log the activity
            ActivityLogsController::store($request, "Creating of Profile Assignment", "create profile assignment", " system profile assignment created", json_encode($model->id));
            //return
            return Redirect::back()->with('message', 'Profile ' . $profile_modal->name . ' has been Assigned successfully to '.$new_user_details->name);
        }



    public function delegationList()
    {
        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $delegation = ProfileDelegatedModel::orderBy('created_at')->get();
        } else {
            $id = Auth::user()->id;
            $delegation = ProfileDelegatedModel::where('created_by', $id)->orderBy('created_at')->get();
        }
        $delegation->load('me', 'delegation');
        return view('main.profile.list_delegation')->with(compact('delegation'));
    }

    public function delegationEnd(Request $request, $id)
    {
        $model = ProfileDelegatedModel::find($id);
        $model->config_status_id = config('constants.non_active_state');
        $model->reason = $request->reason;
        $model->delegation_end = Carbon::now();
        $model->save();
        return Redirect::back()->with('message', 'Profile Delegation  has been ended successfully');
    }

    public function removeDelegation(Request $request)
    {
        $user = Auth::user();
        $count = 0 ;
        if($request->delegated_profiles == null ){
            return Redirect::back()->with('error', 'no delegated profiles selected to remove' );
        }
        foreach ($request->delegated_profiles as $d) {
            $count++;
            $model = ProfileDelegatedModel::find($d);
            $model->config_status_id = config('constants.non_active_state');
            $model->reason = "Delegation removed by system admin";
            $model->delegation_end = Carbon::now();
            $model->save();
        }
        ActivityLogsController::store($request, "Delegated Profile have been removed", "profile delegation removal", " system admin {$user->name} has removed {$count} delegations held by user id {$request->owner_id}", $request->owner_id);
        return Redirect::back()->with('message', 'Profile Delegation  has been ended successfully');
    }




    public function delegationCreate()
    {

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

    public function delegationShowOnBehalf()
    {
        //get all the categories
        $eforms = EFormModel::all();
        $users = User::with('user_profile')->orderBy('name')->get();
        $mine = Auth::user();
       // $profiles = ProfileAssigmentModel::all();

        dd($users->first() );

        //data to send to the view
        $params = [
            'profiles' => $profiles,
            'eforms' => $eforms,
            'users' => $users,
            'mine' => $mine,
        ];


        return view('main.profile.delegation_on_behalf')->with($params);
    }

    public function delegationStore(Request $request)
    {
        //get logged in user
        $user = Auth::user();
        //get eform
        $eform = EFormModel::find($request->eform_id);

        //create model
        $model = ProfileDelegatedModel::firstOrCreate(
            [
                'eform_id' => $eform->id,
                'eform_code' => $eform->name,

                'delegated_to' => $request->user_id,
                'delegated_profile' => $request->profile,
                'delegated_user_unit' => $user->user_unit_code,
                'delegated_job_code' => $user->job_code,
                'delegated_unit_column' => $user->unit_column,
                'delegated_code_column' => $user->code_column,
                'delegation_end' => $request->delegation_end_date,
                'config_status_id' => config('constants.active_state'),
                'created_by' => $user->id
            ],
            [
                'eform_id' => $eform->id,
                'eform_code' => $eform->name,
                'delegated_to' => $request->user_6id,
                'delegated_profile' => $request->profile,
                'delegated_user_unit' => $user->user_unit_code,
                'delegated_job_code' => $user->job_code,
                'delegated_unit_column' => $user->unit_column,
                'delegated_code_column' => $user->code_column,
                'delegation_end' => $request->delegation_end_date,
                'config_status_id' => config('constants.active_state'),
                'created_by' => $user->id
            ]);

        //log the activity
        ActivityLogsController::store($request, "Creating of Profile Delegation", "update", " system profile delegation created", json_encode($model->id));

        return Redirect::back()->with('message', 'Profile has been Delegated successfully');

    }

    public function delegationStoreOnBehalf(Request $request)
    {
        //get logged in user
        $owner = User::find($request->owner_id);
        //get eform
        $eform = EFormModel::find($request->eform_id);

        $profile = Profile::find($request->profile);

        //create model
        $model = ProfileDelegatedModel::firstOrCreate(
            [
                'eform_id' => $eform->id,
                'eform_code' => $eform->name,

                'delegated_to' => $request->user_id,
                'delegated_profile' => $profile->id,
                'delegated_user_unit' => $owner->user_unit_code,
                'delegated_job_code' => $owner->job_code,
                'delegated_unit_column' => $owner->unit_column,
                'delegated_code_column' => $owner->code_column,
                'delegation_end' => $request->delegation_end_date,
                'config_status_id' => config('constants.active_state'),
                'created_by' => $owner->id
            ],
            [
                'eform_id' => $eform->id,
                'eform_code' => $eform->name,
                'delegated_to' => $request->user_id,
                'delegated_profile' => $profile->id,
                'delegated_user_unit' => $owner->user_unit_code,
                'delegated_job_code' => $owner->job_code,
                'delegated_unit_column' => $owner->unit_column,
                'delegated_code_column' => $owner->code_column,
                'delegation_end' => $request->delegation_end_date,
                'config_status_id' => config('constants.active_state'),
                'created_by' => $owner->id
            ]);

        //log the activity
        ActivityLogsController::store($request, "Creating of Profile Delegation", "update", " system profile delegation created", json_encode($model->id));

        return Redirect::back()->with('message', 'Profile has been Delegated successfully');

    }

    public function delegationStoreUser(Request $request)
    {

        $user_login = Auth::user();
        //get logged in user
        $owner1 = User::where('staff_no',$request->user_id);

        if($owner1->exists()){
            $active_profile =ProfileDelegatedModel::where('delegated_to', $request->user_id)
                ->where( 'config_status_id' ,config('constants.active_state')) ;

            if($active_profile->exists()){
                $msg = 'The Delegated User '.$owner1->first()->name .', already has an Active Delegation ('.$active_profile->first()->delegated_profile.')' ;
                return Redirect::back()->with('error', $msg);
            }
        }else{
            $msg = 'Delegated Users Staff Number '.$request->user_id .', was not found in our records' ;
            return Redirect::back()->with('error', $msg);
        }

        //get logged in user
        $owner = User::where('staff_no',$request->owner_id)->first();


        //get eform
        $eform = EFormModel::find($request->eform_id);

        $profile = ProfileModel::find($request->profile);

        //create model
        $model = ProfileDelegatedModel::firstOrCreate(
            [
                'eform_id' => $eform->id,
                'eform_code' => $eform->name,

                'delegated_to' => $owner1->first()->id,
                'delegated_profile' => $profile->id,
                'delegated_user_unit' => $owner->user_unit_code,
                'delegated_job_code' => $owner->job_code,
                'delegated_unit_column' => $owner->unit_column,
                'delegated_code_column' => $owner->code_column,
                'delegation_end' => $request->delegation_end_date,
                'config_status_id' => config('constants.active_state'),
                'created_by' => $user_login->id
            ],
            [
                'eform_id' => $eform->id,
                'eform_code' => $eform->name,
                'delegated_to' => $owner1->first()->id,
                'delegated_profile' => $profile->id,
                'delegated_user_unit' => $owner->user_unit_code,
                'delegated_job_code' => $owner->job_code,
                'delegated_unit_column' => $owner->unit_column,
                'delegated_code_column' => $owner->code_column,
                'delegation_end' => $request->delegation_end_date,
                'config_status_id' => config('constants.active_state'),
                'owner'=> $owner->id ,
                'created_by' => $user_login->id
            ]);

        //log the activity
        ActivityLogsController::store($request, "Creating of Profile Delegation", "update", " system profile delegation created", json_encode($model->id));

        return Redirect::back()->with('message', 'Profile has been Delegated successfully');

    }


    // PROFILE TRANSFER
    public function transfer()
    {

        //get all the categories
        $eforms = EFormModel::all();
        $users = User::orderBy('name')->get();
        $mine = Auth::user();
        // $users->load('user_profile');

        $profiles = ProfileAssigmentModel::orderBy('id')->get();
        $profiles->load('profiles', 'form');

        return view('main.profile.transfer')->with(compact('users', 'eforms', 'mine', 'profiles'));
    }

    public function transferCreate(Request $request)
    {
        $owner_id = $request->owner_id;
        $user_id = $request->user_id;
        $key = 0 ;
        if($request->transfer_profiles == null ){
            return Redirect::back()->with('error', 'no profiles selected to transfer' );
        }

        foreach ($request->transfer_profiles as $d) {
            $key ++ ;
            $obj = json_decode($d);

            $eform_id = $obj->form;
            $profile = $obj->profiles;

            $profileAssigned = ProfileAssigmentModel::where('user_id', $owner_id)
                ->where('eform_id', $eform_id)
                ->where('profile', $profile)
                ->first();
            $profileAssigned->user_id = $user_id;

            //get profile
            $profile_modal = ProfileModel::where('code',$profileAssigned->profile)->first();
            //the users to swap
            $old_user_details = User::find($owner_id);
            $new_user_details = User::find($user_id);

            //update the jobs codes from phris


            //the columns to affect
            $code_column = $profile_modal->code_column ;
            $unit_column = $profile_modal->unit_column ;
            //make the update on config workflow
            if($code_column != "" && $unit_column != "" ){

//                $work_flow = ConfigWorkFlow::where($code_column,$old_user_details->job_code )
//                    ->where($unit_column,$old_user_details->user_unit_code )
//                    ->get();
//                dd($work_flow);

                //work-flow
                $work_flow = ConfigWorkFlow::where($code_column,$old_user_details->job_code )
                    ->where($unit_column,$old_user_details->user_unit_code )
                    ->update([
                        $code_column => $new_user_details->job_code,
                        $unit_column => $new_user_details->user_unit_code
                    ]);
                //save the assignment
                $profileAssigned->save();

                //log the activity
                ActivityLogsController::store($request, "Profile Transfer", "update", $old_user_details->name." ".$key ." profiles have been transferred to ".$new_user_details->name, json_encode($old_user_details->id));
                //return
                return Redirect::back()->with('message', 'Successfully Transferred the selected profile');
            }
            else{
                //return
                return Redirect::back()->with('error', 'Profile Transfer failed because code_column and unit_column are empty for'.$profile_modal->code) ;
            }



        }


    }

    // PROFILE REMOVE
    public function remove()
    {
        //get all the categories
        $eforms = EFormModel::all();
        $users = User::orderBy('name')->get();
        $mine = Auth::user();
        //
        $profiles = ProfileAssigmentModel::orderBy('id')->get();
        $profiles->load('profiles', 'form');

        return view('main.profile.remove')->with(compact('users', 'eforms', 'mine', 'profiles'));
    }

    public function removeCreate(Request $request)
    {
        $owner_id = $request->owner_id;
        $key = 0 ;

        if($request->remove_profiles == null ){
            return Redirect::back()->with('error', 'no profiles selected to remove' );
        }

        foreach ($request->remove_profiles as $d) {
            $key ++ ;
            $obj = json_decode($d);
            $eform_id = $obj->form;
            $profile = $obj->profiles;
            $profileAssigned = ProfileAssigmentModel::where('user_id', $owner_id)
                ->where('eform_id', $eform_id)
                ->where('profile', $profile)
                ->first();



            //delete
            $model = ProfileAssigmentModel::destroy($profileAssigned->id);

            //get profile
            $profile_modal = ProfileModel::where('code',$profile)->first();
            $old_user_details = User::find($owner_id);

            $code_column = $profile_modal->code_column ;
            $unit_column = $profile_modal->unit_column ;

            if($code_column != "" && $unit_column != "" ){
                //work-flow
//                $work_flow = ConfigWorkFlow::where($profile_modal->code_column,$old_user_details->job_code )
//                    ->where($profile_modal->unit_column,$old_user_details->user_unit_code )
//                    ->update([
//                        $code_column => "",
//                        $unit_column => ""
//                    ]);
            }

        }

        //log the activity
        ActivityLogsController::store($request, "Profile Removed", "delete", $old_user_details->name." ".$key ." profiles have been deleted ", json_encode($old_user_details->id));
        //return
        return Redirect::back()->with('message', 'Successfully Deleted the selected profile');
    }

}
