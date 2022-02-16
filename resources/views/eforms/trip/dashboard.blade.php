@extends('layouts.eforms.trip.master')


@push('custom-styles')
    <!-- -->
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark text-uppercase text-green ">Trip</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Trip</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


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

            <!-- Info boxes -->
            <div class="row">
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'trip.list', config('constants.trip_status.new_trip') ) }}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> New Forms</span>
                            <span class="info-box-number">{{ $totals['new_forms'] }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'trip.list', 'pending')}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Open Forms</span>
                            <span class="info-box-number">{{ $totals['pending_forms'] }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>

                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'trip.list', config('constants.trip_status.new_trip'))}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Closed Forms</span>
                            <span class="info-box-number">{{ $totals['closed_forms'] }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'trip.list', config('constants.petty_cash_status.rejected'))}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Rejected Forms</span>
                            <span class="info-box-number">{{ $totals['rejected_forms'] }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>


                <!-- /.col -->

            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <div class="col-md-12" >
                    <a class="btn  bg-green float-left mb-2"
                       href="{{route('subsistence.home')}}"
                       title="Go to trips">
                        Subsistence  <i class="fas fa-arrow-right"></i></a>
                </div>
                <!-- Left col -->
                <div class="col-md-12">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent  bg-gradient-orange " style="opacity: .9">
                            <h3 class="card-title">Needs your Attention</h3>  <span
                                class="badge badge-success right ml-2">{{$list->count()}}</span>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table m-0">
                                    <thead>
                                    <tr>
                                        <th>Serial</th>
                                        <th>Name</th>
                                        <th>Destination</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Members</th>
                                        <th>Status</th>
                                        <th>Period</th>
                                        <td>View</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $list as $item )
                                        <tr>
                                            <td><a href="{{ route('logout') }}" class="dropdown-item"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form'+{{$item->id}}).submit();"> {{$item->code}}</a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('trip.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </td>
                                            <td>{{$item->name}}</td>
                                            <td>{{$item->destination}}</td>
                                            <td>{{$item->date_from}}</td>
                                            <td>{{$item->date_to}}</td>
                                            <td>Invited: {{$item->invited}}, Subscribed:{{sizeof($item->members) ?? "bra"}} </td>
                                            <td><span
                                                    class="badge badge-{{$item->status->html}}">{{$item->status->name}}</span>
                                            </td>
                                            <td>{{$item->created_at->diffForHumans()}}</td>
                                            <td><a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                                   onclick="event.preventDefault();
                                                           document.getElementById('show-form'+{{$item->id}}).submit();"> view</a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('trip.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_004')     )
                                <a href="{{route('trip.create')}}"
                                   class="btn btn-sm bg-gradient-green float-left">New Trip</a>
                                <a href="{{route( 'trip.list', 'existing')}}"
                                   class="btn btn-sm bg-gradient-orange float-left ml-2">Existing Trip</a>
                            @endif
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->



    <!-- CREATE NEW TRIP-->
    <div class="modal fade" id="modal-trip">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Create a new Trip</h4>
                </div>
                <!-- form start -->
                <form role="form-new" method="post" action="{{route('trip.store')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="code">Directorate</label>
                                    <input type="text" class="form-control " id="user_unit_code" name="user_unit_code"
                                           placeholder="Enter user unit code e.g C1931">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="code">User Unit Code</label>
                                    <input type="text" class="form-control " id="user_unit_code" name="user_unit_code"
                                           placeholder="Enter user unit code e.g C1931">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


@endsection


@push('custom-scripts')
    <!--  -->
@endpush
