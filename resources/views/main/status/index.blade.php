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
                    <h1 class="m-0 text-dark">System Statuses</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">System Statuses</li>
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
                    New Status
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
                            <th>Status</th>
                            <th>Next</th>
                            <th>Fail</th>
                            <th>html</th>
                            <th>eForm</th>
                            <th>Created At</th>
                            <th>Period</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($list as $key => $item)
                            <tr>
                                <td>{{$item->id}} </td>
                                <td>{{$item->name}} </td>
                                <td>{{$item->status}} </td>
                                <td>{{$item->status_next}} </td>
                                <td>{{$item->status_failed}} </td>
                                <td>{{$item->html}} </td>
                                <td>{{$item->eform->name ?? ""}} </td>
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
                    <h4 class="modal-title text-center">Create New System Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form-new" method="post" action="{{route('main-status-store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           placeholder="Enter status name" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status Other Name</label>
                                    <input type="text" class="form-control" id="other_name" name="other_name"
                                           placeholder="Enter status other name">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status Description</label>
                                    <input type="text" class="form-control" id="description" name="description"
                                           placeholder="Enter description">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status For</label>
                                    <select class="form-control" name="eform_id" >
                                        <option value="1"> Dashboard Eforms</option>
                                        @foreach($forms as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status Html</label>
                                    <input list="list_status" type="text" class="form-control" name="html"
                                           placeholder="Enter Status Html">
                                    <datalist id="list_status">
                                        <option>danger</option>
                                        <option>warning</option>
                                        <option>success</option>
                                        <option>info</option>
                                        <option>default</option>
                                        <option>secondary</option>
                                        <option>primary</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status Percentage</label>
                                    <input list="list_percent" type="number" class="form-control" name="percentage"
                                           placeholder="Enter Completion Percentage">
                                    <datalist id="list_percent">
                                        <option>0</option>
                                        <option>10</option>
                                        <option>20</option>
                                        <option>30</option>
                                        <option>40</option>
                                        <option>50</option>
                                        <option>60</option>
                                        <option>70</option>
                                        <option>80</option>
                                        <option>90</option>
                                        <option>100</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status</label>
                                    <input type="number" class="form-control is-valid" id="status" name="status"
                                           placeholder="Enter Status">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status Next</label>
                                    <input type="number" class="form-control is-warning" id="next" name="next"
                                           placeholder="Enter Next Status">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Status Fail</label>
                                    <input type="number" class="form-control is-invalid" id="fail" name="fail"
                                           placeholder="Enter Failed Status">
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
                    <h4 class="modal-title text-center">Status Details</h4>
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
                                <b>Other:</b> <span id="span_other"></span><br>
                                <b>Description:</b> <span id="span_description"></span><br>
                                <b>Status:</b> <span id="span_status"></span><br>
                                <b>Next Status:</b> <span id="span_next"></span><br>
                                <b>Failed Status:</b> <span id="span_failed"></span><br>
                                <b>Html:</b> <span id="span_html"></span><br>
                                <b>Percentage:</b> <span id="span_percentage"></span><br>
                                <b>eForm Code:</b> <span id="span_eform_code"></span><br>
                                <b>eForm Name:</b> <span id="span_eform_id"></span><br>
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
                    <h4 class="modal-title text-center">Update System Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main-status-update')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" class="form-control" id="status_name2" name="name"
                                           placeholder="Enter Form name" required>
                                </div>
                                <input hidden type="text" class="form-control" id="status_id2" name="form_id"
                                       required>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Other</label>
                                    <input type="text" class="form-control" id="other2" name="other_name"
                                           placeholder="Enter Form Code" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Description</label>
                                    <input type="text" class="form-control" id="description2" name="description"
                                           placeholder="Enter description">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input type="text" class="form-control" id="status2"
                                           name="status"
                                           placeholder="Enter status">
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="next">Next Status</label>
                                    <textarea rows="1" type="text" class="form-control" id="next2"
                                              name="next"
                                              placeholder="Enter Next status"> </textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="fail">Failed Status</label>
                                    <textarea rows="1" type="text" class="form-control" id="failed2"
                                              name="fail"
                                               placeholder="Enter Failed Status"> </textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="eform_code">HTML</label>
                                    <input list="list_status" type="text" class="form-control" id="html" name="html"
                                           placeholder="Enter Status Html">
                                    <datalist id="list_status">
                                        <option>danger</option>
                                        <option>warning</option>
                                        <option>success</option>
                                        <option>info</option>
                                        <option>default</option>
                                        <option>secondary</option>
                                        <option>primary</option>
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="eform_id">Percentage</label>
                                    <textarea rows="1" type="text" class="form-control" id="percentage"
                                              name="percentage"
                                               placeholder="Form percentage"> </textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="eform_code">eForm </label>
                                    <select class="form-control" id="eform_code" name="eform_code"  >
                                        <option disabled value="">Select Form</option>
                                        @foreach($forms as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                                    <textarea hidden rows="1" type="text" class="form-control" id="eform_id"
                                              name="eform_id"
                                              readonly placeholder="Form ID"> </textarea>


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
                        <h4 class="modal-title text-center">Delete System Status</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('main-status-destroy', ['id' => $item->id])}}">
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
        $('#status_name2').val(recipient.name);
        $('#status_id2').val(recipient.id);
        $('#other2').val(recipient.other);
        $('#description2').val(recipient.description);
        $('#status2').val(recipient.status);
        $('#next2').val(recipient.status_next);
        $('#failed2').val(recipient.status_failed);
        $('#eform_code').val(recipient.eform_id);
        $('#eform_id').val(recipient.eform_id);
        $('#html').val(recipient.html);
        $('#percentage').val(recipient.percentage);
    });


    $('#modal-view').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('sent_data'); // Extract info from data-* attributes

        var span_id = document.getElementById("span_id");
        span_id.textContent = recipient.id;
        var span_name = document.getElementById("span_name");
        span_name.textContent = recipient.name;
        var span_other = document.getElementById("span_other");
        span_other.textContent = recipient.other;
        var span_status = document.getElementById("span_status");
        span_status.textContent = recipient.status;
        var span_next = document.getElementById("span_next");
        span_next.textContent = recipient.status_next;
        var span_description = document.getElementById("span_description");
        span_description.textContent = recipient.description;
        var span_failed = document.getElementById("span_failed");
        span_failed.textContent = recipient.status_failed;
        var span_created_by = document.getElementById("span_created_by");
        span_created_by.textContent = recipient.user.name;
        var span_created_at = document.getElementById("span_created_at");
        span_created_at.textContent = recipient.created_at;
        var span_html = document.getElementById("span_html");
        span_html.textContent = recipient.html;
        var span_percentage = document.getElementById("span_percentage");
        span_percentage.textContent = recipient.percentage;
        var span_eform_id = document.getElementById("span_eform_id");
        span_eform_id.textContent = recipient.eform_id;
        var span_eform_code = document.getElementById("span_eform_code");
        span_eform_code.textContent = recipient.eform_code;

    });

</script>
@endpush
