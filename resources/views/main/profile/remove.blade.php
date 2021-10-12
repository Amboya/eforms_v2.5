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
                    <h1 class="m-0 text-dark">Profile Remove</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Profile Remove</li>
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
                <span>Select User to transfer profiles to</span>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                        <i class="fas fa-minus"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- form start -->
                <form role="form-new" method="post" action="{{route('main.profile.remove.create')}}">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Select Profile Owner</label>
                                    <input list="list1" class="form-control select2" id="owner_id" name="owner_id"
                                           required
                                           style="width: 100%;">
                                    <datalist id="list1">
                                        <option disabled value="" selected>Select Profile Owner</option>
                                        @foreach($users as $user)
                                            @if($user->id  != Auth::user()->id)
                                                <option
                                                    value="{{$user->id}}"> {{$user->name}}
                                                    : {{$user->staff_no}}  </option>
                                            @endif
                                        @endforeach
                                    </datalist>
                                </div>
                                <!-- /.form-group -->
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                <label>Select Profile to transfer</label>
                                <table  class="table m-0">
                                    {{--                                    @endif--}}
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                    </tr>
                                    </thead>
                                    <tbody id="profiles">

                                    </tbody>
                                </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Remove</button>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
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
            $("#owner_id").change(function () {
                // Get selected value
                var selected_value1 = $("#owner_id").val();
                //
                var profile = {!! json_encode($profiles->toArray()) !!};
                var responce1 = "  ";
                var responce2 = "  ";
                var responce3 = "  ";

                $.each(profile, function (index, value1) {
                    if (value1.user_id == selected_value1) {
                        var pro =  '"'+value1.profiles.code +'"';
                        responce2 += "<tr><td> " +
                            "<div class='icheck-warning d-inline'> " +
                            "<input type='checkbox' value='{"+'"profiles"'+":" + pro +" ,"+'"form"'+":" + value1.form.id +"}'  id='remove_profiles[]'" +
                            "name='remove_profiles[]'> " +
                            // "<input type='hidden' value=" + value1.form.id +" id='transfer_forms[]'" +
                            // "name='transfer_forms[]'> " +
                        "</div> " +
                        "</td> " +
                        "<td>"+ value1.profiles.code +"</td> " +
                        "<td>"+ value1.profiles.name +"</td> " +
                        "<td>"+ value1.form.name +"</td> /<tr>" ;
                    }
                });
                //
                $("#profiles").html(responce1+responce2+responce3);

            });


            $("#profile_select").change(function () {
                // Get selected value
                var selected_value2 = $("#profile_select").val();

                var profiles = {!! json_encode($profiles->toArray()) !!};
                var responce = "";
                var profile_id = $("#profile_select").children(':selected').data('profile_id')

                $.each(profiles, function (index, value) {
                    if (value.id == profile_id) {
                        responce += "<option value=" + value.form.id + "    > " + value.form.name + "  </option> ";
                    }
                });
                $("#eform_select").html(responce);

            });
        });
    </script>


@endpush
