@extends('layouts.eforms.subsistence.master')


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
                    <h1 class="m-0 text-dark text-orange text-uppercase">Subsistence Claim <span class="text-green">{{ $form->code }}</span> </h1>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                           document.getElementById('show-form'+{{$trip->id}}).submit();">
                        <span class="text-orange text-bold">TRIP CODE :  <span class="text-green "> {{ $trip->code }}</span></span>
                    </a>
                    <form id="show-form{{$trip->id}}"
                          action="{{ route('trip.show', $trip->id) }}"
                          method="POST" class="d-none">
                        @csrf
                    </form>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('subsistence.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Subsistence Claim</li>
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
                <p class="lead"> {!! session()->get('message') !!}</p>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible">
                <p class="lead"> {!! session()->get('message') !!}</p>
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

        <form id="show_form" name="db1" action="{{route('subsistence.approve')}}" method="post"
              enctype="multipart/form-data">
        @csrf

        <!-- Default box -->
            <div class="card">
                <div class="card-body">
                    <span
                        class="badge badge-{{$form->status->html ?? "default"}}">{{$form->status->name ?? "none"}}</span>
                    <input type="hidden" name="id" value="{{ $form->id}}" readonly required>
                    <input type="hidden" name="sig_date" value=" {{date('Y-m-d H:i:s')}}" readonly required>


                    <table border="1" width="100%" data-height="100px" cellspacing="0" cellpadding="0" align="Centre"
                           class=" mt-4 mb-4 ">
                        <thead>
                        <tr class="border-success">
                            <th width="33%" class="text-center"><a href="#"><img
                                        src="{{ asset('dashboard/dist/img/zesco1.png')}}" title="ZESCO" alt="ZESCO"
                                        width="30%"></a></th>
                            <th width="33%" colspan="4" class="text-center">CLAIM FOR SUBSISTENCE AND TRAVEL EXPENSES
                            </th>
                            <th width="34%" colspan="1" class="p-3">Doc Number:<br>CO.14900.FORM.00040<br>Version: 5
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <div class="row mt-2 mb-4">
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Date:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->claim_date }}" type="text" name="date" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Name of claimant:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->claimant_name }}" type="text" name="name" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1 ">
                            <div class="row">
                                <div class="col-6"><label>Man No.:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->claimant_staff_no }}" type="text" name="employee_number"
                                           readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1 ">
                            <div class="row">
                                <div class="col-6"><label>Grade:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->grade }}" type="text" name="grade" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Cost Center:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->cost_center }}" type="cost_center"
                                           readonly name="date" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1 ">
                            <div class="row">
                                <div class="col-6"><label>Section:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->section }}" type="text" name="department"
                                           readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Station:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->station }} " type="text" name="station" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Ext No.:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->ext_no }}" type="text" name="extension" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>System Reference No.:</label></div>
                                <div class="col-6">
                                    <input value="{{$form->ref_no }}" type="text" name="ref_no" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <table
                            class="table table-bordered mt-2 mb-4">
                            <tr style="text-align: center" width="w-100">
                                <td colspan="5" width="w-100"><strong class="text-orange">A. ABSENCE CLAIM</strong></td>
                            </tr>
                            <tr>
                                <td class="text-green text-bold ">Period of Absence Date</td>
                                <td class="text-green">From</td>
                                <td><input readonly id="absc_absent_from" name="absc_absent_from" class="form-control"
                                           type="text"
                                           value="{{ Carbon::parse(  $form->absc_absent_from )->isoFormat('Do MMM Y') }}">
                                </td>
                                <td class="text-green">To</td>
                                <td><input readonly id="absc_absent_to" name="absc_absent_to" class="form-control"
                                           type="text"
                                           value="{{  Carbon::parse(  $form->absc_absent_to )->isoFormat('Do MMM Y')  }}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green  ">Place visited and reason for journey</td>
                                <td class="text-green">Place</td>
                                <td><textarea readonly name="absc_visited_place"
                                              class="form-control">{{$form->absc_visited_place}}</textarea></td>
                                <td class="text-green">Reason</td>
                                <td><textarea readonly name="absc_visited_place_reason"
                                              class="form-control">{{$form->absc_visited_reason}}</textarea></td>
                            </tr>
                            <tr>
                                <td class="text-green  ">Allowance Claim per Night</td>
                                <td class="text-green">ZMW</td>
                                <td><input id="absc_allowance_per_night" name="absc_allowance_per_night"
                                           class="form-control" type="text" value="{{$form->absc_allowance_per_night}}"
                                           readonly></td>

                                <td class="text-green"><strong>Total Amount</strong></td>
                                <td class="text-green"><input readonly id="absc_amount1" class="form-control"
                                                              name="absc_amount"
                                                              value="ZMW {{number_format($form->total_night_allowance,)}}" type="text">
                                </td>

                            </tr>
                        </table>
                    </div>

                    <div class="row">
                        <table
                            class="table table-bordered mt-2 mb-4">
                            <tr style="text-align: center" width="w-100">
                                <td colspan="2" width="w-100" class="text-orange"><strong> AMOUNT OF CLAIM FOR
                                        SUBSISTENCE </strong></td>
                            </tr>
                            <tr style="text-align: center" width="w-100">
                                <td colspan="2" width="w-100" class="text-orange"><strong>B. TRAVELLING
                                        EXPENSE </strong></td>
                            </tr>
                            <tr>
                                <td class="text-green text-bold "><strong>Total of Attached Claim (If Any) ZMW:</strong></td>
                                <td>
                                    <input readonly id="absc_absent_to" name="absc_absent_to" class="form-control"
                                           type="text"
                                           value="ZMW {{number_format($form->trex_total_attached_claim ?? 0,2) }}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green  ">Total Amount of Claim (A+B):</td>
                                <td>
                                    <input readonly id="absc_absent_to" name="absc_absent_to" class="form-control"
                                           type="text"
                                           value="ZMW {{number_format(($form->total_claim_amount),2)}}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green  ">Deduct any advance received against these expenses:</td>
                                <td>
                                    <input readonly id="absc_amount1" class="form-control" name="absc_amount"
                                           value="ZMW {{number_format($form->trex_deduct_advance_amount ?? 0,2)}}" type="text">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green  ">Net Amount to be paid:</td>
                                <td>
                                    <input readonly id="absc_amount1" class="form-control" name="absc_amount"
                                           value="ZMW {{number_format( (($form->net_amount_paid ?? 0)),2)}}"
                                           type="text">
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="row">
                        <table
                            class="table table-bordered mt-2 mb-4">
                            <tr>
                                <td>
                                    <div class="row">
                                        <div class="col-3"><label class="text-green">Amount ZMW:</label></div>
                                        <div class="col-9">
                                            <input
                                                value="ZMW {{ number_format( (($form->net_amount_paid ?? 0)),2)  }}"
                                                type="text" name="date" readonly
                                                class="form-control  text-bold ">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-3"><label class="text-green">Allocation Code:</label></div>
                                        <div class="col-9">
                                            <input value="{{$form->allocation_code ?? " " }}" type="text" name="date"
                                                   readonly
                                                   class="form-control  text-bold">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <p><b>Note:</b> The system reference number is <span
                            class="text-primary font-weight-bold">mandatory</span>
                        and is from
                        any of the systems at ZESCO such as a work request number from PEMS, Task
                        number from HQMS, Meeting Number from HQMS, Incident number from IMS etc.
                        giving rise to the expenditure</p>

                </div>
            </div>
            <!-- /.card -->

            {{-- FINANCIAL POSTINGS  --}}
            @if(  ($form->config_status_id == config('constants.subsistence_status.closed')
|| ($form->config_status_id == config('constants.subsistence_status.audited') )
)
               )
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-bold text-orange text-capitalize">Financial Accounts Postings</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="table-responsive">
                                <div class="col-lg-12 ">
                                    <TABLE class="table">
                                        <thead>
                                        <TR class="text-green">
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
                                                <TD><input type="text" id="credited_amount"
                                                           value="{{ number_format($item->creditted_amount ?? 0,2) }}"
                                                           class="form-control amount" readonly>
                                                </TD>
                                                <TD><input type="text"
                                                           value="{{ number_format($item->debitted_amount ?? 0, 2) }}"
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
                        <span class="font-weight-normal">Change was: ZMW {{$form->change ?? 0}}</span>
                    </div>
                </div>
            @endif

            {{-- NEXT PERSONS TO ACT --}}
            @if(  $form->config_status_id != config('constants.subsistence_status.audited')   )
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title text-bold text-orange text-capitalize">Next Person/s to Act</h4>
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
                                    <span class="font-weight-bold">Test:</span><span>{{$item->staff_no}}</span><br>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <span class="font-weight-bold">Next Action:</span><span> {{$form->status->other}}</span>
                        @if ($form->status->id == config('constants.subsistence_status.security_approved'))
                            <span class="font-weight-bold text-red"> Note:</span><span class="text-red"> Export Data to Excel and Import in Oracle Financial's using ADI</span>
                        @endif
                    </div>
                </div>
            @endif

            {{--  QUOTATION FILES--}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-bold text-orange text-capitalize">Attached Files</h4>
                    @if( ($user->type_id == config('constants.user_types.developer')  || (
                                 $user->profile_id ==  config('constants.user_profiles.EZESCO_002')
                               &&  $user->id  == $form->created_by )
                             )
                               )
                        <a class="float-right" href="#" data-toggle="modal" data-sent_data="{{$form}}"
                           data-target="#modal-add-attached_file">Add File</a>
                    @endif
                </div>
                <div class="card-body" style="width:100%;  ">
                    <div class="row">
                        @foreach($attached_files as $item)
                            <div class="col-6">
                                <iframe id="{{$item->id}}" src="{{asset('storage/subsistence_files/'.$item->name)}}"
                                        style="width:100%; height: 1000px " title="{{$item->name}}">
                                </iframe>
                                <span >Size:{{number_format( $item->file_size, 2) }}MB  Name: {{$item->name}} </span>
                                <span> | </span>
                                <a href="{{asset('storage/subsistence_files/'.$item->name)}}">View</a>
                                @if( ($user->type_id == config('constants.user_types.developer')  || (
                                    $user->profile_id ==  config('constants.user_profiles.EZESCO_002')
                                  &&  $user->id  == $form->created_by )
                                )
                                  )
                                    <span> | </span>
                                    <a href="#" data-toggle="modal" data-sent_data="{{$item}}"
                                       data-target="#modal-change">Edit</a>

                                @endif
{{--                                <span> | </span>--}}
{{--                                <span  onclick="ZoomiframeScale() " >--}}
{{--                                <i class="fa fa-plus" > </i>--}}
{{--                                </span >--}}
{{--                                <span> | </span>--}}
{{--                                <span onclick="ZoomOutIframe() ">--}}
{{--                                    <i class="fa fa-minus" > </i>--}}
{{--                                </span>--}}

                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>

            {{--  FORM PPROVALS--}}
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title text-bold text-orange text-capitalize">Approvals</h4>  <span
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
                            <TR class="text-white text-bold text-uppercase bg-gradient-green">
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
                    {{--  CLAIMANT REQUEST FOR REFUND--}}
                    @if(  \Illuminate\Support\Facades\Auth::user()->id == $form->created_by )
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

                    {{--  CLAIMANT EDIT--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_002')
                         &&  $form->config_status_id == config('constants.subsistence_status.new_application')
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

                    {{--  DESTINANTION HOD APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_004')
                         &&  $form->config_status_id == config('constants.subsistence_status.destination_approval')

                      )
                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                           document.getElementById('show-form'+{{$trip->id}}).submit();">
                                        <span class="btn btn-outline-success text-center  text-orange "> Click to Open the Trip Form </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif



                    {{--  HOD APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_004')
                         &&  $form->config_status_id == config('constants.trip_status.trip_authorised')
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

                    {{--  HOD APPROVAL 2--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_004')
                         &&  $departmental_hod ==  true )

                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a href="{{ route('logout') }}" class="btn btn-outline-warning"
                                       onclick="event.preventDefault();
                                           document.getElementById('show-form'+{{$trip->id}}).submit();">

                                        OPEN TRIP {{ $trip->code }} TO ACT
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endif


                    @if($departmental)
                        {{-- DEPARTMENTAL HR APPROVAL--}}
                        @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_009')
                             &&  $form->config_status_id == config('constants.trip_status.hod_approved_trip')
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

                        {{-- SNR MANAGER--}}
                        @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_015')
                             &&  $form->config_status_id == config('constants.trip_status.hr_approved_trip')
                             &&  $form->user_unit->dm_code == $user->profile_job_code
                             &&  $form->user_unit->dm_unit == $user->profile_unit_code
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

                        {{-- CHIEF ACCOUNTANT APPROVAL--}}
                        @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_007')
                             &&  $form->config_status_id == config('constants.subsistence_status.station_mgr_approved')
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
                    @endif

                    {{-- HR APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_009')
                         &&  $form->config_status_id == config('constants.subsistence_status.hod_approved')
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


                    {{--  HR APPROVAL 2--}}
                    @if( $user->profile_id ==   config('constants.user_profiles.EZESCO_009')
                         &&  $form->config_status_id == config('constants.trip_status.hod_approved_trip')
                         &&  $departmental_hod ==  true )

                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a href="{{ route('logout') }}" class="btn btn-outline-warning"
                                       onclick="event.preventDefault();
                                           document.getElementById('show-form'+{{$trip->id}}).submit();">
                                        OPEN TRIP {{ $trip->code }} TO ACT
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endif



                    {{-- SNR MANAGER--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_015')
                         &&  $form->config_status_id == config('constants.subsistence_status.hr_approved')
                         &&  $form->user_unit->dm_code == $user->profile_job_code
                         &&  $form->user_unit->dm_unit == $user->profile_unit_code
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



                    {{--  SNR MANAGER 2--}}
                    @if( $user->profile_id ==   config('constants.user_profiles.EZESCO_015')
                         &&  $form->config_status_id == config('constants.trip_status.hr_approved_trip')
                         &&  $departmental_hod ==  true )

                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a href="{{ route('logout') }}" class="btn btn-outline-warning"
                                       onclick="event.preventDefault();
                                           document.getElementById('show-form'+{{$trip->id}}).submit();">
                                        OPEN TRIP {{ $trip->code }} TO ACT
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endif

                    {{-- CHIEF ACCOUNTANT APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_007')
                         &&  $form->config_status_id == config('constants.subsistence_status.hr_approved')
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

                    {{-- PRE-AUDIT APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_011')
                        &&  $form->config_status_id == config('constants.subsistence_status.chief_accountant')
                        &&  $form->user_unit->audit_unit == $user->profile_unit_code
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
                                                value='Approved'>AUDITED
                                        </button>
                                        <button id="btnSubmit_reject" type="submit" name="approval"
                                                class="btn btn-outline-danger ml-2 p-2  "
                                                value='Rejected'>QUERIED
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

                    {{-- FUNDS DISBURSEMNET APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_014')
                         &&  $form->config_status_id == config('constants.subsistence_status.pre_audited')
                         &&  $form->user_unit->expenditure_unit == $user->profile_unit_code
                       )
                        <div class="">
                            <div class="row">
                                    <div class="col-1">
                                        <label class="form-control-label">Allocation Code</label>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                            <input value=" " type="text" id="allocation_code"
                                                   name="allocation_code"
                                                   class="form-control text-orange text-bold">
                                        </div>
                                    </div>
                                </div>
                            <h5 class="text-center">Please Update the Accounts </h5>
                            <h6 class="text-center">(Total Amount : ZMW {{$form->net_amount_paid}}) </h6>
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
                                                                       value="Place Visited: {{$form->absc_visited_place ?? ""}}. Reason: {{$form->absc_visited_reason ?? ""}}. Number of Days:{{$form->getNumdaysAttribute() ?? ""}} days. "
                                                                       class="form-control amount"
                                                                       placeholder="Select Item/s   "
                                                                       id="account_items1">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-3">
                                                            <select name="credited_account[]" id="credited_account"
                                                                    required
                                                                    class="form-control amount">
                                                                @if($form->user->affiliated_union  == null)
                                                                    <option
                                                                        value="{{$accounts->where('id', config('constants.non_rep_subsistence_account_id'))->first()->code}}">
                                                                        {{$accounts->where('id', config('constants.non_rep_subsistence_account_id'))->first()->name}}
                                                                        :{{$accounts->where('id', config('constants.non_rep_subsistence_account_id'))->first()->code}}
                                                                    </option>
                                                                @endif

                                                                @if($form->user->affiliated_union  != null)
                                                                    <option
                                                                        value="{{$accounts->where('id', config('constants.rep_subsistence_account_id'))->first()->code}}">
                                                                        {{$accounts->where('id', config('constants.rep_subsistence_account_id'))->first()->name}}
                                                                        :{{$accounts->where('id', config('constants.rep_subsistence_account_id'))->first()->code}}
                                                                    </option>
                                                                @endif
                                                                    @foreach($accounts as $account)
                                                                        <option
                                                                            value="{{$account->code}}">{{$account->name}}</option>
                                                                    @endforeach

                                                            </select>
                                                        </div>

                                                        <div class="col-3">
                                                            <input type="number" id="credited_amount"
                                                                   name="credited_amount[]"
                                                                   onchange="getvalues()" step="any"
                                                                   class="form-control amount"
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
                                                <TD>
                                                    <select name="tax[]" id="debited_account"
                                                            required
                                                            class="form-control amount is-warning">
                                                        @foreach($taxes as $tax)
                                                            <option value="{{$tax->id}}">{{$tax->name}}</option>
                                                        @endforeach
                                                    </select>
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
                                        Sorry, You can not submit because Credited Accounts total does not equal to the total payment requested <strong>(ZMK {{$form->net_amount_paid}}
                                                )</strong>
                                   </span>
                                </div>
                                <div id="submit_possible" class="col-12 text-center">
                                    <div class="col-12 text-center ">
                                        <div id="divSubmit_show">
                                            <button id="btnSubmit_approve" type="submit" name="approval"
                                                    class="btn btn-outline-success mr-2 p-2  " title="Only click this Button to confirm that you will or have given out the money"
                                                    value='Approved'>FUNDS DISBURSED
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
                        </div>
                    @endif

                    {{-- FUNDS ACKNOWELEDGMENT APPROVAL--}}
                    @if( $form->config_status_id == config('constants.subsistence_status.funds_disbursement')
                         &&  $form->claimant_staff_no == $user->staff_no
                          )
                        <div class="">
                            <hr>
                            <div class="row">
                                <textarea hidden class="form-control" rows="2" name="reason"
                                          required> Funds Received</textarea>
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-2">
                                            <label class="form-control-label text-right">Confirmation File (Optional)</label>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <input type="file" title="Confirmation of money received e.g Screenshot of money receipt message" class="form-control" multiple name="confirmation[]"
                                                       id="confirmation" >
                                            </div>
                                        </div>
                                        <div class="col-1">
                                            <label class="form-control-label text-right">Paid Account</label>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <input type="text" title="Confirmation of account number which received the funds" class="form-control" placeholder="Enter Account Number" name="account_number"
                                                       id="account_number" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 text-left ">
                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                title="Only Click This Button to confirm that you have actually received the money"
                                                value='Approved'>FUNDS RECEIVED
                                        </button>
                                        <button style="display: none" id="btnSubmit_reject" type="submit"
                                                name="approval"
                                                class="btn btn-outline-danger mr-2 p-2  "
                                                value='Rejected'>FUNDS NOT RECEIVED
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

                    {{-- AUDIT APPROVAL--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_011')
                        &&  $form->config_status_id == config('constants.subsistence_status.await_audit')
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

                    {{-- QUERIED RESOLUTION--}}
                    @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_014')
                         &&  $form->config_status_id == config('constants.subsistence_status.queried')
                         &&  $form->user_unit->expenditure_unit == $user->profile_unit_code
                        )
                        <div class="">
                            <div class="row">
                                <div class="col-lg-10 p-2 mt-3 ">
                                    <div class="row">
                                        <div class="col-1">
                                            <label class="form-control-label">Total Change</label>
                                        </div>
                                        <div class="col-5">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="number" step="any" onchange="showChange()" class="form-control"
                                                           name="change" id="change" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-1 offset-1">
                                            <label class="form-control-label text-right">Upload Files</label>
                                        </div>
                                        <div class="col-4">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <div class="input-group">
                                                        <input type="file" class="form-control" multiple name="receipt[]"
                                                               id="receipt" required>
                                                    </div>
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
                                                    <TD width="30%">
                                                        <div class="form-group">
                                                            <input list="items_list" type="text" name="account_item"
                                                                   class="form-control amount"
                                                                   placeholder="Change Description " id="account_item1">
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
                                                    <TD>
                                                        <select name="tax" id="debited_account"
                                                                required
                                                                class="form-control amount is-warning">
                                                            <option value="">--Choose--</option>
                                                            @foreach($taxes as $tax)
                                                                <option value="{{$tax->id}}">{{$tax->name}}</option>
                                                            @endforeach
                                                        </select>
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
                            </div>
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

{{--                        <div class="">--}}
{{--                            <hr>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-10">--}}
{{--                                    <div class="row">--}}
{{--                                        <div class="col-1">--}}
{{--                                            <label class="form-control-label">Reason</label>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-11">--}}
{{--                                            <textarea class="form-control" rows="2" name="reason" required></textarea>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="col-2 text-center ">--}}
{{--                                    <div id="divSubmit_show">--}}
{{--                                        <button id="btnSubmit_approve" type="submit" name="approval"--}}
{{--                                                class="btn btn-outline-success mr-2 p-2  "--}}
{{--                                                value='Resolve'>RESOLVE QUERY--}}
{{--                                        </button>--}}
{{--                                        <button style="display: none" id="btnSubmit_reject" type="submit"--}}
{{--                                                name="approval"--}}
{{--                                                class="btn btn-outline-success mr-2 p-2  "--}}
{{--                                                value='Rejected'>RESOLVE QUERY--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                    <div id="divSubmit_hide">--}}
{{--                                        <button disabled class="btn btn-outline-success mr-2 p-2  "--}}
{{--                                                value='Approved'>Processing. Please wait...--}}
{{--                                        </button>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
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
                <form id="change_form" method="post"
                      action="{{route('attached.file.change')}}" enctype="multipart/form-data">
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
    <div class="modal fade" id="modal-add-attached_file">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Add Quotation File</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- form start -->
                <form id="qoutation_form" method="post"
                      action="{{route('attached.file.add')}} " enctype="multipart/form-data">
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

    <!-- ADD -->
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
                <form id="receipt_form" method="post"
                      action="{{route('attached.file.add')}} " enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-12">
                                <div class="form-group">
                                    <label>Add File</label>
                                    <input type="file" class="form-control" id="add_file2" name="add_file"
                                           placeholder="Enter profile name" required>
                                    <input hidden class="form-control" id="item_type2" name="file_type"
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


        //ROUND OFF FUNCTION
        Number.prototype.round = function(places) {
            return +(Math.round(this + "e+" + places)  + "e-" + places);
        }

        function getvalues() {
            var inps = document.getElementsByName('credited_amount[]');
            var debiteds = document.getElementsByName('debited_amount[]');
            var total = 0;
            for (var i = 0; i < inps.length; i++) {
                var inp = inps[i];
                total = total + parseFloat(inp.value || 0);
                total =  total.round(2);

                //get the related field
                var debited = debiteds[i];
                //set value
                debited.value = parseFloat(inp.value || 0);
            }

            var net_amount_paid = {!! json_encode($form->net_amount_paid) !!};

            if (!isNaN(total)) {

                //check if petty cash accounts is equal to absc_amount
                if (total == net_amount_paid) {
                    $('#submit_possible').show();
                    $('#submit_not_possible').hide();
                } else if (total < net_amount_paid) {
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
                total =  total.round(2);

                //get the related field
                var debited = debiteds[i];
                //set value
                debited.value = parseFloat(inp.value || 0);
            }

            var net_amount_paid = {!! json_encode($form->net_amount_paid) !!};

            if (!isNaN(total)) {

                //check if petty cash accounts is equal to net_amount_paid
                if (total == net_amount_paid) {
                    $('#submit_possible').show();
                    $('#submit_not_possible').hide();
                } else if (total < net_amount_paid) {
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

                //check if petty cash accounts is equal to absc_amount
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

            var url_start = '{{asset('storage/subsistence_files')}}';
            var path = "public/subsistence_files";
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
        $('#modal-add-attached_file').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes

            var path = "public/subsistence_files";
            var type = {!! config('constants.file_type.subsistence') !!};
            var form_id = {!! config('constants.eforms_id.subsistence') !!};
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


    <script>
        function ZoomOutIframe(){
            $('#iframe').css({
                'height':'90%',
                'width': '95%',
                '-ms-zoom': '1',
                '-moz-transform': 'scale(1)',
                '-moz-transform-origin': '0 0',
                '-o-transform': 'scale(1)',
                '-o-transform-origin':' 0 0',
                '-webkit-transform': 'scale(1)',
                '-webkit-transform-origin': '0 0'
            });
        }
        function ZoomiframeScale(){
            $('#iframe').css({
                'height':'280%',
                'width': '300%',
                '-ms-zoom': '0.3',
                '-moz-transform': 'scale(0.3)',
                '-moz-transform-origin': '0 0',
                '-o-transform': 'scale(0.3)',
                '-o-transform-origin':' 0 0',
                '-webkit-transform': 'scale(0.3)',
                '-webkit-transform-origin': '0 0'
            });
        }
    </script>

@endpush



