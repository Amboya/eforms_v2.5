@extends('layouts.eforms.subsistence.master')


@push('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endpush


@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark text-orange text-uppercase">SUBSISTENCE DUPLICATES : <span class="text-green">{{$category}}</span></h1>
                    <span class="text-orange text-bold">{{$date_range}}</span>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('subsistence.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">SUBSISTENCE {{$category}}</li>
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
            <div class="card-header">

            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">

                    <table id="example1" class="table m-0">
                        <thead class="bg-gradient-green">
                        <tr>
                            <th>#</th>
                            <th>Serial</th>
                            <th>Claimant</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach( $list as $item )
                            <tr>
                                <td>{{++$total_num}}</td>
                                <td><a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                        document.getElementById('show-form'+{{$item->id}}).submit();"> {{$item->code}}</a>
                                    <form id="show-form{{$item->id}}" action="{{ route('subsistence.show', $item->id) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </td>
                                <td>{{$item->claimant_name}}</td>
                                <td>@money($item->total ) </td>
                                <td><span class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                </td>
                                <td>{{$item->updated_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{ route('logout') }}" class="btn btn-sm bg-orange" onclick="event.preventDefault();
                                        document.getElementById('show-form'+{{$item->id}}).submit();"> View </a>
                                    <form id="show-form{{$item->id}}" action="{{ route('subsistence.show', $item->id) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    @if(Auth::user()->type_id == config('constants.user_types.developer'))
                                        <button class="btn btn-sm bg-gradient-gray " style="margin: 1px"
                                                title="Mark as Void."
                                                data-toggle="modal"
                                                data-target="#modal-void{{$item->id}}">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                        <button class="btn btn-sm bg-gradient-gray " style="margin: 1px"
                                                title="Reverse Form to the previous state."
                                                data-toggle="modal"
                                                data-target="#modal-reverse{{$item->id}}">
                                            <i class="fa fa-redo"></i>
                                        </button>
                                        <a class="btn btn-sm bg-gradient-gray "  href="{{route('subsistence.sync', $item->id)}}"
                                           title="Sync Application Forms">
                                            <i class="fas fa-sync"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot class="bg-gradient-orange">
                        <tr>
                            <td><b>{{$total_num}}</b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b> @money($list->sum('total'))</b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                        </tr>
                        </tfoot>

                    </table>
                    @if(Auth::user()->type_id != config('constants.user_types.developer'))
                        {{--                            {!! $list->links() !!}--}}
                    @else

                    @endif
                </div>
            </div>

        </div>
        <!-- /.card -->


    </section>
    <!-- /.content -->



@endsection


@push('custom-scripts')

    <!-- DataTables -->
    {{--    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>--}}
    {{--    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>--}}
    {{--    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>--}}
    {{--    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>--}}

    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
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
                "buttons": ["csv", "excel", "pdf", "print"]
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
        function myFunction() {
            // Declare variables

            var input, filter, table, tr, td, th, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr"),
                th = table.getElementsByTagName("th");

            // Loop through all table rows, and hide those who don't match the        search query
            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                for (var j = 0; j < th.length; j++) {
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        if (td.innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>






@endpush
