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
                    <h1 class="m-0 text-dark">Profile Delegation</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Profile Delegation</li>
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
                    <span>Select User to delegate your profile</span>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fas fa-minus"></i></button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- form start -->
                    <form role="form-new" method="post" action="{{route('main.profile.delegation.store')}}">
                        @csrf
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Select User</label>
                                        <select class="form-control select2" name="user_id" required
                                                style="width: 100%;">
                                            <option disabled  value="" selected>Select User</option>
                                            @foreach($users as $user)
                                                @if( ($user->functional_unit_id  == \Auth::user()->functional_unit_id) && ($user->id  != \Auth::user()->id))
                                                    <option
                                                        value="{{$user->id}}"> {{$user->name}} : {{$user->staff_no}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Select Profile</label>
                                        <select class="form-control select2" id="profile_select" name="profile" required
                                                style="width: 100%;">
                                            <option disabled value="" selected>Select Profile to Delegate</option>
                                            @foreach($profiles as $profile)
                                                <option
                                                    value="{{$profile->profiles->id ?? ''}}">   {{$profile->form->name  ?? ""}} : {{$profile->profiles->code ?? ''}} : {{$profile->profiles->name ?? ''}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-6 ">
                                    <div class="form-group">
                                        <label>Select E-Form</label>
                                        <select class="form-control select2" id="eform_select" name="eform_id" required
                                                style="width: 100%;">
                                        </select>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-6 ">
                                    <div class="form-group">
                                        <label>Delegation End-Date</label>
                                        <input type="date" class="form-control" name="delegation_end_date" id="delegation_end_date">
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
            $("#profile_select").change(function () {
                var selected_text = ''; // Selected text
                var selected_value = ''; // Selected value
                var selected_index = ''; // Selected index
                // Get selected value
                $('#profile_select option:selected').each(function () {
                    selected_text += $(this).text();
                    selected_value += $(this).val();
                    selected_index += $(this).index();
                });

                var profile = {!! json_encode($profiles->toArray()) !!};
                var profile_form = profile[selected_index-1] ;

               // console.log(profile_form.form );
                responce =  "<option value=" + profile_form.form.id + "   > " + profile_form.form.name + "  </option> ";

                $("#eform_select").html(responce);

            });
        });
    </script>


@endpush
