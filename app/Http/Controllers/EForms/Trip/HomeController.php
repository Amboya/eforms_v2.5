<?php

namespace App\Http\Controllers\EForms\Trip;

use App\Http\Controllers\Controller;
use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\EForms\Trip\Destinations;
use App\Models\EForms\Trip\Invitation;
use App\Models\EForms\Trip\Trip;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Artisan;
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

        //clear all expired trips
        //get all trips that have expired as of today
        $allTrips = Trip::where('date_to', '<', Carbon::now())
            ->where('config_status_id', config('constants.trip_status.new_trip') )
            ->get() ;

        foreach ($allTrips as $trip){
            $trip->config_status_id = config('constants.trip_status.trip_closed') ;
            $trip->save();
           // echo "  ".$trip->name . " Expired and Closed.   :   " ;
        }
        //

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
        $list = self::needsMeList();
        //count all that needs me
        $totals_needs_me = $list->count();
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

    public static function needsMeCount(){
        $list = self::needsMeList();
        return $list->count() ;
    }



    public static function needsMeList()
    {
        $user = Auth::user();
        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {

        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list_inv = Invitation::where('man_no', $user->staff_no)
                ->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }
            $list = Trip::whereIn('code', $mine)
                ->orderBy('code')->get();

        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {
            $list_inv = Invitation::where('man_no', $user->staff_no)->orderBy('trip_code')->get();
            $mine = [];
            foreach ($list_inv as $item) {
                $mine[] = $item->trip_code;
            }

            //get the my list
            $fdsf = \App\Http\Controllers\Main\HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
            $my_units = $fdsf->pluck('user_unit_code')->toArray();

            //get the destination approvals
            $dest = Destinations::whereIn('user_unit_code',$my_units )->get();
            foreach ($dest as $des) {
                $mine[] = $des->trip_code;
            }

            //get the trips
            $list = Trip::whereIn('code', array_unique($mine))
                ->orWhere('hod_code', $user->profile_job_code)
                ->where('hod_unit', $user->profile_unit_code)
                ->get();


        } //for the HRM
        elseif (($user->profile_id == config('constants.user_profiles.EZESCO_009'))
            || ($user->profile_id == config('constants.user_profiles.EZESCO_015'))
            || ($user->profile_id == config('constants.user_profiles.EZESCO_011'))
            || ($user->profile_id == config('constants.user_profiles.EZESCO_014'))
            || ($user->profile_id == config('constants.user_profiles.EZESCO_007'))
        ) {
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

     //  $new_list =  $list->where('config_status_id' , '!=', config('constants.trip_status.trip_closed') ) ;
        $list =  $list->where('config_status_id' , '!=', config('constants.trip_status.trip_closed') ) ;

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
