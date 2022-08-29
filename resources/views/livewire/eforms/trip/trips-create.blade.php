<div class="p-2 mt-4">
    {{-- Because she competes with no one, no one can compete with her. --}}


    <section class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
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
                </div>
            </div>


            <div class="row">

                <div class="col-10 offset-1">

                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">TRIP CREATION FORM</h3>
                            <div class="card-tools">
                                <div wire:loading>
                                    <div class="spinner-border text-warning" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form wire:submit.prevent="submit" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <label class="text-orange">STEP 1</label>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="name">Name of the Trip</label>
                                            <input type="text" name="name" class="form-control" id="name"
                                               required   placeholder="Enter name">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Trip Date From</label>
                                            <input type="date" min="{{ date('Y-m-d')}}" wire:model.defer="date_from"
                                                   required    class="form-control" id="date_from" placeholder="Enter date from">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Trip Date To</label>
                                            <input type="date" min="{{ date('Y-m-d') }}" max="{{$max_date}}"
                                                   required    wire:model.defer="date_to"  name="date_to"
                                                   class="form-control" id="date_to" placeholder="Enter date to">
                                            <span>{{$no_of_days }} Nights</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Trip Description</label>
                                            <textarea rows="3" name="description" class="form-control" id="description"
                                                      required   wire:model.defer="description"   placeholder="Enter purpose of the trip"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Trip Authorization Document</label>
                                            <input type="file" wire:model.defer="file" name="file" class="form-control"
                                                   required    id="file" placeholder="Enter file">
                                        </div>
                                    </div>
                                </div>

                                <label class="text-orange">STEP 2</label>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Trip Destination</label>
                                            <input type="text" wire:model.defer="destination" name="destination"
                                                   required  class="form-control mt-1" id="destination" placeholder="Enter name">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Destination User Unit:</label>
                                            <div class="mt-1">
                                                <div class="card card-outline collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Select Destination User Units</h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-sm btn-outline-dark"
                                                                    title="This is the user unit which will sign on the trip form confirming the number of days taken"
                                                                    data-card-widget="collapse">
                                                                <i class="fa fa-plus"></i>
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
                                                                <tbody id="myTable1">
                                                                @foreach($destination_units as $item)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-group clearfix">
                                                                                <div class="icheck-warning d-inline">
                                                                                    <input type="checkbox"
                                                                                           value="{{ $item->user_unit_code }}"
                                                                                           wire:model.defer="selectedDestinations.{{ $item->user_unit_code }}"
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
                                </div>

                                <label class="text-orange">STEP 3</label>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="description">Trip Budget Holder Unit</label>
                                            <div class="mt-1">
                                                <div class="card card-outline collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Select User-Unit to Approve Budget</h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-sm btn-outline-dark"
                                                                    title="This is the user unit where the money is coming from - every project also has a user-unit associated to it"
                                                                    data-card-widget="collapse">
                                                                <i class="fa fa-plus"></i>
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
                                                                <tbody id="myTable2">
                                                                @foreach($destination_units as $item2)
                                                                    <tr>
                                                                        <td>
                                                                            <div class="form-group clearfix">
                                                                                <div class="icheck-warning d-inline">
                                                                                    <input type="radio"
                                                                                           value="{{ $item2->user_unit_code }}"
                                                                                           wire:model.defer="selectedBudgetUnit.{{ $item2->user_unit_code }}"
                                                                                           id="budget_Holder_unit"
                                                                                           name="budget_Holder_unit">

                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td><span for="accounts"> <span
                                                                                    class="text-gray">{{$item2->user_unit_code}}</span>  </span>
                                                                        </td>
                                                                        <td><span for="accounts"> <span
                                                                                    class="text-gray">{{$item2->user_unit_description}}</span>  </span>
                                                                        </td>
                                                                        <td><span for="accounts"> <span
                                                                                    class="text-gray">{{$item2->user_unit_bc_code}}</span>  </span>
                                                                        </td>
                                                                        <td><span for="accounts"> <span
                                                                                    class="text-gray">{{$item2->user_unit_cc_code}}</span> </span>
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
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label>Trip Members</label>
                                            <div class="mt-1">
                                                <div class="card card-outline collapsed-card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Select members</h3>
                                                        <div class="card-tools">
                                                            <button type="button" class="btn btn-sm btn-outline-dark"
                                                                    title="These are the people who are going on this trip"
                                                                    data-card-widget="collapse">
                                                                <i class="fa fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <!-- /.card-tools -->
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <div class="col-12">
                                                            <input class="mb-2" id="myInputUsers" type="text"
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
                                                                                           value="{{ $user->id }}"
                                                                                           wire:model.defer="selectedUsers.{{ $user->id }}"
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
                                </div>

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>

</div>

