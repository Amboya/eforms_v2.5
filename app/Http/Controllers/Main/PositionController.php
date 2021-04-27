<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use App\Models\Main\PositionModel;
use App\Models\Main\UserUnitModel;
use App\Models\PhrisUserDetailsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PositionController extends Controller
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
        //get all the categories
        $list = PositionModel::all();
        $user_units = UserUnitModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
            'user_units' => $user_units,
        ];

        return view('main.position.index')->with($params);
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
    public function store(PositionRequest $request)
    {

        $user = Auth::user();
        $model = PositionModel::firstOrCreate(
            [
                'name' => $request->name,
                'code' => $request->code,
                'superior_name' => $request->superior_name,
                'superior_code' => $request->superior_code,
                'user_unit_id' => $request->user_unit,
            ],

            [
                'name' => $request->name,
                'code' => $request->code,
                'superior_name' => $request->superior_name,
                'superior_code' => $request->superior_code,
                'user_unit_id' => $request->user_unit,
                'created_by' => $user->id,
            ]);


        //log the activity
        ActivityLogsController::store($request, "Creating of System Position", "update", " system position created", json_encode($model));

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
    public function update(PositionRequest $request)
    {
        $model = PositionModel::find($request->position_id);

        $model->name = $request->name;
        $model->code = $request->code;
        $model->superior_name = $request->superior_name;
        $model->superior_code = $request->superior_code;
        $model->user_unit_id = $request->user_unit;
        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of User Unit", "update", " unit user updated", json_encode($model));
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
        $model = PositionModel::find($id);
        PositionModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request, "Deleting of Position ", "delete", " position deleted", json_encode($model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');

    }

    /**
     * @return $this
     */
    public static function sync()
    {
        $user = Auth::user();
        $id = $user->id ?? 1 ;
        //get positions from phris
        $phirs_job_titles = PhrisUserDetailsModel::select('job_title')->groupBy('job_title')->get();

        foreach ($phirs_job_titles as $key => $item) {

            // Delimit by multiple spaces, hyphen, underscore, comma
            $words = preg_split("/[\s,_-]+/", $item->job_title ?? "None");
            $acronym = "";
            foreach ($words as $w) {
                try {
                    $acronym .= $w[0];
                } catch (\Exception $exception) {
                    $acronym = $w;
                }
            }

            //create the grade
            $model = PositionModel::firstOrCreate(
                [
                    'name' => $item->job_title,
                ],
                [
                    'name' => $item->job_title,
                    'code' => $acronym . "-" . ++$key,
                    'created_by' =>  $id ,
                ]
            );
        }
        //return back
        return Redirect::back()->with('message', 'Positions have been Synced successfully');
    }




}
