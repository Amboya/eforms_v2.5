@extends('layouts.eforms.petty-cash.master')


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
                    <h1 class="m-0 text-dark">Petty Cash Detail [ {{ $form->code }} ]</h1>
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

        <form id="show_form" name="db1" action="{{route('petty-cash-approve')}}" method="post"
              enctype="multipart/form-data">
        @csrf

        <!-- Default box -->
            <div class="card">
                <div class="card-body">
                    <span
                        class="badge badge-{{$form->status->html ?? "default"}}">{{$form->status->name ?? "none"}}</span>
                    <input type="hidden" name="id" value="{{ $form->id}}" readonly required>
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
                                    <div class="col-12"><input value="{{  $form->claim_date }}" type="text"
                                                               name="date"
                                                               readonly class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12 "><label>Cost Center:</label>
                                        @if( $user->type_id ==  config('constants.user_types.developer')  )
                                            <a href="{{route('petty.cash.workflow.show',['id'=> $form->user_unit->id ?? "code", 'code' => $form->id ?? "code" ] )}}">
                                                Check Workflow
                                            </a>
                                        @endif
                                    </div>
                                    <div class="col-12"><input type="text" name="cost_center" class="form-control"
                                                               value="{{ $form->user_unit_code}}" readonly required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12"><label>HQMS No:</label></div>
                                    <div class="col-12"><input type="text" value="{{$form->ref_no}}" name="ref_no"
                                                               readonly required class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12"><label>Project Number:</label></div>
                                    <div class="col-12"><input list="project_list" type="text" name="projects_id"
                                                               readonly value="{{$form->project->name ?? '' }}"
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
                                    @foreach($form->item as $item)
                                        <TR>
                                            <TD>
                                                <textarea type="text" name="name[]" class="form-control amount"
                                                          rows="3" id="name" readonly> {{$item->name}}</textarea>
                                            </TD>
                                            <TD><input type="text" id="amount" name="amount[]" onchange="getvalues()"
                                                       readonly class="form-control amount"
                                                       value="ZMW {{$item->amount}}">
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
                                               name="total_payment" value="ZMW {{$form->total_payment}}">
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
                                   value="{{$form->claimant_name}}" readonly required></div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" name="sig_of_claimant" class="form-control"
                                                  value="{{$form->claimant_staff_no}}" readonly required></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" name="date_claimant" class="form-control"
                                                  value="{{$form->claim_date}}" readonly required>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><label>Claim Authorised by:</label></div>
                        <div class="col-3"><input type="text" value="{{$form->authorised_by ?? "" }}"
                                                  name="claim_authorised_by" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->authorised_staff_no  ?? "" }}"
                                                  name="sig_of_authorised" readonly class="form-control">
                        </div>
                        <div class="col-1  text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->authorised_date ?? "" }}"
                                                  name="authorised_date" readonly class="form-control">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><label>HR/Station Manager:</label></div>
                        <div class="col-3"><input type="text" value="{{$form->station_manager ?? "" }}"
                                                  name="station_manager" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->station_manager_staff_no ?? "" }}"
                                                  name="sig_of_station_manager" readonly
                                                  class="form-control"></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->station_manager_date ?? "" }}"
                                                  name="manager_date" readonly class="form-control"></div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-2"><label>Accountant:</label></div>
                        <div class="col-3"><input type="text" value="{{$form->accountant ?? "" }}"
                                                  name="accountant" readonly class="form-control"></div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->accountant_staff_no ?? "" }}"
                                                  name="sig_of_accountant" readonly class="form-control">
                        </div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->accountant_date ?? "" }}"
                                                  name="accountant_date" readonly class="form-control">
                        </div>
                    </div>


                    <p><b>Note:</b> The system reference number is mandatory and is from
                        any of the systems at ZESCO such as a work request number from PEMS, Task
                        number from HQMS, Meeting Number from HQMS, Incident number from IMS etc.
                        giving rise to the expenditure</p>

                </div>
            </div>
            <!-- /.card -->

            {{-- FINANCIAL POSTINGS  --}}
            @if(  ($form->config_status_id >= config('constants.petty_cash_status.closed') )
               && ( ($user->profile_id ==  config('constants.user_profiles.EZESCO_007'))
               || ($user->profile_id ==  config('constants.user_profiles.EZESCO_014'))
               || ($user->profile_id ==  config('constants.user_profiles.EZESCO_011'))
               ) )
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Financial Accounts Postings</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <div class="col-lg-12 ">
                                    <TABLE class="table">
                                        <thead>
                                        <TR>
                                            <TD>Account</TD>
                                            <TD>Debited Amount</TD>
                                            <TD>Credited Amount</TD>
                                        </TR>
                                        </thead>

                                        <tbody>
                                        @foreach($form_accounts as $item)
                                            <TR>
                                                <TD><input list="accounts_list" type="text"
                                                           value="{{$item->account}}"
                                                           class="form-control amount" readonly>
                                                </TD>
                                                <TD><input type="number" id="credited_amount"
                                                           value="{{ number_format($item->creditted_amount ?? 0,2) }}"
                                                           class="form-control amount" readonly>
                                                </TD>
                                                <TD><input type="number" value="{{ number_format($item->debitted_amount ?? 0, 2) }}"
                                                           class="form-control amount" readonly>
                                                </TD>
                                            </TR>
                                        @endforeach
                                        </tbody>

                                    </TABLE>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-light">Change was: ZMW {{$form->change ?? 0}}</span>
                    </div>
                </div>
            @endif
            {{-- NEXT PERSONS TO ACT --}}
            @if(  $form->config_status_id != config('constants.petty_cash_status.closed')   )
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Next Person/s to Act</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($user_array as $item)
                                <div class="col-4 text-red mb-4">
                                <span
                                    class="font-weight-bold">Position:</span><span>{{$item->position->name ?? "No Position" }}</span><br>
                                    <span class="font-weight-bold">Name:</span><span>{{$item->name}}</span><br>
                                    <span class="font-weight-bold">Phone:</span><span>{{$item->phone}}</span><br>
                                    <span class="font-weight-bold">Email:</span><span>{{$item->email}}</span><br>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold">Next Action:</span><span> {{$form->status->other}}</span>
                        @if ($form->status->id == config('constants.petty_cash_status.security_approved'))
                            <span class="font-weight-bold text-red"> Note:</span><span class="text-red"> Export Data to Excel and Import in Oracle Financial's using ADI</span>
                        @endif
                    </div>
                </div>
            @endif

            {{--  QUOTATION FILES--}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quotation Files</h4>
                    @if( ($user->type_id == config('constants.user_types.developer')  || (
                                 $user->profile_id ==  config('constants.user_profiles.EZESCO_002')
                               &&  $user->id  == $form->created_by )
                                 &&  ($form->config_status_id == config('constants.petty_cash_status.new_application'))
                             )
                               )
                        <a class="float-right" href="#" data-toggle="modal" data-sent_data="{{$form}}"
                           data-target="#modal-add-quotation">Add File</a>
                    @endif
                </div>
                <div class="card-body" style="width:100%; height: 900px ">
                    <div class="row">
                        @foreach($quotations as $item)
                            <div class="col-12">
                                <iframe id="{{$item->id}}" src="{{asset('storage/petty_cash_quotation/'.$item->name)}}"
                                        style="width:100%; height: 850px" title="{{$item->name}}"></iframe>
                                <span>{{number_format( $item->file_size, 2) }}MB {{$item->name}} </span>
                                <span> | </span>
                                <a href="{{asset('storage/petty_cash_quotation/'.$item->name)}}">View</a>
                                @if( ($user->type_id == config('constants.user_types.developer')  || (
                                  $user->profile_id ==  config('constants.user_profiles.EZESCO_002')
                                &&  $user->id  == $form->created_by ))
                                &&  ($form->config_status_id == config('constants.petty_cash_status.new_application'))
                                )
                                    <span> | </span>
                                    <a href="#" data-toggle="modal" data-sent_data="{{$item}}"
                                       data-target="#modal-change">Edit</a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>

            {{--  RECEIPT FILES - ONLY WHEN FORM HAS BEEN CLOSED--}}
            @if(  $form->config_status_id == config('constants.petty_cash_status.closed')
            ||  $form->config_status_id == config('constants.petty_cash_status.audited')
            ||  $form->config_status_id == config('constants.petty_cash_status.queried')    )
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Receipt Files</h4>
                        @if( (($user->profile_id ==  config('constants.user_profiles.EZESCO_014')
                &&  $form->user_unit->expenditure_unit == $user->profile_unit_code)
              || ($user->type_id == config('constants.user_types.developer'))
            &&  ( ($form->config_status_id == config('constants.petty_cash_status.queried')) ||
            ($form->config_status_id == config('constants.petty_cash_status.closed')) ) )    )
                            <a class="float-right" href="#" data-toggle="modal" data-sent_data="{{$form}}"
                               data-target="#modal-add-receipt">Add File</a>
                        @endif
                    </div>
                    <div class="card-body" style="width:100%;  ">
                        <div class="row">
                            @foreach($receipts as $item)
                                <div class="col-12">
                                    <iframe id="{{$item->id}}"
                                            src="{{asset('storage/petty_cash_receipt/'.$item->name)}}"
                                            style="width:100%; " title="{{$item->name}}"></iframe>
                                    <span>{{number_format( $item->file_size, 2) }}MB {{$item->name}} </span>
                                    <span> | </span>
                                    <a href="{{asset('storage/petty_cash_receipt/'.$item->name)}}">View</a>

                                    @if( (($user->profile_id ==  config('constants.user_profiles.EZESCO_014')
                             &&  $form->user_unit->expenditure_unit == $user->profile_unit_code)
                           || ($user->type_id == config('constants.user_types.developer'))  )
                         &&  ( ($form->config_status_id == config('constants.petty_cash_status.queried')) ||
                         ($form->config_status_id == config('constants.petty_cash_status.closed'))  )    )
                                        <span> | </span>
                                        <a href="#" data-toggle="modal" data-sent_data="{{$item}}"
                                           data-target="#modal-change">Edit</a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                    </div>
                </div>
            @endif

            {{--  FORM PPROVALS--}}
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Approvals</h4>  <span
                        class="badge badge-secondary right ml-2">{{$approvals->count()}}</span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
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
                                <TD>From Form <br>Submission</TD>
                            </TR>
                            @foreach($approvals as $item)
                                <TR>
                                    <TD>{{$item->name}}</TD>
                                    <TD>{{$item->staff_no}}</TD>
                                    <TD>{{$item->action}}</TD>
                                    <TD>{{$item->from_status->name ?? ""}}</TD>
                                    <TD>{{$item->to_status->name ?? ""}}</TD>
                                    <TD>{{$item->reason}}</TD>
                                    <TD>{{$item->created_at}}</TD>
                                    <TD>{{$form->created_at->diffAsCarbonInterval($item->created_at)}}</TD>
                                </TR>
                            @endforeach
                        </TABLE>

                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{--  CLAIMANT EDIT--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_002')
                         &&  $form->config_status_id == config('constants.petty_cash_status.new_application')
                         &&  $user->id  == $form->created_by)
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
                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Cancelled'>CANCEL PETTY CASH
                                        </button>
                                        <button hidden id="btnSubmit_reject" type="submit" name="approval"
                                                class="btn btn-outline-danger ml-2 p-2  "
                                                value='Rejected'>REJECT
                                        </button>
                                    </div>
                                    <div id="divSubmit_hide">
                                        <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>Processing. Please wait...
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  HOD APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_004')
                         &&  $form->config_status_id == config('constants.petty_cash_status.new_application')
                         &&  $form->user_unit->hod_code == $user->profile_job_code
                         &&  $form->user_unit->hod_unit == $user->profile_unit_code
                      )
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
                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>APPROVE
                                        </button>
                                        <button id="btnSubmit_reject" type="submit" name="approval"
                                                class="btn btn-outline-danger ml-2 p-2  "
                                                value='Rejected'>REJECT
                                        </button>
                                    </div>
                                    <div id="divSubmit_hide">
                                        <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>Processing. Please wait...
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  HR APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_009')
                         &&  $form->config_status_id == config('constants.petty_cash_status.hod_approved')
                         &&  $form->user_unit->hrm_code == $user->profile_job_code
                         &&  $form->user_unit->hrm_unit == $user->profile_unit_code
                     )
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
                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>APPROVE
                                        </button>
                                        <button id="btnSubmit_reject" type="submit" name="approval"
                                                class="btn btn-outline-danger ml-2 p-2  "
                                                value='Rejected'>REJECT
                                        </button>
                                    </div>
                                    <div id="divSubmit_hide">
                                        <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>Processing. Please wait...
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  CHIEF ACCOUNTANT APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_007')
                         &&  $form->config_status_id == config('constants.petty_cash_status.hr_approved')
                         &&  $form->user_unit->ca_code == $user->profile_job_code
                         &&  $form->user_unit->ca_unit == $user->profile_unit_code
                        )
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
                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>APPROVE
                                        </button>
                                        <button id="btnSubmit_reject" type="submit" name="approval"
                                                class="btn btn-outline-danger ml-2 p-2  "
                                                value='Rejected'>REJECT
                                        </button>
                                    </div>
                                    <div id="divSubmit_hide">
                                        <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>Processing. Please wait...
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  FUNDS DISBURSEMNET APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_014')
                         &&  $form->config_status_id == config('constants.petty_cash_status.chief_accountant')
                         &&  $form->user_unit->expenditure_unit == $user->profile_unit_code
                       )
                        <div class="">
                            <h5 class="text-center">Please Update the Accounts </h5>
                            <h6 class="text-center">(Total Amount : ZMW {{$form->total_payment}}) </h6>
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="table-responsive">
                                    <div class="col-lg-12 ">
                                        <TABLE id="dataTable2" class="table">
                                            <TR>
                                                <TD><INPUT type="checkbox" name="chk"/></TD>
                                                <TD>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <input list="items_list1" type="text"
                                                                       name="account_items[]"
                                                                       class="form-control amount"
                                                                       placeholder="Select Item/s   "
                                                                       id="account_items1">
                                                                <datalist id="items_list1">
                                                                    @foreach($form->item as $item)
                                                                        <option>{{$item->name}} : (ZMK {{$item->amount}}
                                                                            )
                                                                        </option>
                                                                    @endforeach
                                                                </datalist>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{--                                                </TD>--}}
                                                    {{--                                                <TD>--}}
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <select name="credited_account[]" id="credited_account"
                                                                    required
                                                                    class="form-control amount">
                                                                @foreach($accounts as $account)
                                                                    @if($account->id  ==  config('constants.petty_cash_account_id')  )
                                                                        <option
                                                                            value="{{$account->code}}">{{$account->name}}
                                                                            :{{$account->code}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        {{--                                                </TD>--}}
                                                        {{--                                                <TD>--}}
                                                        <div class="col-3">
                                                            <input type="number" id="credited_amount"
                                                                   name="credited_amount[]"
                                                                   onchange="getvalues()" class="form-control amount"
                                                                   placeholder=" Amount [ZMK]" required>
                                                        </div>
                                                        {{--                                                </TD>--}}
                                                        {{--                                                <TD>--}}
                                                        <div class="col-3">
                                                            <select name="debited_account[]" id="debited_account"
                                                                    required
                                                                    class="form-control amount">
                                                                <option value="">Select Expense Account</option>
                                                                @foreach($accounts as $account)
                                                                    <option
                                                                        value="{{$account->code}}">{{$account->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        {{--                                                </TD>--}}
                                                        {{--                                                <TD>--}}
                                                        <div class="col-3">
                                                            <input type="number" id="debited_amount"
                                                                   name="debited_amount[]"
                                                                   class="form-control amount"
                                                                   placeholder="Debited Amount [ZMK]" readonly required>
                                                        </div>
                                                    </div>
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
                                        <INPUT type="button" value="Add Row" onclick="addRow('dataTable2')"/>
                                        <INPUT type="button" value="Delete Row" onclick="deleteRow('dataTable2')"/>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <textarea hidden class="form-control" rows="2" name="reason" required> Funds Disbursement</textarea>
                                <div id="submit_not_possible" class="col-12 text-center">
                                        <span class="text-red"><i class="icon fas fa-ban"></i> Alert!
                                        Sorry, You can not submit because Credited Accounts total does not equal to the total payment requested <strong>(ZMK {{$form->total_payment}}
                                                )</strong>
                                   </span>
                                </div>
                                <div id="submit_possible" class="col-12 text-center">
                                    <div class="col-12 text-center ">
                                        <div id="divSubmit_show">
                                            <button id="btnSubmit_approve" type="submit" name="approval"
                                                    class="btn btn-outline-success mr-2 p-2  "
                                                    value='Approved'>FUNDS DISBURSED
                                            </button>
                                            <button style="display: none" id="btnSubmit_reject" type="submit"
                                                    name="approval"
                                                    class="btn btn-outline-success mr-2 p-2  "
                                                    value='Rejected'>FUNDS DISBURSED
                                            </button>
                                        </div>
                                        <div id="divSubmit_hide">
                                            <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                    value='Approved'>Processing. Please wait...
                                            </button>
                                            {{--                                            <button  disabled--}}
                                            {{--                                                     class="btn btn-outline-success mr-2 p-2  "--}}
                                            {{--                                                     value='Approved'>FUNDS DISBURSED--}}
                                            {{--                                            </button>--}}
                                            {{--                                            <button style="display: none" disabled--}}
                                            {{--                                                    class="btn btn-outline-success mr-2 p-2  "--}}
                                            {{--                                                    value='Rejected'>FUNDS DISBURSED--}}
                                            {{--                                            </button>--}}
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                    {{--  FUNDS ACKNOWELEDGMENT APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_002')
                         &&  $form->config_status_id == config('constants.petty_cash_status.funds_disbursement')
                         &&  $form->claimant_staff_no == $user->staff_no
                          )
                        <div class="">
                            <hr>
                            <div class="row">
                                <textarea hidden class="form-control" rows="2" name="reason"
                                          required> Funds Received</textarea>

                                <div class="col-12 text-center ">
                                    {{--                                    <button id="btnSubmit_approve" type="submit" name="approval"--}}
                                    {{--                                            class="btn btn-outline-success mr-2 p-2  "--}}
                                    {{--                                            value='Approved'>FUNDS RECEIVED--}}
                                    {{--                                    </button>--}}

                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>FUNDS RECEIVED
                                        </button>
                                        <button style="display: none" id="btnSubmit_reject" type="submit"
                                                name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Rejected'>FUNDS RECEIVED
                                        </button>
                                    </div>
                                    <div id="divSubmit_hide">
                                        <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>Processing. Please wait...
                                        </button>
                                        {{--                                        <button  disabled--}}
                                        {{--                                                 class="btn btn-outline-success mr-2 p-2  "--}}
                                        {{--                                                 value='Approved'>FUNDS RECEIVED--}}
                                        {{--                                        </button>--}}
                                        {{--                                        <button style="display: none" disabled--}}
                                        {{--                                                class="btn btn-outline-success mr-2 p-2  "--}}
                                        {{--                                                value='Rejected'>FUNDS RECEIVED--}}
                                        {{--                                        </button>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  SECURITY APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_013')
                         &&  $form->config_status_id == config('constants.petty_cash_status.funds_acknowledgement')
                         &&  $form->user_unit->security_unit == $user->profile_unit_code
                        )
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
                                    {{--                                    <button id="btnSubmit_approve" type="submit" name="approval" class="btn btn-outline-success mr-2 p-2  "--}}
                                    {{--                                            value='Approved'>APPROVE RECEIPTS--}}
                                    {{--                                    </button>--}}

                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>APPROVE RECEIPTS
                                        </button>
                                        <button style="display: none" id="btnSubmit_reject" type="submit"
                                                name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Rejected'>APPROVE RECEIPTS
                                        </button>
                                    </div>
                                    <div id="divSubmit_hide">
                                        <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>Processing. Please wait...
                                        </button>
                                        {{--                                        <button  disabled--}}
                                        {{--                                                 class="btn btn-outline-success mr-2 p-2  "--}}
                                        {{--                                                 value='Approved'>APPROVE RECEIPTS--}}
                                        {{--                                        </button>--}}
                                        {{--                                        <button style="display: none" disabled--}}
                                        {{--                                                class="btn btn-outline-success mr-2 p-2  "--}}
                                        {{--                                                value='Rejected'>APPROVE RECEIPTS--}}
                                        {{--                                        </button>--}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  RECEIPT APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_014')
                         &&  $form->config_status_id == config('constants.petty_cash_status.security_approved')
                         &&  $form->user_unit->expenditure_unit == $user->profile_unit_code
                       )
                        <div class="">
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
                                                                @foreach($form->item as $item)
                                                                    <option>{{$item->name}}</option>
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                    </TD>
                                                    <TD>
                                                        <select name="credited_account" id="credited_account1"
                                                                class="form-control amount">
                                                            <option value="">Select Account To Credit</option>
                                                            @foreach($accounts as $account)
                                                                <option
                                                                    value="{{$account->code}}">{{$account->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </TD>
                                                    <TD><input type="number" name="credited_amount"
                                                               id="credited_amount1" class="form-control amount"
                                                               placeholder=" Credited Amount [ZMK]" readonly>
                                                    </TD>
                                                    <TD>
                                                        <select name="debited_account" id="debited_account1"
                                                                class="form-control amount">
                                                            @foreach($accounts as $account)
                                                                @if($account->id  ==  config('constants.petty_cash_account_id')  )
                                                                    <option
                                                                        value="{{$account->code}}">{{$account->name}}
                                                                        :{{$account->code}}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </TD>
                                                    <TD><input type="number" name="debited_amount"
                                                               class="form-control amount" id="debited_amount1"
                                                               placeholder="Amount [ZMK]" readonly>
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <textarea hidden class="form-control" rows="2" name="reason" required> Closing of petty cash</textarea>
                                    <div class="col-2 text-center ">
                                        {{--                                        <button id="btnSubmit_approve" type="submit" name="approval" class="btn btn-outline-success mr-2 p-2  "--}}
                                        {{--                                                value='Approved'>CLOSE PETTY-CASH--}}
                                        {{--                                        </button>--}}

                                        <div id="divSubmit_show">
                                            <button id="btnSubmit_approve" type="submit" name="approval"
                                                    class="btn btn-outline-success mr-2 p-2  "
                                                    value='Approved'>CLOSE PETTY-CASH
                                            </button>
                                            <button style="display: none" id="btnSubmit_reject" type="submit"
                                                    name="approval"
                                                    class="btn btn-outline-success mr-2 p-2  "
                                                    value='Rejected'>CLOSE PETTY-CASH
                                            </button>
                                        </div>
                                        <div id="divSubmit_hide">
                                            <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                    value='Approved'>Processing. Please wait...
                                            </button>
                                            {{--                                            <button  disabled--}}
                                            {{--                                                     class="btn btn-outline-success mr-2 p-2  "--}}
                                            {{--                                                     value='Approved'>CLOSE PETTY-CASH--}}
                                            {{--                                            </button>--}}
                                            {{--                                            <button style="display: none" disabled--}}
                                            {{--                                                    class="btn btn-outline-success mr-2 p-2  "--}}
                                            {{--                                                    value='Rejected'>CLOSE PETTY-CASH--}}
                                            {{--                                            </button>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  AUDIT APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_011')
                        &&  $form->config_status_id == config('constants.petty_cash_status.closed')
                        &&  $form->user_unit->audit_unit == $user->profile_unit_code
                          )
                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-1">
                                            <label class="form-control-label">Reason</label>
                                        </div>
                                        <div class="col-11">
                                            <textarea class="form-control" rows="2" name="reason" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-center ">
                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success p-2   "
                                                value='Approved'>AUDITED
                                        </button>
                                        <button id="btnSubmit_reject" type="submit"
                                                name="approval"
                                                class="btn btn-outline-danger p-2   "
                                                value='Queried'>QUERIED
                                        </button>
                                    </div>
                                    <div id="divSubmit_hide">
                                        <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>Processing. Please wait...
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  QUERIED RESOLUTION--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_014')
                         &&  $form->config_status_id == config('constants.petty_cash_status.queried')
                         &&  $form->user_unit->expenditure_unit == $user->profile_unit_code
                        )
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
                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Resolve'>RESOLVE QUERY
                                        </button>
                                        <button style="display: none" id="btnSubmit_reject" type="submit"
                                                name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Rejected'>RESOLVE QUERY
                                        </button>
                                    </div>
                                    <div id="divSubmit_hide">
                                        <button disabled class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>Processing. Please wait...
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <!-- /.card-footer-->
            </div>

        </form>

    </section>
    <!-- /.content -->


    <!-- CHANGE MODAL-->
    <div class="modal fade" id="modal-change">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">You want to change this file</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form-new" enctype="multipart/form-data" method="post"
                      action="{{route('attached-file-change')}} ">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div id="name2">

                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Change File</label>
                                    <input type="file" class="form-control" id="change_file" name="change_file"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="item_id" name="id"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="form_id" name="form_id"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="path" name="path"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.CHANGE modal -->

    <!-- ADD MODAL-->
    <div class="modal fade" id="modal-add-quotation">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Add Quotation File</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- form start -->
                <form role="form-new" enctype="multipart/form-data" method="post"
                      action="{{route('attached-file-add')}} ">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Add File</label>
                                    <input type="file" class="form-control" id="add_file1" name="add_file"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="item_type1" name="file_type"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="form_type1" name="form_type"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="form_code1" name="form_id"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="path1" name="path"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-add-receipt">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Add Receipt File</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form-new" enctype="multipart/form-data" method="post"
                      action="{{route('attached-file-add')}} ">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Add File</label>
                                    <input type="file" class="form-control" id="add_file2" name="add_file"
                                           placeholder="Enter profile name" required>
                                    <input hidden  class="form-control" id="item_type2" name="file_type"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="form_type2" name="form_type"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="form_code2" name="form_id"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="path2" name="path"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.ADD modal -->


@endsection


@push('custom-scripts')
    <!-- JQuery -->
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
            var debiteds = document.getElementsByName('debited_amount[]');
            var total = 0;
            for (var i = 0; i < inps.length; i++) {
                var inp = inps[i];
                total = total + parseFloat(inp.value || 0);

                //get the related field
                var debited = debiteds[i];
                //set value
                debited.value = parseFloat(inp.value || 0);
            }

            var total_payment = {!! json_encode($form->total_payment) !!};

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

            }
        }


        function getvalues1() {
            var inps = document.getElementsByName('debited_amount[]');
            var debiteds = document.getElementsByName('credited_amount[]');
            var total = 0;
            for (var i = 0; i < inps.length; i++) {
                var inp = inps[i];
                total = total + parseFloat(inp.value || 0);

                //get the related field
                var debited = debiteds[i];
                //set value
                debited.value = parseFloat(inp.value || 0);
            }

            var total_payment = {!! json_encode($form->total_payment) !!};

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

                var change_value_int = parseFloat(change_value);

                //check if petty cash accounts is equal to total_payment
                if (Number(change_value_int) > 0) {
                    $('#show_change').show();
                    //set value
                    document.getElementById('credited_amount1').value = change_value;
                    document.getElementById('debited_amount1').value = change_value;
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

    <script>
        $(document).ready(function () {
            $("#divSubmit_hide").hide();
            //disable the submit button
            $("#btnSubmit_approve").on('click', function () {
                $("#show_form").submit(function (e) {
                    //  e.preventDefault()
                    //do something here
                    $("#divSubmit_show").hide();
                    $("#divSubmit_hide").show();
                    //continue submitting
                    e.currentTarget.submit();
                });
            });
        });
    </script>

    <script>
        $('#modal-change').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes

            var url_start = '{{asset('storage/petty_cash_quotation')}}';
            var path = "public/petty_cash_quotation";
            var dds = recipient.file_type;
            if (dds == 0) {
                url_start = '{{asset('storage/petty_cash_receipt')}}';
                path = "public/petty_cash_receipt";
            }
            var url = url_start + "/" + recipient.name;

            console.log(url);

            var sdfdfds = " <iframe  style='width:100%; height: 550px ' src='" + url + "'  ></iframe>"

            $('#name2').html(sdfdfds);
            $('#item_id').val(recipient.id);
            $('#form_id').val(recipient.form_id);
            $('#path').val(path);


        });
    </script>

    <script>

        $('#modal-add-quotation').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes

            var path = "public/petty_cash_quotation";
            var type = {!! config('constants.file_type.quotation') !!};
            var form_id = {!! config('constants.eforms_id.petty_cash') !!};
            var form_code = recipient.code;

            $('#item_type1').val(type);
            $('#form_type1').val(form_id);
            $('#form_code1').val(form_code);
            $('#path1').val(path);


        });

        $('#modal-add-receipt').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes

            var path = "public/petty_cash_receipt";
            var type = {!! config('constants.file_type.receipt') !!};
            var form_id = {!! config('constants.eforms_id.petty_cash') !!};
            var form_code = recipient.code;

            $('#item_type2').val(type);
            $('#form_type2').val(form_id);
            $('#form_code2').val(form_code);
            $('#path2').val(path);

        });
    </script>

@endpush
