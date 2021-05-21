@extends('layouts.eforms.hotel-accommodation.master')


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
                    <h1 class="m-0 text-dark">Hotel Accommodation : {{$category}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Hotel Accommodation  : {{$category}}</li>
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
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row">
                        <div class="col-6 offset-6">
                        <input type="text" class="form-control m-2" id="myInput" onkeyup="myFunction()" placeholder="Search ..">
                    </div>
                    </div>

                @if(Auth::user()->type_id != config('constants.user_types.developer'))
                        <table id="myTable" class="table m-0">
                            @else
                                <table id="example1" class="table m-0">
                                    @endif
                                    <thead>
                                    <tr>
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
                                            <td><a href="{{ route('logout') }}" class="dropdown-item"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form'+{{$item->id}}).submit();"> {{$item->code}}</a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('hotel.accommodation.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </td>
                                            <td>{{$item->claimant_name}}</td>
                                            <td>{{$item->total_payment}}</td>
                                            <td><span
                                                    class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                            </td>
                                            <td>{{ $item->updated_at }}</td>
                                            <td><a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form'+{{$item->id}}).submit();">
                                                    View </a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('hotel.accommodation.show', $item->id) }}"
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
                                                    <a class="btn btn-sm bg-gradient-gray "  href="{{route('hotel.accommodation.sync', $item->id)}}"
                                                       title="Sync Application Forms">
                                                        <i class="fas fa-sync"></i>
                                                    </a>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                        @if(Auth::user()->type_id != config('constants.user_types.developer'))
                            {!! $list->links() !!}
                        @else

                        @endif
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002'))
                    @if($pending < 1)
                        <a href="{{route('hotel.accommodation.create')}}"
                           class="btn btn-sm bg-gradient-green float-left">Hotel Accommodation </a>
                    @else
                        <a href="#" class="btn btn-sm btn-default float-left">Hotel Accommodation </a>
                        <span class="text-danger m-3"> Sorry, You can not raise a new Hotel Accommodation  because you already have an open Hotel Accommodation Transaction.</span>
                    @endif
                @endif
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->


@endsection


@push('custom-scripts')

    <!-- DataTables -->
    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

    <!-- page script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
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
