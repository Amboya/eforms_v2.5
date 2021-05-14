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
                    <h1 class="m-0 text-dark">Petty-Cash Accounts List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('main-home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Petty-Cash Accounts List</li>
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
                <a class="btn btn-sm btn-success " href="{{route('petty-cash-report-export')}}">
                    Export {{sizeof($list)}} Records <i class="fa fa-download"></i>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table m-0">
                        <thead>
                        <tr>
                            <th>#</th>
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
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $list as $key=>$item )
                            <tr>
                                <td>{{++$key}}</td>
                                <td>{{$item->petty_cash->code}}</td>
                                <td>{{$item->petty_cash->claimant_name}}</td>
                                <td>{{$item->petty_cash->claim_date}}</td>
                                <td>{{$item->company}}</td>
                                <td>{{$item->petty_cash->business_unit_code}}</td>
                                <td>{{$item->petty_cash->cost_center}}</td>
                                <td>{{$item->creditted_account_id  ?? $item->creditted_account_id }}</td>
                                <td>{{$item->project}}</td>
                                <td>{{$item->intra_company}}</td>
                                <td>{{$item->spare}}</td>
                                <td>{{$item->pems_project}}</td>
                                <td>{{$item->debitted_amount}}</td>
                                <td>{{$item->creditted_amount}}</td>
                                <td>{{$item->description}}</td>
                                {{--<td><span class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>--}}
                                {{--</td>--}}
                                {{--<td>{{$item->created_at->diffForHumans()}}</td>--}}
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
                        {{--<a href="{{route('petty-cash-create')}}"--}}
                           {{--class="btn btn-sm bg-gradient-green float-left">New Petty Cash</a>--}}
                    {{--@else--}}
                        {{--<a href="#" class="btn btn-sm btn-default float-left">New Petty Cash</a>--}}
                        {{--<span class="text-danger m-3"> Sorry, You can not raise a new petty cash because you already have an open petty cash.</span>--}}
                    {{--@endif--}}
                {{--@endif--}}
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->


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
