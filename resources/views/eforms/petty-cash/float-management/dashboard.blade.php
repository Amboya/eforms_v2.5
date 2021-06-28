@extends('layouts.eforms.petty-cash.master')

@push('custom-styles')
  <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Petty Cash Float</h1>
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
                            <span><i class="fa fa-building"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Total Units</span>
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
                        <!-- /.info-box-content -->
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
                            <span class="info-box-number">ZMW {{ number_format($totals['total_utilised'], 2) }}</span>
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
                            <span class="info-box-number"> ZMW {{ number_format( $totals['total_cash']) }}</span>
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
                <!-- Left col -->
                <div class="col-md-12 mt-3">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent  bg-gradient-green " style="opacity: .9">
                            <h3 class="card-title">User Units Transactions </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-2">
                            <div class="table-responsive mt-10 ">
                                <table id="example12" class="table ">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User Unit</th>
                                        <th>Float</th>
                                        <th>Utilised</th>
                                        <th>Cash</th>
                                        <td>Action</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $list as $key => $item )
                                        <tr>
                                            <td>{{++$key}}
                                            </td>
                                            <td width="50%">{{$item->user_unit_code}} : {{$item->user_unit->user_unit_description}}</td>
                                            <td width="10%">ZMW {{number_format($item->float,2)}}</td>
                                            <td width="10%">ZMW {{number_format($item->utilised,2) }}</td>
                                            <td width="10%">ZMW {{number_format($item->cash,2) }} </td>
                                            <td width="15%" >
                                                <button class="btn btn-sm bg-gradient-gray " style="margin: 1px"
                                                        title="Mark as Void."
                                                        data-toggle="modal" data-sent_data="{{$item}}"
                                                        data-target="#modal-float">
                                                    <i class="fa fa-briefcase"></i> Float
                                                </button>
                                                <a class="btn btn-sm bg-gradient-gray "  href="{{route('petty.cash.float.reimbursement.show', $item->id)}}"
                                                   title="Sync Application Forms">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
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
    <div class="modal fade" id="modal-float">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Petty Cash Float</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form id="float_form"  method="post"
                      action="{{route('petty.cash.float.update')}} "  enctype="multipart/form-data">
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Current Float</label>
                                    <input type="number" class="form-control" id="current_float" name="current_float" readonly>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>New Float</label>
                                    <input type="number" step="any" class="form-control" id="new_float" name="new_float"
                                           placeholder="Enter New Float" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit"  id="btnSubmit" class="btn btn-primary">Submit</button>
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
        $('#modal-float').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes
            $('#user_unit_field').val(recipient.id);
            $('#current_float').val(recipient.float);
            $('#user_unit').val(recipient.user_unit.user_unit_code  +" : "+recipient.user_unit.user_unit_description );
        });
    </script>


    <script>
        $(document).ready(function () {
            //disable the submit button
            $("#btnSubmit").on('click', function () {
                $("#float_form").submit(function (e) {
                    e.preventDefault()
                    //do something here
                    var new_float = parseFloat( document.getElementById('new_float').value );
                    var current_float = parseFloat(  document.getElementById('current_float').value ) ;
                    var diff = (current_float) - (new_float) ;
                    if (!isNaN(new_float)) {
                        //check if petty cash is below 2000
                        if (( new_float) > (current_float) )  {
                            e.currentTarget.submit();
                        } else {
                            if (confirm('Are you sure you want to save this new Float('+new_float+' ) which is lower than the Current Float('+current_float+' ) by '+diff+' ?')) {
                                e.currentTarget.submit();
                            } else {
                                // Do nothing!
                                console.log('Thing was not saved to the database.');
                            }
                        }
                    }
                });
            });

        });
    </script>



@endpush
