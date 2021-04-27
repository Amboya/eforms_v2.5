<?php

namespace App\Http\Controllers\EForms\PettyCash;

use App\Http\Controllers\Controller;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\Main\ProfileAssigmentModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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
        session(['eform_id' => config('constants.eforms_id.main_dashboard')]);
        session(['eform_code' => config('constants.eforms_name.main_dashboard')]);

    }


    /**
     * Show the main application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //count new forms
        $new_forms = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.new_application'))
            ->count();
        //count pending forms
        $pending_forms = PettyCashModel::where('config_status_id', '>', config('constants.petty_cash_status.new_application'))
            ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
            ->count();
        //count closed forms
        $closed_forms = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.closed'))
            ->count();
        //count rejected forms
        $rejected_forms = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.rejected'))
            ->count();

        //add to totals
        $totals['new_forms'] = $new_forms;
        $totals['pending_forms'] = $pending_forms;
        $totals['closed_forms'] = $closed_forms;
        $totals['rejected_forms'] = $rejected_forms;

        //count all that needs me
        $totals_needs_me = self::needsMeCount();
        //list all that needs me
        $list = self::needsMeList();
        //pending forms for me before i apply again
        $pending = self::pendingForMe();

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list,
            'totals' => $totals,
            'pending' => $pending,
        ];
        //return view
        return view('eforms.petty-cash.dashboard')->with($params);
    }


    public static function needsMeCount()
    {
        //get the profile associated with petty cash, for this user
        $user = Auth::user();

        // dd($user);
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('user_id', $user->id)->first();

        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->save();

        $pending = 0;

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = PettyCashModel::whereDate('updated_at', \Carbon::today())->count();

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = PettyCashModel::where('config_status_id', '=', config('constants.petty_cash_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.petty_cash_status.funds_disbursement'))
                ->count();
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.new_application'))
                // ->where('code_superior', Auth::user()->position->code )
                ->count();

        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.hod_approved'))->count();

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.hr_approved'))->count();

        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.petty_cash_status.security_approved'))
                ->count();
        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.funds_acknowledgement'))->count();
            //
        } else {
            $list = PettyCashModel::where('config_status_id', 0)->get();
        }
        return $list;
    }


    public static function needsMeList()
    {
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('user_id', $user->id)->first();

        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->save();

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = PettyCashModel::whereDate('updated_at', \Carbon::today())->get();
            dd(1);

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = PettyCashModel::where('config_status_id', '=', config('constants.petty_cash_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.petty_cash_status.funds_disbursement'))
                ->get();
            //   dd(2) ;

        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {

            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.new_application'))
               // ->where('code_superior', Auth::user()->position->code )
                ->get();

//            $status = config('constants.petty_cash_status.new_application');
//            $user_unit_code = Auth::user()->user_unit->code;
//            $list = DB::select("SELECT * FROM eform_petty_cash
//                        WHERE user_unit_code = '{$user_unit_code}'
//                        AND config_status_id = {$status}
//                        ORDER BY config_status_id, updated_at ASC ");
//            $list = PettyCashModel::hydrate($list);


        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.hod_approved'))->get();

            $pay_point_id = Auth::user()->pay_point_id;
//            $status = config('constants.petty_cash_status.hod_approved') ;
//            $list = DB::select("SELECT * FROM eform_petty_cash
//                        WHERE config_status_id = {$status}
//                        ORDER BY config_status_id, updated_at ASC ");
//            $list = PettyCashModel::hydrate($list);

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.hr_approved'))->get();
            //  dd(5) ;
        }
        //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
              $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.chief_accountant'))
              ->orWhere('config_status_id', config('constants.petty_cash_status.security_approved'))
               ->get();
//            $pay_point_id = Auth::user()->pay_point_id;
//            $chief_accountant = config('constants.petty_cash_status.chief_accountant');
//            $security_approved = config('constants.petty_cash_status.security_approved');
//            $list = DB::select("SELECT * FROM eform_petty_cash
//                        WHERE config_status_id = {$chief_accountant}  OR config_status_id = {$security_approved}
//                        AND pay_point_id =  {$pay_point_id}
//                        ORDER BY config_status_id, updated_at ASC ");
//            $list = PettyCashModel::hydrate($list);

        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            // $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.funds_acknowledgement'))->get();
            //   dd(7) ;

            $funds = config('constants.petty_cash_status.funds_acknowledgement');
            $pay_point_id = Auth::user()->pay_point_id;
            $list = DB::select("SELECT * FROM eform_petty_cash
                        WHERE config_status_id = {$funds}
                        AND pay_point_id =  {$pay_point_id}
                        ORDER BY config_status_id, updated_at ASC ");
            $list = PettyCashModel::hydrate($list);
        } else {
            $list = PettyCashModel::where('config_status_id', 0)->get();
            //  dd(8) ;
        }
        return $list;
    }


    public static function pendingForMe()
    {
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('user_id', $user->id)->first();

        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->save();

        $pending = 0;

        //for the REQUESTER
        if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            //count pending applications
            $pending = PettyCashModel::where('config_status_id', '>=', config('constants.petty_cash_status.new_application'))
                ->where('config_status_id', '<', config('constants.petty_cash_status.closed'))
                ->count();
        }

        return $pending;
    }


}
