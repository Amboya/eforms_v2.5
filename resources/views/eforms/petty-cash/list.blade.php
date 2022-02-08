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
                    <h1 class="m-0 text-dark text-orange text-uppercase">Petty-Cash : <span
                            class="text-green">{{$category}}</span></h1>
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
            <form id="list_form" action="{{route('petty.cash.approve.batch', $value)}}" method="post">
            @csrf
            <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        {{--                    <div class="row">--}}
                        {{--                        <div class="col-6 offset-6">--}}
                        {{--                        <input type="text" class="form-control m-2" id="myInput" onkeyup="myFunction()" placeholder="Search ..">--}}
                        {{--                    </div>--}}
                        {{--                    </div>--}}

                        {{--                @if(Auth::user()->type_id != config('constants.user_types.developer'))--}}
                        {{--                        <table id="myTable" class="table m-0">--}}
                        {{--                            @else--}}

                        <tr>
                            <td>
                                <input id="selectAll" type="checkbox"><label for='selectAll'>Select All</label>
                            </td>

                        </tr>
                        <table id="example1" class="table m-0">
                            {{--                                    @endif--}}
                            <thead class="table text-white text-bold text-uppercase bg-gradient-green ">
                            <tr>
                                <th></th>
                                <th>Serial</th>
                                <th>Claimant</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach( $list as $item )

                                <tr>
                                    <td>
                                        <div class="icheck-warning d-inline">
                                            <input type="checkbox" value="{{$item->id}}" id="forms[]"
                                                   name="forms[]">
                                        </div>
                                    </td>
                                    <td><a href="{{ route('logout') }}" class="dropdown-item"
                                           onclick="event.preventDefault();
                                               document.getElementById('show-form'+{{$item->id}}).submit();"> {{$item->code}}</a>
                                        <form id="show-form{{$item->id}}"
                                              action="{{ route('petty.cash.show', $item->id) }}"
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{route('main.user.show',$item->created_by)}}" class="text-dark"
                                           style="margin: 1px">
                                            {{$item->claimant_name}}
                                        </a>
                                    </td>
                                    <td>ZMW {{ number_format($item->total_payment  - $item->change, 2)}}</td>
                                    <td><span
                                            class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? $item->name ?? "-"}}</span>
                                    </td>
                                    <td>{{ $item->created_at }}</td>
                                    <td><a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                           onclick="event.preventDefault();
                                               document.getElementById('show-form'+{{$item->id}}).submit();">
                                            View </a>
                                        <form id="show-form{{$item->id}}"
                                              action="{{ route('petty.cash.show', $item->id) }}"
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>

                                        @if(Auth::user()->type_id == config('constants.user_types.developer'))
                                            <a class="btn btn-sm bg-gradient-gray " style="margin: 1px"
                                               title="Mark as Void."
                                               data-toggle="modal"
                                               data-target="#modal-void{{$item->id}}">
                                                <i class="fa fa-ban"></i>
                                            </a>
                                            <a class="btn btn-sm bg-gradient-gray " style="margin: 1px"
                                               title="Reverse Form to the previous state."
                                               data-toggle="modal"
                                               data-target="#modal-reverse{{$item->id}}">
                                                <i class="fa fa-redo"></i>
                                            </a>
                                            <a class="btn btn-sm bg-gradient-gray "
                                               href="{{route('petty.cash.sync', $item->id)}}"
                                               title="Sync Application Forms">
                                                <i class="fas fa-sync"></i>
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="bg-gray-light">
                            <tr>
                                <td><b> </b></td>
                                <td><b>Count : {{ number_format(sizeof($list ))}}</b></td>
                                <td><b></b></td>
                                <td>
                                    <b>ZMW {{number_format(($list->sum('total_payment')- ($list->sum('change'))), 2)}}</b>
                                </td>
                                <td><b></b></td>
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
                    @if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002'))
                        @if($pending < 1)
                            <a href="{{route('petty.cash.create')}}"
                               class="btn btn-sm bg-gradient-green float-left">New Petty Cash</a>
                        @else
                            <a href="#" class="btn btn-sm btn-default float-left">New Petty Cash</a>
                            <span class="text-danger m-3"> Sorry, You can not raise a new petty cash because you already have an open petty cash.</span>
                        @endif
                    @endif


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
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->



    @foreach($list as $item)
        <!-- VOID MODAL-->
        <div class="modal fade" id="modal-void{{$item->id}}">
            <div class="modal-dialog modal-md">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Mark Voucher as Void</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('petty.cash.void', ['id' => $item->id])}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text">Are you sure you want to mark this form as void? </p>
                                    <p class="text">Note that you can not undo this action. </p>
                                </div>

                                <div class="col-2">
                                    <label>Reason</label>
                                </div>
                                <div class="col-10">
                                    <div class="input-group">
                                        <textarea class="form-control" rows="2" name="reason" placeholder="Enter reason why" required></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Mark</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.VOID modal -->

        <!-- REVERSE MODAL-->
        <div class="modal fade" id="modal-reverse{{$item->id}}">
            <div class="modal-dialog modal-md">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Reverse this petty cash to a new Status</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('petty.cash.reverse', ['id' => $item->id])}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-left">Are you sure you want to reverse this application to the
                                        previous stage? </p>
                                </div>

                                <div class="input-group">
                                    <div class="col-lg-2 col-sm-12">
                                        <label>New Status</label>
                                    </div>
                                    <div class="col-lg-10 col-sm-12">
                                        <select name="new_status_name" class="form-control">
                                            <option value="">--Choose--</option>
                                            @foreach($statuses as $status)
                                                <option value="{{$status->id}}">{{$status->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <label>Reason</label>
                                </div>
                                <div class="col-10">
                                    <div class="input-group">
                                        <textarea class="form-control" rows="2" name="reason" placeholder="Enter reason why" required></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.REVERSE modal -->
    @endforeach



@endsection


@push('custom-scripts')

    <!-- DataTables -->
    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

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
