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
                    <h1 class="m-0 text-dark text-orange text-uppercase">Petty-Cash : <span class="text-green">{{$category}}</span></h1>
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
            <form id="list_form"  action="{{route('petty.cash.approve.batch', $value)}}" method="post">
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
                            <thead>
                            <tr>
                                <th></th>
                                <th>Serial</th>
                                <th>Invoice Type</th>
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
                                            <input type="checkbox" value="{{$item->invoice_id}}" id="forms[]"
                                                   name="forms[]">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('logout') }}" class="dropdown-item"
                                           onclick="event.preventDefault();
                                               document.getElementById('show-form'+{{$item->invoice_id ?? 0 }}).submit();"> {{$item->invoice_id ?? 0 }}</a>
                                        <form id="show-form{{$item->invoice_id}}"
                                              action="{{ route('petty.cash.show', $item->invoice_id ?? 0) }}"
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </td>
                                    <td>{{$item->invoice_type}}</td>
                                    <td>{{$item->supplier_num}}</td>
                                    <td>{{$item->invoice_currency_code}} {{ number_format($item->invoice_amount , 2)}}</td>
                                    <td><span
                                            class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                    </td>
                                    <td>{{ $item->creation_date }}</td>
                                    <td><a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                           onclick="event.preventDefault();
                                               document.getElementById('show-form'+{{$item->invoice_id}}).submit();">
                                            View </a>
                                        <form id="show-form{{$item->invoice_id}}"
                                              action="{{ route('petty.cash.show', $item->invoice_id) }}"
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>

                                        @if(Auth::user()->type_id == config('constants.user_types.developer'))
                                            <button class="btn btn-sm bg-gradient-gray " style="margin: 1px"
                                                    title="Mark as Void."
                                                    data-toggle="modal"
                                                    data-target="#modal-void{{$item->invoice_id}}">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                            <button class="btn btn-sm bg-gradient-gray " style="margin: 1px"
                                                    title="Reverse Form to the previous state."
                                                    data-toggle="modal"
                                                    data-target="#modal-reverse{{$item->invoice_id}}">
                                                <i class="fa fa-redo"></i>
                                            </button>
                                            <a class="btn btn-sm bg-gradient-gray "  href="{{route('petty.cash.sync', $item->invoice_id)}}"
                                               title="Sync Application Forms">
                                                <i class="fas fa-sync"></i>
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>

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
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->



    @foreach($list as $item)
        <!-- VOID MODAL-->
        <div class="modal fade" id="modal-void{{$item->invoice_id}}">
            <div class="modal-dialog modal-md">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Mark Voucher as Void</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('petty.cash.void', ['id' => $item->invoice_id])}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-center">Are you sure you want to mark this form as void? </p>
                                    <p class="text-center">Note that you can not undo this action. </p>
                                </div>

                                <div class="col-2">
                                    <label>Reason</label>
                                </div>
                                <div class="col-10">
                                    <div class="input-group">
                                        <textarea class="form-control" rows="2" name="reason"
                                                  placeholder="Enter reason why" required>
                                        </textarea>
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
        <div class="modal fade" id="modal-reverse{{$item->invoice_id}}">
            <div class="modal-dialog modal-md">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Reverse this petty cash one step backwards</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('petty.cash.reverse', ['id' => $item->invoice_id])}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-center">Are you sure you want to reverse this application to the
                                        previous stage? </p>
                                    <p class="text-center">Note that you can not undo this action. </p>
                                </div>

                                <div class="col-2">
                                    <label>Reason</label>
                                </div>
                                <div class="col-10">
                                    <div class="input-group">
                                        <textarea class="form-control" rows="2" name="reason"
                                                  placeholder="Enter reason why" required>
                                        </textarea>
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
            $("#selectAll").click(function(){
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            });

        });
    </script>



@endpush
