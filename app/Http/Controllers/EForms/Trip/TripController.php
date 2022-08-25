<?php

namespace App\Http\Controllers\EForms\Trip;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EForms\Subsistence\SubsistenceController;
use App\Mail\SendMail;
use App\Models\EForms\PettyCash\PettyCashAccountModel;
use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\EForms\Trip\Destinations;
use App\Models\EForms\Trip\DestinationsApprovals;
use App\Models\EForms\Trip\Invitation;
use App\Models\EForms\Trip\Trip;
use App\Models\Main\AttachedFileModel;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\StatusModel;
use App\Models\Main\TotalsModel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;


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
     * @return Response
     */
    public function index(Request $request, $value)
    {
        //get list of all Trip forms for today
        if ($value == "all") {
            $list = Trip::all();
            $category = "All";
        } else if ($value == "existing") {
            $list = Trip::all();
            $category = "Existing Trips";
        } else if ($value == "pending") {
            $list = Trip::where('config_status_id', config('constants.trip_status.new_trip'))
                ->orWhere('config_status_id', config('constants.trip_status.trip_authorised'))
                ->get();
            $category = "Opened";
        } else if ($value == config('constants.trip_status.new_trip')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.new_trip'))
                ->get();
            $category = "New Application";
        } else if ($value == config('constants.trip_status.closed')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.trip_closed'))
                ->get();
            $category = "Closed";
        } else if ($value == config('constants.trip_status.rejected')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.trip_rejected'))
                ->get();
            $category = "Rejected";
        } else if ($value == "needs_me") {
            $list =  HomeController::needsMeList();
            $category = "Needs My Attention";
        } else if ($value == "admin") {
            $list = Trip::all();
            $category = "All";
        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))->get();

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me ?? [],
            'list' => $list ?? [],
            'totals' => $totals ?? [],
            'pending' => $pending ?? 0,
            'category' => $category ?? "0",
        ];

        //return view
        return view('eforms.trip.list')->with($params);

    }


    /**
     * Display a listing of the resource for the admin.
     *
     * @return Response
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
     * @return Response
     */
    public function void(Request $request, $id)
    {
        //GET THE Trip MODEL
        $list = DB::select("SELECT * FROM eform_trip where id = {$id} ");
        $form = Trip::hydrate($list)->first();
        //get the status
        $current_status = $form->status->id;
        $new_status = 0;
        $user = auth()->user();
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
                'claimant_staff_no' => $user->staff_no,
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
     * @return Response
     */
    public function create()
    {
        $user = auth()->user();
        $units = \App\Http\Controllers\Main\HomeController::getMyViisbleUserUnits();
        $units->load('users_list');
        $destination_units = ConfigWorkFlow::select('id', 'user_unit_description', 'user_unit_code', 'user_unit_bc_code', 'user_unit_cc_code')
            ->where('user_unit_status', config('constants.user_unit_active'))
            ->get();
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        $users = User::where('con_st_code', config('constants.phris_user_active'))->get();

        //show the create form
        return view('eforms.trip.create')->with(compact('users', 'destination_units', 'units', 'user', 'totals_needs_me'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        //check if members were invited
        if ($request->destination_units == null) {
            return Redirect::back()->with('error', 'Please select the destination user-unit or user-units');
        }

        //get team email addresses
        $to = [];
        $names = "";
        //check if members were invited
        if ($request->users == null) {
            return Redirect::back()->with('error', 'Please Invite some members to subscribe to the trip');
        }

        $invited = sizeof($request->users);

        //[1]-get the logged in user
        $user = auth()->user();   //superior_code
        //[6]-generate the Trip unique code
        $code = self::randGenerator("TR", 1);
        $formModel = Trip::updateOrCreate(
            [
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'name' => $request->name,
                'destination' => $request->destination,
            ],
            [
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'hod_code' => $user->profile_job_code,
                'hod_unit' => $user->profile_unit_code,

                'code' => $code,
                'name' => $request->name,
                'description' => $request->description,
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
                    'trip_code' => $formModel->code,
                    'date_from' => $formModel->date_from,
                    'user_unit' => $userObj->user_unit_code,
                    'date_to' => $formModel->date_to,
                ],
                [
                    'man_no' => $userObj->staff_no,
                    'trip_id' => $formModel->id,
                    'user_unit' => $userObj->user_unit_code,
                    'trip_code' => $formModel->code,
                    'date_from' => $formModel->date_from,
                    'date_to' => $formModel->date_to,
                    'status_id' => config('constants.trip_status.pending'),
                ]
            );
        }

        /** save user units destinations */
        //add hods email addresses
        foreach ($request->destination_units as $destination_unit) {

            // insert into invitations table
            $dest = Destinations::UpdateOrCreate(
                [
                    'user_unit_code' => $destination_unit,
                    'trip_code' => $formModel->code,
                    'trip_id' => $formModel->id,
                    'date_from' => $formModel->date_from,
                    'date_to' => $formModel->date_to,
                ],
                [
                    'user_unit_code' => $destination_unit,
                    'trip_code' => $formModel->code,
                    'trip_id' => $formModel->id,
                    'date_from' => $formModel->date_from,
                    'date_to' => $formModel->date_to,
                ]
            );
        }

        /** upload attached files */
        //upload the receipt files
        $files = $request->file('trip_files');
        if ($request->hasFile('trip_files')) {
            foreach ($files as $file) {
                $filenameWithExt = preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName());
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                //get size
                $size = number_format($file->getSize() * 0.000001, 2);
                // Get just ext
                $extension = $file->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
                // Upload File
                $path = $file->storeAs('public/trip_files', $fileNameToStore);

                //upload the receipt
                $file = AttachedFileModel::updateOrCreate(
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $formModel->code,
                        'form_type' => config('constants.eforms_id.trip'),
                        'file_type' => config('constants.file_type.trip')
                    ],
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $formModel->code,
                        'form_type' => config('constants.eforms_id.trip'),
                        'file_type' => config('constants.file_type.trip')
                    ]
                );
            }
        }

        //prepare details
        $details = [
            'name' => $names,
            'url' => 'trip.home',
            'subject' => "New Trip To {$request->destination}",
            'title' => "New Trip Needs Your Attention",
            'body' => "Please note that {$user->name} has successfully created a Trip to {$request->destination} with the following details:
                    <pre>&nbsp;&nbsp;serial: {$formModel->code}</pre>
                    <pre>&nbsp;&nbsp;destination: {$formModel->destination}</pre>
                    <pre>&nbsp;&nbsp;from: {$formModel->date_from}</pre>
                    <pre>&nbsp;&nbsp;to: {$formModel->date_to}</pre>
                    <pre>&nbsp;&nbsp;description: {$formModel->description} and</pre>
                    <pre>&nbsp;&nbsp;Status:{$formModel->status->name}.</pre>  <br>
            To subscribe to this trip, kindly click on the button below to login to E-ZESCO and raise subsistence for the trip.
            <br><br> "
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));

        //log the activity
//        ActivityLogsController::store($request, "Creating of Trip", "create", " trip created", $formModel->id);
        //return the view
        return Redirect::route('trip.home')->with('message', 'Trip Details for ' . $formModel->code . ' have been Created successfully');

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
        } catch (Exception $exception) {
            $total = 0;
        }

        if ($total < 1) {
            return $random;
        } else {
            self::randGenerator($head, $value);
        }
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
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $user = auth()->user();
        //GET THE Trip MODEL if you are an admin
        if (auth()->user()->type_id == config('constants.user_types.developer')) {
            $list = DB::select("SELECT * FROM eform_trip where id = {$id} ");
            $form = Trip::hydrate($list)->first();
        } else {
            //find the Trip with that id
            $form = Trip::find($id);
        }
        $form->load('members', 'members.user', 'members.destinations', 'user_unit');


        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.trip'))->get();

        //get the list of users who are supposed to work on the form
        $user_array = self::findMyNextPerson($form, auth()->user()->user_unit, auth()->user());

        //all invitations
        $all_inv = Invitation::where('trip_code', $form->code)->get();
        $all_inv->load('members');

        //my invitation
        $list_inv = $all_inv->where('man_no', $user->staff_no)->first();

        //check for pending
        //[1B] check pending forms for me before i apply again
        $pending = SubsistenceModel::where('absc_absent_to', '>=', $form->date_from)
            ->where('absc_absent_from', '<=', $form->date_from)
            ->whereRaw("claimant_staff_no = '".$user->staff_no."'
           AND ( config_status_id = ".config('constants.subsistence_status.hod_approved')."
                OR config_status_id = ".config('constants.subsistence_status.station_mgr_approved')."
                OR config_status_id = ".config('constants.subsistence_status.hr_approved')."
                OR config_status_id = ".config('constants.subsistence_status.chief_accountant')."
                OR config_status_id = ".config('constants.subsistence_status.funds_disbursement')."
                OR config_status_id = ".config('constants.subsistence_status.funds_acknowledgement')."
                OR config_status_id = ".config('constants.subsistence_status.destination_approval')."
                OR config_status_id = ".config('constants.trip_status.hr_approved')."
                OR config_status_id = ".config('constants.trip_status.trip_authorised')."
                OR config_status_id = ".config('constants.trip_status.hr_approved_trip')."
                OR config_status_id = ".config('constants.trip_status.hod_approved_trip')."
                OR config_status_id = ".config('constants.exported')."
                OR config_status_id = ".config('constants.uploaded')."
                OR config_status_id = ".config('constants.subsistence_status.pre_audited')."
                OR config_status_id = ".config('constants.subsistence_status.dr_approved')."
                ) ")

            // config('constants.subsistence_status.dr_approved')
//            ->where('config_status_id', '=', config('constants.subsistence_status.hod_approved'))
//            ->orWhere('config_status_id', '=', config('constants.subsistence_status.station_mgr_approved'))
//            ->orWhere('config_status_id', '=', config('constants.subsistence_status.station_mgr_approved'))
//            ->orWhere('config_status_id', '=', config('constants.subsistence_status.hr_approved'))
//            ->orWhere('config_status_id', '=', config('constants.trip_status.hr_approved'))
//            ->orWhere('config_status_id', '=', config('constants.trip_status.trip_authorised'))
//            ->orWhere('config_status_id', '=', config('constants.trip_status.hr_approved_trip'))
//            ->orWhere('config_status_id', '=', config('constants.trip_status.hod_approved_trip'))
//            ->orWhere('config_status_id', '=', config('constants.subsistence_status.chief_accountant'))
//            ->orWhere('config_status_id', '=', config('constants.subsistence_status.funds_disbursement'))
//            ->orWhere('config_status_id', '=', config('constants.subsistence_status.funds_acknowledgement'))
//            ->orWhere('config_status_id', '=', config('constants.subsistence_status.destination_approval'))
//            ->orWhere('config_status_id', '=', config('constants.exported'))
//            ->orWhere('config_status_id', '=', config('constants.uploaded'))
//            ->orWhere('config_status_id', '=', config('constants.subsistence_status.pre_audited'))

            ->count();

      //  dd($pending);

        //[1B] check pending forms for me before i apply again
        $pendingb = SubsistenceModel::where('trip_id', $form->id)
            ->where('claimant_staff_no', $user->staff_no)
            ->count();

        $pending = $pending + $pendingb;

      //  dd($pending);

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //return view
        return view('eforms.trip.show')->with(compact('pending', 'list_inv', 'user', 'user_array', 'totals_needs_me', 'form', 'all_inv', 'approvals'));

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
     * @return Response
     */
    public function edit(Trip $trip)
    {

        dd($trip);
        return view('eforms.trip.edit')->with(compact('trip' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function approve(Request $request)
    {
        //GET THE Trip MODEL
        $trip = Trip::find($request->id);
        $current_status = $trip->status->id;
        $new_status = 0;
        $user = auth()->user();

        //HANDLE SUBSCRIPTION
        if ($request->approval == config('constants.approval.subscribe')) {
            return SubsistenceController::create($trip);
        }

    }

    public function membershipApprove(Request $request, Trip $trip)
    {

        $id = $request->membership;
        $list = DB::select("SELECT * FROM eform_subsistence where id = {$id} ");
        $subsistence = SubsistenceModel::hydrate($list)->first();
        //
        $current_status = $subsistence->config_status_id;
        $new_status = 0;
        $insert_reasons = true;
        $user = auth()->user();
        $profile = " ";
        $member = User::find($subsistence->created_by);


        //FOR APPROVE
        if (
            $user->profile_id == config('constants.user_profiles.EZESCO_004')
            && $current_status == config('constants.trip_status.accepted')
        ) {
            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.trip_status.cancelled');
                $profile = config('constants.owner');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.trip_status.trip_rejected');
                $profile = config('constants.owner');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.trip_status.hod_approved_trip');
                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_009'));
            } else {
                $new_status = config('constants.trip_status.accepted');
                $insert_reasons = false;
            }
            //update
            $subsistence->config_status_id = $new_status;
            $subsistence->initiator_name = $user->name;
            $subsistence->initiator_staff_no = $user->staff_no;
            $subsistence->initiator_date = $request->sig_date;
            $subsistence->save();
        }

        //FOR HR
        if (
            $user->profile_id == config('constants.user_profiles.EZESCO_009')
            && $current_status == config('constants.trip_status.hod_approved_trip')
        ) {
            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.trip_status.cancelled');
                $profile = config('constants.owner');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.trip_status.trip_rejected');
                $profile = config('constants.owner');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
//                $new_status = config('constants.trip_status.hr_approved_trip');   // removed snr manager
//                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_015'));
                $new_status = config('constants.trip_status.trip_authorised');
                $profile = config('constants.owner');
            } else {
                $new_status = config('constants.trip_status.hod_approved_trip');
                $insert_reasons = false;
            }
            //update
            $subsistence->config_status_id = $new_status;
//            $membership->hrm_name = $user->name;
//            $membership->hrm_staff_no = $user->staff_no;
//            $membership->hrm_date = $request->sig_date;
            $subsistence->save();
        } //FOR AUTHORIZER   // remove snr manager stage
        elseif (
            $user->profile_id == config('constants.user_profiles.EZESCO_015')
            && $current_status == config('constants.trip_status.hod_approved_trip')
        ) {
            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.trip_status.cancelled');
                $profile = config('constants.owner');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.trip_status.trip_rejected');
                $profile = config('constants.owner');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.trip_status.trip_authorised');
                $profile = config('constants.owner');
            } else {
                $new_status = config('constants.trip_status.hod_approved_trip');
                $insert_reasons = false;
            }
            //update
            $subsistence->config_status_id = $new_status;
            $subsistence->save();

        } //FOR AUTHORIZER
        elseif (
            $user->profile_id == config('constants.user_profiles.EZESCO_004')
            && $current_status == config('constants.subsistence_status.destination_approval')
        ) {
            //my user units
            $my_units = \App\Http\Controllers\Main\HomeController::getUserResponsibleUnits($user)->pluck('user_unit_code')->toArray();

            //check for the one in destination approvals
            $subsistence->load('destinations');
            $destinations_approvals = $subsistence->destinations;

            //the ones am supposed to work on
            $approvals_lists = $destinations_approvals->whereIn('user_unit_code', $my_units);

            //if there are no units to work on for you
            if (sizeof($approvals_lists) < 1) {
                return Redirect::route('subsistence.home')->with('error', 'User-Unit not properly aligned');
            }

            //loop through and approve them
            foreach ($approvals_lists as $approvals_list) {
                $approvals_list->created_by = $user->id;
                $approvals_list->dest_comment = $request->reason;
                $approvals_list->date_from = $request->date_from;
                $approvals_list->date_to = $request->date_to;
                $approvals_list->save();
            }

            //reason
            $request->reason = "Arrived on " . $request->date_from . "  and Left on  " . $request->date_to . " , Reasons/Comment : " . $request->reason;
            $insert_reasons = true;

            //status
            if ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.destination_approval');
                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));
            } else {
                $new_status = config('constants.subsistence_status.destination_approval');
                $insert_reasons = false;
                $profile = config('constants.owner');
            }

            //check for the remaining un confirmed destinations
            $count_dest_approvals = $destinations_approvals->whereNull("created_by");

            //choose to update the
            if (($count_dest_approvals->count()) == 0) {

                //highest date
                $highest_from_date = $approvals_lists->sortByDesc('date_from')->first();
                $highest_to_date = $approvals_lists->sortByDesc('date_to')->first();
                //

//                dd($highest_from_date->date_from);
                $new_status = config('constants.subsistence_status.await_audit');
                $subsistence->config_status_id = $new_status;
                $subsistence->date_left = date("d-m-Y", strtotime($highest_from_date->date_from));
                $subsistence->date_arrived = date("d-m-Y", strtotime($highest_to_date->date_to));
                //replace with the latest
                $subsistence->closed_by_name = $user->name;
                $subsistence->closed_by_staff_no = $user->staff_no;
                $subsistence->closed_by_date = $request->sig_date;
                //
                $profile = config('constants.owner');
            } else {
                foreach ($count_dest_approvals as $count_dest_approval) {
                    //send the email
                    self::nextUserSendMail($new_status, $count_dest_approval->user_unit_code, $profile, $subsistence);
                }
            }


        }

        //save reason
        if ($insert_reasons) {
            $reason = EformApprovalsModel::Create(
                [
                    'profile' => $user->profile_id,
                    'claimant_staff_no' => $subsistence->claimant_staff_no,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $request->reason,
                    'action' => $request->approval,
                    'current_status_id' => $current_status,
                    'action_status_id' => $new_status,
                    'config_eform_id' => config('constants.eforms_id.trip'),
                    'eform_id' => $trip->id,
                    'eform_code' => $trip->code,
                    'created_by' => $user->id,
                ]);

            //record approval in
            $reason2 = EformApprovalsModel::Create(
                [
                    'profile' => $user->profile_id,
                    'claimant_staff_no' => $subsistence->claimant_staff_no,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $request->reason,
                    'action' => $request->approval,
                    'current_status_id' => $current_status,
                    'action_status_id' => $new_status,
                    'config_eform_id' => config('constants.eforms_id.subsistence'),
                    'eform_id' => $subsistence->id,
                    'eform_code' => $subsistence->code,
                    'created_by' => $user->id,
                ]);

            $subsistence->config_status_id = $new_status;
            $subsistence->save();

            if ($current_status == config('constants.subsistence_status.destination_approval')) {

            } else {
                //send the email
                self::nextUserSendMail($new_status, $subsistence->user_unit_code, $profile, $subsistence);
            }

        }

        //redirect home
        return Redirect::route('trip.home')->with('message', 'Trip Form to ' . $subsistence->absc_visited_place . ' for ' . $subsistence->claimant_name . ' has been ' . $request->approval . ' successfully');
    }


    /**
     * Send Email to the Next Person/s who are supposed to work on the form next
     * @param $profile
     * @param $stage
     * @param $claim_staff
     */

    public function nextUserSendMail($new_status, $user_unit_code, $profile, $form)
    {
        $form->load('trip');
        $names = "";
        $claimant_details = User::find($form->created_by);

        if ($profile == config('constants.owner')) {
            $user_array = User::where('id', $form->created_by)->get();
        } else {
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit_code, $profile);
        }

        $form_state = StatusModel::find($new_status);
        //check if this next profile is for a claimant and if the Trip needs Acknowledgement
        if ($new_status == config('constants.trip_status.accepted')
            || $new_status == config('constants.trip_status.hod_approved_trip')
            || $new_status == config('constants.trip_status.hr_approved_trip')
            || $new_status == config('constants.trip_status.trip_authorised')
        ) {
            //message details
            $subject = 'Trip Form Needs Your Attention';
            $title = 'Trip to ' . $form->trip->name;
            $message = 'This is to notify you that there is a Trip Form (' . $form->trip->code . ') raised by ' . $claimant_details->name . ', that needs your attention.
            <br>Please login to e-ZESCO by clicking on the button below to take action on the voucher.<br>The form is currently at <em>' . $form_state->name  ?? $form->status->name . ' stage </em>';
        } //check if this next profile is for a claimant and if the Trip is closed
        else if ($new_status == config('constants.trip_status.closed')) {
            $names = $names . '<br>' . $claimant_details->name;
            //message details
            $subject = 'Trip Form Closed Successfully';
            $title = 'Trip to ' . $form->trip->name;
            $message = 'This is to notify you that there is a Trip Form (' . $form->trip->code . ') raised by ' . $claimant_details->name . ', has been closed successfully.
            <br>Please login to e-ZESCO by clicking on the button below to take action on the voucher.<br>The form is currently at <em>' . $form_state->name ?? $form->status->name  . ' stage </em>';

        } // other wise get the users
        else {
            //message details
            $subject = 'Trip Form Needs Your Attention';
            $title = 'Trip to ' . $form->trip->name;
            $message = 'This is to notify you that there is a Trip Form (' . $form->trip->code . ') raised by ' . $claimant_details->name . ', has been closed successfully.
            <br>Please login to e-ZESCO by clicking on the button below to take action on the voucher.<br>The form is currently at <em>' . $form_state->name ?? $form->status->name . ' stage </em>';
        }

        /** send email to supervisor */
        $to = [];
        //add hods email addresses
        foreach ($user_array as $item) {
            //use the pay point
//            $to[] = ['email' => $item->email, 'name' => $item->name];
//            $to[] = ['email' => $claimant_details->email, 'name' => $claimant_details->name];
            $names = $names . '<br>' . $item->name;
        }

        //  dd($user_array);
        $to[] = ['email' => 'nshubart@zesco.co.zm', 'name' => 'Shubart Nyimbili'];

        //prepare details
        $details = [
            'name' => $names,
            'url' => 'trip.home',
            'subject' => $subject,
            'title' => $title,
            'body' => $message
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));
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


    public function invite(Request $request, Trip $form)
    {

        if ($request->users == null) {
            return Redirect::route('trip.home')->with('error', 'Sorry no users were selected for this trip.');
        }

        //get team email addresses
        $to = [];
        $names = "";
        $invited = 0;

        /** send email to the invited */
        //add hods email addresses
        foreach ($request->users as $user_id) {
            $userObj = User::find($user_id);
            $invited++;

            $to[] = ['email' => $userObj->email, 'name' => $userObj->name];
            $names = $names . '<br>' . $userObj->name;

            // insert into invitations table
            $invitation = Invitation::UpdateOrCreate(
                [
                    'man_no' => $userObj->staff_no,
                    'trip_code' => $form->code,
                    'date_from' => $form->date_from,
                    'user_unit' => $userObj->user_unit_code,
                    'date_to' => $form->date_to,
                ],
                [
                    'man_no' => $userObj->staff_no,
                    'trip_id' => $form->id,
                    'user_unit' => $userObj->user_unit_code,
                    'trip_code' => $form->code,
                    'date_from' => $form->date_from,
                    'date_to' => $form->date_to,
                    'status_id' => config('constants.trip_status.pending'),
                ]
            );
        }

        $form->invited = $invited + $form->invited;

        $form->save();
        //prepare details
        $details = [
            'name' => $names,
            'url' => 'trip.home',
            'subject' => "New Trip To {$form->destination}",
            'title' => "New Trip Needs Your Attention",
            'body' => "Please note that {$userObj->name} has successfully created a Trip to {$form->destination} with the following details:
                    <pre>&nbsp;&nbsp;serial: {$form->code}</pre>
                    <pre>&nbsp;&nbsp;destination: {$form->destination}</pre>
                    <pre>&nbsp;&nbsp;from: {$form->date_from}</pre>
                    <pre>&nbsp;&nbsp;to: {$form->date_to}</pre>
                    <pre>&nbsp;&nbsp;description: {$form->description} and</pre>
                    <pre>&nbsp;&nbsp;Status:{$form->status->name}.</pre>  <br>
            To subscribe to this trip, kindly click on the button below to login to E-ZESCO and raise subsistence for the trip.
            <br><br> "
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));

        return Redirect::route('trip.home')->with('message', 'Users have been invited to the trip successfully.');

    }


    public function search(Request $request)
    {
        $search = strtoupper($request->search);
        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $list = DB::select("SELECT * FROM eform_trip
              where code LIKE '%{$search}%'
              or name LIKE '%{$search}%'
              or destination LIKE '%{$search}%'
              or hod_code LIKE '%{$search}%'
              or initiator_staff_no LIKE '%{$search}%'
              or config_status_id LIKE '%{$search}%'
            ");
            $list = Trip::hydrate($list);
        } else {

            //find the Subsistence with that id
            $list = Trip::
            where('code', 'LIKE', "%{$search}%")
                ->orWhere('name', 'LIKE', "%{$search}%")
                ->orWhere('destination', 'LIKE', "%{$search}%")
                ->orWhere('hod_code', 'LIKE', "%{$search}%")
                ->orWhere('initiator_staff_no', 'LIKE', "%{$search}%")
                ->orWhere('config_status_id', 'LIKE', "%{$search}%")
                ->paginate(50);
        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.trip'))->get();
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();
        $category = "Search Results";

        //list of statuses
        $statuses = StatusModel::where('eform_id', config('constants.eforms_id.trip'))->get();


        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list,
            'totals' => $totals,
            'pending' => $pending,
            'category' => $category,
            'statuses' => $statuses,
        ];

        //return view
        return view('eforms.trip.list')->with($params);
    }




}
