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
                    <h1 class="m-0 text-dark">Confidential Users</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Confidential Users</li>
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
                    New Confidential Users</button>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fas fa-minus"></i></button>
{{--                    <a class="btn btn-tool" href="{{route('main.confidential.users.sync')}}"--}}
{{--                       title="Sync Positions">--}}
{{--                        <i class="fas fa-sync"></i></a>--}}
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Man No</th>
                            <th>User Unit</th>
                            <th>Job Code</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            {{--                            <th>Type</th>--}}
                            {{--                            <th>Created At</th>--}}
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $key => $item)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>
                                    <a href="{{route('main.user.show',$item->id)}}" class=" " style="margin: 1px">
                                        {{$item->name ?? ""}}
                                    </a>
                                </td>
                                <td>{{$item->staff_no ?? "" }} </td>
                                <td class="text-orange">{{$item->user_unit->user_unit_code ?? "" }}</td>
                                <td class="text-orange" >{{$item->job_code ?? ""}} </td>
                                <td>{{$item->email ?? ""}} </td>
                                <td>{{$item->phone ?? ""}} </td>
                                <td> {{$item->con_st_code ?? " "}} </td>
                                {{--                                <td>{{$item->user_type->name ?? ""}} </td>--}}
                                {{--                                <td>{{$item->created_at ?? ""}}</td>--}}
                                <td>{{$item->created_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{route('main.user.show',$item->id)}}" class="btn btn-sm bg-gradient-gray float-left " style="margin: 1px">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if( Auth::user()->type_id == config('constants.user_types.developer'))
                                        <button class="btn btn-sm bg-gradient-gray float-left" style="margin: 1px"
                                                title="Delete"
                                                data-toggle="modal"
                                                data-target="#modal-delete{{$item->id}}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
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
                    <h4 class="modal-title text-center">Create New Confidential Users</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form-new" method="post" action="{{route('main.confidential.users.store')}}">
                        @csrf
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Full Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="name" required
                                       placeholder="Name" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName" class="col-sm-2 col-form-label">Man Number</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="staff_no" required
                                       placeholder="Staff Number" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" name="email" required
                                       placeholder="Email" value="">
                            </div>
                        </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="phone" required
                                   placeholder="Phone" value="0">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputName2" class="col-sm-2 col-form-label">Extension</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="extension" required
                                   placeholder="Extension" value="0">
                        </div>
                    </div>
                        <div class="form-group row">
                            <label for="inputExperience"
                                   class="col-sm-2 col-form-label">User Type</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="user_type_id" required>
                                        @if( Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                            @foreach($user_types as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label text-orange ">Position </label>
                            <div class="col-sm-10">
                                <select id="position" class="form-control " required name="position">
                                    <option
                                        value=""> Please Select Position </option>
                                    @foreach($positions as $item)
                                        <option
                                            value="{{$item->id}}">{{$item->name}}
                                          </option>
                                    @endforeach
                                </select>


                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label text-orange ">Grades </label>
                            <div class="col-sm-10">
                                <select id="grade" class="form-control " required name="grade">
                                    <option
                                        value=""> Please Select Grades </option>
                                    @foreach($grades as $item)
                                        <option
                                            value="{{$item->id}}">{{$item->name}}
                                            : {{$item->category->name ?? "" }}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label text-orange ">User
                                Unit</label>
                            <div class="col-sm-10">
                                <select id="user_unit_new" class="form-control " required name="user_unit_new">
                                    <option
                                        value=""> Please Select User Unit </option>
                                    @foreach($user_unit_new as $item)
                                        <option
                                            value="{{$item->id}}">{{$item->user_unit_description}}
                                            : {{$item->user_unit_code}}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputName2" class="col-sm-2 col-form-label text-orange ">Directorate</label>
                            <div class="col-sm-10">
                                <select id="directorate" class="form-control " required name="directorate">
                                    <option
                                        value=""> Please Select Directorate </option>
                                    @foreach($directorates as $item)
                                        <option
                                            value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>


                            </div>
                        </div>

                        <div> <span>Default password is zescoOne</span></div>

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
                    <h4 class="modal-title text-center">Confidential Users Details</h4>
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
                                <b>Code:</b> <span id="span_tax"></span><br>
                                <b>Created By:</b> <span id="span_created_by"></span><br>
                                <b>Created At:</b> <span id="span_created_at"></span><br>
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
                    <h4 class="modal-title text-center">Update Confidential Users</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main.confidential.users.update')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Confidential Users Name</label>
                                    <input type="text" class="form-control" id="name2" name="name"
                                           placeholder="Enter tax name" required>
                                    <input hidden type="text" class="form-control" id="tax_id2" name="tax_id"
                                           required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="tax">Confidential Users Code</label>
                                    <input type="text" class="form-control" id="tax2" name="tax"
                                           placeholder="Enter tax tax">
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


{{--    @foreach($list as $item)--}}
{{--        <!-- DELETE MODAL-->--}}
{{--        <div class="modal fade" id="modal-delete{{$item->id}}">--}}
{{--            <div class="modal-dialog modal-sm">--}}
{{--                <div class="modal-content bg-defualt">--}}
{{--                    <div class="modal-header">--}}
{{--                        <h4 class="modal-title text-center">Delete Confidential Users</h4>--}}
{{--                    </div>--}}
{{--                    <!-- form start -->--}}
{{--                    <form role="form" method="post"--}}
{{--                          action="{{route('main.confidential.users.destroy', ['id' => $item->id])}}">--}}
{{--                        @csrf--}}
{{--                        <div class="modal-body">--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-12">--}}
{{--                                    <p class="text-center">Are you sure you want to delete? </p>--}}
{{--                                    <p class="text-center">Note that you can not undo this action. </p>--}}
{{--                                </div>--}}

{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="modal-footer justify-content-between">--}}
{{--                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                            <button type="submit" class="btn btn-danger">Delete</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <!-- /.modal-content -->--}}
{{--            </div>--}}
{{--            <!-- /.modal-dialog -->--}}
{{--        </div>--}}
{{--        <!-- /.DELETE modal -->--}}
{{--    @endforeach--}}


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
            $('#name2').val(recipient.name);
            $('#tax_id2').val(recipient.id);
            $('#tax2').val(recipient.tax);
        });

        $('#modal-view').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes

            var span_id = document.getElementById("span_id");
            span_id.textContent = recipient.id;
            var span_name = document.getElementById("span_name");
            span_name.textContent = recipient.name;
            var span_tax = document.getElementById("span_tax");
            span_tax.textContent = recipient.tax;
            var span_created_by = document.getElementById("span_created_by");
            span_created_by.textContent = recipient.user.name;
            var span_created_at = document.getElementById("span_created_at");
            span_created_at.textContent = recipient.created_at;

        });

    </script>
@endpush
