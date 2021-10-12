<?php

namespace App\Http\Controllers\EForms\Trip;

use App\Http\Controllers\Controller;
use App\Models\EForms\Trip\Invitation;
use App\Models\EForms\Trip\Trip;
use App\Models\Main\ConfigWorkFlow;
use App\Models\Main\ProfileAssigmentModel;
use App\Models\Main\ProfileDelegatedModel;
use Carbon;
use Illuminate\Contracts\Support\Renderable;
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
        session(['eform_id' => config('constants.eforms_id.trip')]);
        session(['eform_code' => config('constants.eforms_name.trip')]);
    }

    /**
     * Show the main application dashboard.
     *
     * @return Renderable
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
        return view('eforms.trip.dashboard')->with($params);
    }

    public static function needsMeCount()
    {
        $user = Auth::user();
        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = Trip::whereDate('updated_at', Carbon::today())->count();

        }
        //for REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->count();
        }
        //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->orWhere('hod_code', $user->profile_job_code)
                ->where('hod_unit', $user->profile_unit_code)
                ->count();
        } //for the HRM
        elseif (  $user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $fdsf = \App\Http\Controllers\Main\HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
            $my_units = $fdsf->pluck('user_unit_code')->toArray();
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->orWhereIn('hod_unit', $my_units)
                ->count();
        }  //for the SNR MANAGER
        elseif (  $user->profile_id == config('constants.user_profiles.EZESCO_015')) {
            $fdsf = \App\Http\Controllers\Main\HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
            $my_units = $fdsf->pluck('user_unit_code')->toArray();
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->orWhereIn('hod_unit', $my_units)
                ->count();
        } //for ANYONE ELSE
        else {
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->count();
        }

        return $list;
    }

    public static function needsMeList()
    {
        $user = Auth::user();
        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
//            $list_inv = Invitation::where('man_no',$user->staff_no )->orderBy('trip_code')->get();
//            $mine = [] ;
//            foreach ($list_inv as $item){
//                $mine[] = $item->trip_code ;
//            }
//            $list = Trip::whereIn('code',$mine )
//                ->orWhere('hod_code', $user->profile_job_code )
//                ->where('hod_unit', $user->profile_unit_code )
//                ->orderBy('code')->get();

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)->orderBy('code')->get();

        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->orWhere('hod_code', $user->profile_job_code)
                ->where('hod_unit', $user->profile_unit_code)
                ->orderBy('code')->get();
        } //for the HRM
        elseif ( $user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $fdsf = \App\Http\Controllers\Main\HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
            $my_units = $fdsf->pluck('user_unit_code')->toArray();
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->orWhereIn('hod_unit', $my_units)
                ->orderBy('code')->get();
        } //for the SNR MANAGER
        elseif (  $user->profile_id == config('constants.user_profiles.EZESCO_015')) {
            $fdsf = \App\Http\Controllers\Main\HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
            $my_units = $fdsf->pluck('user_unit_code')->toArray();

           // dd($my_units);

            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->orWhereIn('hod_unit', $my_units)
                ->orderBy('code')->get();
        } //for ANYONE ELSE
        else {
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->orderBy('code')->get();
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
            $pending = Invitation::where('man_no', $user->staff_no)->count();
        } else {
            $pending = Invitation::where('man_no', $user->staff_no)->count();
        }

        return $pending;
    }


}
