<?php

namespace App\Http\Controllers\EForms\Subsistence1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Http\Requests\CreateSubsistenceRequest;
use App\Mail\SendMail;
use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\Main\AccountsChartModel;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\ProfilePermissionsModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\StatusModel;
use App\Models\main\TotalsModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class SubsistenceController extends Controller
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
        session(['eform_id' => config('constants.eforms_id.subsistence')]);
        session(['eform_code' => config('constants.eforms_name.subsistence')]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $value)
    {

        //get list of all subsistence forms for today
        if ($value == "all") {
            $list = SubsistenceModel::all();
            $category = "All";
        } else if ($value == "pending") {
            $list = SubsistenceModel::where('config_status_id', '>', config('constants.subsistence_status.new_application'))
                ->where('config_status_id', '<', config('constants.subsistence_status.closed'))
                ->get();
            $category = "Opened";
        } else if ($value == config('constants.subsistence_status.new_application')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.new_application'))
                ->get();
            $category = "New Application";
        } else if ($value == config('constants.subsistence_status.closed')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.closed'))
                ->get();
            $category = "Closed";
        } else if ($value == config('constants.subsistence_status.rejected')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.rejected'))
                ->get();
            $category = "Rejected";
        } else if ($value == "needs_me") {
            $list = $totals_needs_me = HomeController::needsMeList();
            $category = "Needs My Attention";
        } else if ($value == "admin") {

        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))->get();

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list,
            'totals' => $totals,
            'pending' => $pending,
            'category' => $category,
        ];

        //return view
        return view('eforms.subsistence.list')->with($params);

    }

    /**
     * Display a listing of the resource for the admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function records(Request $request, $value){

        //get list of all subsistence for today
        if($value == "all"){

            $list = DB::select("SELECT * FROM eform_subsistence");
            $list = SubsistenceModel::hydrate($list);

            $category = "All Records";

        }elseif ($value == "pending"){

            $list = SubsistenceModel::where('config_status_id','>', config('constants.subsistence_status.new_application'))
                ->where('config_status_id', '<', config('constants.subsistence_status.closed'))
                ->get();

            $category = "Opened";

        }elseif ($value == config('constants.subsistence_status.new_application')){

            $list = SubsistenceModel::where('config_status_id',config('constants.subsistence_status.new_application'))
                ->get();

            $category = "New Application";

        }elseif ($value == config('constants.subsistence_status.closed')){

            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.closed'))
                ->get();

            $category = "Closed";

        }elseif ($value == config('constants.approval.reject')){

            $list = SubsistenceModel::where('config_status_id', config('constants.approval.reject'))
                ->get();

            $category = "Rejected";

        } elseif ($value == "needs_me") {

            $list = $totals_needs_me = HomeController::needsMeCount();

            $category = "Needs My Attention";

        } else if($value == "admin"){

        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))->get();

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list,
            'totals' => $totals,
            'pending' => $pending,
            'category' => $category,
        ];

        //return view
        return view('eforms.subsistence.records')->with($params);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = auth()->user();
        $subsistences = SubsistenceModel::all();
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'user' => $user,
            'subs' => $subsistences
        ];
        //return view
        return view("eforms.subsistence.create")->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubsistenceRequest $request)
    {
        //get the logged in user
        $user = Auth::user();

        //[2A] find my code superior
        $my_hods = self::myHODs($user);

        if (empty($my_hods)) {
           // dd(22);
            //prepare details
            $details = [
                'name' => "Team",
                'url' => 'subsistence-home',
                'subject' => "Subsistence-Voucher Configuration Needs Your Attention",
                'title' => "Code Superior Not Defined for {$user->name}",
                'body' => "Please note that {$user->name} with Staff Number ({$user->staff_no} ), failed to submit or raise subsistence voucher.
              <br>This could be as a result of either : <br><br>
                   1: The superior has not registered in the system. Make sure the superior to {$user->name} is registered. <br>
                   2: The superior has not been assigned the correct profile. Please assign the right profile. <br>
                   3: The code superior for {$user->position->code} is empty. Please assign the correct code superior. <br>
                <br>You can update the code superior by clicking on 'Positions' menu, then search for {$user->position->code}
                <br> and 'Edit' to update the correct 'code superior' ."
            ];

            //send emails
            $to = config('constants.team_email_list');
            $mail_to_is = Mail::to($to)->send(new SendMail($details));

            //return with error msg
            return Redirect::route('subsistence-home')->with('error', 'Sorry!, The superior who is supposed to approve your subsistence,
                       <br> has not registered or not fully configured yet, Please, <b>try first contacting your superior</b> so as to make sure he/she has registered in the system,
                       then you can contact eZESCO Admins to configure your superior. Your Subsistence has not been saved.');
        }

        //generate code
        $code = self::randGenerator("SUB");
        //create the form
        $formModel = SubsistenceModel::updateOrCreate(
            [
                'code' => $code,
                'ref_no' => $request->ref_no,
                'config_status_id' => config('constants.subsistence_status.new_application'),
                'grade' => $user->grade->name,
                'ext_no' => $user->phone,
                'claim_date' => $request->date,
                'claimant_name' => $user->name,
                'claimant_staff_no' => $user->staff_no,
                'absc_absent_from' => $request->absc_absent_to,
                'absc_absent_to' => $request->absc_absent_from,
                'absc_visited_place_reason' => $request->absc_visited_place_reason,
                'absc_visited_place' => $request->absc_visited_place,
                'absc_allowance_per_night' => $request->absc_allowance_per_night,
                'absc_amount' => $request->absc_amount,
            ],
            [
                'cost_center' => $user->user_unit->cost_center_code,
                'business_unit_code' => $user->user_unit->business_unit_code,
                'user_unit_code' => $user->user_unit->code,
                'user_unit_id' => $user->user_unit->id,
                'pay_point_id' => $user->pay_point_id,
                'location_id' => $user->location_id,
                'division_id' => $user->user_division_id,
                'region_id' => $user->user_region_id,
                'directorate_id' => $user->user_directorate_id,

                'grade' => $user->grade->name,
                'ext_no' => $user->phone,
                'claim_date' => $request->date,
                'claimant_name' => $user->name,
                'claimant_staff_no' => $user->staff_no,
                'station' => $request->station,
                'section' => $user->department->name,

                'code' => $code,
                'ref_no' => $request->ref_no,
                'config_status_id' => config('constants.subsistence_status.new_application'),

                'absc_absent_from' => $request->absc_absent_to,
                'absc_absent_to' => $request->absc_absent_from,
                'absc_visited_place_reason' => $request->absc_visited_place_reason,
                'absc_visited_place' => $request->absc_visited_place,
                'absc_allowance_per_night' => $request->absc_allowance_per_night,
                'absc_amount' => $request->absc_amount,

                'created_by' => $user->id,
                'profile' => Auth::user()->profile_id,
                'code_superior' => Auth::user()->position->superior_code,
            ]);


        /** update the totals */
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('id', config('constants.totals.subsistence_new'))
            ->first();
        $totals->value = $totals->value + 1;
        $totals->save();

        $eform_pettycash = EFormModel::find(config('constants.eforms_id.subsistence'));
        $eform_pettycash->total_new = $totals->value;
        $eform_pettycash->save();

        /** send email to supervisor */
        //get team email addresses
       // $to = config('constants.team_email_list');
        $to[] = ['email' => 'nshubart@zesco.co.zm', 'name' => 'Shubart'];
        $names = "";
        //add hods email addresses
        foreach ($my_hods as $item) {
            $to[] = ['email' => $item->email, 'name' => $item->name];
            $names = $names . ',<br>' . $item->name;
        }
        //prepare details
        $details = [
            'name' => $names,
            'url' => 'subsistence-home',
            'subject' => "New Subsistence Form Needs Your Attention",
            'title' => "New Subsistence Form Needs Your Attention",
            'body' => "Please note that {$user->name} with Staff Number {$user->staff_no} has successfully raised subsistence of ZMW {$formModel->absc_amount}
                   <br><br>     serial: {$formModel->code}  <br>    reference: {$formModel->ref_no} and <br>   Status: {$formModel->status->name}</br> <br>
            This voucher now needs your approval, kindly click on the button below to login to E-ZESCO and take action on the voucher."
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));
        //log the activity
        ActivityLogsController::store($request, "Creating of Subsistence Form", "create", " subsistence form {$formModel->code} created", $formModel->id);
        //return the view
        return Redirect::route('subsistence-home')->with('message', 'Subsistence ' . $formModel->code . ' Submitted Successfully');
    }

    /**
     * Fetch a list of my HODs
     * @param $user
     * @return array
     */
    public function myHODs($user)
    {
        //[1A] Find my code superior
        $my_user_unit_users = User::where('user_unit_id', $user->user_unit_id)->get();
        $hods_array = [];

        //[1B] THE ASSIGNED PROFILE PROFILE
        $hod_profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));

        //[2] find my hod base
        foreach ($my_user_unit_users as $item) {
            //[A]check if the users in my user unit have this assigned profile
            $hods_assigned = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.subsistence'))
                ->where('profile', $hod_profile->code)
                ->where('user_id', $item->id);
            if ($hods_assigned->exists()) {
                $hods_array[] = $item;  // worked
            } else {

                //[B]check if the users in my user unit have this delegated profile
                $hods_assigned = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.subsistence'))
                    ->where('delegated_profile', $hod_profile->code)
                    ->where('delegated_to', $item->id)
                    ->where('delegated_user_unit', $item->user_unit_id)
                    ->where('config_status_id', config('constants.active'));
                if ($hods_assigned->exists()) {
                    $hods_array[] = $item;
                    dd(2);
                } else {

                    //Else get the user who has your code superior as his job title
                    if ($user->position->superior_code == $item->position->code) {
                        $hods_array[] = $item;
                        dd($hods_array);
                    }
                }
            }

        }

        //[3] return the list of users
        return $hods_array;
    }

    /**
     * Generate Voucher Code
     * @param $head
     * @return string
     */
    public function randGenerator($head)
    {
        // use of oracle sequence
        $count = DB::select("SELECT id as total  FROM eform_subsistence");

        if ($count) {
            try{
                $size = sizeof($count);
                $size = $count[$size - 1];
                $random = $size->total;  // oracle sequence
            }catch (\OutOfBoundsException $ex){
                $random = 1;  // oracle sequence
            }
        } else {
            $random = 1;  // oracle sequence
        }
        $random = sprintf("%07d", $random);
        $random = $head . $random;

        // check if this code exits already
        $check = SubsistenceModel::where('code', $random)->get();

        if ($check->isEmpty()) {
            return $random;
        } else {
            self::randGenerator("SUB");
        }
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //GET THE PETTY CASH MODEL if you are an admin
        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $list = DB::select("SELECT * FROM eform_subsistence where id = {$id} ");
            $form = SubsistenceModel::hydrate($list)->first();
        } else {
            //find the subsistence with that id
            $form = subsistenceModel::find($id);
        }

        $projects = ProjectsModel::all();
        $accounts = AccountsChartModel::all();
        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.subsistence'))->get();

        //get the list of users who are supposed to work on the form
        $user_array = self::nextUsers($form);
        //   $user_array = self::nextUsers($form->profile, $form->config_status_id, $form->claimant_staff_no);

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'form' => $form,
            'projects' => $projects,
            'user_array' => $user_array,
            'approvals' => $approvals,
            'accounts' => $accounts
        ];
        //return view
        return view('eforms.subsistence.show')->with($params);

    }

    /**
     * List the users who are supposed to work on the form next
     * @param $last_profile
     * @param $current_status
     * @param $claimant_man_no
     * @return array
     */
    public function nextUsers($form)
    {

        $user_array = [];
        $user_claimant = User::where('staff_no', $form->claimant_staff_no)->get()->first();

        //[1]
        //THE LAST PROFILE
        $last_profile_who_worked = ProfileModel::find($form->profile);
        //get the next profiles to work from the last profile  PROFILE Permissions
        $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('profile', $last_profile_who_worked->code)
            ->first();

        //[2]
        //THE NEXT PROFILE
        $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next;
        //get the profile permissions associated with this next_profile_to_work
        $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('profile', $next_profile_to_work->code)
            ->get();


        //[3]
        //THE USERS
        //check if this next profile is for a claimant and if the Form needs Acknowledgement
        if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $form->config_status_id == config('constants.subsistence_status.security_approved')) {
            $user = User::where('staff_no', $form->claimant_staff_no)
                ->first();
            $user_array[] = $user;
        } //check if this next profile is for a claimant and if the Form is closed
        else if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $form->config_status_id == config('constants.subsistence_status.closed')) {
            //get user
            $user = User::where('staff_no', $form->claimant_staff_no)->first();
            $user_array[] = $user;
        } //check if the Form is closed
        else if ($form->config_status_id == config('constants.subsistence_status.closed')) {
            //get no user
            $user_array = [];
        } //check if the Form is closed
        else if ($form->config_status_id == config('constants.subsistence_status.rejected')) {
            //get no user
            $user_array = [];
        } // other wise get the users
        else {
            foreach ($profileAssignement as $item) {
                //get user who is the next person
                $user = User::find($item->user_id);
                // FILTER: based on user unit  if the form is a New Application
                if ($form->status->id == config('constants.subsistence_status.new_application')) {
                        $user_array = self::myHODs($user_claimant);
                }
                if ($form->status->id == config('constants.subsistence_status.new_application')) {
                    $user_array = self::myHODs($user_claimant);
                }
                else {
                    //use the pay point
                    if ($user->pay_point_id == $form->pay_point_id) {
                        $user_array[] = $user;
                    }
                }
            }
        }

        //[4]
        //return the list of users
        return $user_array;

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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function approve(Request $request)
    {
        //GET THE SUBSISTENCE MODEL
        $form = SubsistenceModel::find($request->id);
        $current_status = $form->status->id;
        $new_status = 0;
        $user = Auth::user();
        $eform_subsistence = EFormModel::find(config('constants.eforms_id.subsistence'));

        dd($request->approval);

        //make sure the code superior for the HOD is defined
        if ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            if ($user->position->superior_code == null) {

                dd(1111);
                //if code superior is missing , do not proceed, ask them their superior to register first
                $details = [
                    'name' => "Team",
                    'url' => 'subsistence-home',
                    'subject' => "Subsistence-Voucher Configuration Needs Your Attention",
                    'title' => "Code Superior Not Defined for {$user->name}",
                    'body' => "Please note that {$user->name} with Staff Number ({$user->staff_no} ), failed to Approve subsistence which is at {$form->status->name}.
                <br>This could be as a result of <br><br>
                   1: The superior has not registered in the system. Make sure the superior to {$user->name} is registered. <br>
                   2: The superior has not been assigned the correct profile. Please assign the right profile. <br>
                   3: The code superior for {$user->position->code} is empty. Please assign the correct code superior. <br>
                <br>You can update the code superior by clicking on 'Positions' menu, then search for {$user->position->code}
                <br> and 'Edit' to update the correct 'code superior' ."
                ];

                //send emails
                $to = config('constants.team_email_list');
                $mail_to_is = Mail::to($to)->send(new SendMail($details));

                //return with error msg
                return back()->with('error', 'Sorry!, The superior who is supposed to approve your subsistence,
                       <br> has not registered or not fully configured yet, Please, <b>try first contacting your superior</b> so as to make sure he/she has registered in the system,
                       then you can contact eZESCO Admins to configure your superior. Your Subsistence has not been saved.');
            }
        }

        //[1]HANDLE REJECTION
        if ($request->approval == config('constants.approval.reject')) {
            $new_status = config('constants.subsistence_status.rejected');

            //update the totals rejected
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))
                ->where('id', config('constants.totals.subsistence_reject'))
                ->first();
            $totals->value = $totals->value + 1;
            $totals->save();
            $eform_subsistence->total_rejected = $totals->value;
            $eform_subsistence->save();

            //update the totals open
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))
                ->where('id', config('constants.totals.subsistence_open'))
                ->first();
            $totals->value = $totals->value - 1;
            $totals->save();
            $eform_subsistence->total_pending = $totals->value;
            $eform_subsistence->save();

            //get status id
            $status_model = StatusModel::where('id', $new_status)
                ->where('eform_id', config('constants.eforms_id.subsistence'))->first();
            $new_status = $status_model->id;

        }

        //[2]HANDLE APPROVAL
        if ($request->approval == config('constants.approval.approve')) {
            $new_status = $form->status->status_next;

            //form about to be closed
            if ($form->status->id == config('constants.subsistence_status.funds_acknowledgement')) {

                //update the totals closed
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))
                    ->where('id', config('constants.totals.subsistence_closed'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_subsistence->total_closed = $totals->value;
                $eform_subsistence->save();

                //update the totals open
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))
                    ->where('id', config('constants.totals.subsistence_open'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_subsistence->total_pending = $totals->value;
                $eform_subsistence->save();

            } else if ($form->status->id == config('constants.subsistence_status.new_application')) {
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))
                    ->where('id', config('constants.totals.subsistence_open'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_subsistence->total_pending = $totals->value;
                $eform_subsistence->save();

                //update the totals new
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))
                    ->where('id', config('constants.totals.subsistence_new'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_subsistence->total_new = $totals->value;
                $eform_subsistence->save();
            }

            //get status id
            $status_model = StatusModel::where('status', $new_status)
                ->where('eform_id', config('constants.eforms_id.subsistence'))->first();
            $new_status = $status_model->id;
        }

        $insert_reasons = false ;

        //[3A]FOR HOD
        if ($user->profile_id == config('constants.user_profiles.EZESCO_004')
            && $current_status  == config('constants.subsistence_status.new_application')
        ) {
            //update
            $form->config_status_id = $new_status;
            $form->authorised_by = $user->name;
            $form->authorised_staff_no = $user->staff_no;
            $form->authorised_date = $request->sig_date;
            $form->code_superior = $user->position->superior_code;
            $form->profile = $user->profile_id;
            $form->save();
            $insert_reasons = true ;
        }

        //[3B]FOR SNR MANAGER
        if ($user->profile_id == config('constants.user_profiles.EZESCO_015')
            &&  $current_status == config('constants.subsistence_status.hod_approved')
        ) {
            //update
            $form->config_status_id = $new_status;
            $form->station_manager = $user->name;
            $form->station_manager_staff_no = $user->staff_no;
            $form->station_manager_date = $request->sig_date;
            $form->profile = $user->profile_id;
            $form->save();
            $insert_reasons = true ;
        }
        //[3C]FOR HR
        if ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            //update
            $form->config_status_id = $new_status;
            $form->hr_office = $user->name;
            $form->hr_office_staff_no = $user->staff_no;
            $form->hr_date = $request->sig_date;
            $form->profile = $user->profile_id;
            $form->save();
            $insert_reasons = true ;
        }
        //[3D]FOR CHIEF ACCOUNTANT
        if ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            //update
            $form->config_status_id = $new_status;
            $form->chief_accountant = $user->name;
            $form->chief_accountant_staff_no = $user->staff_no;
            $form->chief_accountant_date = $request->sig_date;
            $form->profile = $user->profile_id;
            $form->save();
            $insert_reasons = true ;
        }
        //[3E]FOR AUDIT
        if ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            //update
            $form->config_status_id = $new_status;
            $form->audit_name = $user->name;
            $form->audit_staff_no = $user->staff_no;
            $form->audit_date = $request->sig_date;
            $form->profile = $user->profile_id;
            $form->save();
            $insert_reasons = true ;
        }
        //[3F]FOR EXPENDITURE
        if ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            //update
            $form->config_status_id = $new_status;
            $form->authorised_by = $user->name;
            $form->authorised_staff_no = $user->staff_no;
            $form->authorised_date = $request->sig_date;
            $form->profile = $user->profile_id;
            $form->save();
            $insert_reasons = true ;
        }
        //[3G]FOR APPROVAL
        if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            //update
            $form->config_status_id = $new_status;
            $form->profile = $user->profile_id;
            $form->save();
            $insert_reasons = true ;
        }

        if($insert_reasons) {
            //save reason
            $reason = EformApprovalsModel::Create(
                [
                    'profile' => $user->profile_id,
                    'title' => $user->profile_id,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $request->reason,
                    'action' => $request->approval,
                    'current_status_id' => $current_status,
                    'action_status_id' => $new_status,
                    'config_eform_id' => config('constants.eforms_id.subsistence'),
                    'eform_id' => $form->id,
                    'created_by' => $user->id,
                ]);
            //send the email
            self::nextUsersSendMail($user->profile_id, $new_status, $form);
        }

        //redirect home
        return Redirect::route('subsistence-home')->with('message', 'Subsistence Form ' . $form->code . ' for ' . $form->claimant_staff_name . ' has been ' . $request->approval . ' successfully');

    }


    public function nextUsersSendMail($last_profile, $new_status, $form)
    {
        $user_array = [];
        $claimant_details = User::find($form->created_by);

        //message details
        $subject = 'Subsistence Form Needs Your Attention';
        $title = 'Subsistence Form Needs Your Attention';
        $message = 'This is to notify you that subsistence (' . $form->code . ') with a total of ZMW $form_code}that needs your attention.
            Please login to e-ZESCO by clicking on the button below to take action on the voucher.';

        //[1]
        //THE LAST PROFILE
        $last_profile_who_worked = ProfileModel::find($last_profile);
        //get the next profiles to work from the last profile  PROFILE Permissions
        $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('profile', $last_profile_who_worked->code)
            ->first();


        //[2]
        //THE NEXT PROFILE
        $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next;
        //get the profile permissions associated with this next_profile_to_work
        $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('profile', $next_profile_to_work->code)
            ->get();

        //[3]
        //THE USERS
        //check if this next profile is for a claimant and if the Form needs Acknowledgement
        if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $new_status == config('constants.subsistence_status.security_approved')) {
            $user = User::where('staff_no', $form->claimant_man_no)->first();
            $user_array[] = $user;

            //message details
            $subject = 'Subsistence Needs Your Attention';
            $title = 'Subsistence Needs Your Attention';
            $message = 'This is to notify you that there is a Subsistence (' . $form->code . ') that needs your attention.
            Please login to e-ZESCO by clicking on the button below to take action on the voucher.';
        } //check if this next profile is for a claimant and if the Form is closed
        else if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $new_status == config('constants.subsistence_status.closed')) {
            //get user
            $user = User::where('staff_no', $form->claimant_man_no)->first();
            $user_array[] = $user;

            //message details
            $subject = 'Subsistence Closed Successfully';
            $title = 'Subsistence Closed Successfully';
            $message = ' Congratulation! This is to notify you that Subsistence ' . $form->code . ' has been closed successfully .
            Please login to e-ZESCO by clicking on the button below to view the voucher.';
        } // other wise get the users
        else {
            foreach ($profileAssignement as $item) {
                //get user
                $user = User::find($item->user_id);
                $user_array[] = $user;
            }
        }

        /** send email to supervisor */
        try {
            //get team email addresses
            $to = config('constants.team_email_list');
            $names = "";
            //add hods email addresses
            foreach ($user_array as $item) {
                $to[] = ['email' => $item->email, 'name' => $item->name];
                $to[] = ['email' => $claimant_details->email, 'name' => $claimant_details->name];
                $names = $names . '<br>' . $item->name;
            }
            //prepare details
            $details = [
                'name' => $user->name,
                'url' => 'subsistence-home',
                'subject' => $subject,
                'title' => $title,
                'body' => $message
            ];
            //send mail
            $mail_to_is = Mail::to($to)->send(new SendMail($details));

            if (Mail::failures()) {
                return response()->Fail('Sorry! Please try again latter');
            } else {
                return response()->success('Great! Successfully send in your mail');
            }

            //get user details
        } catch (\Exception $exe) {

        }


        //[5] return the list of users
        return $user_array;

    }


    public function reports(Request $request)
    {
        //get the accounts
        $list = SubsistenceModel::where('config_status_id',config('constants.subsistence_status.not_exported'))->get();

        //count all that needs me
        $total_needs_me = HomeController::needsMeCount();

        $params = [
            'totals_needs_me' => $total_needs_me,
            'list' => $list,
        ];

        return view('eforms.subsistence.report')->with($params);
    }


    public function reportsExport(Request $request)
    {
        $fileName = 'Subsistence_Accounts.csv';

        $tasks = SubsistenceModel::where('status_id', config('constants.subsistence_status.not_exported'))->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = [
            'Code',
            'Claimant',
            'Claim Date',
            'Company',
            'Business Unit',
            'Cost Center',
            'Account',
            'Project',
            'Intra-Company',
            'Spare',
            'PEMS Project',
            'Debit',
            'Credit',
            'Line Description'
        ];

        $callback = function () use ($tasks, $columns){
            $file = fopen('php://output', 'w');
            fputcsv($file,$columns);

            foreach ($tasks as $item){

                //mark the item as exported
                $item->status_id = config('constants.subsistence_status.exported');
                $item->save();

                $row['Code'] = $item->subsistence->code;
                $row['Claimant'] = $item->subsistence->claimant_name;
                $row['Claim Date'] = $item->subsistence->claim_date;
                $row['Company'] = $item->company;
                $row['Business Unit'] = $item->subsistence->business_unit_code;
                $row['Cost Center'] = $item->subsistence->cost_center;
                $row['Account'] = $item->account;
                $row['Project'] = $item->project;
                $row['Intra-Company'] = $item->intra_company;
                $row['Spare'] = $item->spare;
                $row['PEMS Project'] = $item->pems_project;
                $row['Debit'] = $item->debitted_amount;
                $row['Credit'] = $item->creditted_amount;
                $row['Line Description'] = $item->description;


                fputcsv($file, array(

                    $row['Code'],
                    $row['Claimant'],
                    $row['Claim Date'],
                    $row['Company'],
                    $row['Business Unit'],
                    $row['Cost Center'],
                    $row['Account'],
                    $row['Project'],
                    $row['Intra-Company'],
                    $row['Spare'],
                    $row['PEMS Project'],
                    $row['Debit'],
                    $row['Credit'],
                    $row['Line Description']

                ));
            }

            fclose($file);

        };

        return response()->stream($callback, 200, $headers);
    }


}
