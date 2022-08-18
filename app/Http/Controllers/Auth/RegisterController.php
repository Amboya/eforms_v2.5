<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\DirectoratesController;
use App\Http\Controllers\Main\DivisionsController;
use App\Http\Controllers\main\FunctionalUnitController;
use App\Http\Controllers\Main\GradesController;
use App\Http\Controllers\main\LocationController;
use App\Http\Controllers\main\PayPointController;
use App\Http\Controllers\Main\PositionController;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\DivisionsModel;
use App\Models\main\FunctionalUnitModel;
use App\Models\Main\GradesCategoryModel;
use App\Models\Main\GradesModel;
use App\Models\main\LocationModel;
use App\Models\main\PaypointModel;
use App\Models\Main\PositionModel;
use App\Models\main\TotalsModel;
use App\Models\Main\UserUnitModel;
use App\Models\Main\UserUnitSpmsSyncModel;
use App\Models\PhrisUserDetailsModel;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'phone' => ['required', 'string', 'max:255'],
            'staff_no' => ['required', 'string', 'unique:users'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile_id' => ['required', 'integer'],
            'type_id' => ['required', 'integer'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */

    protected function create(array $data , $phris_view)
    {
        //[4] CREATE THE USER
        return User::UpdateOrCreate(
            [
                'email' => $phris_view->staff_email,
            ],
            [
                'name' => $phris_view->name ,
                'nrc' => $phris_view->nrc ,
                'contract_type' => "" ,
                'con_st_code' => "" ,
                'con_wef_date' => "" ,
                'con_wet_date' => "" ,
                'job_code' => "-" ,
                'staff_no' => $data['staff_no'],
                'email' => $phris_view->staff_email,
                'phone' => $phris_view->mobile_no,
                'extension' => $data['phone'],
                'password' => Hash::make($data['password']),
                'profile_id' => $data['profile_id'],
                'type_id' => $data['type_id'],
                'password_changed' => config('constants.password_changed'),
                'grade_id' => "0" ,
                'positions_id' => "0" ,
                'location_id' => "0" ,
                'user_division_id' => "0" ,
                'pay_point_id' => "0" ,
                'user_directorate_id' => 0,
                'functional_unit_id' => "0",
                'user_unit_id' =>0,
                'user_unit_code'=> 0,
            ]);

    }


    protected function create1(array $data, $phirs_user_details, $grade, $position, $location, $division, $pay_point, $functional_section, $directorate, $user_unit)
    {
        $email = $phirs_user_details->staff_email ?? $data['email'] ;
        if($email == "payroll-admin@zesco.co.zm" ){
            $email  = $data['email'] ;
        }

        //[4] CREATE THE USER
        return User::create([
            'name' => $phirs_user_details->name,
            'nrc' => $phirs_user_details->nrc,
            'contract_type' => $phirs_user_details->contract_type,
            'con_st_code' => $phirs_user_details->con_st_code,
            'con_wef_date' => $phirs_user_details->con_wef_date,
            'con_wet_date' => $phirs_user_details->con_wet_date,
            'job_code' => $phirs_user_details->job_code,
            'staff_no' => $data['staff_no'],
            'email' => $email,
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'profile_id' => $data['profile_id'],
            'type_id' => $data['type_id'],
            'password_changed' => config('constants.password_changed'),
            'grade_id' => $grade->first()->id,
            'positions_id' => $position->first()->id,
            'location_id' => $location->first()->id,
            'user_division_id' => $division->first()->id,
            'pay_point_id' => $pay_point->first()->id,
            'user_directorate_id' => $directorate->first()->id,
            'functional_unit_id' => $functional_section->first()->id,
            'user_unit_id' => $user_unit->id,
            'user_unit_code'=> $user_unit->user_unit_code,
        ]);

    }



    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        $user_unit = ConfigWorkFlow::orderBy('user_unit_code')
            ->where('user_unit_status', config('constants.user_unit_active') )
            ->get();
        $params = [
            'user_unit' => $user_unit
        ];
        return view('auth.register')->with($params);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        //
        $phris_view = PhrisUserDetailsModel::where( 'con_per_no', $request->staff_no )->first();




        if( ($phris_view->name ?? "pica" ) == "pica"){
            return redirect()->back()->with('error', 'Sorry this man number ('.$request->staff_no.') does not exit in our records or its a confidential man no');
        }
        elseif( $phris_view->con_st_code  == "INA"   ){
            return redirect()->back()->with('error', 'Sorry this man number ('.$request->staff_no.') is inactive ');
        }
        elseif(   $phris_view->con_st_code  == "SUS"  ){
            return redirect()->back()->with('error', 'Sorry this man number ('.$request->staff_no.') is suspended ');
        }


        //validate password
        if($request->password == 'Zesco123' || $request->password == 'zesco123' || $request->password == 'zesco@123'||
            $request->password == 'Zesco@123' || $request->password == 'Zesco12345' || $request->password == 'zesco12345' ){
            return redirect()->back()->withInput()->withErrors(['password' => "Sorry your new password has been listed as too common hence not so much secure.Please change to another password."]);
        }


        //
        event(new Registered($user = $this->create($request->all(), $phris_view)));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }


    public function register1(Request $request)
    {

        //first validate the fields
        $this->validator($request->all())->validate();

        //validate password
        if($request->password == 'Zesco123' || $request->password == 'zesco123' || $request->password == 'zesco@123'||
            $request->password == 'Zesco@123' || $request->password == 'Zesco12345' || $request->password == 'zesco12345' ){
            return redirect()->back()->withInput()->withErrors(['password' => "Sorry your new password has been listed as too common hence not so much secure.Please change to another password."]);
        }

        //then get the user details in phris
        $phirs_user = PhrisUserDetailsModel::where('con_per_no', $request->staff_no)
            ->where('con_st_code', config('constants.phris_user_active'));

        //check if this user exits
        $exits = $phirs_user->exists();
        if ($exits) {
            //if this user exits, then get the details
            $phirs_user_details = $phirs_user->first();

            //[1] GET THE DETAILS FROM MODELS
            $directorate = DirectoratesModel::where('name', $phirs_user_details->directorate)->get();
            $position = PositionModel::where('name', $phirs_user_details->job_title)->get();
            $grade = GradesModel::where('name', $phirs_user_details->grade)->get();
            $location = LocationModel::where('name', $phirs_user_details->location)->get();
            $pay_point = PaypointModel::where('name', $phirs_user_details->pay_point)->get();
            $functional_section = FunctionalUnitModel::where('name', $phirs_user_details->functional_section)->get();
            $division = DivisionsModel::where('name', $phirs_user_details->pay_point)->get();

            //[2] Sync the phris details with the records in the tables from the following controllers
            $check_after_sync = false;
            if ($directorate->isEmpty()) {
                DirectoratesController::sync();
                $check_after_sync = true;
            }
            if ($position->isEmpty()) {
                PositionController::sync();
                $check_after_sync = true;
            }
            if ($grade->isEmpty()) {
                GradesController::sync();
                $check_after_sync = true;
            }
            if ($location->isEmpty()) {
                LocationController::sync();
                $check_after_sync = true;
            }
            if ($pay_point->isEmpty()) {
                PayPointController::sync();
                $check_after_sync = true;
            }
            if ($functional_section->isEmpty()) {
                FunctionalUnitController::sync();
                $check_after_sync = true;
            }
            if ($division->isEmpty()) {
                DivisionsController::sync();
                $check_after_sync = true;
            }

            //[3] try to get again after a sync
            if ($check_after_sync) {
                $directorate = DirectoratesModel::where('name', $phirs_user_details->directorate)->get();
                $position = PositionModel::where('name', $phirs_user_details->job_title)->get();
                $grade = GradesModel::where('name', $phirs_user_details->grade)->get();
                $location = LocationModel::where('name', $phirs_user_details->location)->get();
                $pay_point = PaypointModel::where('name', $phirs_user_details->pay_point)->get();
                $functional_section = FunctionalUnitModel::where('name', $phirs_user_details->functional_section)->get();
                $division = DivisionsModel::where('name', $phirs_user_details->pay_point)->get();

            }

            //[4] Get the department user units
            $user_unit = ConfigWorkFlow::find($request->user_unit) ;
            try{
                $user_unit->id ;
            }catch (\Exception $exception){
                return redirect()->back()->withInput()->withErrors(['user_unit' => "Sorry we could not register your account, please contact system admin."]);
            }

//            //[5] Get the department user units
//            $user_email= User::where('email', $request->email)->first() ;
//            try{
//                    $user_email->id ;
//            }catch (\Exception $exception){
//                return redirect()->back()->withInput()->withErrors(['email' =>
//                    "Ummmmmm, Sorry. The system could not register your account because your email is still reflecting as ".$phirs_user_details->staff_email." in PHRIS. -    Kindly contact PHRIS HelpDesk to get your ."]);
//            }
//
//
            // CREATE THE USER
            event(new Registered($user = $this->create($request->all(), $phirs_user_details, $grade, $position, $location, $division, $pay_point, $functional_section, $directorate, $user_unit)
            ));

            //login the user
            $this->guard()->login($user);

            if ($response = $this->registered($request, $user)) {
                return $response;
            }

            return $request->wantsJson()
                ? new JsonResponse([], 201)
                : redirect($this->redirectPath());
        } else {
            //user does not exit
            return redirect()->back()->withInput()->withErrors(['staff_no' => "Sorry Staff Number Does not Exits in PHRIS, or is Inactive"]);
        }

    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {



        /**  UPDATE THE DETAILS FROM PHRIS */

        $phris_view = PhrisUserDetailsModel::where( 'con_per_no', $user->staff_no)->first();

        //get the functional section
        $functional_section =  ConfigWorkFlow::where('user_unit_bc_code', $phris_view->bu_code )
            ->where('user_unit_cc_code', $phris_view->cc_code )
            ->first();

        //get the directorate
        $directorate = DirectoratesModel::where( 'name', $phris_view->directorate )->first();
        //get the location
        $location = LocationModel::where( 'name', $phris_view->location )->first();
        //get the grade
        $position = PositionModel::where( 'name', $phris_view->job_title )->first();
        //get the grade
        $grade = GradesModel::where( 'name', $phris_view->grade )->first();
        //get the grade cat
        $grade_cat = GradesCategoryModel::where( 'name', $phris_view->group_type )->first();

        //create directorate if null
        if(  ($directorate->id ?? 'none')  == 'none'  ){
            // Delimit by multiple spaces, hyphen, underscore, comma
            $words = preg_split("/[\s,_-]+/", $phris_view->directorate ?? "None");
            $acronym = "";
            foreach ($words as $w) {
                try {
                    $acronym .= $w[0];
                } catch (\Exception $exception) {
                    $acronym = $w;
                }
            }
            //create the grade
            $directorate = DirectoratesModel::firstOrCreate(
                [
                    'name' => $phris_view->directorate,
                ],
                [
                    'name' => $phris_view->directorate,
                    'code' => $acronym ,
                    'created_by' =>   auth()->user()->id ,
                ]
            );
        }

        //get division
        $division = DivisionsModel::where('name', $phris_view->pay_point)
            ->where('directorate_id', $directorate->id )->first();
//
//        dd(compact('directorate', 'division','location', 'position', 'grade', 'grade_cat') );

        //get division
        if(  ($division->id ?? 'none')  == 'none'  ) {
            $words = preg_split("/[\s,_-]+/", $phris_view->pay_point ?? "None");
            $acronym = "";
            foreach ($words as $w) {
                try {
                    $acronym .= $w[0];
                } catch (Exception $exception) {
                    $acronym = $w;
                }
            }

            //create the division
            $division = DivisionsModel::firstOrCreate(
                [
                    'name' => $phris_view->pay_point,
                    'code' => $acronym . "-" . $directorate->code,
                    'directorate_id' => $directorate->id,
                ],
                [
                    'name' => $phris_view->pay_point ,
                    'code' => $acronym . "-" . $directorate->code,
                    'directorate_id' => $directorate->id,
                    'created_by' =>   auth()->user()->id ,
                ]
            );
        }

        //create functional_section if null
        if(  ($functional_section->id ?? 'none')  == 'none'  ){
            //check from organisational units
            $org_units = UserUnitSpmsSyncModel::where('bu_code', $phris_view->bu_code)
                ->where('cc_code' ,$phris_view->cc_code )
                ->first();

            if(  ($org_units->id ?? 'none')  == 'none'  ){
                //create a functional section
                $functional_section =   ConfigWorkFlow::firstOrCreate(
                    [
                        'user_unit_bc_code' => $phris_view->bu_code,
                        'user_unit_cc_code' => $phris_view->cc_code,
                    ],
                    [
                        'user_unit_code' => $phris_view->bu_code."".$phris_view->cc_code,
                        'user_unit_superior' => 0 ,
                        'user_unit_description'  => $phris_view->functional_section,
                        'user_unit_bc_code' => $phris_view->bu_code,
                        'user_unit_cc_code' => $phris_view->cc_code,
                        'user_unit_status' => '02',
                        'division_name'  => $division->name,
                        'division_id'=> $division->id,
                        'directorate_id'=> $directorate->id,
                        'directorate_name'=> $directorate->name,
                        'created_by' =>   auth()->user()->id ,
                    ]
                );
            }else{
                //create a functional section
                $functional_section =   ConfigWorkFlow::firstOrCreate(
                    [
                        'user_unit_bc_code' => $org_units->bu_code,
                        'user_unit_cc_code' => $org_units->cc_code,
                    ],
                    [
                        'user_unit_code' => $org_units->code_unit,
                        'user_unit_superior' => $org_units->code_unit_superior,
                        'user_unit_description'  => $org_units->description,
                        'user_unit_bc_code' => $org_units->bu_code,
                        'user_unit_cc_code' => $org_units->cc_code,
                        'user_unit_status' => $org_units->status,
                        'directorate_id'=> $directorate->id,
                        'directorate_name'=> $directorate->name,
                        'created_by' =>   auth()->user()->id ,
                    ]
                );
            }
        }

        else{
            //update the names and directorate
            $functional_section =   ConfigWorkFlow::firstOrCreate(
                [
                    'user_unit_bc_code' => $phris_view->bu_code,
                    'user_unit_cc_code' => $phris_view->cc_code,
                ],
                [
                    'user_unit_bc_code' => $phris_view->bu_code,
                    'user_unit_cc_code' => $phris_view->cc_code,
                    'user_unit_description'  => $phris_view->functional_section,
                    'division_name'  => $division->name,
                    'division_id'=> $division->id,
                    'directorate_id'=> $directorate->id,
                    'directorate_name'=> $directorate->name,
                    'created_by' =>   auth()->user()->id ,
                ]
            );
        }

        //create location if null
        if(  ($location->id ?? 'none')  == 'none'  ){
            // Delimit by multiple spaces, hyphen, underscore, comma
            $words = preg_split("/[\s,_-]+/", $phris_view->location ?? "None");
            $acronym = "";
            foreach ($words as $w) {
                try {
                    $acronym .= $w[0];
                } catch (\Exception $exception) {
                    $acronym = $w;
                }
            }
            //create the location
            $location = LocationModel::firstOrCreate(
                [
                    'name' => $phris_view->location,
                ],
                [
                    'name' => $phris_view->location,
                    'code' => $acronym ,
                    'created_by' => auth()->user()->id,
                ]
            );
        }

        //create grade_cat if null
        if(  ($grade_cat->id ?? 'none')  == 'none'  ){
            $grade_cat = GradesCategoryModel::firstOrCreate(
                [
                    'name' => $phris_view->group_type ?? "none"
                ],
                [
                    'name' => $phris_view->group_type ?? "none" ,
                    'created_by' =>   auth()->user()->id ,
                ]);
        }

        //create grade if null
        if(  ($grade->id ?? 'none')  == 'none'  ){
            $grade = GradesModel::firstOrCreate(
                [
                    'name' => $phris_view->grade ?? "none",
                    'category_id' => $grade_cat->id ?? 1
                ],
                [
                    'name' => $phris_view->grade ?? "none" ,
                    'category_id' => $grade_cat->id ?? 1  ,
                    'sub_rate' => 0  ,
                    'kilometer_allowance_rate' => 0  ,
                    'created_by' =>   auth()->user()->id ,
                ]);
        }

        //create position if null
        if(  ($position->id ?? 'none')  == 'none'  ){
            $position = PositionModel::firstOrCreate(
                [
                    'name' => $phris_view->job_title ?? "none",
                    'code' => $phris_view->job_code ?? "none",
                ],
                [
                    'name' => $phris_view->job_title ?? "none",
                    'code' => $phris_view->job_code ?? "none",
                    'created_by' =>   auth()->user()->id ,
                ]);


        }


        //update user unit
        $functional_section->user_unit_description = $phris_view->functional_section ;
        $functional_section->directorate_id = $directorate->id ;
        $functional_section->directorate_name = $directorate->name ;
        $functional_section->division_id = $division->id ;
        $functional_section->division_name = $division->name ;
        $functional_section->save();
        //update grade
        $grade->category_id = $grade_cat->id ;
        $grade->save();
        //
        $user->name  = $phris_view->name;
        $user->email  = $phris_view->staff_email;
        $user->phone  = $phris_view->mobile_no;
        $user->user_unit_id  = $functional_section->id  ??  0 ;
        $user->user_directorate_id  = $directorate->id  ??  0 ;
        $user->user_division_id  = $division->id  ??  0 ;
//        $user->user_region_id; = null
        $user->location_id  = $location->id  ??  0 ;
//        $user->pay_point_id  = $pay_point_id->id;
        $user->functional_unit_id  = $functional_section->id  ??  0 ;
        $user->user_unit_id  = $functional_section->id  ??  0  ;

        $user->nrc  = $phris_view->nrc;
        $user->contract_type  = $phris_view->contract_type;
        $user->con_st_code  = $phris_view->con_st_code;
        $user->con_wef_date  = $phris_view->con_wef_date;
        $user->con_wet_date  = $phris_view->con_wet_date;
        $user->positions_id  = $position->id;
        $user->grade_id  = $grade->id ;
        $user->user_unit_code  = $functional_section->user_unit_code;
        $user->job_code  = $position->code;
        $user->station  = $phris_view->station;
        $user->affiliated_union =  $phris_view->union_affiliation;


        //count the users login times
        $user->total_login += 1 ;
        $user->save();



        //get the users totals value
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.main_dashboard'))
            ->where('name', 'Total Users')
            ->get()->first();
        //update
        $totals->value = 1 + ($totals->value);
        $totals->save();

        //count the users login times
        $user->total_login = 1 + ($user->total_login);
        $user->save();
    }
}
