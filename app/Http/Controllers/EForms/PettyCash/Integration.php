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
        $ready = config('constants.uploaded') ;
        //GET THE PETTY CASH MODEL if you are an admin
        $accounts = DB::select("SELECT * FROM eform_petty_cash_account where status_id = '{$ready}' ORDER BY  petty_cash_code  ");
        $list = PettyCashAccountModel::hydrate($accounts);
        $list->load("form.user_unit");



        $category = 'Petty Cash Details Ready to be Uploaded';
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.petty-cash.integration.ready')->with(compact('category', 'list', 'totals_needs_me'));
 }


    public function ready()
    {
//        $ready = config('constants.export_not_ready');
        $ready = config('constants.not_exported');
        $type = config('constants.account_type.expense');
        //GET THE PETTY CASH MODEL if you are an admin
        $accounts = DB::select("SELECT * FROM eform_petty_cash_account where status_id = '{$ready}'   AND  account_type = '{$type}'   ORDER BY  petty_cash_code  ");
        $list = PettyCashAccountModel::hydrate($accounts);
//        $list->load("form.user_unit");
//        dd($list);
        $category = 'Petty Cash Details Ready to be Uploaded';
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.petty-cash.integration.ready')->with(compact('category', 'list', 'totals_needs_me'));

    }


    public function send(Request $request)
    {

        if($request->forms == null ){
            return back()->with('error', 'No invoices were selected');
        }

        //get the requested accounts
        $codes_array = array_unique($request->forms);
        $type = config('constants.account_type.expense');

        //dd($codes_array);
        $codes_array = implode(',', $codes_array);
        $accounts = DB::select("SELECT * FROM eform_petty_cash_account WHERE petty_cash_code IN ({$codes_array})  AND  account_type = '{$type}'   ORDER  BY  petty_cash_code  ");
        $list = PettyCashAccountModel::hydrate($accounts);
        $list->load("form.user_unit");

        //
        $rec = 0;



        //loop through the accounts
        foreach ($list as $account) {
            $org = $account->org_id ?? $account->form->user_unit->org_id;
            //check for header
            $header = DB::select("SELECT count(invoice_num) as total FROM fms_invoice_interface_header
                  WHERE invoice_num = '{$account->form->code}' AND org_id = {$org}   ");
            $header_list = ZescoItsInvInterfaceHeader::hydrate($header)->first();

            //
            if($header_list->total  == 0){
                // [1] insert into header
                DB::table('fms_invoice_interface_header')->insert(
                //insert column
                    [
                        'invoice_id' => $account->form->code ,
                        'transaction_type' => config('constants.transaction_type.petty_cash'),
                        'invoice_num' => $account->form->code,
                        'invoice_date' => $account->form->created_at,
                        'invoice_description' => $account->description,
                        'invoice_type' =>  config('constants.transaction_type.invoice_type'),
                        'supplier_num' => $account->form->claimant_staff_no,
                        'invoice_amount' => $account->form->total_payment,
                        'invoice_currency_code' =>config('constants.currency'),
                        'exchange_rate'  =>config('constants.exchange_rate'),
                        'gl_date' => $account->created_at,
                        'org_id' => $org,
                        'creation_date' => $account->form->claim_date,
                        'process_yn' => config('constants.unprocessed'),

                    ]
                );

              //  mark as as updated
                $affected_form = DB::table('eform_petty_cash')
                    ->where('id', $account->form->id)
                    ->update(['config_status_id'  =>config('constants.uploaded') ]);
            }


            //check for detail
            $detail = DB::select("SELECT count(invoice_id) as total FROM fms_invoice_interface_detail
                  WHERE invoice_id = '{$account->form->code}' AND org_id = {$org}
                    AND item_description = '{$account->description}' AND  gl_account = '{$account->account}' ");
            $detail_list = ZescoItsInvInterfaceDetail::hydrate($detail)->first();


            if($detail_list->total  == 0) {

                ++$rec ;
                //count line number
                $detail_count = DB::select("SELECT count(invoice_id) as total FROM fms_invoice_interface_detail
                  WHERE invoice_id = '{$account->form->code}'  ");

                // [2] insert into detail
                DB::table('fms_invoice_interface_detail')->insert(
                //insert column
                    [
                        'invoice_id' => $account->form->code,
                        'line_number' => $detail_count[0]->total + 1,
                        'amount' => $account->debitted_amount ?? ( "".$account->creditted_amount),
                        'item_description' => $account->description,
                        'org_id' => $org ,
                        'company_code' => $account->company,
                        'business_unit' => $account->business_unit_code,
                        'cost_centre' => $account->cost_center,
                        'gl_account' => $account->account,
                        'vat_rate' => $account->vat_rate ?? 0,
                        'line_type' => $account->line_type,
                        'creation_date' => $account->created_at
                    ]
                );

                //mark as as updated
                $affected_account = DB::table('eform_petty_cash_account')
                    ->where('id', $account->id)
                    ->update(['status_id'  =>config('constants.uploaded') ]);
            }

        }

     return Redirect::back()->with('message', $rec. ' Uploaded successfully');
    }


    public function header(){

        $list = ZescoItsInvInterfaceHeader::where('transaction_type', config('constants.transaction_type.petty_cash'))->get();
        $list->load('code');
        $category = 'Zesco Its Inv Interface Header';
        $value = config('constants.petty_cash_status.processed');

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.petty-cash.integration.header')->with(compact('list', 'category', 'value', 'totals_needs_me'));

    }


    public function details($item){

        $list = ZescoItsInvInterfaceDetail::where('invoice_id', $item )->get();
        $list->load('code');
        $category = 'Zesco Its Inv Interface Details for '.$item;
        $value = config('constants.petty_cash_status.processed');

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.petty-cash.integration.details')->with(compact('list', 'category', 'value', 'totals_needs_me'));

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
