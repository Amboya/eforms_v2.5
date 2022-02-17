<?php

namespace App\Http\Controllers\Eforms\KilometerAllowance;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\EForms\KilometerAllowance\KilometerAllowanceAccountModel;
use App\Models\Main\ProfileDelegatedModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\EForms\KilometerAllowance\KilometerAllowanceModel;
use App\Models\Main\TotalsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Models\Main\AttachedFileModel;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\AccountsChartModel;
use App\Models\Main\ProfilePermissionsModel;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\StatusModel;
use App\Models\User;
use Mockery\CountValidator\Exception;

class KilometerAllowanceController extends Controller
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
        session(['eform_id' => config('constants.eforms_id.kilometer_allowance')]);
        session(['eform_code' => config('constants.eforms_name.kilometer_allowance')]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request, $value)
    {

        $list_for_auditors_action = 0;
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')) {
            /** check if auditor created last months files */
            $last_month = Carbon::now()->subDays(30)->toDateTimeString();
            $list_for_auditors_action = KilometerAllowanceModel::
            where('config_status_id', config('constants.kilometer_allowance_status.closed'))
                ->where('created_at', '>=', $last_month)
                ->count();
        }

        //get list of all kilometer allowance forms for today
        if ($value == "all") {
            if ($list_for_auditors_action > 1) {
                // not cleared
                $list = KilometerAllowanceModel::
                where('config_status_id', '!=', config('constants.kilometer_allowance_status.chief_accountant'))
                    ->orderBy('code')->paginate(50);
            } else {
                //cleared
                $list = KilometerAllowanceModel::orderBy('code')->paginate(50);
            }
            $category = "All";
        }
        else if ($value == "pending") {
            if ($list_for_auditors_action > 1) {
                // not cleared
                $list = KilometerAllowanceModel::
                where('config_status_id', '!=' , config('constants.kilometer_allowance_status.new_application'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.closed'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.audit_approved'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.rejected'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.audited'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.cancelled'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.void'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.receipt_approved'))
                    ->orderBy('code')->paginate(50);
            } else {
                //cleared
                $list = KilometerAllowanceModel::
                where('config_status_id', '!=' , config('constants.kilometer_allowance_status.new_application'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.closed'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.audit_approved'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.rejected'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.audited'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.cancelled'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.void'))
                    ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.receipt_approved'))
                    ->orderBy('code')->paginate(50);
            }
            $category = "Opened";
        } else if ($value == config('constants.kilometer_allowance_status.new_application')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.new_application'))
                ->orderBy('code')->paginate(50);
            $category = "New Application";
        } else if ($value == config('constants.kilometer_allowance_status.closed')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.receipt_approved'))
                ->orderBy('code')->paginate(50);
            $category = "Closed";
            //  dd(11);
        } else if ($value == config('constants.kilometer_allowance_status.rejected')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.rejected'))
                ->orderBy('code')->paginate(50);
            $category = "Rejected";
        } else if ($value == config('constants.kilometer_allowance_status.cancelled')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.cancelled'))
                ->orderBy('code')->paginate(50);

            $category = "Cancelled";
        } else if ($value == config('constants.kilometer_allowance_status.void')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.void'))
                ->orderBy('code')->paginate(50);
            $category = "Void";
        } else if ($value == config('constants.kilometer_allowance_status.audited')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.audited'))
                ->orderBy('code')->paginate(50);
            $category = "Audited";
        } else if ($value == config('constants.kilometer_allowance_status.queried')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.queried'))
                ->orderBy('code')->paginate(50);
            $category = "Queried";
        } else if ($value == "needs_me") {
            $list = $totals_needs_me = HomeController::needsMeList();
            $category = "Needs My Attention";
        } else if ($value == "admin") {
            $list = KilometerAllowanceModel::where('config_status_id', 0)
                ->orderBy('code')->paginate(50);
        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))->get();

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
        return view('eforms.kilometer-allowance.list')->with($params);

    }


    /**
     * Display a listing of the resource for the admin.
     *
     * @return \Illuminate\HtFtp\Response
     */
    public function records(Request $request, $value)
    {
        //get list of all kilometer allowance forms for today
        if ($value == "all") {

            $list = DB::table('eform_kilometer_allowance')
                ->select('eform_kilometer_allowance.*', 'config_status.name as status_name ', 'config_status.html as html ')
                ->join('config_status', 'eform_kilometer_allowance.config_status_id', '=', 'config_status.id')
                ->paginate(50);

        //  dd($list);

            $category = "All Records";
        } else if ($value == "pending") {
            $list = KilometerAllowanceModel::where('config_status_id', '>', config('constants.kilometer_allowance_status.new_application'))
                ->where('config_status_id', '<', config('constants.kilometer_allowance_status.closed'))
                ->orderBy('code')->paginate(50);
            $category = "Opened";
        } else if ($value == config('constants.kilometer_allowance_status.new_application')) {

            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.new_application'))
                ->orderBy('code')->paginate(50);
            $category = "New Application";

        } else if ($value == config('constants.kilometer_allowance_status.closed')) {

            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.closed'))
                ->orderBy('code')->paginate(50);
            $category = "Closed";

        } else if ($value == config('constants.kilometer_allowance_status.rejected')) {

            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.rejected'))
                ->orderBy('code')->paginate(50);

            $category = "Rejected";

        } else if ($value == "needs_me") {

            $list = HomeController::needsMeList();

            $category = "Needs My Attention";

        } else if ($value == "admin") {

        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))->get();

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
        return view('eforms.kilometer-allowance.records')->with($params);

    }

    /**
     * Mark the form as void.
     *
     * @return Response
     */
    public function void(Request $request, $id)
    {
        //GET THE PETTY CASH MODEL
        $list = DB::select("SELECT * FROM eform_kilometer_allowance where id = {$id} ");
        $form = KilometerAllowanceModel::hydrate($list)->first();
        //get the status
        $current_status = $form->status->id;
        $new_status = 0;
        $user = Auth::user();

        //HANDLE VOID REQUEST
        $new_status = config('constants.kilometer_allowance_status.void');

        //get status id
        $status_model = StatusModel::where('id', $new_status)
            ->where('eform_id', config('constants.eforms_id.kilometer_allowance'))->first();
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
                'config_eform_id' => config('constants.eforms_id.kilometer_allowance'),
                'eform_id' => $form->id,
                'created_by' => $user->id,
            ]);

        //redirect home
        return Redirect::route('kilometer.allowance.home')->with('message', 'Kilometer Allowance ' . $form->code . ' for has been marked as Void successfully');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $user = auth()->user();
        $projects = ProjectsModel::all();
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'projects' => $projects,
            'user' => $user
        ];
        //show the create form
        return view('eforms.kilometer-allowance.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //[1]get the logged in user
        $user = Auth::user();   //superior_code
        $error = false;

        //[1B] check pending forms for me before i apply again
        $pending = HomeController::pendingForMe();
        if ($pending >= 1) {
            //return with error msg
            return Redirect::route('kilometer.allowance.home')->with('error', 'Sorry, You can not raise a new kilometer allowance because you already have an open kilometer allowance. Please allow the opened one to be closed or cancelled');
        }

        //[2A] find my code superior
        $my_hods = self::findMyNextPerson(config('constants.kilometer_allowance_status.new_application'), $user->user_unit, $user);

        if (empty($my_hods)) {
            //prepare details
            $details = [
                'name' => "Team",
                'url' => 'kilometer.allowance.home',
                'subject' => "Petty-Cash-Voucher Path Configuration Needs Your Attention",
                'title' => "Path Configuration Not Defined For {$user->name}",
                'body' => "Please note that {$user->name} with Staff Number {$user->staff_no} and Phone/Extension {$user->phone}, managed to submit or raise new kilometer.allowance.voucher.
                     <br>But the voucher path is not completely configured. Please confirm that this is so and take action to correct this as soon as possible.
                     <br><br>
                     <b> Path for {$user->user_unit->user_unit_code} user-unit </b><br>
                   1: HOD -> {$user->user_unit->hod_code} : {$user->user_unit->hod_unit}  <br>
                   2: HR/Station Manager ->  {$user->user_unit->hrm_code} : {$user->user_unit->hrm_unit} <br>
                   3: Account -> {$user->user_unit->ca_code} : {$user->user_unit->ca_unit}  <br>
                   4: Expenditure -> {$user->user_unit->expenditure_code} : {$user->user_unit->expenditure_unit}  <br>
                   5: Security -> {$user->user_unit->security_code} : {$user->user_unit->security_unit}  <br>
                   Please assign the correct position code and position user-unit for {$user->user_unit->user_unit_code}. <br>
                <br>You can update the details by clicking on 'Kilometer Allowance Work Flow' menu, then search for {$user->user_unit->user_unit_code}
                 and 'Edit' to update the correct details . <br> <br>
                 Else the HOD has not registered or assigned the correct profile yet.
                 "
            ];

            //send emails
            $to = config('constants.team_email_list');
            $mail_to_is = Mail::to($to)->send(new SendMail($details));

            $error = true;
            //return with error msg

        }

        //generate the kilometer allowance unique code
        $code = self::randGenerator("KA", 1);

      //  dd($user->user_unit->user_unit_cc_code);

        //raise the voucher
        $formModel = KilometerAllowanceModel::updateOrCreate(
            [

                'destination'  => $request->destination,
                'purpose_of_visit' => $request->purpose,
                'start_date' => $request->period_of_stay_from,
                'end_date' => $request->period_of_stay_to,
                'reg_no' => $request->vehicle_reg_no,
                'engine_capacity' => $request->engine_capacity,
                'fuel_type' => $request->propelled_by,
                'kilometers' => $request->covered_kilometers,
                'pump_price' => $request->pump_price,
                'amount' => $request->claim_amount,
                'staff_name' => $request->staff_name,
                'staff_no' => $request->employee_number,
            ],
            [
                'code' => $code,
                'destination'  => $request->destination,
                'station'  => $request->station,
                'purpose_of_visit' => $request->purpose,
                'start_date' => $request->period_of_stay_from,
                'end_date' => $request->period_of_stay_to,
                'reg_no' => $request->vehicle_reg_no,
                'engine_capacity' => $request->engine_capacity,
                'fuel_type' => $request->propelled_by,
                'kilometers' => $request->covered_kilometers,
                'pump_price' => $request->pump_price,
                'amount' => $request->claim_amount,
                'staff_name' => $user->name,
                'staff_no' => $request->employee_number,
                'claim_date' => $request->date_claimant,
                'config_status_id' => config('constants.kilometer_allowance_status.new_application'),
                'profile' => Auth::user()->profile_id,

                'cost_centre' => $user->user_unit->user_unit_cc_code,
                'business_code' => $user->user_unit->user_unit_bc_code,
                'user_unit_code' => $user->user_unit->user_unit_code,

                'hod_code' => $user->user_unit->hod_code,
                'hod_unit' => $user->user_unit->hod_unit,
                'ca_code' => $user->user_unit->ca_code,
                'ca_unit' => $user->user_unit->ca_unit,
                'hrm_code' => $user->user_unit->hrm_code,
                'hrm_unit' => $user->user_unit->hrm_unit,
                'expenditure_code' => $user->user_unit->expenditure_code,
                'expenditure_unit' => $user->user_unit->expenditure_unit,
                'dm_code' => $user->user_unit->dm_code,
                'dm_unit' => $user->user_unit->dm_unit,
                'audit_code' => $user->user_unit->audit_code,
                'audit_unit' => $user->user_unit->audit_unit,

                'created_by' => $user->id,
            ]);


        /** upload quotation files */
        // upload the receipt files
        $files = $request->file('quotation');
        if ($request->hasFile('quotation')) {
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
                $path = $file->storeAs('public/kilometer_allowance_quotation', $fileNameToStore);

                //upload the receipt
                $file = AttachedFileModel::updateOrCreate(
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $formModel->code,
                        'form_type' => config('constants.eforms_id.kilometer_allowance'),
                        'file_type' => config('constants.file_type.quotation')
                    ],
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $formModel->code,
                        'form_type' => config('constants.eforms_id.kilometer_allowance'),
                        'file_type' => config('constants.file_type.quotation')
                    ]
                );
            }
        }

        /** send email to supervisor */
        //get team email addresses

        $names = "";
        $to = [];
        //add hods email addresses
        foreach ($my_hods as $item) {
            $to[] = ['email' => $item->email, 'name' => $item->name];
            $names = $names . '<br>' . $item->name;
        }

        //prepare details
        $details = [
            'name' => $names,
            'url' => 'kilometer.allowance.home',
            'subject' => "New Petty-Cash Voucher Needs Your Attention",
            'title' => "New Petty-Cash Voucher Needs Your Attention {$user->name}",
            'body' => "Please note that {$user->name} with Staff Number {$user->staff_no} has successfully raised a kilometer.allowance.voucher with
                   <br> Serial: {$formModel->code}  <br> Reference: {$formModel->ref_no} <br> Status: {$formModel->status->name}  and <br> <b>Amount: ZMW {$request->total_payment}</b></br>. <br>
            This voucher now needs your approval, kindly click on the button below to login to E-ZESCO and take action on the voucher.<br> regards. "
        ];
        // send mail
     //   $mail_to_is = Mail::to($to)->send(new SendMail($details));

        if ($error) {
            // return with error msg
            return Redirect::route('kilometer.allowance.home')->with('error', 'Sorry!, The superior who is supposed to approve your kilometer allowance,
                       <br> has not registered or not fully configured yet, Please, <b>try first contacting your superior</b> so as to make sure he/she has registered in the system,
                       then you can contact eZESCO Admins (1142,1126,2350,2345,3309,3306 or 3319) isd@zesco.co.zm to configure your kilometer allowance voucher path. Your kilometer.allowance.voucher has been saved.');
        } else {
            // return the view
            return Redirect::route('kilometer.allowance.home')->with('message', 'Kilometer Allowance Details for ' . $formModel->code . ' have been Created successfully');
        }
    }


    /**
     * Fetch a list of my HODs
     * @param $user
     * @return array
     */
    public function findMyNextPerson($current_status, $user_unit, $claimant)
    {
        $users_array = [];
        $not_claimant = true;

        //FOR MY HOD USERS
        if ($current_status == config('constants.kilometer_allowance_status.new_application')) {
            $superior_user_unit = $user_unit->hod_unit;
            $superior_user_code = $user_unit->hod_code;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));

        } elseif ($current_status == config('constants.kilometer_allowance_status.hod_approved')) {
            $superior_user_code = $user_unit->dm_code;
            $superior_user_unit = $user_unit->dm_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_015'));

        } elseif ($current_status == config('constants.kilometer_allowance_status.manager_approved')) {
            $superior_user_code = $user_unit->hrm_code;
            $superior_user_unit = $user_unit->hrm_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_009'));

        } elseif ($current_status == config('constants.kilometer_allowance_status.hr_approved')) {
            $superior_user_code = $user_unit->ca_code;
            $superior_user_unit = $user_unit->ca_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_007'));

        } elseif ($current_status == config('constants.kilometer_allowance_status.chief_accountant')) {
            $superior_user_unit = $user_unit->audit_unit;
            $superior_user_code = $user_unit->audit_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_011'));

        }  elseif ($current_status == config('constants.kilometer_allowance_status.audited')) {
            $superior_user_unit = $user_unit->expenditure_unit;
            $superior_user_code = $user_unit->expenditure_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_011'));

        } elseif ($current_status == config('constants.kilometer_allowance_status.funds_disbursement')) {
            $not_claimant = false;

        } elseif ($current_status == config('constants.kilometer_allowance_status.funds_acknowledgement')) {
            $superior_user_unit = $user_unit->security_unit;
            $superior_user_code = $user_unit->security_code;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_013'));

        } elseif ($current_status == config('constants.kilometer_allowance_status.security_approved')) {
            $superior_user_unit = $user_unit->expenditure_unit;
            $superior_user_code = $user_unit->expenditure_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_014'));
            // dd(1);
        } elseif ($current_status == config('constants.kilometer_allowance_status.closed')) {
            $superior_user_unit = $user_unit->audit_unit;
            $superior_user_code = $user_unit->audit_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_011'));
            // dd(1);
        } else {
            //no one
            $superior_user_unit = "0";
            $superior_user_code = "0";
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_002'));
        }

        if ($not_claimant) {
            //SELECT USERS
            $users_list[] = '';
            //[A]check for any users who have this assigned profile
            $assigned_users = ProfileAssigmentModel::
            where('eform_id', config('constants.eforms_id.kilometer_allowance'))
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
            where('eform_id', config('constants.eforms_id.kilometer_allowance'))
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
     * Generate Voucher Code
     * @param $head
     * @return string
     */
    public function randGenerator($head, $value)
    {
        // use the total number of kilometer allowance in the system
        $count = DB::select("SELECT count(id) as total FROM eform_kilometer_allowance ");

        //random number
        // $random = rand(1, 9999999);
        $random = $count[0]->total;  // count total and begin again
        // $random = $size->total ;  // oracle sequence
        $random = sprintf("%07d", ($random + $value));
        $random = $head . $random;

        $count_existing_forms = DB::select("SELECT count(id) as total FROM eform_kilometer_allowance WHERE code = '{$random}'");
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
     * @return Response
     */
    public function show($id)
    {
        //GET THE PETTY CASH MODEL if you are an admin
        //  if (Auth::user()->type_id == config('constants.user_types.developer')) {
        $list = DB::select("SELECT * FROM eform_kilometer_allowance where id = {$id} ");
        $form = KilometerAllowanceModel::hydrate($list)->first();
//        } else {
//            //find the kilometer allowance with that id
//            $form = KilometerAllowanceModel::find($id);
//        }


        $receipts = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.kilometer_allowance'))
            ->where('file_type', config('constants.file_type.receipt'))
            ->get();
        $quotations = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.kilometer_allowance'))
            ->where('file_type', config('constants.file_type.quotation'))
            ->get();
        $form_accounts = KilometerAllowanceAccountModel::where('eform_kilometer_allowance_id', $id)->get();
        $projects = ProjectsModel::all();
        $accounts = AccountsChartModel::all();
        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.kilometer_allowance'))
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
            'user' => Auth::user(),
            'accounts' => $accounts
        ];
        //return view
        return view('eforms.kilometer-allowance.show')->with($params);

    }


    public function showForm($id)
    {
        //GET THE PETTY CASH MODEL if you are an admin
        $list = DB::select("SELECT * FROM eform_kilometer_allowance where id = {$id} ");
        $form = KilometerAllowanceModel::hydrate($list)->first();

        $receipts = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.kilometer_allowance'))
            ->where('file_type', config('constants.file_type.receipt'))
            ->get();

        $quotations = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.kilometer_allowance'))
            ->where('file_type', config('constants.file_type.quotation'))
            ->get();

        $form_accounts = KilometerAllowanceAccountModel::where('eform_kilometer_allowance_id', $id)->get();
        $projects = ProjectsModel::all();
        $accounts = AccountsChartModel::all();
        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.kilometer_allowance'))
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
            'user' => Auth::user(),
            'projects' => $projects,
            'user_array' => $user_array,
            'approvals' => $approvals,
            'accounts' => $accounts
        ];


        //return view
        return view('eforms.kilometer-allowance.show')->with($params);

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
        //GET THE PETTY CASH MODEL
        $form = KilometerAllowanceModel::find($request->id);
        $current_status = $form->status->id;
        $user = Auth::user();

        //FOR CLAIMANT CANCELLATION
        if (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_002')
            && $current_status == config('constants.kilometer_allowance_status.new_application')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } else {
                $new_status = config('constants.kilometer_allowance_status.new_application');
                $insert_reasons = false;
            }
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        } //FOR HOD
        elseif (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_004')
            && $current_status == config('constants.kilometer_allowance_status.new_application')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.hod_approved');
            } else {
                $new_status = config('constants.kilometer_allowance_status.new_application');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->authorised_by = $user->name;
            $form->authorised_staff_no = $user->staff_no;
            $form->authorised_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }
        //FOR SENIOR MANAGER
        elseif (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_015')
            && $current_status == config('constants.kilometer_allowance_status.hod_approved')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.manager_approved');
            } else {
                $new_status = config('constants.kilometer_allowance_status.hod_approved');
                $insert_reasons = false;
            }

            //update
            $form->config_status_id = $new_status;
            $form->station_manager = $user->name;
            $form->station_manager_staff_no = $user->staff_no;
            $form->station_manager_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();

        }
        //FOR CHIEF HR
        elseif (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_009')
            && $current_status == config('constants.kilometer_allowance_status.manager_approved')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.hr_approved');
            } else {
                $new_status = config('constants.kilometer_allowance_status.hod_approved');
                $insert_reasons = false;
            }

            //update
            $form->config_status_id = $new_status;

            $form->hrm_manager = $user->name;
            $form->hrm_manager_staff_no = $user->staff_no;
            $form->hrm_manager_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();

        }


        //FOR FOR CHIEF ACCOUNTANT
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_007')
            && $current_status == config('constants.kilometer_allowance_status.hr_approved')
        ) {
            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.chief_accountant');
            } else {
                $new_status = config('constants.kilometer_allowance_status.hr_approved');
                $insert_reasons = false;
            }

            //dd($new_status);
            //update
            $form->config_status_id = $new_status;
            $form->accountant = $user->name;
            $form->accountant_staff_no = $user->staff_no;
            $form->accountant_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        } //FOR FOR EXPENDITURE OFFICE FUNDS
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')
            && $current_status == config('constants.kilometer_allowance_status.audited')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.funds_disbursement');
            } else {
                $new_status = config('constants.kilometer_allowance_status.chief_accountant');
                $insert_reasons = false;
            }

            //update
            $form->config_status_id = $new_status;
            $form->expenditure_office = $user->name;
            $form->expenditure_office_staff_no = $user->staff_no;
            $form->expenditure_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();


            //create records for the accounts associated with this kilometer allowance transaction
            for ($i = 0; $i < sizeof($request->credited_amount); $i++) {


                $des = "";
                $des = $des . " " . $form->destination. ": " . $form->purpose_of_visit. ",";
                $des = "Kilometer Claim Serial: " . $form->code . ", Claimant: " . $form->staff_name . ', Destination/Purpose : ' . $des . ' Amount: ZMW ' . $request->credited_amount[$i] . '.';

                //[1] CREDITED ACCOUNT
                //[1A] - money
                $formAccountModel = KilometerAllowanceAccountModel::updateOrCreate(
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        'creditted_amount' => $request->credited_amount[$i],
                        'account' => $request->credited_account[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        //'debitted_amount' => $request->debited_amount[$i],
                        'eform_kilometer_allowance_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'status_id' => config('constants.kilometer_allowance_status.export_not_ready')
                    ],
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        'creditted_amount' => $request->credited_amount[$i],
                        'account' => $request->credited_account[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        //'debitted_amount' => $request->debited_amount[$i],

                        'eform_kilometer_allowance_id' => $form->id,
                        'kilometer_allowance_code' => $form->code,
                        'cost_center' => $form->cost_center,
                        'business_unit_code' => $form->business_unit_code,
                        'user_unit_code' => $form->user_unit_code,
                        'staff_name' => $form->staff_name,
                        'staff_no' => $form->staff_no,
                        'claim_date' => $form->claim_date,

                        'hod_code' => $form->hod_code,
                        'hod_unit' => $form->hod_unit,
                        'ca_code' => $form->ca_code,
                        'ca_unit' => $form->ca_unit,
                        'hrm_code' => $form->hrm_code,
                        'hrm_unit' => $form->hrm_unit,
                        'expenditure_code' => $form->expenditure_code,
                        'expenditure_unit' => $form->expenditure_unit,
                        'security_code' => $form->security_code,
                        'security_unit' => $form->security_unit,
                        'audit_code' => $form->audit_code,
                        'audit_unit' => $form->audit_unit,

                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                        'status_id' => config('constants.kilometer_allowance_status.export_not_ready')
                    ]
                );

                //[2] DEBITED ACCOUNT
                //[2A] - money
                $formAccountModel = KilometerAllowanceAccountModel::updateOrCreate(
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        //'creditted_amount' => $request->credited_amount[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        'debitted_amount' => $request->debited_amount[$i],
                        'account' => $request->debited_account[$i],
                        'eform_kilometer_allowance_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'status_id' => config('constants.kilometer_allowance_status.export_not_ready')
                    ],
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        //'creditted_amount' => $request->credited_amount[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        'debitted_amount' => $request->debited_amount[$i],
                        'account' => $request->debited_account[$i],

                        'eform_kilometer_allowance_id' => $form->id,
                        'kilometer_allowance_code' => $form->code,
                        'cost_center' => $form->cost_center,
                        'business_unit_code' => $form->business_unit_code,
                        'user_unit_code' => $form->user_unit_code,
                        'staff_name' => $form->staff_name,
                        'staff_no' => $form->staff_no,
                        'claim_date' => $form->claim_date,
                        'hod_code' => $form->hod_code,
                        'hod_unit' => $form->hod_unit,
                        'ca_code' => $form->ca_code,
                        'ca_unit' => $form->ca_unit,
                        'hrm_code' => $form->hrm_code,
                        'hrm_unit' => $form->hrm_unit,
                        'expenditure_code' => $form->expenditure_code,
                        'expenditure_unit' => $form->expenditure_unit,
                        'security_code' => $form->security_code,
                        'security_unit' => $form->security_unit,
                        'audit_code' => $form->audit_code,
                        'audit_unit' => $form->audit_unit,

                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                        'status_id' => config('constants.kilometer_allowance_status.export_not_ready')
                    ]
                );
            }

        } //FOR CLAIMANT - ACKNOWLEDGEMENT
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_002')
            && $current_status == config('constants.kilometer_allowance_status.funds_disbursement')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.funds_acknowledgement');
            } else {
                $new_status = config('constants.kilometer_allowance_status.funds_disbursement');
                $insert_reasons = false;
            }

           // dd($new_status);
            //update
            $form->config_status_id = $new_status;
//          $form->profile = Auth::user()->profile_id;
            $form->profile = config('constants.user_profiles.EZESCO_007');
            $form->save();
        } //FOR FOR SECURITY
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_013')
            && $current_status == config('constants.kilometer_allowance_status.funds_acknowledgement')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.security_approved');
            } else {
                $new_status = config('constants.kilometer_allowance_status.funds_acknowledgement');
                $insert_reasons = false;
            }

            //update
            $form->config_status_id = $new_status;
            $form->security_office = $user->name;
            $form->security_staff_no = $user->staff_no;
            $form->security_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }
        //FOR FOR EXPENDITURE OFFICE - RECEIPT
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')
            && $current_status == config('constants.kilometer_allowance_status.security_approved')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.receipt_approved');
            } else {
                $new_status = config('constants.kilometer_allowance_status.security_approved');
                $insert_reasons = false;
            }

            //update the form
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
                $des = $des . " " . $form->destination . "/,". $form->purpose_of_visit.",";
                $des = "Kilometer Claim Serial: " . $form->code . ", Claimant: " . $form->staff_name . ', Destination/Purpose : ' . $des . ' Amount: ' . $request->credited_amount . '.';

                //[1] CREDITED ACCOUNT
                //[1A] - money
                $formAccountModel = KilometerAllowanceAccountModel::updateOrCreate(
                    [
                        'creditted_account_id' => $request->credited_account,
                        'creditted_amount' => $request->credited_amount,
                        'account' => $request->credited_account,
                        'debitted_account_id' => $request->debited_account,
                        //'debitted_amount' => $request->debited_amount,
                        'eform_kilometer_allowance_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'status_id' => config('constants.kilometer_allowance_status.export_not_ready')
                    ],
                    [
                        'creditted_account_id' => $request->credited_account,
                        'creditted_amount' => $request->credited_amount,
                        'account' => $request->credited_account,
                        'debitted_account_id' => $request->debited_account,
                        //'debitted_amount' => $request->debited_amount,

                        'eform_kilometer_allowance_id' => $form->id,
                        'kilometer_allowance_code' => $form->code,
                        'cost_center' => $form->cost_center,
                        'business_unit_code' => $form->business_unit_code,
                        'user_unit_code' => $form->user_unit_code,
                        'staff_name' => $form->staff_name,
                        'staff_no' => $form->staff_no,
                        'claim_date' => $form->claim_date,
                        'hod_code' => $form->hod_code,
                        'hod_unit' => $form->hod_unit,
                        'ca_code' => $form->ca_code,
                        'ca_unit' => $form->ca_unit,
                        'hrm_code' => $form->hrm_code,
                        'hrm_unit' => $form->hrm_unit,
                        'expenditure_code' => $form->expenditure_code,
                        'expenditure_unit' => $form->expenditure_unit,
                        'security_code' => $form->security_code,
                        'security_unit' => $form->security_unit,
                        'audit_code' => $form->audit_code,
                        'audit_unit' => $form->audit_unit,

                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                        'status_id' => config('constants.kilometer_allowance_status.export_not_ready')
                    ]
                );

                //[2] DEBITED ACCOUNT
                //[2A] - money
                $formAccountModel = KilometerAllowanceAccountModel::updateOrCreate(
                    [
                        'creditted_account_id' => $request->credited_account,
                        //'creditted_amount' => $request->credited_amount,
                        'debitted_account_id' => $request->debited_account,
                        'debitted_amount' => $request->debited_amount,
                        'account' => $request->debited_account,
                        'eform_kilometer_allowance_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'status_id' => config('constants.kilometer_allowance_status.export_not_ready')
                    ],
                    [
                        'creditted_account_id' => $request->credited_account,
                        //'creditted_amount' => $request->credited_amount,
                        'debitted_account_id' => $request->debited_account,
                        'debitted_amount' => $request->debited_amount,
                        'account' => $request->debited_account,

                        'eform_kilometer_allowance_id' => $form->id,
                        'kilometer_allowance_code' => $form->code,
                        'cost_center' => $form->cost_center,
                        'business_unit_code' => $form->business_unit_code,
                        'user_unit_code' => $form->user_unit_code,
                        'staff_name' => $form->staff_name,
                        'staff_no' => $form->staff_no,
                        'claim_date' => $form->claim_date,
                        'hod_code' => $form->hod_code,
                        'hod_unit' => $form->hod_unit,
                        'ca_code' => $form->ca_code,
                        'ca_unit' => $form->ca_unit,
                        'hrm_code' => $form->hrm_code,
                        'hrm_unit' => $form->hrm_unit,
                        'expenditure_code' => $form->expenditure_code,
                        'expenditure_unit' => $form->expenditure_unit,
                        'security_code' => $form->security_code,
                        'security_unit' => $form->security_unit,
                        'audit_code' => $form->audit_code,
                        'audit_unit' => $form->audit_unit,

                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                        'status_id' => config('constants.kilometer_allowance_status.export_not_ready')
                    ]
                );
            }

            //Make the update on the kilometer allowance account
            $export_not_ready = config('constants.kilometer_allowance_status.export_not_ready');
            $not_exported = config('constants.kilometer_allowance_status.not_exported');
            $id = $form->id;
            $formAccountModelList = DB::table('eform_kilometer_allowance_account')
                ->where('eform_kilometer_allowance_id', $id)
                ->where('status_id', $export_not_ready)
                ->update(
                    ['status_id' => $not_exported]
                );

            // upload the receipt files
            $files = $request->file('receipt');
            if ($request->hasFile('receipt')) {
                foreach ($files as $file) {
                    $filenameWithExt = preg_replace("/[^a-zA-Z]+/", "_", $file->getClientOriginalName());
                    // Get just filename
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    //get size
                    $size =   $file->getSize() * 0.000001 ;
                    // Get just ext
                    $extension = $file->getClientOriginalExtension();
                    // Filename to store
                    $fileNameToStore = trim(preg_replace('/\s+/', ' ', $filename . '_' . time() . '.' . $extension));
                    // Upload File
                    $path = $file->storeAs('public/kilometer_allowance_receipt', $fileNameToStore);

                    //upload the receipt
                    $file = AttachedFileModel::updateOrCreate(
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.kilometer_allowance'),
                            'file_type' => config('constants.file_type.receipt')
                        ],
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.kilometer_allowance'),
                            'file_type' => config('constants.file_type.receipt')
                        ]
                    );
                }
            }

        }
        //FOR AUDITING OFFICE
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_011')
            && $current_status == config('constants.kilometer_allowance_status.chief_accountant')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.audited');
            }//audit status
            elseif ($request->approval == config('constants.approval.queried')) {
                $new_status = config('constants.kilometer_allowance_status.queried');
            } else {
                $new_status = config('constants.kilometer_allowance_status.closed');
                $insert_reasons = false;
            }

            //update
            $form->config_status_id = $new_status;
            $form->audit_office = $user->name;
            $form->audit_staff_no = $user->staff_no;
            $form->audit_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }
        //FOR QUERIED RESOLVING
        elseif( Auth::user()->profile_id  ==  config('constants.user_profiles.EZESCO_014')
            &&  $form->config_status_id == config('constants.kilometer_allowance_status.queried')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.audited');
            }//audit status
            elseif ($request->approval == config('constants.approval.resolve')) {
                $new_status = config('constants.kilometer_allowance_status.closed');
            } else {
                $new_status = config('constants.kilometer_allowance_status.queried');
                $insert_reasons = false;
            }
              dd($new_status);
            //update
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }
        //FOR NO-ONE
        else {
            //return with an error
            return Redirect::route('kilometer.allowance.home')->with('message', 'Kilometer Allowance ' . $form->code . ' for has been ' . $request->approval . ' successfully');
        }

        //reason
        if ($insert_reasons) {
            //save reason
            $reason = EformApprovalsModel::updateOrCreate(
                [
                    'profile' => $user->profile_id,
                    'title' => $user->profile_id,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $request->reason,
                    'action' => $request->approval,
                    'config_eform_id' => config('constants.eforms_id.kilometer_allowance'),
                    'eform_id' => $form->id,
                    'created_by' => $user->id,
                ],
                [
                    'profile' => $user->profile_id,
                    'title' => $user->profile_id,
                    'name' => $user->name,
                    'staff_no' => $user->staff_no,
                    'reason' => $request->reason,
                    'action' => $request->approval,
                    'current_status_id' => $current_status,
                    'action_status_id' => $new_status,
                    'config_eform_id' => config('constants.eforms_id.kilometer_allowance'),
                    'eform_id' => $form->id,
                    'created_by' => $user->id,
                ]

            );
            //send the email
            self::nextUserSendMail($new_status, $form);

        }

        //redirect home
        return Redirect::route('kilometer.allowance.home')->with('message', $form->total_payment . ' kilometer.allowance.' . $form->code . ' for ' . $form->staff_name . ' has been ' . $request->approval . ' successfully');

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
        $user_array = self::nextUsers($new_status, $form->user_unit, $form->user);
        $names = "";
        $claimant_details = User::find($form->created_by);

        //check if this next profile is for a claimant and if the Petty-Cash needs Acknowledgement
        if ($new_status == config('constants.kilometer_allowance_status.security_approved')) {
            //message details
            $subject = 'Petty-Cash Voucher Needs Your Attention';
            $title = 'Petty-Cash Voucher Needs Your Attention';
            $message = 'This is to notify you that there is a <b>ZMW ' . $form->total_payment . '</b>  Petty-Cash Voucher (' . $form->code . ') raised by ' . $form->staff_name . ', that needs your attention.
            <br>Please login to e-ZESCO by clicking on the button below to take action on the voucher.<br>The form is currently at ' . $form->status->name . ' stage';
        } //check if this next profile is for a claimant and if the Petty-Cash is closed
        else if ($new_status == config('constants.kilometer_allowance_status.closed')) {
            $names = $names . '<br>' . $claimant_details->namee;
            //message details
            $subject = 'Petty-Cash Voucher Closed Successfully';
            $title = 'Petty-Cash Voucher Closed Successfully';
            $message = 'This is to notify you that kilometer.allowance.voucher ' . $form->code . ' has been closed successfully .
            <br>Please login to e-ZESCO by clicking on the button below to view the voucher. <br>The kilometer allowance voucher has now been closed.';
        } // other wise get the users
        else {
            //message details
            $subject = 'Petty-Cash Voucher Needs Your Attention';
            $title = 'Petty-Cash Voucher Needs Your Attention';
            $message = 'This is to notify you that there is a <b>ZMW ' . $form->total_payment . '</b>  Petty-Cash Voucher (' . $form->code . ') raised by ' . $form->staff_name . ',that needs your attention.
            <br>Please login to e-ZESCO by clicking on the button below to take action on the voucher.<br>The form is currently at ' . $form->status->name . ' stage.';
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
//        $to[] = ['email' => 'nshubart@zesco.co.zm', 'name' => 'Shubart Nyimbili'];
//        $to[] = ['email' => 'csikazwe@zesco.co.zm', 'name' => 'Chapuka Sikazwe'];
//        $to[] = ['email' => 'bchisulo@zesco.co.zm', 'name' => 'Bwalya Chisulo'];
        //prepare details
        $details = [
            'name' => $names,
            'url' => 'kilometer.allowance.home',
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


    public function nextUsers($new_status, $user_unit, $user)
    {
        $users_array = [];
        $not_claimant = true;

        //FOR MY HOD USERS
        if ($new_status == config('constants.kilometer_allowance_status.new_application')) {
            $superior_user_unit = $user_unit->hod_unit;
            $superior_user_code = $user_unit->hod_code;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_004'));
        } elseif ($new_status == config('constants.kilometer_allowance_status.hod_approved')) {
            $superior_user_code = $user_unit->hrm_code;
            $superior_user_unit = $user_unit->hrm_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_009'));
        } elseif ($new_status == config('constants.kilometer_allowance_status.hr_approved')) {
            $superior_user_code = $user_unit->ca_code;
            $superior_user_unit = $user_unit->ca_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_007'));
        } elseif ($new_status == config('constants.kilometer_allowance_status.chief_accountant')) {
            $superior_user_unit = $user_unit->expenditure_unit;
            $superior_user_code = $user_unit->expenditure_code;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_014'));
        } elseif ($new_status == config('constants.kilometer_allowance_status.funds_disbursement')) {
            $not_claimant = false;
        } elseif ($new_status == config('constants.kilometer_allowance_status.funds_acknowledgement')) {
            $superior_user_unit = $user_unit->security_unit;
            $superior_user_code = $user_unit->security_code;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_013'));
        } elseif ($new_status == config('constants.kilometer_allowance_status.security_approved')) {
            $superior_user_unit = $user_unit->expenditure_unit;
            $superior_user_code = $user_unit->expenditure_code;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_014'));
        } elseif ($new_status == config('constants.kilometer_allowance_status.closed')) {
            $superior_user_unit = $user_unit->audit_unit;
            $superior_user_code = $user_unit->audit_unit;
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_011'));
            // dd(1);
        } else {
            //no one
            $superior_user_unit = "0";
            $superior_user_code = "0";
            $profile = ProfileModel::find(config('constants.user_profiles.EZESCO_002'));
        }

        if ($not_claimant) {
            //SELECT USERS

            $users_list[] = '';
            //[A]check if the users in my user unit have this assigned profile
            $assigned_users = ProfileAssigmentModel::
            where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('profile', $profile->code)
                ->get();
            //loop through assigned users
            foreach ($assigned_users as $item) {
                if ($profile->id == config('constants.user_profiles.EZESCO_014') ||
                    $profile->id == config('constants.user_profiles.EZESCO_013') ||
                    $profile->id == config('constants.user_profiles.EZESCO_011')) {
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
            where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('delegated_profile', $profile->id)
                ->where('delegated_job_code', $superior_user_code)
                ->where('delegated_user_unit', $superior_user_unit)
                ->where('config_status_id', config('constants.active_state'))
                ->get();
            //loop through delegated users
            foreach ($delegated_users as $item) {
                $user = User::find($item->delegated_to);
                $users_array[] = $user;
            }

        } else {
            $users_array[] = $user;
            // $hods_array[] = $user;
        }

        //[3] return the list of users
        return $users_array;
    }

    public function reports(Request $request, $value)
    {
        //get the accounts
        $title = "";

        if ($value == config('constants.all')) {
            if (Auth::user()->type_id == config('constants.user_types.developer')) {
                $list = DB::select("SELECT * FROM eform_kilometer_allowance_account order by created_at desc  ");
                $list = KilometerAllowanceAccountModel::hydrate($list);
            } else {
                $list = KilometerAllowanceAccountModel::orderBy('created_at')->get();
            }
            $title = "ALl";
        } elseif ($value == config('constants.kilometer_allowance_status.not_exported')) {
            if (Auth::user()->type_id == config('constants.user_types.developer')) {
                $status = config('constants.kilometer_allowance_status.not_exported');
                $list = DB::select("SELECT * FROM eform_kilometer_allowance_account where status_id = {$status}  order by created_at desc   ");
                $list = KilometerAllowanceAccountModel::hydrate($list);
            } else {
                $list = KilometerAllowanceAccountModel::where('status_id', config('constants.kilometer_allowance_status.not_exported'))
                    ->orderBy('created_at')->get();
            }
            $title = "Not Exported";
        } elseif ($value == config('constants.kilometer_allowance_status.exported')) {
            if (Auth::user()->type_id == config('constants.user_types.developer')) {
                $status = config('constants.kilometer_allowance_status.exported');
                $list = DB::select("SELECT * FROM eform_kilometer_allowance_account where status_id = {$status}  order by created_at desc   ");
                $list = KilometerAllowanceAccountModel::hydrate($list);
            } else {
                $list = KilometerAllowanceAccountModel::where('status_id', config('constants.kilometer_allowance_status.exported'))
                    ->orderBy('created_at')->get();
            }
            $title = " Exported";
        } elseif ($value == config('constants.kilometer_allowance_status.export_failed')) {
            if (Auth::user()->type_id == config('constants.user_types.developer')) {
                $status = config('constants.kilometer_allowance_status.export_failed');
                $list = DB::select("SELECT * FROM eform_kilometer_allowance_account where status_id = {$status}  order by created_at desc   ");
                $list = KilometerAllowanceAccountModel::hydrate($list);
            } else {
                $list = KilometerAllowanceAccountModel::where('status_id', config('constants.kilometer_allowance_status.export_failed'))
                    ->orderBy('created_at')
                    ->get();
            }
            $title = "Failed Export";
        }


        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //data to send to the view
        $params = [
            'title' => $title,
            'totals_needs_me' => $totals_needs_me,
            'list' => $list,
        ];
        //  dd($list);
        return view('eforms.kilometer-allowance.report')->with($params);
    }

    public function reportsExport(Request $request)
    {

        // dd($request->all());
        $date_from = $request->date_from  ;
        $date_to = $request->date_to  ;

        $fileName = 'PettyCash_Accounts.csv';

        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $not_exported = config('constants.kilometer_allowance_status.not_exported');
            $tasks = DB::select("SELECT * FROM eform_kilometer_allowance_account
                        WHERE status_id = {$not_exported}
                        and created_at >= '{$date_from}'
                        and created_at <= '{$date_to}'
                        ORDER BY eform_kilometer_allowance_id ASC ");
            $tasks = KilometerAllowanceAccountModel::hydrate($tasks);
        } else {

            $tasks = KilometerAllowanceAccountModel::
            where('status_id', config('constants.kilometer_allowance_status.not_exported'))
                ->whereDate('created_at' , '>=', $date_from )
                ->whereDate('created_at' , '<=', $date_to )
                ->get();
        }

        //  dd($tasks);

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

                // dd($item);

                //mark the item as exported
//                $item->status_id = config('constants.kilometer_allowance_status.exported');
//                $item->save();

                //Make the update on the kilometer allowance account
                $previous_status = config('constants.kilometer_allowance_status.exported');
                $id = $item->id;
                $eform_kilometer_allowance_item = DB::table('eform_kilometer_allowance_account')
                    ->where('id', $id)
                    ->update(['status_id' => $previous_status]);

                $row['Code'] = $item->kilometer_allowance_code;
                $row['Claimant'] = $item->staff_name;
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
        $list = KilometerAllowanceModel:: select(DB::raw('cost_centre, name_of_claimant, count(id) as total_forms , sum(total_payment) as forms_sum '))
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
        return view('eforms.kilometer-allowance.chart')->with($params);
        //  dd($request);
    }

    public function sync($id)
    {
        //SYNC ONE
        //get the form
        $form = DB::table('eform_kilometer_allowance')
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
            return Redirect::back()->with('error', 'Kilometer Allowance Voucher did not sync, because of the user-unit problem.');
        }

        //make the update
        $update_eform_kilometer_allowance = DB::table('eform_kilometer_allowance')
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
//        $eform_kilometer_allowance_all = DB::select("SELECT * FROM eform_kilometer_allowance  ");

//        foreach ($eform_kilometer_allowance_all as $form) {
//
//            //get the form
//            $eform_kilometer_allowance = DB::table('eform_kilometer_allowance')
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
//            $update_eform_kilometer_allowance = DB::table('eform_kilometer_allowance')
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
//          //  dd($update_eform_kilometer_allowance);
//
//        }
        //  dd($eform_kilometer_allowance_all);


//        $eform_kilometer_allowance = DB::select("SELECT * FROM eform_kilometer_allowance where id =  {$id} ");
//        $eform_kilometer_allowance = KilometerAllowanceAccountModel::hydrate($eform_kilometer_allowance);
//
//        $claimant = User::find($eform_kilometer_allowance[0]->created_by);
//        $user_unit_code = $claimant->user_unit->code;
//        $superior_code = $claimant->position->superior_code;
//        $eform_kilometer_allowance = DB::table('eform_kilometer_allowance')
//            ->where('id', $id)
//            ->update(['code_superior' => $superior_code,
//                'user_unit_code' => $user_unit_code,
//            ]);

        //redirect home
        return Redirect::route('kilometer.allowance.home')->with('message', 'Kilometer Allowance Voucher have been synced successfully');

        dd($claimant->position->superior_code ?? "");
    }

    public function reportsExportUnmarkExported($value)
    {
        //get a list of forms with the above status
        $tasks = KilometerAllowanceAccountModel::find($value);
        //umark them
        dd($tasks);
    }

    public function reportsExportUnmarkExportedAll()
    {
        //get a list of forms with the above status
        // $tasks = KilometerAllowanceAccountModel::where('status_id', config('constants.kilometer_allowance_status.exported'))->get();
        $exported = config('constants.kilometer_allowance_status.exported');
        $tasks = DB::select("SELECT * FROM eform_kilometer_allowance_account
                        WHERE status_id = {$exported}
                        ORDER BY eform_kilometer_allowance_id ASC ");
        $tasks = KilometerAllowanceAccountModel::hydrate($tasks);

        foreach ($tasks as $item) {
//            $item->status_id = config('constants.kilometer_allowance_status.not_exported');
//            $item->save();

            $previous_status = config('constants.kilometer_allowance_status.not_exported');
            $id = $item->id;
            $eform_kilometer_allowance_item = DB::table('eform_kilometer_allowance_account')
                ->where('id', $id)
                ->update(['status_id' => $previous_status]);

        }
        //redirect home
        return Redirect::back()->with('message', 'Kilometer Allowance Exported Accounts have been reversed successfully');
    }

    public function markAccountLinesAsDuplicates($id)
    {
        //$id = 124 ;
        $account_line = DB::select("SELECT * FROM eform_kilometer_allowance_account where id =  {$id} ");
        $account_line = KilometerAllowanceAccountModel::hydrate($account_line);
        $size = sizeof($account_line);
        if ($size > 0) {
            $item = $account_line[$size - 1];
            $item->status_id = config('constants.kilometer_allowance_status.void');
            $item->save();
        }
        //redirect home
        return Redirect::back()->with('message', 'Kilometer Allowance Account Line have been Marked as Duplicate successfully');

    }

    public function reverse(Request $request, $id)
    {

        try {
            // get the form using its id
            $eform_kilometer_allowance = DB::select("SELECT * FROM eform_kilometer_allowance where id =  {$id} ");
            $eform_kilometer_allowance = KilometerAllowanceAccountModel::hydrate($eform_kilometer_allowance);

            //get current status id
            $status_model = StatusModel::where('id', $eform_kilometer_allowance[0]->config_status_id)
                ->where('eform_id', config('constants.eforms_id.kilometer_allowance'))->first();
            $current_status = $status_model->id;

            //new status
            $new_status_id = $current_status - 1;


            $status_model = StatusModel::where('id', $new_status_id)
                ->where('eform_id', config('constants.eforms_id.kilometer_allowance'))->first();
            $previous_status = $status_model->id;

            //  $eform_kilometer_allowance = DB::select("UPDATE eform_kilometer_allowance SET config_status_id = {$previous_status} where id =  {$id} ");
            $eform_kilometer_allowance = DB::table('eform_kilometer_allowance')
                ->where('id', $id)
                ->update(['config_status_id' => $previous_status]);

            $user = Auth::user();
            //save reason
//            $reason = EformApprovalsModel::updateOrCreate(
//                [
//                    'profile' => $user->profile_id,
//                    'title' => $user->profile_id,
//                    'name' => $user->name,
//                    'staff_no' => $user->staff_no,
//                    'reason' => $request->reason,
//                    'action' => $request->approval,
//                    'current_status_id' => $current_status,
//                    'action_status_id' => $previous_status,
//                    'config_eform_id' => config('constants.eforms_id.kilometer_allowance'),
//                    'eform_id' => $eform_kilometer_allowance[0]->id,
//                    'created_by' => $user->id,
//                ]);
            return Redirect::back()->with('message', 'Kilometer Allowance Account Line have been dropped to the previous stage successfully');
        } catch (Exception $exception) {
            return Redirect::back()->with('error', 'Sorry an error happened');
        }
    }

    public function reportsSync()
    {
        try {

//            /*
//             * NEEDED AS A FUNCTION SOMEWHERE IN PETTY CASH CONTROLLER

//            $tasks = DB::select("SELECT * FROM eform_kilometer_allowance_account where business_unit_code LIKE '%13231%'

            $form = DB::select("SELECT * FROM eform_kilometer_allowance
                            WHERE config_status_id = 28 ");
            $form = KilometerAllowanceModel::hydrate($form)->all();

            foreach ($form as $form_item) {
                $form_id = $form_item->id ;
                $tasks = DB::select("SELECT * FROM eform_kilometer_allowance_account
                            where status_id != '41'  and status_id != '41' and eform_kilometer_allowance_id  = '{$form_id}'
                             ");
                $tasks = KilometerAllowanceAccountModel::hydrate($tasks);

                if(sizeof($tasks) > 0){
                    dd($tasks);
                }


            }



            dd(122112212 );

            $tasks = DB::select("SELECT * FROM eform_kilometer_allowance_account
                            where status_id = '41'
                            ORDER BY eform_kilometer_allowance_id ASC ");
            $tasks = KilometerAllowanceAccountModel::hydrate($tasks);

            //  dd($tasks);
            foreach ($tasks as $account) {
                //get associated kilometer allowance
                $kilometer_allowance_id = $account->eform_kilometer_allowance_id;
                $tasks_pt = DB::select("SELECT * FROM eform_kilometer_allowance
                            WHERE id = {$kilometer_allowance_id}  ");
                $tasks_pt = KilometerAllowanceModel::hydrate($tasks_pt)->first();

                //update account with the kilometer allowance details
                $eform_kilometer_allowance_account = DB::table('eform_kilometer_allowance_account')
                    ->where('id', $account->id)
                    ->update([
                        'status_id' => '41',
                    ]);

            }


            //UPDATE ONE  - Update all kilometer allowance accounts with the user unit and work-flow details
            //get a list of all the kilometer allowance account models
            $tasks = DB::select("SELECT * FROM eform_kilometer_allowance_account
                            ORDER BY eform_kilometer_allowance_id ASC ");
            $tasks = KilometerAllowanceAccountModel::hydrate($tasks);

            foreach ($tasks as $account) {
                //get associated kilometer allowance
                $kilometer_allowance_id = $account->eform_kilometer_allowance_id;
                $tasks_pt = DB::select("SELECT * FROM eform_kilometer_allowance
                            WHERE id = {$kilometer_allowance_id}  ");
                $tasks_pt = KilometerAllowanceModel::hydrate($tasks_pt)->first();

                //update account with the kilometer allowance details
                $eform_kilometer_allowance_account = DB::table('eform_kilometer_allowance_account')
                    ->where('id', $account->id)
                    ->update([
                        'cost_center' => $tasks_pt->cost_center,
                        'business_unit_code' => $tasks_pt->business_unit_code,
                        'user_unit_code' => $tasks_pt->user_unit_code,

                        'staff_name' => $tasks_pt->staff_name,
                        'staff_no' => $tasks_pt->staff_no,
                        'claim_date' => $tasks_pt->claim_date,
                        'kilometer_allowance_code' => $tasks_pt->code,

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
//           */


            return Redirect::back()->with('message', 'Kilometer Allowance Account Line have been dropped to the previous stage successfully');
        } catch (Exception $exception) {
            return Redirect::back()->with('error', 'Sorry an error happened');
        }
    }

    public function search(Request $request)
    {
        $search = strtoupper($request->search);
        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $list = DB::select("SELECT * FROM eform_kilometer_allowance
              where code LIKE '%{$search}%'
              or staff_name LIKE '%{$search}%'
              or staff_no LIKE '%{$search}%'
              or config_status_id LIKE '%{$search}%'
              or user_unit_code LIKE '%{$search}%'
            ");
            $list = KilometerAllowanceModel::hydrate($list);
        } else {
            //find the kilometer allowance with that id
            $list = KilometerAllowanceModel::
            where('code', 'LIKE', "%{$search}%")
                ->orWhere('staff_name', 'LIKE', "%{$search}%")
                ->orWhere('staff_no', 'LIKE', "%{$search}%")
                ->orWhere('config_status_id', 'LIKE', "%{$search}%")
                ->orWhere('user_unit_code', 'LIKE', "%{$search}%")
                ->paginate(50);
        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))->get();
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
        return view('eforms.kilometer-allowance.list')->with($params);
    }



}
