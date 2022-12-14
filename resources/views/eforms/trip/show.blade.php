@extends('layouts.eforms.trip.master')


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
                    <h1 class="m-0 text-dark text-uppercase text-orange ">TRIP CODE <span
                            class="text-green ">{{ $form->code }}</span></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('trip.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Trip Detail</li>
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

            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6 invoice-col">
                        <span
                            class="badge badge-{{$form->status->html ?? "default"}}">{{$form->status->name ?? "none"}}</span><br>
                        <b class='text-orange'>Name:</b> <span class='text-green'>{{ $form->name}}</span><br>
                        <b class='text-orange'>Date From:</b> <span
                            class='text-green'>{{  Carbon::parse(  $form->date_from )->isoFormat('Do MMM Y')  }}</span><br>
                        <b class='text-orange'>Date To:</b> <span
                            class='text-green'>{{ Carbon::parse(  $form->date_to )->isoFormat('Do MMM Y')  }}</span><br>
                        <b class='text-orange'>Members:</b> <span class='text-green'>Invited {{ $form->invited}} and {{sizeof($form->members)}} have
                        subscribed.</span><br>
                    </div>
                    <div class="col-sm-6 invoice-col">

                        <b class='text-orange'>Destination:</b><span class='text-green'> {{ $form->destination}} </span><br>
                        <b class='text-orange'>Description:</b><span
                            class='text-green'>  {{ $form->description}} </span><br>
                        <b class='text-orange'>Created By:</b> <span
                            class=" text-green">{{ $form->initiator_name}}  </span> <br>
                        <b class='text-orange'>HOD's Cost Center:</b> <span
                            class=" text-green">{{ $form->user_unit->user_unit_description ?? "" }} : {{ $form->user_unit->user_unit_code ?? ""}}  : {{ $form->user_unit->user_unit_bc_code ?? ""}}  : {{ $form->user_unit->user_unit_cc_code ?? ""}}  </span>
                        <br>
                        <b class='text-orange'>Created At:</b> <span
                            class=" text-green">{{ $form->created_at}} : that is {{ $form->created_at->diffForHumans()}} </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" value="{{ $form->id}}" readonly required>
                <input type="hidden" name="sig_date" value=" {{date('Y-m-d H:i:s')}}" readonly required>

                <table border="1" width="98.5%" cellspacing="0" cellpadding="0" align="Centre"
                       class=" m-3 mr-3 ">
                    <thead>
                    <tr class="border-success">
                        <th width="33%" colspan="1" class="text-center"><a href="#"><img
                                    src="{{ asset('dashboard/dist/img/zesco1.png')}}" title="ZESCO" alt="ZESCO"
                                    width="25%"></a></th>
                        <th width="33%" colspan="4" class="text-center">TRIP FORM</th>
                        <th width="34%" colspan="1" class="p-3">Doc Number:<br>CO.14900.FORM.00006<br>Version: 2
                        </th>
                    </tr>
                    </thead>
                </table>

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="table-responsive">
                        <div class="col-lg-12 ">
                            <table class="table table-striped">
                                <thead class="table text-white text-bold text-uppercase bg-gradient-green ">
                                <tr>
                                    <th></th>
                                    <th>MAN NO</th>
                                    <th>NAME</th>
                                    <th>HOD SIGNATURE</th>
                                    <th>DATE OF DEPARTURE</th>
                                    <th>DAYS CLAIMED</th>
                                    <th>M/V NUMBER</th>
                                    <th>DESTINATION</th>
                                    <th>ACTUAL DAYS TAKEN</th>
                                    <th>
                                        DESTINATION APPROVALS
                                    </th>

                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                                </thead>
                                @foreach($form->members as $item)
                                    <TR>
                                        <td></td>
                                        <td>
                                            {{$item->claimant_staff_no ?? ""}}
                                        </td>
                                        <td>
                                            <a href="" data-toggle="modal"
                                               data-sent_data="{{$item}}"
                                               data-target="#modal-approve-member">
                                                {{$item->claimant_name ?? ""}}
                                            </a>
                                        </td>
                                        <td>
                                            {{$item->initiator_name ?? ""}} <br> {{$item->initiator_staff_no ?? ""}}
                                        </td>
                                        <td>
                                            {{ Carbon::parse(  $form->date_from )->isoFormat('Do MMM Y') }}
                                        </td>
                                        <td>
                                            {{$item->num_days ?? "0"}} Days
                                        </td>
                                        <td>
                                            {{$item->m_v_number ?? "" }}
                                        </td>
                                        <td>
                                            {{$item->absc_visited_place ?? "" }}
                                        </td>
                                        <td>
                                            {{$item->actual_days ?? "" }} Days
                                        </td>
                                        <td>
                                            <table class="table-sm" border="0">
                                                <tr>
                                                    <th>DATE ARRIVED</th>
                                                    <th>DATE LEFT</th>
                                                    <th>REGIONAL, AREA, BRANCH OR LINE MANAGER???S SIGNATURE</th>
                                                </tr>
                                                @foreach($item->destinations as $dest_app)
                                                    <tr>
                                                        <td>
                                                            @if( $dest_app == null)
                                                                -  <br>
                                                            @else
                                                                @if( $dest_app->date_to  == null)
                                                                    {{$dest_app->user_unit->user_unit_description ?? ""}}
                                                                    <br>
                                                                @else
                                                                    {{ Carbon::parse(  $dest_app->date_from )->isoFormat('Do MMM Y') }}
                                                                    <br>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if( $dest_app == null)
                                                                -  <br>
                                                            @else
                                                                @if( $dest_app->date_to  == null)
                                                                    {{$dest_app->user_unit->user_unit_code ?? ""}} <br>
                                                                @else
                                                                    {{ Carbon::parse(  $dest_app->date_to )->isoFormat('Do MMM Y') }}
                                                                    <br>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{$dest_app->approver->name  ?? "" }}
                                                            - {{$dest_app->approver->job_code  ?? "" }} <br>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                        <td>
                                            <div class="row">
                                                @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_002')  )
                                                    <div class="col-sm-12 mt-0">
                                                        @if($user->staff_no ==  $item->claimant_staff_no)
                                                            <a href="{{ route('logout') }}"
                                                               title="Open subsistence form"
                                                               class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                               onclick="event.preventDefault();
                                                                   document.getElementById('show-form1'+{{$item->id}}).submit();">
                                                    <span
                                                        class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                                            </a>
                                                        @else
                                                            <button disabled
                                                                    class="btn btn-sm bg-{{$item->status->html ?? "default"}}">
                                                    <span title="No Access to this subsistence form"
                                                          class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                                            </button>
                                                        @endif

                                                        @else
                                                            <a href="{{ route('logout') }}"
                                                               title="Open subsistence form"

                                                               onclick="event.preventDefault();
                                                                   document.getElementById('show-form1'+{{$item->id}}).submit();">
                                                    <span
                                                        class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <form id="show-form1{{$item->id}}"
                                                          action="{{ route('subsistence.show', $item->id) }}"
                                                          method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="row">
                                                @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_002')  )
                                                    @if($user->staff_no ==  $item->claimant_staff_no)
                                                        <div class="col-sm-12 mt-0">
                                                            <button
                                                                class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                                title="Trip Approvals List"
                                                                data-toggle="modal"
                                                                data-sent_data="{{$item}}"
                                                                data-target="#modal-approve-member">
                                                                <i class="fa fa-file"></i> Track
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-12 mt-2">
                                                            <a href="{{ route('logout') }}"
                                                               title="Open subsistence form"
                                                               class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                               onclick="event.preventDefault();
                                                                   document.getElementById('show-form1'+{{$item->id}}).submit();">
                                                                <i class="fa fa-file"></i> Sub Form
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="col-sm-12 mt-0">
                                                            <button
                                                                class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                                title="Trip Approvals List"
                                                                data-toggle="modal" disabled
                                                                data-sent_data="{{$item}}"
                                                                data-target="#modal-approve-member">
                                                                <i class="fa fa-file"></i> Track
                                                            </button>
                                                        </div>
                                                        <div class="col-sm-12 mt-2">
                                                            <button disabled title="No Access to this subsistence form"
                                                                    class="btn btn-sm bg-{{$item->status->html ?? "default"}}">
                                                                <i class="fa fa-file"></i> Open
                                                            </button>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="col-sm-12 mt-0">
                                                        <button
                                                            class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                            title="Trip Approvals List"
                                                            data-toggle="modal"
                                                            data-sent_data="{{$item}}"
                                                            data-target="#modal-approve-member">
                                                            <i class="fa fa-file"></i> Track
                                                        </button>
                                                    </div>
                                                    <div class="col-sm-12 mt-2">
                                                        <a href="{{ route('logout') }}" title="Open subsistence form"
                                                           class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                           onclick="event.preventDefault();
                                                               document.getElementById('show-form1'+{{$item->id}}).submit();">
                                                            <i class="fa fa-file"></i> Open
                                                        </a>
                                                    </div>
                                                @endif
                                                <form id="show-form1{{$item->id}}"
                                                      action="{{ route('subsistence.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </td>
                                    </TR>
                                @endforeach
                            </TABLE>
                        </div>
                        <button
                            class="btn btn-sm bg-default"
                            title="Invited Trip Members"
                            data-toggle="modal"
                            data-target="#modal-trip-members">
                            <i class="fa fa-user-friends"></i> {{$all_inv->count()}} Invited members
                        </button>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->


            <div class="card-footer">
                <br>
                <div class="row">
                    <div class="col-sm-3 ml-lg-3  ">
                        @if( $form->status->id ?? 0 != config('constants.trip_status.trip_closed'))
                            @if($list_inv != null)
                                @if( $list_inv->status_id == config('constants.trip_status.pending'))
                                    <form name="db1"
                                          action="{{route('subsistence.subscribe', ['trip' => $form, 'invitation'=> $list_inv])}}"
                                          method="post" enctype="multipart/form-data">
                                        @csrf
                                        @if($pending == 0)
                                            <button type="submit" name="approval"
                                                    class="btn btn-outline-success mr-2 p-2  "
                                                    title="Click here to accept invitation to be part of this trip"
                                                    value='Subscribe'>Raise Subsistence
                                            </button>
                                        @else
                                            <button disabled name="approval"
                                                    class="btn btn-outline-secondary mr-2 p-2  "
                                                    style="margin-top: 10px"
                                                    title="You cannot subscribe to this trip. Trips are overlapping or you have an open subsistance form"
                                                    value='Subscribe'>Raise Subsistence
                                            </button>
                                        @endif

                                    </form>
                                @else
                                    <button disabled name="approval" class="btn btn-outline-secondary mr-2 p-2  "
                                            style="margin-top: 10px"
                                            title="You cannot subscribe to this trip"
                                            value='Subscribe'>Raise Subsistence
                                    </button>
                                @endif
                            @else
                                <button disabled name="approval" class="btn btn-outline-secondary mr-2 p-2  "
                                        style="margin-top: 10px"
                                        title="You cannot subscribe to this trip"
                                        value='Subscribe'>Raise Subsistence
                                </button>
                                <a href="{{route('trip.edit', $form)}}" name="edit_trip"
                                   class="btn btn-outline-secondary mr-2 p-2  " style="margin-top: 10px"
                                   title="Edit the trips"
                                >Edit Trip Form
                                </a>


                            @endif

                            {{--  HOD APPROVER--}}
                            @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_004')    )
                                <button name="invite" class="btn btn-outline-primary  p-2 justify-content-start "
                                        style="margin-top: 10px"
                                        title="Invite members to be part of this trip"
                                        data-toggle="modal"
                                        data-target="#modal-invite-trip-members"
                                        value='invite'> Add Trip Members
                                </button>

                            @endif
                        @else
                            <button disabled name="approval" class="btn btn-outline-secondary mr-2 p-2  "
                                    style="margin-top: 10px"
                                    title="This trip has ended you can not raise subsistence for it"
                                    value='Subscribe'>Raise Subsistence
                            </button>
                            {{--  HOD APPROVER--}}
                            @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_004')    )
                                <button disabled name="invite"
                                        class="btn btn-outline-primary  p-2 justify-content-start "
                                        title="This trip has ended you can not Invite members to be part of this trip"
                                        data-toggle="modal"
                                        data-target="#modal-invite-trip-members"
                                        value='invite'> Add Trip Members
                                </button>
                            @endif
                        @endif

                    </div>


                </div>


                <br>
                <form name="db1" action="{{route('trip.approve')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{--                    <p><b>Note:</b> TRIP FORM TO BE RETIRED TO EXPENDITURE OFFICE IMMEDIATELY UPON RETURN </p>--}}
                </form>

            </div>
            <!-- /.card-footer-->

        </div>
        <!-- /.card -->


        {{--  AUTHORIZATION FILES FILES--}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-bold text-orange">Authorization Files</h4>
                @if( ($user->type_id == config('constants.user_types.developer')  || ($user->id  == $form->created_by ) )   )
                    <a class="float-right" href="#" data-toggle="modal"
                       data-target="#modal-add-quotation">Add File</a>
                @endif
            </div>
            <div class="card-body" style="width:100%;  ">
                <div class="row">
                    @foreach($authorizations as $item)
                        <div class="col-lg-6 col-sm-12 mb-3">
                            <iframe id="{{$item->id}}" src="{{asset('storage/authorization_file/'.$item->name)}}"
                                    style="width:100%; height: 1000px " title="{{$item->name}}"></iframe>
                            <span>Size:{{number_format( $item->file_size, 2) }}MB  Name: {{$item->name}} </span>
                            <span> | </span>
                            <a href="{{asset('storage/authorization_file/'.$item->name)}}" target="_blank">View</a>
                            @if( ($user->type_id == config('constants.user_types.developer')  || ( $user->id  == $form->created_by )    )  )
                                <span> | </span>
                                <a href="#" data-toggle="modal"
                                   data-target="#modal-change">Edit</a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
            </div>
        </div>

        <div class="modal fade" id="modal-trip-members">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content  ">
                    <div class="modal-header">
                        <label> Invited Members</label>
                    </div>
                    <div class="modal-body">
                        <div id="trip_members">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-invite-trip-members">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content  ">
                    <div class="modal-header">
                        <label> Invite Trip Members</label>
                    </div>
                    <div class="modal-body">
                        <!-- Image loader -->
                        <div id="loader_inv" style="display: none; ">
                            <img src="{{ asset('dashboard/dist/gif/Eclipse_loading.gif')}}"
                                 width="100px"
                                 height="100px">
                        </div>
                        <form id="create_form" name="invite_member_form" action="{{route('trip.invite', $form)}}"
                              enctype="multipart/form-data"
                              method="post">
                        @csrf
                        <!-- Image loader -->
                            <div id="invite_trip_members">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modal-approve-member">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <!-- form start -->
                    <form id="approve_form_one" name="approve_form_one"
                          action="{{route('trip.approve.membership', ['trip' => $form ])}}"
                          method="post" enctype="multipart/form-data">
                        <input type="hidden" name="sig_date" value=" {{date('Y-m-d H:i:s')}}" readonly required>
                        @csrf
                        <div class="modal-header">
                            <div id="details_0">

                            </div>
                        </div>
                        <div class="modal-body">
                            <div id="details_1">

                            </div>
                            <div id="details_2">

                            </div>
                            <hr>
                            <label>Next Person To Act</label>
                            <div id="details_3">

                            </div>

                            <hr>
                            {{--  HOD APPROVER--}}
                            @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_004')    )
                                <div class="" id="div_hod">

                                </div>
                                @if( $user->staff_no !=  $form->claimant_staff_no    )
                                    <div class="" id="div_dest">

                                    </div>
                                @endif
                            @endif
                            {{--  HR--}}
                            @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_009')     )
                                <div class="" id="div_hr">

                                </div>
                            @endif
                            {{--  SNR--}}
                            @if( $user->profile_id ==  config('constants.user_profiles.EZESCO_015')     )
                                <div class="" id="div_snr">

                                </div>
                            @endif
                            {{--  CHIEF ACC--}}
                            @if( ( $user->profile_id ==  config('constants.user_profiles.EZESCO_007')  )
                               ||  ( $user->profile_id ==  config('constants.user_profiles.EZESCO_003')  )
                               ||  ( $user->profile_id ==  config('constants.user_profiles.EZESCO_011')  )
                               ||  ( $user->profile_id ==  config('constants.user_profiles.EZESCO_014')  ) )
                                <div class="" id="div_cac">

                                </div>
                            @endif


                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.APPROVE MEMBER MODAL -->


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

    <script>
        $('#modal-invite-trip-members').on('show.bs.modal', function (event) {

                var route_invite = '{{url('main/user/list/all')}}';

                /* AJAX TO GET USERS*/
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: route_invite,
                    type: 'get',
                    beforeSend: function () {
                        // Show image container
                        $("#loader_inv").show();
                    },
                    success: function (response_data) {
                        var obj = JSON.parse(response_data);

                        //HANDLE WHO'S NEXT
                        var details_1 = "" +
                            " <div class='col-12'> <input class='mb-2'  id='myInputUsers' type='text' placeholder='Search..'> </div>" +
                            "" +
                            "<table border='1' class='table table-striped border-transparent '>" +
                            "<thead>" +
                            "<tr>" +
                            "<td> #</td>" +
                            "<td> Man Number</td>" +
                            "<td> Name</td>" +
                            "<td> Job Code</td>" +
                            "</tr>" +
                            "</thead>" +
                            "<tbody id='myTableUsers' >"

                        var details_1B = " ";
                        for (i = 0; i < obj.length; i++) {
                            details_1B +=
                                "   <tr> " +
                                "<td> " +
                                "<div c.lass='form-group clearfix'> " +
                                "<div class='icheck-warning d-inline'> " +
                                "<input type='checkbox' value=' " + obj[i].id + " ' id='users[]' name='users[]'> </div> " +
                                "</div> " +
                                "</td> " +
                                "<td><span for='accounts'> <span class='text-gray'>" + obj[i].staff_no + "</span>  </span>  </td> " +
                                "<td><span for='accounts'> <span class='text-gray'>" + obj[i].name + "</span>  </span>  </td> " +
                                "<td><span for='accounts'> <span class='text-gray'>" + obj[i].job_code + "</span>  </span> </td> " +
                                "";

                        }
                        var details_1C = "</tbody></table>" +
                            "" +
                            " <button type='submit' class='btn btn-outline-success mr-2 p-2  '>Invite</button>" +
                            "";

                        //02 - SET
                        $('#invite_trip_members').html(details_1 + details_1B + details_1C);

                        $(document).ready(function () {
                            $("#myInputUsers").on("keyup", function () {
                                var value = $(this).val().toLowerCase();
                                $("#myTableUsers tr").filter(function () {
                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                });
                            });
                        });

                    },
                    complete: function (response_data) {
                        // Hide image container
                        $("#loader_inv").hide();
                    }
                });

                //02 - SET
                //   $('#invite_trip_members').html(details_1 + details_1B + details_1C);
            }
        );

        $('#modal-trip-members').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal

                //2 - SET APPROVES
                var details_1 = "<table border='1' class='table table-striped border-transparent '>" +
                    "<thead>" +
                    "<tr>" +
                    "<td> Name</td>" +
                    "<td> Man Number</td>" +
                    "<td> Email</td>" +
                    "<td> Contact</td>" +
                    "</tr>" +
                    "</thead>" +
                    "<tbody>";
                var details_1B = "";

                var all_invited = {!! json_encode($all_inv ) !!};

                $.each(all_invited, function (index, value) {
                    details_1B +=
                        " <tr> " +
                        " <td> " + value.members.name + " </td> " +
                        " <td> " + value.members.staff_no + " </td> " +
                        " <td> " + value.members.email + " </td> " +
                        " <td> " + value.members.phone + " </td> " +
                        "</tr> ";
                });
                var details_1C = "</tbody></table>" +
                    "";

                //02 - SET
                $('#trip_members').html(details_1 + details_1B + details_1C);
            }
        );


        $('#modal-approve-member').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes
            var html_div = "";

            //01 - SET HEADER
            var header = "<h4 class='modal-title text-center'>COMMENTS FOR " + recipient.claimant_name + "'S TRIP FORM</h4> status : <span class='badge badge-" + recipient.status.html + "'> " + recipient.status.name + "  </span>" +
                "<input hidden value='" + recipient.id + "' id='membership' name='membership'>"
            $('#details_0').html(header);

            //02 - SET DETAILS
            var details_1 = "<div class='row'>" +
                "<div class='col-6  invoice-col'>" +
                "<label> Staff Details </label> <br>" +
                "<span class='text-green' > <label class='text-orange'> Name : </label> " + recipient.claimant_name + "  </span>  <br> " +
                "<span class='text-green' ><label class='text-orange'> Staff Number :</label> " + recipient.claimant_staff_no + "  </span> <br>  " +
                "<span class='text-green' ><label class='text-orange'> Status :  </label> " + recipient.user.con_st_code + "  </span> <br>   " +
                "<span class='text-green' ><label class='text-orange'> Email : </label> " + recipient.user.email + " </span> <br>  " +
                "</div>" +
                "<div class='col-6  invoice-col'>" +
                "<label> Trip Details </label> <br>" +
                "<span class='text-green' > <label class='text-orange'> Trip To : </label> " + recipient.absc_visited_place + "  </span>  <br> " +
                "<span class='text-green' ><label class='text-orange'> Reason :</label> " + recipient.absc_visited_reason + "  </span>  <br> " +
                "<span class='text-green' ><label class='text-orange'> Date From :  </label> " + recipient.absc_absent_from + "  </span>  <br>  " +
                "<span class='text-green' ><label class='text-orange'> Date To : </label> " + recipient.absc_absent_to + " </span>  <br> " +
                "<span class='text-green' ><label class='text-orange'> Days Claimed : </label> " + recipient.num_days + " Days </span>  <br> " +
                "</div>" +
                "<div class='col-12'>" +
                "<label> Trip Approvals </label> <br>" +
                "<TABLE id='dataTable' class='table'> " +
                "<TR  class='table text-white text-bold text-uppercase bg-gradient-green '> " +
                "<TD><b>Name</b></TD> " +
                "<TD><b>Man No</b></TD> " +
                "<TD><b>Action</b></TD> " +
                "<TD><b>Status From</b></TD> " +
                "<TD><b>Status To</b></TD> " +
                "<TD><b>Reason</b></TD> " +
                "<TD><b>Date</b></TD> " +
                "</TR>";

            //2 - SET APPROVES
            var details_1B = "";

            var approvals = recipient.approvals;
            $.each(approvals, function (index, value) {

                if (value.claimant_staff_no == recipient.claimant_staff_no) {
                    details_1B +=
                        "<TR>" +
                        "<TD>" + value.name + "</TD>" +
                        "<TD>" + value.staff_no + "</TD>" +
                        "<TD>" + value.action + "</TD>" +
                        "<TD>" + value.from_status.name + "</TD>" +
                        // "<TD>" + value.to_status.name + "</TD>" +
                        "<TD>" + value.reason + "</TD>" +
                        "<TD>" + value.created_at + "</TD>" +
                        "</TR> ";
                }
            });
            var details_1C = "</TABLE>" +
                "</div>" +
                "</div>";

            //02 - SET
            $('#details_1').html(details_1 + details_1B + details_1C);

            //03 - SET BUTTONS
            var hod_profile = "  <div class='row ''> " +
                "<div class='col-2'> " +
                "<label class='form-control-label'>Reason</label> " +
                "</div> " +
                "<div class='col-8'> " +
                "<textarea class='form-control' rows='2' name='reason' required></textarea> " +
                "</div> " +
                "<div class='col-2 text-center ''> " +
                "<div id='divSubmit_show'> " +
                "<button id='btnSubmit_approve' type='submit' name='approval'" +
                "class='btn btn-outline-success mr-2 p-2  '" +
                "value='Approved'>APPROVED " +
                "</button> " +
                "<button style='display: none' id='btnSubmit_reject' type='submit' name='approval'" +
                "class='btn btn-outline-success mr-2 p-2  '" +
                "value='Rejected'>REJECT </button> " +
                "</div> " +
                "<div id='divSubmit_hide' style='display: none'> " +
                "<button disabled class='btn btn-outline-success mr-2 p-2  '" +
                "value='Approved'>Processing. Please wait... " +
                "</button> " +
                "</div> " +
                "</div> " +
                "</div>";


            var route = '{{url('subsistence/show/now')}}' + '/' + recipient.id;
            var hod_profile_sub = "  <div class='row ''> " +
                "<div class='col-12  text-center'> " +
                "  <a href='" + route + "' title='Open subsistence form'" +
                "class='btn btn-sm btn-outline-warning ' " +
                " <span class='badge 'default' > CLICK HERE TO OPEN FORM </span> </a> </div>" +
                "</div> " +
                "</div>";
            var chif_acc_profile = hod_profile_sub;
            var snr_profile_sub = hod_profile_sub;


            var date_from = Date.parse(recipient.absc_absent_from);
            var date_to = Date.parse(recipient.absc_absent_to);

            function format(date_1) {
                date_1 = new Date(date_1);
                var day = ('0' + date_1.getDate()).slice(-2);
                var month = ('0' + (date_1.getMonth() + 1)).slice(-2);
                var year = date_1.getFullYear();
                return year + '-' + month + '-' + day;
            }


            //05
            var dest_profile = "  <div class='row ''> " +

                "<div class='col-6'> " +
                "<label class='form-control-label'>Reason</label> " +
                "<textarea class='form-control' rows='2' name='reason' required></textarea> " +
                "</div>" +
                "" +
                "<div class='col-2'>" +
                "<label class='form-control-label'>Date Arrived</label> " +
                "<input class='form-control' min='" + format(date_from) + "'   max='" + format(date_to) + "'  required type='date' id='date_from' name='date_from'>" +
                "</div>" +
                " " +
                "" +
                "<div class='col-2'>" +
                "<label class='form-control-label'>Date Left</label> " +
                "<input class='form-control'  min='" + format(date_from) + "'   required type='date' id='date_to' name='date_to'>" +
                "</div>" +
                " " +
                "<div class='col-2 text-center'> " +
                "<label class='form-control-label '>Trip Approval</label> " +
                "<div id='divSubmit_show'> " +
                "<button id='btnSubmit_approve' type='submit' name='approval'" +
                "class='btn btn-outline-success mr-2 p-2  '" +
                "value='Approved'>CONFIRMED " +
                "</button> " +
                "<button style='display: none' id='btnSubmit_reject' type='submit' name='approval'" +
                "class='btn btn-outline-success mr-2 p-2  '" +
                "value='Rejected'>REJECT </button> " +
                "</div> " +
                "<div id='divSubmit_hide' style='display: none' > " +
                "<button disabled class='btn btn-outline-success mr-2 p-2  '" +
                "value='Approved'>Processing. Please wait... " +
                "</button> " +
                "</div> " +
                "</div> " +
                "</div>";

            html_div = dest_profile;


            //STATUS
            var accepted = {!!  json_encode(config('constants.trip_status.accepted')) !!};
            var dr_approved = {!!  json_encode(config('constants.subsistence_status.dr_approved')) !!};
            var hod_approved = {!!  json_encode(config('constants.subsistence_status.hod_approved')) !!};
            var hod_approved_trip = {!!  json_encode(config('constants.trip_status.hod_approved_trip')) !!};
            var trip_authorised = {!!  json_encode(config('constants.trip_status.trip_authorised')) !!};
            var hr_approved_trip = {!!  json_encode(config('constants.trip_status.hr_approved_trip')) !!};
            var hr_approved = {!!  json_encode(config('constants.subsistence_status.hr_approved')) !!};
            var station_mgr_approved = {!!  json_encode(config('constants.subsistence_status.station_mgr_approved')) !!};
            var chief_accountant = {!!  json_encode(config('constants.subsistence_status.chief_accountant')) !!};
            var audit = {!!  json_encode(config('constants.subsistence_status.pre_audited')) !!};
            var funds_disbursement = {!!  json_encode(config('constants.subsistence_status.funds_disbursement')) !!};
            var destination_approvals = {!!  json_encode(config('constants.subsistence_status.destination_approval')) !!};
            var await_audit = {!!  json_encode(config('constants.subsistence_status.await_audit')) !!};
            var next_profile = "";
            var user_unit = recipient.user_unit_code;
            var route_back = '{{url('main/user/unit/users')}}';


            //HOD
            if ((recipient.config_status_id == accepted)) {


                if (recipient.grade == "M3" || recipient.grade == "M2" || recipient.grade == "M1") {
                    next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_003')) !!};
                    $('#div_cac').html(hod_profile_sub);
                } else {
                    next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_004')) !!};
                    $('#div_hod').html(hod_profile);
                }


                user_unit = recipient.user.user_unit_code;

            }

            //HR 1
            else if (
                (recipient.config_status_id == station_mgr_approved)
                || (recipient.config_status_id == dr_approved)) {
                // html_div = hod_profile;
                html_div = hod_profile_sub;
                next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_009')) !!};


                if (recipient.grade == "M3" || recipient.grade == "M2" || recipient.grade == "M1") {
                    user_unit = recipient.user.user_unit_code;
                } else {
                    //check if you belong to same dpts
                    if (recipient.trip_hod_unit == recipient.user.user_unit_code) {
                        user_unit = recipient.user.user_unit_code;
                    } else {
                        user_unit = recipient.trip_hod_unit;
                    }
                }


                //  user_unit = recipient.user.user_unit_code;
            }


            //SNR MANAGER  TRIP
            else if ((recipient.config_status_id == hod_approved_trip)) {
                // $('#div_snr').html(hod_profile);
                html_div = hod_profile;
                next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_015')) !!};
                user_unit = recipient.user.user_unit_code;
            }

            //SNR MANAGER  TRIP
            else if ((recipient.config_status_id == hod_approved)) {
                html_div = hod_profile_sub;

                //  html_div = hod_profile;
                next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_015')) !!};
                //  user_unit = recipient.user.user_unit_code;

                //check if you belong to same dpts
                if (recipient.trip_hod_unit == recipient.user.user_unit_code) {
                    user_unit = recipient.user.user_unit_code;
                } else {
                    user_unit = recipient.trip_hod_unit;
                }
            }


            {{--//SNR MANAGER--}}
                {{--else if ((recipient.config_status_id == hr_approved)) {--}}
                {{--    html_div = snr_profile_sub;--}}
                {{--    next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_015')) !!};--}}
                {{--}--}}
                {{--    --}}
                {{--//CHIEF ACCOUNTANT--}}
                {{--else if ((recipient.config_status_id == station_mgr_approved)) {--}}
                {{--    $('#div_cac').html(chif_acc_profile);--}}
                {{--    next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_007')) !!};--}}
                {{--}--}}


            //CHIEF ACCOUNTANT
            else if ((recipient.config_status_id == hr_approved)) {
                $('#div_cac').html(chif_acc_profile);
                next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_007')) !!};
            }


            //AUDIT (REMOVED) NOW EXPENDITURE
            else if ((recipient.config_status_id == chief_accountant)) {
                $('#div_cac').html(chif_acc_profile);
                next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_014')) !!};
            }

            //EXPENDITURE
            else if ((recipient.config_status_id == audit)) {
                $('#div_cac').html(chif_acc_profile);
                next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_014')) !!};
            }


            //DESTINATION APPROVALS
            else if ((recipient.config_status_id == destination_approvals)) {
                next_profile = {!!  json_encode(config('constants.user_profiles.EZESCO_004')) !!};
                route_back = '{{url('main/user/unit/many/users')}}';

                //remove from the array the user unit already worked on
                var aaaa = recipient.destinations;
                for (var i = 0; i < aaaa.length; ++i) {
                    if (aaaa[i].created_by != null) {
                        aaaa.splice(i, 1);
                    }
                }
                //final of unworked on user units - get the user unit code only removing duplicates
                user_unit = aaaa.map(aaaa => aaaa.user_unit_code);

                //user units urls
                var url = '';
                for (var i = 0; i < user_unit.length; ++i) {
                    if (url.indexOf('?') === -1) {
                        url = url + '?array[]=' + user_unit[i];
                    } else {
                        url = url + '&array[]=' + user_unit[i];
                    }
                }
                //inter-change
                user_unit = next_profile;
                next_profile = url;
            }

            //USER
            else if ((recipient.config_status_id == funds_disbursement)) {
                $('#div_cac').html(chif_acc_profile);
                next_profile = {!!  json_encode(config('constants.user_profiles.EZESCO_002')) !!};
                //HANDLE WHO'S NEXT
                var whos_next1 = "<div class='row'> ";
                var whos_next2 = " ";
                whos_next2 += "" +
                    "<div class='col-4 '> " +
                    "<span class='font-weight-bold text-orange'>Test : </span><span class='text-green'>" + recipient.user.staff_no + "</span><br> " +
                    "<span class='font-weight-bold text-orange'>Position : </span><span class='text-green'>" + recipient.user.job_code + "</span><br> " +
                    "<span class='font-weight-bold text-orange'>Name : </span><span class='text-green'>" + recipient.user.name + "</span><br> " +
                    "<span class='font-weight-bold text-orange'>Phone : </span><span class='text-green'>" + recipient.user.phone + "</span><br> " +
                    "<span class='font-weight-bold text-orange'>Email : </span><span class='text-green'>" + recipient.user.email + "</span><br> " +
                    "</div>";
                var whos_next3 = "</div>" +
                    "";
                $("#details_3").html(whos_next1 + whos_next2 + whos_next3);
            }

            //AWAIT AUDIT
            else if ((recipient.config_status_id == await_audit)) {
                $('#div_cac').html(chif_acc_profile);
                next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_011')) !!};
            }

            //APPROVED
            else if ((recipient.config_status_id == trip_authorised)) {
                $('#div_hod').html(hod_profile_sub);
                next_profile = '/' + {!!  json_encode(config('constants.user_profiles.EZESCO_004')) !!};
            }

            //HANDLE BUTTON SUBMISSIONS
            $("#divSubmit_hide").hide();
            //disable the submit button
            $("#btnSubmit_approve").on('click', function () {
                $("#approve_form_one").submit(function (e) {
                    //  e.preventDefault()
                    //do something here
                    $("#divSubmit_show").hide();
                    $("#divSubmit_hide").show();
                    //continue submitting
                    e.currentTarget.submit();
                });
            });

            getNextUsers(route_back, user_unit, next_profile, recipient.config_status_id, html_div);
        });


    </script>




    <script>

        //GET ARTICLE FROM DB
        function getNextUsers(route_back, user_unit, profile, stage, html_div) {
            var route = route_back + '/' + user_unit + profile;

            /* AJAX */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: route,
                type: 'get',
                beforeSend: function () {
                    // Show image container
                    $("#loader").show();
                },
                success: function (response_data) {
                    var obj = JSON.parse(response_data);
                    //HANDLE WHO'S NEXT
                    var whos_next1 = "<div class='row'> ";
                    var whos_next2 = " ";
                    var user = {!!  json_encode($user) !!};
                    var mine_to_work = false;
                    for (i = 0; i < obj.length; i++) {
                        whos_next2 += "<div class='col-4 '> " +
                            "<span class='font-weight-bold text-orange'>test : </span><span class='text-green'>" + obj[i].staff_no + "</span><br> " +
                            "<span class='font-weight-bold text-orange'>Position : </span><span class='text-green'>" + obj[i].job_code + "</span><br> " +
                            "<span class='font-weight-bold text-orange'>Name : </span><span class='text-green'>" + obj[i].name + "</span><br> " +
                            "<span class='font-weight-bold text-orange'>Phone : </span><span class='text-green'>" + obj[i].phone + "</span><br> " +
                            "<span class='font-weight-bold text-orange'>Email : </span><span class='text-green'>" + obj[i].email + "</span><br><br> " +
                            " </div>";

                        if (user.staff_no == obj[i].staff_no) {
                            mine_to_work = true;
                        }
                    }
                    var whos_next3 = "</div>" +
                        "";
                    $("#details_3").html(whos_next1 + whos_next2 + whos_next3);

                    var dest = {!!  json_encode(config('constants.subsistence_status.destination_approval')) !!};
                    if ((stage == dest) && (mine_to_work == true)) {

                        $('#div_dest').html(html_div);
                    }

                    var myDiv = document.getElementById("div_snr");
                    var dest1 = {!!  json_encode(config('constants.subsistence_status.hod_approved')) !!};
                    var dest2 = {!!  json_encode(config('constants.trip_status.hod_approved_trip')) !!};
                    if ((stage == dest1) && (mine_to_work == true)) {
                        myDiv.innerHTML = "";//remove all child elements inside of myDiv
                        $('#div_snr').html(html_div);
                    } else if ((stage == dest2) && (mine_to_work == true)) {
                        myDiv.innerHTML = "";//remove all child elements inside of myDiv
                        $('#div_snr').html(html_div);
                    } else if ((stage == dest1) && (mine_to_work == false)) {
                        myDiv.innerHTML = "";//remove all child elements inside of myDiv
                    } else if ((stage == dest2) && (mine_to_work == false)) {
                        myDiv.innerHTML = "";//remove all child elements inside of myDiv
                    } else {

                    }


                    var mydiv_snr = document.getElementById("div_hr");

                    var hr_approved_trip = {!!  json_encode(config('constants.trip_status.station_mgr_approved')) !!};
                    var hr_approved = {!!  json_encode(config('constants.subsistence_status.station_mgr_approved')) !!};


                    if ((stage == hr_approved_trip) && (mine_to_work == true)) {
                        mydiv_snr.innerHTML = "";//remove all child elements inside of myDiv
                        $('#div_hr').html(html_div);
                    } else if ((stage == hr_approved) && (mine_to_work == true)) {
                        mydiv_snr.innerHTML = "";//remove all child elements inside of myDiv
                        $('#div_hr').html(html_div);
                    } else if ((stage == hr_approved_trip) && (mine_to_work == false)) {
                        mydiv_snr.innerHTML = "";//remove all child elements inside of myDiv
                    } else if ((stage == hr_approved) && (mine_to_work == false)) {
                        mydiv_snr.innerHTML = "";//remove all child elements inside of myDiv
                    } else {

                    }


                },
                complete: function (response_data) {
                    // Hide image container
                    $("#loader").hide();
                }
            });

        }

        function removeAllChildNodes(parent) {
            while (parent.firstChild) {
                parent.removeChild(parent.firstChild);
            }
        }

    </script>

@endpush
