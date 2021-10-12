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
                    <h1 class="m-0 text-dark">New Subsistence Claim</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('subsistence.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">New Subsistence Claim</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main page content -->
    <section class="content">


        <div class="alert alert-success alert-dismissible">
            <p class="lead"> {{$message}}</p>
        </div>

        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible">
                <p class="lead"> {{session()->get('error')}}</p>
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
            <form enctype="multipart/form-data" id="create_form" name="create_form" action="{{route('subsistence.store', compact('trip'))}}"
                  method="post">
                @csrf
                <div class="card-body">

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
                                    <input value="{{ date('d M Y') }}" type="text"
                                           name="date" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Name of claimant:</label></div>
                                <div class="col-6">
                                    <input value="{{Auth::user()->name }}" type="text" name="name" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1 ">
                            <div class="row">
                                <div class="col-6"><label>Man No.:</label></div>
                                <div class="col-6">
                                    <input value="{{Auth::user()->staff_no }}" type="text" name="employee_number"
                                           readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1 ">
                            <div class="row">
                                <div class="col-6"><label>Grade:</label></div>
                                <div class="col-6">
                                    <input value="{{Auth::user()->grade->name }}" type="text" name="grade" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Cost Center:</label></div>
                                <div class="col-6">
                                    <select name="cost_center" required class="form-control is-warning">
                                        <option value="">--choose--</option>
                                        @foreach($cost_centers as $cc)
                                            <option
                                                value="{{$cc->id}}" {{auth()->user()->user_unit->user_unit_cc_code == $cc->user_unit_cc_code ? 'selected':''}}>{{$cc->user_unit_description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1 ">
                            <div class="row">
                                <div class="col-6"><label>Section:</label></div>
                                <div class="col-6">
                                    <input value="{{Auth::user()->department->name }}" type="text" name="department"
                                           readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Station:</label></div>
                                <div class="col-6">
                                    <input value="{{Auth::user()->station ?? "" }}" type="text" name="station"
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Ext No.:</label></div>
                                <div class="col-6">
                                    <input value="{{Auth::user()->phone }}" type="text" name="extension" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>System Reference No.:</label></div>
                                <div class="col-6">
                                    <input value="" type="text" name="ref_no" required
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <table
                            class="table table-bordered mt-2 mb-4">
                            <tr style="text-align: center" width="w-100">
                                <td colspan="5" class="text-orange" ><strong>A. ABSENCE CLAIM</strong>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green">Period of Absence Date</td>
                                <td class="text-green">From</td>
                                <td><input id="absc_absent_from" name="absc_absent_from" class="form-control"
                                           required  type="text" value="{{$trip->date_from}}" >
                                </td>
                                <td class="text-green">To</td>
                                <td><input required  id="absc_absent_to" name="absc_absent_to" class="form-control"
                                           type="text"  value="{{$trip->date_to}}">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green">Place visited and reason for journey</td>
                                <td class="text-green">Place</td>
                                <td><textarea required name="absc_visited_place"
                                              class="form-control">{{$trip->destination}}</textarea></td>
                                <td class="text-green">Reason</td>
                                <td><textarea required name="absc_visited_reason"
                                              class="form-control">{{$trip->description}}</textarea></td>
                            </tr>
                            <tr>
                                <td class="text-green">Allowance Claim per Night</td>
                                <td class="text-green">ZMW</td>
                                <td><input id="absc_allowance_per_night" name="absc_allowance_per_night"
                                           class="form-control" type="text" value="{{Auth::user()->grade->sub_rate}}"
                                           readonly></td>

                                <td><strong class="text-green">Total Amount</strong></td>
                                <td>K <span id="total_amount">0</span> <input id="absc_amount" name="absc_amount"
                                                                              type="hidden"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="row">
                        <table
                            class="table table-bordered mt-2 mb-4" border="3" style="border-color: rgba(255,140,0,0.8)">
                            <tr style="text-align: center" width="w-100">
                                <td colspan="2" class="text-orange" width="w-100"><strong> AMOUNT OF
                                        CLAIM FOR SUBSISTENCE </strong></td>
                            </tr>
                            <tr style="text-align: center" width="w-100">
                                <td colspan="2" class="text-orange" width="w-100"><strong>B. TRAVELLING
                                        EXPENSE </strong></td>
                            </tr>
                            <tr>
                                <td><strong class="text-green">Total of Attached Claim (If Any) ZMW:</strong></td>
                                <td>
                                    <input required id="trex_total_attached_claim" name="trex_total_attached_claim"
                                           class="form-control" type="number">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green">Total Amount of Claim (A+B):</td>
                                <td>
                                    <input readonly id="trex_total_amount_claim" name="trex_total_amount_claim"
                                           class="form-control" type="text">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green">Deduct any advance received against these expenses:</td>
                                <td>
                                    <input required id="trex_deduct_advance" class="form-control"
                                           name="trex_deduct_advance" type="number">
                                </td>
                            </tr>
                            <tr>
                                <td class="text-green">Net Amount to be paid:</td>
                                <td>
                                    <input readonly id="net_amount_paid" class="form-control" name="net_amount_paid"
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
                                        <div class="col-3"><label>Amount ZMW:</label></div>
                                        <div class="col-9">
                                            <input value="  " type="text" id="final" name="final" readonly
                                                   class="form-control text-orange text-bold ">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-3"><label>Allocation Code:</label></div>
                                        <div class="col-9">
                                            <input value=" " type="text" id="allocation_code" name="allocation_code"
                                                   class="form-control text-orange text-bold">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="row mb-1 mt-4">
                        <div class="col-lg-12 mb-4">
                            <div class="row">
                                <div class="col-2 ">
                                    <label class="form-control-label">Attach Files (optional)</label>
                                </div>
                                <div class="col-4">
                                    <div class="input-group">
                                        <input type="file" class="form-control" multiple name="subsistence[]"
                                               id="subsistence" title="Upload Files (Optional)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <p><b>Note:</b> The system reference number is optional and is from
                        any of the systems at ZESCO such as a work request number from PEMS, Task
                        number from HQMS, Meeting Number from HQMS, Incident number from IMS etc.
                        giving rise to the expenditure</p>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div id="submit_possible1" class="col-12 text-center">
                            <div id="divSubmit_show">
                                <input class="btn btn-lg btn-success" type="submit"
                                       value="Submit Subsistence " id="btnSubmit"
                                       name="submit_form">
                            </div>
                            <div id="divSubmit_hide">
                                <input class="btn btn-lg btn-success"
                                       value="Submitting. Please wait..." disabled
                                       name="submit_form">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-footer-->
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


        //ROUND OFF FUNCTION
        Number.prototype.round = function(places) {
            return +(Math.round(this + "e+" + places)  + "e-" + places);
        }


        // Navigation Script Starts Here
        $(document).ready(function () {

                $("#divSubmit_hide").hide();
                //disable the submit button
                $("#btnSubmit").on('click', function () {
                    $("create_form").submit(function (e) {
                        e.preventDefault()
                        //do something here
                        $("#divSubmit_show").hide();
                        $("#divSubmit_hide").show();
                        //continue submitting
                        e.currentTarget.submit();
                    });
                });


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
            if (diff > 0) {
                var total = diff * absc_allowance_per_night;
                total =  total.round(2);

                $('#total_amount').html(numeral(total).format('0,0'));
                $('#absc_amount').val(total);
            }

            $("#absc_absent_from").change(function () {
                var from = moment($(this).val());
                var to = moment($('#absc_absent_to').val());
                var diff = to.diff(from, 'days');
                if (diff > 0) {
                    var total = diff * absc_allowance_per_night;
                    total =  total.round(2);
                    $('#total_amount').html(numeral(total).format('0,0'));
                    $('absc_amount').val(total);
                }
            });

            $("#absc_absent_to").change(function () {
                var from = moment($('#absc_absent_from').val());
                var to = moment($(this).val());
                var diff = to.diff(from, 'days');
                if (diff > 0) {
                    var total = diff * absc_allowance_per_night;
                    total =  total.round(2);
                    $('#total_amount').html(numeral(total).format('0,0'));
                    $('#absc_amount').val(total);
                }
            });

            //calculate trex_total_attached_claim
            $("#trex_total_attached_claim").change(function () {
                var absc_amount = $('#absc_amount').val();
                var attached_claim = $(this).val();
                // if (diff > 0) {
                var total = parseFloat(absc_amount) + parseFloat(attached_claim);
                total =  total.round(2);
                $('#trex_total_amount_claim').val(total);
                // }
            });

            //calculate final amount after deductions
            $("#trex_deduct_advance").change(function () {
                var attached_claim = $('#trex_total_amount_claim').val();
                var trex_deduct_advance = $(this).val();
                // if (diff > 0) {
                var total = parseFloat(attached_claim) - parseFloat(trex_deduct_advance);
                total =  total.round(2);
                $('#net_amount_paid').val(total);
                $('#final').val("ZMW " + total);

                // }
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


@endpush
