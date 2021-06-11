
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>eZesco | Petty Cash</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="http://isd-dev/eZESCO3/public/dashboard/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="http://isd-dev/eZESCO3/public/dashboard/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="http://isd-dev/eZESCO3/public/dashboard/dist/css/adminlte.min.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">


    <!-- Select2 -->
    <link rel="stylesheet" href="http://isd-dev/eZESCO3/public/dashboard/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="http://isd-dev/eZESCO3/public/dashboard/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

</head>
<body class="hold-transition sidebar-dark-secondary sidebar-mini sidebar-collapse layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="http://isd-dev/eZESCO3/public/main/home" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        </ul>

        <!-- SEARCH FORM -->
        <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown ">
                <div class="user-panel mt-1 pb-1 d-flex">
                    <div class="image">
                        <img src="http://isd-dev/eZESCO3/public/storage/user_avatar/muslim-man-icon-in-cartoon-style-vector-9381822_1612796003.jpg" class="img-circle elevation-2"
                             alt="User Image"
                             onerror="this.src='http://isd-dev/eZESCO3/public/dashboard/dist/img/avatar.png';"
                        >
                    </div>
                </div>
            </li>
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown ">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    PETER  MUDENDA</a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="http://isd-dev/eZESCO3/public/main/user/show/21"  class="dropdown-item">
                        <i class="fas fa-user-circle mr-2"></i> My Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="http://isd-dev/eZESCO3/public/logout" class="dropdown-item" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>

                    <form id="logout-form" action="http://isd-dev/eZESCO3/public/logout" method="POST" class="d-none">
                        <input type="hidden" name="_token" value="NIN2MpJ1eEKe5ggc1xfLv1y0Oku9VR2beRwc7g62">                </form>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-light bg-gradient-dark  elevation-4">

        <!-- Brand Logo -->
        <a href="http://isd-dev/eZESCO3/public/main/home" class="brand-link mt 3 p 3 bg-gradient-orange ">
            <img src="http://isd-dev/eZESCO3/public/dashboard/dist/img/zesco1.png" alt="Zesco Logo"
                 class="brand-image img-rounded elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light ">eZesco</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="http://isd-dev/eZESCO3/public/petty_cash/home" class="nav-link ">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-header">Petty Cash</li>
                    <li class="nav-item">
                        <a href="http://isd-dev/eZESCO3/public/petty_cash/list" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> All</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://isd-dev/eZESCO3/public/petty_cash/list" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> New
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://isd-dev/eZESCO3/public/petty_cash/list" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> Open
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://isd-dev/eZESCO3/public/petty_cash/list" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> Closed
                            </p>
                        </a>
                    </li>
                    <li class="nav-header">REPORTS</li>
                    <li class="nav-item">
                        <a href="http://isd-dev/eZESCO3/public/petty_cash/reports" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> Reports Export
                            </p>
                        </a>
                    </li>

                    <li class="nav-header">CONFIG</li>
                    <li class="nav-item">
                        <a href="http://isd-dev/eZESCO3/public/main/profile/assignment" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Profile Assignments</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="http://isd-dev/eZESCO3/public/main/profile/delegation" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Profile Delegation </p>
                        </a>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->

    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">


        <!-- Main content -->

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Petty Cash Detail</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="http://isd-dev/eZESCO3/public/petty_cash/home">Home</a></li>
                            <li class="breadcrumb-item active">Petty Cash Detail</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->


        <!-- Main page content -->
        <section class="content">





            <!-- Default box -->
            <div class="card">
                <form name="db1" action="http://isd-dev/eZESCO3/public/petty_cash/approve" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="NIN2MpJ1eEKe5ggc1xfLv1y0Oku9VR2beRwc7g62">                <div class="card-body">
                        <span class="badge badge-info">New Application</span>
                        <input type="hidden" name="id" value="23" readonly required>
                        <input type="hidden" name="sig_date" value=" 2021-02-08 16:56:50" readonly required>

                        <table border="1" width="100%" cellspacing="0" cellpadding="0" align="Centre"
                               class="mt-2 mb-4">
                            <thead>
                            <tr>
                                <th width="33%" colspan="1" class="text-center"><a href="#"><img
                                            src="http://isd-dev/eZESCO3/public/dashboard/dist/img/zesco1.png" title="ZESCO" alt="ZESCO"
                                            width="25%"></a></th>
                                <th width="33%" colspan="4" class="text-center">Petty Cash Voucher</th>
                                <th width="34%" colspan="1" class="p-3">Doc Number:<br>CO.14900.FORM.00165<br>Version: 3
                                </th>
                            </tr>
                            </thead>
                        </table>

                        <div class="row">
                            <div class="row mt-2 mb-4">
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12"><label>Date:</label></div>
                                        <div class="col-12"><input value="2021-02-08 16:53:38" type="text"
                                                                   name="date"
                                                                   readonly class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12 "><label>Cost Center:</label></div>
                                        <div class="col-12"><input type="text" name="cost_center" class="form-control"
                                                                   value="14450" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12"><label>SYS Ref No:</label></div>
                                        <div class="col-12"><input type="text" value="PT0762781" name="ref_no"
                                                                   readonly required class="form-control"></div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="row">
                                        <div class="col-12"><label>Project Number:</label></div>
                                        <div class="col-12"><input list="project_list" type="text" name="projects_id"
                                                                   value=""
                                                                   class="form-control">
                                            <datalist id="project_list">
                                            </datalist>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>


                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="table-responsive">
                                <div class="col-lg-12 ">
                                    <table class="table bg-green">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>DETAILS OF PAYMENT</th>
                                            <th>AMOUNT</th>
                                        </tr>
                                        </thead>
                                    </table>.
                                </div>
                                <div class="col-lg-12 ">
                                    <TABLE id="dataTable1" class="table">
                                        <TR>
                                            <TD><input type="text" name="name[]" class="form-control amount"
                                                       value="test" id="name" required>
                                            </TD>
                                            <TD><input type="text" id="amount" name="amount[]" onchange="getvalues()"
                                                       class="form-control amount" value="100">
                                            </TD>
                                        </TR>
                                        <TR>
                                            <TD><input type="text" name="name[]" class="form-control amount"
                                                       value="new" id="name" required>
                                            </TD>
                                            <TD><input type="text" id="amount" name="amount[]" onchange="getvalues()"
                                                       class="form-control amount" value="100">
                                            </TD>
                                        </TR>
                                        <TR>
                                            <TD><input type="text" name="name[]" class="form-control amount"
                                                       value="gome" id="name" required>
                                            </TD>
                                            <TD><input type="text" id="amount" name="amount[]" onchange="getvalues()"
                                                       class="form-control amount" value="100">
                                            </TD>
                                        </TR>
                                    </TABLE>
                                </div>
                                <div class="col-lg-6 offset-6 ">
                                    <div class="row">
                                        <div class="col-4 text-right">
                                            TOTAL PAYMENT
                                        </div>
                                        <div class="col-8">
                                            <input type="text" class="form-control text-bold" readonly id="total-payment"
                                                   name="total_payment" value="300">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>


                        <div class="row mb-1 mt-4">
                            <div class="col-2">
                                <label>Name of Claimant:</label>
                            </div>
                            <div class="col-3">
                                <input type="text" name="claimant_name" class="form-control"
                                       value="PETER  MUDENDA" readonly required></div>
                            <div class="col-2 text-center"><label>Signature:</label></div>
                            <div class="col-2"><input type="text" name="sig_of_claimant" class="form-control"
                                                      value="16503" readonly required></div>
                            <div class="col-1 text-center"><label>Date:</label></div>
                            <div class="col-2"><input type="text" name="date_claimant" class="form-control"
                                                      value="2021-02-08 16:53:38" readonly required>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2"><label>Claim Authorised by:</label></div>
                            <div class="col-3"><input type="text" value=""
                                                      name="claim_authorised_by" readonly class="form-control">
                            </div>
                            <div class="col-2 text-center"><label>Signature:</label></div>
                            <div class="col-2"><input type="text" value=""
                                                      name="sig_of_authorised" readonly class="form-control">
                            </div>
                            <div class="col-1  text-center"><label>Date:</label></div>
                            <div class="col-2"><input type="text" value=""
                                                      name="authorised_date" readonly class="form-control">
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2"><label>HR/Station Manager:</label></div>
                            <div class="col-3"><input type="text" value=""
                                                      name="station_manager" readonly class="form-control">
                            </div>
                            <div class="col-2 text-center"><label>Signature:</label></div>
                            <div class="col-2"><input type="text" value=""
                                                      name="sig_of_station_manager" readonly
                                                      class="form-control"></div>
                            <div class="col-1 text-center"><label>Date:</label></div>
                            <div class="col-2"><input type="text" value=""
                                                      name="manager_date" readonly class="form-control"></div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-2"><label>Accountant:</label></div>
                            <div class="col-3"><input type="text" value=""
                                                      name="accountant" readonly class="form-control"></div>
                            <div class="col-2 text-center"><label>Signature:</label></div>
                            <div class="col-2"><input type="text" value=""
                                                      name="sig_of_accountant" readonly class="form-control">
                            </div>
                            <div class="col-1 text-center"><label>Date:</label></div>
                            <div class="col-2"><input type="text" value=""
                                                      name="accountant_date" readonly class="form-control">
                            </div>
                        </div>


                        <p><b>Note:</b> The system reference number is mandatory and is from
                            any of the systems at ZESCO such as a work request number from PEMS, Task
                            number from HQMS, Meeting Number from HQMS, Incident number from IMS etc.
                            giving rise to the expenditure</p>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">

                        <div class="row">
                            <div id="submit_not_possible" class="col-12 text-center">
                                <div class="alert alert-danger ">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h5><i class="icon fas fa-ban"></i> Alert!</h5>
                                    Sorry, You can not submit <strong>petty cash above K2000</strong>
                                </div>
                            </div>
                            <div id="submit_possible" class="col-12 text-center">
                                <input class="btn btn-lg btn-success" type="submit"
                                       value="update"
                                       name="submit_form" class="form-control"
                                       onClick="formValidation()">
                            </div>
                        </div>
















                    </div>
                    <!-- /.card-footer-->
                </form>
            </div>
            <!-- /.card -->

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Next Person/s</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>

            <div class="card collapsed-card">
                <div class="card-header">
                    <h4 class="card-title">Approvals</h4>  <span
                        class="badge badge-secondary right ml-2">0</span>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div style="display: none;" class="card-body">
                    <div class="col-lg-12 ">
                        <TABLE id="dataTable" class="table">
                            <TR>
                                <TD>Name</TD>
                                <TD>Man No</TD>
                                <TD>Action</TD>
                                <TD>Status From</TD>
                                <TD>Status To</TD>
                                <TD>Reason</TD>
                                <TD>Date</TD>
                            </TR>
                        </TABLE>

                    </div>
                </div>
                <div class="card-footer">
                </div>
            </div>
        </section>
        <!-- /.content -->
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2021 <a href="https://www.zesco.co.zm/">ZESCO Limited</a>.</strong>
        Designed by Information & Cyber Security Systems Division (I&CSS). All Rights Reserved
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 2.0.0
        </div>
    </footer>

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="http://isd-dev/eZESCO3/public/dashboard/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="http://isd-dev/eZESCO3/public/dashboard/dist/js/demo.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/raphael/raphael.min.js"></script>
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/chart.js/Chart.min.js"></script>

<!-- PAGE SCRIPTS -->
<script src="http://isd-dev/eZESCO3/public/dashboard/dist/js/pages/dashboard2.js"></script>

<!--  -->

<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="http://isd-dev/eZESCO3/public/dashboard/plugins/select2/js/select2.full.min.js"></script>

<script type="text/javascript">

    // Navigation Script Starts Here
    $(document).ready(function () {
        //first hide the buttons
        $('#submit_possible').hide();
        $('#submit_not_possible').hide();
        $('#show_change').hide();

        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

    });


    function getvalues() {
        var inps = document.getElementsByName('credited_amount[]');
        var total = 0;
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            total = total + parseFloat(inp.value || 0);
        }

        var total_payment = "300";

        if (!isNaN(total)) {

            //check if petty cash accounts is equal to total_payment
            if (total == total_payment) {
                $('#submit_possible').show();
                $('#submit_not_possible').hide();
            } else if (total < total_payment) {
                $('#submit_not_possible').show();
                $('#submit_possible').hide();
            } else {
                $('#submit_not_possible').show();
                $('#submit_possible').hide();
            }
            //set value
            //document.getElementById('total-payment').value = total;
        }
    }


    function getvalues1() {
        var inps = document.getElementsByName('debited_amount[]');
        var total = 0;
        for (var i = 0; i < inps.length; i++) {
            var inp = inps[i];
            total = total + parseFloat(inp.value || 0);
        }

        var total_payment = "300";

        if (!isNaN(total)) {

            //check if petty cash accounts is equal to total_payment
            if (total == total_payment) {
                $('#submit_possible').show();
                $('#submit_not_possible').hide();
            } else if (total < total_payment) {
                $('#submit_not_possible').show();
                $('#submit_possible').hide();
            } else {
                $('#submit_not_possible').show();
                $('#submit_possible').hide();
            }
            //set value
            //document.getElementById('total-payment').value = total;
        }
    }


    function showChange() {

        var change_value = document.getElementById('change').value;

        if (!isNaN(change_value)) {

            //check if petty cash accounts is equal to total_payment
            if (change_value > 0) {
                $('#show_change').show();
                //set value
                document.getElementById('credited_amount1').value = change_value;
                document.getElementById('debited_amount1').value = change_value;
            } else {
                $('#show_change').hide();
            }

        }
    }


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

</body>
</html>
