<?php

namespace App\Http\Controllers\Eforms\KilometerAllowance;

use App\Http\Controllers\Controller;
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //count new forms
        $new_forms  = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.new_application'))
            ->count();
        //count pending forms
        $pending_forms  = KilometerAllowanceModel::where('config_status_id', '>', config('constants.kilometer_allowance_status.new_application'))
            ->where('config_status_id', '<', config('constants.kilometer_allowance_status.closed'))
            ->where('config_status_id', '!=', config('constants.kilometer_allowance_status.rejected'))
            ->count();
        //count closed forms
        $closed_forms  = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.closed'))
            ->count();
        //count rejected forms
        $rejected_forms  = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.rejected'))
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
        return view('eforms.kilometer-allowance.dashboard')->with($params);


    }


    public static function needsMeCount(){
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('user_id', $user->id)->first();

        $default_profile =  $profile_assignement->profiles->id  ?? config('constants.user_profiles.EZESCO_002') ;
        $user->profile_id = $default_profile ;
        $user->save();

        $pending = 0;

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
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.new_application'))->count();

        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.hod_approved'))->count();

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.hr_approved'))->count();

        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.kilometer_allowance_status.security_approved'))
                ->count();
        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.funds_acknowledgement'))->count();
            //
        }
        else{
            $list = KilometerAllowanceModel::where('config_status_id', 0 )->get();
        }
        return $list;
    }


    public static function needsMeList(){
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('user_id', $user->id)->first();

        $default_profile =  $profile_assignement->profiles->id  ?? config('constants.user_profiles.EZESCO_002') ;
        $user->profile_id = $default_profile ;
        $user->save();

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = KilometerAllowanceModel::whereDate('updated_at', \Carbon::today())->get();
            dd(1) ;
        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = KilometerAllowanceModel::where('config_status_id', '=', config('constants.kilometer_allowance_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.kilometer_allowance_status.funds_disbursement'))
                ->get();
            //   dd(2) ;
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.new_application'))->get();
            //  dd(3) ;
        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.hod_approved'))->get();
            //   dd(4) ;
        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.hr_approved'))->get();
            //  dd(5) ;
        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.kilometer_allowance_status.security_approved'))
                ->get();
            //    dd(6) ;
        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = KilometerAllowanceModel::where('config_status_id', config('constants.kilometer_allowance_status.funds_acknowledgement'))->get();
            //   dd(7) ;
        }
        else{
            $list = KilometerAllowanceModel::where('config_status_id', 0 )->get();
            dd(8) ;
        }
        return $list;
    }


    public static function pendingForMe()
    {
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.kilometer_allowance'))
            ->where('user_id', $user->id)->first();

        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->save();

        $pending = 0;

        //for the REQUESTER
        if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            //count pending applications
            $pending = KilometerAllowanceModel::where('config_status_id', '>=', config('constants.kilometer_allowance_status.new_application'))
                ->where('config_status_id', '<', config('constants.kilometer_allowance_status.closed'))
                ->where('config_status_id', '!=', config('constants.kilometer_allowance_status.rejected'))
                ->count();
        }

        return $pending ;
    }

    //
}
