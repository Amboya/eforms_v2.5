@extends('layouts.eforms.hotel-accommodation.master')


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
                    <h1 class="m-0 text-dark">New Hotel Accommodation Form</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('hotel.accommodation.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">New Hotel Accommodation Form</li>
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
            <form method="post" enctype="multipart/form-data" name="db1"
                  action="{{route('hotel.accommodation.store')}}">
                @csrf
                <div class="card-body">
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
                            <td><input type="text" name="staff_name" class="form-control" value="{{Auth::user()->name}}"
                                       readonly></td>
                            <td class="text-center"><label>Man No:</label></td>
                            <td><input type="text" name="staff_no"
                                       class="form-control" value="{{Auth::user()->staff_no}}"
                                       readonly></td>
                            <td class="text-center"><label>Grade:</label></td>
                            <td><input type="text" name="grade" class="form-control"
                                       value="{{Auth::user()->grade->name}}" readonly></td>

                        </tr>
                        <tr>
                            <td><label>Directorate:</label></td>
                            <td><input type="text" name="directorate"
                                       class="form-control" value="{{Auth::user()->directorate->name}}"
                                       readonly></td>
                            <td class="text-center"><label>Hotel:</label></td>
                            <td><input type="text" required name="hotel" class="form-control"
                                       placeholder="Enter Hotel Name"></td>
                        </tr>
                        <tr>
                            <td class="text-"><label>Cost Centre:</label></td>
                            <td><input type="text" name=""
                                       class="form-control" value="{{Auth::user()->user_unit->cost_center_code}}"
                                       readonly></td>
                            <td><label>SYS Ref No.</label></td>
                            <td><input type="text" required name="ref_no" class="form-control" value=""></td>

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
                            <td><input type="text" required name="purpose_of_journey" class="form-control"
                                       placeholder="Enter Purpose of Journey"></td>
                        </tr>

                        <tr>
                            <td><label>Estimated Period of Stay</label></td>
                            <td><input type="text" required name="estimated_period_of_stay" class="form-control"
                                       placeholder="Enter Estimated Period of Stay"></td>
                            <td class="text-center"><label>Estimated Cost:</label></td>
                            <td><input type="text" required name="estimated_cost" class="form-control"
                                       placeholder="Enter Estimated Cost"></td>
                        </tr>
                        <tr>
                            <td><label>Employee Name:</label></td>
                            <td><input type="text" name="employee_name" class="form-control"
                                       value="{{Auth::user()->name}}" readonly></td>
                            <td class="text-center"><label>Signature:</label></td>
                            <td><input type="text" name="employee_staff_no"
                                       class="form-control" value="{{Auth::user()->staff_no}}"
                                       readonly></td>
                            <td class="text-center"><label>Date:</label></td>
                            <td><input type="date" required name="claim_date" class="form-control"
                                       value="{{date('Y-m-d')}}" readonly></td>
                        </tr>
                        <tr>
                            <td height="20"></td>
                        </tr>
                        <tr>
                            <td colspan="6"><p><b>Imprest Upon Being Accomodated in a Hotel:</b></p></td>
                        </tr>
                        <tr>
                            <td><label>Amount Claimed ZMW:</label></td>
                            <td><input type="text" required name="amount_claimed" class="form-control"
                                       placeholder="Enter Amount Claimed"></td>
                            <td class="text-center"><label>Being ZMW:</label></td>
                            <td><input type="text" required name="amount" class="form-control"
                                       placeholder="Enter Amount"></td>
                            <td class="text-center"><label>Per Night:</label></td>
                            <!--                <td><input type="text" name="" class="form-control"></td>-->
                        </tr>
                        <tr>
                            <td><label>Name of Chief Accountant</label></td>
                            <td><input type="text" required name="chief_accountant_name" class="form-control" readonly>
                            </td>
                            <td class="text-center"><label>Sign:</label></td>
                            <td><input type="text" name="chief_staff_no" class="form-control" readonly></td>
                            <td class="text-center"><label>Date:</label></td>
                            <td><input type="Date" required name="chief_accountant_authorised_date" class="form-control"
                                       readonly></td>
                        </tr>
                        <tr>
                            <td><label>Name of HOD:</label></td>
                            <td><input type="text" required name="hod_name" class="form-control" readonly></td>
                            <td class="text-center"><label>Signature:</label></td>
                            <td><input type="text" required name="hod_staff_no" class="form-control" readonly></td>
                            <td class="text-center"><label>Date:</label></td>
                            <td><input type="Date" required name="hod_authorised_date" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td><label>Approved by Director:</label></td>
                            <td><input type="text" required name="director_name" class="form-control" readonly></td>
                            <td class="text-center"><label>Signature:</label></td>
                            <td><input type="text" required name="director_staff_no" class="form-control" readonly></td>
                            <td class="text-center"><label>Date:</label></td>
                            <td><input type="Date" required name="director_authorised_date" class="form-control"
                                       readonly></td>
                        </tr>
                        <tr>
                            <td height="10" colspan="6"></td>
                        </tr>
                        <tr>
                            <td height="15"></td>
                        </tr>
                        <tr>
                            <td colspan="2"><p><b>Note:</b> To be Filled in Duplicate. </br>
                                    <br>First Copy to the Chief Accountant for Payment.
                                    Second Copy to Finance for Order Processing</br>

                                </p></td>
                        </tr>
                        <tr>
                            <td colspan="6"><p><b>Note:</b> The system reference number is mandatory. It is a
                                    number from any of the systems at ZESCO such as a work request number from
                                    PEMS, Task number from HQMS,Meeting Number from HQMS, Incident Number from
                                    IMS, DCS etc. giving rise to the expenditure</p></td>

                        </tr>


                    </table>
                </div>
                <div class="card-footer">
                    <div class="col-12 text-center">
                        <input class="btn btn-lg btn-success" type="submit"
                               value="submit"
                               name="submit_form" class="form-control"
                        >
                    </div>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@push('custom-scripts')
    <!--  -->
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

    </SCRIPT>

@endpush
