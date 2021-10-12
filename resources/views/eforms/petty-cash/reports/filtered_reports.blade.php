@extends('layouts.eforms.petty-cash.master')


@push('custom-styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
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
                        <li class="breadcrumb-item active">Petty-Cash  : {{$category}}</li>
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
                    <h5>Search</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row" >
                        <div class="col-4">
                            <div class="form-group">
                                <label for="user_unit_select">Select User Unit</label>
                                <select   class="form-control" id="user_unit_select" name="user_unit_select" required>
                                    <option value=""> Select User Unit</option>
                                    @foreach($user_units as $item)
                                        <option value="{{$item->user_unit_code}}" >  {{$item->user_unit_code}}:  {{$item->user_unit_description}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="status_select">Select Status</label>
                                <select   class="form-control" id="status_select" name="status_select" required>
                                    <option value=""> Select Status</option>
                                    @foreach($status as $item)
                                        <option value="{{$item->id}}" >  {{$item->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="audit_id">Start Date</label>
                                <input  type="date" class="form-control" id="start_date" name="start_date" >
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="audit_id">End Date</label>
                                <input  type="date" class="form-control" id="end_date" name="end_date" >
                            </div>
                        </div>
                        <div class="col-2 " style="margin-top: 30px">
                            <div class="form-group">
                                <button  class="btn btn-success" id="search_button"><i class="far fa-search"></i>Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h5>List</h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-center">
                        <!-- Image loader -->
                        <div id="loader" style="display: none;">
                            <img src="{{ asset('dashboard/dist/gif/Eclipse_loading.gif')}}" width="100px" height="100px">
                        </div>
                        <!-- Image loader -->
                    </div>
                </div>
                <div class="table-responsive" id="my_table">


{{--                    {!! $list->links() !!}--}}
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

            <div class="card">
                <div class="card-header">
                    <h5>Summary</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="audit_id">Status</label>
                        <input  type="text" readonly class="form-control" id="summary_status" name="summary_status" >
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="audit_id">Total Transactions</label>
                        <input  type="text" readonly class="form-control" id="summary_total" name="summary_total" >
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="audit_id">Total Amount</label>
                        <input  type="text" readonly class="form-control text-bold " id="summary_amount" name="summary_amount" >
                    </div>
                </div>
            </div>
            </div>
            <!-- /.card-body -->
            </div>
    </section>
    <!-- /.content -->


@endsection


@push('custom-scripts')


    <!-- DataTables  & Plugins -->
    <script src="{{ asset('dashboard/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('dashboard/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

    <!-- page script -->
    <script>
        // $(function () {
        //
        //     $("#example1").DataTable({
        //         "responsive": true, "lengthChange": false, "autoWidth": false,
        //         "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        //     }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        //
        //     $('#example2').DataTable({
        //         "paging": true,
        //         "lengthChange": false,
        //         "searching": false,
        //         "ordering": true,
        //         "info": true,
        //         "autoWidth": false,
        //         "responsive": true,
        //     });
        // });
    </script>


    <script>
        $("#search_button").click(function () {
            var unit_selected_text = ''; // Selected text
            var unit_selected_value = ''; // Selected value
            var unit_selected_index = ''; // Selected index
            // Get selected value
            $('#user_unit_select option:selected').each(function () {
                unit_selected_text += $(this).text();
                unit_selected_value += $(this).val();
                unit_selected_index += $(this).index();
            });
            // alert(unit_selected_value);

            var status_selected_text = ''; // Selected text
            var status_selected_value = ''; // Selected value
            var status_selected_index = ''; // Selected index
            // Get selected value
            $('#status_select option:selected').each(function () {
                status_selected_text += $(this).text();
                status_selected_value += $(this).val();
                status_selected_index += $(this).index();
            });
            // alert(status_selected_value);

            var start_date = $("#start_date").val() ;
            // alert(start_date);
            var end_date = $("#end_date").val() ;
            // alert(end_date);

            /* AJAX */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //make sure all fields are filled in
            if(unit_selected_value == "" || status_selected_value == "" || start_date == "" || end_date == ""  ){
                alert("Please fill in all the search parameters.");
            }else{
                //get the route
                var route = '{{url('petty/cash/filtered/report/get')}}' + '/' + unit_selected_value  + '/' + status_selected_value+ '/' + start_date+ '/' + end_date;
                // alert(route);
                $.ajax({
                    url: route,
                    type: 'get',
                    beforeSend: function(){
                        // Show image container
                        $("#loader").show();
                    },
                    success: function(response_data){
                         console.log(response_data);
                        list_responce = "";
                         if(response_data.superior){
                             var list = response_data.list ;
                             var total = 0 ;
                             var amount = 0 ;
                             for(var i = 0; i < list.length; i++){
                                 // loop through list array
                                 $.each( list[i], function (index, value) {

                                     //list array
                                     var route = '{{ url('petty/cash/show/form') }}' + '/'+ value.id ;
                                     var View = "View";
                                     //populate the table
                                     list_responce +=
                                         "<tr> " +
                                         "<td  > " + value.user_unit_code + " </td>" +
                                         "<td  > " + value.business_unit_code + " </td>" +
                                         "<td  > " + value.cost_center + " </td>" +
                                         "<td  > " + value.code + " </td>" +
                                         "<td  > " + value.claimant_name + " </td>" +
                                         "<td  > " + value.total_payment + " </td>" +
                                         "<td  > " + response_data.status + " </td>" +
                                         "<td  > " + value.claim_date + " </td>" +
                                         "<td  > <a href='"+route+"' class='btn btn-sm bg-orange' >"+ View +"</a>  </td>" +
                                         "</tr>";

                                     total++ ;
                                     amount = amount += parseFloat(value.total_payment );
                                 });

                             }

                             //list array  summary_total
                             document.getElementById('summary_total').value= total ;
                             document.getElementById('summary_amount').value= 'ZMW '+  amount ;
                             document.getElementById('summary_status').value= response_data.status;

                         }else{
                             $.each( response_data.list, function (index, value) {
                                 //list array
                                 var form_id  = 'show-form'+value.id ;
                                 var route = '{{ url('petty/cash/show/form') }}' + '/'+ value.id ;
                                 var View = "View";
                                 var csrf = '@csrf' ;
                                 //populate the table
                                 list_responce +=
                                     "<tr> " +
                                     "<td  > " + value.user_unit_code + " </td>" +
                                     "<td  > " + value.business_unit_code + " </td>" +
                                     "<td  > " + value.cost_center + " </td>" +
                                     "<td  > " + value.code + " </td>" +
                                     "<td  > " + value.claimant_name + " </td>" +
                                     "<td  > " + value.total_payment + " </td>" +
                                     "<td  > " + response_data.status + " </td>" +
                                     "<td  > " + value.claim_date + " </td>" +
                                     "<td  > <a href='"+route+"' class='btn btn-sm bg-orange' >"+ View +"</a>  </td>" +
                                     "</tr>";
                             });
                             // loop through summary array
                             $.each( response_data.summary, function (index, value) {
                                 amount =    value.amount  ;
                                 //list array  summary_total
                                 $("#summary_total").value = value.total;
                                 document.getElementById('summary_total').value=value.total ;
                                 document.getElementById('summary_amount').value= 'ZMW '+ amount  ;
                                 document.getElementById('summary_status').value= response_data.status;
                             });
                         }

                         var head = "  <table id='example1' class='table m-0'> " +
                             "<thead> " +
                             "<tr> " +
                             "<th>UserUnit</th> " +
                             "<th>Bu Code</th> " +
                             "<th>CC Code</th> " +
                             "<th>Serial</th> " +
                             "<th>Claimant</th> " +
                             "<th>Payment</th> " +
                             "<th>Status</th> " +
                             "<th>Claim Date</th> " +
                             "<th>Action</th> " +
                             "</tr> " +
                             "</thead> " +
                             "<tbody > " +
                        list_responce +
                        "</tbody>  </table>" ;

                        $("#my_table").html(head);


                        $(function () {

                            $("#example1").DataTable({
                                "responsive": true, "lengthChange": false, "autoWidth": false,
                                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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


                    },
                    complete:function(data){
                        // Hide image container
                        $("#loader").hide();
                    }
                });
            }




            {{--//get the route--}}
            {{--var route = '{{url('petty/cash/filtered/report/get')}}' + '/' + selected_value  + '/' + selected_value+ '/' + selected_value+ '/' + selected_value;--}}

            /*GET */
            // $.get(route, function (data) {
            //     responce = "";
            //     // loop through array
            //     $.each(data, function (index, value) {
            //         //populate the table
            //         responce +=
            //             "<tr> " +
            //             "<td  width='18%'> <input type='text' value='" + value.name + "'  class='form-control' id='material[]' name='material[]' required> </td>" +
            //             "<td  width='7%' > <input type='text' value='" + value.code + "'  class='form-control' id='code[]' name='code[]'  required> </td>" +
            //             "<td  width='6%' > <input type='text' value='" + value.price + "'  class='form-control' id='price[]' name='price[]'  required> </td>" +
            //             "<td  width='6%' > <input type='number' step='any' class='form-control' id='market_price[]' name='market_price[]' placeholder='Enter Price' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='jan[]' name='jan[]' placeholder='Enter Jan Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='feb[]' name='feb[]' placeholder='Enter Feb Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='mar[]' name='mar[]' placeholder='Enter Mar Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='apr[]' name='apr[]' placeholder='Enter Apr Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='may[]' name='may[]' placeholder='Enter May Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='jun[]' name='jun[]' placeholder='Enter Jun Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='jul[]' name='jul[]' placeholder='Enter Jul Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='aug[]' name='aug[]' placeholder='Enter Aug Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='sep[]' name='sep[]' placeholder='Enter Sep Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='oct[]' name='oct[]' placeholder='Enter Oct Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)'  class='form-control' id='nov[]' name='nov[]' placeholder='Enter Nov Amount' > </td>" +
            //             "<td> <input type='number' step='any' onchange='getValues(this.value)' class='form-control' id='dec[]' name='dec[]' placeholder='Enter Dec Amount' > </td>" +
            //             "<td> <input type='number'  step='any' class='form-control text-bold' id='total[]' name='total[]' placeholder='Enter Total Amount' > </td>" +
            //             "</tr>";
            //     });
            //     $("#create_table").html(responce);
            // });
        });
    </script>



@endpush
