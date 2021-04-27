@extends('layouts.eforms.virement.master')


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
                    <h1 class="m-0 text-dark">New Virement Form</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('petty-cash-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">New Virement Form</li>
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
            <form name="db2" action="{{route('virement-store')}}" method="post">
                @csrf
                <div class="card-body">

                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="table-responsive">
                            <div class="col-lg-12 ">
                                <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre">
                                    <thead>
                                    <tr style="height: 100px">
                                        <th colspan="1" class="text-center"><a href="#"><img
                                                        src="{{ asset('dashboard/dist/img/zesco1.png')}}" title="ZESCO"
                                                        alt="ZESCO" width="100"></a></th>
                                        <th colspan="5" class="text-center" style="height: 100px"><b>Budget Virement
                                                Policy</b></th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="height: 50px; padding-left: 10px"><b>Document No</th>
                                        <th style="height: 50px; padding-left: 10px" colspan="2">CO.14900.POLY.00017
                                        <th style="height: 50px; padding-left: 10px" colspan="2">Version: 1</b></th>
                                    </tr>
                                    <tr>
                                        <th style="height: 50px; padding-left: 10px" colspan="6" class="text-center"><b>Budget/Budget
                                                Holder Details</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <tr>
                                        <!-- <td class="text-left"><label>Man No:</label></td>
                                        <td><input type="text" id="man_no" name="man_no" placeholder="" onChange="myFunc(this.value)" class="form-control" maxlength="5" required=""> -->

                                        <td class="text-center" style="padding-right:5px ">
                                            <label><b>Directorate</b></label></td>
                                        <td><input style="font-size:10px" type="text" name="ref_no" class="form-control"
                                                   value="{{Auth::user()->directorate->name}}" readonly></td>
                                        <td class="text-right" style="padding-right:5px "><label><b>Department Name</b></label>
                                        </td>
                                        <td><input style="font-size:10px;" type="text" name="serial_no" class="form-control"
                                                   value="{{Auth::user()->department->name}}" readonly></td>
                                        <td class="text-right" style="padding-right:5px "><label><b>Budget
                                                    Holder</b></label></td>
                                        <td><input style="font-size:10px;" type="text" name="serial_no" class="form-control"></td>

                                    </tr>
                                    <tr>
                                        <th style="height: 50px; padding-left: 10px" colspan="6" class="text-center"><b>Section
                                                2 - Virement Details</b></th>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center" style="padding-right:5px "><label><b>Reason
                                                    for Virement</b></label></td>
                                        <td colspan="4"><textarea name="ref_no" class="form-control"></textarea></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center" style="padding-right:5px "><label><b>Amount
                                                    of Virement</b></label></td>
                                        <td colspan="2"><input type="text" name="ref_no" class="form-control"
                                                               placeholder="Amount of Virement In Year"></td>
                                        <td colspan="2"><input type="text" name="ref_no" class="form-control"
                                                               placeholder="Amount of Virement Full Year"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center" style="padding-right:5px "><label><b>Nature
                                                    of Virement</b></label></td>
                                        <td colspan="4"><select class="custom-select" id="inputGroupSelect01">
                                                <option selected>Nature of Virement</option>
                                                <option value="Non-Recurrent">Non-Recurrent</option>
                                                <option value="Recurrent">Recurrent</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center" style="padding-right:5px "><label><b>Source
                                                    of Virement (From)</b></label></td>
                                        <td colspan="4"><select class="custom-select" id="inputGroupSelect01">
                                                <option selected>Nature of Virement</option>
                                                <option value="Non-Recurrent">Non-Recurrent</option>
                                                <option value="Recurrent">Recurrent</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center" style="padding-right:5px "><label><b>Source
                                                    of Virement (To)</b></label></td>
                                        <td colspan="4"><select class="custom-select" id="inputGroupSelect01">
                                                <option selected>Nature of Virement</option>
                                                <option value="Non-Recurrent">Non-Recurrent</option>
                                                <option value="Recurrent">Recurrent</option>
                                            </select>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                    <div class="row mb-1 mt-4">
                        <div class="col-2">
                            <label>Name of Claimant:</label>
                        </div>
                        <div class="col-3">
                            <input type="text" name="claimant_name" class="form-control"
                                   value="{{Auth::user()->name}}" readonly required></div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" name="sig_of_claimant" class="form-control"
                                                  value="{{Auth::user()->staff_no}}" readonly required></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="Date" name="date_claimant" class="form-control"
                                                  value="{{date('Y-m-d')}}" readonly required>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><label>Claim Authorised by:</label></div>
                        <div class="col-3"><input type="text" name="claim_authorised_by" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" name="sig_of_authorised" readonly class="form-control">
                        </div>
                        <div class="col-1  text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" name="authorised_date" readonly class="form-control">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><label>HR/Station Manager:</label></div>
                        <div class="col-3"><input type="text" name="station_manager" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" name="sig_of_station_manager" readonly
                                                  class="form-control"></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" name="manager_date" readonly class="form-control"></div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-2"><label>Accountant:</label></div>
                        <div class="col-3"><input type="text" name="accountant" readonly class="form-control"></div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" name="sig_of_accountant" readonly class="form-control">
                        </div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" name="accountant_date" readonly class="form-control">
                        </div>
                    </div>


                    <p><b>Note:</b> The system reference number is mandatory and is from
                        any of the systems at ZESCO such as a work request number from PEMS, Task
                        number from HQMS, Meeting Number from HQMS, Incident number from IMS etc.
                        giving rise to the expenditure</p>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="row">

                        <div id="submit_button" class="col-12 text-center">
                            <input class="btn btn-lg btn-success" type="submit"
                                   value="Submit Virement"
                                   name="submit_form" class="form-control"
                            >
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
            }
            else {
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
