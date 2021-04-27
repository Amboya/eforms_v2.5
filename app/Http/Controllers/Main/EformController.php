<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\EformRequest;
use App\Models\Main\EFormCategoryModel;
use App\Models\Main\EFormModel;
use App\Models\Main\StatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EformController extends Controller
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
        $list = EFormModel::all();
        $categories = EFormCategoryModel::all();
        $status = StatusModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
            'categories' => $categories,
            'status' => $status,

        ];

        //return with the data
        return view('main.eform.index')->with($params);
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
    public function store(EformRequest $request)
    {
        $user = Auth::user();

        //generate the code from the name
        // Delimit by multiple spaces, hyphen, underscore, comma
        $words = preg_split("/[\s,_-]+/",  $request->name ?? "None");
        $acronym = "";
        foreach ($words as $w) {
            try {
                $acronym .= $w[0];
            } catch (\Exception $exception) {
                $acronym = $w;
            }
        }
        $acronym = strtoupper($acronym);

        $model = EFormModel::firstOrCreate(
            [
                'name' => $request->name,
                'code' => $acronym."-EFORM",
            ],
            [
                'name' =>  $request->name,
                'code' => $acronym."-EFORM",
                'test_url' => $request->test_url,
                'icon' => $request->icon,
                'production_url' => $request->production_url,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'total_new' => 0,
                'total_pending' => 0,
                'total_closed' => 0,
                'total_rejected' => 0,
                'status_id' => $request->status_id,
                'created_by' => $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of eform item","update", " eform created", $model->name);

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

        $model = EFormModel::find($request->form_id);
        $model->name = $request->name ;
        $model->code = $request->code ;
        $model->icon = $request->icon ;
        $model->description = $request->description ;
        $model->test_url = $request->test_url ;
        $model->production_url = $request->production_url ;
        $model->category_id = $request->category_id ;
        $model->status_id = $request->status_id ;
        $model->save() ;
        //log the activity
        ActivityLogsController::store($request,"Updating of eform item","update", " eform updated", $model->name);
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $model = EFormModel::find($id);
        EFormModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request,"Deleting of eform item","delete", " eform deleted", $model->name );
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');

    }
}
