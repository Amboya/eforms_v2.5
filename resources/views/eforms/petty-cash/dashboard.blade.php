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
                    <h1 class="m-0 text-dark text-uppercase text-green ">Petty Cash Voucher</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Petty Cash</li>
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
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'petty.cash.list', config('constants.petty_cash_status.new_application') ) }}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> New Forms</span>
                            <span class="info-box-number">{{ $totals['new_forms'] }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'petty.cash.list', 'pending')}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Open Forms</span>
                            <span class="info-box-number">{{ $totals['pending_forms'] }}</span>
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
                           href="{{route( 'petty.cash.list', config('constants.petty_cash_status.closed'))}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Closed Forms</span>
                            <span class="info-box-number">{{ $totals['closed_forms'] }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'petty.cash.list', config('constants.petty_cash_status.rejected'))}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Rejected Forms</span>
                            <span class="info-box-number">{{ $totals['rejected_forms'] }}</span>
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
                <div class="col-md-12">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent  bg-gradient-orange " style="opacity: .9">
                            <h3 class="card-title">Needs your Attention</h3>  <span
                                class="badge badge-success right ml-2">{{$list->count()}}</span>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-2">
                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_014')   )
                                @if($auditor < 1)
                                @else
                                    <p class="text-danger  mb-10">
                                           Please note that the transactions for the current period cannot be closed because the transactions for the previous period
                                            have not been cleared by Audit. ({{$auditor}} transactions pending).</p>
                                @endif
                            @endif
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
                                            <td><a href="{{ route('logout') }}" class=""
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form'+{{$item->id}}).submit();"> {{$item->code}}</a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('petty.cash.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </td>
                                            <td>{{$item->user_unit_code}} : {{$item->user_unit->user_unit_description}}</td>
                                            <td>
                                                <a href="{{route('main.user.show',$item->created_by)}}" class="text-dark" style="margin: 1px">
                                                {{$item->claimant_name}}
                                                </a>
                                            </td>
                                            <td>ZMW {{ number_format($item->total_payment  - $item->change, 2)}}</td>
                                            <td><span
                                                    class="badge badge-{{$item->status->html}}">{{$item->status->name}}</span>
                                            </td>
                                            <td>{{$item->created_at->diffForHumans()}}</td>
                                            <td><a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                                   onclick="event.preventDefault();
                                                           document.getElementById('show-form'+{{$item->id}}).submit();"> view</a>
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
                            <div class="row ">
                                <div class=" col-lg-12 col-sm-12">
                                @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002')  ||   Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_0013')   )
                                    @if($pending < 1)
                                        <a href="{{route('petty.cash.create')}}"
                                           class="btn btn-sm bg-gradient-green float-left">New Petty Cash</a>
                                    @else
                                        <a href="#" class="btn btn-sm btn-default float-left">New Petty Cash</a>
                                        <span class="text-warning m-3"> You can not raise a new petty cash because you already have an open petty cash.</span>
                                    @endif
                                @endif
                            </div>
                            </div>
                            <div class="row ">
                                <div class="col-lg-12 col-sm-12">
                                <div class="pagination-sm mt-2">
                                {!! $list->links() !!}
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>.
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
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


@endpush
