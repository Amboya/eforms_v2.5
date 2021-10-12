@extends('layouts.eforms.datacenter-ca.master')


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
                    <h1 class="m-0 text-dark">Data Center Critical Assets Form [{{$form->code}}]</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('petty.cash.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Data Center Critical Assets Form Details</li>
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
            <form name="db1" action="#" method="post" enctype="multipart/form-data">
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
                                <input type="text" readonly class="form-control" id="asset_name" name="asset_name" required maxlength="100"
                                       value="{{$form->asset_name}}">
                            </div>
                            <div class="col-6 form-group mt-4">
                                <label for="asset_category"> ASSET CATEGORY/ APPLICATION: <span
                                        class="required">*</span></label>
                                <input type="text" readonly class="form-control" id="asset_category" name="asset_category"
                                       value="{{$form->asset_category}}">
                            </div>
                            <div class="col-6 form-group">
                                <label for="rack_location"> RACK LOCATION: <span class="required">*</span></label>
                                <input type="text" readonly class="form-control" id="rack_location" name="rack_location"
                                       value="{{$form->rack_location}}">
                            </div>
                            <div class="col-6 form-group">
                                <label for="criticality"> CRITICALITY: <span class="required">*</span></label>
                                <input type="text" readonly class="form-control" id="criticality" name="criticality"
                                       value="{{$form->criticality}}">
                            </div>
                            <div class="col-6 form-group mb-2">
                                <label for="physical_location"> PHYSICAL LOCATION: <span
                                        class="required">*</span></label>
                                <input value="{{$form->physical_location}}" type="text" class="form-control" id="physical_location" name="physical_location"
                                     readonly >

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
                                   value="{{$form->staff_name}}" readonly required></div>
                        <div class="col-2 text-center"><label>Signature:</label></div>
                        <div class="col-2"><input type="text" name="sig_of_claimant" class="form-control"
                                                  value="{{$form->staff_no}}"  readonly required></div>
                        <div class="col-1 text-center"><label>Date:</label></div>
                        <div class="col-2"><input type="Date" name="date_claimant" class="form-control"
                                                  value="{{$form->submitted_date}}"   readonly required>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer">

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
        var total = 0;
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            total = total + parseFloat(inp.value || 0);
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


    function getvalues1() {
        var inps = document.getElementsByName('debited_amount[]');
        var total = 0;
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            total = total + parseFloat(inp.value || 0);
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

            //check if petty cash accounts is equal to total_payment
            if (change_value > 0) {
                $('#show_change').show();
                //set value
                document.getElementById('credited_amount1').value = change_value;
                document.getElementById('debited_amount1').value = 0;
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

@endpush
