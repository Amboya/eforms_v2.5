<?php

namespace App\Http\Controllers\EForms\PettyCash;

use App\Http\Controllers\Controller;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use App\Models\User;
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

        //list all that needs me
        $get_profile = self::getMyProfile();

        //count all that needs me
        $totals_needs_me = self::needsMeCount();
        //list all that needs me
        $list = self::needsMeList();
        //pending forms for me before i apply again
        $pending = self::pendingForMe();

       // dd($list);

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
        $user = Auth::user();

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
        } //for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.closed'))
                ->count();
        } else {
            $list = PettyCashModel::where('config_status_id', 0)->count();
        }
        return $list;
    }


    public static function needsMeList()
    {
        $user = Auth::user();

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = PettyCashModel::whereDate('updated_at', \Carbon::today())
                ->orderBy('code')->paginate(50);
            dd(1);

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = PettyCashModel::where('config_status_id', '=', config('constants.petty_cash_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.petty_cash_status.funds_disbursement'))
                ->orderBy('code')->paginate(50);
            //   dd(2) ;
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.new_application'))
                // ->where('code_superior', Auth::user()->position->code )
                ->orderBy('code')->paginate(50);
        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.hod_approved'))
                ->orderBy('code')->paginate(50);

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.hr_approved'))
                ->orderBy('code')->paginate(50);
            //  dd(5) ;
        }
        //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.petty_cash_status.security_approved'))
                ->orderBy('code')->paginate(50);

        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.funds_acknowledgement'))
                ->orderBy('code')->paginate(50);
        }//for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $list = PettyCashModel::where('config_status_id', config('constants.petty_cash_status.closed'))
                ->orderBy('code')->paginate(50);
        }
        else {
            $list = PettyCashModel::where('config_status_id', 0)
                ->orderBy('code')->paginate(50);
            //  dd(8) ;
        }
        return $list;
    }


    public static function pendingForMe()
    {
        $user = Auth::user();
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



    public static function getMyProfile()
    {
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        //[1]  GET YOUR PROFILE
        $profile_assignement = ProfileAssigmentModel::
        where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('user_id', $user->id)->first();
        //  use my profile - if i dont have one - give me the default
        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->profile_unit_code = $user->user_unit_code;
        $user->profile_job_code = $user->job_code;
        $user->save();

        //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
        $profile_delegated = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.petty_cash'))
            ->where('delegated_to', $user->id)
            ->where('config_status_id',  config('constants.active_state') );
        if ($profile_delegated->exists()) {
            //
            $default_profile = $profile_delegated->first()->delegated_profile ?? config('constants.user_profiles.EZESCO_002');
            $user->profile_id = $default_profile;
            $user->profile_unit_code = $profile_delegated->first()->delegated_user_unit ?? $user->user_unit_code;
            $user->profile_job_code = $profile_delegated->first()->delegated_job_code ?? $user->job_code;
            $user->save();
        }
    }


}
