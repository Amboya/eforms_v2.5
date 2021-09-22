<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DepartmentModel;
use App\Models\Main\UserUnitModel;
use App\Models\Main\UserUnitSpmsSyncModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ConfigWorkFlowController extends Controller
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
    public static function syncFromConfigUserUnits()
    {

        //select from config user unit
        $list = UserUnitSpmsSyncModel::orderBy('id')->get();


        //loop through them and make an insert into the new
        foreach ($list as $item) {
            // make an insert into the new
           // dd($item);

            // "user_unit_code" => "D3132"
            //    "user_unit_description" => "CONSTRUCTION & SPECIAL PROJECTS - HUMAN RESOURCES "
            //    "user_unit_superior" => "D3000"
            //    "user_unit_bc_code" => "13610"
            //    "user_unit_cc_code" => "14320"
            //    "user_unit_status" => "00"

            $model = ConfigWorkFlow::updateOrCreate(
                [
                    'user_unit_code' => $item->user_unit_code,
                    'user_unit_description' => $item->user_unit_description,
                    'user_unit_superior' => $item->user_unit_superior,
                    'user_unit_bc_code' => $item->user_unit_bc_code,
                    'user_unit_cc_code' => $item->user_unit_cc_code, // user_unit_active
                ],
                [
                    "dr_code" => $item->dr_code,
                    "dr_unit" => $item->dr_unit,
                    "dm_code" => $item->dm_code,
                    "dm_unit" => $item->dm_unit,
                    "hod_code" => $item->hod_code,
                    "hod_unit" => $item->hod_unit,
                    "arm_code" => $item->arm_code,
                    "arm_unit" => $item->arm_unit,
                    "bm_code" => $item->bm_code,
                    "bm_unit" => $item->bm_unit,
                    "ca_code" => $item->ca_code,
                    "ca_unit" => $item->ca_unit,
                    "ma_code" => $item->ma_code,
                    "ma_unit" => $item->ma_unit,
                    "psa_code" => $item->psa_code,
                    "psa_unit" => $item->psa_unit,
                    "hrm_code" => $item->hrm_code,
                    "hrm_unit" => $item->hrm_unit,
                    "phro_code" => $item->phro_code,
                    "phro_unit" => $item->phro_unit,
                    "shro_unit" => $item->shro_unit,
                    "shro_code" => $item->shro_code,
                    "audit_code" => $item->audit_code,
                    "audit_unit" => $item->audit_unit,
                    "expenditure_code" => $item->expenditure_code,
                    "expenditure_unit" => $item->expenditure_unit,
                    "payroll_code" => $item->payroll_code,
                    "payroll_unit" => $item->payroll_unit,
                    "security_code" => $item->security_code,
                    "security_unit" => $item->security_unit,
                    "transport_code" => $item->transport_code,
                    "transport_unit" => $item->transport_unit,
                    "sheq_code" => $item->sheq_code,
                    "sheq_unit" => $item->sheq_unit,
                ]
            );


        }

        // dd($model);

        //return back
        return Redirect::route('main-home')->with('message', 'User Units have been Synced successfully');
    }

    public static function sync1111()
    {

        $user = Auth::user();
        $id = $user->id ?? 1;

        //highest level
        $user_units_from_spms1 = UserUnitSpmsSyncModel::where('cc_code', null)
            ->where('code_unit_superior', null)
            ->where('status', config('constants.user_unit_active'))
            ->paginate(10);

        //get positions from phris
        $user_units_from_spms3 = UserUnitSpmsSyncModel::where('cc_code', null)
            ->where('status', config('constants.user_unit_active'))
            ->paginate(10);

        dd($user_units_from_spms3);

//        foreach ($user_units_from_spms3 as $item){
//
//            //get positions from phris
//            $user_units_from_spms2 = UserUnitSpmsSyncModel::where('code_unit', $item->code_unit_superior)
//               // ->where('status', config('constants.user_unit_active'))
//                ->get()->first();
//            $array[] = $user_units_from_spms2 ;
//        }

        dd($array);

//        $user_units_from_spms = UserUnitSpmsSyncModel::where('code_unit', 'D3000')
//            ->where('status', config('constants.user_unit_active'))
//            ->get();

        dd($user_units_from_spms);

        foreach ($user_units_from_spms as $key => $item) {

            $find_model = UserUnitModel::where('code', $item->code_unit)
                ->where('business_unit_code', $item->bu_code)
                ->where('cost_center_code', $item->cc_code)
                ->where('code_unit_superior', $item->code_unit_superior);

            //check if the model exits
            if ($find_model->exists()) {

            } else {
                try {
                    //create the user unit
                    $model = UserUnitModel::create(
                        [
                            'name' => $item->description ?? "null",
                            'code' => $item->code_unit ?? "0",
                            'business_unit_code' => $item->bu_code ?? "0",
                            'cost_center_code' => $item->cc_code ?? "0",
                            'code_unit_superior' => $item->code_unit_superior ?? "0",
                            'status' => $item->status ?? "0",
                            'created_by' => $id
                        ]);
                } catch (\Exception $exception) {

                }
            }

        }
        //return back
        return Redirect::back()->with('message', 'User Units have been Synced successfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get all the categories
        $list = DepartmentModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
        ];

        return view('main.division.userunit')->with($params);
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

        $model = DepartmentModel::find($request->user_unit_id);
        $model->name = $request->name;
        $model->code = $request->code;
        $model->business_unit_code = $request->business_unit_code;
        $model->cost_center_code = $request->cost_center_code;
        $model->code_unit_superior = $request->code_unit_superior;
        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of User Unit", "update", " unit user updated", $model->id);
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Created successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $model = DepartmentModel::find($id);
        DepartmentModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request, "Deleting of User Unit ", "delete", " user unit deleted", json_encode($model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');

    }
}
