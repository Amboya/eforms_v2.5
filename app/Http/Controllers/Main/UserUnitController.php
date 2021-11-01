<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUnitRequest;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\UserUnitModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class UserUnitController extends Controller
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
     * @return $this
     */
    public static function sync()
    {
        //get positions from phris
        $user_units_from_spms = UserUnitModel::select('*')
            ->where('status', config('constants.user_unit_active'))
            ->get();

        foreach ($user_units_from_spms as $key => $item) {

            $model = ConfigWorkFlow::updateOrCreate(
                [
//                    'user_unit_description' => $item->description,
                    'user_unit_code' => $item->code_unit ?? '0',
//                    'user_unit_bc_code' => $item->bu_code ?? '0',
//                    'user_unit_cc_code' => $item->cc_code ?? '0',
                ],
                [
                    'user_unit_description' => $item->description ?? '0',
                    'user_unit_code' => $item->code_unit ?? '0',
                    'user_unit_bc_code' => $item->bu_code ?? '0',
                    'user_unit_cc_code' => $item->cc_code ?? '0',
                    'user_unit_status' => $item->status ?? '0',
                    'user_unit_superior' => $item->code_unit_superior ?? '0',
                ]);

            // dd($model);

        }
        //return back
        return Redirect::back()->with('message', 'User Units have been Synced successfully');
    }


    public function search(Request $request)
    {
        $search = $request->user_unit_code;

        $list = ConfigWorkFlow::where('user_unit_code', 'LIKE', "%{$search}%")
            ->orWhere('user_unit_description', 'LIKE', "%{$search}%")
            ->orWhere('user_unit_superior', 'LIKE', "%{$search}%")
            ->orWhere('user_unit_bc_code', 'LIKE', "%{$search}%")
            ->orWhere('user_unit_cc_code', 'LIKE', "%{$search}%")
            ->orWhere('user_unit_status', 'LIKE', "%{$search}%")
            ->get();


        //  $users = User::select('id', 'staff_no', 'name', 'user_unit_code', 'job_code' )->where('profile_id','!=', config('constants.user_profiles.EZESCO_002'))->orderBy('name')->get();
        $users = User::select('id', 'staff_no', 'name', 'user_unit_code', 'job_code')->whereNotNull('profile_id')->orderBy('name')->get();

        //data to send to the view
        return view('main.user_unit.index')->with(compact('list', 'users', 'search'));
    }

    public function searchId($id)
    {
        $search = $id;

        $list = ConfigWorkFlow::where('id', $id)
//            ->orWhere('user_unit_description', 'LIKE', "%{$search}%")
//            ->orWhere('user_unit_superior', 'LIKE', "%{$search}%")
//            ->orWhere('user_unit_bc_code', 'LIKE', "%{$search}%")
//            ->orWhere('user_unit_cc_code', 'LIKE', "%{$search}%")
//            ->orWhere('user_unit_status', 'LIKE', "%{$search}%")
            ->get();


        $users = User::select('id', 'staff_no', 'name', 'user_unit_code', 'job_code')->where('profile_id', '!=', config('constants.user_profiles.EZESCO_002'))->orderBy('name')->get();
        //data to send to the view
        return view('main.user_unit.index')->with(compact('list', 'users', 'search'));
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //get all the categories
        $list = ConfigWorkFlow::orderBy('user_unit_code')
            ->where('user_unit_status', config('constants.user_unit_active'))
            ->get();
        $search = 'All';
        $users = User::select('id', 'staff_no', 'name', 'user_unit_code', 'job_code')->whereNotNull('profile_id')->orderBy('name')->get();
        //data to send to the view
        return view('main.user_unit.index')->with(compact('list', 'users', 'search'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(UserUnitRequest $request)
    {
        $user = Auth::user();
        $model = UserUnitModel::firstOrCreate(
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
     * @return Response
     */
    public function show($id)
    {
        //get all the categories
        $list = ConfigWorkFlow::where('id', $id)
            ->where('user_unit_status', config('constants.user_unit_active'))
            ->get();
        $search = 'One';
        $users = User::select('id', 'staff_no', 'name', 'user_unit_code', 'job_code')->whereNotNull('profile_id')->orderBy('name')->get();

        //data to send to the view
        return view('main.user_unit.index')->with(compact('list', 'users', 'search'));

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
        $model = ConfigWorkFlow::find($request->workflow_id);

        $model->dr_code = $request->dr_code ?? $model->dr_code;
        $model->dr_unit = $request->dr_unit ?? $model->dr_unit;
        $model->dm_code = $request->dm_code ?? $model->dm_code;
        $model->dm_unit = $request->dm_unit ?? $model->dm_unit;
        $model->hod_code = $request->hod_code ?? $model->hod_code;
        $model->hod_unit = $request->hod_unit ?? $model->hod_unit;
        $model->arm_code = $request->arm_code ?? $model->arm_code;
        $model->arm_unit = $request->arm_unit ?? $model->arm_unit;
        $model->bm_code = $request->bm_code ?? $model->bm_code;
        $model->bm_unit = $request->bm_unit ?? $model->bm_unit;
        $model->ca_code = $request->ca_code ?? $model->ca_code;
        $model->ca_unit = $request->ca_unit ?? $model->ca_unit;
        $model->ma_code = $request->ma_code ?? $model->ma_code;
        $model->ma_unit = $request->ma_unit ?? $model->ma_unit;
        $model->psa_code = $request->psa_code ?? $model->psa_code;
        $model->psa_unit = $request->psa_unit ?? $model->psa_unit;
        $model->hrm_code = $request->hrm_code ?? $model->hrm_code;
        $model->hrm_unit = $request->hrm_unit ?? $model->hrm_unit;
        $model->phro_code = $request->phro_code ?? $model->phro_code;
        $model->phro_unit = $request->phro_unit ?? $model->phro_unit;
        $model->shro_code = $request->shro_code ?? $model->shro_code;
        $model->shro_unit = $request->shro_unit ?? $model->shro_unit;
        $model->audit_code = $request->audit_code ?? $model->audit_code;
        $model->audit_unit = $request->audit_unit ?? $model->audit_unit;
        $model->expenditure_code = $request->expenditure_code ?? $model->expenditure_code;
        $model->expenditure_unit = $request->expenditure_unit ?? $model->expenditure_unit;
        $model->payroll_code = $request->payroll_code ?? $model->payroll_code;
        $model->payroll_unit = $request->payroll_unit ?? $model->payroll_unit;
        $model->security_code = $request->security_code ?? $model->security_code;
        $model->security_unit = $request->security_unit ?? $model->security_unit;
        $model->transport_code = $request->transport_code ?? $model->transport_code;
        $model->transport_unit = $request->transport_unit ?? $model->transport_unit;
        $model->sheq_code = $request->sheq_code ?? $model->sheq_code;
        $model->sheq_unit = $request->sheq_unit ?? $model->sheq_unit;
        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of User Unit", "update", " unit user updated", $model->id);

        return redirect()->route('workflow.show', ['configWorkFlow' => $model])->with('message', 'Details for ' . $model->name . ' have been Created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        ConfigWorkFlow::destroy($request->workflow_id);
        return Redirect::back()->with('message', 'Details for ' . $request->name . ' have been Deleted successfully');

    }


    public function assign(Request $request)
    {

        if ($request->units == null) {
            return Redirect::back()->with('error', 'Yangutata!, Sorry,assignment failed to complete because no User-Units have been selected');
        }
        //get request user and profile
        $new_user_details = User::find($request->owner_id);
        //update the profiles
        $profiles = ProfileAssigmentModel::where('user_id', $new_user_details->id)->get();
        $profiles->load('profiles');

        foreach ($profiles as $profile) {
            $profile_modal = $profile->profiles;

            //the columns to affect
            $code_column = $profile_modal->code_column;
            $unit_column = $profile_modal->unit_column;

            //make the update on config workflow
            if ($code_column != "" && $unit_column != "") {
                //update the workflow
                $units = $request->units;
                foreach ($units as $unit) {
                    $work_flow = ConfigWorkFlow::find($unit);
                    $work_flow->
                    update([
                        $code_column => $new_user_details->job_code,
                        $unit_column => $new_user_details->user_unit_code
                    ]);
                }
//                $work_flow = ConfigWorkFlow::whereIn('id', $units)
//                    ->update([
//                        $code_column => $new_user_details->job_code,
//                        $unit_column => $new_user_details->user_unit_code
//                    ]);
            } else {
                //  return Redirect::back()->with('error', 'Assignment failed because profile unit_code and code_column are empty for the selected profile : '.$profile_modal->name);
            }
        }

        //log the activity
        ActivityLogsController::store($request, "User Units Assignment to a user", "user units assignment", " user units have been assigned to " . $new_user_details->name . " with the profile for " . $profile_modal->name, json_encode($profile_modal->id));
        //return
        return Redirect::back()->with('message', 'Profile has been Assigned successfully to ' . $new_user_details->name);

    }


}
