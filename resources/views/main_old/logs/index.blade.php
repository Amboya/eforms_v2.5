@extends('layouts.main.master')


@push('custom-styles')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endpush


@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Activity Logs</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Activity Logs</li>
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
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fas fa-minus"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Form</th>
                        <th>IP</th>
                        <th>Device Type</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Period</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $key => $item)
                        <tr>
                            <td>{{++$key ?? ""}}</td>
                            <td> {{$item->eform_code ?? ""}}</td>
                            <td>  {{$item->ip_address ?? ""}}</td>
                            <td>  {{$item->device_type ?? ""}}</td>
                            <td>   {{$item->username ?? ""}}</td>
                            <td>   {{$item->action_name ?? ""}}</td>
                            <td>   {{$item->country ?? ""}}</td>
                            <td>   {{$item->city ?? ""}}</td>
                            <td> {{ $item->created_at->diffForHumans()}}</td>
                            <td>
                                <a class="btn btn-sm bg-gradient-gray float-left " style="margin: 1px"
                                        title="Edit"
                                        href="{{route('main-logs-show', ['id'=> $item->id])}}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <button class="btn btn-sm bg-gradient-gray float-left" style="margin: 1px"
                                        title="Delete"
                                        data-toggle="modal"
                                        data-target="#modal-delete{{$item->id}}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach


                    </tbody>

                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->


    @foreach($list as $item)
        <!-- DELETE MODAL-->
        <div class="modal fade" id="modal-delete{{$item->id}}">
            <div class="modal-dialog modal-sm">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Delete eform Category</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('main-eforms-category-destroy', ['id' => $item->id])}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-center">Are you sure you want to delete? </p>
                                    <p class="text-center">Note that you can not undo this action. </p>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.NEW modal -->
    @endforeach


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


@endpush
