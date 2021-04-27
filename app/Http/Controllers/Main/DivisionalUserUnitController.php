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

class DivisionalUserUnitController extends Controller
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

        //select from config user unit
        $list = UserUnitModel::all();

        //loop through them and make an insert into the new
        foreach ($list as $item){
            // make an insert into the new

            $model = ConfigWorkFlow::updateOrCreate(
                [
                    'user_unit_id' => $item->id ,
                    'user_unit_code' => $item->code ,
                    'user_unit_description' => $item->name ,
                    'user_unit_superior' => $item->code_unit_superior ,
                    'user_unit_bc_code' => $item->business_unit_code ,
                    'user_unit_cc_code' =>$item->cost_center_code   ,
                    'user_unit_status' => config('constants.user_unit_active'),
                ],
                [
                    'user_unit_id' => $item->id ,
                    'user_unit_code' => $item->code ,
                    'user_unit_description' => $item->name ,
                    'user_unit_superior' => $item->code_unit_superior ,
                    'user_unit_bc_code' => $item->business_unit_code ,
                    'user_unit_cc_code' =>$item->cost_center_code   ,
                    'user_unit_status' => config('constants.user_unit_active'),
                ]
            );

        }

        //return back
        return Redirect::back()->with('message', 'User Units have been Synced successfully');
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
                try{
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
                }catch (\Exception $exception){

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
