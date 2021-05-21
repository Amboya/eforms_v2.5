<?php

namespace App\Http\Controllers\EForms\HotelAccommodation;

use App\Http\Controllers\Controller;
use App\Models\Eforms\HotelAccommodation\HotelAccommodationModel;
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
        $new_forms = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.new_application'))
            ->count();
        //count pending forms
        $pending_forms = HotelAccommodationModel::where('config_status_id', '>', config('constants.hotel_accommodation_status.new_application'))
            ->where('config_status_id', '<', config('constants.hotel_accommodation_status.closed'))
            ->count();
        //count closed forms
        $closed_forms = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.closed'))
            ->count();
        //count rejected forms
        $rejected_forms = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.rejected'))
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
        return view('eforms.hotel-accommodation.dashboard')->with($params);
    }


    public static function needsMeCount()
    {
        $user = Auth::user();

        $pending = 0;

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = HotelAccommodationModel::whereDate('updated_at', \Carbon::today())->count();

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = HotelAccommodationModel::where('config_status_id', '=', config('constants.hotel_accommodation_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.hotel_accommodation_status.funds_disbursement'))
                ->count();
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.new_application'))
                // ->where('code_superior', Auth::user()->position->code )
                ->count();

        } //for the DR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_003')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.hod_approved'))->count();

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.director_approved'))->count();

        } //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.chief_accountant_approved'))
                ->count();
              } //for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.closed'))
                ->count();
        } else {
            $list = HotelAccommodationModel::where('config_status_id', 0)->count();
        }
        return $list;
    }

    public static function needsMeList()
    {
        $user = Auth::user();

        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = HotelAccommodationModel::whereDate('updated_at', \Carbon::today())
                ->orderBy('code')->paginate(50);
            dd(1);
            dd(1);

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = HotelAccommodationModel::where('config_status_id', '=', config('constants.hotel_accommodation_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.hotel_accommodation_status.funds_disbursement'))
                ->orderBy('code')->paginate(50);
            //   dd(2) ;
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.new_application'))
                // ->where('code_superior', Auth::user()->position->code )
                ->orderBy('code')->paginate(50);
        } //for the DR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_003')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.hod_approved'))
                ->orderBy('code')->paginate(50);

        } //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.director_approved'))
                ->orderBy('code')->paginate(50);
            //  dd(5) ;
        }
        //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.chief_accountant_approved'))

                ->orderBy('code')->paginate(50);

        }//for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            $list = HotelAccommodationModel::where('config_status_id', config('constants.hotel_accommodation_status.closed'))
                ->orderBy('code')->paginate(50);
        }
        else {
            $list = HotelAccommodationModel::where('config_status_id', 0)
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
            $pending = HotelAccommodationModel::where('config_status_id', '>=', config('constants.hotel_accommodation_status.new_application'))
                ->where('config_status_id', '<', config('constants.hotel_accommodation_status.closed'))
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
        where('eform_id', config('constants.eforms_id.hotel_accommodation'))
            ->where('user_id', $user->id)->first();
        //  use my profile - if i dont have one - give me the default
        $default_profile = $profile_assignement->profiles->id ?? config('constants.user_profiles.EZESCO_002');
        $user->profile_id = $default_profile;
        $user->profile_unit_code = $user->user_unit_code;
        $user->profile_job_code = $user->job_code;
        $user->save();

        //[2] THEN CHECK IF YOU HAVE A DELEGATED PROFILE - USE IT IF YOU HAVE -ELSE CONTINUE WITH YOURS
        $profile_delegated = ProfileDelegatedModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
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
