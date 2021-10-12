<aside class="main-sidebar sidebar-light bg-gradient-dark  elevation-4">

    <!-- Brand Logo -->
    <a href="{{route('main.home')}}" class="brand-link mt 3 p 3 bg-gradient-orange ">
        <img src="{{ asset('dashboard/dist/img/zesco1.png')}}" alt="Zesco Logo"
             class="brand-image img-rounded elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light ">eZesco</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('main.home')}}" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p> Profiles
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('main.profile.delegation.list')}}" class="nav-link ">
                                <i class="nav-icon far fa-circle"></i>
                                <p> List
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('main.profile.delegation')}}" class="nav-link ">
                                <i class="nav-icon far fa-circle"></i>
                                <p> Delegate
                                </p>
                            </a>
                        </li>
                        @if( Auth::user()->type_id == config('constants.user_types.developer'))
                            <li class="nav-item">
                                <a href="{{route('main.profile.delegation.show.on.behalf')}}" class="nav-link ">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p> Delegate on Behalf
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('main.profile.assignment')}}" class="nav-link ">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p> Assign
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('main.profile.transfer')}}" class="nav-link ">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p> Transfer
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('main.profile.remove')}}" class="nav-link ">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p> Remove
                                    </p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </li>


            @if( Auth::user()->type_id == config('constants.user_types.developer'))



                    <li class="nav-header">SYSTEM USERS</li>

                    <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p> System Users
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a class="nav-link "
                                       title="Search"
                                       data-toggle="modal"
                                       data-target="#modal-search-user">
                                        <i class="nav-icon fas fa-search"></i>
                                        <p> Search Users
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('main.user')}}" class="nav-link ">
                                        <i class="nav-icon far fa-circle"></i>
                                        <p> All System Users
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>



                    <li class="nav-header">CONFIG</li>
                    <li class="nav-item">
                        <a href="{{route('main.eforms.category')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> eForms Category</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.eforms')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> eForms </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.status')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> System Status </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.logs')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> System Logs </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.profile')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Profiles </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.profile.permissions')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Profiles Permissions </p>
                        </a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{route('main.user.unit')}}" class="nav-link ">--}}
{{--                            <i class="nav-icon fas fa-calendar-alt"></i>--}}
{{--                            <p> User.Units Workflow </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p> System Work Flow
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a class="nav-link "
                                   title="Search"
                                   data-toggle="modal"
                                   data-target="#modal-search">
                                    <i class="nav-icon fas fa-search"></i>
                                    <p> Search Work Flow
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('main.user.unit')}}" class="nav-link ">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p> All Work Flows
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.user.type')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> User Category </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.position')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Positions </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.directorate')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Directorates </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.division')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Divisions </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.region')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Regions </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.grade.category')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Grades Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.grade')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Grades </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.project')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Projects </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.account')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Accounts </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.location')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Location </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.pay.point')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> PayPoint </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main.functional.unit')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Functional Unit </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('main.totals')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Totals </p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

</aside>


<!-- SEARCH USER MODAL-->
<div class="modal fade" id="modal-search-user">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">User Search</h4>
            </div>
            <!-- form start -->
            <form role="form-new" method="post" action="{{route('main.user.search')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="code">Enter Search Term <span class="text-sm text-gray">(Case Sensitive)</span></label>
                                <input type="text" class="form-control " id="search_user" name="search"
                                       placeholder="Enter ManNo/Name/Email/NRC/Unit/JobCode/Phone">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- SEARCH UNIT MODAL-->
<div class="modal fade" id="modal-search">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-center">Search User Unit Work Flow</h4>
                {{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--                    <span aria-hidden="true">&times;</span>--}}
                {{--                </button>--}}
            </div>
            <!-- form start -->
            <form role="form-new" method="post" action="{{route('main.user.unit.search')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="code">Enter User Unit Code</label>
                                <input type="text" class="form-control " id="user_unit_code" name="user_unit_code"
                                       placeholder="Enter user unit code e.g C1931">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

