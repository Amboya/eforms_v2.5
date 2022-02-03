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
                    <h1 class="m-0 text-dark text-orange text-uppercase">Petty-Cash BY DIVISIONS : <span class="text-green">{{$category}}</span></h1>
                    <span class="text-orange text-bold">{{$date_range}}</span>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('petty.cash.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">PETTY-CASH {{$category}}</li>
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
                <!-- SEARCH FORM -->
                <form class="form-inline ml-3" method="post"
                      action="{{route('petty.cash.invoices.divisions.search')}}">
                    @csrf
                    <div class="input-group">
                        <label  >Date From </label>
                        <input class="form-control ml-2 mr-3" type="date"
                               name="date_from" placeholder="Date From" aria-label="Date From">
                    </div>
                    <div class="input-group">
                        <label >Date To </label>
                        <input class="form-control ml-2 mr-3" type="date"
                               name="date_to" placeholder="Date To" aria-label="Date To">
                    </div>
                    <div class="input-group">
                        <label >Status</label>
                        <div class="input-group input-group-sm ml-2">
                            <select class="form-control" id="status_select" name="status_select" required>
                                <option value=""> Select Status</option>
                                <option value="{{config('constants.all')}}"> {{config('constants.all')}}</option>
                                <option
                                    value="{{config('constants.money_given')}}"> {{config('constants.money_given')}}</option>
                                <option
                                    value="{{config('constants.money_pending')}}"> {{config('constants.money_pending')}}</option>
                                <option
                                    value="{{config('constants.money_queried')}}"> {{config('constants.money_queried')}}</option>
                                <option
                                    value="{{config('constants.money_rejected')}}"> {{config('constants.money_rejected')}}</option>
                                @foreach($status as $item)
                                    <option value="{{$item->id}}">  {{$item->name}} </option>
                                @endforeach
                            </select>
                            {{--                        <input class="form-control form-control-navbar" type="search"--}}
                            {{--                               name="search" placeholder="Search" aria-label="Search">--}}
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-success" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">

                    <table id="example1" class="table m-0">
                        <thead class="bg-gradient-green">
                        <tr>
                            <th>#</th>
                            <th>Directorate</th>
                            <th>Total Invoices</th>
                            <th>Amount Paid</th>
                            <th>Change Returned</th>
                            <th>Net Amount</th>
                            <th>Status</th>
                            <td>Period</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $directorates as $dir )
                            <tr>
                                <td>{{++$total_num}}</td>
                                <td>{{$dir->name ?? "none"}}</td>
                                <td>
                                    @foreach( $list->where('division_id', $dir->id) as $item )
                                        {{$item->total ?? 0}}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach( $list->where('division_id', $dir->id) as $item )
                                        {{ number_format($item->amount ?? 0 , 2 ) }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach( $list->where('division_id', $dir->id) as $item )
                                        {{ number_format($item->change ?? 0 , 2 ) }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach( $list->where('division_id', $dir->id) as $item )
                                        {{ number_format($item->net ?? 0 , 2 ) }}
{{--                                        @money($item->net ?? 0)--}}
                                    @endforeach
                                </td>
                                <td><span
                                        class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? $category }}</span>
                                </td>
                                <td>
                                    {{$date_range}}
                                </td>

                            </tr>

                        @endforeach
                        </tbody>
                        <tfoot class="bg-gradient-orange">
                        <tr>
                            <td><b>{{$total_num}}</b></td>
                            <td><b></b></td>
                            <td><b>{{$list->sum('total')}}</b></td>
                            <td><b> {{$list->sum('amount') }} </b></td>
                            <td><b> {{$list->sum('change')}}</b></td>
                            <td><b> @money($list->sum('net'))</b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                        </tr>
                        </tfoot>

                    </table>
                    @if(Auth::user()->type_id != config('constants.user_types.developer'))
                        {{--                            {!! $list->links() !!}--}}
                    @else

                    @endif
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">

                {{--  HAS RECEIPT - SEND TO AUDIT --}}
                @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_007')
                     &&  $value == config('constants.petty_cash_status.receipt_approved')
                    )
                    <div class="">
                        <hr>
                        <div class="row">
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-1">
                                        <label class="form-control-label">Reason/Comment</label>
                                    </div>
                                    <div class="col-11">
                                        <textarea class="form-control" rows="2" name="reason" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center ">
                                <div id="divSubmit_show">
                                    <button id="btnSubmit_approve" type="submit" name="approval"
                                            class="btn btn-outline-success mr-2 p-2  "
                                            value='Resolve'>SEND TO AUDIT
                                    </button>
                                    <button style="display: none" id="btnSubmit_reject" type="submit"
                                            name="approval"
                                            class="btn btn-outline-success mr-2 p-2  "
                                            value='Rejected'>SEND TO AUDIT1
                                    </button>
                                </div>
                                <div id="divSubmit_hide">
                                    <button disabled class="btn btn-outline-success mr-2 p-2  "
                                            value='Approved'>Processing. Please wait...
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                @endif

            </div>

        </div>
        <!-- /.card -->

        <!-- BAR CHART -->
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Directorates Chart</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart"
                            style="min-height: 750px; height: 750px; max-height: 750px; max-width: 100%;"></canvas>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->



@endsection


@push('custom-scripts')

    <!-- DataTables -->
    {{--    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>--}}
    {{--    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>--}}
    {{--    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>--}}
    {{--    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>--}}

    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

    <!-- page script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

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
        function myFunction() {
            // Declare variables

            var input, filter, table, tr, td, th, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr"),
                th = table.getElementsByTagName("th");

            // Loop through all table rows, and hide those who don't match the        search query
            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                for (var j = 0; j < th.length; j++) {
                    td = tr[i].getElementsByTagName("td")[j];
                    if (td) {
                        if (td.innerHTML.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#divSubmit_hide").hide();
            //disable the submit button
            $("#btnSubmit_approve").on('click', function () {
                $("#show_form").submit(function (e) {
                    //  e.preventDefault()
                    //do something here
                    $("#divSubmit_show").hide();
                    $("#divSubmit_hide").show();
                    //continue submitting
                    e.currentTarget.submit();
                });
            });


            //select all
            $("#selectAll").click(function () {
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            });

        });
    </script>


    <!-- Page specific script -->
    <script>
        $(function () {
            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */

            var directorates = {!! json_encode($direc) !!};
            var areaChartData = {
                labels: ['2021'],
                datasets: [
                    {
                        label: 'COMMERCIAL AND CUSTOMER SERVICES DIRECTORATE',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: directorates['COMMERCIAL AND CUSTOMER SERVICES DIRECTORATE']
                    },
                    {
                        label: 'DISTRIBUTION DIRECTORATE',
                        backgroundColor: 'rgb(112,222,148)',
                        borderColor: 'rgba(112,222,148)',
                        pointRadius: false,
                        pointColor: 'rgba(112,222,148)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(112,222,148)',
                        data: directorates['DISTRIBUTION DIRECTORATE']
                    },
                    {
                        label: 'FINANCE DIRECTORATE',
                        backgroundColor: 'rgb(164,184,224)',
                        borderColor: 'rgb(164,184,224)',
                        pointRadius: false,
                        pointColor: 'rgba(164,184,224)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(164,184,224)',
                        data: directorates['FINANCE DIRECTORATE']
                    },
                    {
                        label: 'GENERATION DIRECTORATE',
                        backgroundColor: 'rgb(236,186,149)',
                        borderColor: 'rgba(236,186,149)',
                        pointRadius: false,
                        pointColor: 'rgba(236,186,149)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(236,186,149)',
                        data: directorates['GENERATION DIRECTORATE']
                    },
                    {
                        label: 'HUMAN RESOURCES DIRECTORATE',
                        backgroundColor: 'rgb(165,116,217)',
                        borderColor: 'rgba(165,116,217)',
                        pointRadius: false,
                        pointColor: 'rgba(165,116,217)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(165,116,217)',
                        data: directorates['HUMAN RESOURCES DIRECTORATE']
                    },
                    {
                        label: 'LEGAL SERVICES DIRECTORATE',
                        backgroundColor: 'rgb(220,205,79)',
                        borderColor: 'rgba(220,205,79)',
                        pointRadius: false,
                        pointColor: 'rgba(220,205,79)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,205,79)',
                        data: directorates['LEGAL SERVICES DIRECTORATE']
                    },
                    {
                        label: 'MANAGING DIRECTOR',
                        backgroundColor: 'rgb(122,219,187)',
                        borderColor: 'rgba(122,219,187)',
                        pointRadius: false,
                        pointColor: 'rgba(122,219,187)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(122,219,187)',
                        data: directorates['MANAGING DIRECTOR']
                    },
                    {
                        label: 'STRATEGY & CORPORATE SERVICES DIRECTORATE',
                        backgroundColor: 'rgb(239,146,80)',
                        borderColor: 'rgba(239,146,80)',
                        pointRadius: false,
                        pointColor: 'rgba(239,146,80)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(239,146,80)',
                        data: directorates['STRATEGY & CORPORATE SERVICES DIRECTORATE']
                    },
                    {
                        label: 'TRANSMISSION DIRECTORATE',
                        backgroundColor: 'rgb(210,216,222)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: false,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data: directorates['TRANSMISSION DIRECTORATE']
                    },
                ]
            }


            //-------------
            //- BAR CHART -
            //-------------
            var barChartCanvas = $('#barChart').get(0).getContext('2d')
            var barChartData = $.extend(true, {}, areaChartData)
            var temp0 = areaChartData.datasets[0]
            var temp1 = areaChartData.datasets[1]
            barChartData.datasets[0] = temp1
            barChartData.datasets[1] = temp0

            var barChartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                datasetFill: false
            }

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: barChartOptions
            })


        })
    </script>



@endpush
