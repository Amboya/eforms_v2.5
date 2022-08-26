<?php

namespace App\Http\Controllers\EForms\Subsistence;

use App\Http\Controllers\Controller;
use App\Models\EForms\Subsistence\SubsistenceModel;
use App\Models\EForms\Trip\Invitation;
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
        session(['eform_id' => config('constants.eforms_id.subsistence')]);
        session(['eform_code' => config('constants.eforms_name.subsistence')]);

    }

    public static function needsMeCount()
    {
        $list = self::needsMeList();
        return $list->count();
    }

    /**
     * Show the main application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        //list all that needs me
      //  $get_profile = \App\Http\Controllers\Main\HomeController::getMyProfile(config('constants.eforms_id.subsistence'));


        //count new forms
        $new_forms = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.new_application'))
            ;
        //count pending forms
        $pending_forms = SubsistenceModel::
        where('config_status_id', '=', config('constants.subsistence_status.hod_approved'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.station_mgr_approved'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.hr_approved'))
            ->orWhere('config_status_id',  '=', config('constants.trip_status.hr_approved'))
            ->orWhere('config_status_id',  '=', config('constants.trip_status.trip_authorised'))
            ->orWhere('config_status_id',  '=', config('constants.trip_status.hr_approved_trip'))
            ->orWhere('config_status_id',  '=', config('constants.trip_status.hod_approved_trip'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.chief_accountant'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.await_audit'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.funds_disbursement'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.funds_acknowledgement'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.destination_approval'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.dr_approved'))
            ->orWhere('config_status_id',  '=', config('constants.exported'))
            ->orWhere('config_status_id',  '=', config('constants.uploaded'))
            ->orWhere('config_status_id',  '=', config('constants.subsistence_status.pre_audited'))
            ;


        //count closed forms
        $closed_forms = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.closed'))
            ;
        //count rejected forms
        $rejected_forms = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.rejected'))
            ->orWhere('config_status_id',  '=', config('constants.export_failed'))
           ;
        //audited
        $audit_approved = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.audit_approved'))
            ;
        //payment processing
        $payment_processing = SubsistenceModel::where('config_status_id', config('constants.exported'))
            ;


        //add to totals
        $totals['new_forms'] = $new_forms;
        $totals['pending_forms'] = $pending_forms;
        $totals['closed_forms'] = $closed_forms;
        $totals['rejected_forms'] = $rejected_forms;
        $totals['audit_approved'] = $audit_approved;
        $totals['exported'] = $payment_processing;


        //list all that needs me
        $list = self::needsMeList();
        //count all that needs me
        $totals_needs_me = $list->count();
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
        return view('eforms.subsistence.dashboard')->with($params);
    }

    public static function needsMeList()
    {
        //get the my list
        // $fdsf = \App\Http\Controllers\Main\HomeController::getMyProfile(config('constants.eforms_id.subsistence'));
        // $my_units = $fdsf->pluck('user_unit_code')->toArray();

        $user = Auth::user();

        $list_inv = Invitation::select('subsistence_id')
            ->where('man_no', $user->staff_no)
            ->where('status_id', config('constants.trip_status.accepted'))
            ->get();
        $list_inv = $list_inv->pluck('subsistence_id')->toArray();


        //for the SYSTEM ADMIN
        if ($user->profile_id == config('constants.user_profiles.EZESCO_001')) {
            $list = SubsistenceModel::whereDate('updated_at', Carbon::today())
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);


        } //for the REQUESTER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            $list = SubsistenceModel::where('config_status_id', '=', config('constants.subsistence_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.funds_disbursement'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.accepted'))
//                ->orWhere('config_status_id', '=', config('constants.trip_status.hod_approved_trip'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);
//               dd(2) ;  //hod_approved_trip
            
        } //for the HOD
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_004')) {

            $list = SubsistenceModel::where('config_status_id', config('constants.trip_status.accepted'))
                ->orWhere('config_status_id', config('constants.subsistence_status.funds_disbursement'))
                ->orWhere('config_status_id', config('constants.subsistence_status.destination_approval'))
                ->orWhere('config_status_id', config('constants.trip_status.trip_authorised'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);


        } //for the HR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_009')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.station_mgr_approved'))
                ->orWhere('config_status_id', '=', config('constants.subsistence_status.dr_approved'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.trip_authorised'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);

        }
        //DIRECTOR
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_003')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.trip_status.accepted'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);

        }

        //for the SNR MANAGER
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_015')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.hod_approved'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.hod_approved_trip'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);
        }
        //for the CHIEF ACCOUNTANT
//        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
//            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.station_mgr_approved'))
//                ->orWhereIn('id', $list_inv)
//                ->orderBy('code')->paginate(50);
//            //  dd(5) ;
//        }

        //for the CHIEF ACCOUNTANT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_007')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.hr_approved'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);
        }
        //for the EXPENDITURE OFFICE
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_014')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.chief_accountant'))
                ->orWhere('config_status_id', config('constants.subsistence_status.queried'))
                ->orWhere('config_status_id', config('constants.uploaded'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);

        } //for the APPROVALS
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_013')) {
            $list = SubsistenceModel::where('config_status_id', config('constants.subsistence_status.funds_acknowledgement'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);
        }//for the AUDIT
        elseif ($user->profile_id == config('constants.user_profiles.EZESCO_011')) {
            //Borrowed authority is very dangerous - snr mgr frank
            $list = SubsistenceModel::
            where('config_status_id', config('constants.subsistence_status.await_audit'))
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);
        } else {
            $list = SubsistenceModel::where('config_status_id', 0)
                ->orWhereIn('id', $list_inv)
                ->orderBy('code')->paginate(50);
            //  dd(8) ;
        }
        return $list;
    }


    public static function pendingForMe()
    {
        $user = Auth::user();
        $pending = 0;
        //
        $list_inv = Invitation::select('subsistence_id')
            ->where('man_no', $user->staff_no)
            ->where('status_id', config('constants.trip_status.pending'))
            ->get();
        $list_inv = $list_inv->pluck('subsistence_id')->toArray();

        //for the REQUESTER
        if ($user->profile_id == config('constants.user_profiles.EZESCO_002')) {
            //count pending applications
            $pending = SubsistenceModel::where('config_status_id', '=', config('constants.subsistence_status.new_application'))
                ->orWhere('config_status_id', '=', config('constants.trip_status.accepted'))
//                ->orWhere('config_status_id', '=', config('constants.trip_status.hod_approved_trip'))
                ->orWhereIn('id', $list_inv)
                ->count();

          // dd($pending);
        }

        return $pending;
    }


}
