@extends('layouts.eforms.vehicle-requisitioning.master')


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
                    <h1 class="m-0 text-dark">New Vehicle Requisition</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('vehicle.requisitioning.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">New Vehicle Requisition</li>
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
            <form enctype="multipart/form-data" name="db1" action="{{route('vehicle.requisitioning.store')}}"
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
                            <th width="33%" colspan="4" class="text-center">Vehicle Requisition</th>
                            <th width="34%" colspan="1" class="p-3">Doc Number:<br>CO.14900.FORM.00039<br>Version: 3
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <div class="row mt-2 mb-4">
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Date:</label></div>
                                <div class="col-6">
                                    <input value="{{ date('Y-m-d H:i:s') }}" type="text" name="date" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1">
                            <div class="row">
                                <div class="col-6"><label>Name:</label></div>
                                <div class="col-6">
                                    <input value="{{Auth::user()->name }}" type="text" name="name" readonly
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1 ">
                            <div class="row">
                                <div class="col-6"><label>Employee Number:</label></div>
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
                                <div class="col-6"><label>User Unit Code:</label></div>
                                <div class="col-6">
                                    <input value="{{Auth::user()->user_unit->user_unit_code }}" type="cost_center"
                                           name="cost_center" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-4 mb-1 ">
                            <div class="row">
                                <div class="col-6"><label>Department:</label></div>
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
                                    <input  type="text" name="station" id="station" placeholder="Enter the Station"
                                            required class="form-control">
                                </div>
                            </div>
                        </div>


                        {{--                        <div class="col-4 mb-1">--}}
                        {{--                            <div class="row">--}}
                        {{--                                <div class="col-6"><label>Sys Ref No:</label></div>--}}
                        {{--                                <div class="col-6">--}}
                        {{--                                    <input  type="text" name="sysRefNo" id="sysRefNo" placeholder="Enter the related Sys Ref no."--}}
                        {{--                                            class="form-control">--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}
                    </div>

                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="form-group">
                            <label for="destination" class="col-sm-2 control-label">Destination</label>
                            <div class="col-sm-12">
                                <input type="name" required class="form-control" name="destination" id="destination"
                                       placeholder="Enter the destination">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="purpose" class="col-sm-2 control-label">Purpose</label>
                            <div class="col-sm-12">
                                <textarea rows="4" required type="text" class="form-control" name="purpose" id="purpose"
                                          placeholder="Enter Trip Purpose"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="period_of_stay_from" class="col-sm-4 control-label">Period of Stay :
                                        FROM</label>
                                    <div class="col-sm-12">
                                        <input type="date" required class="form-control" name="period_of_stay_from"
                                               id="period_of_stay_from">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="period_of_stay_to" class="col-sm-4 control-label">Period of Stay :
                                        TO</label>
                                    <div class="col-sm-12">
                                        <input type="date" required class="form-control" name="period_of_stay_to"
                                               id="period_of_stay_to">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="vehicle_reg_no" class="col-sm-12 control-label">Motor Vehicle Registration
                                No</label>
                            <div class="col-sm-12">
                                <input type="text" required class="form-control" name="vehicle_reg_no"
                                       id="vehicle_reg_no" placeholder="Enter the Motor Vehicle Registration No">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="period_of_stay_from" class="col-sm-12 control-label">Category of
                                        Vehicle: Engine Capacity</label>
                                    <div class="col-sm-12 p-2">
                                        <!-- radio -->
                                        <div class="form-group">
                                            <div class="radio">
                                                <label name="fuel_type" id="fuel_type" >
                                                    (Select Category)
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="engine_capacity" id="engine_capacity1"
                                                           value="Motor Cycle (15% of cost of fuel)" >
                                                    Motor Cycle (15% of cost of fuel)
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="engine_capacity" id="engine_capacity2"
                                                           value="Motor Car Under 1500cc (30% of cost of fuel)">
                                                    Motor Car Under 1500cc (30% of cost of fuel)
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="engine_capacity" id="engine_capacity3"
                                                           value="Motor Car between 1500cc & 2000cc (35% of cost of fuel)">
                                                    Motor Car between 1500cc & 2000cc (35% of cost of fuel)
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="engine_capacity" id="engine_capacity4"
                                                           value="Motor Car over 2000cc (40% of cost of fuel)">
                                                    <span>Motor Car over 2000cc (40% of cost of fuel)</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="period_of_stay_to" class="col-sm-12 control-label">Propelled By</label>
                                    <div class="col-sm-12 p-2">
                                        <!-- radio -->
                                        <div class="form-group">
                                            <div class="radio">
                                                <label name="fuel_type" id="fuel_type" >
                                                    (Select Fuel Type)
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="propelled_by" id="propelled_by1"
                                                           value="Petrol" >
                                                    Petrol
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="propelled_by" id="propelled_by2"
                                                           value="Diesel">
                                                    Diesel
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="pump_price" class="col-sm-12 control-label">Pump Price (ZMW/L)</label>
                                    <div class="col-sm-12">
                                        <input type="number" step='any' required class="form-control" name="pump_price"
                                               readonly   id="pump_price" placeholder="Enter the Pump Price" >
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="covered_kilometers" class="col-sm-12 control-label">Kilometers to be
                                        Covered</label>
                                    <div class="col-sm-12">
                                        <input type="number" required class="form-control" name="covered_kilometers"
                                               id="covered_kilometers" placeholder="Enter the Kilometers to be Covered">
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="covered_kilometers" class="col-sm-12 control-label">Claim Amount</label>
                                    <div class="col-sm-12">
                                        <input type="number" required class="form-control" name="claim_amount"
                                               id="claim_amount" readonly >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="destination" class="col-sm-12 control-label">File Uploads</label>
                                    <div class="col-sm-12">
                                        <input type="file" required multiple class="form-control" name="quotation[]"
                                               id="file_upload" placeholder="Upload a file">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-12">
                                <hr>
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
                        <div class="col-2"><label>HR/Senior Manager:</label></div>
                        <div class="col-3"><input type="text" name="station_manager" readonly class="form-control">
                        </div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" name="sig_of_station_manager" readonly
                                                  class="form-control"></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="text" name="manager_date" readonly class="form-control"></div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-2"><label>Chief Accountant:</label></div>
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
                        <div id="possible8" class="col-12 text-center">
                            <input class="btn btn-lg btn-success" type="submit"
                                   value="Submit Claim"
                                   name="submit_form" class="form-control">
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

                //check if vehicle requisition is below 2000
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

    <script>
        $('#propelled_by1').change(function(){
            if($('#propelled_by1').val()=="Petrol" ){
                $('#pump_price').val('17.62')
                $('#covered_kilometers').val('')
            }
        });
    </script>
    <script>
        $('#propelled_by1').change(function(){
            if($('#propelled_by1').val()=="Petrol" ){
                $('#pump_price').attr('readonly')
            }
        });
    </script>
    <script>
        $('#propelled_by2').change(function(){
            if($('#propelled_by2').val()=="Diesel" ){
                $('#pump_price').val('15.59')
                $('#covered_kilometers').val('')

            }
        });
    </script>
    <script>
        $('#propelled_by2').change(function(){
            if($('#propelled_by2').val()=="Diesel" ){
                $('#pump_price').attr('readonly')
                $('#covered_kilometers').val('')
            }
        });
    </script>

    <script>
        $('#covered_kilometers').change(function(){
            if($('#propelled_by2').val()=="Diesel" ){
                var pump_price_field = document.getElementById("pump_price");
                var pumpPrice = pump_price_field.value;
                var kilometers = document.getElementById("covered_kilometers");
                var kilometersOutput = kilometers.value;
                var totAmount =  parseFloat(pumpPrice) * parseFloat(kilometersOutput) ;
                $('#claim_amount').val(totAmount);

            }
        });
    </script>
    <script>
        $('#claim_amount').change(function(){
            if($('#propelled_by2').val()=="Diesel" ){
                $('#claim_amount').attr('readonly')
            }
        });
    </script>







@endpush

