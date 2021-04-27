<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradesRequest;
use App\Models\Main\GradesCategoryModel;
use App\Models\Main\GradesModel;
use App\Models\PhrisUserDetailsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class GradesController extends Controller
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
        //get all the grades
        $list = GradesModel::all();
        $categories = GradesCategoryModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
            'categories' => $categories,
        ];

        //return with the data
        return view('main.grades.index')->with($params);
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
    public function store(GradesRequest $request)
    {
        $user = Auth::user();
        $model = GradesModel::firstOrCreate(
            [
                'name' => $request->name,
                'category_id' => $request->category_id,
            ],
            [
                'name' =>  $request->name,
                'category_id' =>  $request->category_id,
                'sub_rate'=>  $request->sub_rate,
                'kilometer_allowance_rate'=>  $request->kilometer_allowance_rate,
                'created_by'=> $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of Grade","update", " grade created", json_encode( $model));
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
        $model = GradesModel::find($request->grade_id);
        $model->category_id = $request->category_id ;
        $model->sub_rate = $request->sub_rate ;
        $model->kilometer_allowance_rate = $request->kilometer_allowance_rate ;
        $model->name = $request->name ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request,"Updating of Grade","update", " grade updated", " ");
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
        $model = GradesModel::find($id);
        GradesModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request,"Deleting of Grade ","delete", " grade deleted", json_encode( $model));
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
        $phirs_model = PhrisUserDetailsModel::select( 'grade', 'group_type')->groupBy('grade', 'group_type' )->get();

        foreach ($phirs_model as $item){
            //get the category
            $grade_category = GradesCategoryModel::where('name',$item->group_type )->first();

         //   dd($grade_category->id);

           //create the grade
            $model = GradesModel::firstOrCreate(
                [
                    'name' => $item->grade ?? "none",
                    'category_id' => $grade_category->id ?? 1
                ],
                [
                    'name' => $item->grade ?? "none" ,
                    'category_id' => $grade_category->id ?? 1  ,
                    'created_by' =>  $id ,
                ]);
        }
        //return back
        return Redirect::back()->with('message', 'Grades have been Synced successfully');
    }


}
