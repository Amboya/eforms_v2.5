<?php

namespace App\Http\Controllers\EForms\Subsistence;

use App\Http\Controllers\Controller;

use App\Models\EForms\Subsistence\Integration\ZescoItsInvInterfaceHeader;
use App\Models\EForms\Subsistence\SubsistenceAccountModel;
use App\Models\EForms\Subsistence\SubsistenceModel;
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
        self::checkProcessed() ;

        $ready = config('constants.exported') ;

        //GET THE PETTY CASH MODEL if you are an admin
        $accounts = DB::select("SELECT * FROM eform_subsistence_account where status_id = '{$ready}' ORDER BY  subsistence_code  ");

        $list = SubsistenceAccountModel::hydrate($accounts);
        $list->load("form.user_unit");

        $category = 'Subsistence Details Ready to be Uploaded';
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.subsistence.integration.ready')->with(compact('category', 'list', 'totals_needs_me'));
 }


    public function ready()
    {
        $ready = config('constants.not_exported');
        $type = config('constants.account_type.expense');
        //GET THE PETTY CASH MODEL if you are an admin
        $accounts = DB::select("SELECT * FROM eform_subsistence_account where status_id = '{$ready}' AND  account_type = '{$type}'  ORDER BY  subsistence_code  ");
//        $accounts = DB::select("SELECT * FROM eform_subsistence_account   ");
        $list = SubsistenceAccountModel::hydrate($accounts);
        $list->load("form.user_unit");

       // dd($list);

        $category = 'Subsistence Details Ready to be Uploaded';
        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.subsistence.integration.ready')->with(compact('category', 'list', 'totals_needs_me'));

    }


    public function send(Request $request)
    {
        //get the requested accounts

        if($request->forms == null ){
            return back()->with('error', 'No subsistence invoices were selected');
        }

        $type = config('constants.account_type.expense');

        $codes_array = array_unique($request->forms);
        $codes_array = implode(',', $codes_array);
        $accounts = DB::select("SELECT * FROM eform_subsistence_account WHERE subsistence_code IN ({$codes_array}) AND  account_type = '{$type}'  ORDER  BY  subsistence_code  ");
        $list = SubsistenceAccountModel::hydrate($accounts);
        $list->load("form.user_unit");

        //
        $rec = 0;

        //loop through the accounts
        foreach ($list as $account) {

            $org = $account->org_id;

            //   dd($account->form);

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
                        'transaction_type' => config('constants.transaction_type.subsistence'),
                        'invoice_num' => $account->form->code,
                        'invoice_date' => $account->form->created_at,
                        'invoice_description' => $account->description,
                        'invoice_type' =>  config('constants.transaction_type.invoice_type'),
                        'supplier_num' => $account->form->claimant_staff_no,
                        'invoice_amount' => $account->form->net_amount_paid,
                        'invoice_currency_code' =>config('constants.currency'),
                        'exchange_rate'  =>config('constants.exchange_rate'),
                        'gl_date' => $account->created_at,
                        'org_id' => $account->org_id,
                        'creation_date' => $account->form->claim_date,
                        'process_yn' => config('constants.unprocessed'),

                    ]
                );

                //mark as as updated
                $affected_form = DB::table('eform_subsistence')
                    ->where('id', $account->form->id)
                    ->update(['config_status_id'  => config('constants.exported') ]);
            }


            //check for detail
            $detail = DB::select("SELECT count(invoice_id) as total FROM fms_invoice_interface_detail
                  WHERE invoice_id = '{$account->form->code}' AND org_id = {$org}
                    AND item_description = '{$account->description}' AND  gl_account = '{$account->account}' ");
            $detail_list = ZescoItsInvInterfaceHeader::hydrate($detail)->first();


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
                        'amount' => $account->creditted_amount ?? ( "".$account->debitted_amount),
                        'item_description' => $account->description,
                        'org_id' => $account->org_id,
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
                $affected_account = DB::table('eform_subsistence_account')
                    ->where('id', $account->id)
                    ->update(['status_id'  =>  config('constants.exported')  ]);
            }

        }

        return Redirect::back()->with('message', $rec. ' Uploaded successfully');
    }


    public  function sendFromSubistence(SubsistenceModel $subsistenceModel)
    {
        //get the requested accounts

        $type = config('constants.account_type.expense');
        $code = $subsistenceModel->code ;
        $accounts = DB::select("SELECT * FROM eform_subsistence_account WHERE subsistence_code = '{$code}' AND  account_type = '{$type}'    ");

        $list = SubsistenceAccountModel::hydrate($accounts);
        $list->load("form.user_unit");

        //
        $rec = 0;

        //loop through the accounts
        foreach ($list as $account) {

            $org = $account->org_id;

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
                        'transaction_type' => config('constants.transaction_type.subsistence'),
                        'invoice_num' => $account->form->code,
                        'invoice_date' => $account->form->created_at,
                        'invoice_description' => $account->description,
                        'invoice_type' =>  config('constants.transaction_type.invoice_type'),
                        'supplier_num' => $account->form->claimant_staff_no,
                        'invoice_amount' => $account->form->net_amount_paid,
                        'invoice_currency_code' =>config('constants.currency'),
                        'exchange_rate'  =>config('constants.exchange_rate'),
                        'gl_date' => $account->created_at,
                        'org_id' => $account->org_id,
                        'creation_date' => $account->form->claim_date,
                        'process_yn' => config('constants.unprocessed'),

                    ]
                );

                //mark as as updated
                $affected_form = DB::table('eform_subsistence')
                    ->where('id', $account->form->id)
                    ->update(['config_status_id'  => config('constants.exported') ]);
            }


            //check for detail
            $detail = DB::select("SELECT count(invoice_id) as total FROM fms_invoice_interface_detail
                  WHERE invoice_id = '{$account->form->code}' AND org_id = {$org}
                    AND item_description = '{$account->description}' AND  gl_account = '{$account->account}' ");
            $detail_list = ZescoItsInvInterfaceHeader::hydrate($detail)->first();


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
                        'amount' => $account->creditted_amount ?? ( "".$account->debitted_amount),
                        'item_description' => $account->description,
                        'org_id' => $account->org_id,
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
                $affected_account = DB::table('eform_subsistence_account')
                    ->where('id', $account->id)
                    ->update(['status_id'  =>  config('constants.exported')  ]);
            }

        }

        return  $rec. ' Uploaded successfully' ;
    }


    public function header(){

        //sync the ones that have been uploaded
        self::sync();

        $list = ZescoItsInvInterfaceHeader::where('transaction_type', config('constants.transaction_type.subsistence'))->get();
        $list->load('code');
        $category = 'Zesco Its Inv Interface Header';
        $value = config('constants.processed');

        //count all that needs me
        $totals_needs_me = HomeController::needsMeCount();
        //return view
        return view('eforms.subsistence.integration.header')->with(compact('list', 'category', 'value', 'totals_needs_me'));

    }

    public function checkProcessed(){

        //check for all processed invoices
        $list = ZescoItsInvInterfaceHeader::where('transaction_type', config('constants.transaction_type.subsistence'))->get();
        //mark the ones that have been rejected
        $rejected = $list->where('process_yn', config('constants.unprocessed') )->whereNotNull('error_msg');

        foreach ($rejected as $reject){
            //mark form as as updated

            $vaas = $reject->invoice_id ;
            $affected_form = DB::table('eform_subsistence')
                ->where('code', $reject->invoice_id)
                ->update(['config_status_id'  => config('constants.export_failed') ]);

            //mark accounts as as updated
            $affected_accounts = DB::table('eform_subsistence_account')
                ->where('subsistence_code', $reject->invoice_id)
                ->update(['status_id'  => config('constants.export_failed') ]);

        }

        //mark the ones that have been uploaded
        $uploaded = $list->where('process_yn', config('constants.processed') )->whereNull('error_msg');
        foreach ($uploaded as $upload){
            //mark form as as updated
            $affected_form7 = DB::table('eform_subsistence')
                ->where('code', $upload->invoice_id)
                ->update(['config_status_id'  => config('constants.uploaded') ]);
            //mark accounts as as updated
            $affected_accounts7 = DB::table('eform_subsistence_account')
                ->where('subsistence_code', $upload->invoice_id)
                ->update(['status_id'  => config('constants.uploaded') ] );

        }

    }


    public function sync(){

        //sync the ones that have been uploaded
        $uploaded =  config('constants.uploaded') ;
        $exported =  config('constants.exported') ;

        //get all accounts that are waiting to be uploaded

        $detail = DB::select("SELECT * FROM eform_subsistence_account
                  WHERE status_id = '{$exported}'  ");
        $detail_list = SubsistenceAccountModel::hydrate($detail);

        //update if its proceeded
        foreach ($detail_list as $account){
          //  dd($account);
            //mark as as updated
//            $affected_account = DB::table('eform_subsistence_account')
//                ->where('id', $account->id)
//                ->update(['status_id'  =>  config('constants.uploaded')  ]);
//
//            //mark as as updated
//            $affected_form = DB::table('eform_subsistence')
//                ->where('id', $account->form->id)
//                ->update(['config_status_id'  => config('constants.uploaded') ]);
        }

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
        return view('eforms.subsistence.float-management.list')->with($params);
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
