@extends('layouts.eforms.petty-cash.master')

@push('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Petty Cash Float Reimbursement</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('petty.cash.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Petty Cash Float</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <section class="content">

        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible">
                <p class="lead"> {!! session()->get('message') !!}</p>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-info alert-dismissible">
                <p class="lead"> {!!  session()->get('error') !!}</p>
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

        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="#">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Total Transactions</span>
                            <span class="info-box-number">{{ $totals['total_units'] }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="#">
                            <span><i class="fa fa-suitcase"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Total Float</span>
                            <span class="info-box-number">ZMW {{ number_format($totals['total_float'], 2) }}</span>
                        </div>
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="#">
                            <span><i class="fa fa-shopping-cart"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Total Utilised</span>
                            <span class="info-box-number">ZMW {{ number_format($totals['total_utilised']) }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="#">
                            <span><i class="fa fa-money-bill"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Total Cash</span>
                            <span class="info-box-number"> ZMW {{ number_format($totals['total_cash']) }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>


                <!-- /.col -->

            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <div class="col-md-12 mt-3">
                    <button type="button" class="btn btn-sidebar bg-gradient-orange "
                            title="Initiate Reimbursement."
                            data-toggle="modal" data-sent_data="{{$model}}"
                            data-target="#modal-reimbursement">
                        <i class="fas fa-redo"></i> Reimbursement
                    </button>
                </div>
            </div>

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12 mt-3">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent bg-gradient-green  " style="opacity: .9">
                            <h3 class="card-title">Requested Reimbursement of
                                ZMW {{ number_format($total_amount, 2) }}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table id="example1" class="table ">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>UserUnit</th>
                                        <th>Claimant</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Period</th>
                                        <td>View</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $list as $item )
                                        <tr>
                                            <td><a href="{{ route('logout') }}" class="dropdown-item"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form'+{{$item->id}}).submit();"> {{$item->code}}</a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('petty.cash.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </td>
                                            <td>{{$item->user_unit_code}}
                                                : {{$item->user_unit->user_unit_description}}</td>
                                            <td>{{$item->claimant_name}}</td>
                                            <td>ZMW {{ number_format($item->total_payment  - $item->change, 2)}}</td>
                                            <td><span
                                                    class="badge badge-{{$item->status->html}}">{{$item->status->name}}</span>
                                            </td>
                                            <td>{{$item->created_at->diffForHumans()}}</td>
                                            <td><a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form'+{{$item->id}}).submit();">
                                                    view</a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('petty.cash.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002')  ||   Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_0013')   )
                                @if($pending < 1)
                                    <a href="{{route('petty.cash.create')}}"
                                       class="btn btn-sm bg-gradient-green float-left">New Petty Cash Float</a>
                                @else
                                    <a href="#" class="btn btn-sm btn-default float-left">New Petty Cash Float</a>
                                    <span class="text-danger m-3"> Sorry, You can not raise a new petty cash because you already have an open petty cash.</span>
                                @endif
                            @endif
                            {{--                                {!! $list->links() !!}--}}
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->

    <!-- ADD modal -->
    <div class="modal fade" id="modal-reimbursement">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Petty Cash Float Reimbursement</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form id="float_form" method="post"
                      action="{{route('petty.cash.float.reimbursement.store')}} " enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>User Unit</label>
                                    <input type="text" class="form-control" id="user_unit" name="user_unit"
                                           readonly>
                                    <input hidden class="form-control" id="user_unit_field" name="user_unit_field">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Float</label>
                                    <input type="number" class="form-control" id="current_float" name="current_float"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Utilised</label>
                                    <input type="number" step="any" class="form-control" id="utilised" name="utilised"
                                           placeholder="Enter New Utilised" readonly required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Cash</label>
                                    <input type="number" step="any" class="form-control" id="cash" name="cash"
                                           placeholder="Enter New Cash" readonly required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Requested</label>
                                    <input type="text" step="any" class="form-control" id="requested_float"
                                           name="requested_float"
                                           readonly required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Amount Given</label>
                                    <input type="number" onchange="simulate_cash_change(this.value)" step="any" class="form-control" id="float_given"
                                           name="float_given"
                                           placeholder="Enter Amount " required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="date"  class="form-control" id="date_from"
                                           name="date_from" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="date"  class="form-control" id="date_to"
                                           name="date_to" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Reason</label>
                                    <textarea class="form-control" id="reason" name="reason"
                                              placeholder="Enter Reason" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.ADD modal -->



@endsection


@push('custom-scripts')

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

    <!-- page script -->
    <script>
        $(function () {

            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>

    <script>
        $('#modal-reimbursement').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes
            var requested_amount = {!! json_encode( number_format($total_amount), 2) !!};

            $('#user_unit_field').val(recipient.id);
            $('#current_float').val(recipient.float);
            $('#utilised').val(recipient.utilised);
            $('#cash').val(recipient.cash);
            $('#user_unit').val(recipient.user_unit.user_unit_code + " : " + recipient.user_unit.user_unit_description);
            $('#requested_float').val("ZMW " + requested_amount);
        });

        function simulate_cash_change(amount) {

            var float = {!! json_encode( $model->float) !!};
            var utilised = {!! json_encode( $model->utilised) !!};
            var cash = {!! json_encode($model->cash) !!};

            var asdf = parseFloat(cash) + parseFloat(amount)
            var lksdf = parseFloat(utilised) - parseFloat(amount)

            $('#utilised').val(lksdf);
            $('#cash').val(asdf);
            }
    </script>



@endpush
