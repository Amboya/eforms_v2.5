@extends('layouts.eforms.datacenter-ca.master')


@push('custom-styles')
    <!-- -->
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Data Center Critical Assets Register</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Data Center Critical Assets Register</li>
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

        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'datacenter.ca.list', config('constants.data_center_ca_status.new_submission') ) }}">
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
                           href="{{route( 'datacenter.ca.list', 'pending')}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> All Forms</span>
                            <span class="info-box-number">{{ $totals['all_forms'] }}</span>
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
                           href="{{route( 'datacenter.ca.list', 'pending')}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Very Critical</span>
                            <span class="info-box-number">{{ $totals['very_critical'] }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <a class="info-box-icon bg-gray elevation-1"
                           href="{{route( 'datacenter.ca.list', 'pending')}}">
                            <span><i class="fa fa-file"></i></span>
                        </a>
                        <div class="info-box-content">
                            <span class="info-box-text"> Critical</span>
                            <span class="info-box-number">{{ $totals['critical'] }}</span>
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
                <!-- Left col -->
                <div class="col-md-12">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="card">
                        <div class="card-header border-transparent  bg-gradient-orange " style="opacity: .9">
                            <h3 class="card-title">Forms Today</h3>  <span
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
                                        <th>Asset Name</th>
                                        <th>Asset Category</th>
                                        <th>Physical Location</th>
                                        <th>Status</th>
                                        <th>Period</th>
                                        <th>View</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $list as $item )
                                        <tr>
                                            <td><a href="{{ route('logout') }}" class="dropdown-item"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form'+{{$item->id}}).submit();"> {{$item->code}}</a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('datacenter.ca.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </td>
                                            <td>{{$item->asset_name}}</td>
                                            <td>{{$item->asset_category}}</td>
                                            <td>{{$item->physical_location}}</td>
                                            <td><span class="badge badge-{{$item->status->html}}">{{$item->status->name}}</span> </td>
                                            <td>{{$item->created_at->diffForHumans()}}</td>
                                            <td><a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                                   onclick="event.preventDefault();
                                                       document.getElementById('show-form'+{{$item->id}}).submit();"> View</a>
                                                <form id="show-form{{$item->id}}"
                                                      action="{{ route('datacenter.ca.show', $item->id) }}"
                                                      method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_008'))
                                    <a href="{{route('datacenter.ca.create')}}"
                                       class="btn btn-sm bg-gradient-green float-left">New Data Center Critical Asset Form</a>
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
@endsection


@push('custom-scripts')
    <!--  -->
@endpush
