<?php

namespace App\Http\Controllers\EForms\Trip;

use App\Http\Controllers\Controller;
use App\Models\EForms\Trip\Invitation;
use App\Models\EForms\Trip\Trip;
use App\Models\Main\ConfigWorkFlow;
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
        session(['eform_id' => config('constants.eforms_id.trip')]);
        session(['eform_code' => config('constants.eforms_name.trip')]);
    }


    /**
     * Show the main application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //count new forms
        $new_forms = Trip::where('config_status_id', config('constants.trip_status.new_trip'))
            ->count();
        //count pending forms
        $pending_forms = Trip::where('config_status_id', '>', config('constants.trip_status.new_trip'))
            ->where('config_status_id', '<', config('constants.trip_status.closed'))
            ->count();
        //count closed forms
        $closed_forms = Trip::where('config_status_id', config('constants.trip_status.closed'))
            ->count();
        //count rejected forms
        $rejected_forms = Trip::where('config_status_id', config('constants.trip_status.rejected'))
            ->count();

        //add to totals
        $totals['new_forms'] = $new_forms;
        $totals['pending_forms'] = $pending_forms;
        $totals['closed_forms'] = $closed_forms;
        $totals['rejected_forms'] = $rejected_forms;

        //list all that needs me
        //   $get_profile = self::getMyProfile();

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
        return view('eforms.trip.dashboard')->with($params);
    }


    public static function needsMeCount()
    {
        $user = Auth::user();

        //  dd(config('constants.trip_status.new_trip'));

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = Trip::whereDate('updated_at', \Carbon::today())->count();

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = Trip::
            where('config_status_id', '=', config('constants.trip_status.new_trip'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.funds_disbursement'))
                ->count();
        }

        //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.new_trip'))
                // ->where('code_superior', Auth::user()->position->code )
                ->count();
//            dd($list);

        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.hod_approved'))->count();

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.hr_approved'))->count();

        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.trip_status.security_approved'))
                ->count();
        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.funds_acknowledgement'))->count();
            //
        } //for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.closed'))
                ->count();
        } else {
            $list = Trip::where('config_status_id', 0)->count();
        }
        return $list;
    }


    public static function needsMeList()
    {
        $user = Auth::user();


        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = Trip::whereDate('updated_at', \Carbon::today())
                ->orderBy('code')->paginate(50);
            dd(1);

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {

            dd('shubart');

            $list = Trip::where('config_status_id', '=', config('constants.trip_status.new_trip'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.funds_disbursement'))
                ->orderBy('code')->paginate(50);
            //   dd(2) ;
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {

            $list = Invitation::where('man_no',$user->staff_no )->orderBy('trip_code')->get();


        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.hod_approved'))
                ->orderBy('code')->paginate(50);

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.hr_approved'))
                ->orderBy('code')->paginate(50);
            //  dd(5) ;
        }
        //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.trip_status.security_approved'))
                ->orderBy('code')->paginate(50);

        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.funds_acknowledgement'))
                ->orderBy('code')->paginate(50);
        }//for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $list = Trip::where('config_status_id', config('constants.trip_status.closed'))
                ->orderBy('code')->paginate(50);
        }
        else {
            $list = Trip::where('config_status_id', 0)
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
        if ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            //count pending applications
            $pending = Invitation::where('man_no',$user->staff_no )->count();
        }else{
            dd(323);
            $pending = Invitation::where('man_no',$user->staff_no )->count();
        }

        return $pending;
    }



    public static function getMyProfile(){
        if (auth()->check()) {
            //get the profile associated with petty cash, for this user
            $user = Auth::user();

            //[1]  GET YOUR PROFILE
            $profile_assignement = ProfileAssigmentModel::
            where('eform_id', config('constants.eforms_id.trip'))
                ->where('user_id', $user->id)->first();
            // dd($profile_assignement);

            if ($profile_assignement->exists()) {
                $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
                $user->profile_id = $default_profile;
                $user->profile_unit_code = $user->user_unit_code;
                $user->profile_job_code = $user->job_code;
                $user->code_column = $profile_assignement->profiles->code_column ?? 'id';
                $user->unit_column = $profile_assignement->profiles->unit_column  ?? 'user_unit_code';
                }else{
                $default_profile = config('constants.user_profiles.EZESCO_002');
                $user->profile_id = $default_profile;
                $user->profile_unit_code = $user->user_unit_code;
                $user->profile_job_code = $user->id;
                $user->code_column = $profile_assignement->profiles->code_column ?? 'id';
                $user->unit_column = $profile_assignement->profiles->unit_column  ?? 'user_unit_code';
            }

            //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
            $profile_delegated = ProfileDelegatedModel::
            where('eform_id', config('constants.eforms_id.trip'))
                ->where('delegated_to', $user->id)
                ->where('config_status_id', config('constants.active_state'));
            if ($profile_delegated->exists()) {
                //
                $default_profile = $profile_delegated->first()->delegated_profile ?? config('constants.user_profiles.EZESCO_002');
                $user->profile_id = $default_profile;
                $user->profile_unit_code = $profile_delegated->first()->delegated_user_unit ?? $user->user_unit_code;
                $user->profile_job_code = $profile_delegated->first()->delegated_job_code ?? $user->job_code;
                $user->code_column = $profile_delegated->first()->profile->code_column ?? 'id';
                $user->unit_column = $profile_delegated->first()->profile->unit_column  ?? 'user_unit_code';

            }
            $user->save();

        }
    }


    public static function getMyViisbleUserUnitsProcessed()
    {
        //get the profile associated
        $user = Auth::user();
        $my_user_units = ConfigWorkFlow::where($user->unit_column, $user->profile_unit_code)
            ->where($user->code_column, $user->profile_job_code)
            ->where('user_unit_cc_code', '!=', '0')
            ->orderBy('user_unit_description')
            ->get();
        $myUnits = $my_user_units->pluck('user_unit_code')->unique();
        return $myUnits->toArray();
    }


    public static function getMyViisbleUserUnits()
    {
        //get the profile associated
        $user = Auth::user();
        $my_user_units = ConfigWorkFlow::where($user->unit_column, $user->profile_unit_code)
            ->where($user->code_column, $user->profile_job_code)
            ->where('user_unit_cc_code', '!=', '0')
            ->orderBy('user_unit_description')
            ->get();

        return $my_user_units;
//        $myUnit = $my_user_units ;
//        return $myUnit->toArray();
    }

    public static function getMyViisbleDirectorates()
    {
        //get the profile associated
        $user = Auth::user();
        $my_user_units = ConfigSystemWorkFlow::select('directorate_id', 'directorate_name')
            ->where($user->unit_column, $user->profile_unit_code)
            ->where($user->code_column, $user->profile_job_code)
            ->where('user_unit_cc_code', '!=', '0')
            ->groupBy('directorate_id', 'directorate_name')
            ->get();
        return $my_user_units;
    }



}
