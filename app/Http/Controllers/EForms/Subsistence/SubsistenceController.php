<?php

namespace App\Http\Controllers\EForms\Subsistence;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Mail\SendMail;
use App\Models\EForms\Subsistence\SubsistenceAccountModel;
use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\EForms\Trip\Destinations;
use App\Models\EForms\Trip\DestinationsApprovals;
use App\Models\EForms\Trip\Invitation;
use App\Models\EForms\Trip\Trip;
use App\Models\Main\AccountsChartModel;
use App\Models\Main\AttachedFileModel;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\StatusModel;
use App\Models\Main\TaxModel;
use App\Models\Main\TotalsModel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Mockery\CountValidator\Exception;


class SubsistenceController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // Store a piece of data in the session...
        session(['eform_id' => config('constants.eforms_id.subsistence')]);
        session(['eform_code' => config('constants.eforms_name.subsistence')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public static function create(Trip $trip, Invitation $invitation)
    {
        $user = auth()->user();
        $projects = ProjectsModel::all();
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        $cost_centers = ConfigWorkFlow::orderBy('user_unit_description', 'ASC')->get();
        $trip->load('user_unit');

        $previous_subsistence = SubsistenceModel::where('claimant_staff_no', $user->staff_no)->latest('created_at')->first();

        //dd($previous_subsistence);

        //data to send to the view
        $profile_delegated = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('delegated_to', $user->id)
            ->where('config_status_id', config('constants.active_state'))->first();

        if ($profile_delegated) {
            $delegated_user = User::with(['grade'])->find($profile_delegated->created_by);

            if ($profile_delegated->created_at->diffInDays(now()) > 14) {
                $user->grade_id = $delegated_user->grade_id;
            }
        }
        $message = 'Please complete this Subsistence Form in order to complete your subscription to the ' . $trip->destination . ' trip.';

        //show the create form
        return view('eforms.subsistence.create', compact(
            'totals_needs_me',
            'projects',
            'user',
            'trip',
            'message',
            'previous_subsistence',
            'cost_centers'
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, $value)
    {

        //get list of all Subsistence forms for today
        if ($value == "all") {
            $list = SubsistenceModel::orderBy('code')->paginate(50);
            $category = "All";
        } else if ($value == "pending") {
            $list = SubsistenceModel::
            where('config_status_id', '=', config('constants.subsistence_status.hod_approved'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.station_mgr_approved'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.hr_approved'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.hr_approved'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.hod_approved'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.hod_approved_trip'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.trip_authorised'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.chief_accountant'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.await_audit'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.funds_disbursement'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.funds_acknowledgement'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.destination_approval'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.pre_audited'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.dr_approved'))
                ->orWhere('config_status_id', '=', config('constants.exported'))
                ->orWhere('config_status_id', '=', config('constants.uploaded'))
                ->orderBy('code')->paginate(50);


            $category = "Opened";
        } else if ($value == config('constants.subsistence_status.new_application')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.new_application'))
                ->orderBy('code')->paginate(50);
            $category = "New Application";
        } else if ($value == config('constants.subsistence_status.closed')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.closed'))
                ->orderBy('code')->paginate(50);
            $category = "Closed";
        } else if ($value == config('constants.subsistence_status.rejected')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.rejected'))
                ->orWhere('config_status_id', '=', config('constants.export_failed'))
                ->orderBy('code')->paginate(50);
            $category = "Rejected";
        } else if ($value == config('constants.subsistence_status.cancelled')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status. cancelled'))
                ->orderBy('code')->paginate(50);
            $category = "Cancelled";
        } else if ($value == config('constants.subsistence_status.void')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.void'))
                ->orderBy('code')->paginate(50);
            $category = "Void";
        } else if ($value == config('constants.subsistence_status.audit_approved')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.audit_approved'))
                ->orderBy('code')->paginate(50);
            $category = "Audited";
        } else if ($value == config('constants.subsistence_status.queried')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.queried'))
                ->orderBy('code')->paginate(50);
            $category = "Queried";
        } else if ($value == config('constants.exported')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.exported'))
                ->orderBy('code')->paginate(50);
            $category = "Payment Processing in FMS";
        } else if ($value == "needs_me") {
            $list = HomeController::needsMeList();
            $category = "Needs My Attention";
        } else if ($value == "admin") {
            $list = SubsistenceModel::where('config_status_id', 0)
                ->orderBy('code')->paginate(50);
        }

        //count all
        // $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))->get();

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();

        //list of statuses
        //  $statuses = StatusModel::where('eform_id', config('constants.eforms_id.subsistence'))->get();
        $statuses = StatusModel::orderBy('eform_id')->get();

        //return view
        return view('eforms.subsistence.list')->with(compact(
            'statuses', 'list', 'totals_needs_me', 'pending', 'category', 'value'));

    }

    /**
     * Display a listing of the resource for the admin.
     *
     * @return \Illuminate\HtFtp\Response
     */
    public function records(Request $request, $value)
    {
        //get list of all Subsistence forms for today
        if ($value == "all") {
            $list = DB::table('eform_subsistence')
                ->select('eform_subsistence.*', 'config_status.name', 'config_status.html')
                ->join('config_status', 'eform_subsistence.config_status_id', '=', 'config_status.id')
                ->paginate(50);

            //   dd($list);
            $category = "All Records";
            //  dd($list);
        } else if ($value == "pending") {
            $list = SubsistenceModel::where('config_status_id', '>', config('constants.subsistence_status.new_application'))
                ->where('config_status_id', '<', config('constants.subsistence_status.closed'))
                ->orderBy('code')->paginate(50);
            $category = "Opened";

        } else if ($value == config('constants.subsistence_status.new_application')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.new_application'))
                ->orderBy('code')->paginate(50);
            $category = "New Application";

        } else if ($value == config('constants.subsistence_status.closed')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.closed'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.audit_approved'))
                ->orderBy('code')->paginate(50);
            $category = "Closed";

        } else if ($value == config('constants.subsistence_status.rejected')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.rejected'))
                ->orderBy('code')->paginate(50);
            $category = "Rejected";

        } else if ($value == "needs_me") {
            $list = HomeController::needsMeList();
            $category = "Needs My Attention";
        } else if ($value == "admin") {
        }

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();


        //list of statuses
        $statuses = StatusModel::where('eform_id', config('constants.eforms_id.subsistence'))->get();


        //return view
        return view('eforms.subsistence.records')->with(compact('statuses', 'totals_needs_me', 'list', 'pending', 'category'));

    }

    /**
     * Mark the form as void.
     *
     * @return Response
     */
    public function void(Request $request, $id)
    {
        //GET THE Subsistence MODEL
        $list = DB::select("SELECT * FROM eform_subsistence where id = {$id} ");
        $form = SubsistenceModel::hydrate($list)->first();
        //get the status
        $current_status = $form->status->id;
        $user = Auth::user();

        //HANDLE VOID REQUEST
        $new_status = config('constants.subsistence_status.void');

        //update the accounts
        $affected_accounts = DB::table('eform_subsistence_account')
            ->where('eform_subsistence_id', $id)
            ->update(['status_id' => $new_status]);

        //update the form status
        $form->config_status_id = $new_status;
        $form->save();

        //save reason
        $reason = EformApprovalsModel::Create(
            [
                'profile' => $user->profile_id,
                'claimant_staff_no' => $form->claimant_staff_no,
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

        //redirect home
        return Redirect::back()->with('message', 'Subsistence ' . $form->code . ' for has been marked as Void successfully');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request, Trip $trip)
    {
        //[1] get the logged in user
        $user = Auth::user();   //superior_code
        $cost_center = ConfigWorkFlow::find($request->cost_center);
        $error = false;
        $trip->load('user');

        //what telling
        $date = Carbon::parse($trip->date_from);
        $now = Carbon::parse($trip->date_to);
        $diff = $date->diffInDays($now);

        //control :
        //do not allow more than 7 days
        if ($diff > config('constants.sub_max_days')) {
            return Redirect::route('trip.home')->with('error', 'Sorry, You can not raise a new Subsistence Claim for the ' . $trip->destination . ', because the trip has exceeded the maximum number of days.');
        }

        //[1B] check pending forms for me before i apply again
        $pending = SubsistenceModel::where('absc_absent_to', '>=', $date)
            ->where('absc_absent_from', '<=', $date)
            ->where('claimant_staff_no', $user->staff_no)
            ->where('config_status_id', config('constants.subsistence_status.reject'))
            ->orWhere('config_status_id', config('constants.subsistence_status.destination_approval'))
            ->count();
        $pending = $pending;
        // check for overlapping days
        if ($pending >= 1) {
            //return with error msg
            return Redirect::route('trip.home')->with('error', 'Sorry, You can not raise a new Subsistence Claim for the' . $trip->destination . ' trip because you already have an open Subsistence Claim in the the specified');
        }


        //[1B] check pending forms for me before i apply again
        $pendingb = SubsistenceModel::where('trip_id', $trip->id)
            ->where('claimant_staff_no', $user->staff_no)
            ->count();

        $pending = $pendingb;
        // check for overlapping days
        if ($pending >= 1) {
            //return with error msg
            return Redirect::route('trip.home')->with('error', 'Sorry, You can not raise a new Subsistence Claim for the' . $trip->destination . ' trip because you already have an open Subsistence Claim for this trip.');
        }

        //[2A] find my code superior
        $my_hods = self::findMyNextPerson(config('constants.trip_status.new_trip'), $user->user_unit, $user);

        if (empty($my_hods)) {
            //prepare details
            $details = [
                'name' => "Team",
                'url' => 'subsistence.home',
                'subject' => "subsistence.Voucher Path Configuration Needs Your Attention",
                'title' => "Path Configuration Not Defined For {$user->name}",
                'body' => "Please note that {$user->name} with Staff Number {$user->staff_no} and Phone/Extension {$user->phone}, managed to submit or raise new Subsistence Claim.
                     <br>But the voucher path is not completely configured. Please confirm that this is so and take action to correct this as soon as possible.
                     <br><br>
                     <b> Path for {$user->user_unit->user_unit_code} user-unit </b><br>
                   1: HOD -> {$user->user_unit->hod_code} : {$user->user_unit->hod_unit}  <br>
                   2: HR/Station Manager ->  {$user->user_unit->hrm_code} : {$user->user_unit->hrm_unit} <br>
                   3: Account -> {$user->user_unit->ca_code} : {$user->user_unit->ca_unit}  <br>
                   4: Expenditure -> {$user->user_unit->expenditure_code} : {$user->user_unit->expenditure_unit}  <br>
                   5: Security -> {$user->user_unit->security_code} : {$user->user_unit->security_unit}  <br>
                   Please assign the correct position code and position user-unit for {$user->user_unit->user_unit_code}. <br>
                <br>You can update the details by clicking on 'Subsistence Work Flow' menu, then search for {$user->user_unit->user_unit_code}
                 and 'Edit' to update the correct details . <br> <br>
                 Else the HOD has not registered or assigned the correct profile yet.
                 "
            ];

            //send emails
            $to = config('constants.team_email_list');
            // $mail_to_is = Mail::to($to)->send(new SendMail($details));

            $error = true;
            //return with error msg
        }

        // $absent_amount = $diff * $request->absc_allowance_per_night;
        // $total_amount = ($absent_amount + $request->trex_total_attached_claim) - $request->trex_deduct_advance;

        //generate the Subsistence unique code
        $code = self::randGenerator("SUB", 1);


        $departmental = false;
        //check if the next if cost center of the TRIP and SUBSISTENCE IS THE SAME

        $user = Auth::user();   //superior_code
        $cost_center = ConfigWorkFlow::find($request->cost_center);
        $user->load('grade');

        //CHECK TO SEE IF YOU ARE SNR MANAGEMENT
        if($user->grade->category_id == config('constants.grade_category.senior_mgt')){
            $status = config('constants.trip_status.accepted');
        }else{
            if ($user->user_unit->user_unit_code == $cost_center->user_unit_code) {
                $status = config('constants.subsistence_status.hod_approved');
                $departmental = true;
            } else {
                $status = config('constants.trip_status.accepted');
            }
        }




        //raise the voucher
        $formModel = SubsistenceModel::firstOrCreate(
            [
                'ref_no' => $request->ref_no,
                'config_status_id' => $status,
                'grade' => $user->grade->name,
                'ext_no' => $user->phone,
                'claim_date' => $request->date,
                'claimant_name' => $user->name,
                'claimant_staff_no' => $user->staff_no,
                'absc_absent_from' => $date,
                'absc_absent_to' => $now,
                'absc_visited_reason' => $request->absc_visited_reason,
                'absc_visited_place' => $request->absc_visited_place,
                'absc_allowance_per_night' => $request->absc_allowance_per_night,
                'trip_id' => $trip->id,
            ],
            [
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
                'claimant_unit_code' => $user->user_unit->user_unit_code,

                'code' => $code,
                'ref_no' => $request->ref_no,
                'config_status_id' => $status,
                'type' => config('constants.file_type.subsistence'),
                'trip_id' => $trip->id,

                'absc_absent_from' => $date,
                'absc_absent_to' => $now,
                'absc_visited_reason' => $request->absc_visited_reason,
                'absc_visited_place' => $request->absc_visited_place,
                'absc_allowance_per_night' => $request->absc_allowance_per_night,

                'trex_total_attached_claim' => $request->trex_total_attached_claim,
                'trex_deduct_advance_amount' => $request->trex_deduct_advance,

                'allocation_code' => $request->allocation_code,

                'created_by' => $user->id,
                'profile' => Auth::user()->profile_id,
                'code_superior' => $user->user_unit->user_unit_superior,
                //cost center
                'cost_center' => $cost_center->user_unit_cc_code,
                'business_unit_code' => $cost_center->user_unit_bc_code,
                'user_unit_code' => $cost_center->user_unit_code,
                'user_unit_id' => $cost_center->id,
            ]
        );

        //accept invitation
        $list_inv = Invitation::where('man_no', $user->staff_no)
            ->where('trip_code', $trip->code)
            ->first();

        $list_inv->status_id = $status;
        $list_inv->subsistence_id = $formModel->id;
        $list_inv->subsistence_code = $code;
        $list_inv->save();

        //destination approvals
        $visits = Destinations::where('trip_code', $trip->code)->get();
        foreach ($visits as $visit) {
            $model_visit = DestinationsApprovals::updateOrCreate(
                [
                    'trip_id' => $trip->id,
                    'trip_code' => $visit->trip_code,
                    'subsistence_id' => $formModel->id,
                    'subsistence_code' => $formModel->code,
                    'user_unit_code' => $visit->user_unit_code,

                ],
                [
                    'trip_id' => $trip->id,
                    'trip_code' => $visit->trip_code,
                    'subsistence_id' => $formModel->id,
                    'subsistence_code' => $formModel->code,
                    'user_unit_code' => $visit->user_unit_code,
                ]
            );
        }

        /** upload attached files */
        //upload the receipt files
        $files = $request->file('subsistence');
        if ($request->hasFile('subsistence')) {
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
                $path = $file->storeAs('public/subsistence_files', $fileNameToStore);

                //upload the receipt
                $file = AttachedFileModel::updateOrCreate(
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $formModel->code,
                        'form_type' => config('constants.eforms_id.subsistence'),
                        'file_type' => config('constants.file_type.subsistence')
                    ],
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $formModel->code,
                        'form_type' => config('constants.eforms_id.subsistence'),
                        'file_type' => config('constants.file_type.subsistence')
                    ]
                );
            }
        }

        //reason
        if ($departmental) {
            //save reason for sub
            $reason = EformApprovalsModel::updateOrCreate(
                [
                    'profile' => $user->profile_id,
                    'claimant_staff_no' => $formModel->claimant_staff_no,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $trip->user->name . " (" . $trip->user->staff_no . ") :  Hod approved raising this subsistence",
                    'action' => "Approved",
                    'config_eform_id' => config('constants.eforms_id.subsistence'),
                    'eform_id' => $formModel->id,
                    'created_by' => $user->id,
                ],
                [
                    'profile' => $user->profile_id,
                    'claimant_staff_no' => $formModel->claimant_staff_no,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $trip->user->name . " (" . $trip->user->staff_no . ") :  Hod approved raising this subsistence",
                    'action' => "Approved",
                    'current_status_id' => config('constants.trip_status.accepted'),
//                    'action_status_id' => config('constants.trip_status.hod_approved_trip'),
                    'action_status_id' => config('constants.subsistence_status.hod_approved'),
                    'config_eform_id' => config('constants.eforms_id.subsistence'),
                    'eform_id' => $formModel->id,
                    'created_by' => $user->id,
                ]

            );

            //reason for trip
            $reason = EformApprovalsModel::Create(
                [
                    'profile' => $user->profile_id,
                    'claimant_staff_no' => $formModel->claimant_staff_no,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $trip->user->name . " (" . $trip->user->staff_no . ") :  Hod approved raising this subsistence",
                    'action' => "Approved",
                    'current_status_id' => config('constants.trip_status.accepted'),
//                    'action_status_id' => config('constants.trip_status.hod_approved_trip'),
                    'action_status_id' => config('constants.subsistence_status.hod_approved'),
                    'config_eform_id' => config('constants.eforms_id.trip'),
                    'eform_id' => $trip->id,
                    'eform_code' => $trip->code,
                    'created_by' => $user->id,
                ]);

            $sub = SubsistenceModel::find($formModel->id);
            //save
            $sub->authorised_by = $trip->user->name;
            $sub->authorised_staff_no = $trip->user->staff_no;
            $sub->authorised_date = $request->date;

            $sub->initiator_name = $trip->user->name;
            $sub->initiator_staff_no = $trip->user->staff_no;
            $sub->initiator_date = $request->date;

            $sub->profile = Auth::user()->profile_id;
            $sub->save();

            //send the email
            // self::nextUserSendMail($new_status, $form);
        }


        /** send email to supervisor */
        //get team email addresses

        $names = "";
        $to = [];
        // add hods email addresses
        foreach ($my_hods as $item) {
            $to[] = ['email' => $item->email, 'name' => $item->name];
            $names = $names . '<br>' . $item->name;
        }

        //prepare details
        $details = [
            'name' => $names,
            'url' => 'trip.home',
            'subject' => "New Trip Needs Your Attention",
            'title' => "New Trip Needs Your Attention",
            'body' => "Please note that {$user->name} with Staff Number {$user->staff_no} has successfully raised a Subsistence Claim  for the {$trip->destination} trip, with
                   <br> <pre>  Subsistence Number: {$formModel->code}</pre>
                   <br> <pre>&nbsp;&nbsp;Reference: {$formModel->ref_no}</pre>
                   <br> <pre>&nbsp;&nbsp;Trip Number: {$trip->code}</pre>
                   <br> <pre>&nbsp;&nbsp;Trip Destination: {$trip->destination}</pre>
                   <br> <pre>&nbsp;&nbsp;Status: {$formModel->status->name}</pre>
                   <br> <pre>&nbsp;&nbsp;Amount: ZMW {$formModel->net_amount_paid}. </pre> </br>
            This claim now needs your approval, kindly click on the button below to login to E-ZESCO and take action on the voucher.<br><br> "
        ];

        // send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));

        // log the activity
        ActivityLogsController::store($request, "Creating of Subsistence", "create", " a subsistence claim of {$formModel->net_amount_paid} was raised by {$user->name} for the {$trip->destination} trip for {$diff} days ", $formModel->id);

        //if there was error
        if ($error) {
            // return with error msg
            return Redirect::route('subsistence.home')->with('error', 'Sorry!, The superior who is supposed to approve your Subsistence,
                       <br> has not registered or not fully configured yet, Please, <b>try first contacting your superior</b> so as to make sure he/she has registered in the system,
                       then you can contact eZESCO Admins (1142,1126,2350,2345,3306) isd@zesco.co.zm to configure your Subsistence voucher path. Your Subsistence Claim has been saved.');
        } else {
            // return the view
            return Redirect::route('subsistence.home')->with('message', 'Subsistence Details for ' . $formModel->code . ' have been Created successfully');
        }
    }


    /**
     * Fetch a list of my HODs
     * @param $user
     * @return array
     */


    public function findMyNextPerson($next_status, $user_unit, $claimant)
    {

        $user_array = [];
        $not_claimant = true;

        //check if this belongs to the same department
        $departmental = false;
        if (($user_unit->user_unit_code) == ($claimant->user_unit_code)) {
            $departmental = true;
        }


        //FOR SUBSISTENCE RAISED
        if ($next_status == config('constants.trip_status.accepted')) {
            $claimant->load('grade');

            //CHECK TO SEE IF YOU ARE SNR MANAGEMENT
            if($claimant->grade->category_id == config('constants.grade_category.senior_mgt')){
                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_003'));
            }else{
                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));
            }

            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($claimant->user_unit_code, $profile);
        } //FOR HOD
        elseif ($next_status == config('constants.trip_status.trip_authorised')) {
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);
        } // HOD APPROVAL AGAIN
        elseif ($next_status == config('constants.trip_status.new_trip')) {
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);

        }



        // FORM HAS BEEN APPROVED BY HOD - SNR MANGER IS NEXT
        elseif ($next_status == config('constants.trip_status.hod_approved_trip')
        ) {
//            // - SAME
//            if ($departmental) {
//                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_009'));
//                $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);
//            }
//            // - DIFF
//            else {
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_015'));
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($claimant->user_unit_code, $profile);

//            }
        }  //HOD HAS APPROVED FORM SNR MANAGER IS NEXT
        elseif ($next_status == config('constants.subsistence_status.hod_approved')) {
            // - SAME
//            if ($departmental) {
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_015'));
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);
//            }
//            // - DIFF
//            else {
//                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_009'));
//                $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($claimant->user_unit_code, $profile);
//            }
        } //HR APPROVAL - SAME
        elseif ($next_status == config('constants.trip_status.hr_approved_trip')) {

            if ($departmental) {
                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_015'));  // remove snr manager
                $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);

            } else {
                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_015')); // remove snr manager
                // dd($profile);
                $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($claimant->user_unit_code, $profile);
            }

        } //HOD  HAS APPROVED FORM SNR MANAGER IS NEXT
        elseif ($next_status == config('constants.subsistence_status.hod_approved')) {
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_015'));  //SNR MGER
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);

        }
        //HR APPROVAL
        elseif ($next_status == config('constants.subsistence_status.hr_approved')) {

//            if ($claimant->grade->name == 'M1' || $claimant->grade->name == 'M2' || $claimant->grade->name == 'M3' ) {
//
//                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_007'));
//                $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);
//            } else {

                $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_007')); //CA
                $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);
//            }

        } // CHIEF ACCOUNTANT TO AUDIT
        elseif (($next_status == config('constants.subsistence_status.chief_accountant'))
            || ($next_status == config('constants.subsistence_status.audit_box'))
            || ($next_status == config('constants.subsistence_status.await_audit'))
        ) {
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_011')); //AUDITOR
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);

        } // VISIBLE TO EXPENDITURES
        elseif (($next_status == config('constants.subsistence_status.pre_audited'))
            || ($next_status == config('constants.subsistence_status.queried'))
            || ($next_status == config('constants.exported'))
            || ($next_status == config('constants.uploaded'))
        ) {
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_014')); //EXPENDITURES
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);

        } // FUNDS HAVE BEEN DISBURSED - CLIENT NEEDS TO CONFIRM
        elseif ($next_status == config('constants.subsistence_status.funds_disbursement')) {
            $not_claimant = false;

        } //DESTINATION APPROVAL
        elseif ($next_status == config('constants.subsistence_status.destination_approval')) {
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004')); //HOD
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);

        } //HR APPROVAL
        elseif ($next_status == config('constants.subsistence_status.station_mgr_approved')
            || $next_status == config('constants.subsistence_status.dr_approved') ) {

            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_009')); //HR
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);

        } //CLOSE------------
        elseif ($next_status == config('constants.subsistence_status.closed')) {

            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_011')); //AUDITORS
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);

        } else {
            //no one
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_002')); //REQUESTER
            $user_array = \App\Http\Controllers\Main\HomeController::getMySuperior($user_unit->user_unit_code, $profile);
        }

        if ($not_claimant) {

        } else {
            $user_array[] = $claimant;
        }

        //[3] return the list of users
        return $user_array;
    }


    /**
     * Generate Voucher Code
     * @param $head
     * @return string
     */
    public function randGenerator($head, $value)
    {
        // use the total number of Subsistence in the system
        $count = DB::select("SELECT count(id) as total FROM eform_subsistence ");
        // $random = rand(1, 9999999);
        $random = $count[0]->total;  // count total and begin again
        // $random = $size->total ;  // oracle sequence
        $random = sprintf("%07d", ($random + $value));
        $random = $head . $random;
        //
        $count_existing_forms = DB::select("SELECT count(id) as total FROM eform_subsistence WHERE code = '{$random}'");
        try {
            $total = $count_existing_forms[0]->total;
        } catch (\Exception $exception) {
            $total = 0;
        }
        //test
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
     * @return Response
     */
    public function show($id)
    {
        $form = SubsistenceModel::where('id', $id)->Orwhere('code', $id)->first();


        if ($form->id == null) {
            return back()->with('error', 'No subsistence invoices ' . $id . ' could not be found, or you do not have permissions to view it. ');
        }

        $trip = Trip::findOrFail($form->trip_id);
        //
        $receipts = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.subsistence'))
            ->get();
        $attached_files = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.subsistence'))
            ->get();
        //
        $form_accounts = SubsistenceAccountModel::all();
        $projects = ProjectsModel::all();
        $accounts = AccountsChartModel::orderBy('code')->get();
        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.subsistence'))
            ->orderBy('created_at', 'asc')->get();

        $user = User::find($form->created_by);

        $user_array = []; //T3218

        if ($form->config_status_id == config('constants.subsistence_status.destination_approval')) {
            $dest_approvals = DestinationsApprovals::where('subsistence_id', $form->id)
                ->whereNull('created_by')
                ->get();
            $dest_approvals->load('user_unit');
            foreach ($dest_approvals as $key => $dest) {
                if ($key != 0) {
                    $user_array = $user_array->merge(self::findMyNextPerson($form->config_status_id, $dest->user_unit, $user));
                } else {
                    $user_array = self::findMyNextPerson($form->config_status_id, $dest->user_unit, $user);
                }
            }

        } else {
            $user_array = self::findMyNextPerson($form->config_status_id, $form->user_unit, $user);
        }

        //check if this belongs to the same department
        $departmental = false;
        $departmental_hod = false;
        if (($form->user_unit_code) == ($user->user_unit_code)) {
            $departmental = true;
        } else {
            if (($form->config_status_id == config('constants.trip_status.accepted'))
                || ($form->config_status_id == config('constants.trip_status.hod_approved_trip')
                    || ($form->config_status_id == config('constants.trip_status.hr_approved_trip')))
            ) {

                $fdsf = \App\Http\Controllers\Main\HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
                $mine = $fdsf->pluck('user_unit_code')->toArray();
                if (in_array($user->user_unit_code, $mine)) {
                    $departmental_hod = true;

                }
            }
        }


        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        $taxes = TaxModel::where('tax', 0)->get();

        //data to send to the view
        $params = [
            'receipts' => $receipts,
            'attached_files' => $attached_files,
            'form_accounts' => $form_accounts,
            'totals_needs_me' => $totals_needs_me,
            'form' => $form,
            'user' => Auth::user(),
            'projects' => $projects,
            'user_array' => $user_array,
            'taxes' => $taxes,
            'approvals' => $approvals,
            'accounts' => $accounts,
            'trip' => $trip,
            'departmental_hod' => $departmental_hod,
            'departmental' => $departmental
        ];
        //return view
        return view('eforms.subsistence.show')->with($params);

    }


    public function showForm($id)
    {
        //GET THE Subsistence MODEL if you are an admin
        $list = DB::select("SELECT * FROM eform_subsistence where id = {$id} or where code = '{$id}' ");
        $form = SubsistenceModel::hydrate($list)->first();

        if ($form->id == null) {
            return back()->with('error', 'No subsistence invoices ' . $id . ' could not be found, or you do not have permissions to view it. ');
        }

        $receipts = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.subsistence'))
            ->get();
        $quotations = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.subsistence'))
            ->get();
        $form_accounts = SubsistenceAccountModel::where('eform_subsistence_id', $id)->get();
        $projects = ProjectsModel::all();
        $accounts = AccountsChartModel::all();
        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.subsistence'))
            ->orderBy('created_at', 'asc')->get();

        $user = User::find($form->created_by);
        $user_array = self::findMyNextPerson($form->config_status_id, $user->user_unit, $user);

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //data to send to the view
        $params = [
            'receipts' => $receipts,
            'quotations' => $quotations,
            'form_accounts' => $form_accounts,
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
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
        //GET THE Subsistence MODEL
        $form = SubsistenceModel::find($request->id);
        $form->load('user_unit.operating');
        $current_status = $form->status->id;
        $user = Auth::user();
        $insert_reasons = false;


        //check if this belongs to the same department
        $departmental = false;
        if (($form->user_unit_code) == ($form->user->user_unit_code)) {
            $departmental = true;
        }


        /** ****************************************************
         * FOR SAME DEPARTMENT
         ***************************************************** */

        //ROUTE ONE    - HOD to SNR MANAGER
        //FOR HR
        if (
            $user->profile_id == config('constants.user_profiles.EZESCO_009')
            && (
                $current_status == config('constants.subsistence_status.station_mgr_approved')
                || $current_status == config('constants.subsistence_status.dr_approved')
            )
        ) {

            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.trip_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.hr_approved');
            } else {
                $new_status = config('constants.subsistence_status.station_mgr_approved');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            //
            $form->hr_office = $user->name;
            $form->hr_office_staff_no = $user->staff_no;
            $form->hr_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            //
            $form->save();

            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();

            $list_inv->status_id = $new_status;
            $list_inv->save();
        }

        //FOR AUTHORIZER
        elseif (
            $user->profile_id == config('constants.user_profiles.EZESCO_015')
            && $current_status == config('constants.subsistence_status.hod_approved')
        ) {
            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.trip_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.station_mgr_approved');
            } else {
//                $new_status = config('constants.trip_status.hr_approved_trip');
                $new_status = config('constants.subsistence_status.hod_approved');
                $insert_reasons = false;
            }
            //update

            $form->config_status_id = $new_status;
            $form->closed_by_name = $user->name;
            $form->closed_by_staff_no = $user->staff_no;
            $form->closed_by_date = $request->sig_date;
            //
            $form->station_manager = $user->name;
            $form->station_manager_staff_no = $user->staff_no;
            $form->station_manager_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            //
            $form->save();

            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();


            $list_inv->status_id = $new_status;
            $list_inv->save();
        }

//ROUTE ONE B HOD TO HR

//        //FOR HR
//        if (
//            $user->profile_id == config('constants.user_profiles.EZESCO_009')
//            && $current_status == config('constants.subsistence_status.hod_approved')
////            && $current_status == config('constants.trip_status.hod_approved_trip')
//        ) {
//            $insert_reasons = true;
//            //cancel status
//            if ($request->approval == config('constants.approval.cancelled')) {
//                $new_status = config('constants.trip_status.cancelled');
//            } //reject status
//            elseif ($request->approval == config('constants.approval.reject')) {
//                $new_status = config('constants.subsistence_status.rejected');
//            }//approve status
//            elseif ($request->approval == config('constants.approval.approve')) {
////                $new_status = config('constants.trip_status.hr_approved_trip');
//                $new_status = config('constants.subsistence_status.hr_approved');
//            } else {
////                $new_status = config('constants.trip_status.hod_approved_trip');
//                $new_status = config('constants.subsistence_status.hod_approved');
//                $insert_reasons = false;
//            }
//            //update
//            $form->config_status_id = $new_status;
//            //
//            $form->hr_office = $user->name;
//            $form->hr_office_staff_no = $user->staff_no;
//            $form->hr_date = $request->sig_date;
//            $form->profile = Auth::user()->profile_id;
//            //
//            $form->save();
//
//            //change invitation status
//            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
//                ->where('trip_id', $form->trip_id)
//                ->first();
//
//            $list_inv->status_id = $new_status;
//            $list_inv->save();
//        }
//
//        //FOR AUTHORIZER
//        elseif (
//            $user->profile_id == config('constants.user_profiles.EZESCO_015')
//            && $current_status == config('constants.subsistence_status.hr_approved')
////            && $current_status == config('constants.trip_status.hr_approved_trip')
//        ) {
//            $insert_reasons = true;
//            //cancel status
//            if ($request->approval == config('constants.approval.cancelled')) {
//                $new_status = config('constants.trip_status.cancelled');
//            } //reject status
//            elseif ($request->approval == config('constants.approval.reject')) {
//                $new_status = config('constants.subsistence_status.rejected');
//            }//approve status
//            elseif ($request->approval == config('constants.approval.approve')) {
//                $new_status = config('constants.subsistence_status.station_mgr_approved');
//            } else {
////                $new_status = config('constants.trip_status.hr_approved_trip');
//                $new_status = config('constants.subsistence_status.hr_approved');
//                $insert_reasons = false;
//            }
//            //update
//
//            $form->config_status_id = $new_status;
//            $form->closed_by_name = $user->name;
//            $form->closed_by_staff_no = $user->staff_no;
//            $form->closed_by_date = $request->sig_date;
//            //
//            $form->station_manager = $user->name;
//            $form->station_manager_staff_no = $user->staff_no;
//            $form->station_manager_date = $request->sig_date;
//            $form->profile = Auth::user()->profile_id;
//            //
//            $form->save();
//
//            //change invitation status
//            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
//                ->where('trip_id', $form->trip_id)
//                ->first();
//
//
//            $list_inv->status_id = $new_status;
//            $list_inv->save();
//        }

        elseif (
            $user->profile_id == config('constants.user_profiles.EZESCO_003')
            && $current_status == config('constants.trip_status.accepted')
        ) {

         //   dd(  $new_status = config('constants.subsistence_status.dr_approved'));

            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.trip_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.dr_approved');
            } else {
                $new_status = config('constants.trip_status.accepted');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            //
            $form->save();

            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();

            $list_inv->status_id = $new_status;
            $list_inv->save();
        }



        /** ****************************************************
         * FOR DIFFERENT DEPARTMENT
         ***************************************************** */

        //FOR CLAIMANT CANCELLATION
        elseif (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_002')
            && $current_status == config('constants.subsistence_status.new_application')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.cancelled');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } else {
                $new_status = config('constants.subsistence_status.new_application');
                $insert_reasons = false;
            }
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();

            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();

            $list_inv->status_id = $new_status;
            $list_inv->save();
        } //FOR HOD
        elseif (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_004')
            && $current_status == config('constants.trip_status.trip_authorised')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.hod_approved');
            } else {
                $new_status = config('constants.subsistence_status.new_application');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->authorised_by = $user->name;
            $form->authorised_staff_no = $user->staff_no;
            $form->authorised_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();

            $list_inv->status_id = $new_status;
            $list_inv->save();
        }

        //FOR  HR
//        elseif (
//            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_009')
//            && $current_status == config('constants.subsistence_status.hod_approved')
//        ) {
//            //cancel status
//            $insert_reasons = true;
//            if ($request->approval == config('constants.approval.cancelled')) {
//                $new_status = config('constants.subsistence_status.cancelled');
//            } //reject status
//            elseif ($request->approval == config('constants.approval.reject')) {
//                $new_status = config('constants.subsistence_status.rejected');
//            }//approve status
//            elseif ($request->approval == config('constants.approval.approve')) {
//                $new_status = config('constants.subsistence_status.hr_approved');
//            } else {
//                $new_status = config('constants.subsistence_status.hod_approved');
//                $insert_reasons = false;
//            }
//
//            //update
//            $form->config_status_id = $new_status;
//            $form->hr_office = $user->name;
//            $form->hr_office_staff_no = $user->staff_no;
//            $form->hr_date = $request->sig_date;
//            $form->profile = Auth::user()->profile_id;
//            $form->save();
//
//            //change invitation status
//            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
//                ->where('trip_id', $form->trip_id)
//                ->first();
//
//            $list_inv->status_id = $new_status;
//            $list_inv->save();
//        }

        //FOR CHIEF ACCOUNTANT
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_007')
            && $current_status == config('constants.subsistence_status.hr_approved')
        ) {
            -
            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.chief_accountant');
            } else {
                $new_status = config('constants.subsistence_status.hr_approved');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->chief_accountant = $user->name;
            $form->chief_accountant_staff_no = $user->staff_no;
            $form->chief_accountant_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();

            $list_inv->status_id = $new_status;
            $list_inv->save();
        } //FOR CHIEF ACCOUNTANT
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_007')
            && $current_status == config('constants.subsistence_status.hr_approved')
        ) {
            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.chief_accountant');
            } else {
                $new_status = config('constants.subsistence_status.hr_approved');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->chief_accountant = $user->name;
            $form->chief_accountant_staff_no = $user->staff_no;
            $form->chief_accountant_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();

            $list_inv->status_id = $new_status;
            $list_inv->save();
        } //FOR PRE AUDIT   - REMOVE PRE AUDITS
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_011-REMOVED')
            && $current_status == config('constants.subsistence_status.chief_accountant-REMOVED')
        ) {
            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.pre_audited');
            } else {
                $new_status = config('constants.subsistence_status.chief_accountant');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();
            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();

            $list_inv->status_id = $new_status;
            $list_inv->save();
        }

        //FOR STATION MANAGER -SNR
//        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_015')
//            && $current_status == config('constants.subsistence_status.hr_approved')
//        ) {
//            $insert_reasons = true;
//            //cancel status
//            if ($request->approval == config('constants.approval.cancelled')) {
//                $new_status = config('constants.subsistence_status.cancelled');
//            } //reject status
//            elseif ($request->approval == config('constants.approval.reject')) {
//                $new_status = config('constants.subsistence_status.rejected');
//            }//approve status
//            elseif ($request->approval == config('constants.approval.approve')) {
//                $new_status = config('constants.subsistence_status.chief_accountant');
//            } else {
//                $new_status = config('constants.subsistence_status.hr_approved');
//                $insert_reasons = false;
//            }
//            //update
//            $form->config_status_id = $new_status;
//            $form->station_manager = $user->name;
//            $form->station_manager_staff_no = $user->staff_no;
//            $form->station_manager_date = $request->sig_date;
//            $form->profile = Auth::user()->profile_id;
//            $form->save();
//            //change invitation status
//            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
//                ->where('trip_id', $form->trip_id)
//                ->first();
//
//            $list_inv->status_id = $new_status ;
//            $list_inv->save();
//        }

        //   FOR EXPENDITURE UPLOADING TO FMS
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')
//        && $current_status == config('constants.subsistence_status.pre_audited-AFTER_PRE_AUDITS')
        && $current_status == config('constants.subsistence_status.pre_audited-chief_accountant')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.exported');
            } else {
                $new_status = config('constants.subsistence_status.pre_audited');
                $insert_reasons = false;
            }

            //update
            $form->allocation_code = $request->allocation_code;
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();

            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();
            $list_inv->status_id = $new_status;
            $list_inv->save();

            //create records for the accounts associated with this Subsistence transaction
            for ($i = 0; $i < sizeof($request->credited_amount); $i++) {
                $des = "";
                $des = $des . " " . $request->account_items[$i] . ",";
                $des = "Subsistence Claim Serial: " . $form->code . ", Claimant: " . $form->claimant_name . ', ' . $des . ' Amount: ' . $request->credited_amount[$i] . '.';

                $apply_tax = TaxModel::find($request->tax[$i]);
                $vat_rate = $apply_tax->tax;

                if ($apply_tax->tax < 1) {

                    //[1] CREDITED ACCOUNT
                    //[1A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account[$i],
                            'creditted_amount' => $request->credited_amount[$i],
                            'account' => $request->credited_account[$i],
                            'debitted_account_id' => $request->debited_account[$i],
//                            'debitted_amount' => 0,
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account[$i],
                            'creditted_amount' => $request->credited_amount[$i],
                            'account' => $request->credited_account[$i],
                            'debitted_account_id' => $request->debited_account[$i],
//                            'debitted_amount' => 0,

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $form->cost_center,
                            'business_unit_code' => $form->business_unit_code,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'account_type' => config('constants.account_type.operating'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $des,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );

                    //[2] DEBITED ACCOUNT
                    //[2A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account[$i],
////                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account[$i],
                            'debitted_amount' => $request->debited_amount[$i],
                            'account' => $request->debited_account[$i],
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account[$i],
////                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account[$i],
                            'debitted_amount' => $request->debited_amount[$i],
                            'account' => $request->debited_account[$i],

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $form->cost_center,
                            'business_unit_code' => $form->business_unit_code,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'account_type' => config('constants.account_type.expense'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $des,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );
                } else {
                    //calculation
                    $total_percent = 100 + $apply_tax->tax;
                    $tax_amount = ($request->credited_amount[$i] * $apply_tax->tax) / $total_percent;
                    $without_tax = ($request->credited_amount[$i]) - $tax_amount;


                    //[1] CREDITED ACCOUNT
                    //[1A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account[$i],
                            'creditted_amount' => $request->credited_amount[$i],
                            'account' => $request->credited_account[$i],
                            'debitted_account_id' => $request->debited_account[$i],
//                            'debitted_amount' => 0,
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => 0,
//                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account[$i],
                            'creditted_amount' => $request->credited_amount[$i],
                            'account' => $request->credited_account[$i],
                            'debitted_account_id' => $request->debited_account[$i],
//                            'debitted_amount' => 0,

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $form->cost_center,
                            'business_unit_code' => $form->business_unit_code,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => 0,
//                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'account_type' => config('constants.account_type.operating'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $des,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );

                    //[2] DEBITED ACCOUNT
                    //[2A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account[$i],
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account[$i],
                            'debitted_amount' => $without_tax,
                            'account' => $request->debited_account[$i],
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account[$i],
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account[$i],
                            'debitted_amount' => $without_tax,
                            'account' => $request->debited_account[$i],
                            'account_type' => config('constants.account_type.expense'),

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $form->cost_center,
                            'business_unit_code' => $form->business_unit_code,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $des,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );

                    //[2] TAX AMOUNT ACCOUNT 1
                    //[2A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account[$i],
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account[$i],
                            'debitted_amount' => $tax_amount,
                            'account' => $request->debited_account[$i],
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => 0,
//                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.tax'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account[$i],
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account[$i],
                            'debitted_amount' => $tax_amount,
                            'account' => $request->debited_account[$i],
                            'account_type' => config('constants.account_type.expense'),

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $apply_tax->cost_center,
                            'business_unit_code' => $apply_tax->business_unit,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => 0,
//                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.tax'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $apply_tax->name,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );
                }

            }

            //upload invoice to FMS
            $integration = new Integration();
            $integration->sendFromSubistence($form);

        } //   FOR EXPENDITURE OFFICE FUNDS
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')
            && $current_status == config('constants.uploaded')
        ) {

            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.funds_disbursement');
            } else {
                $new_status = config('constants.subsistence_status.uploaded');
                $insert_reasons = false;
            }

            //update
            $form->allocation_code = $request->allocation_code;
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();

            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();
            $list_inv->status_id = $new_status;
            $list_inv->save();

        } //FOR CLAIMANT - ACKNOWLEDGEMENT
        elseif (Auth::user()->staff_no == $form->claimant_staff_no
            && $current_status == config('constants.subsistence_status.funds_disbursement')
        ) {

            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.destination_approval');
            } else {
                $new_status = config('constants.subsistence_status.funds_disbursement');
                $insert_reasons = false;
            }


            $request->reason = $request->reason . ". Account number used : " . $request->account_number;

            /** upload attached files */
            //upload the receipt files
            $files = $request->file('confirmation');
            if ($request->hasFile('confirmation')) {
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
                    $path = $file->storeAs('public/subsistence_files/confirmation', $fileNameToStore);

                    //upload the receipt
                    $file = AttachedFileModel::updateOrCreate(
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.subsistence'),
                            'file_type' => config('constants.file_type.subsistence')
                        ],
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.subsistence'),
                            'file_type' => config('constants.file_type.subsistence')
                        ]
                    );
                }
            }

            if ($request->account_number != null) {
                //update
                $form->config_status_id = $new_status;
                $form->account_number = $request->account_number;
                $form->profile = config('constants.user_profiles.EZESCO_007');
                $form->save();

                //cancel status
                $insert_reasons = true;

                //change invitation status
                $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                    ->where('trip_id', $form->trip_id)
                    ->first();
                $list_inv->status_id = $new_status;
                $list_inv->save();

            }

        } //DESTINATION APPROVAL
        elseif (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_004')
            &&
            $current_status == config('constants.subsistence_status.destination_approval')
        ) {

            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.await_audit');
            } else {
                $new_status = config('constants.subsistence_status.destination_approval');
                $insert_reasons = false;
            }

            //  $new_status = config('constants.subsistence_status.await_audit');
            //update
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();

            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();
            $list_inv->status_id = $new_status;
            $list_inv->save();


        } //FOR POST AUDIT OFFICE
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_011')
            && $current_status == config('constants.subsistence_status.await_audit')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.subsistence_status.audited');
            }//audit status
            elseif ($request->approval == config('constants.approval.queried')) {
                $new_status = config('constants.subsistence_status.queried');
            } else {
                $new_status = config('constants.subsistence_status.await_audit');
                $insert_reasons = false;
            }

            //  dd($new_status);

            //update
            $form->config_status_id = $new_status;
            $form->audit_name = $user->name;
            $form->audit_staff_no = $user->staff_no;
            $form->audit_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();

            // update the accounts to be now ready for upload to fms
            $ready_for_export = config('constants.not_exported');
            $form->load('accounts');
            $accounts = $form->accounts;
            foreach ($accounts as $account) {
                $account->status_id = $ready_for_export;
                $account->save();
            }


            //change invitation status
            $list_inv = Invitation::where('man_no', $form->claimant_staff_no)
                ->where('trip_id', $form->trip_id)
                ->first();
            $list_inv->status_id = $new_status;
            $list_inv->save();
        } //EXPENDITURE WORK ON QUERY
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')
            && $current_status == config('constants.subsistence_status.queried')
        ) {
            if ($request->change > 0) {
                if ($request->account_item == "" || $request->credited_account == "" || $request->credited_amount == "" || $request->debited_account == "") {
                    return Redirect::route('subsistence.home')->with('error', 'Subsistence ' . $form->code . ' did not successfully complete because some of the Accounts Values were empty');
                }
            }
            // dd($request->all() );
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.subsistence_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.subsistence_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.resolve')) {
                $new_status = config('constants.subsistence_status.await_audit');
            } else {
                $new_status = config('constants.subsistence_status.queried');
                $insert_reasons = false;
            }

            //update
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();


            //create records for the accounts associated with this Subsistence transaction
            //If there was change
            if ($request->change > 0) {
                $des = "";
                $des = $des . " " . $request->account_item . ",";
                $des = "Subsistence Claim Serial: " . $form->code . ", Claimant: " . $form->claimant_name . ', ' . $des . ' Amount: ' . $request->credited_amount . '.';

                $apply_tax = TaxModel::find($request->tax);
                $vat_rate = $apply_tax->tax;

                if ($apply_tax->tax < 1) {

                    //[1] CREDITED ACCOUNT
                    //[1A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account,
                            'creditted_amount' => $request->credited_amount,
                            'account' => $request->credited_account,
                            'debitted_account_id' => $request->debited_account,
//                            'debitted_amount' => 0,
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account,
                            'creditted_amount' => $request->credited_amount,
                            'account' => $request->credited_account,
                            'debitted_account_id' => $request->debited_account,
//                            'debitted_amount' => 0,

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $form->cost_center,
                            'business_unit_code' => $form->business_unit_code,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'account_type' => config('constants.account_type.operating'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $des,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );

                    //[2] DEBITED ACCOUNT
                    //[2A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account,
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account,
                            'debitted_amount' => $request->debited_amount,
                            'account' => $request->debited_account,
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account,
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account,
                            'debitted_amount' => $request->debited_amount,
                            'account' => $request->debited_account,

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $form->cost_center,
                            'business_unit_code' => $form->business_unit_code,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'account_type' => config('constants.account_type.expense'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $des,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );
                } else {
                    //calculation
                    $total_percent = 100 + $apply_tax->tax;
                    $tax_amount = ($request->credited_amount * $apply_tax->tax) / $total_percent;
                    $without_tax = ($request->credited_amount) - $tax_amount;


                    //[1] CREDITED ACCOUNT
                    //[1A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account,
                            'creditted_amount' => $request->credited_amount,
                            'account' => $request->credited_account,
                            'debitted_account_id' => $request->debited_account,
//                            'debitted_amount' => 0,
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
//                            'vat_rate' => $vat_rate,
                            'vat_rate' => 0,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account,
                            'creditted_amount' => $request->credited_amount,
                            'account' => $request->credited_account,
                            'debitted_account_id' => $request->debited_account,
//                            'debitted_amount' => 0,

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $form->cost_center,
                            'business_unit_code' => $form->business_unit_code,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
//                            'vat_rate' => $vat_rate,
                            'vat_rate' => 0,
                            'line_type' => config('constants.line_type.goods'),
                            'account_type' => config('constants.account_type.operating'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $des,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );

                    //[2] DEBITED ACCOUNT
                    //[2A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account,
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account,
                            'debitted_amount' => $without_tax,
                            'account' => $request->debited_account,
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.subsistence_status.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account,
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account,
                            'debitted_amount' => $without_tax,
                            'account' => $request->debited_account,

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $form->cost_center,
                            'business_unit_code' => $form->business_unit_code,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.goods'),
                            'account_type' => config('constants.account_type.expense'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $des,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );

                    //[2] TAX AMOUNT ACCOUNT 2
                    //[2A] - money
                    $formAccountModel = SubsistenceAccountModel::updateOrCreate(
                        [
                            'creditted_account_id' => $request->credited_account,
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account,
                            'debitted_amount' => $tax_amount,
                            'account' => $request->debited_account,
                            'eform_subsistence_id' => $form->id,
                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => 0,
//                            'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.tax'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'status_id' => config('constants.export_not_ready')
                        ],
                        [
                            'creditted_account_id' => $request->credited_account,
//                            'creditted_amount' => 0,
                            'debitted_account_id' => $request->debited_account,
                            'debitted_amount' => $tax_amount,
                            'account' => $request->debited_account,

                            'eform_subsistence_id' => $form->id,
                            'subsistence_code' => $form->code,
                            'cost_center' => $apply_tax->cost_center,
                            'business_unit_code' => $apply_tax->business_unit,
                            'user_unit_code' => $form->user_unit_code,
                            'claimant_name' => $form->claimant_name,
                            'claimant_staff_no' => $form->claimant_staff_no,
                            'claim_date' => $form->claim_date,

                            'created_by' => $user->id,
                            'company' => '01',
                            'intra_company' => '01',
                            'project' => '0',
                            'pems_project' => 'N',
                            'spare' => '0000',
                            'vat_rate' => 0,
//                           'vat_rate' => $vat_rate,
                            'line_type' => config('constants.line_type.tax'),
                            'account_type' => config('constants.account_type.expense'),
                            'org_id' => $form->user_unit->operating->org_id,
                            'description' => $apply_tax->name,
                            'status_id' => config('constants.export_not_ready')
                        ]
                    );
                }

            }


            /** upload attached files */
            //upload the receipt files
            $files = $request->file('receipt');
            if ($request->hasFile('receipt')) {
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
                    $path = $file->storeAs('public/subsistence_files', $fileNameToStore);

                    //upload the receipt
                    $file = AttachedFileModel::updateOrCreate(
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.subsistence'),
                            'file_type' => config('constants.file_type.subsistence')
                        ],
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.subsistence'),
                            'file_type' => config('constants.file_type.subsistence')
                        ]
                    );
                }
            }


        } //FOR NO-ONE
        else {
            //return with an error
            return Redirect::route('subsistence.home')->with('message', 'Subsistence ' . $form->code . ' for has been ' . $request->approval . ' successfully');
        }

        //reason
        if ($insert_reasons) {
            //save reason
            $reason_sub = EformApprovalsModel::updateOrCreate(
                [
                    'profile' => $user->profile_id,
                    'claimant_staff_no' => $form->claimant_staff_no,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $request->reason,
                    'action' => $request->approval,
                    'config_eform_id' => config('constants.eforms_id.subsistence'),
                    'eform_id' => $form->id,
                    'created_by' => $user->id,
                ],
                [
                    'profile' => $user->profile_id,
                    'claimant_staff_no' => $form->claimant_staff_no,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $request->reason,
                    'action' => $request->approval,
                    'current_status_id' => $current_status,
                    'action_status_id' => $new_status,
                    'config_eform_id' => config('constants.eforms_id.subsistence'),
                    'eform_id' => $form->id,
                    'created_by' => $user->id,
                ]

            );

            if ($departmental) {
                $reason_trip = EformApprovalsModel::Create(
                    [
                        'profile' => $user->profile_id,
                        'claimant_staff_no' => $form->claimant_staff_no,
                        'name' => $user->name,
                        'staff_no' => $user->staff_no,
                        'reason' => $request->reason,
                        'action' => $request->approval,
                        'current_status_id' => $current_status,
                        'action_status_id' => $new_status,
                        'config_eform_id' => config('constants.eforms_id.trip'),
                        'eform_id' => $form->trip->id,
                        'eform_code' => $form->trip->code,
                        'created_by' => $user->id,
                    ]);
            }
            //send the email
            self::nextUserSendMail($new_status, $form);

        }

        //redirect home
        return Redirect::route('subsistence.home')->with('message', $form->total_payment . ' Subsistence Claim ' . $form->code . ' for ' . $form->claimant_name . ' has been ' . $request->approval . ' successfully');

    }

    /**
     * Send Email to the Next Person/s who are supposed to work on the form next
     * @param $profile
     * @param $stage
     * @param $claim_staff
     */

    public function nextUserSendMail($new_status, $form)
    {
        //get the users
        $user_array = self::findMyNextPerson($new_status, $form->user_unit, $form->user);

        $names = "";
        $claimant_details = User::find($form->created_by);
        $form_state = StatusModel::find($new_status);

        //check if this next profile is for a claimant and if the Subsistence Claim needs Acknowledgement
        if ($new_status == config('constants.subsistence_status.security_approved')) {
            //message details
            $subject = 'Subsistence Claim Needs Your Attention';
            $title = 'Subsistence Claim Needs Your Attention';
            $message = 'This is to notify you that there is a <b>ZMW ' . $form->total_payment . '</b>  Subsistence Claim (' . $form->code . ') raised by ' . $form->claimant_name . ', that needs your attention.
            <br>Please login to e-ZESCO by clicking on the button below to take action on the voucher.<br>The form is currently at ' . $form_state->name . ' stage';
        } //check if this next profile is for   a claimant and if the Subsistence Claim is closed
        else if ($new_status == config('constants.subsistence_status.closed')) {
            $names = $names . '<br>' . $claimant_details->namee;
            //message details
            $subject = 'Subsistence Claim Closed Successfully';
            $title = 'Subsistence Claim Closed Successfully';
            $message = 'This is to notify you that Subsistence Claim ' . $form->code . ' has been closed successfully .
            <br>Please login to e-ZESCO by clicking on the button below to view the voucher. <br>The Subsistence voucher has now been closed.';
        } // other wise get the users
        else {
            //message details
            $subject = 'Subsistence Claim Needs Your Attention';
            $title = 'Subsistence Claim Needs Your Attention';
            $message = 'This is to notify you that there is a <b>ZMW ' . $form->total_payment . '</b>  Subsistence Claim (' . $form->code . ') raised by ' . $form->claimant_name . ',that needs your attention.
            <br>Please login to e-ZESCO by clicking on the button below to take action on the voucher.<br>The form is currently at ' . $form_state->name . ' stage.';
        }

        /** send email to supervisor */
        $to = [];
        //add hods email addresses
        foreach ($user_array as $item) {
            //use the pay point
            $to[] = ['email' => $item->email, 'name' => $item->name];
            $to[] = ['email' => $claimant_details->email, 'name' => $claimant_details->name];
            $names = $names . '<br>' . $item->name;
        }

        //  dd($user_array);
        $to[] = ['email' => 'nshubart@zesco.co.zm', 'name' => 'Shubart Nyimbili'];
        $to[] = ['email' => 'bchisulo@zesco.co.zm', 'name' => 'Bwalya Chisulo'];
        //prepare details
        $details = [
            'name' => $names,
            'url' => 'subsistence.home',
            'subject' => $subject,
            'title' => $title,
            'body' => $message
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));

    }

    /**
     * List the users who are supposed to work on the form next
     * @param $last_profile
     * @param $current_status
     * @param $claimant_man_no
     * @return array
     */


//    public function nextUsers($new_status, $user_unit, $user)
//    {
//        $users_array = [];
//        $not_claimant = true;
//
//        //FOR MY HOD USERS
//        if ($new_status == config('constants.subsistence_status.new_application')) {
//            $superior_user_unit = $user_unit->hod_unit;
//            $superior_user_code = $user_unit->hod_code;
//            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));
//        } elseif ($new_status == config('constants.subsistence_status.hod_approved')) {
//            $superior_user_code = $user_unit->hrm_code;
//            $superior_user_unit = $user_unit->hrm_unit;
//            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_009'));
//        } elseif ($new_status == config('constants.subsistence_status.hr_approved')) {
//            $superior_user_code = $user_unit->ca_code;
//            $superior_user_unit = $user_unit->ca_unit;
//            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_007'));
//        } elseif ($new_status == config('constants.subsistence_status.chief_accountant')) {
//            $superior_user_unit = $user_unit->expenditure_unit;
//            $superior_user_code = $user_unit->expenditure_code;
//            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_014'));
//        } elseif ($new_status == config('constants.subsistence_status.funds_disbursement')) {
//            $not_claimant = false;
//        } elseif ($new_status == config('constants.subsistence_status.funds_acknowledgement')) {
//            $superior_user_unit = $user_unit->security_unit;
//            $superior_user_code = $user_unit->security_code;
//            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_013'));
//        } elseif ($new_status == config('constants.subsistence_status.security_approved')) {
//            $superior_user_unit = $user_unit->expenditure_unit;
//            $superior_user_code = $user_unit->expenditure_code;
//            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_014'));
//        } elseif ($new_status == config('constants.subsistence_status.closed')) {
//            $superior_user_unit = $user_unit->audit_unit;
//            $superior_user_code = $user_unit->audit_unit;
//            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_011'));
//            // dd(1);
//        } else {
//            //no one
//            $superior_user_unit = "0";
//            $superior_user_code = "0";
//            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_002'));
//        }
//
//        if ($not_claimant) {
//            //SELECT USERS
//
//            $users_list[] = '';
//            //[A]check if the users in my user unit have this assigned profile
//            $assigned_users = ProfileAssigmentModel::
//            where('eform_id', config('constants.eforms_id.subsistence'))
//                ->where('profile', $profile->code)
//                ->get();
//            //loop through assigned users
//            foreach ($assigned_users as $item) {
//                if ($profile->id == config('constants.user_profiles.EZESCO_014') ||
//                    $profile->id == config('constants.user_profiles.EZESCO_013') ||
//                    $profile->id == config('constants.user_profiles.EZESCO_011')) {
//                    //expenditure, audit and security
//                    $my_superiors = User::where('user_unit_code', $superior_user_unit)
//                        ->where('id', $item->user_id)
//                        ->get();
//                    foreach ($my_superiors as $item) {
//                        $users_array[] = $item;
//                    }
//                } else {
//                    //hod, hr, ca
//                    $my_superiors = User::where('user_unit_code', $superior_user_unit)
//                        ->where('job_code', $superior_user_code)
//                        ->where('id', $item->user_id)
//                        ->get();
//                    foreach ($my_superiors as $item) {
//                        $users_array[] = $item;
//                    }
//                }
//
//            }
//            //[B]check if one the users with the profile have this delegated profile
//            $delegated_users = ProfileDelegatedModel::
//            where('eform_id', config('constants.eforms_id.subsistence'))
//                ->where('delegated_profile', $profile->code)
//                ->where('delegated_job_code', $superior_user_code)
//                ->where('delegated_user_unit', $superior_user_unit)
//                ->where('config_status_id', config('constants.active_state'))
//                ->get();
//            //loop through delegated users
//            foreach ($delegated_users as $item) {
//                $user = User::find($item->delegated_to);
//                $users_array[] = $user;
//            }
//
//        } else {
//            $users_array[] = $user;
//            // $hods_array[] = $user;
//        }
//
//        //[3] return the list of users
//        return $users_array;
//    }

    public function reports(Request $request, $value)
    {
        //get the accounts
        $title = "";

        if ($value == config('constants.all')) {
            if (Auth::user()->type_id == config('constants.user_types.developer')) {
                $list = DB::select("SELECT * FROM eform_subsistence_account  ");
                $list = SubsistenceAccountModel::hydrate($list);
            } else {
                $list = SubsistenceAccountModel::all();
            }
            $title = "ALl";
        } elseif ($value == config('constants.subsistence_status.not_exported')) {
            if (Auth::user()->type_id == config('constants.user_types.developer')) {
                $status = config('constants.subsistence_status.not_exported');
                $list = DB::select("SELECT * FROM eform_subsistence_account where status_id = {$status} ");
                $list = SubsistenceAccountModel::hydrate($list);
            } else {
                $list = SubsistenceAccountModel::where('status_id', config('constants.subsistence_status.not_exported'))->get();
            }
            $title = "Not Exported";
        } elseif ($value == config('constants.subsistence_status.exported')) {
            if (Auth::user()->type_id == config('constants.user_types.developer')) {
                $status = config('constants.subsistence_status.exported');
                $list = DB::select("SELECT * FROM eform_subsistence_account where status_id = {$status} ");
                $list = SubsistenceAccountModel::hydrate($list);
            } else {
                $list = SubsistenceAccountModel::where('status_id', config('constants.subsistence_status.exported'))->get();
            }
            $title = " Exported";
        } elseif ($value == config('constants.subsistence_status.export_failed')) {
            if (Auth::user()->type_id == config('constants.user_types.developer')) {
                $status = config('constants.subsistence_status.export_failed');
                $list = DB::select("SELECT * FROM eform_subsistence_account where status_id = {$status} ");
                $list = SubsistenceAccountModel::hydrate($list);
            } else {
                $list = SubsistenceAccountModel::where('status_id', config('constants.subsistence_status.export_failed'))->get();
            }
            $title = "Failed Export";
        }


        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //data to send to the view
        $params = [
            'title' => $title,
            'totals_needs_me' => $totals_needs_me,
            'list' => $list
        ];
        //  dd($list);
        return view('eforms.subsistence.report')->with($params);
    }

    public function reportsExport(Request $request)
    {
        $fileName = 'PettyCash_Accounts.csv';
        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $tasks = SubsistenceAccountModel::where('status_id', config('constants.subsistence_status.not_exported'))->get();
        } else {
            $not_exported = config('constants.subsistence_status.not_exported');
            $tasks = DB::select("SELECT * FROM eform_subsistence_account
                        WHERE status_id = {$not_exported}
                        ORDER BY eform_subsistence_id ASC ");
            $tasks = SubsistenceAccountModel::hydrate($tasks);
        }


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
//                $item->status_id = config('constants.subsistence_status.exported');
//                $item->save();

                //Make the update on the Subsistence account
                $previous_status = config('constants.subsistence_status.exported');
                $id = $item->id;
                $eform_subsistence_item = DB::table('eform_subsistence_account')
                    ->where('id', $id)
                    ->update(['status_id' => $previous_status]);

                $row['Code'] = $item->petty_cash_code;
                $row['Claimant'] = $item->claimant_name;
                $row['Claim Date'] = $item->claim_date;
                $row['Company'] = $item->company;
                $row['Business Unit'] = $item->business_unit_code;
                $row['Cost Center'] = $item->cost_center;
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
        $list = SubsistenceModel:: select(DB::raw('cost_centre, name_of_claimant, count(id) as total_forms , sum(total_payment) as forms_sum '))
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
        return view('eforms.subsistence.chart')->with($params);
        //  dd($request);
    }

    public function sync($id)
    {

        //SYNC ONE
        //get the form
        $form = DB::table('eform_subsistence')
            ->where('id', $id)
            ->get()->first();


        //get the claimant with the user unit which has the workflow details
//        $user_unit = ConfigWorkFlow::where('user_unit_code',$form->user_unit_code )
//            ->where('user_unit_cc_code',$form->cost_center)
//            ->where('user_unit_bc_code',$form->business_unit_code)->first();

        $user = User::find($form->created_by);
        $user_unit = $user->user_unit;
        try {
            $test = $user_unit->user_unit_cc_code;
        } catch (\Exception $exception) {
//            $user = User::find($form->created_by);
//            $user_unit = $user->user_unit;
            //redirect home
            return Redirect::back()->with('error', 'Subsistence Voucher did not sync, because of the user-unit problem.');
        }

        //make the update
        $update_eform_petty_cash = DB::table('eform_subsistence')
            ->where('id', $form->id)
            ->update([

                'cost_center' => $user_unit->user_unit_cc_code,
                'business_unit_code' => $user_unit->user_unit_bc_code,
                'user_unit_code' => $user_unit->user_unit_code,

                'hod_code' => $user_unit->hod_code,
                'hod_unit' => $user_unit->hod_unit,
                'ca_code' => $user_unit->ca_code,
                'ca_unit' => $user_unit->ca_unit,
                'hrm_code' => $user_unit->hrm_code,
                'hrm_unit' => $user_unit->hrm_unit,
                'expenditure_code' => $user_unit->expenditure_code,
                'expenditure_unit' => $user_unit->expenditure_unit,
                'security_code' => $user_unit->security_code,
                'security_unit' => $user_unit->security_unit
            ]);


        // SYNC ALL
//        $eform_subsistence_all = DB::select("SELECT * FROM eform_subsistence  ");

//        foreach ($eform_subsistence_all as $form) {
//
//            //get the form
//            $eform_subsistence = DB::table('eform_subsistence')
//                ->where('id', $form->id)
//                ->get()->first();
//
//            //get the claimant with the user unit which has the workflow details
//            $user_unit = ConfigWorkFlow::where('user_unit_code',$form->user_unit_code )
//                ->where('user_unit_cc_code',$form->cost_center)
//                ->where('user_unit_bc_code',$form->business_unit_code)->first();
//
//            try {
//               $test =  $user_unit->user_unit_cc_code ;
//            }catch (\Exception $exception){
//                $user = User::find($form->created_by);
//             $user_unit = $user->user_unit;
//            }
//
//            //make the update
//            $update_eform_petty_cash = DB::table('eform_subsistence')
//                ->where('id', $form->id )
//                ->update([
//
//                    'cost_center' => $user_unit->user_unit_cc_code,
//                    'business_unit_code' => $user_unit->user_unit_bc_code,
//                    'user_unit_code' => $user_unit->user_unit_code,
//
//                    'hod_code' => $user_unit->hod_code,
//                    'hod_unit' => $user_unit->hod_unit,
//                    'ca_code' =>  $user_unit->ca_code,
//                    'ca_unit' =>  $user_unit->ca_unit,
//                    'hrm_code' => $user_unit->hrm_code,
//                    'hrm_unit' => $user_unit->hrm_unit,
//                    'expenditure_code' => $user_unit->expenditure_code,
//                    'expenditure_unit' => $user_unit->expenditure_unit,
//                    'security_code' => $user_unit->security_code,
//                    'security_unit' => $user_unit->security_unit
//                ]);
//
//          //  dd($update_eform_petty_cash);
//
//        }
        //  dd($eform_subsistence_all);

//        $eform_subsistence = DB::select("SELECT * FROM eform_subsistence where id =  {$id} ");
//        $eform_subsistence = SubsistenceAccountModel::hydrate($eform_subsistence);
//
//        $claimant = User::find($eform_subsistence[0]->created_by);
//        $user_unit_code = $claimant->user_unit->code;
//        $superior_code = $claimant->position->superior_code;
//        $eform_subsistence = DB::table('eform_subsistence')
//            ->where('id', $id)
//            ->update(['code_superior' => $superior_code,
//                'user_unit_code' => $user_unit_code,
//            ]);
        //redirect home
        return Redirect::route('subsistence.home')->with('message', 'Subsistence Voucher have been synced successfully');

        dd($claimant->position->superior_code ?? "");
    }

    public function reportsExportUnmarkExported($value)
    {
        //get a list of forms with the above status
        $tasks = SubsistenceAccountModel::find($value);
        //umark them
        dd($tasks);
    }

    public function reportsExportUnmarkExportedAll()
    {
        //get a list of forms with the above status
        // $tasks = SubsistenceAccountModel::where('status_id', config('constants.subsistence_status.exported'))->get();
        $exported = config('constants.subsistence_status.exported');
        $tasks = DB::select("SELECT * FROM eform_subsistence_account
                        WHERE status_id = {$exported}
                        ORDER BY eform_subsistence_id ASC ");
        $tasks = SubsistenceAccountModel::hydrate($tasks);

        foreach ($tasks as $item) {
//            $item->status_id = config('constants.subsistence_status.not_exported');
//            $item->save();

            $previous_status = config('constants.subsistence_status.not_exported');
            $id = $item->id;
            $eform_subsistence_item = DB::table('eform_subsistence_account')
                ->where('id', $id)
                ->update(['status_id' => $previous_status]);

        }
        //redirect home
        return Redirect::back()->with('message', 'Subsistence Exported Accounts have been reversed successfully');
    }

    public function markAccountLinesAsDuplicates($id)
    {
        //$id = 124 ;
        $account_line = DB::select("SELECT * FROM eform_subsistence_account where id =  {$id} ");
        $account_line = SubsistenceAccountModel::hydrate($account_line);
        $size = sizeof($account_line);
        if ($size > 0) {
            $item = $account_line[$size - 1];
            $item->status_id = config('constants.subsistence_status.void');
            $item->save();
        }
        //redirect home
        return Redirect::back()->with('message', 'Subsistence Account Line have been Marked as Duplicate successfully');
    }

    public function reverse(Request $request, $id)
    {
        try {
            // get the form using its id
            $eform_subsistence = DB::select("SELECT * FROM eform_subsistence where id =  {$id} ");
            $eform_subsistence = SubsistenceModel::hydrate($eform_subsistence)->first();
            $eform_subsistence->load('status');
            //new status
            $status = StatusModel::find($request->new_status_name);
            //update form
            $eform_subsistence_update = DB::table('eform_subsistence')
                ->where('id', $id)
                ->update(['config_status_id' => $status->id]);
            //update accounts
            $eform_accounts_update = DB::table('eform_subsistence_account')
                ->where('id', $id)
                ->update(['eform_subsistence_id' => $eform_subsistence->id]);

            $user = Auth::user();
            // log the activity
            ActivityLogsController::store($request, "Subsistence Status manual change", "status update of subsistence " . $eform_subsistence->code, $user->name . " updated status of subsistence voucher from " . $eform_subsistence->status->name . " to " . $status->name, $eform_subsistence->id);
            return Redirect::route('subsistence.home')->with('message', 'Subsistence (' . $eform_subsistence->code . ') Has been set to a new Status ' . $status->name . ' from ' . $eform_subsistence->status->name);
        } catch (Exception $exception) {
            return Redirect::back()->with('error', 'Sorry an error happened');
        }


//        try {
//            // get the form using its id
//            $eform_subsistence = DB::select("SELECT * FROM eform_subsistence where id =  {$id} ");
//            $eform_subsistence = SubsistenceAccountModel::hydrate($eform_subsistence);
//
//            //get current status id
//            $status_model = StatusModel::where('id', $eform_subsistence[0]->config_status_id)
//                ->where('eform_id', config('constants.eforms_id.subsistence'))->first();
//            $current_status = $status_model->id;
//
//            //new status
//            $new_status_id = $current_status - 1;
//            $status_model = StatusModel::where('id', $new_status_id)
//                ->where('eform_id', config('constants.eforms_id.subsistence'))->first();
//            $previous_status = $status_model->id;
//
//            //  $eform_subsistence = DB::select("UPDATE eform_subsistence SET config_status_id = {$previous_status} where id =  {$id} ");
//            $eform_subsistence = DB::table('eform_subsistence')
//                ->where('id', $id)
//                ->update(['config_status_id' => $previous_status]);
//
//            $user = Auth::user();
//            //save reason
////            $reason = EformApprovalsModel::updateOrCreate(
////                [
////                    'profile' => $user->profile_id,
////                    'title' => $user->profile_id,
////                    'name' => $user->name,
////                    'staff_no' => $user->staff_no,
////                    'reason' => $request->reason,
////                    'action' => $request->approval,
////                    'current_status_id' => $current_status,
////                    'action_status_id' => $previous_status,
////                    'config_eform_id' => config('constants.eforms_id.subsistence'),
////                    'eform_id' => $eform_subsistence[0]->id,
////                    'created_by' => $user->id,
////                ]);
//
//            return Redirect::back()->with('message', 'Subsistence Account Line have been dropped to the previous stage successfully');
//        } catch (Exception $exception) {
//            return Redirect::back()->with('error', 'Sorry an error happened');
//        }
    }

    public function reportsSync()
    {
        try {
//            /*
//             * NEEDED AS A FUNCTION SOMEWHERE IN Subsistence CONTROLLER

            //UPDATE ONE  - Update all Subsistence accounts with the user unit and work-flow details
            //get a list of all the Subsistence account models
            $tasks = DB::select("SELECT * FROM eform_subsistence_account
                            ORDER BY eform_subsistence_id ASC ");
            $tasks = SubsistenceAccountModel::hydrate($tasks);

            foreach ($tasks as $account) {
                //get associated Subsistence
                $petty_cash_id = $account->eform_subsistence_id;
                $tasks_pt = DB::select("SELECT * FROM eform_subsistence
                            WHERE id = {$petty_cash_id}  ");
                $tasks_pt = SubsistenceModel::hydrate($tasks_pt)->first();

                //update account with the Subsistence details
                $eform_subsistence_account = DB::table('eform_subsistence_account')
                    ->where('id', $account->id)
                    ->update([
                        'cost_center' => $tasks_pt->cost_center,
                        'business_unit_code' => $tasks_pt->business_unit_code,
                        'user_unit_code' => $tasks_pt->user_unit_code,

                        'claimant_name' => $tasks_pt->claimant_name,
                        'claimant_staff_no' => $tasks_pt->claimant_staff_no,
                        'claim_date' => $tasks_pt->claim_date,
                        'petty_cash_code' => $tasks_pt->code,

                        'hod_code' => $tasks_pt->hod_code,
                        'hod_unit' => $tasks_pt->hod_unit,
                        'ca_code' => $tasks_pt->ca_code,
                        'ca_unit' => $tasks_pt->ca_unit,
                        'hrm_code' => $tasks_pt->hrm_code,
                        'hrm_unit' => $tasks_pt->hrm_unit,
                        'expenditure_code' => $tasks_pt->expenditure_code,
                        'expenditure_unit' => $tasks_pt->expenditure_unit,
                        'security_code' => $tasks_pt->security_code,
                        'security_unit' => $tasks_pt->security_unit,
                    ]);
            }
            // */
            return Redirect::back()->with('message', 'Subsistence Account Line have been dropped to the previous stage successfully');
        } catch (Exception $exception) {
            return Redirect::back()->with('error', 'Sorry an error happened');
        }
    }

    public function search(Request $request)
    {
        $search = strtoupper($request->search);
        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $list = DB::select("SELECT * FROM eform_subsistence
              where code LIKE '%{$search}%'
              or claimant_name LIKE '%{$search}%'
              or claimant_staff_no LIKE '%{$search}%'
              or config_status_id LIKE '%{$search}%'
            ");
            $list = SubsistenceModel::hydrate($list);
        } else {

            //find the Subsistence with that id
            $list = SubsistenceModel::
            where('code', 'LIKE', "%{$search}%")
                ->orWhere('claimant_name', 'LIKE', "%{$search}%")
                ->orWhere('claimant_staff_no', 'LIKE', "%{$search}%")
                ->orWhere('config_status_id', 'LIKE', "%{$search}%")
                ->paginate(50);
        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))->get();
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();
        $category = "Search Results";

        //list of statuses
        $statuses = StatusModel::where('eform_id', config('constants.eforms_id.subsistence'))->get();


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
        return view('eforms.subsistence.list')->with($params);
    }

    public function search1(Request $request)
    {
        if (Auth::user()->type_id == config('constants.user_types.developer')) {

            // dd(222);
            $list = DB::select("SELECT * FROM eform_subsistence
              where code LIKE '%{$request->search}%'
              or claimant_name LIKE '%{$request->search}%'
              or claimant_staff_no LIKE '%{$request->search}%'
              or claim_date LIKE '%{$request->search}%'
              or AUTHORISED_BY LIKE '%{$request->search}%'
              or AUTHORISED_STAFF_NO LIKE '%{$request->search}%'
              or STATION_MANAGER LIKE '%{$request->search}%'
              or STATION_MANAGER LIKE '%{$request->search}%'
              or ACCOUNTANT LIKE '%{$request->search}%'
              or ACCOUNTANT_STAFF_NO LIKE '%{$request->search}%'
              or EXPENDITURE_OFFICE LIKE '%{$request->search}%'
              or EXPENDITURE_OFFICE_STAFF_NO LIKE '%{$request->search}%'
              or SECURITY_NAME LIKE '%{$request->search}%'
              or SECURITY_STAFF_NO LIKE '%{$request->search}%'
              or total_payment LIKE '%{$request->search}%'
              or config_status_id LIKE '%{$request->search}%'
            ");
            $list = SubsistenceModel::hydrate($list)->all();
        } else {
            //find the Subsistence with that id
            $list = SubsistenceModel::
            where('code', 'LIKE', "%{$request->search}%")
                ->orWhere('claimant_name', 'LIKE', "%{$request->search}%")
                ->orWhere('claimant_staff_no', 'LIKE', "%{$request->search}%")
                ->orWhere('claim_date', 'LIKE', "%{$request->search}%")
                ->orWhere('AUTHORISED_BY', 'LIKE', "%{$request->search}%")
                ->orWhere('AUTHORISED_STAFF_NO', 'LIKE', "%{$request->search}%")
                ->orWhere('STATION_MANAGER', 'LIKE', "%{$request->search}%")
                ->orWhere('STATION_MANAGER_STAFF_NO', 'LIKE', "%{$request->search}%")
                ->orWhere('ACCOUNTANT', 'LIKE', "%{$request->search}%")
                ->orWhere('ACCOUNTANT_STAFF_NO', 'LIKE', "%{$request->search}%")
                ->orWhere('EXPENDITURE_OFFICE', 'LIKE', "%{$request->search}%")
                ->orWhere('EXPENDITURE_OFFICE_STAFF_NO', 'LIKE', "%{$request->search}%")
                ->orWhere('SECURITY_NAME', 'LIKE', "%{$request->search}%")
                ->orWhere('SECURITY_STAFF_NO', 'LIKE', "%{$request->search}%")
                ->orWhere('total_payment', 'LIKE', "%{$request->search}%")
                ->orWhere('config_status_id', 'LIKE', "%{$request->search}%")
                ->get();
        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.subsistence'))->get();
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //pending forms for me before i apply again
        $pending = HomeController::pendingForMe();
        $category = "Search Results";

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


}
