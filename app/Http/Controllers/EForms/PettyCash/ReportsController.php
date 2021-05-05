<?php

namespace App\Http\Controllers\EForms\PettyCash;

use App\Http\Controllers\Controller;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\EForms\PettyCash\Views\DailyPettyCashTotalsView;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\StatusModel;
use App\Models\Main\Totals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class ReportsController extends Controller
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


    public function filteredReports()
    {
//        $user_units = DailyPettyCashTotalsView::get();
//        dd($user_units);
        $user = Auth::user();
        //[1] REQUESTER
        if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $user_units = ConfigWorkFlow::where('user_unit_code', $user->user_unit_code)
                ->orderBy('user_unit_code')->get();
        }//[2A] HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $user_units = ConfigWorkFlow::where('hod_unit', $user->profile_unit_code)
                ->where('hod_code', $user->profile_job_code)
                ->orderBy('user_unit_code')->get();
        } //[2B] HUMAN RESOURCE.
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $user_units = ConfigWorkFlow::where('hrm_code', $user->profile_job_code)
                ->where('hrm_unit', $user->profile_unit_code)
                ->orderBy('user_unit_code')->get();
        } //[2C] CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $user_units = ConfigWorkFlow::where('ca_code', $user->profile_job_code)
                ->where('ca_unit', $user->profile_unit_code)
                ->orderBy('user_unit_code')->get();
        } //[2D] EXPENDITURE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $user_units = ConfigWorkFlow::where('expenditure_unit', $user->profile_unit_code  ?? "0")
                ->orderBy('user_unit_code')->get();
        } //[2E] SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $user_units = ConfigWorkFlow::where('security_unit', $user->profile_unit_code  ?? "0" )
                ->orderBy('user_unit_code')->get();
        } //[2F] AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $user_units = ConfigWorkFlow::where('audit_unit', $user->profile_unit_code ?? "0")
                ->orderBy('user_unit_code')->get();
        } else {
            $user_units = ConfigWorkFlow::orderBy('user_unit_code')->get();
        }


        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->orderBy('name')->get();

        //data to send to the view
        $params = [
            'category' => "Filtered List" ,
            'status' => $status,
            'user_units' => $user_units,
            'totals_needs_me' => $totals_needs_me,
        ];

        //reports one page
        return view('eforms.petty-cash.reports.filtered_reports')->with($params);
    }

    public function getFilteredReports($user_unit, $status, $start_date, $end_date){

        //first check if you have something in the
        $belongs_to_superior = ConfigWorkFlow::where('user_unit_superior',$user_unit ) ;
        $superior = false ;

      //  dd($belongs_to_superior->get());
        if($belongs_to_superior->exists()){
            $belongs_to_superior =  $belongs_to_superior->get();
            $superior = true ;
            $list = [] ;
            $summary = [] ;
            //select
            foreach ($belongs_to_superior as $item){

                $user_unit_new = $item->user_unit_code ;
                //get the list of transactions
                $list_one = DB::select("SELECT * FROM eform_petty_cash
                    where config_status_id = '{$status}'
                      and user_unit_code = '{$user_unit_new}'
                      and  created_at <= '{$end_date}'
                      and  created_at >= '{$start_date}'
                     ");
                $my_list = PettyCashModel::hydrate($list_one);
                if(sizeof($my_list) < 1){
                    //dd($my_list);
                }else{
                    $list[] = PettyCashModel::hydrate($list_one);
                }



                //get the summary
                $summary_one = DB::select("SELECT sum(amount) as amount , sum(total)as total
                    FROM eform_petty_cash_dashboard_daily_totals_view
                      where config_status_id = '{$status}'
                      and  user_unit_code = '{$user_unit_new}'
                      and  claim_date <= '{$end_date}'
                      and  claim_date >= '{$start_date}'
                       ");
                $my_summary = DailyPettyCashTotalsView::hydrate($summary_one);
                if(sizeof($my_summary) < 1){
                   // dd($my_summary);
                }else{
                    $summary[] = DailyPettyCashTotalsView::hydrate($summary_one);
                }


            }
        }else{
            //get the list of transactions
            $list = DB::select("SELECT * FROM eform_petty_cash
                    where config_status_id = '{$status}'
                      and user_unit_code = '{$user_unit}'
                      and  created_at <= '{$end_date}'
                      and  created_at >= '{$start_date}'
                     ");
            $list = PettyCashModel::hydrate($list);


            //get the summary
            $summary = DB::select("SELECT sum(amount) as amount , sum(total)as total
                    FROM eform_petty_cash_dashboard_daily_totals_view
                      where config_status_id = '{$status}'
                      and  user_unit_code = '{$user_unit}'
                      and  claim_date <= '{$end_date}'
                      and  claim_date >= '{$start_date}'
                       ");
            $summary = DailyPettyCashTotalsView::hydrate($summary);
        }


        //get the status
        $status = StatusModel::find($status);

        //prepare the data
        $params = [
            'status' => $status->name ,
            'list' => $list,
            'superior' => $superior,
            'summary' => $summary
        ];
        //response
        return Response::json($params);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public
    function index(Request $request)
    {
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        $directorates = Totals:: select('column_one_value')
            ->where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('column_one', config('constants.config_totals.directorate'))
            ->groupBy('column_one_value')
            ->get();
        $directs[] = '';
        foreach ($directorates as $director) {
            $directs[] = $director->myDirectorate->code ?? "hi";
        }

        //get the totals closed
        $directorates_closed_totals = Totals:: select('*')
            ->where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('column_one', config('constants.config_totals.directorate'))
            ->where('total_one', config('constants.config_totals.dir_total_closed_count'))
            ->get();

        //  dd($directorates_closed_totals);


        //data to send to the view
        $params = [
            'directorates' => $directorates,
            'totals_needs_me' => $totals_needs_me,
            'directorates_closed_totals' => $directorates_closed_totals,
//            'total_approved' => $total_approved,
//            'total_new' => $total_new,
//            'total_rejected' => $total_rejected,
//            'total_cancelled' => $total_cancelled,
//            'total_open' => $total_open,
//            'total_void' => $total_void,
            'directs' => $directs,
        ];
        //reports one page
        return view('eforms.petty-cash.reports.directorates')->with($params);
    }

    public
    function syncDirectorates()
    {
        /**
         * total Closed
         */
        $closed_status = config('constants.petty_cash_status.closed');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id
           FROM eform_petty_cash where config_status_id = {$closed_status} group by directorate_id  order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        // dd($total_forms);

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'total_one' => config('constants.config_totals.dir_total_closed_count'),
                'total_two' => config('constants.config_totals.dir_total_closed_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,

                'total_one' => config('constants.config_totals.dir_total_closed_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.dir_total_closed_amount'),
                'total_two_value' => $total->amount
            ]);

        }


        /**
         * total new
         */
        $new_status = config('constants.petty_cash_status.new_application');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id
           FROM eform_petty_cash where config_status_id = {$new_status} group by directorate_id  order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'total_one' => config('constants.config_totals.dir_total_new_count'),
                'total_two' => config('constants.config_totals.dir_total_new_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,

                'total_one' => config('constants.config_totals.dir_total_new_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.dir_total_new_amount'),
                'total_two_value' => $total->amount
            ]);

        }

        /**
         * total rejected
         */
        $rejected_status = config('constants.petty_cash_status.rejected');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id
           FROM eform_petty_cash where config_status_id = {$rejected_status} group by directorate_id  order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'total_one' => config('constants.config_totals.dir_total_rejected_count'),
                'total_two' => config('constants.config_totals.dir_total_rejected_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,

                'total_one' => config('constants.config_totals.dir_total_rejected_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.dir_total_rejected_amount'),
                'total_two_value' => $total->amount
            ]);
        }


        /**
         * total pending
         */
        $status1 = config('constants.petty_cash_status.hod_approved');
        $status2 = config('constants.petty_cash_status.hr_approved');
        $status3 = config('constants.petty_cash_status.chief_accountant');
        $status4 = config('constants.petty_cash_status.funds_disbursement');
        $status5 = config('constants.petty_cash_status.funds_acknowledgement');
        $status6 = config('constants.petty_cash_status.security_approved');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id
           FROM eform_petty_cash
           where config_status_id = {$status1}
           or config_status_id = {$status2}
           or config_status_id = {$status3}
           or config_status_id = {$status4}
           or config_status_id = {$status5}
           or config_status_id = {$status6}
           group by directorate_id  order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'total_one' => config('constants.config_totals.dir_total_pending_count'),
                'total_two' => config('constants.config_totals.dir_total_pending_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,

                'total_one' => config('constants.config_totals.dir_total_pending_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.dir_total_pending_amount'),
                'total_two_value' => $total->amount
            ]);
        }


        /**
         * total Cancelled
         */
        $cancelled_status = config('constants.petty_cash_status.cancelled');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id
           FROM eform_petty_cash where config_status_id = {$cancelled_status} group by directorate_id  order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'total_one' => config('constants.config_totals.dir_total_cancelled_count'),
                'total_two' => config('constants.config_totals.dir_total_cancelled_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,

                'total_one' => config('constants.config_totals.dir_total_cancelled_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.dir_total_cancelled_amount'),
                'total_two_value' => $total->amount
            ]);

        }


        /**
         * total Void
         */
        $void_status = config('constants.petty_cash_status.void');
        $void_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id, user_unit_code
           FROM eform_petty_cash where config_status_id = {$void_status} group by directorate_id , user_unit_code order by amount desc ");
        $total_forms = PettyCashModel::hydrate($void_forms)->all();

        foreach ($void_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'total_one' => config('constants.config_totals.dir_total_void_count'),
                'total_two' => config('constants.config_totals.dir_total_void_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,

                'total_one' => config('constants.config_totals.dir_total_void_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.dir_total_void_amount'),
                'total_two_value' => $total->amount
            ]);

        }

        //return back
        return Redirect::back()->with('message', 'Totals Have Been Updated successfully');


    }

    public
    function syncUserUnits()
    {

        // dd(11111);
        /**
         * total Closed
         */
        $closed_status = config('constants.petty_cash_status.closed');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id, user_unit_code
           FROM eform_petty_cash where config_status_id = {$closed_status} group by directorate_id , user_unit_code order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        //  dd($total_forms);

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate(
                [
                    'eform_id' => config('constants.eforms_id.petty_cash'),
                    'eform_code' => config('constants.eforms_name.petty_cash'),

                    'column_one' => config('constants.config_totals.directorate'),
                    'column_one_value' => $total->directorate_id,
                    'column_two' => config('constants.config_totals.user_unit'),
                    'column_two_value' => $total->user_unit_code,

                    'total_one' => config('constants.config_totals.total_closed_count'),
                    'total_two' => config('constants.config_totals.total_closed_amount')
                ],
                [
                    'eform_id' => config('constants.eforms_id.petty_cash'),
                    'eform_code' => config('constants.eforms_name.petty_cash'),

                    'column_one' => config('constants.config_totals.directorate'),
                    'column_one_value' => $total->directorate_id,
                    'column_two' => config('constants.config_totals.user_unit'),
                    'column_two_value' => $total->user_unit_code,

                    'total_one' => config('constants.config_totals.total_closed_count'),
                    'total_one_value' => $total->total,
                    'total_two' => config('constants.config_totals.total_closed_amount'),
                    'total_two_value' => $total->amount
                ]);

        }


        /**
         * total new
         */
        $new_status = config('constants.petty_cash_status.new_application');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id, user_unit_code
           FROM eform_petty_cash where config_status_id = {$new_status} group by directorate_id , user_unit_code order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,
                'total_one' => config('constants.config_totals.total_new_count'),
                'total_two' => config('constants.config_totals.total_new_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,

                'total_one' => config('constants.config_totals.total_new_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.total_new_amount'),
                'total_two_value' => $total->amount
            ]);

        }

        /**
         * total rejected
         */
        $rejected_status = config('constants.petty_cash_status.rejected');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id, user_unit_code
           FROM eform_petty_cash where config_status_id = {$rejected_status} group by directorate_id , user_unit_code order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,
                'total_one' => config('constants.config_totals.total_rejected_count'),
                'total_two' => config('constants.config_totals.total_rejected_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,

                'total_one' => config('constants.config_totals.total_rejected_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.total_rejected_amount'),
                'total_two_value' => $total->amount
            ]);
        }


        /**
         * total pending
         */
        $status1 = config('constants.petty_cash_status.hod_approved');
        $status2 = config('constants.petty_cash_status.hr_approved');
        $status3 = config('constants.petty_cash_status.chief_accountant');
        $status4 = config('constants.petty_cash_status.funds_disbursement');
        $status5 = config('constants.petty_cash_status.funds_acknowledgement');
        $status6 = config('constants.petty_cash_status.security_approved');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id, user_unit_code
           FROM eform_petty_cash
           where config_status_id = {$status1}
           or config_status_id = {$status2}
           or config_status_id = {$status3}
           or config_status_id = {$status4}
           or config_status_id = {$status5}
           or config_status_id = {$status6}
           group by directorate_id , user_unit_code order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,
                'total_one' => config('constants.config_totals.total_pending_count'),
                'total_two' => config('constants.config_totals.total_pending_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,

                'total_one' => config('constants.config_totals.total_pending_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.total_pending_amount'),
                'total_two_value' => $total->amount
            ]);
        }


        /**
         * total Cancelled
         */
        $cancelled_status = config('constants.petty_cash_status.cancelled');
        $total_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id, user_unit_code
           FROM eform_petty_cash where config_status_id = {$cancelled_status} group by directorate_id , user_unit_code order by amount desc ");
        $total_forms = PettyCashModel::hydrate($total_forms)->all();

        foreach ($total_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,
                'total_one' => config('constants.config_totals.total_cancelled_count'),
                'total_two' => config('constants.config_totals.total_cancelled_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,

                'total_one' => config('constants.config_totals.total_cancelled_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.total_cancelled_amount'),
                'total_two_value' => $total->amount
            ]);

        }


        /**
         * total Void
         */
        $void_status = config('constants.petty_cash_status.void');
        $void_forms = DB::select("SELECT SUM(total_payment) as amount,  count('id') as total , directorate_id, user_unit_code
           FROM eform_petty_cash where config_status_id = {$void_status} group by directorate_id , user_unit_code order by amount desc ");
        $total_forms = PettyCashModel::hydrate($void_forms)->all();

        foreach ($void_forms as $total) {
            $total_create = Totals::updateOrCreate([
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),
                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,
                'total_one' => config('constants.config_totals.total_void_count'),
                'total_two' => config('constants.config_totals.total_void_amount')
            ], [
                'eform_id' => config('constants.eforms_id.petty_cash'),
                'eform_code' => config('constants.eforms_name.petty_cash'),

                'column_one' => config('constants.config_totals.directorate'),
                'column_one_value' => $total->directorate_id,
                'column_two' => config('constants.config_totals.user_unit'),
                'column_two_value' => $total->user_unit_code,

                'total_one' => config('constants.config_totals.total_void_count'),
                'total_one_value' => $total->total,
                'total_two' => config('constants.config_totals.total_void_amount'),
                'total_two_value' => $total->amount
            ]);

        }

        //return back
        return Redirect::back()->with('message', 'Totals Have Been Updated successfully');


    }

}
