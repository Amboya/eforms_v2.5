<?php

namespace App\Http\Controllers\EForms\PettyCash;

use App\Http\Controllers\Controller;
use App\Models\EForms\PettyCash\Integration\ZescoItsInvInterfaceDetail;
use App\Models\EForms\PettyCash\Integration\ZescoItsInvInterfaceHeader;
use App\Models\EForms\PettyCash\PettyCashAccountModel;
use App\Models\EForms\PettyCash\PettyCashFloat;
use App\Models\EForms\PettyCash\PettyCashModel;
use App\Models\EForms\PettyCash\PettyCashReimbursement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class Integration extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //GET THE PETTY CASH MODEL
//        $status = config('constants.petty_cash_status.not_exported');
//        $list = DB::select("SELECT * FROM eform_petty_cash_account where status_id = {$status} ");
//        $form = PettyCashAccountModel::hydrate($list);
//        $form->load(
//            'form',
//            'form.item',
//            'form.user',
//            'status');
      //  $form = ZescoItsInvInterfaceDetail::all();

//       dd($form);

//        foreach ($form as $item) {
//            $list = DB::select("SELECT * FROM eform_petty_cash_account where petty_cash_code = '{$item->invoice_id}' ");
//            $form_1 = PettyCashAccountModel::hydrate($list)->first();
//            $form_1->load(
//                'form',
//                'form.item',
//                'form.user',
//                'status');
//
////            $list22 = DB::table('fms_invoice_interface_header')
////                ->where('invoice_id', $form_1->petty_cash_code)
////                ->update(['supplier_num' => $form_1->form->claimant_staff_no ]);
//
//
//
//
//            //one
//            $list22 = DB::table('fms_invoice_interface_detail')
//                ->where('invoice_id', $form_1->petty_cash_code)
//                ->where('gl_account', $form_1->creditted_account_id)
//                ->update([
////                    'company_code' =>  config('constants.company'),
//                    'amount' =>  "-". ($form_1->creditted_amount ??  $form_1->debitted_amount)
//                ]);
//
//            //two
//            $list22 = DB::table('fms_invoice_interface_detail')
//                ->where('invoice_id', $form_1->petty_cash_code)
//                ->where('gl_account', $form_1->debitted_account_id)
//                ->update([
////                    'company_code' =>  config('constants.company'),
//                    'amount' =>   $form_1->debitted_amount ?? $form_1->creditted_amount
//                ]);
//
////            dd( "-".$form_1->creditted_amount ??  $form_1->debitted_amount);
//
//        }


    //    dd($form);


        $list = ZescoItsInvInterfaceHeader::where('transaction_type', config('constants.transaction_type.petty_cash'))->get();
        $list->load('code');
        $category = 'Zesco Its Inv Interface Header';
        $value = config('constants.petty_cash_status.processed');

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.petty-cash.integration.list')->with(compact('list', 'category', 'value', 'totals_needs_me'));
    }


    public function send()
    {
        $Detail = ZescoItsInvInterfaceDetail::all();
        $Header = ZescoItsInvInterfaceHeader::all();
        dd($Detail->first());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $model = PettyCashFloat::find($request->user_unit_field);
        $model->float = $request->new_float;
        //IDEAL - CASH HAS TO INCREASE
        $model->cash = ($request->new_float) - ($model->utilised ?? 0);
        $model->save();
        return Redirect::back()->with('message', 'Submitted Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }


    public function reimbursementShow($id)
    {
        //get the details
        $model = PettyCashFloat::find($id);
        $myUnits = PettyCashModel:: Where('user_unit_code', $model->user_unit_code);

        $total_payment = $myUnits->sum('total_payment');
        $total_change = $myUnits->sum('change');
        $total_amount = $total_payment - $total_change;

        //add to totals
        $totals['total_units'] = sizeof($myUnits->get());
        $totals['total_float'] = $model->float;
        $totals['total_utilised'] = $model->utilised;
        $totals['total_cash'] = $model->cash;

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();

        //data to send to the view
        $params = [
            'totals_needs_me' => $totals_needs_me,
            'list' => $myUnits->get(),
            'totals' => $totals,
            'model' => $model,
            'total_amount' => $total_amount
        ];

        //return view
        return view('eforms.petty-cash.float-management.list')->with($params);
    }

    public function reimbursementStore(Request $request)
    {

        $float_model = PettyCashFloat::find($request->user_unit_field);
        $percentage = ($float_model->cash * 100) / $float_model->float;

        dd($request->date_from);
        $user = Auth::user();
        $reimbursement_float = PettyCashReimbursement::updateOrCreate(
            [
                'user_unit_id' => $float_model->user_unit_id,
                'user_unit_code' => $float_model->user_unit_code,
                'from' => $request->date_from,
                'to' => $request->date_to,
                'amount' => $request->float_given,
                'reason' => $request->reason,
                'name' => $user->name,
                'title' => $user->job_code,
                'profile' => $user->profile_id,
                'created_by' => $user->id,
                'cash_percentage' => $percentage,
            ],
            [
                'user_unit_id' => $float_model->user_unit_id,
                'user_unit_code' => $float_model->user_unit_code,
                'from' => $request->date_from,
                'to' => $request->date_to,
                'amount' => $request->float_given,
                'reason' => $request->reason,
                'name' => $user->name,
                'title' => $user->job_code,
                'profile' => $user->profile_id,
                'created_by' => $user->id,
                'cash_percentage' => $percentage,
            ]
        );

        //IDEAL - CASH HAS TO INCREASE  AND UTILISED GOES DOWN
        $float_model->cash = ($float_model->cash) + ($request->float_given ?? 0);
        $float_model->utilised = ($float_model->utilised) - ($request->float_given ?? 0);
        $float_model->save();

        //FINALLY MOVE THE FORMS TO A NEW STATUS
        PettyCashModel::where('user_unit_code', $float_model->user_unit_code)
            ->update(
                [
//                    'config_status_id' => 1
                    'region' => 1
                ]
            );

        return Redirect::back()->with('message', 'Submitted Successfully');
    }


}
