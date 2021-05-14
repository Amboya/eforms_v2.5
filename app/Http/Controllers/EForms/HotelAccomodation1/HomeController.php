<?php

namespace App\Http\Controllers\Eforms\HotelAccomodation;

use App\Http\Controllers\Controller;
use App\Models\Eforms\HotelAccomodation\HotelAccomodationModel;
use App\Models\Main\ProfileAssigmentModel;
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
        session(['eform_id' => config('constants.eforms_id.hotel_accommodation')]);
        session(['eform_code' => config('constants.eforms_name.hotel_accommodation')]);

    }


    /**
     * Show the main application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        //count new forms
        $new_forms  = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.new_application'))
            ->count();

        //count pending forms
        $pending_forms  = HotelAccomodationModel::where('config_status_id', '>', config('constants.hotel_accommodation_status.new_application'))
         ->where('config_status_id', '<', config('constants.hotel_accommodation_status.closed'))
         ->where('config_status_id', '!=', config('constants.hotel_accommodation_status.rejected'))
          ->count();
//        dd( config('constants.hotel_accommodation_status.rejected') );
        //count closed forms
        $closed_forms  = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.closed'))
            ->count();
        //count rejected forms
        $rejected_forms  = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.rejected'))
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

     //   dd($list);

        //return view
        return view('eforms.hotel-accommodation.dashboard')->with($params);


    }

    public static function needsMeCount(){
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
            ->where('user_id', $user->id)->first();

        $default_profile =  $profile_assignement->profiles->id  ?? config('constants.user_profiles.EZESCO_002') ;
        $user->profile_id = $default_profile ;
        $user->save();

        $pending = 0;

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = HotelAccomodationModel::whereDate('updated_at', \Carbon::today())->count();

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = HotelAccomodationModel::where('config_status_id', '=', config('constants.hotel_accommodation_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.hotel_accommodation_status.funds_disbursement'))
                ->orWhere('config_status_id', '=', config('constants.hotel_accommodation_status.funds_disbursement'))
                ->count();
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.new_application'))->count();

        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.hod_approved'))->count();

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.hr_approved'))->count();

        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.hotel_accommodation_status.security_approved'))
                ->orWhere('config_status_id', config('constants.hotel_accommodation_status.security_approved'))
                ->count();
        } //for the SECURITY
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.funds_acknowledgement'))->count();
            //
        }
        else{
            $list = HotelAccomodationModel::where('config_status_id', 0 )->get();
        }
        return $list;
    }


    public static function needsMeList(){
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
            ->where('user_id', $user->id)->first();

        $default_profile =  $profile_assignement->profiles->id  ?? config('constants.user_profiles.EZESCO_002') ;
        $user->profile_id = $default_profile ;
        $user->save();

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = HotelAccomodationModel::whereDate('updated_at', \Carbon::today())->get();
            dd(1) ;
        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = HotelAccomodationModel::where('config_status_id', '=', config('constants.hotel_accommodation_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.hotel_accommodation_status.funds_disbursement'))
                ->get();
            //   dd(2) ;
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.new_application'))->get();
            //  dd(3) ;
        } //for the director
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_003')) {
            $list = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.chief_accountant_approved'))->get();
            //   dd(4) ;
        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = HotelAccomodationModel::where('config_status_id', config('constants.hotel_accommodation_status.hod_approved'))->get();
            //  dd(5) ;
               }
        else{
            $list = HotelAccomodationModel::where('config_status_id', 0 )->get();
          //  dd(8) ;
        }
        return $list;
    }


    public static function pendingForMe()
    {
        //get the profile associated with petty cash, for this user
        $user = Auth::user();
        $profile_assignement = ProfileAssigmentModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
            ->where('user_id', $user->id)->first();

        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->save();

        $pending = 0;

        //for the REQUESTER
        if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            //count pending applications
            $pending = HotelAccomodationModel::where('config_status_id', '>=', config('constants.hotel_accommodation_status.new_application'))
                ->where('config_status_id', '<', config('constants.hotel_accommodation_status.closed'))
                ->where('config_status_id', '<', config('constants.hotel_accommodation_status.closed'))
                ->where('config_status_id', '!=', config('constants.hotel_accommodation_status.rejected'))
                ->where('config_status_id', '!=', config('constants.hotel_accommodation_status.rejected'))
                ->count();
        }

        return $pending ;
    }

    //

}
