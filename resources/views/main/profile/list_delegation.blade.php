@extends('layouts.main.master')


@push('custom-styles')
    <!-- DataTables -->

@endpush


@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Profile Delegation</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Profile Delegation</li>
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
                <span>List of my Delegation</span>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Form</th>
                            <th>Profile Owner</th>
                            <th>Delegating</th>
                            <th>Unit</th>
                            <th>JobCode</th>
                            <th>Profile</th>
                            <th>Profile <br> Status</th>
                            <th>Created At</th>
                            <th>Delegation Start</th>
                            <th>Delegation End</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($delegation as $key => $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->form->name ?? ""}} </td>
                                <td>{{$item->me->name ?? ""}} </td>
                                <td>{{$item->delegation->name ?? ""}} </td>
                                <td>{{$item->user_unit->user_unit_code ?? ""}}: {{$item->user_unit->user_unit_description ?? ""}}  </td>
                                <td>{{$item->delegated_job_code}}</td>
                                <td>{{$item->profile->name}} </td>
                                <td>
                                    <span class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                </td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->created_at->diffForHumans()}}</td>
                                <td>{{ $item->delegation_end }}</td>
                                <td>
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
            </div>
            <div class="card-footer">

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->


    @foreach($delegation as $item)
        <!-- VOID MODAL-->
        <div class="modal fade" id="modal-delete{{$item->id}}">
            <div class="modal-dialog modal-md">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">End This Delegation</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('main.profile.delegation.end', ['id' => $item->id])}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-center">Are you sure you want to end this profile delegation? </p>
                                    <p class="text-center">Note that you can not undo this action. </p>
                                </div>

                                <div class="col-2">
                                    <label>Reason</label>
                                </div>
                                <div class="col-10">
                                    <div class="input-group">
                                        <textarea class="form-control" rows="2" name="reason"
                                                  placeholder="Enter reason why" required>
                                        </textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Mark</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.VOID modal -->
    @endforeach



@endsection


@push('custom-scripts')

    <!-- DataTables -->
    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

    <!-- page script -->

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
