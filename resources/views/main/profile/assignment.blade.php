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
                    <h1 class="m-0 text-dark">Profile Assignment To Units</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Profile Assignment To Units</li>
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
        @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible">
                <p class="lead"> {{session()->get('error')}}</p>
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
                <span>Assign a user a new profile</span>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fas fa-minus"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form-new" method="post" action="{{route('main.profile.assignment.store')}}">
                @csrf
                <div class="modal-body">

                    <div class="row">

                        <div class="col-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="text-uppercase mb-2 text-green" >Select User</label>
                                        <input list="user_list1" class="form-control " id="user_select" name="user_id" required
                                               style="width: 100%;">
                                        <datalist id="user_list1">
                                            <option value="" selected>Select User</option>
                                            @foreach($users as $user)
                                                @if($user->id  != \Auth::user()->id)
                                                    <option
                                                        value="{{$user->id}}"> {{$user->name}} :  {{$user->staff_no}} </option>
                                                @endif
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="text-uppercase mb-2 text-green" >Select E-Form</label>
                                        <select class="form-control select2" id="eform_select" name="eform_id" required
                                                style="width: 100%;">
                                            <option value="" selected>Select E-Form</option>
                                            @foreach($eforms as $eform)
                                                <option
                                                    value="{{$eform->id}}"> {{$eform->name}} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="text-uppercase mb-2 text-green" >Select User Profile</label>
                                        <select class="form-control select2" id="profile_select" name="profile" required
                                                style="width: 100%;">
                                            <option value="" selected>Assign Profile</option>
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="row">
                                <div class="col-sm-12">
                                    <label class="text-uppercase mb-2 text-green" for="code">Select User Units</label><br>
                                    <div class="col-12">
                                        <input class="form-control" id="myInput" type="text" placeholder="Search..">
                                    </div>
                                    <div class="col-12">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>BU</th>
                                                <th>CC</th>
                                            </tr>
                                            </thead>
                                            <tbody id="myTable">
                                            @foreach($user_units as $item)
                                                <tr>
                                                    <td>
                                                        <div class="form-group clearfix">
                                                            <div class="icheck-warning d-inline">
                                                                <input type="checkbox"
                                                                       value="{{$item->id}}"
                                                                       id="units[]" name="units[]">

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><span for="accounts"> <span
                                                                class="text-gray">{{$item->user_unit_code}}</span>  </span>
                                                    </td>
                                                    <td><span for="accounts"> <span
                                                                class="text-gray">{{$item->user_unit_description}}</span>  </span>
                                                    </td>
                                                    <td><span for="accounts"> <span
                                                                class="text-gray">{{$item->user_unit_bc_code}}</span>  </span>
                                                    </td>
                                                    <td><span for="accounts"> <span
                                                                class="text-gray">{{$item->user_unit_cc_code}}</span> </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        <div class="pagination-sm">
{{--                                            {!! $user_units->links() !!}--}}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->



@endsection


@push('custom-scripts')

    <!-- DataTables -->
    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

    <!-- page script -->

    <script>
        $(document).ready(function () {
            $("#eform_select").change(function () {
                var selected_text = ''; // Selected text
                var selected_value = ''; // Selected value
                var selected_index = ''; // Selected index
                // Get selected value
                $('#eform_select option:selected').each(function () {
                    selected_text += $(this).text();
                    selected_value += $(this).val();
                    selected_index += $(this).index();
                });

                var profile = {!! json_encode($profiles->toArray()) !!};
                // console.log(division[1].directorate.user_unit);
                // console.log(selected_value);
                responce = " <option selected disabled=\"true\"  value=\"\"> Select Profile</option>";
                $.each(profile, function (index, value) {
                    if (value.eform_id == selected_value) {
                        responce +=  "<option value=" + value.profiles.code + "  > " + value.profiles.code + " : "+ value.profiles.name + "  </option> ";
                    }
                });
                $("#profile_select").html(responce);

            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>


@endpush
