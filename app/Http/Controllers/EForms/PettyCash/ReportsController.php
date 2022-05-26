<?php

namespace App\Http\Controllers\EForms\PettyCash;

use App\Http\Controllers\Controller;
use App\Models\EForms\PettyCash\PettyCashAccountModel;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\EForms\PettyCash\Views\AllPettyCashTotalsView;
use App\Models\EForms\PettyCash\Views\DailyPettyCashTotalsView;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\DirectoratesModel;
use App\Models\Main\DivisionsModel;
use App\Models\Main\StatusModel;
use App\Models\Main\Totals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

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


    //UNITS REPORTS
    public function units($status_id)
    {
        //QUERY 1
        if ($status_id == config('constants.all')) {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");
        } //QUERY 2
        else {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");
        }
        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);

        //STATUS LIST
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        $category = " All";
        $total_num = 0;

        //GET ALL UNITS
        $units = ConfigWorkFlow::select('id', 'user_unit_code', 'user_unit_description')
            ->where('user_unit_status', config('constants.user_unit_active'))
            ->get();

        //RETURN VIEW
        return view('eforms.petty-cash.reports.units')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'units'
            ));
    }

    public function unitsSearch(Request $request)
    {
        //RECEIVE STATUS
        $status_id = $request->status_select;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();

        //GET ALL
        if ($status_id == config('constants.all')) {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");
            $status_name = config('constants.all');
        } //GET GIVEN
        elseif ($status_id == config('constants.money_given')) {
            $status_id_1 = config('constants.petty_cash_status.funds_disbursement');
            $status_id_2 = config('constants.petty_cash_status.funds_acknowledgement');
            $status_id_3 = config('constants.petty_cash_status.security_approved');
            $status_id_4 = config('constants.petty_cash_status.receipt_approved');
            $status_id_5 = config('constants.petty_cash_status.closed');
            $status_id_6 = config('constants.petty_cash_status.audited');
            $status_id_7 = config('constants.petty_cash_status.reimbursement_box');
            $status_id_8 = config('constants.petty_cash_status.await_audit');
            $status_id_9 = config('constants.petty_cash_status.audit_box');
            $form = DB::select("SELECT
        count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id_1}
        or config_status_id = {$status_id_2}
        or config_status_id = {$status_id_3}
        or config_status_id = {$status_id_4}
        or config_status_id = {$status_id_5}
        or config_status_id = {$status_id_6}
        or config_status_id = {$status_id_7}
        or config_status_id = {$status_id_8}
        or config_status_id = {$status_id_9}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id
        ");
            $status_name = config('constants.money_given');
        } //GET QUERIED
        elseif ($status_id == config('constants.money_queried')) {
            $status_id_1 = config('constants.petty_cash_status.audit_rejected');
            $form = DB::select("SELECT
       count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id_1}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id
        ");
            $status_name = config('constants.money_queried');
        } //GET PENDING
        elseif ($status_id == config('constants.money_pending')) {
            $status_id_1 = config('constants.petty_cash_status.new_application');
            $status_id_2 = config('constants.petty_cash_status.hod_approved');
            $status_id_3 = config('constants.petty_cash_status.hr_approved');
            $status_id_4 = config('constants.petty_cash_status.chief_accountant');
            $form = DB::select("SELECT
        count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id_1}
        or config_status_id = {$status_id_2}
        or config_status_id = {$status_id_3}
        or config_status_id = {$status_id_4}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id
        ");
            $status_name = config('constants.money_pending');
        } //GET REJECTED
        elseif ($status_id == config('constants.money_rejected')) {
            $status_id_1 = config('constants.petty_cash_status.rejected');
            $status_id_2 = config('constants.petty_cash_status.void');
            $status_id_3 = config('constants.petty_cash_status.cancelled');
            $form = DB::select("SELECT
         count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id_1}
        or config_status_id = {$status_id_2}
        or config_status_id = {$status_id_3}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id
        ");
            $status_name = config('constants.money_rejected');
        } //GET SPECIFIED
        else {
            $form = DB::select("SELECT
         config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");
            $status_name = $status->where('id', $status_id)->first()->name;
        }

        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);

        //GET ALL UNITS
        $units = ConfigWorkFlow::select('id', 'user_unit_code', 'user_unit_description')
            ->where('user_unit_status', config('constants.user_unit_active'))
            ->get();

        //count all that needs me
        $total_num = 0;
        $totals_needs_me = HomeController::needsMeCount();
        $category = " " . $status_name;
        //RETURN VIEW
        return view('eforms.petty-cash.reports.units')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'units'
            ));
    }

    //DIRECTORATES REPORTS
    public function directorates($status_id)
    {
        $date_range = "";
        //QUERY 1
        if ($status_id == config('constants.all')) {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");
        } //QUERY 2
        else {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");

        }
        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);
        //GET ALL DIRECTORATES
        $directorates = DirectoratesModel::all();
        $direc = [];
        foreach ($directorates as $directorate) {
            $direc[$directorate->name][] = $list->where('directorate_id', $directorate->id)->first()->amount ?? 0;
        }

        //count all that needs me
        $total_num = 0;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();
        $totals_needs_me = HomeController::needsMeCount();
        $category = " All";

        //RETURN VIEW
        return view('eforms.petty-cash.reports.directorates')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'direc', 'directorates', 'date_range'
            ));
    }

    public function directoratesSearch(Request $request)
    {
        //RECEIVE STATUS
        $date_range = "";
        $status_id = $request->status_select;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();


        /* ****************************************
        * GET ALL
        * ***************************************/
        if ($status_id == config('constants.all')) {
            $form = DB::select("SELECT
        count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
        from eform_petty_cash
        group by  directorate_id
        ");


            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id)  as total, sum(total_payment) as amount ,sum(change) as change  , directorate_id
                    from eform_petty_cash
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id)  as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment , change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment , change  , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment , change  , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.all');
        } /* ****************************************
         * GET GIVEN
         * ***************************************/
        elseif ($status_id == config('constants.money_given')) {



            //  'new_application' => "21",
            //        'hod_approved' => "22",
            //        'hr_approved' => "23",
            //        'chief_accountant' => "24",
            //        'funds_disbursement' => "25",
            //        'funds_acknowledgement' => "26",
            //        'security_approved' => "27",
            //        'receipt_approved' => "28",
            //        'closed' => "28",
            //        'audited' => "29",
            //        'rejected' => "30",
            //        'export_not_ready' => "141",
            //        'not_exported' => "41",
            //        'exported' => "42",
            //        'export_failed' => "43",
            //        'void' => "101",
            //        'cancelled' => "161",
            //        'queried' => "201",

            $status_id_1 = 25 ;
            $status_id_2 = 26;
            $status_id_3 = 27;
            $status_id_4 = 28;
            $status_id_5 = 28;
            $status_id_6 = 29;
            $status_id_7 = 29;
            $status_id_8 = 29;
            $status_id_9 = 29;

            /** STATUSES*/
//            $status_id_1 = config('constants.petty_cash_status.funds_disbursement');
//            $status_id_2 = config('constants.petty_cash_status.funds_acknowledgement');
//            $status_id_3 = config('constants.petty_cash_status.security_approved');
//            $status_id_4 = config('constants.petty_cash_status.receipt_approved');
//            $status_id_5 = config('constants.petty_cash_status.closed');
//            $status_id_6 = config('constants.petty_cash_status.audited');
//            $status_id_7 = config('constants.petty_cash_status.reimbursement_box');
//            $status_id_8 = config('constants.petty_cash_status.await_audit');
//            $status_id_9 = config('constants.petty_cash_status.audit_box');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                    ");



            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {

//                dd(5555);
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where  created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where   config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            } /** SPECIFIC DATE*/
            elseif (($request->date_from != null && $request->date_to != null) && ($request->date_from == $request->date_to)) {
                $date_range = "Transactions for " . $request->date_to;

                //  dd($request->date_to);

                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            }


            $status_name = config('constants.money_given');
        } /* ****************************************


         * GET QUERIED
         * ***************************************/
        elseif ($status_id == config('constants.money_queried')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.audit_rejected');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    group by  directorate_id
                    ");
            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.money_queried');
        } /* ****************************************
        *GET PENDING
        * ***************************************/
        elseif ($status_id == config('constants.money_pending')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.new_application');
            $status_id_2 = config('constants.petty_cash_status.hod_approved');
            $status_id_3 = config('constants.petty_cash_status.hr_approved');
            $status_id_4 = config('constants.petty_cash_status.chief_accountant');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.money_pending');
        } /* ****************************************
        *GET REJECTED
        * ***************************************/
        elseif ($status_id == config('constants.money_rejected')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.rejected');
            $status_id_2 = config('constants.petty_cash_status.void');
            $status_id_3 = config('constants.petty_cash_status.cancelled');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            }
            $status_name = config('constants.money_rejected');
        } /* ****************************************
        *GET SPECIFIED
        * ***************************************/
        else {

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                    where config_status_id = {$status_id}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                   where config_status_id = {$status_id}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                     where config_status_id = {$status_id}
                      group by  directorate_id
                   ");
            }

            $status_name = $status->where('id', $status_id)->first()->name;
        }

        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);
        //GET ALL DIRECTORATES
        $directorates = DirectoratesModel::all();
        $direc = [];
        foreach ($directorates as $directorate) {
            $direc[$directorate->name][] = $list->where('directorate_id', $directorate->id)->first()->amount ?? 0;
        }
        $total_num = 0;


        //  dd($list);


        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        $category = " " . $status_name;
        //RETURN VIEW
        return view('eforms.petty-cash.reports.directorates')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'direc', 'directorates', 'date_range'
            ));
    }

    //DIVISIONS REPORTS
    public function divisions($status_id)
    {
        $date_range = "";
        //QUERY 1
        if ($status_id == config('constants.all')) {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");
        } //QUERY 2
        else {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");

        }
        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);
        //GET ALL DIRECTORATES
        $directorates = DirectoratesModel::all();
        $direc = [];
        foreach ($directorates as $directorate) {
            $direc[$directorate->name][] = $list->where('directorate_id', $directorate->id)->first()->amount ?? 0;
        }

        //count all that needs me
        $total_num = 0;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();
        $totals_needs_me = HomeController::needsMeCount();
        $category = " All";

        //RETURN VIEW
        return view('eforms.petty-cash.reports.divisions')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'direc', 'directorates', 'date_range'
            ));
    }

    public function divisionsSearch(Request $request)
    {

        //RECEIVE STATUS
        $date_range = "";
        $status_id = $request->status_select;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();


        /* ****************************************
        * GET ALL
        * ***************************************/
        if ($status_id == config('constants.all')) {
            $form = DB::select("SELECT
        count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
        from eform_petty_cash
        group by  directorate_id
        ");


            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id)  as total, sum(total_payment) as amount ,sum(change) as change  , directorate_id
                    from eform_petty_cash
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                dd(33333);
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id)  as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment , change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment , change  , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment , change  , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.all');
        } /* ****************************************
         * GET GIVEN
         * ***************************************/
        elseif ($status_id == config('constants.money_given')) {

            /** STATUSES*/
            $status_id_1 = config('constants.petty_cash_status.funds_disbursement');
            $status_id_2 = config('constants.petty_cash_status.funds_acknowledgement');
            $status_id_3 = config('constants.petty_cash_status.security_approved');
            $status_id_4 = config('constants.petty_cash_status.receipt_approved');
            $status_id_5 = config('constants.petty_cash_status.closed');
            $status_id_6 = config('constants.petty_cash_status.audited');
            $status_id_7 = config('constants.petty_cash_status.reimbursement_box');
            $status_id_8 = config('constants.petty_cash_status.await_audit');
            $status_id_9 = config('constants.petty_cash_status.audit_box');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                    ");


            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {

                print_r("SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , division_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , division_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  division_id
                     ");

                dd();

                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , division_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , division_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  division_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            } /** SPECIFIC DATE*/
            elseif (($request->date_from != null && $request->date_to != null) && ($request->date_from == $request->date_to)) {
                $date_range = "Transactions for " . $request->date_to;

                //  dd($request->date_to);

                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            }


            $status_name = config('constants.money_given');
        } /* ****************************************
         * GET QUERIED
         * ***************************************/
        elseif ($status_id == config('constants.money_queried')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.audit_rejected');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    group by  directorate_id
                    ");
            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.money_queried');
        } /* ****************************************
        *GET PENDING
        * ***************************************/
        elseif ($status_id == config('constants.money_pending')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.new_application');
            $status_id_2 = config('constants.petty_cash_status.hod_approved');
            $status_id_3 = config('constants.petty_cash_status.hr_approved');
            $status_id_4 = config('constants.petty_cash_status.chief_accountant');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.money_pending');
        } /* ****************************************
        *GET REJECTED
        * ***************************************/
        elseif ($status_id == config('constants.money_rejected')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.rejected');
            $status_id_2 = config('constants.petty_cash_status.void');
            $status_id_3 = config('constants.petty_cash_status.cancelled');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            }
            $status_name = config('constants.money_rejected');
        } /* ****************************************
        *GET SPECIFIED
        * ***************************************/
        else {

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                    where config_status_id = {$status_id}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                   where config_status_id = {$status_id}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                     where config_status_id = {$status_id}
                      group by  directorate_id
                   ");
            }

            $status_name = $status->where('id', $status_id)->first()->name;
        }

        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);
        //GET ALL DIRECTORATES
       // $directorates = DirectoratesModel::all();
        $directorates = DivisionsModel::all();

        // division_id
        $direc = [];
        foreach ($directorates as $directorate) {
            $direc[$directorate->name][] = $list->where('division_id', $directorate->id)->first()->amount ?? 0;
        }
        $total_num = 0;


        //  dd($list);


        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        $category = " " . $status_name;
        //RETURN VIEW
        return view('eforms.petty-cash.reports.divisions')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'direc', 'directorates', 'date_range'
            ));
    }


    //DUPLICATES
    public function updateThePettyCashAccountsWithCorrectPTCODE($status_id)
    {
        //get all the petty petty cash accounts
        $accounts_lines = DB::select("
            select * from eform_petty_cash_account where id > 20000 and  id < 25000
        ");

        //HYDRATE THE LIST
        $accounts_lines_list = PettyCashAccountModel::hydrate($accounts_lines);

        //loop through all accounts
        foreach ($accounts_lines_list as $accounts_line) {
            //get associated petty cash
            $petty_cash_id = $accounts_line->eform_petty_cash_id;
            $petty_cash = DB::select("SELECT * FROM eform_petty_cash
                            WHERE id = {$petty_cash_id}  ");
            $tasks_pt = PettyCashModel::hydrate($petty_cash)->first();

            //update

            $accounts_line->cost_center = $tasks_pt->cost_center;
            $accounts_line->business_unit_code = $tasks_pt->business_unit_code;
            $accounts_line->user_unit_code = $tasks_pt->user_unit_code;

            $accounts_line->claimant_name = $tasks_pt->claimant_name;
            $accounts_line->claimant_staff_no = $tasks_pt->claimant_staff_no;
            $accounts_line->claim_date = $tasks_pt->claim_date;
            $accounts_line->petty_cash_code = $tasks_pt->code;
            $accounts_line->hod_code =  $tasks_pt->hod_code;
                    $accounts_line->hod_unit =  $tasks_pt->hod_unit;
                    $accounts_line->ca_code =  $tasks_pt->ca_code;
                    $accounts_line->ca_unit =  $tasks_pt->ca_unit;
                    $accounts_line->hrm_code =  $tasks_pt->hrm_code;
                    $accounts_line->hrm_unit =  $tasks_pt->hrm_unit;
                    $accounts_line->expenditure_code =  $tasks_pt->expenditure_code;
                    $accounts_line->expenditure_unit =  $tasks_pt->expenditure_unit;
                    $accounts_line->security_code = $tasks_pt->security_code;
                    $accounts_line->security_unit = $tasks_pt->security_unit;
            $accounts_line->save();

        }

        dd($accounts_lines_list);



    }

    public function duplicates($status_id)
    {
        $date_range = "";
        //QUERY 1
        if ($status_id == config('constants.all')) {
            $form = DB::select("
            select * from eform_petty_cash where code  in (  SELECT code
                FROM eform_petty_cash
                GROUP BY code
                HAVING COUNT(code) > 1 )
        ");
        } //QUERY 2
        else {
            $form = DB::select("
            select * from eform_petty_cash where code  in (  SELECT code
            FROM eform_petty_cash
            GROUP BY code
            HAVING COUNT(code) > 1 )
        ");

        }
        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);
        //GET ALL DIRECTORATES
        $directorates = DirectoratesModel::all();
        $direc = [];
        foreach ($directorates as $directorate) {
            $direc[$directorate->name][] = $list->where('directorate_id', $directorate->id)->first()->amount ?? 0;
        }

        //count all that needs me
        $total_num = 0;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();
        $totals_needs_me = HomeController::needsMeCount();
        $category = " All";

        //RETURN VIEW
        return view('eforms.petty-cash.reports.duplicates')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'direc', 'directorates', 'date_range'
            ));
    }

    public function duplicatesSearch(Request $request)
    {
        $status_id = "" ;
        $date_range = "";
        //QUERY 1
        if ($status_id == config('constants.all')) {
            $form = DB::select("
            select * from eform_petty_cash where code  in (  SELECT code
                FROM eform_petty_cash
                GROUP BY code
                HAVING COUNT(code) > 1 )
        ");
        } //QUERY 2
        else {
            $form = DB::select("
            select * from eform_petty_cash where code  in (  SELECT code
            FROM eform_petty_cash
            GROUP BY code
            HAVING COUNT(code) > 1 )
        ");

        }
        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);
        //GET ALL DIRECTORATES
        $directorates = DirectoratesModel::all();
        $direc = [];
        foreach ($directorates as $directorate) {
            $direc[$directorate->name][] = $list->where('directorate_id', $directorate->id)->first()->amount ?? 0;
        }

        //count all that needs me
        $total_num = 0;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();
        $totals_needs_me = HomeController::needsMeCount();
        $category = " All";

        //RETURN VIEW
        return view('eforms.petty-cash.reports.duplicates')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'direc', 'directorates', 'date_range'
            ));
    }


    //BUSINESS UNITS REPORTS
    public function businessUnits($status_id)
    {
        $date_range = "";

        //QUERY 1
        if ($status_id == config('constants.all')) {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");
        } //QUERY 2
        else {
            $form = DB::select("SELECT
        config_status_id, count(id) as total, sum(total_payment) as amount ,sum(change) as change ,
        user_unit_code, business_unit_code ,cost_center, directorate_id
        from eform_petty_cash
        where config_status_id = {$status_id}
        group by  user_unit_code, business_unit_code ,cost_center, directorate_id, config_status_id
        ");

        }
        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);
        //GET ALL DIRECTORATES
        $directorates = DirectoratesModel::all();
        $direc = [];
        foreach ($directorates as $directorate) {
            $direc[$directorate->name][] = $list->where('directorate_id', $directorate->id)->first()->amount ?? 0;
        }

        //count all that needs me
        $total_num = 0;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();
        $totals_needs_me = HomeController::needsMeCount();
        $category = " All";

        //RETURN VIEW
        return view('eforms.petty-cash.reports.business_units')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'direc', 'directorates', 'date_range'
            ));
    }

    public function businessUnitsSearch(Request $request)
    {
        //RECEIVE STATUS
        $date_range = "";
        $status_id = $request->status_select;
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->get();


        /* ****************************************
        * GET ALL
        * ***************************************/
        if ($status_id == config('constants.all')) {
            $form = DB::select("SELECT
        count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
        from eform_petty_cash
        group by  directorate_id
        ");


            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , business_unit_code
                    from eform_petty_cash
                    group by  business_unit_code
                    ");

                //  dd($form);


            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.all');
        } /* ****************************************
         * GET GIVEN
         * ***************************************/
        elseif ($status_id == config('constants.money_given')) {

            /** STATUSES*/
            $status_id_1 = config('constants.petty_cash_status.funds_disbursement');
            $status_id_2 = config('constants.petty_cash_status.funds_acknowledgement');
            $status_id_3 = config('constants.petty_cash_status.security_approved');
            $status_id_4 = config('constants.petty_cash_status.receipt_approved');
            $status_id_5 = config('constants.petty_cash_status.closed');
            $status_id_6 = config('constants.petty_cash_status.audited');
            $status_id_7 = config('constants.petty_cash_status.reimbursement_box');
            $status_id_8 = config('constants.petty_cash_status.await_audit');
            $status_id_9 = config('constants.petty_cash_status.audit_box');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                    ");


            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            } /** SPECIFIC DATE*/
            elseif (($request->date_from != null && $request->date_to != null) && ($request->date_from == $request->date_to)) {
                $date_range = "Transactions for " . $request->date_to;

                dd($request->date_to);

                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    or config_status_id = {$status_id_5}
                    or config_status_id = {$status_id_6}
                    or config_status_id = {$status_id_7}
                    or config_status_id = {$status_id_8}
                    or config_status_id = {$status_id_9}
                    group by  directorate_id
                     ");
            }


            $status_name = config('constants.money_given');
        } /* ****************************************
         * GET QUERIED
         * ***************************************/
        elseif ($status_id == config('constants.money_queried')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.audit_rejected');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    group by  directorate_id
                    ");
            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                 where config_status_id = {$status_id_1}
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.money_queried');
        } /* ****************************************
        *GET PENDING
        * ***************************************/
        elseif ($status_id == config('constants.money_pending')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.new_application');
            $status_id_2 = config('constants.petty_cash_status.hod_approved');
            $status_id_3 = config('constants.petty_cash_status.hr_approved');
            $status_id_4 = config('constants.petty_cash_status.chief_accountant');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    or config_status_id = {$status_id_4}
                    group by  directorate_id
                     ");
            }

            $status_name = config('constants.money_pending');
        } /* ****************************************
        *GET REJECTED
        * ***************************************/
        elseif ($status_id == config('constants.money_rejected')) {
            /** STATUS*/
            $status_id_1 = config('constants.petty_cash_status.rejected');
            $status_id_2 = config('constants.petty_cash_status.void');
            $status_id_3 = config('constants.petty_cash_status.cancelled');

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                   where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                  where config_status_id = {$status_id_1}
                    or config_status_id = {$status_id_2}
                    or config_status_id = {$status_id_3}
                    group by  directorate_id
                     ");
            }
            $status_name = config('constants.money_rejected');
        } /* ****************************************
        *GET SPECIFIED
        * ***************************************/
        else {

            /** CUMULATIVE*/
            if ($request->date_from == null && $request->date_to == null) {
                $date_range = "Cumulative Totals";
                $form = DB::select("SELECT
                    count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    from eform_petty_cash
                    where config_status_id = {$status_id}
                    group by  directorate_id
                    ");

            } /** BY DATE RANGE*/
            elseif ($request->date_from != null && $request->date_to != null) {
                $date_range = "From " . $request->date_from . " To " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at BETWEEN  '{$request->date_from}' AND '{$request->date_to}'
                         )
                    where config_status_id = {$status_id}
                    group by  directorate_id
                     ");
            } /** DATE FROM TO TODAY (Greater than)*/
            elseif ($request->date_from != null && $request->date_to == null) {
                $date_range = "Transactions on or After " . $request->date_from;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                         where created_at >= '{$request->date_from}'
                         )
                   where config_status_id = {$status_id}
                    group by  directorate_id
                     ");
            } /** DATE TO (Less than)*/
            elseif ($request->date_from == null && $request->date_to != null) {
                $date_range = "Transactions on or Before " . $request->date_to;
                $form = DB::select(
                    "SELECT count(id) as total, sum(total_payment) as amount ,sum(change) as change , directorate_id
                    FROM (
                         SELECT id,   config_status_id ,total_payment ,change , directorate_id
                         from eform_petty_cash
                          where created_at <= '{$request->date_to}'
                         )
                     where config_status_id = {$status_id}
                      group by  directorate_id
                   ");
            }

            $status_name = $status->where('id', $status_id)->first()->name;
        }


        //HYDRATE THE LIST
        $list = AllPettyCashTotalsView::hydrate($form);
        //GET ALL DIRECTORATES
        $business_units = ConfigWorkFlow::where('user_unit_cc_code', 0)->get();
        $b_units = [];

        // dd($list->first());
        foreach ($business_units as $bu) {
            //  dd($business_units->user_unit_description->first());
            $b_units[$bu->user_unit_description][] = $list->where('business_unit_code', $bu->user_unit_bu_code)->first()->amount ?? 0;
        }


        $total_num = 0;

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        $category = " " . $status_name;
        //RETURN VIEW
        return view('eforms.petty-cash.reports.business_units')->with(
            compact('list', 'category', 'totals_needs_me',
                'total_num', 'status', 'b_units', 'business_units', 'date_range'
            ));
    }


    public function filteredReports()
    {
//        $user_units = DailyPettyCashTotalsView::get();
//        dd($user_units);
        $user = Auth::user();
        //[1] REQUESTER
        if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $user_units = ConfigWorkFlow::where('user_unit_code', $user->user_unit_code)
                ->where('user_unit_status', config('constants.user_unit_active'))
                ->orderBy('user_unit_code')->get();
        }//[2A] HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $user_units = ConfigWorkFlow::where('hod_unit', $user->profile_unit_code)
                ->where('hod_code', $user->profile_job_code)
                ->where('user_unit_status', config('constants.user_unit_active'))
                ->orderBy('user_unit_code')->get();
        } //[2B] HUMAN RESOURCE.
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $user_units = ConfigWorkFlow::where('hrm_code', $user->profile_job_code)
                ->where('user_unit_status', config('constants.user_unit_active'))
                ->where('hrm_unit', $user->profile_unit_code)
                ->orderBy('user_unit_code')->get();
        } //[2C] CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $user_units = ConfigWorkFlow::where('ca_code', $user->profile_job_code)
                ->where('user_unit_status', config('constants.user_unit_active'))
                ->where('ca_unit', $user->profile_unit_code)
                ->orderBy('user_unit_code')->get();
        } //[2D] EXPENDITURE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $user_units = ConfigWorkFlow::where('expenditure_unit', $user->profile_unit_code ?? "0")
                ->where('user_unit_status', config('constants.user_unit_active'))
                ->orderBy('user_unit_code')->get();
        } //[2E] SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $user_units = ConfigWorkFlow::where('security_unit', $user->profile_unit_code ?? "0")
                ->where('user_unit_status', config('constants.user_unit_active'))
                ->orderBy('user_unit_code')->get();
        } //[2F] AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $user_units = ConfigWorkFlow::where('audit_unit', $user->profile_unit_code ?? "0")
                ->where('user_unit_status', config('constants.user_unit_active'))
                ->orderBy('user_unit_code')->get();
        } else {
            $user_units = ConfigWorkFlow::orderBy('user_unit_code')->get();
        }

        if ($user->type_id == config('constants.user_types.developer')) {
            $user_units = ConfigWorkFlow::where('user_unit_status', config('constants.user_unit_active'))
                ->orderBy('user_unit_code')->get();
        }


        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        $status = StatusModel::where('eform_id', config('constants.eforms_id.petty_cash'))->orderBy('name')->get();

        //data to send to the view
        $params = [
            'category' => "Filtered List",
            'status' => $status,
            'user_units' => $user_units,
            'totals_needs_me' => $totals_needs_me,
        ];

        //reports one page
        return view('eforms.petty-cash.reports.filtered_reports')->with($params);
    }

    public function getFilteredReports($user_unit, $status, $start_date, $end_date)
    {

        //first check if you have something in the
        $belongs_to_superior = ConfigWorkFlow::where('user_unit_superior', $user_unit);
        $superior = false;

        //  dd($belongs_to_superior->get());
        if ($belongs_to_superior->exists()) {
            $belongs_to_superior = $belongs_to_superior->get();
            $superior = true;
            $list = [];
            $summary = [];
            //select
            foreach ($belongs_to_superior as $item) {

                $user_unit_new = $item->user_unit_code;
                //get the list of transactions
                $list_one = DB::select("SELECT * FROM eform_petty_cash
                    where config_status_id = '{$status}'
                      and user_unit_code = '{$user_unit_new}'
                      and  created_at <= '{$end_date}'
                      and  created_at >= '{$start_date}'
                     ");
                $my_list = PettyCashModel::hydrate($list_one);
                if (sizeof($my_list) < 1) {
                    //dd($my_list);
                } else {
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
                if (sizeof($my_summary) < 1) {
                    // dd($my_summary);
                } else {
                    $summary[] = DailyPettyCashTotalsView::hydrate($summary_one);
                }


            }
        } else {
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
            'status' => $status->name,
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

        $directorate = DailyPettyCashTotalsView::select('directorate_id', 'config_status_id',
            DB::raw('sum(total) as total, sum(amount) as amount'))
            ->groupBy('directorate_id', 'config_status_id', 'total', 'amount');
        $dir2 = $directorate->get();
        $dir = $directorate->where('config_status_id', config('constants.petty_cash_status.closed'))->get();

        $unitss = DailyPettyCashTotalsView::select('directorate_id', 'user_unit_code')
            ->groupBy('user_unit_code', 'directorate_id')->get();

        $unit22 = DailyPettyCashTotalsView::select('directorate_id', 'user_unit_code', 'config_status_id',
            DB::raw('sum(total) as total, sum(amount) as amount'))
            ->groupBy('user_unit_code', 'directorate_id', 'config_status_id')->get();

        // dd($unit22);


        foreach ($unitss as $iiii) {
            $units[] = $iiii->user_unit->user_unit_code ?? "hi";
        }

        foreach ($unit22 as $director) {
            $status[] = strtolower($director->status->name ?? "hi");
            $status_total[] = $director->total ?? "hi";
            $status_amount[] = $director->amount ?? "hi";
        }

        //dd($status);


        //data to send to the view
        $params = [
            'directorates_closed' => $dir,
            'units' => $units,
            'status' => $status,
            'status_total' => $status_total,
            'status_amount' => $status_amount,
            'totals_needs_me' => $totals_needs_me,
        ];
        //reports one page
        return view('eforms.petty-cash.reports.index')->with($params);
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
