@extends('layouts.eforms.petty-cash.master')


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
                    <h1 class="m-0 text-dark">Petty Cash User-Units Workflow</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('petty.cash.home')}}">Home</a></li>
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
                @if($form_id != 0 )
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                       document.getElementById('show-form'+{{$form_id}}).submit();"> View the Form</a>
                <form id="show-form{{$form_id}}"
                      action="{{ route('petty.cash.show', $form_id) }}"
                      method="POST" class="d-none">
                    @csrf
                </form>
                @endif
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

                            <th>HOD</th>
                            <th>HR</th>
                            <th>CA</th>
                            <th>EX</th>
                            <th>SEC</th>
                            <th>AUDIT</th>

                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$workflow->id }}</td>
                            <td>{{$workflow->user_unit_description}} </td>
                            <td>{{$workflow->user_unit_code}} </td>
                            <td>{{$workflow->user_unit_bc_code}} </td>
                            <td>{{$workflow->user_unit_cc_code}} </td>
                            <td>{{$workflow->user_unit_superior}} </td>

                            <td>{{ $workflow->hod_unit }} : {{ $workflow->hod_code }} </td>
                            <td>{{ $workflow->hrm_unit }} : {{ $workflow->hrm_code }} </td>
                            <td>{{ $workflow->ca_unit }} : {{ $workflow->ca_code }} </td>
                            <td>{{ $workflow->expenditure_unit }} : {{ $workflow->expenditure_code }} </td>
                            <td>{{ $workflow->security_unit }} : {{ $workflow->security_code }} </td>
                            <td>{{ $workflow->audit_unit }} : {{ $workflow->audit_code }} </td>

                            <td>{{ $workflow->updated_at }} </td>
                            <td>
                                <button class="btn btn-sm bg-gradient-gray float-left " style="margin: 1px"
                                        title="Edit"
                                        data-toggle="modal"
                                        data-sent_data="{{$workflow}}"
                                        data-target="#modal-view">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn btn-sm bg-gradient-gray float-left " style="margin: 1px"
                                        title="Edit"
                                        data-toggle="modal"
                                        data-sent_data="{{$workflow}}"
                                        data-target="#modal-edit">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                        </tr>
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
                    <h4 class="modal-title text-center">Update Petty Cash User Unit Work Flow</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('petty.cash.workflow.update', $form_id )}}">
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
                        </div>
                        <div class="row">
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
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="hod_id">HOD</label>
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
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="hrm_id">HR</label>
                                    <input list="users_list1" type="text" class="form-control" id="hrm_id" name="hrm_id"
                                           placeholder="Select HR Person">
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
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ca_id">Chief Accountant</label>
                                    <input list="users_list1" type="text" class="form-control" id="ca_id" name="ca_id"
                                           placeholder="Select Chief Accountant Person">
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
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="expenditure_id">Expenditure Accountant</label>
                                    <input list="users_list1" type="text" class="form-control" id="expenditure_id" name="expenditure_id"
                                           placeholder="Select Expenditure Accountant Person">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="expenditure_code">Expenditure Code</label>
                                    <input type="text" class="form-control" id="expenditure_code" name="expenditure_code"
                                           placeholder="Enter Expenditure Accountant Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="expenditure_unit">Expenditure Unit</label>
                                    <input type="text" class="form-control" id="expenditure_unit" name="expenditure_unit"
                                           placeholder="Enter Expenditure Accountant Unit">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="security_id">Security Personal</label>
                                    <input list="users_list1" type="text" class="form-control" id="security_id" name="security_id"
                                           placeholder="Select Security Personal Person">
                                    </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="security_code">Security Personal Code</label>
                                    <input type="text" class="form-control accent-green" id="security_code" name="security_code"
                                           placeholder="Enter Security Personal Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="security_unit">Security Personal Unit</label>
                                    <input type="text" class="form-control accent-green" id="security_unit" name="security_unit"
                                           placeholder="Enter Security Personal Unit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="audit_id">Audit Personal</label>
                                    <input list="users_list1" type="text" class="form-control" id="audit_id" name="audit_id"
                                           placeholder="Select Audit Personal Person">
                                    </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="audit_code">Security Audit Code</label>
                                    <input type="text" class="form-control accent-green" id="audit_code" name="audit_code"
                                           placeholder="Enter Audit Personal Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="audit_unit">Audit Personal Unit</label>
                                    <input type="text" class="form-control accent-green" id="audit_unit" name="audit_unit"
                                           placeholder="Enter Audit Personal Unit">
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
            $('#expenditure_code').val(recipient.expenditure_code);
            $('#expenditure_unit').val(recipient.expenditure_unit);
            $('#security_code').val(recipient.security_code);
            $('#security_unit').val(recipient.security_unit);
            $('#audit_code').val(recipient.audit_code);
            $('#audit_unit').val(recipient.audit_unit);

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

            var expenditure_code = document.getElementById("expenditure_code");
            expenditure_code.textContent = recipient.expenditure_code;
            var expenditure_unit = document.getElementById("expenditure_unit");
            expenditure_unit.textContent = recipient.expenditure_unit;

            var security_code = document.getElementById("security_code");
            security_code.textContent = recipient.security_code;
            var security_unit = document.getElementById("security_unit");
            security_unit.textContent = recipient.security_unit;

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
            //audit
            $("#audit_id").change(function () {
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
                $("#audit_code").val(code);
                $("#audit_unit").val(unit);
            });


        });
    </script>

@endpush
