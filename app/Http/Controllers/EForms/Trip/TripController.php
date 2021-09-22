<?php

namespace App\Http\Controllers\EForms\Trip;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Mail\SendMail;
use App\Models\EForms\PettyCash\PettyCashAccountModel;
use App\Models\EForms\Trip\Invitation;
use App\Models\EForms\Trip\Trip;
use App\Models\Main\AccountsChartModel;
use App\Models\Main\AttachedFileModel;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\main\ProfileModel;
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
use function MongoDB\BSON\toJSON;


class TripController extends Controller
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
        session(['eform_id' => config('constants.eforms_id.trip')]);
        session(['eform_code' => config('constants.eforms_name.trip')]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $value)
    {
        //get list of all Trip forms for today
        if ($value == "all") {
            $list = Trip::all();
            $category = "All";
        } else if ($value == "pending") {
            $list = Trip::where('config_status_id', '>', config('constants.trip_status.new_trip'))
                ->where('config_status_id', '<', config('constants.trip_status.closed'))
                ->get();
            $category = "Opened";
        } else if ($value == config('constants.trip_status.new_trip')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.new_trip'))
                ->get();
            $category = "New Application";
        } else if ($value == config('constants.trip_status.closed')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.closed'))
                ->get();
            $category = "Closed";
        } else if ($value == config('constants.trip_status.rejected')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.rejected'))
                ->get();
            $category = "Rejected";
        } else if ($value == "needs_me") {
            $list = $totals_needs_me = HomeController::needsMeList();
            $category = "Needs My Attention";
        } else if ($value == "admin") {

        }


        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))->get();

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
        return view('eforms.trip.list')->with($params);

    }


    /**
     * Display a listing of the resource for the admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function records(Request $request, $value)
    {

        //get list of all Trip forms for today
        if ($value == "all") {
            $list = DB::select("SELECT * FROM eform_trip ");
            $list = Trip::hydrate($list);

            $category = "All Records";
        } else if ($value == "pending") {
            $list = Trip::where('config_status_id', '>', config('constants.trip_status.new_trip'))
                ->where('config_status_id', '<', config('constants.trip_status.closed'))
                ->get();
            $category = "Opened";
        } else if ($value == config('constants.trip_status.new_trip')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.new_trip'))
                ->get();
            $category = "New Application";
        } else if ($value == config('constants.trip_status.closed')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.closed'))
                ->get();
            $category = "Closed";
        } else if ($value == config('constants.trip_status.rejected')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.rejected'))
                ->get();
            $category = "Rejected";
        } else if ($value == "needs_me") {
            $list = $totals_needs_me = HomeController::needsMeList();
            $category = "Needs My Attention";
        } else if ($value == "admin") {

        }


        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))->get();

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
        return view('eforms.trip.records')->with($params);

    }

    /**
     * Mark the form as void.
     *
     * @return \Illuminate\Http\Response
     */
    public function void(Request $request, $id)
    {
        //GET THE Trip MODEL
        $list = DB::select("SELECT * FROM eform_trip where id = {$id} ");
        $form = Trip::hydrate($list)->first();
        //get the status
        $current_status = $form->status->id;
        $new_status = 0;
        $user = Auth::user();
        //get the form type
        $eform_pettycash = EFormModel::find(config('constants.eforms_id.trip'));

        //HANDLE VOID REQUEST
        $new_status = config('constants.trip_status.void');

        //update the totals rejected
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))
            ->where('id', config('constants.totals.trip_reject'))
            ->first();
        $totals->value = $totals->value + 1;
        $totals->save();
        $eform_pettycash->total_rejected = $totals->value;
        $eform_pettycash->save();

        //update the totals open
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))
            ->where('id', config('constants.totals.trip_open'))
            ->first();
        $totals->value = $totals->value - 1;
        $totals->save();
        $eform_pettycash->total_pending = $totals->value;
        $eform_pettycash->save();

        //get status id
        $status_model = StatusModel::where('id', $new_status)
            ->where('eform_id', config('constants.eforms_id.trip'))->first();
        $new_status = $status_model->id;

        //update the form status
        $form->config_status_id = $new_status;
        $form->save();

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
                'config_eform_id' => config('constants.eforms_id.trip'),
                'eform_id' => $form->id,
                'created_by' => $user->id,
            ]);

        //redirect home
        return Redirect::back()->with('message', 'Trip ' . $form->code . ' for has been marked as Void successfully');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
        $units = HomeController::getMyViisbleUserUnits();
        $units->load('users_list');
       // dd($units);
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

       // dd($units);
        //show the create form
        return view('eforms.trip.create')->with(compact('units', 'user', 'totals_needs_me'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //get team email addresses
        $to = [];
        $names = "";
        $invited = sizeof($request->users);

        if($invited < 1){
            dd("Please Invite some members to subscribe to the trip");
        }


        //[1]get the logged in user
        $user = Auth::user();   //superior_code
        //generate the Trip unique code
        $code = self::randGenerator("TR", 1);
        $formModel = Trip::updateOrCreate(
            [
                'date_from' =>  $request->date_from,
                'date_to' =>  $request->date_to,
                'name'=> $request->name,
                'code'=> $code,
            ],
            [
                'date_from' =>  $request->date_from,
                'date_to' =>  $request->date_to,
                'hod_code' => $user->profile_job_code,
                'hod_unit' => $user->profile_unit_code,

                'code'=> $code,
                'name'=> $request->name,
                'description'=> $request->description,
                'destination' => $request->destination,
                'config_status_id' => config('constants.trip_status.new_trip'),

                'initiator_name' => $user->name,
                'initiator_staff_no' => $user->staff_no,
                'initiator_date' => $user->id,
                'invited' => $invited,

                'created_by' => $user->id,

            ]);


        /** send email to the invited */
        //add hods email addresses
        foreach ($request->users as $item) {
            $userObj = json_decode($item);
            $to[] = ['email' => $userObj->email, 'name' => $userObj->name];
            $names = $names . '<br>' . $userObj->name;

            // insert into invitations table
            $invitation = Invitation::UpdateOrCreate(
                [
                    'man_no' => $userObj->staff_no,
                    'trip_code' => $formModel->code ,
                    'date_from' => $formModel->date_from ,
                    'date_to'=> $formModel->date_to,
                ],
                [
                    'man_no' => $userObj->staff_no,
                    'trip_code' => $formModel->code ,
                    'date_from' => $formModel->date_from ,
                    'date_to'=> $formModel->date_to,
                ]
            );
        }

        //prepare details
        $details = [
            'name' => $names,
            'url' => 'subsistence.home',
            'subject' => "New Trip To {$request->destination}",
            'title' => "New Trip Needs Your Attention",
            'body' => "Please note that {$user->name} has successfully created a Trip to {$request->destination} with the following details:
                   <br> <span class='ml-3'> serial:</span> {$formModel->code}
                   <br> <span class='ml-3'>destination:</span> {$formModel->destination}
                   <br> <span class='ml-3'>from:</span> {$formModel->date_from}
                   <br> <span class='ml-3'>to:</span> {$formModel->date_to}
                   <br> <span class='ml-3'>description:</span> {$formModel->description}
                   and <br> <span class='ml-3'>Status:</span> {$formModel->status->name}</br>. <br>
            To subscribe to this trip, kindly click on the button below to login to E-ZESCO and raise subsistence for the trip.<br> regards. "
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));

        //log the activity
//        ActivityLogsController::store($request, "Creating of Trip", "create", " trip created", $formModel->id);
        //return the view
        return Redirect::route('trip.home')->with('message', 'Trip Details for ' . $formModel->code . ' have been Created successfully');

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
            $hods_assigned = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.trip'))
                ->where('profile', $hod_profile->code)
                ->where('user_id', $item->id);
            if ($hods_assigned->exists()) {
                $hods_array[] = $item;
            } else {

                //[B]check if the users in my user unit have this delegated profile
                $hods_assigned = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.trip'))
                    ->where('delegated_profile', $hod_profile->code)
                    ->where('delegated_to', $item->id)
                    ->where('delegated_user_unit', $item->user_unit_id)
                    ->where('config_status_id', config('constants.active'));
                if ($hods_assigned->exists()) {
                    $hods_array[] = $item;
                } else {
                    //Else get the user who has your code superior as his job titel
                    if ($user->position->superior_code == $item->position->code) {
                        $hods_array[] = $item;
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
    public function randGenerator($head, $value)
    {
        // use the total number of petty cash in the system
        $count = DB::select("SELECT count(id) as total FROM eform_trip ");

        //random number
        // $random = rand(1, 9999999);
        $random = $count[0]->total;  // count total and begin again
        // $random = $size->total ;  // oracle sequence
        $random = sprintf("%07d", ($random + $value));
        $random = $head . $random;

        $count_existing_forms = DB::select("SELECT count(id) as total FROM eform_trip WHERE code = '{$random}'");
        try {
            $total = $count_existing_forms[0]->total;
        } catch (\Exception $exception) {
            $total = 0;
        }

        if ($total < 1) {
            return $random;
        } else {
            self::randGenerator($head, $value);
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
        //GET THE Trip MODEL if you are an admin
        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $list = DB::select("SELECT * FROM eform_trip where id = {$id} ");
            $form = Trip::hydrate($list)->first();
        } else {
            //find the Trip with that id
            $form = Trip::find($id);
        }
        $form->load('members');
        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.trip'))->get();

        //get the list of users who are supposed to work on the form
        $user_array = self::findMyNextPerson($form, Auth::user()->user_unit, Auth::user() );

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.trip.show')->with(compact('user_array','totals_needs_me', 'form', 'approvals'));

    }

    /**
     * List the users who are supposed to work on the form next
     * @param $last_profile
     * @param $current_status
     * @param $claimant_man_no
     * @return array
     */
    public function findMyNextPerson($form, $user_unit, $claimant)
    {
        $users_array = [];
        $not_claimant = true;

        //CLAIMANT TO HOD
        if ($form->config_status_id == config('constants.trip_status.new_trip')) {

          //  dd($user_unit);
            $superior_user_unit = $form->hod_unit;
            $superior_user_code = $form->hod_code;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));

        } //HOD TO HR
        elseif ($form->config_status_id == config('constants.trip_status.hod_approved')) {
            $superior_user_code = $user_unit->hrm_code;
            $superior_user_unit = $user_unit->hrm_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_009'));

        } //HR TO CA
        elseif ($form->config_status_id == config('constants.trip_status.hr_approved')) {
            $superior_user_code = $user_unit->ca_code;
            $superior_user_unit = $user_unit->ca_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_007'));

        } //CA-TO-EXPENDITURE
        elseif ($form->config_status_id == config('constants.trip_status.chief_accountant')) {
            $superior_user_unit = $user_unit->expenditure_unit;
            $superior_user_code = $user_unit->expenditure_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_014'));
        } else {
            //no one
            $superior_user_unit = "0";
            $superior_user_code = "0";
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_002'));
        }

        if ($not_claimant) {
            //SELECT USERS
            //[A]check for any users who have this assigned profile
            $assigned_users = ProfileAssigmentModel::
            where('eform_id', config('constants.eforms_id.trip'))
                ->where('profile', $profile->code)
                ->get();
            //loop through assigned users
            foreach ($assigned_users as $item) {
                if ($profile->id == config('constants.user_profiles.EZESCO_014') ||
                    $profile->id == config('constants.user_profiles.EZESCO_011') ||
                    $profile->id == config('constants.user_profiles.EZESCO_013')) {
                    //expenditure, audit and security
                    $my_superiors = User::where('user_unit_code', $superior_user_unit)
                        ->where('id', $item->user_id)
                        ->get();
                    foreach ($my_superiors as $item) {
                        $users_array[] = $item;
                    }
                } else {
                    //hod, hr, ca
                    $my_superiors = User::where('user_unit_code', $superior_user_unit)
                        ->where('job_code', $superior_user_code)
                        ->where('id', $item->user_id)
                        ->get();
                    foreach ($my_superiors as $item) {
                        $users_array[] = $item;
                    }
                }

            }
            //[B]check if one the users with the profile have this delegated profile
            $delegated_users = ProfileDelegatedModel::
            where('eform_id', config('constants.eforms_id.trip'))
                ->where('delegated_profile', $profile->id)
                ->where('delegated_job_code', $superior_user_code)
                ->where('delegated_user_unit', $superior_user_unit)
                ->where('config_status_id', config('constants.active_state'))
                ->get();

//            dd( $profile->code  );
            //loop through delegated users
            foreach ($delegated_users as $item) {
                $user = User::find($item->delegated_to);
                $users_array[] = $user;
            }

        } else {
            $users_array[] = $claimant;
        }

        //[3] return the list of users
        return $users_array;
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
        //GET THE Trip MODEL
        $form = Trip::find($request->id);
        $current_status = $form->status->id;
        $new_status = 0;
        $user = Auth::user();

        $eform_pettycash = EFormModel::find(config('constants.eforms_id.trip'));

        //HANDLE REJECTION
        if ($request->approval == config('constants.approval.reject')) {
            $new_status = config('constants.trip_status.rejected');

            //update the totals rejected
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))
                ->where('id', config('constants.totals.trip_reject'))
                ->first();
            $totals->value = $totals->value + 1;
            $totals->save();
            $eform_pettycash->total_rejected = $totals->value;
            $eform_pettycash->save();

            //update the totals open
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))
                ->where('id', config('constants.totals.trip_open'))
                ->first();
            $totals->value = $totals->value - 1;
            $totals->save();
            $eform_pettycash->total_pending = $totals->value;
            $eform_pettycash->save();

            //get status id
            $status_model = StatusModel::where('id', $new_status)
                ->where('eform_id', config('constants.eforms_id.trip'))->first();
            $new_status = $status_model->id;
        }

        //HANDLE APPROVAL
        if ($request->approval == config('constants.approval.approve')) {
            $new_status = $form->status->status_next;

            if ($form->status->id == config('constants.trip_status.security_approved')) {

                //update the totals closed
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))
                    ->where('id', config('constants.totals.trip_closed'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_pettycash->total_closed = $totals->value;
                $eform_pettycash->save();

                //update the totals open
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))
                    ->where('id', config('constants.totals.trip_open'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_pettycash->total_pending = $totals->value;
                $eform_pettycash->save();

            } else if ($form->status->id == config('constants.trip_status.new_trip')) {
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))
                    ->where('id', config('constants.totals.trip_open'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_pettycash->total_pending = $totals->value;
                $eform_pettycash->save();

                //update the totals new
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))
                    ->where('id', config('constants.totals.trip_new'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_pettycash->total_new = $totals->value;
                $eform_pettycash->save();
            }

            //get status id
            $status_model = StatusModel::where('status', $new_status)
                ->where('eform_id', config('constants.eforms_id.trip'))->first();
            $new_status = $status_model->id;
        }


        //FOR HOD
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_004')) {
            //update
            $form->config_status_id = $new_status;
            $form->authorised_by = $user->name;
            $form->authorised_staff_no = $user->staff_no;
            $form->authorised_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }

        //FOR FOR CHIEF HR
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_009')) {
            //update
            $form->config_status_id = $new_status;
            $form->station_manager = $user->name;
            $form->station_manager_staff_no = $user->staff_no;
            $form->station_manager_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }

        //FOR FOR CHIEF ACCOUNTANT
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_007')) {

            //update
            $form->config_status_id = $new_status;
            $form->accountant = $user->name;
            $form->accountant_staff_no = $user->staff_no;
            $form->accountant_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }

        //FOR FOR EXPENDITURE OFFICE FUNDS
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014') && $current_status == config('constants.trip_status.chief_accountant')) {

            //update
            $form->config_status_id = $new_status;
            $form->expenditure_office = $user->name;
            $form->expenditure_office_staff_no = $user->staff_no;
            $form->expenditure_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();

            //create records for the accounts associated with this Trip transaction
            for ($i = 0; $i < sizeof($request->credited_amount); $i++) {
                $des = "";
                $des = $des . " " . $request->account_items[$i] . ",";
                $des = "Petty-Cash Serial: " . $form->code . ", Claimant: " . $form->claimant_name . ', Items : ' . $des . ' Amount: ' . $request->credited_amount[$i] . '.';

                //[1] CREDITED ACCOUNT
                //[1A] - money
                $formAccountModel = PettyCashAccountModel::Create(
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        'creditted_amount' => $request->credited_amount[$i],
                        'account' => $request->credited_account[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        //'debitted_amount' => $request->debited_amount[$i],
                        'eform_trip_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                    ]);

                //[2] DEBITED ACCOUNT
                //[2A] - money
                $formAccountModel = PettyCashAccountModel::Create(
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        //'creditted_amount' => $request->credited_amount[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        'debitted_amount' => $request->debited_amount[$i],
                        'account' => $request->debited_account[$i],
                        'eform_trip_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                    ]);
            }
        }

        //FOR CLAIMANT - ACKNOWLEDGEMENT
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_002') && $current_status == config('constants.trip_status.funds_disbursement')) {
            //update
            $form->config_status_id = $new_status;
//          $form->profile = Auth::user()->profile_id;
            $form->profile = config('constants.user_profiles.EZESCO_007');
            $form->save();
        }

        //FOR FOR SECURITY
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_013')) {
            //update
            $form->config_status_id = $new_status;
            $form->security_name = $user->name;
            $form->security_staff_no = $user->staff_no;
            $form->security_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }

        //FOR FOR EXPENDITURE OFFICE - RECEIPT
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014') && $current_status == config('constants.trip_status.security_approved')) {

            //update
            $form->config_status_id = $new_status;
            $form->expenditure_office = $user->name;
            $form->expenditure_office_staff_no = $user->staff_no;
            $form->expenditure_date = $request->sig_date;
            $form->change = $request->change;
            $form->profile = Auth::user()->profile_id;
            $form->save();

            //check if there is need to create an account
            if ($request->change > 0) {
                $des = "";
                $des = $des . " " . $request->account_item . ",";
                $des = "Petty-Cash Serial: " . $form->code . ", Claimant: " . $form->claimant_name . ', Items : ' . $des . ' Amount: ' . $request->credited_amount . '.';


                //[1] CREDITED ACCOUNT
                //[1A] - money
                $formAccountModel = PettyCashAccountModel::Create(
                    [
                        'creditted_account_id' => $request->credited_account,
                        'creditted_amount' => $request->credited_amount,
                        'account' => $request->credited_account,
                        'debitted_account_id' => $request->debited_account,
                        //'debitted_amount' => $request->debited_amount,
                        'eform_trip_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                    ]);

                //[2] DEBITED ACCOUNT
                //[2A] - money
                $formAccountModel = PettyCashAccountModel::Create(
                    [
                        'creditted_account_id' => $request->credited_account,
                        //'creditted_amount' => $request->credited_amount,
                        'debitted_account_id' => $request->debited_account,
                        'debitted_amount' => $request->debited_amount,
                        'account' => $request->debited_account,
                        'eform_trip_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                    ]);
            }

            //update all accounts associated to this pettycash
            $formAccountModelList = PettyCashAccountModel::where('eform_trip_id', $form->id)->get();
            foreach ($formAccountModelList as $item) {
                $item->status_id = config('constants.trip_status.not_exported');
                $item->save();
            }

// upload the receipt files
            $files = $request->file('receipt');
            if ($request->hasFile('receipt')) {
                foreach ($files as $file) {
                    $filenameWithExt = $file->getClientOriginalName();
                    // Get just filename
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    //get size
                    $size = number_format($file->getSize() * 0.000001, 2);
                    // Get just ext
                    $extension = $file->getClientOriginalExtension();
                    // Filename to store
                    $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
                    // Upload File
                    $path = $file->storeAs('public/trip_receipt', $fileNameToStore);

                    //upload the receipt
                    $file = AttachedFileModel::Create(
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.trip'),
                        ]);
                }
            }
        }


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
                'config_eform_id' => config('constants.eforms_id.trip'),
                'eform_id' => $form->id,
                'created_by' => $user->id,
            ]);

        //send the email
        self::nextUsersSendMail($user->profile_id, $new_status, $form);

        //redirect home
        return Redirect::route('trip-home')->with('message', 'Trip ' . $form->code . ' for has been ' . $request->approval . ' successfully');

    }

    /**
     * Send Email to the Next Person/s who are supposed to work on the form next
     * @param $profile
     * @param $stage
     * @param $claim_staff
     */

    public function nextUsersSendMail($last_profile, $new_status, $form)
    {
        $user_array = [];
        $claimant_details = User::find($form->created_by);

        //message details
        $subject = 'Trip Needs Your Attention';
        $title = 'Trip Needs Your Attention';
        $message = 'This is to notify you that there is a Trip (' . $form->code . ') that needs your attention.
            Please login to e-ZESCO by clicking on the button below to take action on the voucher.';

        //[1]
        //THE LAST PROFILE
        $last_profile_who_worked = ProfileModel::find($last_profile);
        //get the next profiles to work from the last profile  PROFILE Permissions
        $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.trip'))
            ->where('profile', $last_profile_who_worked->code)
            ->first();


        //[2]
        //THE NEXT PROFILE
        $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next;
        //get the profile permissions associated with this next_profile_to_work
        $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.trip'))
            ->where('profile', $next_profile_to_work->code)
            ->get();

//        //If it is me who is actually supposed to work on the form, then find the next users who are supposed to work after me
//        if($user->profile_id ==  $next_profile_to_work->id ){
//            //[1A]
//            //THE LAST PROFILE
//            $last_profile_who_worked = ProfileModel::find($user->profile_id);
//            //get the next profiles to work from the last profile  PROFILE Permissions
//            $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.trip'))
//                ->where('profile', $last_profile_who_worked->code)
//                ->first();
//            //[2B]
//            //THE NEXT PROFILE
//            $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next ;
//            //get the profile permissions associated with this next_profile_to_work
//            $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.trip'))
//                ->where('profile', $next_profile_to_work->code)
//                ->get();
//        }

        //[3]
        //THE USERS
        //check if this next profile is for a claimant and if the Petty-Cash needs Acknowledgement
        if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
                $new_status == config('constants.trip_status.security_approved')) {
            $user = User::where('staff_no', $form->claimant_man_no)->first();
            $user_array[] = $user;

            //message details
            $subject = 'Trip Needs Your Attention';
            $title = 'Trip Needs Your Attention';
            $message = 'This is to notify you that there is a Trip (' . $form->code . ') that needs your attention.
            Please login to e-ZESCO by clicking on the button below to take action on the voucher.';
        } //check if this next profile is for a claimant and if the Petty-Cash is closed
        else if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $new_status == config('constants.trip_status.closed')) {
            //get user
            $user = User::where('staff_no', $form->claimant_man_no)->first();
            $user_array[] = $user;

            //message details
            $subject = 'Trip Closed Successfully';
            $title = 'Trip Closed Successfully';
            $message = ' Congratulation! This is to notify you that Trip ' . $form->code . ' has been closed successfully .
            Please login to e-ZESCO by clicking on the button below to view the voucher.';
        } // other wise get the users
        else {
            foreach ($profileAssignement as $item) {
                //get user
                $user = User::find($item->user_id);
                $user_array[] = $user;
            }
        }

        //[4]
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
        $list = PettyCashAccountModel::where('status_id', config('constants.trip_status.not_exported'))->get();

        //
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list
        ];
        //  dd($list);
        return view('eforms.trip.report')->with($params);
    }


    public function reportsExport(Request $request)
    {

        $fileName = 'PettyCash_Accounts.csv';
        $tasks = PettyCashAccountModel::where('status_id', config('constants.trip_status.not_exported'))->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('Code',
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
        );

        $callback = function () use ($tasks, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($tasks as $item) {

                //mark the item as exported
                $item->status_id = config('constants.trip_status.exported');
                $item->save();


                $row['Code'] = $item->trip->code;
                $row['Claimant'] = $item->trip->claimant_name;
                $row['Claim Date'] = $item->trip->claim_date;
                $row['Company'] = $item->company;
                $row['Business Unit'] = $item->trip->business_unit_code;
                $row['Cost Center'] = $item->trip->cost_center;
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


    public function charts(Request $request)
    {
        //get the accounts
        $list = Trip:: select(DB::raw('cost_centre, name_of_claimant, count(id) as total_forms , sum(total_payment) as forms_sum '))
            //->where('status', '<>', 1)
            ->groupBy('sig_of_claimant', 'name_of_claimant', 'cost_centre')
            ->get();

        // dd($list);

        //test
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list
        ];
        return view('eforms.trip.chart')->with($params);
        //  dd($request);
    }

    public function sync()
    {
        dd(111);
    }


}
