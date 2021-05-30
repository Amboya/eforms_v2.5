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

                    <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
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

                    <div class="row mt-2 mb-2">
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12"><label>Name:</label></div>
                                <div class="col-12"><input type="text" name="staff_name" value="{{Auth::user()->name}}"
                                                           readonly class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12 "><label>Man No:</label></div>
                                <div class="col-12"><input type="text" name="staff_no" class="form-control"
                                                           value="{{Auth::user()->staff_no}}" readonly
                                                           required>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row">
                                <div class="col-12"><label>Grade:</label></div>
                                <div class="col-12"><input type="text" name="grade" class="form-control"
                                                           value="{{Auth::user()->grade->name}}" readonly
                                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="row">
                                <div class="col-12"><label>Directorate:</label></div>
                                <div class="col-12"><input type="text" name="directorate" class="form-control"
                                                           value="{{Auth::user()->directorate->name}}" readonly
                                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2 mb-4">

                        <div class="col-3">
                            <div class="row">
                                <div class="col-12"><label>Cost Centre:</label></div>
                                <div class="col-12"><input type="text" name="ref_no" class="form-control"
                                                           value="{{ $user->user_unit->user_unit_code}}" readonly
                                                           required>
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="col-12"><label>Hotel Name:</label></div>
                            <select id="hotel" class="form-control select2 " name="hotel"  >
                                <option value="" selected disabled >Select Hotel</option>
                                @foreach($hotel as $item)
                                    <option value="{{$item->code_supplier}}" >{{$item->code_supplier}}: {{$item->name_supplier}} </option>
                                @endforeach
                            </select>

                            @error('hotel')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror

                        </div>

                        <div class="col-3">
                            <div class="row">
                                <div class="col-12"><label>Sys RefNo:(Optional)</label></div>
                                <div class="col-12"><input type="text" name="ref_no"
                                                           placeholder="Enter System RefNo (optional)"
                                                           class="form-control"></div>
                            </div>
                        </div>

                    </div>


                    <div class="col-lg-12 grid-margin stretch-card">
                        <div class="table-responsive">
                            <div class="col-lg-12 ">
                                <table class="table bg-green">
                                    <thead>
                                    <tr>
                                        <th>PURPOSE OF JOURNEY</th>
                                        <th>AMOUNT CLAIMED</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="col-lg-12 ">
                                <TABLE class="table">
                                    <TR>
                                        <TD>
                                            <textarea rows="4" type="text" name="purpose_of_journey"
                                                      class="form-control amount"
                                                      placeholder="Enter Purpose of Journey" id="name"
                                                      required></textarea>
                                        </TD>
                                        <TD><input type="number" id="amount1" name="amount" onchange="getvalues()"
                                                   class="form-control amount" placeholder="Amount [ZMW]">
                                        </TD>
                                    </TR>
                                </TABLE>
                            </div>

                            <div class="row mt-2 mb-4">
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12"><label>Estimated Period of Stay (Days):</label></div>
                                        <div class="col-12"><input type="number" name="estimated_period_of_stay" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12"><label>Estimated Cost:</label></div>
                                        <div class="col-12"><input type="number" id="estimated_cost" name="estimated_cost" class="form-control"
                                            value="" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12"><label>BEING PAYMENT:</label></div>
                                        <div class="col-12"><input type="text" class="form-control text-bold" readonly id="total-payment"
                                                                   name="amount_claimed" value="">
                                    </div>
                                </div>
                            </div>
                            </div>

                            <div class="col-lg-12 mb-4">
                                <div class="row">
                                    <div class="col-2 offset-4">
                                        <label class="form-control-label">Attach Quotation Files (optional)</label>
                                    </div>
                                    <div class="col-6">
                                        <div class="input-group">
                                            <input type="file" class="form-control" multiple name="quotation[]"
                                                   id="receipt" title="Upload Quotation Files (Optional)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <div class="row mb-1 mt-4">
                            <div class="col-2"> <label>Employee Name:</label> </div>
                            <div class="col-3">
                                <input type="text" name="employee_name" class="form-control"
                                       value="{{Auth::user()->name}}" readonly required></div>

                            <div class="col-2 text-center"><label>Signature:</label></div>
                            <div class="col-2"><input type="text" name="employee_staff_no" class="form-control"
                                                      value="{{Auth::user()->staff_no}}" readonly required></div>
                            <div class="col-1 text-center"><label>Date:</label></div>

                            <div class="col-2"><input type="Date" name="claim_date" class="form-control"
                                                      value="{{date('Y-m-d')}}" readonly required>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <div class="col-2"><label>Name of HOD:</label></div>
                            <div class="col-3"><input type="text" name="hod_name" readonly class="form-control">
                            </div>
                            <div class="col-2 text-center"><label>Signature:</label></div>
                            <div class="col-2"><input type="text" name="hod_staff_no" readonly class="form-control">
                            </div>
                            <div class="col-1  text-center"><label>Date:</label></div>
                            <div class="col-2"><input type="text" name="hod_authorised_date" readonly
                                                      class="form-control">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2"><label>Approved by Director:</label></div>
                            <div class="col-3"><input type="text" name="director_name" readonly class="form-control">
                            </div>
                            <div class="col-2 text-center"><label>Signature:</label></div>
                            <div class="col-2"><input type="text" name="director_staff_no" readonly
                                                      class="form-control"></div>
                            <div class="col-1 text-center"><label>Date:</label></div>
                            <div class="col-2"><input type="text" name="director_authorised_date" readonly
                                                      class="form-control"></div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-2"><label>Name of Chief Accountant:</label></div>
                            <div class="col-3"><input type="text" name="chief_accountant_name" readonly
                                                      class="form-control"></div>
                            <div class="col-2 text-center"><label>Signature:</label></div>
                            <div class="col-2"><input type="text" name="chief_staff_no" readonly class="form-control">
                            </div>
                            <div class="col-1 text-center"><label>Date:</label></div>
                            <div class="col-2"><input type="text" name="chief_accountant_authorised_date" readonly
                                                      class="form-control">
                            </div>
                        </div>


                    </div>



                    <table border="0" width="100%" cellspacing="0" cellpadding="0" align="Centre">
                        <tr>
                            <td height="5" colspan="6"></td>
                        </tr>

                        <td colspan="6"><p><b>Imprest Upon Being Accomodated in a Hotel:</b></p></td>


                        <tr>
                            <td colspan="2"><p><b>Note:</b> To be Filled in Duplicate.<br>
                                    First Copy to the Chief Accountant for Payment.<br>
                                    Second Copy to Finance for Order Processing<br>
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
            var inps = document.getElementById('amount1');

            var total = parseFloat(inps.value || 0);




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
                document.getElementById('estimated_cost').value = total;
            }
        }


        // Navigation Script Starts Here
        $(document).ready(function () {

            //first hide the buttons
            $('#submit_possible').hide();
            $('#submit_not_possible').hide();

        });

    </script>


@endpush
