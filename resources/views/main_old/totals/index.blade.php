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
                    <h1 class="m-0 text-dark">Totals</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Totals</li>
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
                <button class="btn btn-sm bg-gradient-orange float-left" data-toggle="modal"
                        data-target="#modal-create">
                    New Totals
                </button>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fas fa-minus"></i></button>
                    <a class="btn btn-tool" href="{{route('main-totals-sync')}}"
                       title="Sync Positions">
                        <i class="fas fa-sync"></i></a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>EForm</th>
                            <th>Name</th>
                            <th>Value</th>
                            <th>Color</th>
                            <th>Icon</th>
                            <th>Updated At</th>
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $key => $item)
                            <tr>
                                <td>{{$item->id}} </td>
                                <td>{{$item->eform->name ?? "Main Dashboard"}} </td>
                                <td>{{$item->name}} </td>
                                <td>{{$item->value}} </td>
                                <td>{{$item->color}} </td>
                                <td>{{$item->icon}} </td>
                                <td>{{$item->updated_at}}</td>
                                <td>{{$item->updated_at->diffForHumans()}}</td>
                                <td>
{{--                                    <button class="btn btn-sm bg-gradient-gray float-left " style="margin: 1px"--}}
{{--                                            title="Edit"--}}
{{--                                            data-toggle="modal"--}}
{{--                                            data-sent_data="{{$item}}"--}}
{{--                                            data-target="#modal-view">--}}
{{--                                        <i class="fa fa-eye"></i>--}}
{{--                                    </button>--}}
                                    <button class="btn btn-sm bg-gradient-gray float-left " style="margin: 1px"
                                            title="Edit"
                                            data-toggle="modal"
                                            data-sent_data="{{$item}}"
                                            data-target="#modal-edit">
                                        <i class="fa fa-edit"></i>
                                    </button>
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
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->



    <!-- NEW MODAL-->
    <div class="modal fade" id="modal-create">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Create New Totals</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form-new" method="post" action="{{route('main-totals-store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Totals Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter totals name" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="description">Totals EForm</label>
                                    <select class="form-control" name="eform_id" >
                                        <option disabled value="" >Select eform</option>
                                        <option value="{{config('constants.eforms_id.main_dashboard')}}" >Main Dashboard</option>
                                        @foreach($eforms as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_code">Background HTML Color</label>
                                    <input list="list_status" type="text" class="form-control"  name="color"
                                           placeholder="Enter Html Color">
                                    <datalist id="list_status">
                                        <option>bg-gray</option>
                                        <option>bg-green</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_code">Icon Class</label>
                                    <input list="list_icon" type="text" class="form-control" name="icon"
                                           placeholder="Enter the font awesome icon name ">
                                    <datalist id="list_icon">
                                        <option>fas fa-file</option>
                                        <option>fas fa-users</option>
                                        <option>fas fa-users</option>
                                        <option>fas fa-users</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="eform_route_name">Route Name</label>
                                    <input  type="text" class="form-control" name="url"
                                           placeholder="Enter the route name ">
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



    <!-- VIEW MODAL-->
    <div class="modal fade" id="modal-view">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Totals Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                <tr>
                                    <th colspan="2"></th>
                                </tr>
                                </thead>
                                <tbody id="myTable1">

                                <b>Id:</b> <span id="span_id"></span><br>
                                <b>Name:</b> <span id="span_name"></span><br>
                                <b>Value:</b> <span id="span_value"></span><br>
                                <b>EForm:</b> <span id="span_eform"></span><br>
                                <b>Created By:</b> <span id="span_created_by"></span><br>
                                <b>Updated At:</b> <span id="span_created_at"></span><br>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.View modal -->



    <!-- EDIT MODAL-->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Update Totals</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main-totals-update')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="totals_name2">Name</label>
                                    <input type="text" class="form-control" id="totals_name2" name="name"
                                           placeholder="Enter totals name" required>
                                </div>
                                <input hidden type="text" class="form-control" id="totals_id2" name="totals_id"
                                       required>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_id">EForm</label>
                                    <select class="form-control" id="eform_id2" name="eform_id" >
                                        <option disabled value="" >Select eform</option>
                                        <option value="{{config('constants.eforms_id.main_dashboard')}}" >Main Dashboard</option>
                                        @foreach($eforms as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_code">Background HTML Color</label>
                                    <input list="list_status" type="text" class="form-control" id="html2" name="color"
                                           placeholder="Enter Html Color">
                                    <datalist id="list_status">
                                        <option>bg-gray</option>
                                        <option>bg-green</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_code">Icon Class</label>
                                    <input list="list_icon" type="text" class="form-control" id="icon2" name="icon"
                                           placeholder="Enter the font awesome icon name ">
                                    <datalist id="list_icon">
                                        <option>fas fa-file</option>
                                        <option>fas fa-users</option>
                                        <option>fas fa-users</option>
                                        <option>fas fa-users</option>
                                    </datalist>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="eform_route_name">Route Name</label>
                                    <input  type="text" class="form-control" id="url2" name="url"
                                            placeholder="Enter the route name ">
                                </div>
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


    @foreach($list as $item)
        <!-- DELETE MODAL-->
        <div class="modal fade" id="modal-delete{{$item->id}}">
            <div class="modal-dialog modal-sm">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Delete Totals</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('main-totals-destroy', ['id' => $item->id])}}">
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
        <!-- /.DELETE modal -->
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
            $('#totals_name2').val(recipient.name);
            $('#totals_id2').val(recipient.id);
            $('#value2').val(recipient.value);
            $('#eform_id2').val(recipient.eform_id);
            $('#icon2').val(recipient.icon);
            $('#html2').val(recipient.color);
            $('#url2').val(recipient.url);

        });

        $('#modal-view').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes

            var span_id = document.getElementById("span_id");
            span_id.textContent = recipient.id;
            var span_name = document.getElementById("span_name");
            span_name.textContent = recipient.name;
            var span_value = document.getElementById("span_value");
            span_value.textContent = recipient.value;
            var span_eform = document.getElementById("span_eform");
            span_eform.textContent = recipient.eform.name;
            var span_created_by = document.getElementById("span_created_by");
            span_created_by.textContent = recipient.user.name;
            var span_created_at = document.getElementById("span_created_at");
            span_created_at.textContent = recipient.created_at;

        });

    </script>
@endpush
