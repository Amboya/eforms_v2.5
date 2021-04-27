<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\ProfilePermissionsModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\EFormModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfilePermissionsController extends Controller
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
        $list = ProfilePermissionsModel::all();
        $profiles = ProfileModel::all();
        $eforms = EFormModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
            'profiles' => $profiles,
            'eforms' => $eforms,
        ];

        return view('main.profile.permissions')->with($params);
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
    public function store(Request $request)
    {
        $user = Auth::user();
        $model = ProfilePermissionsModel::firstOrCreate(
            [
                'eform_id' => $request->eform_id,
                'profile' => $request->profile,
                'profile_next' => $request->profile_next,
            ],

            [
                'eform_id' => $request->eform_id,
                'profile' => $request->profile,
                'created_by' => $user->id,
                'profile_next' => $request->profile_next,
            ]);

        //log the activity
        ActivityLogsController::store($request, "Creating of System Profile Permission", "update", " system profile permission created", json_encode($model));

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
        $model = ProfilePermissionsModel::find($request->id);

        $model->eform_id = $request->eform_id;
        $model->profile = $request->profile;
        $model->profile_next = $request->profile_next;
        $model->save();

        //log the activity
        ActivityLogsController::store($request, "Updating of Profile Permission", "update", " profile permission updated",$model->id);
        return Redirect::back()->with('message', 'Details for ' . $model->profile . ' have been Created successfully');

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $model = ProfilePermissionsModel::find($id);
        ProfilePermissionsModel::destroy($id);
        //log the activity
        ActivityLogsController::store($request, "Deleting of Profile Permission ", "delete", " profile permission deleted", $model->id);
        return Redirect::back()->with('message', 'Details for ' . $model->profile . ' have been Deleted successfully');

    }

}
