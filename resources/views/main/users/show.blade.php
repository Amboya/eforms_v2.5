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
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
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
                                    <b>Status</b> <a class="float-right">{{$user->con_st_code ?? ""}}</a>
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
                        @if(  Auth::user()->type_id ==  config('constants.user_types.developer')|| Auth::user()->type_id ==  config('constants.user_types.mgt')  )

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- SEARCH FORM -->
                                        <div class="modal-header">
                                            <label>FIND USER</label>
                                        </div>
                                        <div class="modal-body">
                                            <form class="form-inline ml-3" method="post"
                                                  action="{{route('main.user.search')}}">
                                                @csrf
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control form-control-navbar" type="search"
                                                           name="search" placeholder="Enter Man Number/Name"
                                                           aria-label="Enter Search Term">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-navbar" type="submit">
                                                            <i class="fas fa-search"> Search User</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- PROFILE FORM -->
                                        <form role="form-new" method="post"
                                              action="{{route('main.profile.assignment.store.single')}}">
                                            @csrf
                                            <div class="modal-header">
                                                <label>PROFILE ASSIGNMENT</label>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Select E-Form</label>
                                                            <select class="form-control select2" id="eform_select"
                                                                    name="eform_id" required
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
                                                    <div class="col-6 ">
                                                        <div class="form-group">
                                                            <label>Select User Profile</label>
                                                            <select class="form-control select2" id="profile_select"
                                                                    name="profile" required
                                                                    style="width: 100%;">
                                                                <option value="" selected>Assign Profile</option>
                                                            </select>
                                                        </div>
                                                        <!-- /.form-group -->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <select class="form-control " id="user_select"
                                                                    name="user_id" readonly required>
                                                                <option
                                                                    value="{{$user->id}}"> {{$user->staff_no}} </option>
                                                            </select>
                                                        </div>
                                                        <!-- /.form-group -->
                                                    </div>
                                                    <div class="col-6">
                                                        @if($user->con_st_code == config('constants.phris_user_active'))
                                                        <button type="submit" class="btn btn-primary">Submit</button>
                                                        @else
                                                            <button disabled type="submit" class="btn btn-primary">Submit</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
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
                                <li class="nav-item"><a class="nav-link " href="#units" data-toggle="tab">My User-Units</a>
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
                                                        Unit:</b>
                                                    <a href="{{ route('logout') }}" class="text-dark"
                                                       onclick="event.preventDefault();
                                                           document.getElementById('search-form12').submit();">
                                                        {{$user->user_unit->user_unit_description  ?? ""}} </a>
                                                <form id="search-form12"
                                                      action="{{ route('main.user.unit.search.profile', $user->user_unit->id ) }}"
                                                      method="post" class="d-none">
                                                    @csrf
                                                </form>
                                                </p>
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
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                    <!-- /.post -->
                                    <!-- Post -->
                                    <div class="post">
                                        <div class="user-block">
                                            <span class="username"> <a href="#">PROFILES</a> </span>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <form role="form-remove" method="post"
                                                      action="{{route('main.profile.remove.create')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input hidden value="{{$user->id}}"
                                                               class="form-control select2" id="owner_id"
                                                               name="owner_id"
                                                               required
                                                               style="width: 100%;">
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="text-green">ACTIVE PROFILES</label>
                                                            <table class="table m-0">
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

                                                                @foreach($user->user_profile as $item)
                                                                    <tr>
                                                                        <td>
                                                                            <div
                                                                                class="icheck-warning d-inline">
                                                                                <input type="checkbox"
                                                                                       value='{"profiles": "{{$item->profile}}" ,"form":{{$item->form->id}}}'
                                                                                       id="remove_profiles[]"
                                                                                       name="remove_profiles[]">
                                                                            </div>
                                                                        </td>
                                                                        <td> {{$item->profiles->code}}  </td>
                                                                        <td>  {{$item->profiles->name}} </td>
                                                                        <td>  {{$item->form->name}} </td>
                                                                    <tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @if( Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                                            <button type="submit" class="btn btn-sm btn-warning">
                                                                Remove
                                                            </button>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-6">
                                                <form role="form-remove-delegate" method="post"
                                                      action="{{route('main.profile.delegation.remove')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input hidden value="{{$user->id}} : {{$user->name}}"
                                                               class="form-control select2" id="owner_id"
                                                               name="owner_id"
                                                               required
                                                               style="width: 100%;">
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="text-green">DELEGATED PROFILES</label>
                                                            <table class="table m-0">
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
                                                                @foreach($delegated_profiles as $item_p)
                                                                    <tr>
                                                                        <td>
                                                                            <div
                                                                                class="icheck-warning d-inline">
                                                                                <input type="checkbox"
                                                                                       value='{{$item_p->id}}'
                                                                                       id="delegated_profiles[]"
                                                                                       name="delegated_profiles[]">
                                                                            </div>
                                                                        </td>
                                                                        <td> {{$item_p->form->code ?? ""}}  </td>
                                                                        <td>  {{$item_p->profile->name}} </td>
                                                                        <td>  {{$item_p->me->name}} </td>
                                                                    <tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @if( Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                                            <button type="submit" class="btn btn-sm btn-warning">
                                                                Remove
                                                            </button>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /.post -->
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="settings">
                                    <form class="form-horizontal" method="post"
                                          action="{{route('main.user.update', $user->id )}}">
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
                                            <label for="inputName2" class="col-sm-2 col-form-label text-orange ">User
                                                Unit</label>
                                            <div class="col-sm-10">
                                                <select id="user_unit_new" class="form-control " name="user_unit_new">
                                                    <option
                                                        value="{{$user->user_unit->id  ?? ""}} ">{{$user->user_unit->user_unit_description ?? ""}}
                                                        : {{$user->user_unit->user_unit_code  ?? "Please Select User Unit"}} </option>
                                                    @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                                        @foreach($user_unit_new as $item)
                                                            <option
                                                                value="{{$item->id}}">{{$item->user_unit_description}}
                                                                : {{$item->user_unit_code}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>


                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label for="inputName2" class="col-sm-2 col-form-label">Man No</label>
                                            <div class="col-sm-10">
                                                <input disabled type="text" class="form-control" name="staff_no"
                                                       required
                                                       placeholder="Staff No" value="{{$user->staff_no}}">
                                            </div>
                                        </div>

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


                                        <div class="form-group row">
                                            @if( Auth::user()->id == $user->id || Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                                <div class="offset-sm-2 col-sm-4">
                                                    <button type="submit" class="btn btn-danger">Update</button>
                                                </div>
                                                <div class="offset-sm-5 col-sm-1" style="align-content: end">
                                                    <a href="{{route('main.user.sync',$user->id )}}"
                                                       class="btn btn-default"> Sync <i class="fas fa-sync"></i> </a>
                                                </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane" id="units">
                                    <!-- Post -->
                                    <div class="post">
{{--                                        <div class="user-block">--}}
{{--                                            <span class="username"> <a href="#">My Units</a> </span>--}}
{{--                                        </div>--}}
                                        <div class="row">
                                            <div class="col-6">
                                                <form role="form-units" method="post"
                                                      action="{{route('main.profile.remove.create')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input hidden value="{{$user->id}}"
                                                               class="form-control select2" id="owner_id"
                                                               name="owner_id"
                                                               required
                                                               style="width: 100%;">
                                                    </div>

                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="text-green">ACTIVE UNITS</label>
                                                            <table class="table m-0">
                                                                {{--                                    @endif--}}
                                                                <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Name</th>
                                                                    <th>Code</th>
                                                                    <th>BU</th>
                                                                    <th>CC</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody id="units">

                                                                @foreach($responsible_units as $item)
                                                                    <tr>
                                                                        <td>
                                                                            <div
                                                                                class="icheck-warning d-inline">
                                                                                <input type="checkbox"
                                                                                       value='{"code": "{{$item->user_unit_code}}" ,"id":{{$item->id}}}'
                                                                                       id="transfer_units[]"
                                                                                       name="transfer_units[]">
                                                                            </div>
                                                                        </td>
                                                                        <td> {{$item->user_unit_description}}  </td>
                                                                        <td>  {{$item->user_unit_code}} </td>
                                                                        <td>  {{$item->user_unit_bc_code}} </td>
                                                                        <td>  {{$item->user_unit_cc_code}} </td>
                                                                    <tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="col-6">
                                                <form role="form-assign_units" method="post"
                                                      action="{{route('main.user.unit.assign')}}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input hidden value="{{$user->id}}"
                                                               class="form-control select2" id="owner_id"
                                                               name="owner_id"
                                                               required
                                                               style="width: 100%;">
                                                    </div>
                                                    @if( Auth::user()->type_id ==  config('constants.user_types.developer')  )
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="text-orange">ASSIGN UNITS</label>
                                                            <div class="col-12">
                                                                <input class="form-control" id="myInput" type="text" placeholder="Search..">
                                                            </div>
                                                        </div>
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
                                                                    @foreach($user_unit_new as $item)
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


                                                                <button type="submit" class="btn btn-sm btn-info">
                                                                    Assign
                                                                </button>
                                                    </div>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.post -->
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
                <form role="form" method="post" action="{{route('main.user.avatar',$user->id)}}"
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
                        responce += "<option value=" + value.profiles.code + "  > " + value.profiles.code + " : " + value.profiles.name + "  </option> ";
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
