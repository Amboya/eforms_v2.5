<?php

namespace App\Http\Controllers\Eforms\KilometerAllowance;

use App\Http\Controllers\Controller;
use App\Models\Main\ProfileDelegatedModel;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EForms\KilometerAllowance\KilometerAllowanceModel;
use App\Models\Main\ProfileAssigmentModel;

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
        session(['eform_id' => config('constants.eforms_id.kilometer_allowance')]);
        session(['eform_code' => config('constants.eforms_name.kilometer_allowance')]);

    }


    /**
     * Show the main application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        //count new forms
        $new_forms = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.new_application'))
            ->count();
        //count pending forms
        $pending_forms = KilometerAllowanceModel::
        where('config_status_id', '!=' , config('constants.kilometer_allowance_status.new_application'))
            ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.audit_approved'))
            ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.rejected'))
            ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.audited'))
            ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.cancelled'))
            ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.void'))
            ->orWhere('config_status_id', '!=' ,  config('constants.kilometer_allowance_status.receipt_approved'))
            ->count();
        //count closed forms
        $closed_forms = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.receipt_approved'))
            ->count();
        //count rejected forms
        $rejected_forms = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.rejected'))
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

        $list_for_auditors_action = 0;
        //for the EXPENDITURE OFFICE
        if (Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014')) {
            /** check if auditor created last months files */
            // $last_month = Carbon::now()->subDays(30)->toDateTimeString();

            $fromDate = Carbon::now()->subMonth()->startOfMonth()->toDateString();
            $tillDate = Carbon::now()->subMonth()->endOfMonth()->toDateString();

            $list_for_auditors_action = KilometerAllowanceModel::
            where('config_status_id', config('constants.kilometer_allowance_status.closed'))
                ->where('created_at', '>=', $fromDate)
                ->orWhere('created_at', '<=', $tillDate)
                ->count();
        }

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $list,
            'totals' => $totals,
            'pending' => $pending,
            'auditor' => $list_for_auditors_action,
        ];
        //return view
        return view('eforms.kilometer-allowance.dashboard')->with($params);
    }

    public static function getMyProfile()
    {
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        //[1]  GET YOUR PROFILE
        $profile_assignement = ProfileAssigmentModel::
        where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('user_id', $user->id)->first();
        //  use my profile - if i dont have one - give me the default
        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->profile_unit_code = $user->user_unit_code;
        $user->profile_job_code = $user->job_code;
        $user->save();

        //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
        $profile_delegated = ProfileDelegatedModel::
        where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('delegated_to', $user->id)
            ->where('config_status_id', config('constants.active_state'));
        if ($profile_delegated->exists()) {
            //
            $default_profile = $profile_delegated->first()->delegated_profile ?? config('constants.user_profiles.EZESCO_002');
            $user->profile_id = $default_profile;
            $user->profile_unit_code = $profile_delegated->first()->delegated_user_unit ?? $user->user_unit_code;
            $user->profile_job_code = $profile_delegated->first()->delegated_job_code ?? $user->job_code;
            $user->save();
        }
    }

    public static function needsMeCount()
    {
        $user = Auth::user();


        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = KilometerAllowanceModel::whereDate('updated_at', \Carbon::today())->count();

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = KilometerAllowanceModel::where('config_status_id', '=', config('constants.kilometer_allowance_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.kilometer_allowance_status.funds_disbursement'))
                ->count();
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.new_application'))
                // ->where('code_superior', Auth::user()->position->code )
                ->count();
        } //for the SENIOR MANAGER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_015')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.hod_approved'))->count();
        }
        //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.manager_approved'))->count();

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.hr_approved'))->count();

        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.audited'))
                ->count();
        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.funds_acknowledgement'))->count();
            //
        } //for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.closed'))
                ->count();
        } else {
            $list = KilometerAllowanceModel::where('config_status_id', 0)->count();
        }
        return $list;
    }

    public static function needsMeList()
    {

        $user = Auth::user();

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = KilometerAllowanceModel::whereDate('updated_at', \Carbon::today())
                ->orderBy('code')->paginate(50);

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = KilometerAllowanceModel::where('config_status_id', '=', config('constants.kilometer_allowance_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.kilometer_allowance_status.funds_disbursement'))
                ->orderBy('code')->paginate(50);
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.new_application'))
                // ->where('code_superior', Auth::user()->position->code )
                ->orderBy('code')->paginate(50);
        } //for the SENIOR MANAGER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_015')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.hod_approved'))
                ->orderBy('code')->paginate(50);
        }//for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.manager_approved'))
                ->orderBy('code')->paginate(50);

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.hr_approved'))
                ->orderBy('code')->paginate(50);

        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.audited'))
                    ->orderBy('code')->paginate(50);

         //   dd(3233);

        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.funds_acknowledgement'))
                ->orderBy('code')->paginate(50);
        }//for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.closed'))
                ->orderBy('code')->paginate(50);
        } else {
            $list = KilometerAllowanceModel::where('config_status_id', 0)
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
            $pending = KilometerAllowanceModel::where('config_status_id', '>=', config('constants.kilometer_allowance_status.new_application'))
                ->where('config_status_id', '<', config('constants.kilometer_allowance_status.closed'))
                ->count();
        }

        return $pending;
    }


}
