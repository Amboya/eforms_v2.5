<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Http\Requests\StatusRequest;
use App\Models\Main\EFormModel;
use App\Models\Main\StatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class StatusController extends Controller
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
        $list = StatusModel::orderBy('eform_id')->get();
        $forms = EFormModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
            'forms' => $forms,
        ];

        //get all status
        return view('main.status.index')->with($params);
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
    public function store(StatusRequest $request)
    {

        //get the form code
        if ($request->eform_id) {

        }
        $user = Auth::user();

        $model = StatusModel::firstOrCreate(
            [
                'name' => $request->name,
                'status' => $request->status,
                'eform_id' => $request->eform_id,
            ],
            [
                'name' => $request->name,
                'other' => $request->other_name,
                'description' => $request->description,
                'status' => $request->status,
                'status_next' => $request->next,
                'status_failed' => $request->fail,
                'html' => $request->html,
                'percentage' => $request->percentage,
                'eform_code' => $request->name,
                'eform_id' => $request->eform_id,
                'created_by' => $user->id,
            ]);

        //log the activity
        ActivityLogsController::store($request, "Creating of System Status", "update", " system status created", $model->id);

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
    public function update(StatusRequest $request)
    {
        $model = StatusModel::find($request->form_id);
        $model->name = $request->name;
        $model->other = $request->other_name;
        $model->html = $request->html;
        $model->percentage = $request->percentage;
        $model->description = $request->description;
        $model->status = $request->status;
        $model->status_next = $request->next;
        $model->eform_id = $request->eform_id;
        $model->status_failed = $request->fail;
        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of System Status", "update", " system status updated", $model->id);
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $model = StatusModel::find($id);
        StatusModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request, "Deleting of System Status ", "delete", " system status deleted", $model->id);
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');
    }
}
