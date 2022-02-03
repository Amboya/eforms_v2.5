<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\FunctionalUnitRequest;
use App\Models\Main\FunctionalUnitModel;
use App\Models\Main\UserUnitModel;
use App\Models\PhrisUserDetailsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class FunctionalUnitController extends Controller
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
        //get all the Functional Unit
        $list = FunctionalUnitModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
        ];

        //return with the data
        return view('main.functional_unit.index')->with($params);
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
    public function store(FunctionalUnitRequest $request)
    {


        $user = Auth::user();
        $model = FunctionalUnitModel::firstOrCreate(
            [
                'name' => $request->name,
                'code' => $request->code,
            ],
            [
                'name' =>  $request->name,
                'code' =>  $request->code,
                'user_unit_id'=>  $request->user_unit_id,
                'created_by'=> $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of Functional Unit","update", " functional unit created", json_encode( $model));
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
    public function update(FunctionalUnitRequest $request)
    {
        $model = FunctionalUnitModel::find($request->directorate_id);
        $model->name = $request->name ;
        $model->code = $request->code ;
        $model->user_unit_id = $request->user_unit_id ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request,"Updating of Functional Unit","update", " functional unit updated", json_encode( $model));
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
        $model = FunctionalUnitModel::find($id);
        FunctionalUnitModel::destroy($id);

        //log the activity
        ActivityLogsController::store($request,"Deleting of Functional Unit ","delete", " functional unit deleted", json_encode( $model));
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
        $phirs_models = PhrisUserDetailsModel::select('functional_section')->groupBy('functional_section')->get();

        foreach ($phirs_models as $key => $item) {

            // Delimit by multiple spaces, hyphen, underscore, comma
            $words = preg_split("/[\s,_-]+/", $item->functional_section ?? "None");
            $acronym = "";
            foreach ($words as $w) {
                try {
                    $acronym .= $w[0];
                } catch (\Exception $exception) {
                    $acronym = $w;
                }
            }

            //create the grade
            $model = FunctionalUnitModel::firstOrCreate(
                [
                    'name' => $item->functional_section,
                ],
                [
                    'name' => $item->functional_section,
                    'code' => $acronym ,
                    'created_by' =>  $id,
                ]
            );
        }
        //return back
        return Redirect::back()->with('message', 'Functional Unit have been Synced successfully');
    }


}
