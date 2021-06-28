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
                    <h1 class="m-0 text-dark">Profile Permissions</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Profile Permissions</li>
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
                    New Profile Permission
                </button>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fas fa-minus"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Profile</th>
                            <th>Profile Next</th>
                            <th>E-form</th>
                            <th>Created At</th>
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $key => $item)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$item->profiles->code  ?? "" }}:{{$item->profiles->name  ?? "" }} </td>
                                <td>{{$item->profiles_next->code ?? ""}}:{{$item->profiles_next->name ?? ""}} </td>
                                <td>{{$item->eform->name}} </td>
                                <td>{{$item->created_at}}</td>
                                <td>{{$item->created_at->diffForHumans()}}</td>
                                <td>
                                    <button class="btn btn-sm bg-gradient-gray float-left " style="margin: 1px"
                                            title="Edit"
                                            data-toggle="modal"
                                            data-sent_data="{{$item}}"
                                            data-target="#modal-view">
                                        <i class="fa fa-eye"></i>
                                    </button>
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
                    <h4 class="modal-title text-center">Create New System Profile Permission</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form-new" method="post" action="{{route('main.profile.permission.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Select E-Form</label>
                                    <select class="form-control select2" name="eform_id" required
                                            style="width: 100%;">
                                        <option disabled value="" selected>Select E-Form</option>
                                        @foreach($eforms as $eform)
                                            <option
                                                value="{{$eform->id}}"> {{$eform->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Select User Profile</label>
                                    <select class="form-control select2" name="profile" required
                                            style="width: 100%;">
                                        <option disabled value="" selected>Assign Profile</option>
                                        @foreach($profiles as $profile)
                                            <option
                                                    value="{{$profile->code}}">  {{$profile->code}}: {{$profile->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Select Next User Profile</label>
                                    <select class="form-control select2" name="profile_next" required
                                            style="width: 100%;">
                                        <option disabled value="" selected>Assign Next Profile</option>
                                        @foreach($profiles as $profile)
                                            <option
                                                    value="{{$profile->code}}">  {{$profile->code}}: {{$profile->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
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
                    <h4 class="modal-title text-center">Profile Details</h4>
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
                                <b>E-Form:</b> <span id="span_eform"></span><br>
                                <b>Profile:</b> <span id="span_profile"></span><br>
                                <b>Next Profile:</b> <span id="span_profile_next"></span><br>
{{--                                <b>Created By:</b> <span id="span_created_by"></span><br>--}}
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
                    <h4 class="modal-title text-center">Update System Profile</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main.profile.permission.update')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Select E-Form</label>
                                    <select class="form-control select2" id="eform_id2" name="eform_id" required
                                            style="width: 100%;">
                                        <option disabled value="" selected>Select E-Form</option>
                                        @foreach($eforms as $eform)
                                            <option
                                                value="{{$eform->id}}"> {{$eform->name}} </option>
                                        @endforeach
                                    </select>
                                    <input hidden id="id2" name="id" value="">
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Select User Profile</label>
                                    <select class="form-control select2" id="profile2" name="profile" required
                                            style="width: 100%;">
                                        <option disabled value="" selected>Assign Profile</option>
                                        @foreach($profiles as $profile)
                                            <option
                                                    value="{{$profile->code}}">  {{$profile->code}}: {{$profile->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Select User Profile</label>
                                    <select class="form-control select2" id="profile_next2" name="profile_next" required
                                            style="width: 100%;">
                                        <option disabled value="" selected>Assign Next Profile</option>
                                        @foreach($profiles as $profile)
                                            <option
                                                    value="{{$profile->code}}">  {{$profile->code}}: {{$profile->name}} </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- /.form-group -->
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
                        <h4 class="modal-title text-center">Delete System Profile Permission</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('main.profile.permission.destroy', ['id' => $item->id])}}">
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
        $('#eform_id2').val(recipient.eform_id);
        $('#profile2').val(recipient.profile);
        $('#profile_next2').val(recipient.profile_next);
        $('#id2').val(recipient.id);

    });

    $('#modal-view').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('sent_data'); // Extract info from data-* attributes

        var span_id = document.getElementById("span_id");
        span_id.textContent = recipient.id;
        var span_eform = document.getElementById("span_eform");
        span_eform.textContent = recipient.eform.name;
        var span_profile_next = document.getElementById("span_profile_next");
        span_profile_next.textContent = recipient.profile_next;
        var span_profile = document.getElementById("span_profile");
        span_profile.textContent = recipient.profile;
        // var span_created_by = document.getElementById("span_created_by");
        // span_created_by.textContent = recipient.created_by;
        var span_created_at = document.getElementById("span_created_at");
        span_created_at.textContent = recipient.created_at;

    });

</script>
@endpush
