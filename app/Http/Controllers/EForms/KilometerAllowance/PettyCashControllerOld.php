<?php

namespace App\Http\Controllers\EForms\PettyCash;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Mail\SendMail;
use App\Models\EForms\PettyCash\PettyCashAccountModel;
use App\Models\EForms\PettyCash\PettyCashItemModel;
use App\Models\EForms\PettyCash\PettyCashModel;
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
use Mockery\CountValidator\Exception;


class PettyCashControllerOld extends Controller
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
        session(['eform_id' => config('constants.eforms_id.petty_cash')]);
        session(['eform_code' => config('constants.eforms_name.petty_cash')]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $value)
    {

        //get list of all petty cash forms for today
        if ($value == "all") {
            $logged_in_user = Auth::user();

            //expenditure
            if ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_014')) {
                $pay_point_id = Auth::user()->pay_point_id;
                $list = DB::select("SELECT * FROM eform_petty_cash
                        WHERE pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
                $list = PettyCashModel::hydrate($list);
            } //security
            elseif ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_013')) {
                $pay_point_id = Auth::user()->pay_point_id;
                $list = DB::select("SELECT * FROM eform_petty_cash
                        WHERE pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
                $list = PettyCashModel::hydrate($list);
            } //hr
            elseif ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_009')) {
                $pay_point_id = Auth::user()->pay_point_id;
                $list = DB::select("SELECT * FROM eform_petty_cash
                        WHERE pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
                $list = PettyCashModel::hydrate($list);
            } //HOD
            elseif ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_004')) {
                $user_unit_code = Auth::user()->user_unit->code;
                $code_position = Auth::user()->position->code;
                //  WHERE user_unit_code = '{$user_unit_code}'
//                $list = DB::select("SELECT * FROM eform_petty_cash
//                        WHERE user_unit_code = '{$user_unit_code}'
//                        OR code_superior = '{$code_position}'
//                        ORDER BY config_status_id, updated_at ASC ");
//                $list = PettyCashModel::hydrate($list);
                $list = PettyCashModel::all();
            } //chief accountant
            elseif ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_007')) {
                $pay_point_id = Auth::user()->pay_point_id;
                $list = DB::select("SELECT * FROM eform_petty_cash
                        WHERE pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
                $list = PettyCashModel::hydrate($list);
            } //others
            else {
                $list = PettyCashModel::all();
            }


            $category = "All";
        }
        else if ($value == "pending") {
            // $list = PettyCashModel::where('config_status_id', '>', config('constants.petty_cash_status.new_application'))
//                ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
//                ->get();

            $logged_in_user = Auth::user();
            $new = config('constants.petty_cash_status.new_application');
            $closed = config('constants.petty_cash_status.closed');

            //expenditure
            if ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_014')) {
                $pay_point_id = Auth::user()->pay_point_id;
                $list = DB::select("SELECT * FROM eform_petty_cash
                        WHERE config_status_id > {$new}  and config_status_id < {$closed}
                        AND pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
                $list = PettyCashModel::hydrate($list);
            } //security
            elseif ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_013')) {
                $funds = config('constants.petty_cash_status.funds_acknowledgement');
                $pay_point_id = Auth::user()->pay_point_id;

                $list = DB::select("SELECT * FROM eform_petty_cash
                       WHERE config_status_id > {$new}  AND config_status_id < {$closed}
                        AND pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
                $list = PettyCashModel::hydrate($list);
            } //hr
            elseif ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_009')) {
                $pay_point_id = Auth::user()->pay_point_id;
                $list = DB::select("SELECT * FROM eform_petty_cash
                        WHERE config_status_id > {$new}  AND config_status_id < {$closed}
                        AND pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
                $list = PettyCashModel::hydrate($list);
            } //chief accountant
            elseif ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_007')) {
                $pay_point_id = Auth::user()->pay_point_id;
                $list = DB::select("SELECT * FROM eform_petty_cash
                        WHERE config_status_id > {$new}  AND config_status_id < {$closed}
                        AND pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
                $list = PettyCashModel::hydrate($list);
            } //HOD
            elseif ($logged_in_user->profile_id == config('constants.user_profiles.EZESCO_004')) {
//                $user_unit_code = Auth::user()->user_unit->code;
//                $code_position = Auth::user()->position->code;
//                $pay_point_id = Auth::user()->pay_point_id;
//                //  WHERE user_unit_code = '{$user_unit_code}'
//                $list = DB::select("SELECT * FROM eform_petty_cash
//                        WHERE code_superior = '{$code_position}'
//                        AND config_status_id > {$new} AND config_status_id < {$closed}
//                        AND pay_point_id =  {$pay_point_id}
//                        OR user_unit_code = '{$user_unit_code}'
//                        AND config_status_id > {$new} AND config_status_id < {$closed}
//                        AND pay_point_id =  {$pay_point_id}
//                        ORDER BY config_status_id, updated_at ASC ");
//                $list = PettyCashModel::hydrate($list);

                $list = PettyCashModel::where('config_status_id', '>', config('constants.petty_cash_status.new_application'))
                    ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
                    ->get();
            } //others
            else {
                $list = PettyCashModel::where('config_status_id', '>', config('constants.petty_cash_status.new_application'))
                    ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
                    ->get();
            }

            $category = "Opened";
        } else if ($value == config('constants.petty_cash_status.new_application')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.new_application'))
                ->get();
            $category = "New Application";
        } else if ($value == config('constants.petty_cash_status.closed')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.closed'))
                ->get();
            $category = "Closed";
          //  dd(11);
        } else if ($value == config('constants.petty_cash_status.rejected')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.rejected'))
                ->get();
            $category = "Rejected";
        } else if ($value == "needs_me") {
            $list = $totals_needs_me = HomeController::needsMeList();
            $category = "Needs My Attention";
        } else if ($value == "admin") {
            $list = PettyCashModel::where('config_status_id', 0)
                ->get();
        }

        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();

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
        return view('eforms.petty-cash.list')->with($params);

    }


    /**
     * Display a listing of the resource for the admin.
     *
     * @return \Illuminate\HtFtp\Response
     */
    public function records(Request $request, $value)
    {

        //get list of all petty cash forms for today
        if ($value == "all") {

            $list = DB::select("SELECT * FROM eform_petty_cash ORDER BY updated_at ASC ");
            $list = PettyCashModel::hydrate($list);

            $category = "All Records";
        } else if ($value == "pending") {
            $list = PettyCashModel::where('config_status_id', '>', config('constants.petty_cash_status.new_application'))
                ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
                ->get();
            $category = "Opened";
        } else if ($value == config('constants.petty_cash_status.new_application')) {

            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.new_application'))
                ->get();
            $category = "New Application";

        } else if ($value == config('constants.petty_cash_status.closed')) {

            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.closed'))
                ->get();
            $category = "Closed";

        } else if ($value == config('constants.petty_cash_status.rejected')) {

            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.rejected'))
                ->get();

            $category = "Rejected";

        } else if ($value == "needs_me") {

            $list = $totals_needs_me = HomeController::needsMeList();

            $category = "Needs My Attention";

        } else if ($value == "admin") {

        }


        //count all
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();

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
        return view('eforms.petty-cash.records')->with($params);

    }

    /**
     * Mark the form as void.
     *
     * @return \Illuminate\Http\Response
     */
    public function void(Request $request, $id)
    {
        //GET THE PETTY CASH MODEL
        $list = DB::select("SELECT * FROM eform_petty_cash where id = {$id} ");
        $form = PettyCashModel::hydrate($list)->first();
        //get the status
        $current_status = $form->status->id;
        $new_status = 0;
        $user = Auth::user();
        //get the form type
        $eform_pettycash = EFormModel::find(config('constants.eforms_id.petty_cash'));

        //HANDLE VOID REQUEST
        $new_status = config('constants.petty_cash_status.void');

        //update the totals rejected
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('id', config('constants.totals.petty_cash_reject'))
            ->first();
        $totals->value = $totals->value + 1;
        $totals->save();
        $eform_pettycash->total_rejected = $totals->value;
        $eform_pettycash->save();

        //update the totals open
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('id', config('constants.totals.petty_cash_open'))
            ->first();
        $totals->value = $totals->value - 1;
        $totals->save();
        $eform_pettycash->total_pending = $totals->value;
        $eform_pettycash->save();

        //get status id
        $status_model = StatusModel::where('id', $new_status)
            ->where('eform_id', config('constants.eforms_id.petty_cash'))->first();
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
                'config_eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_id' => $form->id,
                'created_by' => $user->id,
            ]);

        //redirect home
        return Redirect::back()->with('message', 'Petty Cash ' . $form->code . ' for has been marked as Void successfully');

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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
        return view('eforms.petty-cash.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //[1]get the logged in user
        $user = Auth::user();   //superior_code

        //[2A] find my code superior
        $my_hods = self::myHODs($user);

        if (empty($my_hods)) {
            //prepare details
            $details = [
                'name' => "Team",
                'url' => 'petty-cash-home',
                'subject' => "Petty-Cash-Voucher Configuration Needs Your Attention",
                'title' => "Code Superior Not Defined for {$user->name}",
                'body' => "Please note that {$user->name} with Staff Number {$user->staff_no} and Phone/Extension {$user->phone}, failed to submit or raise new petty-cash voucher.
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
            return Redirect::route('petty-cash-home')->with('error', 'Sorry!, The superior who is supposed to approve your petty cash,
                       <br> has not registered or not fully configured yet, Please, <b>try first contacting your superior</b> so as to make sure he/she has registered in the system,
                       then you can contact eZESCO Admins to configure your superior. Your petty-cash voucher has not been saved.');
        }

        //generate the petty cash unique code
        $code = self::randGenerator("PT", 1);

        $formModel = PettyCashModel::firstOrCreate(
            [
                'total_payment' => $request->total_payment,
                'claim_date' => $request->date,
                'claimant_name' => $request->claimant_name,
                'claimant_staff_no' => $request->sig_of_claimant,
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
                'projects_id' => $request->projects_id,

                'total_payment' => $request->total_payment,
                'code' => $code,
                'ref_no' => $request->ref_no,
                'config_status_id' => config('constants.petty_cash_status.new_application'),

                'claimant_name' => $request->claimant_name,
                'claimant_staff_no' => $request->sig_of_claimant,
                'claim_date' => $request->date,

                'created_by' => $user->id,
                'profile' => Auth::user()->profile_id,
                'code_superior' => Auth::user()->position->superior_code,
            ]);

        // now we need to insert the petty-cash items
        for ($i = 0; $i < sizeof($request->amount); $i++) {
            $formItemModel = PettyCashItemModel::updateOrCreate(
                [
                    'name' => $request->name[$i],
                    'amount' => $request->amount[$i],
                    'eform_petty_cash_id' => $formModel->id,
                    'created_by' => $user->id,
                ],
                [
                    'name' => $request->name[$i],
                    'amount' => $request->amount[$i],
                    'eform_petty_cash_id' => $formModel->id,
                    'created_by' => $user->id,
                ]
            );
        }

        /** upload quotation files */
        // upload the receipt files
        $files = $request->file('quotation');
        if ($request->hasFile('quotation')) {
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
                $path = $file->storeAs('public/petty_cash_quotation', $fileNameToStore);

                //upload the receipt
                $file = AttachedFileModel::updateOrCreate(
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $formModel->code,
                        'form_type' => config('constants.eforms_id.petty_cash'),
                        'file_type' => config('constants.file_type.quotation')
                    ],
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $formModel->code,
                        'form_type' => config('constants.eforms_id.petty_cash'),
                        'file_type' => config('constants.file_type.quotation')
                    ]
                );
            }
        }

        /** update the totals */
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('id', config('constants.totals.petty_cash_new'))
            ->first();
        $totals->value = $totals->value + 1;
        $totals->save();
        $eform_pettycash = EFormModel::find(config('constants.eforms_id.petty_cash'));
        $eform_pettycash->total_new = $totals->value;
        $eform_pettycash->save();

        /** send email to supervisor */
        //get team email addresses
//        $to = config('constants.team_email_list');
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
            'url' => 'petty-cash-home',
            'subject' => "New Petty-Cash Voucher Needs Your Attention",
            'title' => "New Petty-Cash Voucher Needs Your Attention {$user->name}",
            'body' => "Please note that {$user->name} with Staff Number {$user->staff_no} has successfully raised a petty-cash voucher with
                   <br> serial: {$formModel->code}  <br> reference: {$formModel->ref_no} <br> Status: {$formModel->status->name}  and <br> <b>Amount: ZMW {$request->total_payment}</b></br>. <br>
            This voucher now needs your approval, kindly click on the button below to login to E-ZESCO and take action on the voucher.<br> regards. "
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));

        //log the activity
        ActivityLogsController::store($request, "Creating of Petty Cash", "update", " pay point created", $formModel->id);
        //return the view
        return Redirect::route('petty-cash-home')->with('message', 'Petty Cash Details for ' . $formModel->code . ' have been Created successfully');

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
            $hods_assigned = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                ->where('profile', $hod_profile->code)
                ->where('user_id', $item->id);
            if ($hods_assigned->exists()) {
                $hods_array[] = $item;
            } else {
                //[B]check if the users in my user unit have this delegated profile
                $hods_assigned = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.petty_cash'))
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
        $count = DB::select("SELECT count(id) as total FROM eform_petty_cash ");

        // use of oracle sequence
//          $count = DB::select("SELECT id as total  FROM eform_petty_cash  ");
//          $size = sizeof($count);
//          $size = $count[$size - 1 ];

        //random number
        // $random = rand(1, 9999999);
        $random = $count[0]->total;  // count total and begin again
        // $random = $size->total ;  // oracle sequence
        $random = sprintf("%07d", ($random + $value));
        $random = $head . $random;

        $count_existing_forms = DB::select("SELECT count(id) as total FROM eform_petty_cash WHERE code = '{$random}'");
        try {
            $total = $count_existing_forms[0]->total;
        } catch (\Exception $exception) {
            $total = 0;
        }

        if ($total < 1) {
            return $random;
        } else {
            self::randGenerator("PT", $value );
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
            $list = DB::select("SELECT * FROM eform_petty_cash where id = {$id} ");
            $form = PettyCashModel::hydrate($list)->first();


        } else {
            //find the petty cash with that id
            //  $form = PettyCashModel::find($id);
            $list = DB::select("SELECT * FROM eform_petty_cash where id = {$id} ");
            $form = PettyCashModel::hydrate($list)->first();
        }

        $receipts = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.petty_cash'))
            ->where('file_type', config('constants.file_type.receipt'))
            ->get();
        $quotations = AttachedFileModel::where('form_id', $form->code)
            ->where('form_type', config('constants.eforms_id.petty_cash'))
            ->where('file_type', config('constants.file_type.quotation'))
            ->get();
        $form_accounts = PettyCashAccountModel::where('eform_petty_cash_id', $id)->get();
        $projects = ProjectsModel::all();
        $accounts = AccountsChartModel::all();
        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.petty_cash'))
            ->orderBy('created_at', 'asc')->get();

        // dd($approvals);
        //get the list of users who are supposed to work on the form
        $user_array = self::nextUsers($form->profile, $form);

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
        return view('eforms.petty-cash.show')->with($params);

    }

    /**
     * List the users who are supposed to work on the form next
     * @param $last_profile
     * @param $current_status
     * @param $claimant_man_no
     * @return array
     */
    public function nextUsers($last_profile, $form)
    {
        //[0]get claimant details
        $user_array = [];
        $user_claimant = User::where('staff_no', $form->claimant_staff_no)->get()->first();

        //[1] THE LAST PROFILE
        $last_profile_who_worked = ProfileModel::find($last_profile);
        //get the next profiles to work from the last profile  PROFILE Permissions
        $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('profile', $last_profile_who_worked->code)
            ->first();

        //[2] THE NEXT PROFILE
        $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next;
        //get the profile permissions associated with this next_profile_to_work
        $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('profile', $next_profile_to_work->code)
            ->get();


        //[3] THE USERS
        //check if this next profile is for a claimant and if the Petty-Cash needs Acknowledgement
        if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $form->config_status_id == config('constants.petty_cash_status.security_approved')) {
            $user = User::where('staff_no', $form->claimant_staff_no)
                ->first();
            $user_array[] = $user;
        } //check if this next profile is for a claimant and if the Petty-Cash is closed
        else if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $form->config_status_id == config('constants.petty_cash_status.closed')) {
            //get user
            $user = User::where('staff_no', $form->claimant_staff_no)->first();
            $user_array[] = $user;
        } //check if the Petty-Cash is closed
        else if ($form->config_status_id == config('constants.petty_cash_status.closed')) {
            //get no user
            $user_array = [];
        } //check if the Petty-Cash is closed
        else if ($form->config_status_id == config('constants.petty_cash_status.rejected')) {
            //get no user
            $user_array = [];
        } //check if the Petty-Cash is cancelled
        else if ($form->config_status_id == config('constants.petty_cash_status.cancelled')) {
            //get no user
            $user_array = [];
        } // other wise get the users
        else {
            foreach ($profileAssignement as $item) {
                //get user who is the next person
                $user = User::find($item->user_id);

                // FILTER: based on user unit  if the form is a New Application
                if ($form->config_status_id == config('constants.petty_cash_status.new_application')) {
                    $user_array = self::myHODs($user_claimant);
                } else {
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


//    public function nextUsers1($last_profile, $current_status, $claimant_man_no)
//    {
//
//        $user_array = [];
//        $user = Auth::user();
//
//        //[1]
//        //THE LAST PROFILE
//        $last_profile_who_worked = ProfileModel::find($last_profile);
//        //get the next profiles to work from the last profile  PROFILE Permissions
//        $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
//            ->where('profile', $last_profile_who_worked->code)
//            ->first();
//
//        //[2]
//        //THE NEXT PROFILE
//        $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next;
//        //get the profile permissions associated with this next_profile_to_work
//        $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.petty_cash'))
//            ->where('profile', $next_profile_to_work->code)
//            ->get();
//
//        //If it is you who is actually supposed to work on the form, then find the next users who are supposed to work after me
//        if ($user->profile_id == $next_profile_to_work->id) {
//            //[1A]
//            //THE LAST PROFILE
//            $last_profile_who_worked = ProfileModel::find($user->profile_id);
//            //get the next profiles to work from the last profile  PROFILE Permissions
//            $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
//                ->where('profile', $last_profile_who_worked->code)
//                ->first();
//            //[2B]
//            //THE NEXT PROFILE
//            $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next;
//            //get the profile permissions associated with this next_profile_to_work
//            $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.petty_cash'))
//                ->where('profile', $next_profile_to_work->code)
//                ->get();
//        }
//
//        //[3]
//        //THE USERS
//        //check if this next profile is for a claimant and if the Petty-Cash needs Acknowledgement
//        if ($current_status == config('constants.petty_cash_status.security_approved')) {
//            $user = User::where('staff_no', $claimant_man_no)->first();
//            // $user_array[] = $user;
//            $user_array = [];
//        } //check if this next profile is for a claimant and if the Petty-Cash is needs closed
//        else if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
//            $current_status == config('constants.petty_cash_status.closed')) {
//            //get user
//            $user = User::where('staff_no', $claimant_man_no)->first();
//            $user_array[] = $user;
//        } //check if this next profile is for a claimant and if the Petty-Cash is needs closed
//        else if ($current_status == config('constants.petty_cash_status.chief_accountant')) {
//            //get user
//            $user = User::where('staff_no', $claimant_man_no)->first();
//            $user_array[] = $user;
//
//        } //check if the Petty-Cash is closed
//        else if ($current_status == config('constants.petty_cash_status.closed')) {
//            //get no user
//            $user_array = [];
//        }
//        //check if status is cheif approved
//        // other wise get the users
//        else {
//            foreach ($profileAssignement as $item) {
//                //get user
//                $user = User::find($item->user_id);
//                $user_array[] = $user;
//            }
//        }
//
//        //[4]
//        //return the list of users
//        return $user_array;
//
//    }

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
        //GET THE PETTY CASH MODEL
        $form = PettyCashModel::find($request->id);
        $current_status = $form->status->id;
        $user = Auth::user();
        $eform_pettycash = EFormModel::find(config('constants.eforms_id.petty_cash'));

        //HANDLE CANCELLATION
        if ($request->approval == config('constants.approval.cancelled')) {

            if ($current_status = config('constants.petty_cash_status.new_application')) {
                $total_to_subtract_from = config('constants.totals.petty_cash_new');
            } else {
                $total_to_subtract_from = config('constants.totals.petty_cash_open');
            }

            //update the totals rejected
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                ->where('id', config('constants.totals.petty_cash_reject'))
                ->first();
            $totals->value = $totals->value + 1;
            $totals->save();
            $eform_pettycash->total_rejected = $totals->value;
            $eform_pettycash->save();

            //update the totals open
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                ->where('id', $total_to_subtract_from)
                ->first();
            $totals->value = $totals->value - 1;
            $totals->save();
            $eform_pettycash->total_pending = $totals->value;
            $eform_pettycash->save();

        }

        //HANDLE REJECTION
        if ($request->approval == config('constants.approval.reject')) {

            //update the totals rejected
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                ->where('id', config('constants.totals.petty_cash_reject'))
                ->first();
            $totals->value = $totals->value + 1;
            $totals->save();
            $eform_pettycash->total_rejected = $totals->value;
            $eform_pettycash->save();

            //update the totals open
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                ->where('id', config('constants.totals.petty_cash_open'))
                ->first();
            $totals->value = $totals->value - 1;
            $totals->save();
            $eform_pettycash->total_pending = $totals->value;
            $eform_pettycash->save();

        }

        //HANDLE APPROVAL
        if ($request->approval == config('constants.approval.approve')) {

            if ($form->status->id == config('constants.petty_cash_status.security_approved')) {

                //update the totals closed
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                    ->where('id', config('constants.totals.petty_cash_closed'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_pettycash->total_closed = $totals->value;
                $eform_pettycash->save();

                //update the totals open
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                    ->where('id', config('constants.totals.petty_cash_open'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_pettycash->total_pending = $totals->value;
                $eform_pettycash->save();

            } else if ($form->status->id == config('constants.petty_cash_status.new_application')) {
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                    ->where('id', config('constants.totals.petty_cash_open'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_pettycash->total_pending = $totals->value;
                $eform_pettycash->save();

                //update the totals new
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.petty_cash'))
                    ->where('id', config('constants.totals.petty_cash_new'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_pettycash->total_new = $totals->value;
                $eform_pettycash->save();
            }
        }


        //FOR FOR CLAIMANT CANCELLATION
        if (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_002')
            && $current_status == config('constants.petty_cash_status.new_application')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            } else {
                $new_status = config('constants.petty_cash_status.new_application');
                $insert_reasons = false;
            }
            $form->config_status_id = $new_status;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }
        //FOR HOD
        elseif (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_004')
            && $current_status == config('constants.petty_cash_status.new_application')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.petty_cash_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.petty_cash_status.hod_approved');
            } else {
                $new_status = config('constants.petty_cash_status.new_application');
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
        //FOR CHIEF HR
        elseif (
            Auth::user()->profile_id == config('constants.user_profiles.EZESCO_009')
            && $current_status == config('constants.petty_cash_status.hod_approved')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.petty_cash_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.petty_cash_status.hr_approved');
            } else {
//                $new_status = config('constants.petty_cash_status.void');
                $new_status = config('constants.petty_cash_status.hod_approved');
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
        //FOR FOR CHIEF ACCOUNTANT
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_007')
            && $current_status == config('constants.petty_cash_status.hr_approved')
        ) {

            $insert_reasons = true;
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.petty_cash_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.petty_cash_status.chief_accountant');
            } else {
//                $new_status = config('constants.petty_cash_status.void');
                $new_status = config('constants.petty_cash_status.hr_approved');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->accountant = $user->name;
            $form->accountant_staff_no = $user->staff_no;
            $form->accountant_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }
        //FOR FOR EXPENDITURE OFFICE FUNDS
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')
            && $current_status == config('constants.petty_cash_status.chief_accountant')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.petty_cash_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.petty_cash_status.funds_disbursement');
            } else {
                $new_status = config('constants.petty_cash_status.chief_accountant');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->expenditure_office = $user->name;
            $form->expenditure_office_staff_no = $user->staff_no;
            $form->expenditure_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();

            //create records for the accounts associated with this petty cash transaction
            for ($i = 0; $i < sizeof($request->credited_amount); $i++) {
                $des = "";
                $des = $des . " " . $request->account_items[$i] . ",";
                $des = "Petty-Cash Serial: " . $form->code . ", Claimant: " . $form->claimant_name . ', Items : ' . $des . ' Amount: ' . $request->credited_amount[$i] . '.';

                //[1] CREDITED ACCOUNT
                //[1A] - money
                $formAccountModel = PettyCashAccountModel::updateOrCreate(
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        'creditted_amount' => $request->credited_amount[$i],
                        'account' => $request->credited_account[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        //'debitted_amount' => $request->debited_amount[$i],
                        'eform_petty_cash_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'status_id' => config('constants.petty_cash_status.export_not_ready')
                    ],
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        'creditted_amount' => $request->credited_amount[$i],
                        'account' => $request->credited_account[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        //'debitted_amount' => $request->debited_amount[$i],
                        'eform_petty_cash_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                        'status_id' => config('constants.petty_cash_status.export_not_ready')
                    ]
                );

                //[2] DEBITED ACCOUNT
                //[2A] - money
                $formAccountModel = PettyCashAccountModel::updateOrCreate(
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        //'creditted_amount' => $request->credited_amount[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        'debitted_amount' => $request->debited_amount[$i],
                        'account' => $request->debited_account[$i],
                        'eform_petty_cash_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'status_id' => config('constants.petty_cash_status.export_not_ready')
                    ],
                    [
                        'creditted_account_id' => $request->credited_account[$i],
                        //'creditted_amount' => $request->credited_amount[$i],
                        'debitted_account_id' => $request->debited_account[$i],
                        'debitted_amount' => $request->debited_amount[$i],
                        'account' => $request->debited_account[$i],
                        'eform_petty_cash_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                        'status_id' => config('constants.petty_cash_status.export_not_ready')
                    ]
                );
            }

        }
        //FOR CLAIMANT - ACKNOWLEDGEMENT
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_002')
            && $current_status == config('constants.petty_cash_status.funds_disbursement')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.petty_cash_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.petty_cash_status.funds_acknowledgement');
            } else {
                $new_status = config('constants.petty_cash_status.funds_disbursement');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
//          $form->profile = Auth::user()->profile_id;
            $form->profile = config('constants.user_profiles.EZESCO_007');
            $form->save();
        }
        //FOR FOR SECURITY
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_013')
            && $current_status == config('constants.petty_cash_status.funds_acknowledgement')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.petty_cash_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.petty_cash_status.security_approved');
            } else {
                $new_status = config('constants.petty_cash_status.funds_acknowledgement');
                $insert_reasons = false;
            }
            //update
            $form->config_status_id = $new_status;
            $form->security_name = $user->name;
            $form->security_staff_no = $user->staff_no;
            $form->security_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }
        //FOR FOR EXPENDITURE OFFICE - RECEIPT
        elseif (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')
            && $current_status == config('constants.petty_cash_status.security_approved')
        ) {
            //cancel status
            $insert_reasons = true;
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.petty_cash_status.cancelled');
            }
            //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.petty_cash_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.petty_cash_status.receipt_approved');
            } else {
                $new_status = config('constants.petty_cash_status.security_approved');
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
                $des = $des . " " . $request->account_item . ",";
                $des = "Petty-Cash Serial: " . $form->code . ", Claimant: " . $form->claimant_name . ', Items : ' . $des . ' Amount: ' . $request->credited_amount . '.';


                //[1] CREDITED ACCOUNT
                //[1A] - money
                $formAccountModel = PettyCashAccountModel::updateOrCreate(
                    [
                        'creditted_account_id' => $request->credited_account,
                        'creditted_amount' => $request->credited_amount,
                        'account' => $request->credited_account,
                        'debitted_account_id' => $request->debited_account,
                        //'debitted_amount' => $request->debited_amount,
                        'eform_petty_cash_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'status_id' => config('constants.petty_cash_status.export_not_ready')
                    ],
                    [
                        'creditted_account_id' => $request->credited_account,
                        'creditted_amount' => $request->credited_amount,
                        'account' => $request->credited_account,
                        'debitted_account_id' => $request->debited_account,
                        //'debitted_amount' => $request->debited_amount,
                        'eform_petty_cash_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                        'status_id' => config('constants.petty_cash_status.export_not_ready')
                    ]
                );

                //[2] DEBITED ACCOUNT
                //[2A] - money
                $formAccountModel = PettyCashAccountModel::updateOrCreate(
                    [
                        'creditted_account_id' => $request->credited_account,
                        //'creditted_amount' => $request->credited_amount,
                        'debitted_account_id' => $request->debited_account,
                        'debitted_amount' => $request->debited_amount,
                        'account' => $request->debited_account,
                        'eform_petty_cash_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'status_id' => config('constants.petty_cash_status.export_not_ready')
                    ],
                    [
                        'creditted_account_id' => $request->credited_account,
                        //'creditted_amount' => $request->credited_amount,
                        'debitted_account_id' => $request->debited_account,
                        'debitted_amount' => $request->debited_amount,
                        'account' => $request->debited_account,
                        'eform_petty_cash_id' => $form->id,
                        'created_by' => $user->id,
                        'company' => '01',
                        'intra_company' => '01',
                        'project' => $form->project->code ?? "",
                        'pems_project' => 'N',
                        'spare' => '0000',
                        'description' => $des,
                        'status_id' => config('constants.petty_cash_status.export_not_ready')
                    ]
                );
            }

//            //update all accounts associated to this pettycash
//            $formAccountModelList = PettyCashAccountModel::where('eform_petty_cash_id', $form->id)
//                ->where('status_id', config('constants.petty_cash_status.export_not_ready'))
//                ->get();
//            foreach ($formAccountModelList as $item) {
//                $item->status_id = config('constants.petty_cash_status.not_exported');
//                $item->save();
//            }

            //Make the update on the petty cash account
            $export_not_ready = config('constants.petty_cash_status.export_not_ready');
            $not_exported = config('constants.petty_cash_status.not_exported');
            $id = $form->id;
            $formAccountModelList = DB::table('eform_petty_cash_account')
                ->where('eform_petty_cash_id', $id)
                ->where('status_id', $export_not_ready)
                ->update(
                    ['status_id' => $not_exported]
                );

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
                    $path = $file->storeAs('public/petty_cash_receipt', $fileNameToStore);

                    //upload the receipt
                    $file = AttachedFileModel::updateOrCreate(
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.petty_cash'),
                            'file_type' => config('constants.file_type.receipt')
                        ],
                        [
                            'name' => $fileNameToStore,
                            'location' => $path,
                            'extension' => $extension,
                            'file_size' => $size,
                            'form_id' => $form->code,
                            'form_type' => config('constants.eforms_id.petty_cash'),
                            'file_type' => config('constants.file_type.receipt')
                        ]
                    );
                }
            }

        }
        //FOR NO-ONE
        else {
            //return with an error
            return Redirect::route('petty-cash-home')->with('message', 'Petty Cash ' . $form->code . ' for has been ' . $request->approval . ' successfully');
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
                    'config_eform_id' => config('constants.eforms_id.petty_cash'),
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
                    'config_eform_id' => config('constants.eforms_id.petty_cash'),
                    'eform_id' => $form->id,
                    'created_by' => $user->id,
                ]

            );
            //send the email
            self::nextUserSendMail($user->profile_id, $new_status, $form);

        }

        //redirect home
        return Redirect::route('petty-cash-home')->with('message', 'Petty Cash ' . $form->code . ' for has been ' . $request->approval . ' successfully');

    }

    /**
     * Send Email to the Next Person/s who are supposed to work on the form next
     * @param $profile
     * @param $stage
     * @param $claim_staff
     */

    public function nextUserSendMail($last_profile, $new_status, $form)
    {
        //get the users
        $user_array = self::nextUsers($last_profile, $form);

        $claimant_details = User::find($form->created_by);

        //check if this next profile is for a claimant and if the Petty-Cash needs Acknowledgement
        if ($new_status == config('constants.petty_cash_status.security_approved')) {

            //message details
            $subject = 'Petty-Cash Voucher Needs Your Attention';
            $title = 'Petty-Cash Voucher Needs Your Attention';
            $message = 'This is to notify you that there is a petty-cash voucher (' . $form->code . ') that needs your attention.
            Please login to e-ZESCO by clicking on the button below to take action on the voucher.The form is currently at ' . $form->status->name . ' stage';
        } //check if this next profile is for a claimant and if the Petty-Cash is closed
        else if ($new_status == config('constants.petty_cash_status.closed')) {

            //message details
            $subject = 'Petty-Cash Voucher Closed Successfully';
            $title = 'Petty-Cash Voucher Closed Successfully';
            $message = ' Congratulation! This is to notify you that petty-cash voucher ' . $form->code . ' has been closed successfully .
            Please login to e-ZESCO by clicking on the button below to view the voucher. The petty cash voucher has now been closed.';
        } // other wise get the users
        else {
            //message details
            $subject = 'Petty-Cash Voucher Needs Your Attention';
            $title = 'Petty-Cash Voucher Needs Your Attention';
            $message = 'This is to notify you that there is a petty-cash voucher ('. $form->code . ') that needs your attention.
            Please login to e-ZESCO by clicking on the button below to take action on the voucher. The form is currently at ' . $form->status->name . ' stage.';
        }

        /** send email to supervisor */
        $names = "";
        $to = [];
        //add hods email addresses
        foreach ($user_array as $item) {
            //use the pay point
            if ($item->pay_point_id == $claimant_details->pay_point_id) {
                $to[] = ['email' => $item->email, 'name' => $item->name];
                $to[] = ['email' => $claimant_details->email, 'name' => $claimant_details->name];
                $names = $names . '<br>' . $item->name;
            }
        }

      //  dd($user_array);
        $to[] = ['email' => 'nshubart@zesco.co.zm', 'name' => 'Shubart Nyimbili'];
        $to[] = ['email' => 'csikazwe@zesco.co.zm', 'name' => 'Chapuka Sikazwe'];
        $to[] = ['email' => 'bchisulo@zesco.co.zm', 'name' => 'Bwalya Chisulo'];
        //prepare details
        $details = [
            'name' => $names,
            'url' => 'petty-cash-home',
            'subject' => $subject,
            'title' => $title,
            'body' => $message
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));

    }

    public function reports(Request $request, $value)
    {
        //get the accounts
        $title = "";

        if ($value == config('constants.all')) {
            $list = DB::select("SELECT * FROM eform_petty_cash_account  ");
            $list = PettyCashAccountModel::hydrate($list);

            //dd($list);
            // $list = PettyCashAccountModel::all();
            $title = "ALl";
        } elseif ($value == config('constants.petty_cash_status.not_exported')) {
            // $list = PettyCashAccountModel::where('status_id', config('constants.petty_cash_status.not_exported'))->get();
            $status = config('constants.petty_cash_status.not_exported');
            $list = DB::select("SELECT * FROM eform_petty_cash_account where status_id = {$status} ");
            $list = PettyCashAccountModel::hydrate($list);
            $title = "Not Exported";
        } elseif ($value == config('constants.petty_cash_status.exported')) {
            // $list = PettyCashAccountModel::where('status_id', config('constants.petty_cash_status.exported'))->get();
            $status = config('constants.petty_cash_status.exported');
            $list = DB::select("SELECT * FROM eform_petty_cash_account where status_id = {$status} ");
            $list = PettyCashAccountModel::hydrate($list);
            $title = " Exported";
        } elseif ($value == config('constants.petty_cash_status.export_failed')) {
            // $list = PettyCashAccountModel::where('status_id', config('constants.petty_cash_status.export_failed'))->get();
            $status = config('constants.petty_cash_status.export_failed');
            $list = DB::select("SELECT * FROM eform_petty_cash_account where status_id = {$status} ");
            $list = PettyCashAccountModel::hydrate($list);

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
        return view('eforms.petty-cash.report')->with($params);
    }

    public function reportsExport(Request $request)
    {

        $fileName = 'PettyCash_Accounts.csv';
        //  $tasks = PettyCashAccountModel::where('status_id', config('constants.petty_cash_status.not_exported'))->get();
        $not_exported = config('constants.petty_cash_status.not_exported');
        $tasks = DB::select("SELECT * FROM eform_petty_cash_account
                        WHERE status_id = {$not_exported}
                        ORDER BY eform_petty_cash_id ASC ");
        $tasks = PettyCashAccountModel::hydrate($tasks);

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
//                $item->status_id = config('constants.petty_cash_status.exported');
//                $item->save();

                //Make the update on the petty cash account
                $previous_status = config('constants.petty_cash_status.exported');
                $id = $item->id;
                $eform_petty_cash_item = DB::table('eform_petty_cash_account')
                    ->where('id', $id)
                    ->update(['status_id' => $previous_status]);

                $row['Code'] = $item->petty_cash->code;
                $row['Claimant'] = $item->petty_cash->claimant_name;
                $row['Claim Date'] = $item->petty_cash->claim_date;
                $row['Company'] = $item->company;
                $row['Business Unit'] = $item->petty_cash->business_unit_code;
                $row['Cost Center'] = $item->petty_cash->cost_center;
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
        $list = PettyCashModel:: select(DB::raw('cost_centre, name_of_claimant, count(id) as total_forms , sum(total_payment) as forms_sum '))
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
        return view('eforms.petty-cash.chart')->with($params);
        //  dd($request);
    }

    public function sync($id)
    {
        $eform_petty_cash = DB::select("SELECT * FROM eform_petty_cash where id =  {$id} ");
        $eform_petty_cash = PettyCashAccountModel::hydrate($eform_petty_cash);

        $claimant = User::find($eform_petty_cash[0]->created_by);
        $user_unit_code = $claimant->user_unit->code ;
        $superior_code = $claimant->position->superior_code ;
        $eform_petty_cash = DB::table('eform_petty_cash')
            ->where('id', $id)
            ->update(['code_superior' => $superior_code,
                'user_unit_code' => $user_unit_code,
            ]);
        //redirect home
        return Redirect::back()->with('message', 'Petty Cash Voucher have been synced successfully');

        dd($claimant->position->superior_code);
    }

    public function reportsExportUnmarkExported($value)
    {
        //get a list of forms with the above status
        $tasks = PettyCashAccountModel::find($value);
        //umark them
        dd($tasks);
    }

    public function reportsExportUnmarkExportedAll()
    {
        //get a list of forms with the above status
        // $tasks = PettyCashAccountModel::where('status_id', config('constants.petty_cash_status.exported'))->get();
        $exported = config('constants.petty_cash_status.exported');
        $tasks = DB::select("SELECT * FROM eform_petty_cash_account
                        WHERE status_id = {$exported}
                        ORDER BY eform_petty_cash_id ASC ");
        $tasks = PettyCashAccountModel::hydrate($tasks);

        foreach ($tasks as $item) {
//            $item->status_id = config('constants.petty_cash_status.not_exported');
//            $item->save();

            $previous_status = config('constants.petty_cash_status.not_exported');
            $id = $item->id;
            $eform_petty_cash_item = DB::table('eform_petty_cash_account')
                ->where('id', $id)
                ->update(['status_id' => $previous_status]);

        }
        //redirect home
        return Redirect::back()->with('message', 'Petty Cash Exported Accounts have been reversed successfully');
    }

    public function markAccountLinesAsDuplicates($id)
    {
        //$id = 124 ;
        $account_line = DB::select("SELECT * FROM eform_petty_cash_account where id =  {$id} ");
        $account_line = PettyCashAccountModel::hydrate($account_line);
        $size = sizeof($account_line);
        if ($size > 0) {
            $item = $account_line[$size - 1];
            $item->status_id = config('constants.petty_cash_status.void');
            $item->save();
        }
        //redirect home
        return Redirect::back()->with('message', 'Petty Cash Account Line have been Marked as Duplicate successfully');

    }

    public function reverse(Request $request, $id)
    {

        try {
            // get the form using its id
            $eform_petty_cash = DB::select("SELECT * FROM eform_petty_cash where id =  {$id} ");
            $eform_petty_cash = PettyCashAccountModel::hydrate($eform_petty_cash);

            //get current status id
            $status_model = StatusModel::where('id', $eform_petty_cash[0]->config_status_id)
                ->where('eform_id', config('constants.eforms_id.petty_cash'))->first();
            $current_status = $status_model->id;

            //new status
            $new_status_id = $current_status - 1;
            $status_model = StatusModel::where('id', $new_status_id)
                ->where('eform_id', config('constants.eforms_id.petty_cash'))->first();
            $previous_status = $status_model->id;

            //  $eform_petty_cash = DB::select("UPDATE eform_petty_cash SET config_status_id = {$previous_status} where id =  {$id} ");
            $eform_petty_cash = DB::table('eform_petty_cash')
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
//                    'config_eform_id' => config('constants.eforms_id.petty_cash'),
//                    'eform_id' => $eform_petty_cash[0]->id,
//                    'created_by' => $user->id,
//                ]);
            return Redirect::back()->with('message', 'Petty Cash Account Line have been dropped to the previous stage successfully');
        } catch (Exception $exception) {
            return Redirect::back()->with('error', 'Sorry an error happened');
        }
    }


}
