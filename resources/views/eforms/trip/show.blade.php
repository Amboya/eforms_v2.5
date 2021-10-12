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
                    <h1 class="m-0 text-dark text-uppercase text-orange ">TRIP CODE <span class="text-green ">{{ $form->code }}</span> </h1>
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
                        <b  class='text-orange'>Name:</b> <span class='text-green'>{{ $form->name}}</span><br>
                        <b  class='text-orange'>Date From:</b> <span class='text-green'>{{ $form->date_from}}</span><br>
                        <b  class='text-orange'>Date To:</b> <span class='text-green'>{{ $form->date_to}}</span><br>
                        <b  class='text-orange'>Members:</b> <span class='text-green'>Invited {{ $form->invited}} and {{sizeof($form->members)}} have
                        subscribed.</span><br>
                    </div>
                    <div class="col-sm-6 invoice-col">
                        <br>
                        <br>
                        <b class='text-orange' >Destination:</b><span class='text-green'> {{ $form->destination}} </span><br>
                        <b  class='text-orange'>Description:</b><span class='text-green'>  {{ $form->description}} </span><br>
                        <b  class='text-orange'>Created By:</b> <span
                            class=" text-green">{{ $form->initiator_name}}  </span>
                        <br>
                        <b  class='text-orange'>Created At:</b> <span
                            class=" text-green">{{ $form->created_at}} : that is {{ $form->created_at->diffForHumans()}} </span>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="id" value="{{ $form->id}}" readonly required>
                <input type="hidden" name="sig_date" value=" {{date('Y-m-d H:i:s')}}" readonly required>

                <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
                       class="mt-2 mb-4">
                    <thead>
                    <tr>
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
                                <tr >
                                    <th></th>
                                    <th>MAN NO</th>
                                    <th>NAME</th>
                                    <th>HOD SIGNATURE</th>
                                    <th>DATE OF DEPARTURE</th>
                                    <th>DAYS CLAIMED</th>
                                    <th>M/V NUMBER</th>
                                    <th>DESTINATION</th>
                                    <th>DATE ARRIVED</th>
                                    <th>ACTUAL DAYS TAKEN</th>
                                    <th>DATE LEFT</th>
                                    <th>REGIONAL, AREA, BRANCH OR LINE MANAGER’S SIGNATURE</th>
                                    <th>STATUS</th>
                                    <th>ACTION</th>
                                </tr>
                                </thead>
                                @foreach($form->members as $item)
                                    <TR>
                                        <td></td>
                                        <td>{{$item->claimant_staff_no ?? ""}}</td>
                                        <td >
                                            <a href=""  data-toggle="modal"
                                               data-sent_data="{{$item}}"
                                               data-target="#modal-approve-member" >
                                                {{$item->claimant_name ?? ""}}
                                            </a>

                                           </td>
                                        <td>{{$item->initiator_name ?? ""}} <br> {{$item->initiator_staff_no ?? ""}}</td>
                                        <td>{{$item->absc_absent_from ?? ""}}</td>
                                        <td>{{$item->total_days ?? ""}}</td>
                                        <td>{{$item->m_v_number ?? "" }}</td>
                                        <td>{{$item->absc_visited_place ?? "" }}</td>
                                        <td>{{$item->date_arrived ?? ""}}</td>
                                        <td>{{$item->date_arrived ?? "" }}</td>
                                        <td>{{$item->date_left}}</td>
                                        <td>{{$item->closed_by_name }} <br> {{$item->closed_by_staff_no ?? ""}}</td>
                                        {{--                                        <td>{{$item->man_no ?? ""}}</td>--}}
                                        <td>
                                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002')  )
                                                @if(Auth::user()->staff_no ==  $item->claimant_staff_no)
                                            <a href="{{ route('logout') }}"  title="Open subsistence form"
                                               class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                               onclick="event.preventDefault();
                                                   document.getElementById('show-form1'+{{$item->id}}).submit();">
                                                    <span
                                                        class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                            </a>
                                                @else
                                                    <a  class="btn btn-sm bg-{{$item->status->html ?? "default"}}">
                                                    <span title="This is not your subsistence form - you cannot open it"
                                                        class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="{{ route('logout') }}" title="Open subsistence form"
                                                   class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form1'+{{$item->id}}).submit();">
                                                    <span
                                                        class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                                </a>
                                            @endif
                                            <form id="show-form1{{$item->id}}"
                                                  action="{{ route('subsistence.show', $item->id) }}"
                                                  method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </td>
                                        <td>
                                            <button
                                                class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                title="Trip Approvals List"
                                                data-toggle="modal"
                                                data-sent_data="{{$item}}"
                                                data-target="#modal-approve-member">
                                                <i class="fa fa-check"></i>
                                            </button>


                                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002')  )
                                                @if(Auth::user()->staff_no ==  $item->claimant_staff_no)
                                                    <a href="{{ route('logout') }}" title="Open subsistence form"
                                                       class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                       onclick="event.preventDefault();
                                                           document.getElementById('show-form1'+{{$item->id}}).submit();">
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                                @else
                                                    <a  title="This is not your subsistence form - you cannot open it"
                                                        class="btn btn-sm bg-{{$item->status->html ?? "default"}}">
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <a href="{{ route('logout') }}"  title="Open subsistence form"
                                                   class="btn btn-sm bg-{{$item->status->html ?? "default"}}"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form1'+{{$item->id}}).submit();">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            @endif
                                            <form id="show-form1{{$item->id}}"
                                                  action="{{ route('subsistence.show', $item->id) }}"
                                                  method="POST" class="d-none">
                                                @csrf
                                            </form>

                                        </td>
                                    </TR>
                                @endforeach
                            </TABLE>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->


            <div class="card-footer">
                <br>
                @if($list_inv != null)

                    @if( $list_inv->type == config('constants.trip_status.pending'))
                        <form name="db1"
                              action="{{route('subsistence.subscribe', ['trip' => $form, 'invitation'=> $list_inv])}}"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <button type="submit" name="approval" class="btn btn-outline-success mr-2 p-2  "
                                    title="Click here to accept invitation to be part of this trip"
                                    value='Subscribe'>Raise Subsistence
                            </button>
                        </form>
                    @else
                        <button disabled name="approval" class="btn btn-outline-secondary mr-2 p-2  "
                                title="Sorry you cannot subscribe to this trip because you haven't been invited"
                                value='Subscribe'>Raise Subsistence
                        </button>
                    @endif
                @else
                    <button disabled name="approval" class="btn btn-outline-secondary mr-2 p-2  "
                            title="Sorry you cannot subscribe to this trip because you haven't been invited"
                            value='Subscribe'>Raise Subsistence
                    </button>
                @endif
                <br>
                <form name="db1" action="{{route('trip.approve')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <p><b>Note:</b> TRIP FORM TO BE RETIRED TO EXPENDITURE OFFICE IMMEDIATELY UPON RETURN </p>
                </form>

            </div>
            <!-- /.card-footer-->

        </div>
        <!-- /.card -->


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
                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_004')    )
                                <div class="" id="div_hod">

                                </div>
                            @endif
                            {{--  HR--}}
                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_009')     )
                                <div class="" id="div_hr">

                                </div>
                            @endif
                            {{--  SNR--}}
                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_015')     )
                                <div class="" id="div_snr">

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
        $('#modal-approve-member').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes

            console.log(recipient);

            //01 - SET HEADER
            var header = "<h4 class='modal-title text-center'>COMMENTS FOR " + recipient.claimant_name + "'S TRIP FORM</h4>   <span class='btn btn-sm bg-"+ recipient.status.html + "'> "+ recipient.status.name + "  </span>" +
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
                "<span class='text-green' ><label class='text-orange'> Days Claimed : </label> " + recipient.total_days + " </span>  <br> " +
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

                if(value.claimant_staff_no == recipient.claimant_staff_no){
                    details_1B +=
                        "<TR>" +
                        "<TD>" + value.name + "</TD>" +
                        "<TD>" + value.staff_no + "</TD>" +
                        "<TD>" + value.action + "</TD>" +
                        "<TD>" + value.from_status.name + "</TD>" +
                        "<TD>" + value.to_status.name  + "</TD>" +
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
                "<div id='divSubmit_hide'> " +
                "<button disabled class='btn btn-outline-success mr-2 p-2  '" +
                "value='Approved'>Processing. Please wait... " +
                "</button> " +
                "</div> " +
                "</div> " +
                "</div>";

            //STATUS
            var accepted = {!!  json_encode(config('constants.trip_status.accepted')) !!};
            var hod_approved_trip = {!!  json_encode(config('constants.trip_status.hod_approved_trip')) !!};
            var trip_authorised = {!!  json_encode(config('constants.trip_status.trip_authorised')) !!};
            var hr_approved_trip = {!!  json_encode(config('constants.trip_status.hr_approved_trip')) !!};
            var next_profile = "";

            //HOD
            if ((recipient.config_status_id == accepted)) {
                $('#div_hod').html(hod_profile);
                next_profile = {!!  json_encode(config('constants.user_profiles.EZESCO_004')) !!};
            }

            //HR
            else if ((recipient.config_status_id == hod_approved_trip)) {
                $('#div_hr').html(hod_profile);
                next_profile = {!!  json_encode(config('constants.user_profiles.EZESCO_009')) !!};
            }

            //SNR MANAGER
            else if ((recipient.config_status_id == hr_approved_trip)) {
                $('#div_snr').html(hod_profile);
                next_profile = {!!  json_encode(config('constants.user_profiles.EZESCO_015')) !!};
            }


            //APPROVED
            else if ((recipient.config_status_id == trip_authorised)) {

                {{--$('#div_snr').html(hod_profile);--}}
                {{--next_profile = {!!  json_encode(config('constants.user_profiles.EZESCO_015')) !!};--}}
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

            getNextUsers(recipient.user_unit_code, next_profile ) ;
        });
    </script>

    <script>

        //GET ARTICLE FROM DB
        function getNextUsers(unit, profile) {
            var route = '{{url('main/user/unit/users')}}' + '/' + unit + '/' +profile;
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
                    for(i=0; i< obj.length ; i++){
                        whos_next2 += "<div class='col-4 '> " +
                            "<span class='font-weight-bold text-orange'>test : </span><span class='text-green'>" + obj[i].staff_no + "</span><br> " +
                            "<span class='font-weight-bold text-orange'>Position : </span><span class='text-green'>" + obj[i].job_code + "</span><br> " +
                            "<span class='font-weight-bold text-orange'>Name : </span><span class='text-green'>" + obj[i].name + "</span><br> " +
                            "<span class='font-weight-bold text-orange'>Phone : </span><span class='text-green'>" + obj[i].phone + "</span><br> " +
                            "<span class='font-weight-bold text-orange'>Email : </span><span class='text-green'>" + obj[i].email + "</span><br> " +
                            " </div>";
                    }
                    var whos_next3 = "</div>" +
                        "";
                    $("#details_3").html(whos_next1 + whos_next2 + whos_next3);
                },
                complete: function (response_data) {
                   // Hide image container
                    $("#loader").hide();
                }
            });

        }

    </script>

@endpush
