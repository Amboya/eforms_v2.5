@extends('layouts.eforms.hotel-accommodation.master')


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
                    <h1 class="m-0 text-dark">Hotel Accommodation Detail [ {{ $form->code }} ]</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('hotel.accommodation.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Hotel Accommodation </li>
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

        <form id="show_form" name="db1" action="{{route('hotel.accommodation.approve')}}" method="post"
              enctype="multipart/form-data">
        @csrf

        <!-- Default box -->
            <div class="card">
                <div class="card-body">
                    <span
                        class="badge badge-{{$form->status->html ?? "default"}}">{{$form->status->name ?? "none"}}</span>
                    <input type="hidden" name="id" value="{{ $form->id}}" readonly required>
                    <input type="hidden" name="sig_date" value=" {{date('Y-m-d H:i:s')}}" readonly required>

                    <table border="2" width="100%" cellspacing="0" cellpadding="0" align="Centre"
                           class="mt-2 mb-4">
                        <thead>
                        <tr style="border: 1px">
                            <th width="33%" class="text-center"><a href="#"><img
                                        src="{{ asset('dashboard/dist/img/zesco1.png')}}" title="ZESCO" alt="ZESCO"
                                        width="30%"></a></th>
                            <th colspan="4" class="text-center">Hotel Accommodation Requisition Form</th>
                            <th colspan="1">Doc Number:<br>CO.14900.FORM.0003<br>Version: 3</th>
                        </tr>
                        </thead>
                    </table>

                    <table border="0" width="100%" cellspacing="0" cellpadding="0" align="Centre">
                        <tr>
                            <td height="5" colspan="6"></td>
                        </tr>

                        <tr>
                            <td><label>Name:</label></td>
                            <td><input type="text" name="staff_name" class="form-control"  value="{{$form->staff_name}}" readonly></td>
                            <td class="text-center"><label>Man No:</label></td>
                            <td><input type="text" name="staff_no"
                                       class="form-control" value="{{$form->staff_no}}"
                                       readonly></td>
                            <td class="text-center"><label>Grade:</label></td>
                            <td><input type="text" name="grade" class="form-control"
                                       value="{{$form->grade}}" readonly></td>

                        </tr>
                        <tr>
                            <td><label>Directorate:</label></td>
                            <td><input type="text" name="directorate"
                                       class="form-control" value="{{$form->staff_no}}"
                                       readonly></td>
                            <td class="text-center"><label>Hotel:</label></td>
                            <td><input type="text" required name="hotel" class="form-control" value= "{{$form->hotel}}" readonly placeholder="Enter Hotel Name"></td>
                        </tr>
                        <tr>
                            <td class="text-"><label>Cost Centre:</label></td>
                            <td><input type="text" name=""
                                       class="form-control" value="{{$form->cost_centre}}"
                                       readonly></td>
                            <td class="text-"><label>SYS Ref No.</label></td>
                            <td><input type="text" required name="ref_no" class="form-control" value= "{{$form->ref_number}}"
                                       readonly></td>

                        </tr>
                        <tr>
                            <td height="10" colspan="6"></td>
                        </tr>
                        <tr>
                            <td height="15"></td>
                        </tr>
                        <tr>
                        <tr>
                            <td><label>Purpose of Journey</label></td>
                            <td><input type="text" required name="purpose_of_journey" value= "{{$form->purpose_of_journey}}" class="form-control" readonly ></td>
                        </tr>

                        <tr>
                            <td><label>Estimated Period of Stay</label></td>
                            <td><input type="text" required name="estimated_period_of_stay" value= "{{$form->estimated_period_of_stay}}" class="form-control" readonly></td>
                            <td class="text-center"><label>Estimated Cost:</label></td>
                            <td><input type="text" required name="estimated_cost" class="form-control" value= "{{$form->estimated_cost}}" readonly></td>
                        </tr>
                        <tr>
                            <td><label>Employee Name:</label></td>
                            <td><input type="text" name="employee_name" class="form-control"
                                       value="{{$form->staff_name}}" readonly></td>
                            <td class="text-center"><label>Signature:</label></td>
                            <td><input type="text" name="employee_staff_no"
                                       class="form-control" value="{{$form->staff_no}}"
                                       readonly></td>
                            <td class="text-center"><label>Date:</label></td>
                            <td><input type="date" required name="claim_date" class="form-control" value ="{{date('Y-m-d')}}"readonly></td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td colspan="6"><p><b>Imprest Upon Being Accomodated in a Hotel:</b></p></td>
                        </tr>
                        <tr>
                            <td><label>Amount Claimed ZMW:</label></td>
                            <td><input type="text" required name="amount_claimed" class="form-control" value= "{{$form->amount_claimed}}" readonly></td>
                            <td class="text-center"><label>Being ZMW:</label></td>
                            <td><input type="text" required name="amount" class="form-control" value= "{{$form->amount}}" readonly> </td>
                            <td class="text-center"><label>Per Night:</label></td>
                            <!--                <td><input type="text" name="" class="form-control"></td>-->
                        </tr>
                        <tr>
                            <td><label>Name of Chief Accountant</label></td>
                            <td><input type="text" required name="chief_accountant_name" value= "{{$form->chief_accountant_name}}" class="form-control" readonly></td>
                            <td class="text-center"><label>Sign:</label></td>
                            <td><input type="text" name="chief_staff_no" class="form-control" value= "{{$form->chief_accountant_staff_no}}" readonly></td>
                            <td class="text-center"><label>Date:</label></td>
                            <td><input type="text" required name="chief_accountant_date" value= "{{$form->chief_accountant_date}}" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <td><label>Name of HOD:</label></td>
                            <td><input type="text" required name="hod_name"  value= "{{$form->hod_name}}" class="form-control" readonly></td>
                            <td class="text-center"><label>Signature:</label></td>
                            <td><input type="text" required name="hod_staff_no" class="form-control" value= "{{$form->hod_staff_no}}" readonly></td>
                            <td class="text-center"><label>Date:</label></td>
                            <td><input type="text" required name="hod_authorised_date" value= "{{$form->hod_authorised_date}}" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <td><label>Approved by Director:</label></td>
                            <td><input type="text" required name="director_name"  value= "{{$form->director_name}}" class="form-control" readonly></td>
                            <td class="text-center"><label>Signature:</label></td>
                            <td><input type="text" required name="director_staff_no" value= "{{$form->director_staff_no}}" class="form-control" readonly></td>
                            <td class="text-center"><label>Date:</label></td>
                            <td><input type="text" required name="director_authorised_date" value= "{{$form->director_authorised_date}}" class="form-control" readonly></td>
                        </tr>
                        <tr>
                            <td height="10" colspan="6"></td>
                        </tr>
                        <tr>
                            <td height="15"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p><b>Note:</b> To be Filled in Duplicate.<br>
                                    First Copy to the Chief Accountant for Payment.<br>
                                    Second Copy to Finance for Order Processing<br>
                                </p></td>
                        </tr>
                        <tr>
                            <td colspan="6"><p><b>Note:</b> The system reference number is mandatory. It is a
                                    number from any of the systems at ZESCO such as a work request number from
                                    PEMS, Task number from HQMS,Meeting Number from HQMS, Incident Number from
                                    IMS, DCS etc. giving rise to the expenditure</p></td>

                        </tr>
                        {{--                    <tr>--}}
                        {{--                        <td colspan="6" class="text-center"><input type="submit" value="submit" name="submit_form"--}}
                        {{--                                                                   class="btn btn-outline-success"></td>--}}
                        {{--                    </tr>--}}

                    </table>

                </div>
            </div>
            <!-- /.card -->

            {{-- NEXT PERSONS TO ACT --}}
            @if(  $form->status_id != config('constants.hotel_accommodation_status.closed')   )
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
                        @if ($form->status->id == config('constants.hotel_accommodation_status.security_approved'))
                            <span class="font-weight-bold text-red"> Note:</span><span class="text-red"> Export Data to Excel and Import in Oracle Financial's using ADI</span>
                        @endif
                    </div>
                </div>
            @endif

            {{--  invoice FILES--}}
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Invoice Files</h4>
                </div>
                <div class="card-body" style="width:100%; height: 900px ">
                    <div class="row">
                        @foreach($quotations as $item)
                            <div class="col-12">
                                <iframe id="{{$item->id}}" src="{{asset('storage/hotel_accommodation_quotation/'.$item->name)}}"
                                        style="width:100%; height: 850px" title="{{$item->name}}"></iframe>
                                <span>{{$item->file_size}}MB {{$item->name}} </span>
                                <a href="{{asset('storage/hotel_accommodation_quotation/'.$item->name)}}">View</a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>

            {{--  RECEIPT FILES - ONLY WHEN FORM HAS BEEN CLOSED--}}
            @if(  $form->status_id == config('constants.hotel_accommodation_status.closed')
            ||  $form->status_id == config('constants.hotel_accommodation_status.audited')
            ||  $form->status_id == config('constants.hotel_accommodation_status.queried')    )
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Receipt Files</h4>
                    </div>
                    <div class="card-body" style="width:100%; height: 900px ">
                        <div class="row">
                            @foreach($receipts as $item)
                                <div class="col-12">
                                    <iframe id="{{$item->id}}"
                                            src="{{asset('storage/hotel_accommodation_receipt/'.$item->name)}}"
                                            style="width:100%; height: 840px " title="{{$item->name}}"></iframe>
                                    <span>{{$item->file_size}}MB {{$item->name}} </span>
                                    <a href="{{asset('storage/hotel_accommodation_receipt/'.$item->name)}}">View</a>
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
                                <TD>From Form <br>Submission </TD>
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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002')
                         &&  $form->status_id == config('constants.hotel_accommodation_status.new_application')
                         &&  Auth::user()->id  == $form->created_by)
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
                                                value='Cancelled'>CANCEL HOTEL ACCOMMODATION
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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_004')
                         &&  $form->config_status_id == config('constants.hotel_accommodation_status.new_application')

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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_009')
                         &&  $form->config_status_id == config('constants.hotel_accommodation_status.hod_approved')
                         &&  $form->user_unit->hrm_code == Auth::user()->profile_job_code
                         &&  $form->user_unit->hrm_unit == Auth::user()->profile_unit_code
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

{{--                    <h1> {{ Auth::user()->profile_id }} : {{config('constants.user_profiles.EZESCO_007') }}</h1>--}}
{{--                    <h1> {{$form->config_status_id}} : {{ config('constants.hotel_accommodation_status.hod_approved') }} </h1>--}}

                    {{--  CHIEF ACCOUNTANT APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_007')
                         &&  $form->config_status_id == config('constants.hotel_accommodation_status.hod_approved')

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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_014')
                         &&  $form->config_status_id == config('constants.hotel_accommodation_status.chief_accountant')
                         &&  $form->user_unit->expenditure_unit == Auth::user()->profile_unit_code
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
                                                                        <option  >{{$item->name}} : (ZMK {{$item->amount}})</option>
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
                                                                    @if($account->id  ==  config('constants.hotel_accommodation_account_id')  )
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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002')
                         &&  $form->status_id == config('constants.hotel_accommodation_status.funds_disbursement')
                         &&  $form->claimant_staff_no == Auth::user()->staff_no
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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_013')
                         &&  $form->status_id == config('constants.hotel_accommodation_status.funds_acknowledgement')
                         &&  $form->user_unit->security_unit == Auth::user()->profile_unit_code
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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_014')
                         &&  $form->status_id == config('constants.hotel_accommodation_status.security_approved')
                         &&  $form->user_unit->expenditure_unit == Auth::user()->profile_unit_code
                       )
                        <div class="">
                            <h6 class="text-center">The Updated Accounts</h6>
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="table-responsive">
                                    <div class="col-lg-12 ">
                                        <TABLE class="table">
                                            <thead>
                                            <TR>
                                                <TD>Account</TD>
                                                <TD>Credited Amount</TD>
                                                <TD>Debited Amount</TD>
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
                                                               value="{{$item->creditted_amount}}"
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
                                                                @if($account->id  ==  config('constants.hotel_accommodation_account_id')  )
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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_011')   &&
                        $form->status_id == config('constants.hotel_accommodation_status.closed')   )
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

                </div>
                <!-- /.card-footer-->
            </div>

        </form>

    </section>
    <!-- /.content -->
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

@endpush
