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
                    <h1 class="m-0 text-dark">User-Units Workflow </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">User-Units Workflow</li>
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
{{--                                <button class="btn btn-sm bg-gradient-orange float-left" data-toggle="modal"--}}
{{--                                        data-target="#modal-create">--}}
{{--                                    New Departmental User Unit--}}
{{--                                </button>--}}

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fas fa-minus"></i></button>

                    <div id="loader_c_2" style="display: none;">
                        <img src=" {{ asset('dashboard/dist/gif/Eclipse_loading.gif')}} "
                             width="100px"
                             height="100px">
                    </div>

                    <a  class="btn btn-tool" href="{{route('main.user.unit.sync')}}"
                        title="Sync User Units">
                        <i class="fas fa-sync"></i></a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            {{--                            <th>id</th>--}}
                            <th>Division/Directorate</th>
                            <th>Name</th>
                            <th>Departmental User Unit</th>
                            <th>Business Unit Code</th>
                            <th>Cost Center Code</th>
                            <th>ORG ID</th>

                            <th>DR</th>
                            <th>DM</th>
                            <th>HOD</th>
                            <th>ARM</th>
                            <th>BM</th>
                            <th>CA</th>
                            <th>MA</th>
                            <th>PSA</th>
                            <th>HRM</th>
                            <th>PHRO</th>
                            <th>SHRO</th>
                            <th>AUD</th>
                            <th>EXP</th>
                            <th>PAY</th>
                            <th>SEC</th>
                            <th>TRA</th>
                            <th>SHEQ</th>

                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>


                        @foreach($list as $key => $item)
                            <tr>
                                <td>{{$item->division->name ?? "" }} </td>
                                <td>{{$item->user_unit_description}} </td>
                                <td>{{$item->user_unit_code}} </td>
                                <td>{{$item->user_unit_bc_code}} </td>
                                <td>{{$item->user_unit_cc_code}} </td>
                                <td>{{$item->org_id ?? 0}} </td>

                                <td>{{ $item->dr_unit }} : {{ $item->dr_code }} </td>
                                <td>{{ $item->dm_unit }} : {{ $item->dm_code }} </td>
                                <td>{{ $item->hod_unit }} : {{ $item->hod_code }} </td>
                                <td>{{ $item->arm_unit }} : {{ $item->arm_code }} </td>
                                <td>{{ $item->bm_unit }} : {{ $item->bm_code }} </td>
                                <td>{{ $item->ca_unit }} : {{ $item->ca_code }} </td>
                                <td>{{ $item->ma_unit }} : {{ $item->ma_code }} </td>
                                <td>{{ $item->psa_unit }} : {{ $item->psa_code }} </td>
                                <td>{{ $item->hrm_unit }} : {{ $item->hrm_code }} </td>
                                <td>{{ $item->phro_unit }} : {{ $item->phro_code }} </td>
                                <td>{{ $item->shro_unit }} : {{ $item->shro_code }} </td>
                                <td>{{ $item->audit_unit }} : {{ $item->audit_code }} </td>
                                <td>{{ $item->expenditure_unit }} : {{ $item->expenditure_code }} </td>
                                <td>{{ $item->payroll_unit }} : {{ $item->payroll_code }} </td>
                                <td>{{ $item->security_unit }} : {{ $item->security_code }} </td>
                                <td>{{ $item->transport_unit }} : {{ $item->transport_code }} </td>
                                <td>{{ $item->sheq_unit }} : {{ $item->sheq_code }} </td>

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
                                    <button class="btn btn-sm bg-gradient-gray float-left " style="margin: 1px"
                                            title="Edit"
                                            data-toggle="modal"
                                            data-sent_data="{{$item}}"
                                            data-target="#modal-users">
                                        <i class="fa fa-users"></i>
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
                    <h4 class="modal-title text-center">Update User Unit Work Flow</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main.user.unit.update')}}">
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
                                    <label for="dr_id">Director</label>
                                    <input list="users_list" type="text" class="form-control" id="dr_id" name="dr_id"
                                           value=""  placeholder="Select Director Person">
                                    <datalist id="users_list">
                                        @foreach($users as $item)
                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="dr_code">Director Code</label>
                                    <input type="text" class="form-control" id="dr_code" name="dr_code"
                                           placeholder="Enter Director Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="dr_unit">Director Unit</label>
                                    <input type="text" class="form-control" id="dr_unit" name="dr_unit"
                                           placeholder="Enter Director Unit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="dm_id">Divisional Manager</label>
                                    <input list="users_list" type="text" class="form-control" id="dm_id" name="dm_id"
                                           value=""  placeholder="Select Divisional Manager">
                                    {{--                                    <datalist id="users_list">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="dm_code">DM Code</label>
                                    <input type="text" class="form-control" id="dm_code" name="dm_code"
                                           placeholder="Enter Divisional Manager">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="dm_unit">DM Unit</label>
                                    <input type="text" class="form-control" id="dm_unit" name="dm_unit"
                                           placeholder="Enter Divisional Manager">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="hod_id">HOD</label>
                                    <input list="users_list" type="text" class="form-control" id="hod_id" name="hod_id"
                                           value=""  placeholder="Select Hod Person">
                                    {{--                                    <datalist id="users_list">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
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
                                    <label for="arm_id">AREA MANAGER</label>
                                    <input list="users_list" type="text" class="form-control" id="arm_id" name="arm_id"
                                           placeholder="Select Area Manager">
                                    {{--                                    <datalist id="users_list2">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value=" {{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="arm_code">ARM Code</label>
                                    <input type="text" class="form-control" id="arm_code" name="arm_code"
                                           placeholder="Enter Area Manager">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="arm_unit">ARM Unit</label>
                                    <input type="text" class="form-control" id="arm_unit" name="arm_unit"
                                           placeholder="Enter Area Manager">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="bm_id">BRANCH MANAGER</label>
                                    <input list="users_list" type="text" class="form-control" id="bm_id" name="bm_id"
                                           placeholder="Select BRANCH MANAGER ">
                                    {{--                                    <datalist id="users_list2">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value=" {{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="bm_code">BM Code</label>
                                    <input type="text" class="form-control" id="bm_code" name="bm_code"
                                           placeholder="Enter BRANCH MANAGER Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="bm_unit">BM Unit</label>
                                    <input type="text" class="form-control" id="bm_unit" name="bm_unit"
                                           placeholder="Enter BRANCH MANAGER Unit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="ca_id">Chief Accountant</label>
                                    <input list="users_list" type="text" class="form-control" id="ca_id" name="ca_id"
                                           placeholder="Select Chief Accountant Person">
                                    {{--                                    <datalist id="users_list3">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
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
                                    <label for="ma_id">Management Accountant</label>
                                    <input list="users_list" type="text" class="form-control" id="ma_id" name="ma_id"
                                           placeholder="Select MA Person">
                                    {{--                                    <datalist id="users_list2">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value=" {{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="ma_code">HR Code</label>
                                    <input type="text" class="form-control" id="ma_code" name="ma_code"
                                           placeholder="Enter MA Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="ma_unit">HR Unit</label>
                                    <input type="text" class="form-control" id="ma_unit" name="ma_unit"
                                           placeholder="Enter MA Unit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="psa_id">PSA</label>
                                    <input list="users_list" type="text" class="form-control" id="psa_id" name="psa_id"
                                           value=""  placeholder="Select PSA Person">
                                    {{--                                    <datalist id="users_list">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="psa_code">PSA Code</label>
                                    <input type="text" class="form-control" id="psa_code" name="psa_code"
                                           placeholder="Enter cost unit superior">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="psa_unit">PSA Unit</label>
                                    <input type="text" class="form-control" id="psa_unit" name="psa_unit"
                                           placeholder="Enter cost unit superior">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="hrm_id">HRM</label>
                                    <input list="users_list" type="text" class="form-control" id="hrm_id" name="hrm_id"
                                           placeholder="Select HR Person">
                                    {{--                                    <datalist id="users_list2">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value=" {{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
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
                                    <label for="phro_id">PHRO</label>
                                    <input list="users_list" type="text" class="form-control" id="phro_id" name="phro_id"
                                           value=""  placeholder="Select PHRO Person">
                                    {{--                                    <datalist id="users_list">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="phro_code">PHRO Code</label>
                                    <input type="text" class="form-control" id="phro_code" name="phro_code"
                                           placeholder="Enter PHRO">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="phro_unit">PHRO Unit</label>
                                    <input type="text" class="form-control" id="phro_unit" name="phro_unit"
                                           placeholder="Enter PHRO">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="shro_id">SHRO</label>
                                    <input list="users_list" type="text" class="form-control" id="shro_id" name="shro_id"
                                           placeholder="Select SHRO Person">
                                    {{--                                    <datalist id="users_list2">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value=" {{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="shro_code">SHRO Code</label>
                                    <input type="text" class="form-control" id="shro_code" name="shro_code"
                                           placeholder="Enter SHRO Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="shro_unit">SHRO Unit</label>
                                    <input type="text" class="form-control" id="shro_unit" name="shro_unit"
                                           placeholder="Enter SHRO Unit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="audit_id">Audit Personal</label>
                                    <input list="users_list" type="text" class="form-control" id="audit_id" name="audit_id"
                                           placeholder="Select Audit Personal Person">
                                    {{--                                    <datalist id="users_list6">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="audit_code">Audit Code</label>
                                    <input type="text" class="form-control accent-green" id="audit_code" name="audit_code"
                                           placeholder="Enter Audit Personal Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="audit_unit">Personal Unit</label>
                                    <input type="text" class="form-control accent-green" id="audit_unit" name="audit_unit"
                                           placeholder="Enter Audit Personal Unit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="expenditure_id">Expenditure Accountant</label>
                                    <input list="users_list" type="text" class="form-control" id="expenditure_id" name="expenditure_id"
                                           placeholder="Select Expenditure Accountant Person">
                                    {{--                                    <datalist id="users_list4">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
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
                                    <label for="payroll_id">PAYROLL</label>
                                    <input list="users_list" type="text" class="form-control" id="payroll_id" name="payroll_id"
                                           value=""  placeholder="Select PAYROLL Person">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="payroll_code">PAYROLL Code</label>
                                    <input type="text" class="form-control" id="payroll_code" name="payroll_code"
                                           placeholder="Enter PAYROLL">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="payroll_unit">PAYROLL Unit</label>
                                    <input type="text" class="form-control" id="payroll_unit" name="payroll_unit"
                                           placeholder="Enter PAYROLL">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="security_id">Security Personal</label>
                                    <input list="users_list" type="text" class="form-control" id="security_id" name="security_id"
                                           placeholder="Select Security Personal Person">
                                    {{--                                    <datalist id="users_list5">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value="{{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
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
                                    <label for="transport_id">TRANSPORT</label>
                                    <input list="users_list" type="text" class="form-control" id="transport_id" name="transport_id"
                                           placeholder="Select TRANSPORT Person">
                                    {{--                                    <datalist id="users_list2">--}}
                                    {{--                                        @foreach($users as $item)--}}
                                    {{--                                            <option value=" {{$item->id}}" >  {{$item->staff_no}}:  {{$item->name}}</option>--}}
                                    {{--                                        @endforeach--}}
                                    {{--                                    </datalist>--}}
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="transport_code">TRANSPORT Code</label>
                                    <input type="text" class="form-control" id="transport_code" name="transport_code"
                                           placeholder="Enter TRANSPORT Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="transport_unit">TRANSPORT Unit</label>
                                    <input type="text" class="form-control" id="transport_unit" name="transport_unit"
                                           placeholder="Enter TRANSPORT Unit">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="sheq_id">SHEQ</label>
                                    <input list="users_list" type="text" class="form-control" id="sheq_id" name="sheq_id"
                                           placeholder="Select SHEQ Person">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="sheq_code">SHEQ Code</label>
                                    <input type="text" class="form-control" id="sheq_code" name="sheq_code"
                                           placeholder="Enter SHEQ Code">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="sheq_unit">SHEQ Unit</label>
                                    <input type="text" class="form-control" id="sheq_unit" name="sheq_unit"
                                           placeholder="Enter SHEQ Unit">
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


    <!-- USERS MODAL-->
    <div class="modal fade" id="modal-users">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">USERS FOR THIS WORKFLOW</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main.user.unit.update')}}">
                    @csrf
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-12">
                              <div id="table_body_div">


                                  <br> <label class="text-green">Director Approval</label>
                                  <hr>
                                  <div id="directors_div">
                                  </div>


                                  <br> <label class="text-green">Snr Manager Approval</label>
                                  <hr>
                                  <div id="divisional_div">
                                  </div>


                                  <br> <label class="text-green">Chief Accountant Approval</label>
                                  <hr>
                                  <div id="ca_div">
                                  </div>


                                  <br> <label class="text-green">HRM Approval</label>
                                  <hr>
                                  <div id="hrm_div">
                                  </div>


                                  <br> <label class="text-green">HOD Approval</label>
                                  <hr>
                                  <div id="hod_div">
                                  </div>


                                  <br> <label class="text-green">Audit Approval</label>
                                  <hr>
                                  <div id="audit_div">
                                  </div>


                                  <br> <label class="text-green">Expenditure Approval</label>
                                  <hr>
                                  <div id="expenditure_div">
                                  </div>

                                  <br> <label class="text-green">Management Accountants Approval</label>
                                  <hr>
                                  <div id="ma_div">
                                  </div>

                                  <br> <label class="text-green">Security Approval</label>
                                  <hr>
                                  <div id="security_div">
                                  </div>

                                  <br> <label class="text-green">Sheq Approval</label>
                                  <hr>
                                  <div id="sheq_div">
                                  </div>

                                  <br> <label class="text-green">Transport Approval</label>
                                  <hr>
                                  <div id="transport_div">
                                  </div>

                                  <br> <label class="text-green">Payroll Approval</label>
                                  <hr>
                                  <div id="payroll_div">
                                  </div>

                                  <br> <label class="text-green">PSA Approval</label>
                                  <hr>
                                  <div id="psa_div">
                                  </div>

                                  <br> <label class="text-green">PHRO Approval</label>
                                  <hr>
                                  <div id="phro_div">
                                  </div>

                                  <br> <label class="text-green">Area Manager Approval</label>
                                  <hr>
                                  <div id="arm_div">
                                  </div>

                              </div>
                          </div>
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
    <!-- /.USERS modal -->


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

            $('#dr_code').val(recipient.dr_code);
            $('#dr_unit').val(recipient.dr_unit);
            $('#dm_code').val(recipient.dm_code);
            $('#dm_unit').val(recipient.dm_unit);
            $('#hod_code').val(recipient.hod_code);
            $('#hod_unit').val(recipient.hod_unit);
            $('#arm_code').val(recipient.arm_code);
            $('#arm_unit').val(recipient.arm_unit);
            $('#hrm_code').val(recipient.hrm_code);
            $('#hrm_unit').val(recipient.hrm_unit);
            $('#bm_code').val(recipient.bm_code);
            $('#bm_unit').val(recipient.bm_unit);
            $('#ca_code').val(recipient.ca_code);
            $('#ca_unit').val(recipient.ca_unit);
            $('#psa_code').val(recipient.psa_code);
            $('#psa_unit').val(recipient.psa_unit);
            $('#ma_code').val(recipient.ma_code);
            $('#ma_unit').val(recipient.ma_unit);
            $('#phro_code').val(recipient.phro_code);
            $('#phro_unit').val(recipient.phro_unit);
            $('#shro_code').val(recipient.shro_code);
            $('#shro_unit').val(recipient.shro_unit);
            $('#expenditure_code').val(recipient.expenditure_code);
            $('#expenditure_unit').val(recipient.expenditure_unit);
            $('#payroll_code').val(recipient.payroll_code);
            $('#payroll_unit').val(recipient.payroll_unit);
            $('#security_code').val(recipient.security_code);
            $('#security_unit').val(recipient.security_unit);
            $('#transport_code').val(recipient.transport_code);
            $('#transport_unit').val(recipient.transport_unit);
            $('#sheq_code').val(recipient.sheq_code);
            $('#sheq_unit').val(recipient.sheq_unit);
        });


        $('#modal-users').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('sent_data'); // Extract info from data-* attributes
         //   alert(recipient.user_unit_code);
            getMyWorkflow(recipient.user_unit_code ) ;
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
            //dr
            $("#dr_id").change(function () {
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
                $("#dr_code").val(code);
                $("#dr_unit").val(unit);
            });
            //bm
            $("#dm_id").change(function () {
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
                $("#dm_code").val(code);
                $("#dm_unit").val(unit);
            });
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
            //arm
            $("#arm_id").change(function () {
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
                $("#arm_code").val(code);
                $("#arm_unit").val(unit);
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
            //bm
            $("#bm_id").change(function () {
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
                $("#bm_code").val(code);
                $("#bm_unit").val(unit);
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
            //ma
            $("#ma_id").change(function () {
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
                $("#ma_code").val(code);
                $("#ma_unit").val(unit);
            });
            //psa
            $("#psa_id").change(function () {
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
                $("#psa_code").val(code);
                $("#psa_unit").val(unit);
            });
            //phro
            $("#phro_id").change(function () {
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
                $("#phro_code").val(code);
                $("#phro_unit").val(unit);
            });
            //shro
            $("#shro_id").change(function () {
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
                $("#shro_code").val(code);
                $("#shro_unit").val(unit);
            });
            //transport
            $("#transport_id").change(function () {
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
                $("#transport_code").val(code);
                $("#transport_unit").val(unit);
            });

            //payroll_id
            $("#payroll_id").change(function () {
                var selected_value = ''; // Selected value
                // Get selected value
                selected_value += $(this).val();
                //find the user from the selected array
                var users = {!! json_encode( $users->toArray() ) !!};
                unit = "";
                code = "";
                $.each(users, function (index, value) {
                    if (value.id == selected_value) {
                        unit += value.user_unit_code ;
                        code += value.job_code ;
                    }
                });
                $("#payroll_code").val(code);
                $("#payroll_unit").val(unit);
            });

            //sheq
            $("#sheq_id").change(function () {
                var selected_value = ''; // Selected value
                // Get selected value
                selected_value += $(this).val();
                //find the user from the selected array
                var users = {!! json_encode( $users->toArray() ) !!};
                unit = "";
                code = "";
                $.each(users, function (index, value) {
                    if (value.id == selected_value) {
                        unit += value.user_unit_code ;
                        code += value.job_code ;
                    }
                });
                $("#sheq_code").val(code);
                $("#sheq_unit").val(unit);
            });

        });
    </script>

    <script>

        function getMyWorkflow(user_unit) {
            {{--var loader = '{{ asset('dashboard/dist/gif/Eclipse_loading.gif')}}';--}}
            var route = '{{url('work_flow/mine')}}' + '/' + user_unit;

            //alert(route);

            /* AJAX */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: route,
                type: 'get',
                beforeSend: function () {
                    // Show image container
                    $("#loader_c_2").show();
                },
                success: function (response_data) {

                    var response_data = JSON.parse(response_data);

                    console.log(response_data);

                    var responce_dr = "";
                    $.each(response_data['dr'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#directors_div").html("<div class='row'>" + responce_dr + "</div>");

                    var responce_dr = "";
                    $.each(response_data['dm'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#divisional_div").html("<div class='row'>" + responce_dr + "</div>");

                    var responce_dr = "";
                    $.each(response_data['hod'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#hod_div").html("<div class='row'>" + responce_dr + "</div>");
                    var responce_dr = "";
                    $.each(response_data['ca'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#ca_div").html("<div class='row'>" + responce_dr + "</div>");

                    var responce_dr = "";
                    $.each(response_data['hrm'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#hrm_div").html("<div class='row'>" + responce_dr + "</div>");


                    var responce_dr = "";
                    $.each(response_data['arm'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#arm_div").html("<div class='row'>" + responce_dr + "</div>");


                    var responce_dr = "";
                    $.each(response_data['audit'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#audit_div").html("<div class='row'>" + responce_dr + "</div>");


                    var responce_dr = "";
                    $.each(response_data['bm'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#bm_div").html("<div class='row'>" + responce_dr + "</div>");


                    var responce_dr = "";
                    $.each(response_data['expenditure'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#expenditure_div").html("<div class='row'>" + responce_dr + "</div>");


                    var responce_dr = "";
                    $.each(response_data['security'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#security_div").html("<div class='row'>" + responce_dr + "</div>");


                    var responce_dr = "";
                    $.each(response_data['transport'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#transport_div").html("<div class='row'>" + responce_dr + "</div>");


                    var responce_dr = "";
                    $.each(response_data['sheq'], function (index, value) {

                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#sheq_div").html("<div class='row'>" + responce_dr + "</div>");

                    var responce_dr = "";
                    $.each(response_data['shro'], function (index, value) {
                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#shro_div").html("<div class='row'>" + responce_dr + "</div>");

                    var responce_dr = "";
                    $.each(response_data['payroll'], function (index, value) {
                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#payroll_div").html("<div class='row'>" + responce_dr + "</div>");
                    var responce_dr = "";
                    $.each(response_data['phro'], function (index, value) {
                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#phro_div").html("<div class='row'>" + responce_dr + "</div>");

                    var responce_dr = "";
                    $.each(response_data['psa'], function (index, value) {
                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#psa_div").html("<div class='row'>" + responce_dr + "</div>");

                    var responce_dr = "";
                    $.each(response_data['ma'], function (index, value) {
                        //populate the table
                        responce_dr +=
                            "<div class='col-sm-4'>" +
                            "<span class='text-orange'> Name :</span> <span> " + value.name + "</span> <br>" +
                            "<span class='text-orange'> Email :</span> <span> " + value.email + "</span> <br>" +
                            "<span class='text-orange'>  Job Code :</span> <span>" + value.job_code + " </span> <br>" +
                            "<span class='text-orange'> User-Unit :</span> <span>" + value.user_unit_code + " </span> <br><br>" +
                            "</div>";
                    });
                    $("#ma_div").html("<div class='row'>" + responce_dr + "</div>");


                },
                complete: function (response_data) {
                    // Hide image container
                    $("#loader_c_2").hide();
                }
            });

        }

    </script>

@endpush
