@extends('layouts.eforms.hotel-accommodation.master')


@push('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet"
          href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
@endpush


@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Directorates Petty-Cash Totals</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Directorates Petty-Cash Totals</li>
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

        <div class="container-fluid">
            <h5>All Forms</h5>
            <!-- Info boxes -->
            <div class="row">
                @foreach($directorates as $directorate)
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <a class="info-box-icon bg-gray elevation-1"
                               href="{{route( 'petty-cash-list', config('constants.petty_cash_status.rejected'))}}">
                                <span><i class="fa fa-file"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text"> {{$directorate->myDirectorate->name}}</span>
                                @foreach( $directorates_closed_totals as $key=>$item )
                                    @if($item->column_one_value == $directorate->column_one_value )
                                        <p>Total : <b>{{ $item->total_one_value ?? '0' }}</b> <br>
                                        Amount : <b>{{ $item->total_two_value ?? '0' }}</b> </p>
                                    @endif
                                @endforeach
{{--                                @foreach( $total_approved as $key=>$item )--}}
{{--                                    @if($item->directorate_id == $directorate->id )--}}
{{--                                        <p>Approved : <b>{{ $item->total ?? '0' }}</b> </p>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                @foreach( $total_new as $key=>$item )--}}
{{--                                    @if($item->directorate_id == $directorate->id )--}}
{{--                                        <p>New : <b>{{ $item->total ?? '0' }}</b> </p>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                @foreach( $total_open as $key=>$item )--}}
{{--                                    @if($item->directorate_id == $directorate->id )--}}
{{--                                        <p>Opened : <b>{{ $item->total ?? '0' }}</b> </p>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                @foreach( $total_rejected as $key=>$item )--}}
{{--                                    @if($item->directorate_id == $directorate->id )--}}
{{--                                        <p>Rejected : <b>{{ $item->total ?? '0' }}</b> </p>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                @foreach( $total_cancelled as $key=>$item )--}}
{{--                                    @if($item->directorate_id == $directorate->id )--}}
{{--                                        <p>Cancelled : <b>{{ $item->total ?? '0' }}</b> </p>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                @foreach( $total_void as $key=>$item )--}}
{{--                                    @if($item->directorate_id == $directorate->id )--}}
{{--                                        <p>Void : <b>{{ $item->total ?? '0' }}</b> </p>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
            @endforeach

            <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <!-- BAR CHART -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Bar Chart</h3>

                            <div class="card-tools">
                                <a href="{{route('petty-cash-reports-sync-directorates')}}" class="btn btn-tool">
                                    <i class="fas fa-sync"> Directorates</i>
                                </a>
                                <a href="{{route('petty-cash-reports-sync-units')}}" class="btn btn-tool">
                                    <i class="fas fa-sync">Units</i>
                                </a>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>

        </div>


    @endsection


    @push('custom-scripts')

        <!-- DataTables -->
            <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
            <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
            <script
                src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
            <script
                src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

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
                $(function () {
                    /* ChartJS
                     * -------
                     * Here we will create a few charts using ChartJS
                     */

                    directs  =   {!! json_encode($directs) !!};

                    var areaChartData = {
                        labels  : directs,
                        datasets: [
                            {
                                label               : 'Closed',
                                backgroundColor     : 'rgba(60,141,188,0.9)',
                                borderColor         : 'rgba(60,141,188,0.8)',
                                pointRadius          : false,
                                pointColor          : '#3b8bba',
                                pointStrokeColor    : 'rgba(60,141,188,1)',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(60,141,188,1)',
                                data                : [28, 48, 40, 19, 86, 27, 90]
                            },
                            {
                                label               : 'Opened',
                                backgroundColor     : 'rgba(210, 214, 222, 1)',
                                borderColor         : 'rgba(210, 214, 222, 1)',
                                pointRadius         : false,
                                pointColor          : 'rgba(210, 214, 222, 1)',
                                pointStrokeColor    : '#c1c7d1',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(220,220,220,1)',
                                data                : [65, 59, 80, 81, 56, 55, 40]
                            },
                            {
                                label               : 'New',
                                backgroundColor     : 'rgba(210, 214, 222, 1)',
                                borderColor         : 'rgba(210, 214, 222, 1)',
                                pointRadius         : false,
                                pointColor          : 'rgba(210, 214, 222, 1)',
                                pointStrokeColor    : '#c1c7d1',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(220,220,220,1)',
                                data                : [65, 59, 80, 81, 56, 55, 40]
                            },
                            {
                                label               : 'Rejected',
                                backgroundColor     : 'rgba(210, 214, 222, 1)',
                                borderColor         : 'rgba(210, 214, 222, 1)',
                                pointRadius         : false,
                                pointColor          : 'rgba(210, 214, 222, 1)',
                                pointStrokeColor    : '#c1c7d1',
                                pointHighlightFill  : '#fff',
                                pointHighlightStroke: 'rgba(220,220,220,1)',
                                data                : [65, 59, 80, 81, 56, 55, 40]
                            },
                        ]
                    }


                    //-------------
                    //- BAR CHART -
                    //-------------
                    var barChartCanvas = $('#barChart').get(0).getContext('2d')
                    var barChartData = jQuery.extend(true, {}, areaChartData)
                    var temp0 = areaChartData.datasets[0]
                    var temp1 = areaChartData.datasets[1]
                    barChartData.datasets[0] = temp1
                    barChartData.datasets[1] = temp0

                    var barChartOptions = {
                        responsive              : true,
                        maintainAspectRatio     : false,
                        datasetFill             : false
                    }

                    var barChart = new Chart(barChartCanvas, {
                        type: 'bar',
                        data: barChartData,
                        options: barChartOptions
                    })


                })
            </script>


    @endpush
