<div>

    <div class="p-2">
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
                        <div class="col-3">
                            <div class="form-group">
                                <label for="user_unit_select">Select User Unit</label>
                                <select   class="form-control" id="user_unit_select" wire:model="user_unit_select" required>
                                    <option value=""> Select User Unit</option>
                                                                        @foreach($user_units as $item)
                                                                            <option value="{{$item->id}}" >  {{$item->name}}</option>
                                                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="status_select">Select Status</label>
                                <select   class="form-control" id="status_select"  wire:model="status_select" required>
                                    <option value=""> Select Status</option>
                                    <option value="money_given" > Money Given </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="audit_id">Start Date</label>
                                <input  type="date" class="form-control" id="start_date"  wire:model="start_date" >
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="audit_id">End Date</label>
                                <input  type="date" class="form-control" id="end_date" wire:model="end_date" >
                            </div>
                        </div>
                        <div class="col-1 " style="margin-top: 30px">
                            <div class="form-group">
                                <button  class="btn btn-success" wire:click="filterBy()"><i class="far fa-search"></i>Search</button>
                            </div>
                        </div>
                        <div class="col-2">
                            <div wire:loading >
                                <div class="loader mt-2"></div>
                                <span class="text-info text-sm">Loading...</span>
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
                        <table id="example1" class="table m-0">
                            {{--                                    @endif--}}
                            <thead class="table text-white text-bold text-uppercase bg-gradient-green ">
                            <tr>
                                <th></th>
                                <th>Directorate</th>
                                <th>Division</th>
                                <th>Unit</th>
                                <th>Serial</th>
                                <th>Claimant</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach( $forms as $key=> $item )

                                <tr>
                                    <td>
                                       {{++$key}}
                                    </td>
                                    <td>{{$item->directorate->name ?? ""}}</td>
                                    <td>{{$item->division->name ?? ""}}</td>
                                    <td>{{$item->user_unit->user_unit_description ?? ""}}</td>

                                    <td><a href="{{ route('logout') }}" class=""
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
                                <td><b> </b></td>
                                <td><b> </b></td>
                                <td><b> </b></td>
                                <td><b>

                                    </b></td>
                                <td><b></b></td>
                                <td>
                                    <b>ZMW {{  $total }}
                                    </b>
                                </td>
                                <td><b></b></td>
                                <td><b></b></td>
                                <td><b></b></td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>

</div>
