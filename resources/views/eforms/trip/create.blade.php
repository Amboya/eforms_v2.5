@extends('layouts.eforms.trip.master')


@push('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">New Trip Form</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('trip.home')}}">Home</a></li>
                        <li class="breadcrumb-item active">New Trip Form</li>
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
            <div class="alert alert-danger alert-dismissible">
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


            <div class="row">
                <div class="col-8 offset-2">
    <!-- Default box -->
        <div class="card">
            <form  id="create_form" name="create_form" action="{{route('trip.store')}}" method="post">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label>Trip Name:</label>
                            <input type="text" name="name"
                                   placeholder="Enter Trip Name" required
                                   class="form-control">
                        </div>
                        <div class="col-6">
                            <label>Trip Destination:</label>
                            <input type="text" name="destination"
                                   placeholder="Enter Trip Destination" required
                                   class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label>Trip Description:</label>
                            <textarea type="text" name="description"
                                   placeholder="Enter Trip Description" required
                                      class="form-control"> </textarea>
                        </div>
                        <div class="col-3">
                            <label>Trip Date From:</label>
                            <input type="date" name="date_from" required
                                   class="form-control">
                        </div>
                        <div class="col-3">
                            <label>Trip Date To:</label>
                            <input type="date" name="date_to" required
                                   class="form-control">
                        </div>
                    </div>
                    <p><b>Trip Invitation:</b> Please Select Trip members from the following User-Units aligned to your profile </p>

                    <div class="row">
                        @foreach($units as $unit)
                            <div class="col-6">
                                <div class="card card-outline collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">{{$unit->user_unit_description}}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <input type="checkbox" id="selectAll" value="selectAll"> Select / Deselect All<br/><br/>
                                    @foreach($unit->users_list as $users)
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <!-- checkbox -->
                                                    <div class="form-group">
                                                        <div class="form-check">
                                                            <input class="form-check-input" value="{{$users}}" type="checkbox" name="users[]">
                                                            <label class="form-check-label">{{$users->staff_no}} : {{$users->name}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                            </div>
                        @endforeach

                    </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div  class="col-12 text-right">
                            <div id="submit_possible" class="col-12 text-right">
                                <div id="divSubmit_show">
                                    <input class="btn btn-lg btn-success" type="submit"
                                           value="Create" id="btnSubmit"
                                           name="submit_form">
                                </div>
                                <div id="divSubmit_hide" >
                                    <input class="btn btn-lg btn-success"
                                           value="Submitting. Please wait..." disabled
                                           name="submit_form">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-footer-->
            </form>
        </div>
                </div>
            </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection


@push('custom-scripts')
    <!--  -->

    <script>
        $(document).ready(function () {
            $("#divSubmit_hide").hide();
            //disable the submit button
            $("#btnSubmit").on('click', function () {
                $("#create_form").submit(function (e) {
                    e.preventDefault()
                    //do something here
                    $("#divSubmit_show").hide();
                    $("#divSubmit_hide").show();
                    //continue submitting
                    e.currentTarget.submit();
                });
            });
        });

        $('#selectAll').click(function() {
            if (this.checked) {
                $(':checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    </script>

    {{--    <script type="text/javascript">--}}


{{--        function getvalues() {--}}
{{--            var inps = document.getElementsByName('amount[]');--}}
{{--            var total = 0;--}}
{{--            for (var i = 0; i < inps.length; i++) {--}}
{{--                var inp = inps[i];--}}
{{--                total = total + parseFloat(inp.value || 0);--}}
{{--            }--}}

{{--            if (!isNaN(total)) {--}}

{{--                //check if Trip Form is below 2000--}}
{{--                if (total > 2000) {--}}
{{--                    $('#submit_possible').hide();--}}
{{--                    $('#submit_not_possible').show();--}}
{{--                } else if (total == 0) {--}}
{{--                    $('#submit_not_possible').hide();--}}
{{--                    $('#submit_possible').hide();--}}
{{--                } else {--}}
{{--                    $('#submit_not_possible').hide();--}}
{{--                    $('#submit_possible').show();--}}
{{--                }--}}
{{--                //set value--}}
{{--                document.getElementById('total-payment').value = total;--}}
{{--            }--}}
{{--        }--}}


{{--        // Navigation Script Starts Here--}}
{{--        $(document).ready(function () {--}}

{{--            //first hide the buttons--}}
{{--            $('#submit_possible').hide();--}}
{{--            $('#submit_not_possible').hide();--}}

{{--        });--}}

{{--    </script>--}}


    <SCRIPT language="javascript">
        function addRow(tableID) {

            var table = document.getElementById(tableID);

            var rowCount = table.rows.length;
            var row = table.insertRow(rowCount);

            var colCount = table.rows[0].cells.length;

            for (var i = 0; i < colCount; i++) {

                var newcell = row.insertCell(i);

                newcell.innerHTML = table.rows[0].cells[i].innerHTML;
                //alert(newcell.childNodes);
                switch (newcell.childNodes[0].type) {
                    case "text":
                        newcell.childNodes[0].value = "";
                        break;
                    case "checkbox":
                        newcell.childNodes[0].checked = false;
                        break;
                    case "select-one":
                        newcell.childNodes[0].selectedIndex = 0;
                        break;
                }
            }
        }

        function deleteRow(tableID) {
            try {
                var table = document.getElementById(tableID);
                var rowCount = table.rows.length;

                for (var i = 0; i < rowCount; i++) {
                    var row = table.rows[i];
                    var chkbox = row.cells[0].childNodes[0];
                    if (null != chkbox && true == chkbox.checked) {
                        if (rowCount <= 1) {
                            alert("Cannot delete all the rows.");
                            break;
                        }
                        table.deleteRow(i);
                        rowCount--;
                        i--;
                    }
                }
                getvalues();
            } catch (e) {
                alert(e);
            }
        }

    </SCRIPT>



@endpush
