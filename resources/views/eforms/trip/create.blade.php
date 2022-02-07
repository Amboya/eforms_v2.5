@extends('layouts.eforms.trip.master')


@push('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }


        #create_form {
            background-color: #ffffff;
            margin: 100px auto;
           /* font-family: Raleway; */
            padding: 40px;
            width: 70%;
            min-width: 300px;
        }

        h1 {
            /*text-align: center;*/
        }

        input {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            /*font-family: Raleway;*/
            border: 1px solid #aaaaaa;
        }

        select {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            /*font-family: Raleway;*/
            border: 1px solid #aaaaaa;
        }

        textarea {
            padding: 10px;
            width: 100%;
            font-size: 17px;
            /*font-family: Raleway;*/
            border: 1px solid #aaaaaa;
        }

        /* Mark input boxes that gets an error on validation: */
        input.invalid {
            background-color: #ffdddd;
        }

        select.invalid {
            background-color: #ffdddd;
        }

        textarea.invalid {
            background-color: #ffdddd;
        }


        /* Hide all steps by default: */
        .tab {
            display: none;
        }

        .tab_create {
            display: none;
        }

        button {
            background-color: #04AA6D;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            /*font-family: Raleway;*/
            cursor: pointer;
        }

        button:hover {
            opacity: 0.8;
        }

        #prevBtn {
            background-color: #bbbbbb;
        }

        /* Make circles that indicate the steps of the form: */
        .step {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish {
            background-color: #04AA6D;
        }
    </style>
@endpush


@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark text-green"> Trip Form</h1>
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

        <div>
            <form id="create_form" name="create_form" action="{{route('trip.store')}}" enctype="multipart/form-data"
                  method="post">
                @csrf
                <h1>New Trip:</h1>
                <br>
                <!-- One "tab" for each step in the form: -->
                <div class="tab">Trip Name:
                    <p>
                        <label class="mt-3 text-green">Trip Name:</label>
                        <input type="text" name="name"
                               placeholder="Enter Trip Name" required
                               oninput="this.className = ''">
                    </p>
                    <p>
                        <label class="mt-3 text-green">Trip Description:</label>
                        <input type="text" name="description"
                               placeholder="Enter Trip Description" required
                               oninput="this.className = ''">
                    </p>
                </div>
                <!-- One "tab" for each step in the form: -->
                <div class="tab">Time Period:
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <p>
                                <label class="mt-3 text-green">Trip Date From:</label>
                                <input type="date" name="date_from" min="{{ date("Y-m-d")}}" required
                                       oninput="this.className = ''">
                            </p>
                        </div>
                        <div class="col-6">
                            <p>
                                <label class="mt-3 text-green">Trip Date To:</label>
                                <input type="date" name="date_to" min="{{ date("Y-m-d")}}" required
                                       oninput="this.className = ''">
                            </p>
                        </div>
                    </div>
                </div>


                <div class="tab">Destination:
                    <br>
                    <p>
                    <div class="row">
                        <div class="col-8">
                            <input oninput="this.className = ''" type="text" name="destination"
                                   placeholder="..Enter Destination name/s">
                            <br>
                        </div>
                        <div class="col-12">
                            <label class="mt-3 text-orange">Destination User Unit:</label>
                            <div class="mt-1">
                                <div class="card card-outline collapsed-card">
                                    <div class="card-header">
                                        <h3 class="card-title">Select Destination User Units</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool"
                                                    data-card-widget="collapse">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <!-- /.card-tools -->
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="col-12">
                                            <input class="mb-2" id="myInput" type="text"
                                                   placeholder="Search..">
                                        </div>
                                        <div class="col-12">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>BU</th>
                                                    <th>CC</th>
                                                </tr>
                                                </thead>
                                                <tbody id="myTable">
                                                @foreach($destination_units as $item)
                                                    <tr>
                                                        <td>
                                                            <div class="form-group clearfix">
                                                                <div class="icheck-warning d-inline">
                                                                    <input type="checkbox"
                                                                           value="{{$item->user_unit_code}}"
                                                                           id="destination_units[]"
                                                                           name="destination_units[]">

                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span for="accounts"> <span
                                                                    class="text-gray">{{$item->user_unit_code}}</span>  </span>
                                                        </td>
                                                        <td><span for="accounts"> <span
                                                                    class="text-gray">{{$item->user_unit_description}}</span>  </span>
                                                        </td>
                                                        <td><span for="accounts"> <span
                                                                    class="text-gray">{{$item->user_unit_bc_code}}</span>  </span>
                                                        </td>
                                                        <td><span for="accounts"> <span
                                                                    class="text-gray">{{$item->user_unit_cc_code}}</span> </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <div class="pagination-sm">
                                                {{--                                            {!! $user_units->links() !!}--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>

                    </p>
                </div>
                <div class="tab">Invite Members:
                    <p>
                    <div class="row">
                            <div class="col-12">
                                <label class="mt-3 text-orange">Users:</label>
                                <div class="mt-1">
                                    <div class="card card-outline collapsed-card">
                                        <div class="card-header">
                                            <h3 class="card-title">Select Users</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool"
                                                        data-card-widget="collapse">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                            <!-- /.card-tools -->
                                        </div>
                                        <!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="col-12">
                                                <input class="mb-2"  id="myInputUsers" type="text"
                                                       placeholder="Search..">
                                            </div>
                                            <div class="col-12">
                                                <table class="table table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Man No</th>
                                                        <th>Name</th>
                                                        <th>Job Title</th>
                                                        <th>Unit</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="myTableUsers">
                                                    @foreach($users as $user)
                                                        <tr>
                                                            <td>
                                                                <div class="form-group clearfix">
                                                                    <div class="icheck-warning d-inline">
                                                                        <input type="checkbox"
                                                                               value="{{$user}}"
                                                                               id="users[]" name="users[]">

                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><span for="accounts"> <span
                                                                        class="text-gray">{{$user->staff_no}}</span>  </span>
                                                            </td>
                                                            <td><span for="accounts"> <span
                                                                        class="text-gray">{{$user->name}}</span>  </span>
                                                            </td>
                                                            <td><span for="accounts"> <span
                                                                        class="text-gray">{{$user->job_code}}</span>  </span>
                                                            </td>
                                                            <td><span for="accounts"> <span
                                                                        class="text-gray">{{$user->id}}</span> </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="pagination-sm">
                                                    {{--                                            {!! $user_units->links() !!}--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                </div>
                            </div>
                    </div>
                    </p>
                </div>

                <div style="overflow:auto;">
                    <div style="float:right;">
                        <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                        <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                    </div>
                </div>
                <!-- Circles which indicates the steps of the form: -->
                <div style="text-align:center;margin-top:40px;">
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span hidden class="step"></span>
                </div>
            </form>
        </div>


        <!-- /.card -->
    </section>
    <!-- /.content -->





@endsection


@push('custom-scripts')

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

        $('#selectAll').click(function () {
            if (this.checked) {
                $(':checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function () {
                    this.checked = false;
                });
            }
        });
    </script>

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

    <script>
        $(document).ready(function () {
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            $("#myInputUsers").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTableUsers tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });


        });
    </script>

    <script>
        var currentTab = 0; // Current tab is set to be the first tab (0)
        showTab(currentTab); // Display the current tab

        function showTab(n) {
            // This function will display the specified tab of the form...
            var x = document.getElementsByClassName("tab");
            x[n].style.display = "block";
            //... and fix the Previous/Next buttons:
            if (n == 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }
            if (n == (x.length - 1)) {
                document.getElementById("nextBtn").innerHTML = "Submit";
            } else {
                document.getElementById("nextBtn").innerHTML = "Next";
            }
            //... and run a function that will display the correct step indicator:
            fixStepIndicator(n)
        }

        function nextPrev(n) {
            // This function will figure out which tab to display
            var x = document.getElementsByClassName("tab");
            // Exit the function if any field in the current tab is invalid:
            if (n == 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form...
            if (currentTab >= x.length) {
                // ... the form gets submitted:
                document.getElementById("create_form").submit();
                return false;
            }
            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        function validateForm() {
            // This function deals with validation of the form fields
            var x, y, i, valid = true;
            x = document.getElementsByClassName("tab");
            y = x[currentTab].getElementsByTagName("input");
            // A loop that checks every input field in the current tab:
            for (i = 0; i < y.length; i++) {
                // If a field is empty...
                if (y[i].value == "") {
                    // add an "invalid" class to the field:
                    y[i].className += " invalid";
                    // and set the current valid status to false
                    valid = false;
                }
            }
            // If the valid status is true, mark the step as finished and valid:
            if (valid) {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }
            return valid; // return the valid status
        }

        function fixStepIndicator(n) {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            for (i = 0; i < x.length; i++) {
                x[i].className = x[i].className.replace(" active", "");
            }
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }
    </script>
    </script>

@endpush
