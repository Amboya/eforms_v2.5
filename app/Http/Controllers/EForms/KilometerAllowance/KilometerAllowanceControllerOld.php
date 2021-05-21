<?php

namespace App\Http\Controllers\Eforms\KilometerAllowance;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EForms\KilometerAllowance\KilometerAllowanceModel;
use App\Http\Controllers\EForms\KilometerAllowance\HomeController;
use App\Models\main\TotalsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Models\Main\AttachedFileModel;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Models\Main\EFormModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\main\ProfileModel;
use App\Models\main\ProjectsModel;
use App\Models\main\AccountsChartModel;
use App\Models\main\ProfilePermissionsModel;
use App\Models\main\EformApprovalsModel;
use App\Models\main\StatusModel;
use App\Models\User;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $value)
    {

        //get list of all petty cash forms for today
        if ($value == "all") {
            $list = KilometerAllowanceModel::all();
            $category = "All";
        } else if ($value == "pending") {
            $list = KilometerAllowanceModel::where('config_status_id', '>', config('constants.kilometer_allowance_status.new_application'))
                ->where('config_status_id', '<', config('constants.kilometer_allowance_status.closed'))
                ->where('config_status_id', '!=', config('constants.kilometer_allowance_status.rejected'))
                ->get();
            $category = "Opened";
        } else if ($value == config('constants.kilometer_allowance_status.new_application')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.kilometer_allowance_open'))
                ->get();
            $category = "New Application";
        } else if ($value == config('constants.kilometer_allowance_status.closed')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.closed'))
                ->get();
            $category = "Closed";
        } else if ($value == config('constants.kilometer_allowance_status.rejected')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.rejected'))
                ->get();
            $category = "Rejected";
        } else if ($value == "needs_me") {
            $list = $totals_needs_me = HomeController::needsMeList();
            $category = "Needs My Attention";
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
        ];
        //show the create form
        return view('eforms.kilometer-allowance.create')->with($params);
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

      // dd($request->all());

        //generate random
        $code = self::randGenerator("KAC", 1);

        $kilometer_allowance = KilometerAllowanceModel::firstOrCreate(
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
                'staff_name' => $request->claimant_name,
                'staff_no' => $request->employee_number,
            ],
            [
                'code' => $code,
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
                'staff_name' => $request->claimant_name,
                'staff_no' => $request->employee_number,
                'claim_date' => $request->date_claimant,
                'config_status_id' => config('constants.kilometer_allowance_status.new_application'),
                'profile' => Auth::user()->profile_id,
                'user_unit' => $user->user_unit->code,
                'cost_centre' => $user->user_unit->cost_center_code,
                'business_code' => $user->user_unit->business_unit_code,
                'created_by' => $user->id,

            ]);

        // upload the receipt files
        $files = $request->file('file_upload');
        if ($request->hasFile('file_upload')) {
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
                $path = $file->storeAs('public/kilometer_allowance_files', $fileNameToStore);
                //upload the receipt
                $file = AttachedFileModel::Create(
                    [
                        'name' => $fileNameToStore,
                        'location' => $path,
                        'extension' => $extension,
                        'file_size' => $size,
                        'form_id' => $kilometer_allowance->code,
                        'form_type' => config('constants.eforms_id.kilometer_allowance'),
                    ]);
            }
        }


        //update the totals
        $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('id', config('constants.totals.kilometer_allowance_new'))
            ->first();
        $totals->value = $totals->value + 1;
        $totals->save();

        $eform_model = EFormModel::find(config('constants.eforms_id.kilometer_allowance'));
        $eform_model->total_new = $totals->value;
        $eform_model->save();


        //log the activity
        ActivityLogsController::store($request, "Creating of".config('constants.eforms_name.kilometer_allowance'), "update", " pay point created", $kilometer_allowance->id);

        return Redirect::route('kilometer-allowance-home')->with('message', config('constants.eforms_name.kilometer_allowance').' Details for ' . $kilometer_allowance->code . ' have been Created successfully');
    }



    public function randGenerator($head, $value)
    {
        //random number
        // use the total number of petty cash in the system
        $count = DB::select("SELECT count(id) as total FROM eform_kilometer_allowance ");

        //random number
        $random = $count[0]->total;  // count total and begin again
        $random = sprintf("%06d", ($random + $value));
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
            self::randGenerator($head, $value );
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
        //find the petty cash with that id
        $form = KilometerAllowanceModel::find($id);
        $projects = ProjectsModel::all();
        $accounts = AccountsChartModel::all();
        $attached_files = AttachedFileModel::where('form_id',$form->code) -> where('form_type', config('constants.eforms_id.kilometer_allowance') )->get();
        $approvals = EformApprovalsModel::where('eform_id',$form->id) -> where('config_eform_id', config('constants.eforms_id.kilometer_allowance') )->get();

        //get the list of users who are supposed to work on the form
        $user_array = self::nextUsers($form->profile, $form->config_status_id, $form->staff_no  );


        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'form' => $form,
            'projects' => $projects,
            'attached_files' => $attached_files,
            'user_array' => $user_array,
            'accounts' => $accounts,
            'approvals' => $approvals,
        ];

      //  dd($attached_files);

        //return view
        return view('eforms.kilometer-allowance.show')->with($params);

    }

    /**
     * List the users who are supposed to work on the form next
     * @param $last_profile
     * @param $current_status
     * @param $claimant_man_no
     * @return array
     */
    public function nextUsers($last_profile, $current_status, $claimant_man_no){

        $user_array = [];
        $user = Auth::user();


        //[1]
        //THE LAST PROFILE
        $last_profile_who_worked = ProfileModel::find($last_profile);
        //get the next profiles to work from the last profile  PROFILE Permissions
        $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('profile', $last_profile_who_worked->code)
            ->first();


        //[2]
        //THE NEXT PROFILE
        $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next ;
        //get the profile permissions associated with this next_profile_to_work
        $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('profile', $next_profile_to_work->code)
            ->get();



        //If it is me who is actually supposed to work on the form, then find the next users who are supposed to work after me
        if($user->profile_id ==  $next_profile_to_work->id ){
            //[1A]
            //THE LAST PROFILE
            $last_profile_who_worked = ProfileModel::find($user->profile_id);
            //get the next profiles to work from the last profile  PROFILE Permissions
            $last_profile_who_worked_profilePermission = ProfilePermissionsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('profile', $last_profile_who_worked->code)
                ->first();
            //[2B]
            //THE NEXT PROFILE
            $next_profile_to_work = $last_profile_who_worked_profilePermission->profiles_next ;
            //get the profile permissions associated with this next_profile_to_work
            $profileAssignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('profile', $next_profile_to_work->code)
                ->get();
        }

        //[3]
        //THE USERS
        //check if this next profile is for a claimant and if the Petty-Cash needs Acknowledgement
        if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $current_status == config('constants.kilometer_allowance_status.security_approved')) {
            $user = User::where('staff_no', $claimant_man_no)->first();
            $user_array[] = $user;
        }
        //check if this next profile is for a claimant and if the Petty-Cash is closed
        else if ($next_profile_to_work->id == config('constants.user_profiles.EZESCO_002') &&
            $current_status == config('constants.kilometer_allowance_status.closed') ) {
            //get user
            $user = User::where('staff_no', $claimant_man_no)->first();
            $user_array[] = $user;
        }
        //check if the Petty-Cash is closed
        else if ( $current_status == config('constants.kilometer_allowance_status.closed') ) {
            //get no user
            $user_array = [] ;
        }
        // other wise get the users
        else {
            foreach ($profileAssignement as $item) {
                //get user
                $user = User::find($item->user_id);
                $user_array[] = $user;
            }
        }

        //[4]
        //return the list of users
        return $user_array ;

    }


    public function approve(Request  $request)
    {

        //GET THE KILOMETER ALLOWANCE MODEL
        $form = KilometerAllowanceModel::find($request->id);
        $current_status = $form->status->id;
      //  $new_status = 0;
        $user = Auth::user();

        $eform_model = EFormModel::find(config('constants.eforms_id.kilometer_allowance'));

        //HANDLE REJECTION
        if ($request->approval == config('constants.approval.reject')) {

            //update the totals rejected
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('id', config('constants.totals.kilometer_allowance_reject'))
                ->first();
            $totals->value = $totals->value + 1;
            $totals->save();
            $eform_model->total_rejected = $totals->value;
            $eform_model->save();

            //update the totals open
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                ->where('id', config('constants.totals.kilometer_allowance_open'))
                ->first();
            if ($totals->value > 0) {
                $totals->value = $totals->value - 1;
            }
            $totals->save();
            $eform_model->total_pending = $totals->value;
            $eform_model->save();

        }

        //HANDLE APPROVAL
        if ($request->approval == config('constants.approval.approve')) {

            if ($form->status->id == config('constants.kilometer_allowance_status.audit_approved')) {

                //update the totals closed
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                    ->where('id', config('constants.totals.kilometer_allowance_closed'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_model->total_closed = $totals->value;
                $eform_model->save();

                //update the totals open
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                    ->where('id', config('constants.totals.kilometer_allowance_open'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_model->total_pending = $totals->value;
                $eform_model->save();

            }
            else if ($form->status->id == config('constants.kilometer_allowance_status.new_application')) {
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                    ->where('id', config('constants.totals.kilometer_allowance_open'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_model->total_pending = $totals->value;
                $eform_model->save();

                //update the totals new
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
                    ->where('id', config('constants.totals.kilometer_allowance_new'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_model->total_new = $totals->value;
                $eform_model->save();
            }

        }

        // write a update query to change the
        // insert authorizer's name
        // insert authorizer's man no
        // insert a timestamp
        // status to the new status

        //FOR HOD
        if ( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_004')
            &&  $form->config_status_id == config('constants.kilometer_allowance_status.new_application') ) {
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.hod_approved');
            } else {
                $new_status = config('constants.kilometer_allowance_status.void');
            }
            //update query
            $form->config_status_id = $new_status;
            $form->authorised_by = $user->name;
            $form->authorised_staff_no = $user->staff_no;
            $form->authorised_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }

        //FOR STATION MANAGER
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_003')) {
            //cancel status
            if ($request->approval == config('constants.approval.cancelled')) {
                $new_status = config('constants.kilometer_allowance_status.cancelled');
            } //reject status
            elseif ($request->approval == config('constants.approval.reject')) {
                $new_status = config('constants.kilometer_allowance_status.rejected');
            }//approve status
            elseif ($request->approval == config('constants.approval.approve')) {
                $new_status = config('constants.kilometer_allowance_status.manager_approved');
            } else {
                $new_status = config('constants.kilometer_allowance_status.void');
            }
            //update query
            $form->config_status_id = $new_status;
            $form->station_manager = $user->name;
            $form->station_manager_staff_no = $user->staff_no;
            $form->station_manager_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }

        //save reason
        $reason = EformApprovalsModel::Create(
            [
                'profile' => $user->profile_id,
                'title' => $user->profile_id,
                'staff_no' => $user->staff_no,
                'name' => $user->name,
                'reason' => $request->reason,
                'action' => $request->approval,
                'current_status_id' => $current_status,
                'action_status_id' => $new_status,
                'config_eform_id' => config('constants.eforms_id.kilometer_allowance'),
                'eform_id' => $form->id,
                'created_by' => $user->id,
            ]);

          //send the email
      //  self::nextUsersSendMail($user->profile_id, $new_status,  $form->claimant_staff_no, $form->code) ;

        return Redirect::route('kilometer-allowance-home')->with('message', config('constants.eforms_name.kilometer_allowance').' ' . $form->code . ' for has been ' . $request->approval . ' successfully');


    }



    public function nextUserSendMail($last_profile, $new_status, $form)
    {
        //get the users
        $user_array = self::nextUsers($form);
        $claimant_details = User::find($form->created_by);

        //check if this next profile is for a claimant and if the Petty-Cash needs Acknowledgement
        if ($new_status == config('constants.kilometer_allowance_status.security_approved')) {
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
            $message = 'This is to notify you that there is a petty-cash voucher (' . $form->code . ') that needs your attention.
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
        $to[] = ['email' => 'nshubart@zesco.co.zm', 'name' => 'Shubart Nyimbili'];
        $to[] = ['email' => 'csikazwe@zesco.co.zm', 'name' => 'Chapuka Sikazwe'];
        $to[] = ['email' => 'bchisulo@zesco.co.zm', 'name' => 'Bwalya Chisulo'];
        $to[] = ['email' => 'wsapele@zesco.co.zm', 'name' => 'Wilfred Sapele'];
        //prepare details
        $details = [
            'name' => $names,
            'url' => 'kilometer-allowance-home',
            'subject' => $subject,
            'title' => $title,
            'body' => $message
        ];
        //send mail
        $mail_to_is = Mail::to($to)->send(new SendMail($details));

    }





}
