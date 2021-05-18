@extends('layouts.eforms.subsistence.master')


@push('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Subsistence Claim {{$form->code }}</h1>
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
            <form enctype="multipart/form-data" name="db1" action="{{route('subsistence.approve')}}"
                  method="post">
                @csrf
                <div class="card-body">

                    <span class="badge badge-{{$form->status->html ?? "default"}}">{{$form->status->name ?? "none"}}</span>
                    <input type="hidden" name="id" value="{{ $form->id}}" readonly required>
                    <input type="hidden" name="sig_date" value=" {{date('Y-m-d H:i:s')}}" readonly required>


                    <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
                           class="mt-2 mb-4">
                        <thead>
                        <tr>
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
                                           readonly  name="date" readonly class="form-control">
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
                                    <input value="{{$form->station }} " type="text" name="station" readonly class="form-control">
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
                                <td width="w-100"><strong>A. ABSENCE CLAIM</strong></td>
                            </tr>
                            <tr>
                                <td>Period of Absence Date</td>
                                <td>From</td>
                                <td><input readonly id="absc_absent_from" name="absc_absent_from" class="form-control"
                                           type="text" value="{{$form->absc_absent_from }}"></td>
                                <td>To</td>
                                <td><input readonly id="absc_absent_to" name="absc_absent_to" class="form-control" type="text" value="{{$form->absc_absent_to }}">
                                </td>
                            </tr>
                            <tr>
                                <td>Place visited and reason for journey</td>
                                <td>Place</td>
                                <td><textarea readonly name="absc_visited_place" class="form-control">{{$form->absc_visited_place}}</textarea></td>
                                <td>Reason</td>
                                <td><textarea readonly name="absc_visited_place_reason" class="form-control">{{$form->absc_visited_place_reason}}</textarea></td>
                            </tr>
                            <tr>
                                <td>Allowance Claim per Night</td>
                                <td>ZMW</td>
                                <td><input id="absc_allowance_per_night" name="absc_allowance_per_night"
                                           class="form-control" type="text" value="{{$form->absc_allowance_per_night}}"
                                           readonly></td>

                                <td><strong>Total Amount</strong></td>
                                <td>  <input readonly id="absc_amount1"  class="form-control" name="absc_amount" value="ZMW {{number_format($form->total,2)}}" type="text"></td>

                            </tr>
                        </table>
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
                        <div class="col-2"><label>Station Manager:</label></div>
                        <div class="col-3"><input type="text" value="{{$form->station_manager ?? "" }}"
                                                  name="claim_authorised_by" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->station_manager_staff_no  ?? "" }}"
                                                  name="sig_of_authorised" readonly class="form-control">
                        </div>
                        <div class="col-1  text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->station_manager_date ?? "" }}"
                                                  name="authorised_date" readonly class="form-control">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><label>HR:</label></div>
                        <div class="col-3"><input type="text" value="{{$form->hr_office ?? "" }}"
                                                  name="station_manager" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->hr_office_staff_no ?? "" }}"
                                                  name="sig_of_station_manager" readonly
                                                  class="form-control"></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->hr_date ?? "" }}"
                                                  name="manager_date" readonly class="form-control"></div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-2"><label>Accountant:</label></div>
                        <div class="col-3"><input type="text" value="{{$form->chief_accountant ?? "" }}"
                                                  name="accountant" readonly class="form-control"></div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->chief_accountant_staff_no ?? "" }}"
                                                  name="sig_of_accountant" readonly class="form-control">
                        </div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" value="{{$form->chief_accountant_date ?? "" }}"
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

                    {{--  HOD APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_004')
                         &&  $form->config_status_id == config('constants.subsistence_status.new_application')   )
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
                                    <button type="submit" id="hod_approval" name="approval" class="btn btn-outline-success mr-2 p-2  "
                                            value='Approved'>APPROVE
                                    </button>
                                    <button type="submit" id="hod_reject" name="approval" class="btn btn-outline-danger ml-2 p-2  "
                                            value='Rejected'>REJECT
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{--  STATION MANAGER APPROVAL--}}
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_015')
                         &&  $form->config_status_id == config('constants.subsistence_status.hod_approved')   )
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



                </div>
                <!-- /.card-footer-->



                {{-- NEXT PERSON TO ACT--}}
                @if(  $form->config_status_id != config('constants.subsistence_status.closed')   )
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Next Person/s to Act</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($user_array as $item)
                                    <div class="col-4 text-red ">
                                        <span class="font-weight-bold" >Position:</span><span >{{$item->position->name}}</span><br>
                                        <span class="font-weight-bold">Name:</span><span >{{$item->name}}</span><br>
                                        <span class="font-weight-bold">Phone:</span><span >{{$item->phone}}</span><br>
                                        <span class="font-weight-bold">Email:</span><span >{{$item->email}}</span><br>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">
                            <span class="font-weight-bold">Next Action:</span><span > {{$form->status->other}}</span>
                            @if ($form->status->id == config('constants.subsistence_status.security_approved'))
                                <span class="font-weight-bold text-red"> Note:</span><span class="text-red"> Export Data to Excel and Import in Oracle Financial's using ADI</span>
                            @endif
                        </div>
                    </div>
                @endif



                {{--  FORM PPROVALS--}}
                <div class="card collapsed-card">
                    <div class="card-header">
                        <h4 class="card-title">Approvals</h4>  <span
                            class="badge badge-secondary right ml-2">{{$approvals->count()}}</span>
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
                                @foreach($approvals as $item)
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


            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@push('custom-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
            integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
            crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>

    <script type="text/javascript">

        function getvalues() {
            var inps = document.getElementsByName('amount[]');
            var total = 0;
            for (var i = 0; i < inps.length; i++) {
                var inp = inps[i];
                total = total + parseFloat(inp.value || 0);
            }

            if (!isNaN(total)) {

                //check if petty cash is below 2000
                if (total > 2000) {
                    $('#submit_possible').hide();
                    $('#submit_not_possible').show();
                } else if (total == 0) {
                    $('#submit_not_possible').hide();
                    $('#submit_possible').hide();
                } else {
                    $('#submit_not_possible').hide();
                    $('#submit_possible').show();
                }
                //set value
                document.getElementById('total-payment').value = total;
            }
        }


        // Navigation Script Starts Here
        $(document).ready(function () {

            //first hide the buttons
            $('#submit_possible').hide();
            $('#submit_not_possible').hide();

            // var to = moment($('absc_absent_to'));
            //
            // var diff = from.diff(to);
            var absc_allowance_per_night = $('#absc_allowance_per_night').val();

            var from = moment($('#absc_absent_from').val());
            var to = moment($('#absc_absent_to').val());
            var diff = to.diff(from, 'days');
            if(diff > 0) {
                var total = diff * absc_allowance_per_night;
                $('#total_amount').html(numeral(total).format('0,0'));
                $('absc_amount').val(total)
            }

            console.log("To",$('absc_absent_from').val())

            console.log("from",from)
            console.log("The difference is: ", diff);

            $("#absc_absent_from").change(function () {
                var from = moment($(this).val());
                var to = moment($('#absc_absent_to').val());
                var diff = to.diff(from, 'days');
                if(diff > 0) {
                    var total = diff * absc_allowance_per_night;
                    $('#total_amount').html(numeral(total).format('0,0'));
                    $('absc_amount').val(total)
                }
                console.log("To",to)

                console.log("from",from)
                console.log("The difference is: ", diff);


            });

            $("#absc_absent_to").change(function () {
                var from = moment($('#absc_absent_from').val());
                var to = moment($(this).val());
                var diff = to.diff(from, 'days');
                if(diff > 0) {
                    var total = diff * absc_allowance_per_night;
                    $('#total_amount').html(numeral(total).format('0,0'));
                    $('absc_amount').val(total)
                }
                console.log("To",to)
                console.log("From",from)

                console.log("The difference is: ", diff);
            });


        });

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

        // git remote add origin https://github.com/ZESCOISD/eforms.git
        // git branch -M main
        //  git push -u origin mai

    </SCRIPT>

{{--    <script type="text/javascript">--}}
{{--        var hod_approval = document.getElementById('hod_approval')--}}
{{--        hod_approval.addEventListener('click',hideshow,false);--}}

{{--        var hod_reject = document.getElementById('hod_reject')--}}
{{--        hod_reject.addEventListener('click',hideshow,false);--}}

{{--        function hideshow() {--}}
{{--            document.getElementById('hidden-div').style.display = 'block';--}}
{{--            this.style.display = 'none'--}}
{{--        }--}}
{{--        --}}
{{--    </script>--}}


@endpush
