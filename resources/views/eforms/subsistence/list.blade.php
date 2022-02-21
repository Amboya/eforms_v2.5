@extends('layouts.eforms.subsistence.master')


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
                    <h1 class="m-0 text-dark text-orange text-uppercase">SUBSISTENCE <span class="text-green">{{ $category }}</span> </h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('subsistence.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">SUBSISTENCE : {{$category}}</li>
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

        <form id="list_form" action="{{route('subsistence.approve.batch', 1)}}" method="post">
        @csrf
        <!-- Default box -->
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Claimant</th>
                                <th>No. Days</th>
                                <th>Allowance / Night</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Time Period</th>
                                <th>View</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $list as $item )
                                <tr>
                                    <td><a href="{{ route('logout') }}" class=""
                                           onclick="event.preventDefault();
                                               document.getElementById('show-form'+{{$item->id}}).submit();"> {{$item->code}}</a>
                                        <form id="show-form{{$item->id}}"
                                              action="{{ route('subsistence.show', $item->id) }}"
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </td>
                                    <td>{{$item->claimant_name}}</td>
                                    <td>{{$item->numdays}}</td>
                                    <td>{{$item->absc_allowance_per_night}}</td>
                                    <td>ZMW {{number_format($item->net_amount_paid,2)}}</td>

                                    <td><span
                                            class="badge badge-{{$item->status->html}}">{{$item->status->name}}</span>
                                    </td>
                                    <td>{{$item->created_at->diffForHumans()}}</td>
                                    <td>
                                        <a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                           onclick="event.preventDefault();
                                               document.getElementById('show-form'+{{$item->id}}).submit();"> view</a>
                                        <form id="show-form{{$item->id}}"
                                              action="{{ route('subsistence.show', $item->id) }}"
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
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot class="bg-gray-light">
                            <tr>
                                <td><b>Count : {{ number_format(sizeof($list ))}}</b></td>
                                <td><b></b></td>
                                <td><b></b></td>
                                <td><b></b></td>
                                <td><b>ZMW {{number_format(($list->sum('net_amount_paid')), 2)}}</b></td>
                                <td><b></b></td>
                                <td><b></b></td>
                                <td><b></b></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

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
                                          action="{{route('subsistence.void', ['id' => $item->id])}}">
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
                                        <h4 class="modal-title text-center">Reverse this subsistence to a new Status</h4>
                                    </div>
                                    <!-- form start -->
                                    <form role="form" method="post"
                                          action="{{route('subsistence.reverse', ['id' => $item->id])}}">
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
                                                                <option value="{{$status->id ?? 0 }}">{{$status->eform->name ?? ""}}:{{$status->name ?? ""}}</option>
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
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
        </form>
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
                          action="{{route('subsistence.void', ['id' => $item->id])}}">
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
        <div class="modal fade" id="modal-reverse{{$item->id}}">
            <div class="modal-dialog modal-md">
                <div class="modal-content bg-defualt">
                    <div class="modal-header">
                        <h4 class="modal-title text-center">Reverse this subsistence one step backwards</h4>
                    </div>
                    <!-- form start -->
                    <form role="form" method="post"
                          action="{{route('subsistence.reverse', ['id' => $item->id])}}">
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


@endpush
