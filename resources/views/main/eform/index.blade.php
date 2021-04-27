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
                    <h1 class="m-0 text-dark">eForm</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">eForm</li>
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
                    New eForm
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
                            <th>Name</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->name}} </td>
                                <td>{{$item->code}} </td>
                                <td>{{$item->category->name}} </td>
                                <td>{{$item->status->name}} </td>
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
                    <h4 class="modal-title text-center">Create eform Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form1" method="post" action="{{route('main-eforms-store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">eForm Name</label>
                                    <input type="text" class="form-control" id="form_name" name="name"
                                           placeholder="Enter Form name" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Test URL</label>
                                    <input type="text" class="form-control" id="eform_test_url" name="test_url"
                                           placeholder="Enter Form Test URL">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Production URL</label>
                                    <input type="text" class="form-control" id="eform_production_url"
                                           name="production_url"
                                           placeholder="Enter Form Code Production URL">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Description</label>
                                    <textarea rows="2" type="text" class="form-control" id="eform_description"
                                              name="description"
                                              placeholder="Enter Form Code Production URL"> </textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_category_id">Category</label>
                                    <select class="custom-select" id="eform_category" name="category_id" required>
                                        <option value="">Select Form Category</option>
                                        @foreach($categories as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_status_id">Status</label>
                                    <select class="custom-select" id="eform_status" name="status_id">
                                        <option disabled  value="">Select Status</option>
                                        @foreach($status as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="icon">Icon</label>
                                    <input type="text" class="form-control" id="icon"
                                           name="icon"
                                           placeholder="Enter Form Icon e.g fas fa-file">
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
                    <h4 class="modal-title text-center">eform Details</h4>
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
                                <b>Code:</b> <span id="span_code"></span><br>
                                <b>Description:</b> <span id="span_description"></span><br>
                                <b>Test Url:</b> <span id="span_test_url"></span><br>
                                <b>Production Url:</b> <span id="span_production_url"></span><br>
                                <b>Category:</b> <span id="span_category"></span><br>
                                <b>Icon:</b> <span id="span_icon"></span><br>
                                <b>Status:</b> <span id="span_status"></span><br>
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
                    <h4 class="modal-title text-center">Update eform Category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main-eforms-update')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">eForm Name</label>
                                    <input type="text" class="form-control" id="form_name_id2" name="name"
                                           placeholder="Enter Form name" required>
                                </div>
                                <input hidden type="text" class="form-control" id="form_id2" name="form_id"
                                       required>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">eForm Code</label>
                                    <input type="text" class="form-control" id="eform_code_id2" name="code"
                                           placeholder="Enter Form Code" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Test URL</label>
                                    <input type="text" class="form-control" id="eform_test_url_id2" name="test_url"
                                           placeholder="Enter Form Test URL">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Production URL</label>
                                    <input type="text" class="form-control" id="eform_production_url_id2"
                                           name="production_url"
                                           placeholder="Enter Form Code Production URL">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Description</label>
                                    <textarea rows="4" type="text" class="form-control" id="eform_description_id2"
                                              name="description"
                                              placeholder="Enter Form Code Production URL"> </textarea>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_category_id">Category</label>
                                    <select class="custom-select" id="eform_category_id2" name="category_id" required>
                                        <option value="">Select Form Category</option>
                                        @foreach($categories as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="eform_status_id">Status</label>
                                    <select class="custom-select" id="eform_status_id2" name="status_id">
                                        <option disabled  value="">Select Status</option>
                                        @foreach($status as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="icon">Icon</label>
                                    <input type="text" class="form-control" id="eform_icon2"
                                           name="icon"
                                           placeholder="Enter Form Icon e.g fas fa-file">
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
                        <h4 class="modal-title text-center">Delete eform</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('main-eforms-destroy', ['id' => $item->id])}}">
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
        $('#form_name_id2').val(recipient.name);
        $('#form_id2').val(recipient.id);
        $('#eform_code_id2').val(recipient.code);
        $('#eform_test_url_id2').val(recipient.test_url);
        $('#eform_production_url_id2').val(recipient.production_url);
        $('#eform_description_id2').val(recipient.description);
        $('#eform_icon2').val(recipient.icon);
        $('#eform_category_id2').val(recipient.category.id);
        $('#eform_status_id2').val(recipient.status.id);
    });
    $('#modal-view').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('sent_data'); // Extract info from data-* attributes

        var span_id = document.getElementById("span_id");
        span_id.textContent = recipient.id;
        var span_name = document.getElementById("span_name");
        span_name.textContent = recipient.name;
        var span_code = document.getElementById("span_code");
        span_code.textContent = recipient.code;
        var span_test_url = document.getElementById("span_test_url");
        span_test_url.textContent = recipient.test_url;
        var span_production_url = document.getElementById("span_production_url");
        span_production_url.textContent = recipient.production_url;
        var span_description = document.getElementById("span_description");
        span_description.textContent = recipient.description;
        var span_category = document.getElementById("span_category");
        span_category.textContent = recipient.category.name;
        var span_status = document.getElementById("span_status");
        span_status.textContent = recipient.status.name;

        var span_icon = document.getElementById("span_icon");
        span_icon.textContent = recipient.icon;

        var span_created_by = document.getElementById("span_created_by");
        span_created_by.textContent = recipient.created_by;

        var span_created_at = document.getElementById("span_created_at");
        span_created_at.textContent = recipient.created_at;
    });

</script>

@endpush
