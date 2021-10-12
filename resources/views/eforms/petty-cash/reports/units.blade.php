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
                    <h1 class="m-0 text-dark text-orange text-uppercase">Petty-Cash BY UNITS : <span class="text-green">{{$category}}</span></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('petty.cash.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Petty-Cash : {{$category}}</li>
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
                <form class="form-inline ml-3" method="post" action="{{route('petty.cash.invoices.units.search')}}">
                    @csrf
                    <div class="input-group input-group-sm">
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
                </form>
            </div>

            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    {{--                        <tr>--}}
                    {{--                            <td>--}}
                    {{--                                <input id="selectAll" type="checkbox"><label for='selectAll'>Select All</label>--}}
                    {{--                            </td>--}}
                    {{--                        </tr>--}}
                    <table id="example1" class="table m-0">
                        {{--                                    @endif--}}
                        <thead>
                        <tr>
                            {{--                                <th></th>--}}
                            <th>#</th>
                            <th>User-Unit</th>
                            <th>Total Invoices</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>


                        @foreach( $list as $item )
                            <tr>
                                <td>{{++$total_num}}</td>
                                <td>{{$item->user_unit->user_unit_description}}</td>
                                <td>
                                    {{$item->total}}
                                </td>
                                <td>    {{ number_format($item->amount , 2)}}
                                </td>
                                <td><span
                                        class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? $category}}</span>
                                </td>
                            </tr>
                        @endforeach

                        {{--                        @foreach( $units as $unit )--}}
                        {{--                            <tr>--}}
                        {{--                                <td>{{++$total_num}}</td>--}}
                        {{--                                <td>{{$unit->user_unit_description}}</td>--}}
                        {{--                                <td>--}}
                        {{--                                    @foreach( $list->where('user_unit_code', $unit->user_unit_code) as $item )--}}
                        {{--                                        {{$item->total}}--}}
                        {{--                                    @endforeach--}}
                        {{--                                </td>--}}
                        {{--                                <td> @foreach( $list->where('user_unit_code', $unit->user_unit_code) as $item )--}}
                        {{--                                        {{ number_format($item->amount , 2)}}--}}
                        {{--                                    @endforeach--}}
                        {{--                                </td>--}}
                        {{--                                <td>--}}
                        {{--                                    @foreach( $list->where('user_unit_code', $unit->user_unit_code) as $item )<span--}}
                        {{--                                        class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? $category}}</span>--}}
                        {{--                                    @endforeach--}}
                        {{--                                </td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endforeach--}}
                        </tbody>
                        <tfoot class="bg-gradient-green">
                        <tr>
                            <td><b>{{$total_num}}</b></td>
                            <td><b></b></td>
                            <td><b>{{number_format($list->sum('total'), 2)}}</b></td>
                            <td><b>{{ number_format($list->sum('amount') , 2)}}</b></td>
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
                {{--                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002'))--}}
                {{--                        @if($pending < 1)--}}
                {{--                            <a href="{{route('petty.cash.create')}}"--}}
                {{--                               class="btn btn-sm bg-gradient-green float-left">New Petty Cash</a>--}}
                {{--                        @else--}}
                {{--                            <a href="#" class="btn btn-sm btn-default float-left">New Petty Cash</a>--}}
                {{--                            <span class="text-danger m-3"> Sorry, You can not raise a new petty cash because you already have an open petty cash.</span>--}}
                {{--                        @endif--}}
                {{--                    @endif--}}


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
    <script src=".{{ asset('dashboard/plugins/jszip/jszip.min.js')}}"></script>
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



@endpush
