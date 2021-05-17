@extends('layouts.eforms.petty-cash.master')


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
                @foreach($directorates_closed as $directorate)
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <a class="info-box-icon bg-gray elevation-1"
                               href="{{route( 'petty-cash-list', config('constants.petty_cash_status.rejected'))}}">
                                <span><i class="fa fa-file"></i></span>
                            </a>
                            <div class="info-box-content">
                                <span class="info-box-text"> {{$directorate->directorate->name ?? ""}}</span>
                                <span class="info-box-text"> ({{$directorate->status->name ?? ""}})</span>
                                <p>Total : <b>{{ $directorate->total ?? '0' }}</b> <br>
                                    Amount : <b>ZMW {{ $directorate->amount ?? '0' }}</b></p>

                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
            @endforeach


            {{--                @foreach($directorates as $directorate)--}}
            {{--                    <div class="col-12 col-sm-6 col-md-3">--}}
            {{--                        <div class="info-box mb-3">--}}
            {{--                            <a class="info-box-icon bg-gray elevation-1"--}}
            {{--                               href="{{route( 'petty-cash-list', config('constants.petty_cash_status.rejected'))}}">--}}
            {{--                                <span><i class="fa fa-file"></i></span>--}}
            {{--                            </a>--}}
            {{--                            <div class="info-box-content">--}}
            {{--                                <span class="info-box-text"> {{$directorate->directorate->name ?? ""}}</span>--}}
            {{--                                <span class="info-box-text"> ({{$directorate->status->name ?? ""}})</span>--}}
            {{--                                <p>Total : <b>{{ $directorate->total ?? '0' }}</b> <br>--}}
            {{--                                        Amount : <b>ZMW {{ $directorate->amount ?? '0' }}</b> </p>--}}

            {{--                            </div>--}}
            {{--                            <!-- /.info-box-content -->--}}
            {{--                        </div>--}}
            {{--                        <!-- /.info-box -->--}}
            {{--                    </div>--}}
            {{--            @endforeach--}}

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
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                        class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">

                            <table class="graph">
                                <caption>Bar Chart HTML From HTML Table</caption>
                                <thead>
                                <tr>
                                    <th scope="col">Item</th>
                                    <th scope="col">Percent</th>
                                </tr>
                                </thead><tbody>
                                <tr style="height:845%">
                                    <th scope="row">Your Blog</th>
                                    <td><span>85%</span></td>
                                </tr>
                                <tr style="height:223%">
                                    <th scope="row">Medium</th>
                                    <td><span>23%</span></td>
                                </tr>
                                <tr style="height:7%">
                                    <th scope="row">Tumblr</th>
                                    <td><span>7%</span></td>
                                </tr>
                                <tr style="height:38%">
                                    <th scope="row">Facebook</th>
                                    <td><span>38%</span></td>
                                </tr>
                                <tr style="height:35%">
                                    <th scope="row">Youtube</th>
                                    <td><span>35%</span></td>
                                </tr>
                                <tr style="height:30%">
                                    <th scope="row">LinkedIn</th>
                                    <td><span>30%</span></td>
                                </tr>
                                <tr style="height:5%">
                                    <th scope="row">Twitter</th>
                                    <td><span>5%</span></td>
                                </tr>
                                <tr style="height:20%">
                                    <th scope="row">Other</th>
                                    <td><span>20%</span></td>
                                </tr>
                                </tbody>
                            </table>



                            <div class="chart">
                                <canvas id="myChart" width="400" height="400"></canvas>
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

            <script src=" {{ asset('chart_js_package/dist/chart.js')}}"></script>

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
                {{--$(function () {--}}
                {{--    /* ChartJS--}}
                {{--     * ---------}}
                {{--     * Here we will create a few charts using ChartJS--}}
                {{--     */--}}

                {{--    directs  =   {!! json_encode($units) !!};--}}
                {{--    amounts  =   {!! json_encode($status_amount) !!};--}}
                {{--    totals  =   {!! json_encode($status_total) !!};--}}

                {{--    console.log(directs);--}}

                {{--   // alert(12313);--}}

                {{--    var areaChartData = {--}}
                {{--        labels  : directs,--}}
                {{--        datasets: [--}}
                {{--            {--}}
                {{--                label               : 'Closed',--}}
                {{--                backgroundColor     : 'rgba(60,141,188,0.9)',--}}
                {{--                borderColor         : 'rgba(60,141,188,0.8)',--}}
                {{--                pointRadius          : false,--}}
                {{--                pointColor          : '#3b8bba',--}}
                {{--                pointStrokeColor    : 'rgba(60,141,188,1)',--}}
                {{--                pointHighlightFill  : '#fff',--}}
                {{--                pointHighlightStroke: 'rgba(60,141,188,1)',--}}
                {{--                data                : amounts--}}
                {{--            },--}}
                {{--            {--}}
                {{--                label               : 'Opened',--}}
                {{--                backgroundColor     : 'rgba(210, 214, 222, 1)',--}}
                {{--                borderColor         : 'rgba(210, 214, 222, 1)',--}}
                {{--                pointRadius         : false,--}}
                {{--                pointColor          : 'rgba(210, 214, 222, 1)',--}}
                {{--                pointStrokeColor    : '#c1c7d1',--}}
                {{--                pointHighlightFill  : '#fff',--}}
                {{--                pointHighlightStroke: 'rgba(220,220,220,1)',--}}
                {{--                data                : amounts--}}
                {{--            },--}}
                {{--            {--}}
                {{--                label               : 'New',--}}
                {{--                backgroundColor     : 'rgba(210, 214, 222, 1)',--}}
                {{--                borderColor         : 'rgba(210, 214, 222, 1)',--}}
                {{--                pointRadius         : false,--}}
                {{--                pointColor          : 'rgba(210, 214, 222, 1)',--}}
                {{--                pointStrokeColor    : '#c1c7d1',--}}
                {{--                pointHighlightFill  : '#fff',--}}
                {{--                pointHighlightStroke: 'rgba(220,220,220,1)',--}}
                {{--                data                : amounts--}}
                {{--            },--}}
                {{--            {--}}
                {{--                label               : 'Rejected',--}}
                {{--                backgroundColor     : 'rgba(210, 214, 222, 1)',--}}
                {{--                borderColor         : 'rgba(210, 214, 222, 1)',--}}
                {{--                pointRadius         : false,--}}
                {{--                pointColor          : 'rgba(210, 214, 222, 1)',--}}
                {{--                pointStrokeColor    : '#c1c7d1',--}}
                {{--                pointHighlightFill  : '#fff',--}}
                {{--                pointHighlightStroke: 'rgba(220,220,220,1)',--}}
                {{--                data                : amounts--}}
                {{--            },--}}
                {{--        ]--}}
                {{--    }--}}


                {{--    //---------------}}
                {{--    //- BAR CHART ---}}
                {{--    //---------------}}
                {{--    var barChartCanvas = $('#barChart').get(0).getContext('2d')--}}
                {{--    var barChartData = jQuery.extend(true, {}, areaChartData)--}}
                {{--    var temp0 = areaChartData.datasets[0]--}}
                {{--    var temp1 = areaChartData.datasets[1]--}}
                {{--    barChartData.datasets[0] = temp1--}}
                {{--    barChartData.datasets[1] = temp0--}}

                {{--    var barChartOptions = {--}}
                {{--        responsive              : true,--}}
                {{--        maintainAspectRatio     : false,--}}
                {{--        datasetFill             : false--}}
                {{--    }--}}

                {{--    var barChart = new Chart(barChartCanvas, {--}}
                {{--        type: 'bar',--}}
                {{--        data: barChartData,--}}
                {{--        options: barChartOptions--}}
                {{--    })--}}


                {{--})--}}


            </script>

            <script>
                var ctx = document.getElementById('myChart').getContext('2d');

                directs = {!! json_encode($units) !!};
                amounts = {!! json_encode($status_amount) !!};
                totals = {!! json_encode($status_total) !!};
                status = {!! json_encode($status) !!};

                console.log(status);

                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: status,
                        datasets: [{
                            label: '# of Votes',
                            data: totals ,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>


    @endpush
