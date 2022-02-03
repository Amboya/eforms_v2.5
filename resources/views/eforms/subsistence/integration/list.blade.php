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
                    <h1 class="m-0 text-dark text-orange text-uppercase">Subsistence : <span
                            class="text-green">{{$category}}</span></h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('subsistence.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">Subsistence : {{$category}}</li>
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
        @if(session()->has('error'))
            <div class="alert alert-info alert-dismissible">
                <p class="lead"> {{session()->get('error')}}</p>
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
            <form id="list_form" action="{{route('subsistence.finance.send')}}" method="post">
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
                                <th>Staff no</th>
                                <th>Credited Acc</th>
                                <th>Credited Amt</th>
                                <th>Debited Acc</th>
                                <th>Debited Amt</th>
                                <th>Vat Rate</th>
                                <th>CC : BU</th>
                                <th>Org Id</th>
                                <th>Company</th>
                                <th>Intra Company</th>
                                <th>Project</th>
                                <th>Pems Project</th>
                                <th>Spare</th>
                                <th>Description</th>
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
                                            <input type="checkbox" value="'{{$item->form->code}}'" id="forms[]"
                                                   name="forms[]">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                               document.getElementById('show-form'+{{$item->eform_petty_cash_id ?? 0 }}).submit();"> {{$item->form->code ?? 0 }}</a>
                                        <form id="show-form{{$item->invoice_id}}"
                                              action="{{ route('subsistence.show', $item->eform_petty_cash_id ?? 0) }}"
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </td>
                                    <td>{{$item->claimant_staff_no}}</td>
                                    <td>{{$item->creditted_account_id}}</td>
                                    <td>{{ number_format($item->creditted_amount , 2)}}</td>
                                    <td>{{$item->debitted_account_id}}</td>
                                    <td>{{ number_format($item->debitted_amount , 2)}}</td>

                                    <td>{{$item->vat_rate ?? 0}} </td>

                                    <td>{{$item->form->user_unit->user_unit_cc_code}} : {{$item->form->user_unit->user_unit_bc_code}}</td>
                                    <td>{{$item->form->user_unit->org_id}}</td>
                                    <td>{{$item->company}}</td>
                                    <td>{{$item->intra_company}}</td>
                                    <td>{{$item->project}}</td>
                                    <td>{{$item->pems_project}}</td>
                                    <td>{{$item->spare}}</td>
                                    <td>{{$item->description}}</td>
                                    <td><span
                                            class="badge badge-{{$item->status->html ?? "default"}}">{{$item->status->name ?? "none"}}</span>
                                    </td>
                                    <td>{{ Carbon::parse(  $item->updated_at )->isoFormat('Do MMM Y') }}</td>
                                    <td><a href="{{ route('logout') }}" class="btn btn-sm bg-orange"
                                           onclick="event.preventDefault();
                                               document.getElementById('show-form'+{{$item->eform_petty_cash_id}}).submit();">
                                            View </a>
                                        <form id="show-form{{$item->eform_petty_cash_id}}"
                                              action="{{ route('subsistence.show', $item->eform_petty_cash_id) }}"
                                              method="POST" class="d-none">
                                            @csrf
                                        </form>

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


                    {{-- IS CLOSED- SEND TO PHRIS--}}
                    @if(Auth::user()->type_id == config('constants.user_types.developer'))
                        <div class="">
                            <hr>
                            <div class="row">
                                <div class="col-2 text-center">
                                    <div id="divSubmit_show">
                                        <button id="btnSubmit_approve" type="submit" name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Approved'>SEND TO FMS INTERFACE
                                        </button>
                                        <button style="display: none" id="btnSubmit_reject" type="submit"
                                                name="approval"
                                                class="btn btn-outline-success mr-2 p-2  "
                                                value='Rejected'>SEND TO FMS INTERFACE
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
