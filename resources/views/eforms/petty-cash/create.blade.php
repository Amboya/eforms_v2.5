@extends('layouts.eforms.petty-cash.master')

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
                    <h1 class="m-0 text-dark">New Petty Cash</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('petty-cash-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">New Petty Cash</li>
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
            <form id="create_form" name="db1" action="{{route('petty-cash-store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
                           class="mt-2 mb-4">
                        <thead>
                        <tr>
                            <th width="33%" colspan="1" class="text-center"><a href="#"><img
                                        src="{{ asset('dashboard/dist/img/zesco1.png')}}" title="ZESCO" alt="ZESCO"
                                        width="25%"></a></th>
                            <th width="33%" colspan="4" class="text-center">Petty Cash Voucher</th>
                            <th width="34%" colspan="1" class="p-3">Doc Number:<br>CO.14900.FORM.00165<br>Version: 3
                            </th>
                        </tr>
                        </thead>
                    </table>

                    <div class="row">
                        <div class="row mt-2 mb-4">
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12"><label>Date:</label></div>
                                    <div class="col-12"><input value="{{ date('Y-m-d H:i:s') }}" type="text" name="date"
                                                               readonly class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12 "><label>Cost Center:</label></div>
                                    <div class="col-12"><input type="text" name="cost_center" class="form-control"
                                                               value="{{ $user->user_unit->user_unit_code}}" readonly
                                                               required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12"><label>HQMS No:</label></div>
                                    <div class="col-12"><input type="text" name="ref_no"
                                                               placeholder="Enter Your HQMS Number (optional)"
                                                               class="form-control"></div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row">
                                    <div class="col-12"><label>Project Number:</label></div>
                                    <div class="col-12">
                                        <select name="projects_id"  class="form-control">
                                            <option disabled >Select Project (Optional)</option>
                                            @foreach($projects as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="table-responsive">
                            <div class="col-lg-12 ">
                                <table class="table bg-green">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>DETAILS OF PAYMENT</th>
                                        <th>AMOUNT</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-lg-12 ">
                                <TABLE id="dataTable" class="table">
                                    <TR>
                                        <TD><INPUT type="checkbox" name="chk"/></TD>
                                        <TD>
                                            {{--                                        <input type="text" name="name[]" class="form-control amount" placeholder="Item Details" id="name" required >--}}
                                            <textarea rows="4" type="text" name="name[]" class="form-control amount"
                                                      placeholder="Item Details / Description" id="name"
                                                      required></textarea>
                                        </TD>
                                        <TD><input type="number" step="any" id="amount" name="amount[]" onchange="getvalues()"
                                                   class="form-control amount" placeholder="Amount [ZMW]">
                                        </TD>
                                    </TR>
                                </TABLE>
                            </div>
                            <div class="col-lg-6 offset-6 ">
                                <div class="row">
                                    <div class="col-4 text-right">
                                        <label class="form-control-label">TOTAL PAYMENT </label>
                                    </div>
                                    <div class="col-8">
                                        <input type="text" class="form-control text-bold" readonly id="total-payment"
                                               name="total_payment" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 ">
                                <INPUT type="button" value="Add Row" onclick="addRow('dataTable')"/>
                                <INPUT type="button" value="Delete Row" onclick="deleteRow('dataTable')"/>
                            </div>
                            <div class="col-lg-12 mb-4">
                                <div class="row">
                                    <div class="col-2 offset-4">
                                        <label class="form-control-label">Attach Quotation Files (optional)</label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="file" class="form-control" multiple name="quotation[]" id="receipt" title="Upload Quotation Files (Optional)" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
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
                        <div id="submit_not_possible" class="col-12 text-center">
                            <div class="alert alert-danger ">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    &times;
                                </button>
                                <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                Sorry, You can not submit <strong>petty cash above K2000</strong>
                            </div>
                        </div>
                        <div id="submit_possible" class="col-12 text-center">
                            <div id="divSubmit_show">
                                <input class="btn btn-lg btn-success" type="submit"
                                       value="submit" id="btnSubmit"
                                       name="submit_form">
                            </div>
                            <div id="divSubmit_hide" >
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


    <script>
        $(document).ready(function () {
            $("#divSubmit_hide").hide();
            //disable the submit button
            $("#btnSubmit").on('click', function () {
                $("#create_form").submit(function (e) {
                    e.preventDefault()
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
