<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartmentRequest;
use App\Models\Main\DepartmentModel;
use App\Models\Main\UserUnitSpmsSyncModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DepartmentController extends Controller
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

        $user = Auth::user();
        $id = $user->id ?? 1;
        //get the department from phris
        $departments_from_spms = UserUnitSpmsSyncModel::where('status', config('constants.user_unit_active'))
            ->whereNotNull('bu_code')
            ->whereNotNull('cc_code')
            ->get();

        foreach ($departments_from_spms as $key => $item) {
            $find_model = DepartmentModel::where('code', $item->code_unit)
                ->where('business_unit_code', $item->bu_code)
                ->where('cost_center_code', $item->cc_code)
                ->where('code_unit_superior', $item->code_unit_superior);
            $path = $item->description;
            try {
                //get its superior LEVEL 1  - region
                $superior_model_level_1 = UserUnitSpmsSyncModel::where('status', config('constants.user_unit_active'))
                    ->where('code_unit', $item->code_unit_superior)
                    ->first();
                // insert level one
                //  dd($superior_model_level_1);
                $path = $path . " => " . $superior_model_level_1->description;
                try {
                    //get its superior LEVEL 2  - division
                    $superior_model_level_2 = UserUnitSpmsSyncModel::where('status', config('constants.user_unit_active'))
                        ->where('code_unit', $superior_model_level_1->code_unit_superior)
                        ->first();
                    $path = $path . " => " . $superior_model_level_2->description;
                    // dd($superior_model_level_2);
                    try {
                        //get its superior LEVEL 3  - Directorate
                        $superior_model_level_3 = UserUnitSpmsSyncModel::where('status', config('constants.user_unit_active'))
                            ->where('code_unit', $superior_model_level_2->code_unit_superior)
                            ->first();
                        $path = $path . " => " . $superior_model_level_3->description;
                        // $str = "<br> ".$path ;
                        $str = "<br> " . $path;
                    } catch (\Exception $exception) {
                        $str = "<br> " . $path . "  level 3 ";
                    }
                } catch (\Exception $exception) {
                    $str = "<br> " . $path . "  level 2 ";
                }
            } catch (\Exception $exception) {
                $str = "<br> " . $path . "  level 1 ";
            }

            var_dump($str);

//            //check if the model exits
//            if ($find_model->exists()) {
//
//                $model = $find_model->get()->first();
//                //update
//                $model->name = $item->description ?? "null";
//                $model->code = $item->code_unit ?? "0";
//                $model->business_unit_code = $item->bu_code ?? "0";
//                $model->cost_center_code = $item->cc_code ?? "0";
//                $model->code_unit_superior = $item->code_unit_superior ?? "0";
//                $model->status = $item->status ?? "0";
//                $model->save();
//
//            } else {
//                try {
//                    //create the user unit
//                    $find_model = DepartmentModel::create(
//                        [
//                            'name' => $item->description ?? "null",
//                            'code' => $item->code_unit ?? "0",
//                            'business_unit_code' => $item->bu_code ?? "0",
//                            'cost_center_code' => $item->cc_code ?? "0",
//                            'code_unit_superior' => $item->code_unit_superior ?? "0",
//                            'status' => $item->status ?? "0",
//                            'created_by' => $id
//                        ]);
//
//
//                } catch (\Exception $exception) {
//
//                }
//            }

        }

        dd($superior_model_level_3);

        //return back
        return Redirect::back()->with('message', 'User Units Departments have been Synced successfully');
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

        return view('main.department.index')->with($params);
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
