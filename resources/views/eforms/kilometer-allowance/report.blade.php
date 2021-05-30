@extends('layouts.eforms.kilometer-allowance.master')


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
                    <h1 class="m-0 text-dark">Kilometer-Allowance Accounts Line List : [{{$title}}]</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Kilometer-Allowance Accounts List</li>
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
            <!-- /.card-header -->
            <div class="card-header text-right  ">
                @if ( Auth::user()->profile_id == config('constants.user_profiles.EZESCO_007') || Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014'))
                    <button class="btn btn-sm btn-success "
                       data-toggle="modal"
                       data-target="#modal-export">
                        Export {{sizeof($list)}} Records <i class="fa fa-download"></i>
                    </button>
                @endif
                @if (Auth::user()->type_id == config('constants.user_types.developer') )
                    <a class="btn btn-sm btn-default " href="{{route('kilometer.allowance.report.export.unmark.exported.all')}}"
                       title="Only Chief Account and Expenditure Officers can Export">
                        Unmark the exported Records <i class="fa fa-rev"></i>
                    </a>
                    <a class="btn btn-sm btn-default " href="#"
                       title="Only Chief Account and Expenditure Officers can Export">
                        Export {{sizeof($list)}} Records <i class="fa fa-download"></i>
                    </a>
{{--                    <a class="btn btn-sm btn-default " href="{{route('kilometer.allowance.accounts-duplicate-remove')}}"--}}
{{--                       title="Only Chief Account and Expenditure Officers can Export">--}}
{{--                        Remove Duplicates<i class="fa fa-rev"></i>--}}
{{--                    </a>--}}
                        <a  class="btn btn-tool" href="{{route('kilometer.allowance.report.sync')}}"
                            title="Sync Accounts">
                            <i class="fas fa-sync"></i></a>

                @endif
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table m-0">
                        <thead>
                        <tr>
                            <th>
                                Month
                            </th>
                            <th>Code</th>
                            <th>Claimant</th>
                            <th>Claim Date</th>
                            <th>Company</th>
                            <th>Business Unit</th>
                            <th>Cost Center</th>
                            <th>Account</th>
                            <th>Project</th>
                            <th>Intra-Company</th>
                            <th>Spare</th>
                            <th>PEMS Project</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Line Description</th>
                            @if (Auth::user()->type_id == config('constants.user_types.developer') )
                                <th>Action</th>
                                @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $list as $key=>$item )
                            <tr>
                                <td>{{$item->created_at->format('F')  ?? ""}}</td>
{{--                                <td>{{$item->kilometer_allowance->id ?? ""}}</td>--}}
                                <td>{{$item->kilometer_allowance_code ?? ""}}</td>
                                <td>{{$item->claimant_name ?? ""}}</td>
                                <td>{{$item->claim_date ?? ""}}</td>
                                <td>{{$item->company ?? ""}}</td>
                                <td>{{$item->business_unit_code ?? ""}}</td>
                                <td>{{$item->cost_center ?? ""}}</td>
                                <td>{{$item->account  ?? $item->account  ?? ""}}</td>
                                <td>{{$item->project ?? ""}}</td>
                                <td>{{$item->intra_company ?? ""}}</td>
                                <td>{{$item->spare ?? ""}}</td>
                                <td>{{$item->pems_project ?? ""}}</td>
                                <td>{{$item->debitted_amount ?? ""}}</td>
                                <td>{{$item->creditted_amount ?? ""}}</td>
                                <td>{{$item->description ?? ""}}</td>
                                @if (Auth::user()->type_id == config('constants.user_types.developer') )
                                    <td><a href="{{route('kilometer.allowance.accounts.duplicate.remove',$item->id)}}">Mark As Duplicate</a></td>
                                @endif
                                {{--                                kilometer.allowance.report-export-unmark-exported--}}
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
                {{--@if( Auth::user()->profile_id ==  config('constants.user_profiles.EZESCO_002'))--}}
                {{--@if($pending < 1)--}}
                {{--<a href="{{route('kilometer.allowance.create')}}"--}}
                {{--class="btn btn-sm bg-gradient-green float-left">New Kilometer Allowance</a>--}}
                {{--@else--}}
                {{--<a href="#" class="btn btn-sm btn-default float-left">New Kilometer Allowance</a>--}}
                {{--<span class="text-danger m-3"> Sorry, You can not raise a new kilometer allowance because you already have an open kilometer allowance.</span>--}}
                {{--@endif--}}
                {{--@endif--}}
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->


    <!-- NEW MODAL-->
    <div class="modal fade" id="modal-export">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center">Select Period To Export</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- form start -->
                <form role="form-new" method="get" action="{{route('kilometer.allowance.report.export')}} ">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Date From</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from"
                                           placeholder="Enter profile name" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="code">Date To</label>
                                    <input type="date" class="form-control" id="date_to" name="date_to"
                                           placeholder="Enter profile code">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.NEW modal -->




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
