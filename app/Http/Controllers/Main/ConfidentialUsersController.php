<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\GradesModel;
use App\Models\Main\PositionModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\TaxModel;
use App\Models\Main\UserTypeModel;
use App\Models\Main\UserUnitModel;
use App\Models\PhrisUserDetailsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ConfidentialUsersController extends Controller
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
        $list = User::where('type_id', 2 )->get();
        $grades = GradesModel::with('category')->get();
        $positions = PositionModel::all();
        $directorates = DirectoratesModel::get() ;
        $user_types = UserTypeModel::all();
        $user_unit_new = ConfigWorkFlow:: select('id', 'user_unit_description', 'user_unit_code', 'user_unit_bc_code', 'user_unit_cc_code')
            ->orderBy('user_unit_code')
            ->get();
        //return with the data
        return view('main.confidential_users.index')->with(compact('directorates','list', 'user_types', 'user_unit_new', 'positions', 'grades'));
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

       // dd($request->all() );

        // zescoOne

        //  "name" => "James Chigayo"
        //  "staff_no" => "23423"
        //  "email" => "nshubart@zesco.co.zm23"
        //  "phone" => "0989232323"
        //  "extension" => "0"
        //  "user_type_id" => "2"
        //  "position" => "1036"
        //  "grade" => "81"
        //  "user_unit_new" => "185"

       // $position = PositionModel::find($request->position);
        $position = PositionModel::find($request->position);
        $grade = GradesModel::find($request->grade);
        $user_unit = ConfigWorkFlow::find($request->user_unit_new);

        $model =    User::create([
            'name' => $request->name,
            'nrc' => $request->staff_no ,
            'contract_type' => "CONTRACT",
            'con_st_code' => "ACT",
            'con_wef_date' => "",
            'con_wet_date' => "" ,
            'job_code' => $position->code,
            'staff_no' => $request->staff_no ,
            'email' => $request->email ,
            'phone' => $request->phone ,
            'extension' => $request->extension ,
            'password' => Hash::make("zescoOne"),
            'profile_id' => 1 ,
            'type_id' =>  $request->user_type_id ,
            'password_changed' => config('constants.password_changed'),
            'grade_id' => $grade->id,
            'positions_id' => $position->id,
            'location_id' => 0 ,
            'user_division_id' => 0 ,
            'pay_point_id' =>  0 ,
            'user_directorate_id' => $request->directorate,
            'functional_unit_id' => $user_unit->id,
            'user_unit_id' => $user_unit->id,
            'user_unit_code'=> $user_unit->user_unit_code,
        ]);




        $user = Auth::user();
//        $model = TaxModel::firstOrCreate(
//            [
//                'name' => $request->name,
//                'tax' => $request->tax,
//                'business_unit' => $request->business_unit,
//                'cost_center' => $request->cost_center,
//                'account_code' => $request->account_code,
//            ],
//            [
//                'name' => $request->name,
//                'tax' => $request->tax,
//                'business_unit' => $request->business_unit,
//                'cost_center' => $request->cost_center,
//                'account_code' => $request->account_code,
//            ]);

        //log the activity
     //   ActivityLogsController::store($request,"Creating of tax","update", " tax created", json_encode( $model));
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
