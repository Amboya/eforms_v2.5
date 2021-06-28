@extends('layouts.main.master')


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
                    <h1 class="m-0 text-dark">Activity Details</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('main.logs')}}">Logs</a></li>
                        <li class="breadcrumb-item active">Activity Details</li>
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


        <div class="row">

            <div class="col-8">
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">User Details</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            data-toggle="tooltip"
                                            title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <thead>
                                    <tr>
                                        <th colspan="2"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="myTable1">
                                    <b>Name :</b> {{$activity_log->username ?? ""}}<br>
                                    <b>Email :</b> {{$activity_log->user_email ?? ""}}<br>
                                    <b>Type :</b> {{$activity_log->type ?? ""}}<br>
                                    <b>Role :</b> {{$activity_log->role ?? ""}}<br>
                                    <b>Station :</b> {{$activity_log->station ?? ""}}<br>
                                    <b>Form :</b> {{$activity_log->eform_code ?? ""}}<br>
                                    <b>Date Created :</b> {{$activity_log->created_at ?? ""}}<br>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Device Details</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            data-toggle="tooltip"
                                            title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <thead>
                                    <tr>
                                        <th colspan="2"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="myTable1">
                                    <b>IP Address:</b> {{$activity_log->ip_address ?? ""}}<br>
                                    <b>Device:</b> {{$activity_log->device ?? ""}}<br>
                                    <b>Device Type:</b> {{$activity_log->device_type ?? ""}}<br>
                                    <b>OS:</b> {{$activity_log->os ?? ""}}<br>
                                    <b>OS Version:</b> {{$activity_log->device_type ?? ""}}<br>
                                    <b>Browser:</b> {{$activity_log->browser ?? ""}}<br>
                                    <b>Browser Version:</b> {{$activity_log->browser_version ?? ""}}<br>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Activity Details</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                            data-toggle="tooltip"
                                            title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <thead>
                                        <tr>
                                            <th colspan="2"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="myTable1">
                                        <b>ID:</b> {{$activity_log->id ?? ""}}<br>
                                        <b>Method:</b> {{$activity_log->request_method ?? ""}}<br>
                                        <b>URL:</b> {{$activity_log->route_url ?? ""}}<br>
                                        <b>Previous URL:</b> {{$activity_log->previous_url ?? ""}}<br>
                                        <b>Request Params:</b> {{$activity_log->request_params ?? ""}}<br>
                                        <b>Action:</b> {{$activity_log->action_name ?? ""}}<br>
                                        <b>Comment:</b> {{$activity_log->comment ?? ""}}<br>
                                        <b>Meta Data: </b> {{$activity_log->meta_data ?? ""}}<br>
                                        <b>Status:</b> {{$activity_log->status ?? ""}}<br>
                                        <b>Period :</b> {{$activity_log->created_at->diffForHumans() ?? ""}}<br>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Location Details</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip"
                                    title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th colspan="2"></th>
                                </tr>
                                </thead>
                                <tbody id="myTable1">
                                <b>ISO Code:</b> {{$activity_log->iso_code ?? ""}}<br>
                                <b>Country:</b> {{$activity_log->country ?? ""}}<br>
                                <b>City:</b> {{$activity_log->city ?? ""}}<br>
                                <b>State Name:</b> {{$activity_log->state_name ?? ""}}<br>
                                <b>Postal Code:</b> {{$activity_log->postal_code ?? ""}}<br>
                                <b>Latitude:</b> {{$activity_log->latitude ?? ""}}<br>
                                <b>Longitude:</b> {{$activity_log->longitude ?? ""}}<br>
                                <b>Timezone:</b> {{$activity_log->timezone ?? ""}}<br>
                                <b>Continent:</b> {{$activity_log->continent ?? ""}}<br>
                                <b>Currency:</b> {{$activity_log->currency ?? ""}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Additional User Activity Logs</h3>
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
                            @foreach($activity_logs as $key => $item)
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td> {{$item->id ?? ''}} </td>
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
                                           href="{{route('main.logs.show', ['id'=> $item->id])}}">
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
                            {{--<tfoot>--}}
                            {{--<tr>--}}
                            {{--<th>id</th>--}}
                            {{--<th>Name</th>--}}
                            {{--<th>Created By</th>--}}
                            {{--<th>Created At</th>--}}
                            {{--<th>Period</th>--}}
                            {{--</tr>--}}
                            {{--</tfoot>--}}
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>


    </section>
    <!-- /.content -->



    <!-- NEW MODAL-->
    <div class="modal fade" id="modal-create">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Create eform Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main.eforms.category.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Category Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter Category name" required>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.NEW modal -->



    <!-- EDIT MODAL-->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Update eform Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main.eforms.category.update')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Category Name</label>
                                    <input type="text" class="form-control" id="edit_name" name="name"
                                           placeholder="Enter Category name" required>
                                </div>
                                <input hidden type="text" class="form-control" id="edit_id" name="category_id"
                                       required>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.Edit modal -->


    @foreach($activity_logs as $item)
        <!-- DELETE MODAL-->
        <div class="modal fade" id="modal-delete{{$item->id}}">
            <div class="modal-dialog modal-sm">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Delete eform Category</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('main.eforms.category.destroy', ['id' => $item->id])}}">
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

    <script>
        $('#modal-edit').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes
            $('#edit_name').val(recipient.name);
            $('#edit_id').val(recipient.id);
        });

    </script>

@endpush
