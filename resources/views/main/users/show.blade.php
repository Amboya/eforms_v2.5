@extends('layouts.main.master')


@push('custom-styles')

@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
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


        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <a href="#">
                                    <img class="profile-user-img img-fluid img-circle"
                                         src="{{asset('storage/user_avatar/'.$user->avatar)}}"
                                         alt="Image not found"
                                         onerror="this.src='{{asset('dashboard/dist/img/avatar.png')}}';"

                                         @if( Auth::user()->id == $user->id )
                                         title="Click Here to Edit Image"
                                         data-toggle="modal"
                                         data-target="#modal-edit-profile"
                                        @endif
                                    >
                                </a>
                            </div>

                            <h3 class="profile-username text-center">{{$user->name}}</h3>

                            <p class="text-muted text-center">{{$user->position->name ?? "Position"}}</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')|| Auth::user()->type_id ==  config('constants.user_types.mgt')  )
                                    <li class="list-group-item">
                                        <b>Man Number</b> <a class="float-right">{{$user->staff_no}}</a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>NRC</b> <a class="float-right">{{$user->nrc}}</a>
                                    </li>
                                @endif
                                <li class="list-group-item">
                                    <b>Phone</b> <a class="float-right">{{$user->phone}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{$user->email}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Application Forms</b> <a class="float-right">{{$user->total_forms}}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Logins</b> <a class="float-right">{{$user->total_login}}</a>
                                </li>
                            </ul>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Details</a>
                                </li>
                                @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')|| Auth::user()->type_id ==  config('constants.user_types.mgt')  )
                                    <li class="nav-item"><a class="nav-link" href="#settings"
                                                            data-toggle="tab">Settings</a>
                                    </li>
                                @endif
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="activity">
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username"> <a href="#">Company</a>  </span>
                                        </div>
                                        <!-- /.user-block -->
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-muted">
                                                    <b>Directorate:</b> {{$user->directorate->name  ?? ""}}  </p>
                                                <p class="text-muted"><b>PayPoint:</b> {{$user->pay_point->name  ?? ""}}
                                                </p>
                                                <p class="text-muted"><b>Location:</b> {{$user->location->name  ?? ""}}
                                                </p>
                                                <p class="text-muted"><b>Division:</b> {{$user->division->name  ?? ""}}
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p class="text-muted"><b>User
                                                        Unit:</b> {{$user->user_unit->user_unit_description  ?? ""}}  </p>
                                                <p class="text-muted "><b class=" text-orange">User Unit
                                                            Code:</b> {{$user->user_unit->user_unit_code  ?? ""}}  </p>
                                                <p class="text-muted"><b>Business
                                                        Unit:</b> {{$user->user_unit->user_unit_bc_code  ?? ""}}
                                                </p>
                                                <p class="text-muted"><b>Cost
                                                        Center:</b> {{$user->user_unit->user_unit_cc_code  ?? ""}}  </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.post -->
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username"> <a href="#">Position and Profiles</a> </span>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p class="text-muted"><b>User
                                                        Type:</b> {{$user->user_type->name  ?? ""}}  </p>
                                                <p class="text-muted"><b class=" text-orange">User
                                                        Profiles:</b>
                                                    @foreach($user->user_profile as $item)
                                                        {{$item->form->name  ?? ""}}
                                                        :  {{$item->profiles->code  ?? ""}}
                                                        : {{$item->profiles->name  ?? ""}} <br>
                                                    @endforeach
                                                </p>
                                                <p class="text-muted"><b>Delegated
                                                        Profiles:</b>
                                                    @foreach($user->user_profile as $item)
                                                        {{$item->form->name  ?? ""}}
                                                        :  {{$item->profiles->code  ?? ""}}
                                                        : {{$item->profiles->name  ?? ""}} <br>
                                                    @endforeach
                                                </p>
                                            </div>
                                            @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')|| Auth::user()->type_id ==  config('constants.user_types.mgt')  )
                                                <div class="col-6">
                                                    <p class="text-muted"><b>Contract
                                                            Type:</b> {{$user->contract_type  ?? ""}}  </p>
                                                    <p class="text-muted"><b>Grade:</b> {{$user->grade->name  ?? ""}}
                                                    </p>
                                                    <p class="text-muted">
                                                        <b>Category:</b> {{$user->grade->category->name  ?? ""}}  </p>

                                                    <p class="text-muted"><b>User
                                                            Position:</b> {{$user->position->name  ?? ""}}  </p>
                                                    <p class="text-muted "><b class="text-orange ">Job
                                                            Code:</b> {{$user->job_code  ?? ""}}  </p>
{{--                                                    <p class="text-muted"><b>--}}
{{--                                                            Position Code:</b> {{$user->position->code  ?? ""}} ,--}}
{{--                                                        Superior: {{$user->position->superior_code  ?? ""}}  </p>--}}
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                    <!-- /.post -->
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="timeline">
                                    <!-- The timeline -->
                                    <div class="timeline timeline-inverse">


                                        <!-- timeline time label 1-->
                                        <div class="time-label">
                                            <span class="bg-danger">  10 Feb. 2014   </span>
                                        </div>
                                        <!-- /.timeline-label -->

                                        <!-- timeline item 1 -->
                                        <div>
                                            <i class="fas fa-envelope bg-primary"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> 12:05</span>

                                                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an
                                                    email</h3>

                                                <div class="timeline-body">
                                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                    weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                    quora plaxo ideeli hulu weebly balihoo...
                                                </div>
                                                <div class="timeline-footer">
                                                    <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END timeline item -->

                                        <!-- timeline time label 2 -->
                                        <div class="time-label">
                                            <span class="bg-danger">  10 Feb. 2014   </span>
                                        </div>
                                        <!-- /.timeline-label -->

                                        <!-- timeline item 2-->
                                        <div>
                                            <i class="fas fa-envelope bg-primary"></i>

                                            <div class="timeline-item">
                                                <span class="time"><i class="far fa-clock"></i> 12:05</span>

                                                <h3 class="timeline-header"><a href="#">Support Team</a> sent you an
                                                    email</h3>

                                                <div class="timeline-body">
                                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                    weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                    quora plaxo ideeli hulu weebly balihoo...
                                                </div>
                                                <div class="timeline-footer">
                                                    <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END timeline item -->


                                    </div>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="settings">
                                    <form class="form-horizontal" method="post"
                                          action="{{route('main-user-update', $user->id )}}">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="name" required
                                                       placeholder="Name" value="{{$user->name}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="email" required
                                                       placeholder="Email" value="{{$user->email}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Phone</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" name="phone" required
                                                       placeholder="Phone" value="{{$user->phone}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputExperience"
                                                   class="col-sm-2 col-form-label">User Type</label>
                                            <div class="col-sm-10">
                                                <select class="form-control" name="user_type_id" required>
                                                    <option
                                                        value="{{$user->user_type->id  ?? ""}} ">{{$user->user_type->name  ?? "Please Select User Type"}} </option>

                                                    @if(\Illuminate\Support\Facades\Auth::user()->id != $user->id)
                                                        @if( Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                                            @foreach($user_types as $item)
                                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label text-orange ">User Unit</label>
                                            <div class="col-sm-10">
                                                <select id="user_unit_new" class="form-control " name="user_unit_new"  >
                                                    <option value="{{$user->user_unit->id  ?? ""}} ">{{$user->user_unit->user_unit_description ?? ""}} : {{$user->user_unit->user_unit_code  ?? "Please Select User Unit"}} </option>
                                                    @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                                    @foreach($user_unit_new as $item)
                                                        <option value="{{$item->id}}" >{{$item->user_unit_description}} : {{$item->user_unit_code}}</option>
                                                    @endforeach
                                                        @endif
                                                </select>

{{--                                                <input  list="user_unit_list" id="user_unit_new" class="form-control " name="user_unit_new">--}}

{{--                                                <datalist id="user_unit_list" >--}}
{{--                                                    @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')  )--}}
{{--                                                        @foreach($user_unit_new as $item)--}}
{{--                                                            <option value="{{$item->id}}" >{{$item->code}} : {{$item->name}}</option>--}}
{{--                                                        @endforeach--}}
{{--                                                    @endif--}}
{{--                                                </datalist>--}}

                                            </div>
                                        </div>

{{--                                        <div class="form-group row">--}}
{{--                                            <label for="user_position_id" class="col-sm-2 col-form-label">Code Position</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <input list="user_position_list" id="user_position_id" class="form-control " name="user_position_id"  >--}}
{{--                                                    <datalist id="user_position_list"  >--}}
{{--                                                    <option value="{{$user->position->id  ?? ""}} ">--}}
{{--                                                        {{$user->position->code  ?? "Code"}} :--}}
{{--                                                        {{$user->position->name  ?? "Please Select Users Code Position"}}--}}
{{--                                                    </option>--}}
{{--                                                    @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')  )--}}
{{--                                                    @foreach($positions_with_code_positions as $item)--}}
{{--                                                        <option value="{{$item->id}}" >{{$item->code}} : {{$item->name}}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                    @endif--}}
{{--                                                </datalist>--}}

{{--                                            </div>--}}
{{--                                        </div>--}}


                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Man No</label>
                                            <div class="col-sm-10">
                                                <input disabled type="text" class="form-control" name="staff_no"
                                                       required
                                                       placeholder="Staff No" value="{{$user->staff_no}}">
                                            </div>
                                        </div>


{{--                                        <div class="form-group row">--}}
{{--                                            <label for="inputExperience"--}}
{{--                                                   class="col-sm-2 col-form-label">User Directorate</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <select disabled class="form-control" id="directorate_select"--}}
{{--                                                        name="user_directorate_id">--}}
{{--                                                    <option disabled>{{$user->directorate->name  ?? ""}}</option>--}}
{{--                                                    @foreach($directorates as $item)--}}
{{--                                                        <option value="{{$item->id}}">{{$item->name}}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

{{--                                        <div class="form-group row">--}}
{{--                                            <label for="inputExperience"--}}
{{--                                                   class="col-sm-2 col-form-label">User Unit</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <select disabled class="form-control" id="user_unit_select"--}}
{{--                                                        name="user_unit_id">--}}
{{--                                                    <option--}}
{{--                                                        value="{{$user->user_unit->id  ?? ""}}  ">{{$user->user_unit->name  ?? ""}}  </option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="form-group row">
                                            <label for="inputExperience"
                                                   class="col-sm-2 col-form-label">User Division</label>
                                            <div class="col-sm-10">
                                                <select disabled class="form-control" id="division_select"
                                                        name="user_division_id">
                                                    <option
                                                        value="{{$user->division->id  ?? ""}}  ">{{$user->division->name  ?? ""}}  </option>
                                                </select>
                                            </div>
                                        </div>

{{--                                        <div class="form-group row">--}}
{{--                                            <label for="inputExperience"--}}
{{--                                                   class="col-sm-2 col-form-label">User Region</label>--}}
{{--                                            <div class="col-sm-10">--}}
{{--                                                <select disabled class="form-control" id="regions_select"--}}
{{--                                                        name="user_region_id">--}}
{{--                                                    <option--}}
{{--                                                        value="{{$user->region->id  ?? ""}}  ">{{$user->region->name  ?? ""}}  </option>--}}
{{--                                                    <option disabled value="">Select Region</option>--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}

                                        <div class="form-group row">
                                            @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                                <div class="offset-sm-2 col-sm-4">
                                                    <button type="submit" class="btn btn-danger">Update</button>
                                                </div>
                                                <div class="offset-sm-5 col-sm-1" style="align-content: end">
                                                    <a href="{{route('main-user-sync',$user->id )}}"
                                                       class="btn btn-default"> Sync <i class="fas fa-sync"></i> </a>
                                                </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->



    <!-- VIEW MODAL-->
    <div class="modal fade" id="modal-edit-profile">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">{{$user->name}} Profile Picture</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form" method="post" action="{{route('main-user-avatar',$user->id)}}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <label>
                                    <input style="display: none" class="form-control" type="file"
                                           id="avatar"
                                           name="avatar" value="{{$user['img']}}">
                                    <img class="img-fluid " width="100%"
                                         src="{{asset('storage/user_avatar/'.$user->avatar)}}"
                                         alt="Image not found"
                                         onerror="this.src='{{asset('dashboard/dist/img/avatar.png')}}';">
                                    <small id="fileHelp" class="form-text text-muted"><b>Click Image to change it</b>.
                                        Size of image should not be more than 2MB.</small>
                                </label>


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
    <!-- /.View modal -->



@endsection


@push('custom-scripts')
    <!--  -->
    <script>
        $(document).ready(function () {
            $("#directorate_select").change(function () {
                var selected_text = ''; // Selected text
                var selected_value = ''; // Selected value
                var selected_index = ''; // Selected index
                // Get selected value
                $('#directorate_select option:selected').each(function () {
                    selected_text += $(this).text();
                    selected_value += $(this).val();
                    selected_index += $(this).index();
                });
                //
                var division = {!! json_encode($divisions->toArray()) !!};
                responce = " <option selected disabled=\"true\"  value=\"\"> Select Division</option>";
                user_uni = " ";
                $.each(division, function (index, value) {
                    if (value.directorate_id == selected_value) {
                        console.log(value.directorate.user_unit);
                        user_uni = "<option value=" + value.directorate.user_unit.id + "    > " + value.directorate.user_unit.name + "  </option> ";
                        responce += "<option value=" + value.id + "    > " + value.name + "  </option> ";
                    }
                });
                $("#division_select").html(responce);
                $("#user_unit_select").html(user_uni);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $("#division_select").change(function () {
                var selected_text = ''; // Selected text
                var selected_value = ''; // Selected value
                var selected_index = ''; // Selected index
                // Get selected value
                $('#division_select option:selected').each(function () {
                    selected_text += $(this).text();
                    selected_value += $(this).val();
                    selected_index += $(this).index();
                });
                var region = {!! json_encode($regions->toArray()) !!};
                responce = " <option selected disabled=\"true\"  value=\"\"> Select Region</option>";
                $.each(region, function (index, value) {
                    if (value.division_id == selected_value) {
                        responce += "<option value=" + value.id + "    > " + value.name + "  </option> ";
                    }
                });
                $("#regions_select").html(responce);
            });
        });
    </script>
@endpush
