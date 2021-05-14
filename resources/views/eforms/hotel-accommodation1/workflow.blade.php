@extends('layouts.eforms.hotel-accommodation.master')


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
                    <h1 class="m-0 text-dark">HOTEl Cash User-Units Workflow</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Petty Cash User-Units Workflow</li>
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
{{--                <button class="btn btn-sm bg-gradient-orange float-left" data-toggle="modal"--}}
{{--                        data-target="#modal-create">--}}
{{--                    New Departmental User Unit--}}
{{--                </button>--}}

{{--                <div class="card-tools">--}}
{{--                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"--}}
{{--                            title="Collapse">--}}
{{--                        <i class="fas fa-minus"></i></button>--}}
{{--                    <a class="btn btn-tool" href="{{route('main-workflow-sync')}}"--}}
{{--                       title="Sync Departmental User Units">--}}
{{--                        <i class="fas fa-sync"></i></a>--}}
{{--                </div>--}}
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>Name</th>
                            <th>Departmental User Unit</th>
                            <th>Business Unit Code</th>
                            <th>Cost Center Code</th>
                            <th>Superior Unit Code</th>

                            <th>HOD CODE</th>
                            <th>HOD UNIT</th>
                            <th>HR CODE</th>
                            <th>HR UNIT</th>
                            <th>CA CODE</th>
                            <th>CA UNIT</th>
                            <th>DIR CODE</th>
                            <th>DIR UNIT</th>



                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($list as $key => $item)
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$item->user_unit_description}} </td>
                                <td>{{$item->user_unit_code}} </td>
                                <td>{{$item->user_unit_bc_code}} </td>
                                <td>{{$item->user_unit_cc_code}} </td>
                                <td>{{$item->user_unit_superior}} </td>

                                <td>{{ $item->hod_unit }} : {{ $item->hod_code }} </td>
                                <td>{{ $item->hrm_unit }} : {{ $item->hrm_code }} </td>
                                <td>{{ $item->ca_unit }} : {{ $item->ca_code }} </td>
                                <td>{{ $item->dir_unit }} : {{ $item->dir_code }} </td>

                                <td>{{ $item->updated_at }} </td>
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


    <!-- EDIT MODAL-->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Update Hotel Accommodation User Unit Work Flow</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('hotel_accommodation.workflow.update')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Name</label>
                                    <input type="text" class="form-control" id="workflow_name2" name="name"
                                           readonly placeholder="Enter Departmental user unit name" required>
                                </div>
                                <input hidden type="text" class="form-control" id="workflow_id2" name="workflow_id"
                                       required>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Unit Code</label>
                                    <input type="text" class="form-control" id="workflow_code2" name="code"
                                           readonly placeholder="Enter Departmental user unit Code" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Business Unit Code</label>
                                    <input type="text" class="form-control" id="business_unit_code2" name="business_unit_code"
                                          readonly placeholder="Enter business unit code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Cost Center Unit</label>
                                    <input type="text" class="form-control" id="cost_center_code2" name="cost_center_code"
                                           readonly placeholder="Enter cost center unit">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Code Unit Superior</label>
                                    <input type="text" class="form-control" id="code_unit_superior2" name="code_unit_superior"
                                           readonly placeholder="Enter cost unit superior">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="hod_id">HOD NAME</label>
                                    <input list="users_list1" type="text" class="form-control" id="hod_id" name="hod_id"
                                          value=""  placeholder="Select Hod Person">
                                    <datalist id="users_list1">
                                        @foreach($users as $item)
                                        <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="hod_code">HOD Code</label>
                                    <input type="text" class="form-control" id="hod_code" name="hod_code"
                                            placeholder="Enter cost unit superior">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="hod_unit">HOD Unit</label>
                                    <input type="text" class="form-control" id="hod_unit" name="hod_unit"
                                            placeholder="Enter cost unit superior">
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group">
                                    <label for="hrm_id">HR NAME</label>
                                    <input list="users_list2" type="text" class="form-control" id="hrm_id" name="hrm_id"
                                           placeholder="Select HR Person">
                                    <datalist id="users_list2">
                                        @foreach($users as $item)
                                            <option value=" {{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="hrm_code">HR Code</label>
                                    <input type="text" class="form-control" id="hrm_code" name="hrm_code"
                                           placeholder="Enter HRM Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="hrm_unit">HR Unit</label>
                                    <input type="text" class="form-control" id="hrm_unit" name="hrm_unit"
                                           placeholder="Enter HRM Unit">
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ca_id">Chief Accountant NAME </label>
                                    <input list="users_list3" type="text" class="form-control" id="ca_id" name="ca_id"
                                           placeholder="Select Chief Accountant Person">
                                    <datalist id="users_list3">
                                        @foreach($users as $item)
                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="ca_code">CA Code</label>
                                    <input type="text" class="form-control" id="ca_code" name="ca_code"
                                           placeholder="Enter Chief Accountant Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="ca_unit">CA Unit</label>
                                    <input type="text" class="form-control" id="ca_unit" name="ca_unit"
                                           placeholder="Enter CA Unit">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="dir_id">Director Name</label>
                                    <input list="users_list4" type="text" class="form-control" id="dir_id" name="dir_id"
                                           placeholder="Select Director Name">
                                    <datalist id="users_list4">
                                        @foreach($users as $item)
                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="expenditure_code">Director Code</label>
                                    <input type="text" class="form-control" id="dir_code" name="dir_code"
                                           placeholder="Enter Director Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="expenditure_unit">Director Unit</label>
                                    <input type="text" class="form-control" id="dir_unit" name="dir_unit"
                                           placeholder="Enter Director Unit">
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
        $('#workflow_name2').val(recipient.user_unit_description);
        $('#workflow_id2').val(recipient.id);
        $('#workflow_code2').val(recipient.user_unit_code);
        $('#business_unit_code2').val(recipient.user_unit_bc_code);
        $('#cost_center_code2').val(recipient.user_unit_cc_code);
        $('#code_unit_superior2').val(recipient.user_unit_superior);
        $('#hod_code').val(recipient.hod_code);
        $('#hod_unit').val(recipient.hod_unit);
        $('#hrm_code').val(recipient.hrm_code);
        $('#hrm_unit').val(recipient.hrm_unit);
        $('#ca_code').val(recipient.ca_code);
        $('#ca_unit').val(recipient.ca_unit);
        $('#dir_code').val(recipient.dir_code);
        $('#dir_unit').val(recipient.dir_unit);

    });


    $('#modal-view').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data('sent_data'); // Extract info from data-* attributes

        var span_id = document.getElementById("span_id");
        span_id.textContent = recipient.id;
        var span_name = document.getElementById("span_name");
        span_name.textContent = recipient.user_unit_description;
        var span_code = document.getElementById("span_code");
        span_code.textContent = recipient.user_unit_code;
        var span_business_unit = document.getElementById("span_business_unit");
        span_business_unit.textContent = recipient.user_unit_bc_code;
        var span_cost_center = document.getElementById("span_cost_center");
        span_cost_center.textContent = recipient.user_unit_cc_code;
        var span_superior_code = document.getElementById("span_superior_code");
        span_superior_code.textContent = recipient.user_unit_superior;
        var span_created_at = document.getElementById("span_created_at");
        span_created_at.textContent = recipient.created_at;
        // var span_period = document.getElementById("span_period");
        // span_period.textContent = recipient.created_at;

        var hod_code = document.getElementById("hod_code");
        hod_code.textContent = recipient.hod_code;
        var hod_unit = document.getElementById("hod_unit");
        hod_unit.textContent = recipient.hod_unit;

        var hrm_code = document.getElementById("hrm_code");
        hrm_code.textContent = recipient.hrm_code;
        var hrm_unit = document.getElementById("hrm_unit");
        hrm_unit.textContent = recipient.hrm_unit;

        var ca_code = document.getElementById("ca_code");
        ca_code.textContent = recipient.ca_code;
        var ca_unit = document.getElementById("ca_unit");
        ca_unit.textContent = recipient.ca_unit;

        var dir_code = document.getElementById("dir_code");
        dir_code.textContent = recipient.dir_code;
        var dir_unit = document.getElementById("dir_unit");
        dir_unit.textContent = recipient.dir_unit;

         });

</script>


<script>
    $(document).ready(function () {
        //hod
        $("#hod_id").change(function () {
            var selected_value = ''; // Selected value
            // Get selected value
            selected_value += $(this).val();
            //find the user from the selected array
            var users = {!! json_encode($users->toArray()) !!};
            unit = "";
            code = "";
            $.each(users, function (index, value) {
                if (value.id == selected_value) {
                    unit += value.user_unit_code ;
                    code += value.job_code ;
                }
            });
            $("#hod_code").val(code);
            $("#hod_unit").val(unit);
        });
        //hrm
        $("#hrm_id").change(function () {
            var selected_value = ''; // Selected value
            // Get selected value
            selected_value += $(this).val();
            //find the user from the selected array
            var users = {!! json_encode($users->toArray()) !!};
            unit = "";
            code = "";
            $.each(users, function (index, value) {
                if (value.id == selected_value) {
                    unit += value.user_unit_code ;
                    code += value.job_code ;
                }
            });
            $("#hrm_code").val(code);
            $("#hrm_unit").val(unit);
        });
        //chief accountant
        $("#ca_id").change(function () {
            var selected_value = ''; // Selected value
            // Get selected value
            selected_value += $(this).val();
            //find the user from the selected array
            var users = {!! json_encode($users->toArray()) !!};
            unit = "";
            code = "";
            $.each(users, function (index, value) {
                if (value.id == selected_value) {
                    unit += value.user_unit_code ;
                    code += value.job_code ;
                }
            });
            $("#ca_code").val(code);
            $("#ca_unit").val(unit);
        });
        //expenditure
        $("#expenditure_id").change(function () {
            var selected_text = ''; // Selected text
            var selected_value = ''; // Selected value
            var selected_index = ''; // Selected index
            // Get selected value
            selected_value += $(this).val();
            //find the user from the selected array
            var users = {!! json_encode($users->toArray()) !!};
            unit = "";
            code = "";
            $.each(users, function (index, value) {
                if (value.id == selected_value) {
                    unit += value.user_unit_code ;
                    code += value.job_code ;
                }
            });
            $("#expenditure_code").val(code);
            $("#expenditure_unit").val(unit);
        });
        //security
        $("#security_id").change(function () {
            var selected_text = ''; // Selected text
            var selected_value = ''; // Selected value
            var selected_index = ''; // Selected index
            // Get selected value
            selected_value += $(this).val();
            //find the user from the selected array
            var users = {!! json_encode($users->toArray()) !!};
            unit = "";
            code = "";
            $.each(users, function (index, value) {
                if (value.id == selected_value) {
                    unit += value.user_unit_code ;
                    code += value.job_code ;
                }
            });
            $("#security_code").val(code);
            $("#security_unit").val(unit);
        });


    });
</script>

@endpush
