<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\TaxModel;
use App\Models\Main\UserUnitModel;
use App\Models\PhrisUserDetailsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class TaxController extends Controller
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
        //get all the Tax
        $list = TaxModel::all();

        //data to send to the view
        $params = [
            'list' => $list,
        ];

        //return with the data
        return view('main.tax.index')->with($params);
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
    public function store(Request $request)
    {


        $user = Auth::user();
        $model = TaxModel::firstOrCreate(
            [
                'name' => $request->name,
                'tax' => $request->tax,
                'business_unit' => $request->business_unit,
                'cost_center' => $request->cost_center,
                'account_code' => $request->account_code,
            ],
            [
                'name' => $request->name,
                'tax' => $request->tax,
                'business_unit' => $request->business_unit,
                'cost_center' => $request->cost_center,
                'account_code' => $request->account_code,
            ]);

        //log the activity
        ActivityLogsController::store($request,"Creating of tax","update", " tax created", json_encode( $model));
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
        $model = TaxModel::find($request->directorate_id);
        $model->name = $request->name ;
        $model->tax = $request->tax ;
        $model->business_unit = $request->business_unit ;
        $model->cost_center = $request->cost_center ;
        $model->account_code = $request->account_code ;
        $model->save();

        //log the activity
        ActivityLogsController::store($request,"Updating of Tax","update", " tax updated", json_encode( $model));
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
        $model = TaxModel::find($id);
        TaxModel::destroy($id);

        //log the activity
        ActivityLogsController::store($request,"Deleting of Tax ","delete", " tax deleted", json_encode( $model));
        return Redirect::back()->with('message', 'Details for ' . $model->name . ' have been Deleted successfully');

    }


    /**
     * @return $this
     */
    public static function sync()
    {
        //return back
        return Redirect::back()->with('message', 'Tax have been Synced successfully');
    }


}
