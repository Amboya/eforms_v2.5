<?php

namespace App\Http\Controllers\Eforms\HotelAccomodation;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Eforms\HotelAccomodation\HomeController;
use App\Http\Controllers\Main\ActivityLogsController;
use App\Models\Eforms\HotelAccomodation\HotelAccomodationModel;
use App\Models\EForms\PettyCash\PettyCashAccountModel;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\Main\AccountsChartModel;
use App\Models\Main\AttachedFileModel;
use App\Models\Main\EformApprovalsModel;
use App\Models\Main\EFormModel;
use App\Models\Main\ProjectsModel;
use App\Models\Main\StatusModel;
use App\Models\main\TotalsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HotelAccomodationController extends Controller
{
    public function create()
    {
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
        ];

        //show the create form
        return view('eforms.hotel-accommodation.create')->with($params);
    }

    public function store(Request $request)
    {

//       dd($request->all());

        $user = Auth::user();
        //generate random
        $code = self::randGenerator("HTA");

//        dd($code);

        $hotel_accomodation= HotelAccomodationModel::firstOrCreate(
            [
                'grade'  => $request->grade,
                'directorate'=> $request->directorate,
                'hotel'=> $request->hotel,
                'ref_number'=> $request->ref_no,
                'purpose_of_journey'=> $request->purpose_of_journey,
//        'date_from',
//        'date_to',
                'estimated_period_of_stay'=> $request->estimated_period_of_stay,
                'estimated_cost'=> $request->estimated_cost,
                'amount_claimed'=> $request->amount_claimed,
                'amount'=> $request->amount,
                'staff_name' => $request->staff_name,
                'staff_no' => $request->staff_no,
            ],



            [
                'grade'  => $request->grade,
                'directorate'=> $request->directorate,
                'hotel'=> $request->hotel,
                'ref_number'=> $request->ref_no,
                'purpose_of_journey'=> $request->purpose_of_journey,
                'code' => $code,
                'estimated_period_of_stay'=> $request->estimated_period_of_stay,
                'estimated_cost'=> $request->estimated_cost,
                'amount_claimed'=> $request->amount_claimed,
                'amount'=> $request->amount,
                'staff_name' => $request->staff_name,
                'staff_no' => $request->staff_no,
                'claim_date' => $request->claim_date,
                'config_status_id' => config('constants.hotel_accommodation_status.new_application'),
                'profile' => Auth::user()->profile_id,
                'user_unit' => $user->user_unit->code,
                'cost_centre' => $user->user_unit->cost_center_code,
                'business_code' => $user->user_unit->business_unit_code,
                'created_by' => $user->id,

            ]);

        return Redirect::route('hotel-accommodation-home')->with('message', config('constants.eforms_name.hotel_accommodation').' Details for ' . $hotel_accomodation->code . ' have been Created successfully');
    }


    public function randGenerator($head)
    {        //random number
        $random = rand(1, 9999999);
        $random = sprintf("%07d", $random);
        $random = $head . $random;
        $check = HotelAccomodationModel::where('code', $random)->get();

        if ($check->isEmpty()) {            return $random;
        } else {
            self::randGenerator($head);
        }
    }

    public function show($id)
    {
        //GET THE HOTEL MODEL if you are an admin
        if (Auth::user()->type_id == config('constants.user_types.developer')) {
            $list = DB::select("SELECT * FROM eform_hotel_accomodation where id = {$id} ");
            $form = HotelAccomodationModel::hydrate($list)->first();
        } else {
            //find the hotel form with that id
            $form = HotelAccomodationModel::find($id);
        }

//        $projects = HotelAccommodationModel::all();
//        $accounts = AccountsChartModel::all();
//        $approvals = EformApprovalsModel::where('eform_id', $form->id)->where('config_eform_id', config('constants.eforms_id.petty_cash'))->get();

//        //get the list of users who are supposed to work on the form
//        $user_array = self::nextUsers($form);

        //count all that needs me
        $totals_needs_me = \App\Http\Controllers\EForms\HotelAccomodation\HomeController::needsMeCount();
        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'form' => $form,
//            'projects' => $projects,
//            'user_array' => $user_array,
//            'approvals' => $approvals,
//            'accounts' => $accounts
        ];
        //return view
        return view('eforms.hotel-accommodation.show')->with($params);

    }


    public function approve(Request  $request, $id)
    {



        //GET THE KILOMETER ALLOWANCE MODEL
        $form = HotelAccomodationModel::find($id);
        $current_status = $form->status->id;
        $new_status = 0;
        $user = Auth::user();

        $eform_model = EFormModel::find(config('constants.eforms_id.hotel_accommodation'));

        //HANDLE REJECTION
        if ($request->approval == config('constants.approval.reject')) {
            $new_status = config('constants.hotel_accommodation.rejected');

            //update the totals rejected
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
                ->where('id', config('constants.totals.hotel_accommodation_reject'))
                ->first();
            $totals->value = $totals->value + 1;
            $totals->save();
            $eform_model->total_rejected = $totals->value;
            $eform_model->save();

            //update the totals open
            $totals = TotalsModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
                ->where('id', config('constants.totals.hotel_accommodation_open'))
                ->first();
            if ($totals->value > 0) {
                $totals->value = $totals->value - 1;
            }
            $totals->save();
            $eform_model->total_pending = $totals->value;
            $eform_model->save();

            //get status id
            $status_model = StatusModel::where('id', $new_status)
                ->where('eform_id', config('constants.eforms_id.hotel_accommodation'))->first();
            $new_status = $status_model->id;
        }

        //HANDLE APPROVAL
        if ($request->approval == config('constants.approval.approve')) {
            $new_status = $form->status->status_next;

            if ($form->status->id == config('constants.hotel_accommodation_status.audit_approved')) {

                //update the totals closed
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
                    ->where('id', config('constants.totals.hotel_accommodation_closed'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_model->total_closed = $totals->value;
                $eform_model->save();

                //update the totals open
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
                    ->where('id', config('constants.totals.hotel_accommodation_open'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_model->total_pending = $totals->value;
                $eform_model->save();

            }
            else if ($form->status->id == config('constants.hotel_accommodation_status.new_application')) {
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
                    ->where('id', config('constants.totals.hotel_accommodation_open'))
                    ->first();
                $totals->value = $totals->value + 1;
                $totals->save();
                $eform_model->total_pending = $totals->value;
                $eform_model->save();

                //update the totals new
                $totals = TotalsModel::where('eform_id', config('constants.eforms_id.hotel_accommodation'))
                    ->where('id', config('constants.totals.hotel_accommodation_new'))
                    ->first();
                $totals->value = $totals->value - 1;
                $totals->save();
                $eform_model->total_new = $totals->value;
                $eform_model->save();
            }

            //get status id
            $status_model = StatusModel::where('status', $new_status)
                ->where('eform_id', config('constants.eforms_id.hotel_accommodation'))->first();
            $new_status = $status_model->id;

        }


        //write a update query to change the
        //insert authorizer's name
        // insert authorizer's man no
        // insert a timestamp
        // status to the new status

        //FOR HOD
        if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_004')
        &&  $form->config_status_id == config('constants.hotel_accommodation_status.new_application')   ){
            //update query
            $form->config_status_id = $new_status;
            $form->hod_name = $user->name;
            $form->hod_staff_no = $user->staff_no;
            $form->hod_authorised_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }
        //FOR CA
        if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_007')
            &&  $form->config_status_id == config('constants.hotel_accommodation_status.hod_approved')   ){
            //update query
            $form->config_status_id = $new_status;
            $form->chief_accountant_name = $user->name;
            $form->chief_accountant_staff_no = $user->staff_no;
            $form->chief_accountant_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }


        //FOR DIRECTOR
        if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_003')
            &&  $form->config_status_id == config('constants.hotel_accommodation_status.chief_accountant_approved')   ){
            //update query
            $form->config_status_id = $new_status;
            $form->director_name = $user->name;
            $form->director_staff_no = $user->staff_no;
            $form->director_authorised_date = $request->sig_date;
            $form->profile = Auth::user()->profile_id;
            $form->save();
        }

        //save reason
        $reason = EformApprovalsModel::Create(
            [
                'profile' => $user->profile_id,
                'title' => $user->profile_id,
                'staff_no' => $user->staff_no,
                'name' => $user->name,
                'reason' => $request->reason,
                'action' => $request->approval,
                'current_status_id' => $current_status,
                'action_status_id' => $new_status,
                'config_eform_id' => config('constants.eforms_id.hotel_accommodation'),
                'eform_id' => $form->id,
                'created_by' => $user->id,
            ]);


        //send the email
        //  self::nextUsersSendMail($user->profile_id, $new_status,  $form->claimant_staff_no, $form->code) ;

        return Redirect::route('hotel-accommodation-home')->with('message', config('constants.eforms_name.hotel_accommodation').' ' . $form->code . ' for has been ' . $request->approval . ' successfully');


    }



}
