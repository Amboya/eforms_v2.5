<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\DirectoratesRequest;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\Sync\OrganogramSync;
use App\Models\Main\UserUnitModel;
use App\Models\PhrisUserDetailsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class DirectoratesController extends Controller
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
        $list = DirectoratesModel::all();

        $user_units = ConfigWorkFlow::select('id', 'user_unit_description')->get();

        //data to send to the view
        $params = [
            'list' => $list,
            'user_units' => $user_units,
        ];

        //return with the data
        return view('main.directorate.index')->with($params);
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
    public function store(DirectoratesRequest $request)
    {


        $user = Auth::user();
        $model = DirectoratesModel::firstOrCreate(
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
        ActivityLogsController::store($request,"Creating of Directorate","update", " directorate created", json_encode( $model));
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
    public function update(DirectoratesRequest $request)
    {
        $model = DirectoratesModel::find($request->directorate_id);
        $model->name = $request->name ;
        $model->code = $request->code ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request,"Updating of Directorate","update", " directorate updated", json_encode( $model));
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
        $model = DirectoratesModel::find($id);
        DirectoratesModel::destroy($id);

        //log the activity
        ActivityLogsController::store($request,"Deleting of Directorate ","delete", " directorate deleted", json_encode( $model));
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
        $phirs_models = PhrisUserDetailsModel::select('directorate')->groupBy('directorate')->get();

        foreach ($phirs_models as $key => $item) {

            // Delimit by multiple spaces, hyphen, underscore, comma
            $words = preg_split("/[\s,_-]+/", $item->directorate ?? "None");
            $acronym = "";
            foreach ($words as $w) {
                try {
                    $acronym .= $w[0];
                } catch (\Exception $exception) {
                    $acronym = $w;
                }
            }

            //create the grade
            $model = DirectoratesModel::firstOrCreate(
                [
                    'name' => $item->directorate,
                ],
                [
                    'name' => $item->directorate,
                    'code' => $acronym ,
                    'created_by' =>   $id ,
                ]
            );
        }
        //return back
        return Redirect::back()->with('message', 'Directorates have been Synced successfully');
    }


    public static function syncOrganoGram(){

        $organogramList = OrganogramSync::select('level_1')->groupBy('level_1')->get();

        if(sizeof($organogramList) > 0){
            //update everything first
            $affected_form = DB::table('config_directorate')
                ->update(['status_id'  => 0 ]);
        }


        foreach ($organogramList as $organogram){
            // Delimit by multiple spaces, hyphen, underscore, comma
            $words = preg_split("/[\s,_-]+/", $organogram->level_1 ?? "None");
            $acronym = "";
            foreach ($words as $w) {
                try {
                    $acronym .= $w[0];
                } catch (\Exception $exception) {
                    $acronym = $w;
                }
            }
            //create the grade
            $model = DirectoratesModel::UpdateOrCreate(
                [
                    'name' => $organogram->level_1,
                ],
                [
                    'name' => $organogram->level_1,
                    'code' => $acronym ,
                    'status_id' => 1 ,
                    'created_by' =>   auth()->user()->id ,
                ]
            );

        }
        //return back
        return Redirect::back()->with('message', 'Directorates have been Synced successfully');
    }

}
