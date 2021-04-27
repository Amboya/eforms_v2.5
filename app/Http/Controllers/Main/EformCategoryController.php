<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\EformCategoryRequest;
use App\Models\Main\EFormCategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EformCategoryController extends Controller
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
        //get all the categories
        $list = EFormCategoryModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
        ];

        //return with the data
        return view('main.category.index_eform')->with($params);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(EformCategoryRequest $request)
    {
        $name = $request->name;
        $user = Auth::user();

        $model = EFormCategoryModel::firstOrCreate(
            [
                'name' => $name
            ],
            [
                'name' => $name,
                'created_by' => $user->id,
            ]);


        //log the activity
        ActivityLogsController::store($request,"Creating of eform Category item","create", "new eform category created", json_encode( $request->all()));

        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EformCategoryRequest $request)
    {
        $model = EFormCategoryModel::find($request->category_id);
        $model->name = $request->name;
        $model->save();
        //log the activity
        ActivityLogsController::store($request,"Updating of eform Category item","update", " eform category updated", json_encode( $request->all()));

        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $model = EFormCategoryModel::find($id);
        EFormCategoryModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request,"Deleting of eform Category item","delete", $model->name." deleted", json_encode( $model));

        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');
    }
}
