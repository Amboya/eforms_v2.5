<?php

namespace App\Http\Controllers\EForms\Subsistence1;

use App\Http\Controllers\Controller;
//use App\Models\EForms\PettyCash\SubsistenceModel;
use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\Main\ProfileAssigmentModel;
//use App\Models\Subsistence\SubsistenceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        session(['eform_id' => config('constants.eforms_id.subsistence')]);
        session(['eform_code' => config('constants.eforms_name.subsistence')]);

    }


    /**
     * Show the main application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //count new forms
        $new_forms  = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.new_application'))
            ->count();
        //count pending forms
        $pending_forms  = SubsistenceModel::where('config_status_id', '>', config('constants.subsistence_status.new_application'))
            ->where('config_status_id', '<', config('constants.subsistence_status.closed'))
            ->count();
        //count closed forms
        $closed_forms  = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.closed'))
            ->count();
        //count rejected forms
        $rejected_forms  = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.rejected'))
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

        //  "id" => "2"
        //        "cost_center" => "14450"
        //        "business_unit_code" => "15110"
        //        "user_unit_code" => "C1904"
        //        "pay_point_id" => "7"
        //        "location_id" => "133"
        //        "division_id" => "23"
        //        "region_id" => null
        //        "directorate_id" => "722"
        //        "config_status_id" => "21"
        //        "profile" => "1"
        //        "code_superior" => "MGRNET&O"
        //        "grade" => "M7"
        //        "ext_no" => "1142"
        //        "code" => "1"
        //        "ref_no" => "INFORMATION AND CYBER SECURITY SYSTEMS"
        //        "claim_date" => "2021-03-06 16:39:35"
        //        "claimant_name" => "GILBERT  SIBAJENE"
        //        "claimant_staff_no" => "20309"
        //        "station" => null
        //        "section" => "INFORMATION & CYBER SECURITY SYSTEM SUPORT SERVICE"
        //        "absc_absent_from" => "2021-03-28 00:00:00"
        //        "absc_absent_to" => "2021-03-14 00:00:00"
        //        "absc_visited_place_reason" => "Lusaka"
        //        "absc_allowance_per_night" => "650"
        //        "absc_amount" => null
        //        "trex_total_attached_claim" => null
        //        "trex_total_claim_amount" => null
        //        "trex_deduct_advance" => null
        //        "trex_net_amount_paid" => null
        //        "allocation_code" => null
        //        "total_amount" => null
        //        "authorised_by" => null
        //        "authorised_staff_no" => null
        //        "authorised_date" => null
        //        "station_manager" => null
        //        "station_manager_staff_no" => null
        //        "station_manager_date" => null
        //        "chief_accountant" => null
        //        "chief_accountant_staff_no" => null
        //        "chief_accountant_date" => null
        //        "hr_office" => null
        //        "hr_office_staff_no" => null
        //        "hr_date" => null
        //        "audit_name" => null
        //        "audit_staff_no" => null
        //        "audit_date" => null
        //        "created_by" => "116"
        //        "created_at" => "2021-03-06 16:39:35"
        //        "updated_at" => "2021-03-06 16:39:35"
        //        "deleted_at" => null
        //        "absc_visited_place" => null

        return view('eforms.subsistence.dashboard')->with($params);

    }


    public static function needsMeCount(){
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('user_id', $user->id)->first();

        $default_profile =  $profile_assignement->profiles->id  ?? config('constants.user_profiles.EZESCO_002') ;
        $user->profile_id = $default_profile ;
        $user->save();

        $pending = 0;

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = SubsistenceModel::whereDate('updated_at', \Carbon::today())->count();

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = SubsistenceModel::where('config_status_id', '=', config('constants.subsistence_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.funds_disbursement'))
                ->count();
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.new_application'))->count();

        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.hod_approved'))->count();

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.hr_approved'))->count();

        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.subsistence_status.security_approved'))
                ->count();
        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.funds_acknowledgement'))->count();
            //
        }
        else{
            $list = SubsistenceModel::where('config_status_id', 0 )->get();
        }
        return $list;
    }


    public static function needsMeList(){
        //get the profile associated with subsistence, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('user_id', $user->id)->first();

        $default_profile =  $profile_assignement->profiles->id  ?? config('constants.user_profiles.EZESCO_002') ;
        $user->profile_id = $default_profile ;
        $user->save();

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = SubsistenceModel::whereDate('updated_at', \Carbon::today())->get();
            //  dd(1) ;
        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = SubsistenceModel::where('config_status_id', '=', config('constants.subsistence_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.funds_disbursement'))
                ->get();
            //  dd(2) ;
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.new_application'))->get();
            //  dd(3) ;
        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.hod_approved'))->get();
            //   dd(4) ;
        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.hr_approved'))->get();
            //  dd(5) ;
        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.subsistence_status.security_approved'))
                ->get();
            //  dd(6) ;
        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.funds_acknowledgement'))->get();
            //   dd(7) ;
        }
        else{
            $list = SubsistenceModel::where('config_status_id', 0 )->get();
           // dd(8) ;
        }
        return $list;
    }


    public static function pendingForMe()
    {
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.subsistence'))
            ->where('user_id', $user->id)->first();

        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->save();

        $pending = 0;

        //for the REQUESTER
        if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            //count pending applications
            $pending = SubsistenceModel::where('config_status_id', '>=', config('constants.subsistence_status.new_application'))
                ->where('config_status_id', '<', config('constants.subsistence_status.closed'))
                ->count();
        }

        return $pending ;
    }

}
