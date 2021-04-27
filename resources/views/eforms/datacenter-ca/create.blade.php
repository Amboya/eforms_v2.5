@extends('layouts.eforms.datacenter-ca.master')


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
                    <h1 class="m-0 text-dark">New Data Center Critical Assets Form</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('datacenter-ca-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">New Data Center Critical Assets Form</li>
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
            <form name="db2" action="{{route('datacenter-ca-store')}}" method="post">
                @csrf
                <div class="card-body">
                    <div class="col-lg-12 grid-margin stretch-card">
                        <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
                               class="mt-2 mb-4">
                            <thead>
                            <tr>
                                <th width="33%" colspan="1" class="text-center"><a href="#"><img
                                            src="{{ asset('dashboard/dist/img/zesco1.png')}}" title="ZESCO" alt="ZESCO"
                                            width="25%"></a></th>
                                <th width="33%" colspan="4" class="text-center">Data Center Critical Asset Register</th>
                                <th width="34%" colspan="1" class="p-3">Doc Number:<br>SC.15100.FORM.00486<br>Version: 2
                                </th>
                            </tr>
                            </thead>
                        </table>

                        <div class="row">
                            <div class="col-6 form-group mt-4">
                                <label for="asset_name"> ASSET NAME: <span class="required">*</span></label>
                                <input type="text" class="form-control" id="asset_name" name="asset_name" required maxlength="100"
                                       placeholder="Asset's Name e.g HP Proliant DL380">
                            </div>
                            <div class="col-6 form-group mt-4">
                                <label for="asset_category"> ASSET CATEGORY/ APPLICATION: <span
                                        class="required">*</span></label>
                                <select name="asset_category" id="ASSET_CATEGORY"  class="form-control" required >
                                    <option value="" disabled selected>--Choose Asset Category--</option>
                                    <option value="Server">Server</option>
                                    <option value="Switch">Switch</option>
                                    <option value="UPS">UPS</option>
                                    <option value="Air Conditioner">Air Conditioner</option>
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <label for="rack_location"> RACK LOCATION: <span class="required">*</span></label>
                                <select name="rack_location" id="rack_location" class="form-control" required>
                                    <option disabled value=""  selected>--Choose Asset Location--</option>
                                    <option value="Zambezi">Zambezi</option>
                                    <option value="Kafue">Kafue</option>
                                    <option value="Kabompo">Kabompo</option>
                                    <option value="Kalungwishi">Kalungwishi</option>
                                    <option value="Kalambo">Kalambo</option>
                                </select>
                            </div>
                            <div class="col-6 form-group">
                                <label for="criticality"> CRITICALITY: <span class="required">*</span></label>
                                <select name="criticality" id="criticality" class="form-control" required >
                                    <option disabled value=""  selected>--Choose Asset Criticality--</option>
                                    <option value="Very Critical">Very Critical</option>
                                    <option value="Critical">Critical</option>
                                    <option value="Not Critical">Not Very Critical</option>
                                </select>
                            </div>
                            <div class="col-6 form-group mb-2">
                                <label for="physical_location"> PHYSICAL LOCATION: <span
                                        class="required">*</span></label>
                                <input list="loc_datalist" type="text" class="form-control" id="physical_location" name="physical_location"
                                       maxlength="50" placeholder="Location e.g Lusaka, Kitwe , Ndola" required >
                                <datalist id="loc_datalist">
                                    <option>Lusaka</option>
                                    <option>Kitwe</option>
                                    <option>Ndola</option>
                                    <option>Headoffice</option>
                                </datalist>
                            </div>
                            <div class="col-12 form-group">
                                <hr>
                            </div>

                        </div>

                    </div>

                    <div class="row mb-4 ">
                        <div class="col-2">
                            <label>Submitted By:</label>
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

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div id="submit_button" class="col-12 text-center">
                            <input class="btn btn-lg btn-success" type="submit" value="Submit"
                                   name="submit_form" class="form-control" >
                            <input class="btn btn-lg btn-secondary" type="reset" value="Clear"
                                   name="reset_form" class="form-control" >
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
