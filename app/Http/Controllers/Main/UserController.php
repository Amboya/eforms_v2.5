<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\DivisionsModel;
use App\Models\Main\EFormModel;
use App\Models\main\FunctionalUnitModel;
use App\Models\Main\GradesModel;
use App\Models\main\LocationModel;
use App\Models\main\PaypointModel;
use App\Models\Main\PositionModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\ProfilePermissionsModel;
use App\Models\Main\RegionsModel;
use App\Models\Main\UserTypeModel;
use App\Models\Main\UserUnitModel;
use App\Models\PhrisUserDetailsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = "All";
        $list = User::select('id','user_unit_id','con_st_code','positions_id','staff_no','user_unit_code','job_code','email','name','created_at','phone')
            ->get();
        return view('main.users.index')->with(compact('list', 'search'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get the user based on id
        $user = User::find($id);
        $user_types = UserTypeModel::all();
        $delegated_profiles = ProfileDelegatedModel::where('delegated_to', $user->id)
            ->where('config_status_id',  config('constants.active_state') )->get();
        $user_unit_new = ConfigWorkFlow::select('id', 'user_unit_description', 'user_unit_code', 'user_unit_bc_code', 'user_unit_cc_code')->orderBy('user_unit_code')->get();

        $responsible_units = HomeController::getUserResponsibleUnits($user);

        $profiles = ProfilePermissionsModel::all();
        $eforms = EFormModel::all();

        //return the view
        return view('main.users.show')->with(compact('responsible_units','profiles', 'eforms', 'user_unit_new', 'user', 'user_types', 'delegated_profiles'));
    }

    public function search(Request $request)
    {
        $search = $request->search;
        $list = User::select('id','user_unit_id','con_st_code','positions_id','staff_no','user_unit_code','job_code','email','name','created_at','phone')
           ->where('email', 'LIKE', "%{$search}%")
               ->orWhere('name', 'LIKE', "%{$search}%")
               ->orWhere('nrc', 'LIKE', "%{$search}%")
               ->orWhere('staff_no', 'LIKE', "%{$search}%")
               ->orWhere('user_unit_code', 'LIKE', "%{$search}%")
               ->orWhere('contract_type', 'LIKE', "%{$search}%")
               ->orWhere('con_st_code', 'LIKE', "%{$search}%")
            ->orWhere('job_code', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
               ->get();
        return view('main.users.index')->with(compact('list', 'search'));
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
    public function update(Request $request, $id)
    {
        $model = User::find($id);
        $model->name = $request->name;
        $model->email = $request->email;
        $model->phone = $request->phone;
        $model->type_id = $request->user_type_id;

//        $model->staff_no = $request->staff_no;
//        $model->user_unit_id = $request->user_unit_id;
//        $model->positions_id = $request->user_position_id;
//        $model->profile_id = $request->user_profile_id;
//        $model->user_division_id = $request->user_division_id;
//        $model->user_region_id = $request->user_region_id;
//        $model->user_directorate_id = $request->user_directorate_id;

        //get user unit and save
        $user_unit = ConfigWorkFlow::find($request->user_unit_new);
        $model->user_unit_id = $user_unit->id ;
        $model->user_unit_code = $user_unit->user_unit_code ;

        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of User", "update", " user updated", $model->staff_no);
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        User::destroy($id);
        //log the activity
        ActivityLogsController::store($request, "Deleting of User", "delete user", " user has been deleted from the system", $user->id );
//        return Redirect::route('main.user')->with('message', 'Details for ' . $user->name . ' have been Deleted successfully');

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updatePhoto(Request $request, $id)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('avatar');
        $user = User::find($id);

        if ($request->hasFile('avatar')) {

            $filenameWithExt = $file->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get size
            $size = number_format($file->getSize() * 0.000001, 2);
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
            // Upload File
            $path = $file->storeAs('public/user_avatar', $fileNameToStore);

//            //create an File record
//            $file = UploadedFiles::create(
//                [
//                    'filename' => $fileNameToStore,
//                    'extension' => $extension,
//                    'size' => $size,
//                    'path' => $path,
//                    'form_approval_model_id' => $user->id,
//                    'form_id' => $user->id,
//                    'form_ref' => $user->id,
//                    'company_id' => $user->company_id,
//                    'created_by_user_id' => Auth::user()->id,
//                ]);

            //update the image
            $user->avatar = $fileNameToStore;
            $user->save();

            return redirect()->back()->with('message', $user->name . '  Profile Picture has updated successfully!');
        }
        return redirect()->back()->with('error', 'File was Missing');


    }


    public function sync($id)
    {
        try {
            //get the user model
            $model = User::find($id);


            //get the phirs details for this user
            $phirs_user_details = PhrisUserDetailsModel::where('con_per_no', $model->staff_no)
               // ->where('con_st_code', config('constants.phris_user_active'))
                ->first();

            //get the details
            $directorate = DirectoratesModel::where('name', $phirs_user_details->directorate)->get()->first();
            $position = PositionModel::where('name', $phirs_user_details->job_title)->get()->first();
            $grade = GradesModel::where('name', $phirs_user_details->grade)->get()->first();
            $location = LocationModel::where('name', $phirs_user_details->location)->get()->first();
            $pay_point = PaypointModel::where('name', $phirs_user_details->pay_point)->get()->first();
            $functional_section = FunctionalUnitModel::where('name', $phirs_user_details->functional_section)->get()->first();
            $division = DivisionsModel::where('name', $phirs_user_details->pay_point)->get()->first();

            $user_unit = ConfigWorkFlow::where('user_unit_bc_code', $phirs_user_details->bu_code)
                ->where('user_unit_cc_code', $phirs_user_details->cc_code)
                ->get()->first();


            //update the model with the details from phris
            $model->name = $phirs_user_details->name;
            //  $model->email = $phirs_user_details->staff_email ?? $model->email;
            $model->nrc = $phirs_user_details->nrc;
            $model->contract_type = $phirs_user_details->contract_type;
            $model->con_st_code = $phirs_user_details->con_st_code;
            $model->con_wef_date = $phirs_user_details->con_wef_date;
            $model->con_wet_date = $phirs_user_details->con_wet_date;
            $model->job_code = $phirs_user_details->job_code;
            $model->grade_id = $grade->id;
            $model->positions_id = $position->id;
            $model->location_id = $location->id;
            $model->user_division_id = $division->id;
            $model->pay_point_id = $pay_point->id;
            $model->user_directorate_id = $directorate->id;
            $model->functional_unit_id = $functional_section->id;
            $model->user_unit_id = $user_unit->id;
            $model->user_unit_code = $user_unit->user_unit_code;
            //save
            $model->save();

            //return detains
            return redirect()->back()->with('message', 'User Details Updated Successfully');
        } catch (\Illuminate\Database\QueryException $exception) {
            // You can check get the details of the error using `errorInfo`:
            $errorInfo = $exception->errorInfo;
            return redirect()->back()->with('error', 'User Details Failed to Updated!. ERROR Message : ' . $exception->getMessage());
            // Return the response to the client..
        }
    }


    public function changePassword(Request $request){
        $user = \Auth::user();
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);
        if($request->password == $request->old_password){
            return redirect()->back()->withInput()->withErrors(['password' => "Sorry your old password is the same as the new one"]);
        }
        if($request->password == 'Zesco123' || $request->password == 'zesco123' || $request->password == 'zesco@123'||
            $request->password == 'Zesco@123' || $request->password == 'Zesco12345' || $request->password == 'zesco12345' ){
            return redirect()->back()->withInput()->withErrors(['password' => "Sorry your new password has been listed as too common hence not so much secure.Please change to another password."]);
        }
        if( $user->password == Hash::make($request->password) ){
            return redirect()->back()->withInput()->withErrors(['password' => "Sorry your old password you entered is wrong"]);
        }else{
            $user->password = Hash::make($request->password) ;
            $user->password_changed =   config('constants.password_changed')  ;
            $user->save();
            return redirect()->back()->with('message', 'User Password Updated Successfully');
        }

    }

    public function changeUnit(Request $request){
        //get user unit
        $user_unit = ConfigWorkFlow::find($request->user_unit);
        $model = \Auth::user();
        //  dd($model);
        $model->user_unit_id = $user_unit->id ;
        $model->user_unit_code = $user_unit->user_unit_code ;
        $model->save();
        //log the activity
        ActivityLogsController::store($request, "Updating of User's User-Unit", "update", " user's user-unit updated", $model->staff_no);
        return redirect()->back()->with('error', 'Thank you! your user-unit has been updated successfully.');

    }


}
