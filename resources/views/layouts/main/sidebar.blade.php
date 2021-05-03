<aside class="main-sidebar sidebar-light bg-gradient-dark  elevation-4">

    <!-- Brand Logo -->
    <a href="{{route('main-home')}}" class="brand-link mt 3 p 3 bg-gradient-orange ">
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
                    <a href="{{route('main-home')}}" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>




                @if( Auth::user()->type_id == config('constants.user_types.developer'))
                    <li class="nav-header">SYSTEM</li>
                    <li class="nav-item">
                        <a href="{{route('main-user')}}" class="nav-link ">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-profile-assignment')}}" class="nav-link ">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p> Profile Assignments</p>
                        </a>
                    </li>


                    <li class="nav-header">CONFIG</li>
                    <li class="nav-item">
                        <a href="{{route('main-eforms-category')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> eForms Category</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-eforms')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> eForms </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-status')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> System Status </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-logs')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> System Logs </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-profile')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Profiles </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-profile-permissions')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Profiles Permissions </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-user_unit')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> User Unit </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-user-type')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> User Category </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-position')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Positions </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-directorate')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Directorates </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-division')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Divisions </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-region')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Regions </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-grade-category')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Grades Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-grade')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Grades </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-project')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Projects </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-account')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Accounts </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-location')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Location </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-pay-point')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> PayPoint </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-functional-unit')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Functional Unit </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('main-totals')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Totals </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-divisional-user-unit')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Divisional User Units </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('main-department')}}" class="nav-link ">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p> Departments </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

</aside>
