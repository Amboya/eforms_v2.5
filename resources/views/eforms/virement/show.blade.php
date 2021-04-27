@extends('layouts.eforms.virement.master')


@push('custom-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('dashboard/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Petty Cash Detail</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('petty-cash-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Petty Cash Detail</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main page content -->
    <section class="content">


        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible">
                <p class="lead"> {{session()->get('message')}}</p>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible">
                <p class="lead"> {{session()->get('message')}}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


    <!-- Default box -->
        <div class="card">
            <form name="db1" action="{{route('petty-cash-approve')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <span class="badge badge-{{$pettycash->status->html ?? "default"}}">{{$pettycash->status->name ?? "none"}}</span>
                    <input type="hidden" name="id" value="{{ $pettycash->id}}" readonly required>
                    <input type="hidden" name="sig_date" value=" {{date('Y-m-d H:i:s')}}" readonly required>

                    <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
                           class="mt-2 mb-4">
                        <thead>
                        <tr>
                            <th width="33%" colspan="1" class="text-center"><a href="#"><img
                                            src="{{ asset('dashboard/dist/img/zesco1.png')}}" title="ZESCO" alt="ZESCO"
                                            width="25%"></a></th>
                            <th width="33%" colspan="4" class="text-center">Petty Cash Voucher</th>
                            <th width="34%" colspan="1" class="p-3">Doc Number:<br>CO.14900.FORM.00165<br>Version: 3
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="row mt-2 mb-4 p-2">
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12"><label>Date:</label></div>
                                    <div class="col-12"><input value="{{  $pettycash->claim_date }}" type="text"
                                                               name="date"
                                                               readonly class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12 "><label>Cost Center:</label></div>
                                    <div class="col-12"><input type="text" name="cost_center" class="form-control"
                                                               value="{{ $pettycash->cost_center}}" readonly required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12"><label>SYS Ref No:</label></div>
                                    <div class="col-12"><input type="text" value="{{$pettycash->code}}" name="ref_no"
                                                               readonly required class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12"><label>Project Number:</label></div>
                                    <div class="col-12"><input list="project_list" type="text" name="projects_id"
                                                               value="{{$pettycash->project->name ?? '' }}"
                                                               class="form-control">
                                        <datalist id="project_list">
                                            @foreach($projects as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="table-responsive">
                            <div class="col-lg-12 ">
                                <table class="table bg-green">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>DETAILS OF PAYMENT</th>
                                        <th>AMOUNT</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-lg-12 ">
                                <TABLE id="dataTable1" class="table">
                                    @foreach($pettycash->item as $item)
                                        <TR>
                                            <TD><input type="text" name="name[]" class="form-control amount"
                                                       value="{{$item->name}}" id="name" required>
                                            </TD>
                                            <TD><input type="text" id="amount" name="amount[]" onchange="getvalues()"
                                                       class="form-control amount" value="{{$item->amount}}">
                                            </TD>
                                        </TR>
                                    @endforeach
                                </TABLE>
                            </div>
                            <div class="col-lg-6 offset-6 ">
                                <div class="row">
                                    <div class="col-4 text-right">
                                        TOTAL PAYMENT
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control text-bold" readonly id="total-payment"
                                               name="total_payment" value="{{$pettycash->total_payment}}">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>


                    <div class="row mb-1 mt-4">
                        <div class="col-2">
                            <label>Name of Claimant:</label>
                        </div>
                        <div class="col-3">
                            <input type="text" name="claimant_name" class="form-control"
                                   value="{{$pettycash->claimant_name}}" readonly required></div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" name="sig_of_claimant" class="form-control"
                                                  value="{{$pettycash->claimant_staff_no}}" readonly required></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" name="date_claimant" class="form-control"
                                                  value="{{$pettycash->claim_date}}" readonly required>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><label>Claim Authorised by:</label></div>
                        <div class="col-3"><input type="text" value="{{$pettycash->authorised_by ?? "" }}"
                                                  name="claim_authorised_by" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$pettycash->authorised_staff_no  ?? "" }}"
                                                  name="sig_of_authorised" readonly class="form-control">
                        </div>
                        <div class="col-1  text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$pettycash->authorised_date ?? "" }}"
                                                  name="authorised_date" readonly class="form-control">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><label>HR/Station Manager:</label></div>
                        <div class="col-3"><input type="text" value="{{$pettycash->station_manager ?? "" }}"
                                                  name="station_manager" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$pettycash->station_manager_staff_no ?? "" }}"
                                                  name="sig_of_station_manager" readonly
                                                  class="form-control"></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$pettycash->station_manager_date ?? "" }}"
                                                  name="manager_date" readonly class="form-control"></div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-2"><label>Accountant:</label></div>
                        <div class="col-3"><input type="text" value="{{$pettycash->accountant ?? "" }}"
                                                  name="accountant" readonly class="form-control"></div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$pettycash->accountant_staff_no ?? "" }}"
                                                  name="sig_of_accountant" readonly class="form-control">
                        </div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$pettycash->accountant_date ?? "" }}"
                                                  name="accountant_date" readonly class="form-control">
                        </div>
                    </div>


                    <p><b>Note:</b> The system reference number is mandatory and is from
                        any of the systems at ZESCO such as a work request number from PEMS, Task
                        number from HQMS, Meeting Number from HQMS, Incident number from IMS etc.
                        giving rise to the expenditure</p>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{--  CLAIMANT EDIT--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002'))
                        <div class="row">
                            <div id="submit_not_possible" class="col-12 text-center">
                                <div class="alert alert-danger ">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                    Sorry, You can not submit <strong>petty cash above K2000</strong>
                                </div>
                            </div>
                            <div id="submit_possible" class="col-12 text-center">
                                <input class="btn btn-lg btn-success" type="submit"
                                       value="update"
                                       name="submit_form" class="form-control"
                                       onClick="formValidation()">
                            </div>
                        </div>
                    @endif


                    {{--  HOD APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_004')   &&  $pettycash->config_status_id == config('constants.petty_cash_status.new_application')   )
                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-1">
                                            <label class="form-control-label">Reason</label>
                                        </div>
                                        <div class="col-11">
                                            <textarea class="form-control" rows="2" name="reason" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 text-center ">
                                    <button type="submit" name="approval" class="btn btn-outline-success mr-2 p-2  "
                                            value='Approved'>APPROVE
                                    </button>
                                    <button type="submit" name="approval" class="btn btn-outline-danger ml-2 p-2  "
                                            value='Rejected'>REJECT
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  HR APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_009')   &&  $pettycash->config_status_id == config('constants.petty_cash_status.hod_approved')   )
                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-1">
                                            <label class="form-control-label">Reason</label>
                                        </div>
                                        <div class="col-11">
                                            <textarea class="form-control" rows="2" name="reason" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 text-center ">
                                    <button type="submit" name="approval" class="btn btn-outline-success mr-2 p-2  "
                                            value='Approved'>APPROVE
                                    </button>
                                    <button type="submit" name="approval" class="btn btn-outline-danger ml-2 p-2  "
                                            value='Rejected'>REJECT
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  CHIEF ACCOUNTANT APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_007')   &&  $pettycash->config_status_id == config('constants.petty_cash_status.hr_approved')   )
                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-1">
                                            <label class="form-control-label">Reason</label>
                                        </div>
                                        <div class="col-11">
                                            <textarea class="form-control" rows="2" name="reason" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 text-center ">
                                    <button type="submit" name="approval" class="btn btn-outline-success mr-2 p-2  "
                                            value='Approved'>APPROVE
                                    </button>
                                    <button type="submit" name="approval" class="btn btn-outline-danger ml-2 p-2  "
                                            value='Rejected'>REJECT
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  FUNDS DISBURSEMNET APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_014')   &&  $pettycash->config_status_id == config('constants.petty_cash_status.chief_accountant')   )
                        <div class="">
                            <h6 class="text-center">Please Update the Accounts</h6>
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="table-responsive">
                                    <div class="col-lg-12 ">
                                        <TABLE id="dataTable" class="table">
                                            <TR>
                                                <TD><INPUT type="checkbox" name="chk"/></TD>

                                                <TD>
                                                    <div class="form-group">
                                                        {{--                                                        <select class="form-control"  data-placeholder="Select Item/s   " name="account_items"--}}
                                                        {{--                                                                >--}}
                                                        {{--                                                            @foreach($pettycash->item as $item)--}}
                                                        {{--                                                            <option>{{$item->name}}</option>--}}
                                                        {{--                                                            @endforeach--}}
                                                        {{--                                                        </select>--}}
                                                        <input list="items_list1" type="text" name="account_items[]"
                                                               class="form-control amount"
                                                               placeholder="Select Item/s   " id="account_items1">
                                                        <datalist id="items_list1">
                                                            @foreach($pettycash->item as $item)
                                                                <option>{{$item->name}}</option>
                                                            @endforeach
                                                        </datalist>
                                                    </div>
                                                </TD>

                                                <TD><input list="accounts_list" type="text" name="credited_account[]"
                                                           class="form-control amount" placeholder="Credited Account"
                                                           id="credited_account" required>
                                                </TD>
                                                <TD><input type="number" id="credited_amount" name="credited_amount[]"
                                                           onchange="getvalues()" class="form-control amount"
                                                           placeholder=" Credited Amount [ZMK]" required>
                                                </TD>
                                                <TD><input list="accounts_list" type="text" name="debited_account[]"
                                                           class="form-control amount" placeholder="Debited Account"
                                                           id="debited_account" required>
                                                </TD>
                                                <TD><input type="number" id="debited_amount" name="debited_amount[]"
                                                           class="form-control amount"
                                                           placeholder="Debited Amount [ZMK]" required>
                                                </TD>
                                            </TR>
                                        </TABLE>
                                        <datalist id="accounts_list">
                                            @foreach($accounts as $account)
                                                <option value="{{$account->code}}">{{$account->name}}</option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <div class="col-lg-12 ">
                                        <INPUT type="button" value="Add Row" onclick="addRow('dataTable')"/>
                                        <INPUT type="button" value="Delete Row" onclick="deleteRow('dataTable')"/>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <textarea hidden class="form-control" rows="2" name="reason" required> Funds Disbursement</textarea>
                                <div id="submit_not_possible" class="col-12 text-center">

                                        <span class="text-red"><i class="icon fas fa-ban"></i> Alert!
                                        Sorry, You can not submit because Credited Accounts total does not equal to the total payment requested <strong>(ZMK {{$pettycash->total_payment}}
                                                )</strong>
                                   </span>
                                </div>
                                <div id="submit_possible" class="col-12 text-center">
                                    <div class="col-12 text-center ">
                                        <button type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>FUNDS DISBURSED
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                    {{--  FUNDS ACKNOWELEDGMENT APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002')   &&  $pettycash->config_status_id == config('constants.petty_cash_status.funds_disbursement')   )
                        <div class="">
                            <hr>
                            <div class="row">
                                <textarea hidden class="form-control" rows="2" name="reason"
                                          required> Funds Received</textarea>

                                <div class="col-12 text-center ">
                                    <button type="submit" name="approval"
                                            class="btn btn-outline-success mr-2 p-2  "
                                            value='Approved'>FUNDS RECEIVED
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  SECURITY APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_013')   &&  $pettycash->config_status_id == config('constants.petty_cash_status.funds_acknowledgement')   )
                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-1">
                                            <label class="form-control-label">Reason</label>
                                        </div>
                                        <div class="col-11">
                                            <textarea class="form-control" rows="2" name="reason" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2 text-center ">
                                    <button type="submit" name="approval" class="btn btn-outline-success mr-2 p-2  "
                                            value='Approved'>APPROVE RECEIPTS
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  RECEIPT APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_014')   &&  $pettycash->config_status_id == config('constants.petty_cash_status.security_approved')   )
                        <div class="">
                            <h6 class="text-center">The Updated Accounts</h6>
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="table-responsive">
                                    <div class="col-lg-12 ">
                                        <TABLE class="table">
                                            <thead>
                                            <TR>
                                                <TD>Credited Account</TD>
                                                <TD>Credited Amount</TD>
                                                <TD>Debited Account</TD>
                                                <TD>Debited Amount</TD>
                                            </TR>
                                            </thead>

                                            <tbody>
                                            @foreach($pettycash->accounts as $item)
                                                <TR>
                                                    <TD><input list="accounts_list" type="text"
                                                               value="{{$item->creditted_account_id}}"
                                                               class="form-control amount" readonly>
                                                    </TD>
                                                    <TD><input type="number" id="credited_amount"
                                                               value="{{$item->creditted_amount}}"
                                                               class="form-control amount" readonly>
                                                    </TD>
                                                    <TD><input list="accounts_list" type="text"
                                                               value="{{$item->debitted_account_id}}"
                                                               class="form-control amount" readonly>
                                                    </TD>
                                                    <TD><input type="number" value="{{$item->debitted_amount}}"
                                                               class="form-control amount" readonly>
                                                    </TD>
                                                </TR>
                                            @endforeach
                                            </tbody>

                                        </TABLE>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="col-lg-10 p-2 mt-3 ">
                                    <div class="row">
                                        <div class="col-2">
                                            <label class="form-control-label">Total Change</label>
                                        </div>
                                        <div class="col-8">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="number" onchange="showChange()" class="form-control"
                                                           name="change" id="change" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 grid-margin stretch-card" id="show_change">
                                    <h6 class="text-left p-2">Select Account to Retire Change</h6>
                                    <div class="table-responsive">
                                        <div class="col-lg-12 ">
                                            <TABLE class="table">
                                                <tbody>
                                                <TR>

                                                    <TD>
                                                        <div class="form-group">
                                                            <input list="items_list" type="text" name="account_item"
                                                                   class="form-control amount"
                                                                   placeholder="Select Item/s   " id="account_item1">
                                                            <datalist id="items_list">
                                                                @foreach($pettycash->item as $item)
                                                                    <option>{{$item->name}}</option>
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                    </TD>
                                                    <TD><input list="accounts_list" type="text"
                                                               name="credited_account" class="form-control amount"
                                                               placeholder="Credited Account" id="credited_account1">
                                                    </TD>
                                                    <TD><input type="number" name="credited_amount"
                                                               id="credited_amount1" class="form-control amount"
                                                               placeholder=" Credited Amount [ZMK]" readonly>
                                                    </TD>
                                                    <TD><input list="accounts_list" type="text" name="debited_account"
                                                               class="form-control amount" placeholder="Debited Account"
                                                               id="debited_account1">
                                                    </TD>
                                                    <TD><input type="number" name="debited_amount"
                                                               class="form-control amount" id="debited_amount1"
                                                               placeholder="Debited Amount [ZMK]" readonly>
                                                    </TD>
                                                </TR>
                                                </tbody>
                                                <datalist id="accounts_list">
                                                    @foreach($accounts as $account)
                                                        <option value="{{$account->code}}">{{$account->name}}</option>
                                                    @endforeach
                                                </datalist>

                                            </TABLE>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row p-2">
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-2">
                                                <label class="form-control-label">Receipt Files</label>
                                            </div>
                                            <div class="col-8">
                                                <div class="input-group">
                                                    <input type="file" class="form-control" multiple name="receipt[]"
                                                           id="receipt" required>

                                                    {{--<div class="custom-file">--}}
                                                    {{--<input type="file" class="custom-file-input" multiple name="receipt[]" id="receipt" required >--}}
                                                    {{--<label class="custom-file-label" for="receipt"></label>--}}
                                                    {{--</div>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea hidden class="form-control" rows="2" name="reason" required> Closing of petty cash</textarea>
                                    <div class="col-2 text-center ">
                                        <button type="submit" name="approval" class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>CLOSE PETTY-CASH
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <!-- /.card-footer-->
            </form>
        </div>
        <!-- /.card -->

        {{--  FORM HAS NOT YET BEEN CLOSED--}}
        @if(  $pettycash->config_status_id != config('constants.petty_cash_status.closed')   )
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Next Person/s</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($user_array as $item)
                            <div class="col-4">
                                <span>Name:{{$item->name}}</span><br>
                                <span>Phone:{{$item->phone}}</span><br>
                                <span>Email:{{$item->email}}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        @endif

        {{--  FORM HAS BEEN CLOSED--}}
        @if(  $pettycash->config_status_id == config('constants.petty_cash_status.closed')   )
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Receipt Files</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($pettycash->receipts as $item)
                            <div class="col-3">
                                <iframe id="{{$item->id}}" src="{{asset('storage/petty_cash_receipt/'.$item->name)}}"
                                        style="width:100%;" title="{{$item->name}}"></iframe>
                                <span>{{$item->file_size}}MB {{$item->name}} </span>
                                <a href="{{asset('storage/petty_cash_receipt/'.$item->name)}}">View</a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        @endif


        <div class="card collapsed-card">
            <div class="card-header">
                <h4 class="card-title">Approvals</h4>  <span
                        class="badge badge-secondary right ml-2">{{$pettycash->approval->count()}}</span>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div style="display: none;" class="card-body">
                <div class="col-lg-12 ">
                    <TABLE id="dataTable" class="table">
                        <TR bgcolor="#f5f5f5">
                            <TD>Name</TD>
                            <TD>Man No</TD>
                            <TD>Action</TD>
                            <TD>Status From</TD>
                            <TD>Status To</TD>
                            <TD>Reason</TD>
                            <TD>Date</TD>
                        </TR>
                        @foreach($pettycash->approval as $item)
                            <TR>
                                <TD>{{$item->name}}</TD>
                                <TD>{{$item->staff_no}}</TD>
                                <TD>{{$item->action}}</TD>
                                <TD>{{$item->from_status->name ?? ""}}</TD>
                                <TD>{{$item->to_status->name ?? ""}}</TD>
                                <TD>{{$item->reason}}</TD>
                                <TD>{{$item->created_at}}</TD>
                            </TR>
                        @endforeach
                    </TABLE>

                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection


@push('custom-scripts')
<!--  -->

<script src="{{ asset('dashboard/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Select2 -->
<script src="{{ asset('dashboard/plugins/select2/js/select2.full.min.js')}}"></script>

<script type="text/javascript">

    // Navigation Script Starts Here
    $(document).ready(function () {
        //first hide the buttons
        $('#submit_possible').hide();
        $('#submit_not_possible').hide();
        $('#show_change').hide();

        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

    });


    function getvalues() {
        var inps = document.getElementsByName('credited_amount[]');
        var total = 0;
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            total = total + parseFloat(inp.value || 0);
        }

        var total_payment = {!! json_encode($pettycash->total_payment) !!};

        if (!isNaN(total)) {

            //check if petty cash accounts is equal to total_payment
            if (total == total_payment) {
                $('#submit_possible').show();
                $('#submit_not_possible').hide();
            } else if (total < total_payment) {
                $('#submit_not_possible').show();
                $('#submit_possible').hide();
            } else {
                $('#submit_not_possible').show();
                $('#submit_possible').hide();
            }
            //set value
            //document.getElementById('total-payment').value = total;
        }
    }


    function getvalues1() {
        var inps = document.getElementsByName('debited_amount[]');
        var total = 0;
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            total = total + parseFloat(inp.value || 0);
        }

        var total_payment = {!! json_encode($pettycash->total_payment) !!};

        if (!isNaN(total)) {

            //check if petty cash accounts is equal to total_payment
            if (total == total_payment) {
                $('#submit_possible').show();
                $('#submit_not_possible').hide();
            } else if (total < total_payment) {
                $('#submit_not_possible').show();
                $('#submit_possible').hide();
            } else {
                $('#submit_not_possible').show();
                $('#submit_possible').hide();
            }
            //set value
            //document.getElementById('total-payment').value = total;
        }
    }


    function showChange() {

        var change_value = document.getElementById('change').value;

        if (!isNaN(change_value)) {

            //check if petty cash accounts is equal to total_payment
            if (change_value > 0) {
                $('#show_change').show();
                //set value
                document.getElementById('credited_amount1').value = change_value;
                document.getElementById('debited_amount1').value = 0;
            } else {
                $('#show_change').hide();
            }

        }
    }


</script>


<SCRIPT language="javascript">
    function addRow(tableID) {

        var table = document.getElementById(tableID);

        var rowCount = table.rows.length;
        var row = table.insertRow(rowCount);

        var colCount = table.rows[0].cells.length;

        for (var i = 0; i < colCount; i++) {

            var newcell = row.insertCell(i);

            newcell.innerHTML = table.rows[0].cells[i].innerHTML;
            //alert(newcell.childNodes);
            switch (newcell.childNodes[0].type) {
                case "text":
                    newcell.childNodes[0].value = "";
                    break;
                case "checkbox":
                    newcell.childNodes[0].checked = false;
                    break;
                case "select-one":
                    newcell.childNodes[0].selectedIndex = 0;
                    break;
            }
        }
    }

    function deleteRow(tableID) {
        try {
            var table = document.getElementById(tableID);
            var rowCount = table.rows.length;

            for (var i = 0; i < rowCount; i++) {
                var row = table.rows[i];
                var chkbox = row.cells[0].childNodes[0];
                if (null != chkbox && true == chkbox.checked) {
                    if (rowCount <= 1) {
                        alert("Cannot delete all the rows.");
                        break;
                    }
                    table.deleteRow(i);
                    rowCount--;
                    i--;
                }
            }
            getvalues();
        } catch (e) {
            alert(e);
        }
    }

</SCRIPT>

@endpush
