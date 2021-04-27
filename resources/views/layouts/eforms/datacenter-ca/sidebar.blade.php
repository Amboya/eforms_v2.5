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
            <ul class="nav nav-pills nav-sidebar flex-column " data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{route('datacenter-ca-home')}}" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">DATA CENTER CA</li>
                <li class="nav-item">
                    <a href="{{route('datacenter-ca-list', 'all')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> All</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route( 'datacenter-ca-list', config('constants.data_center_ca_status.new_submission') ) }}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> New
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('datacenter-ca-list', 'very_critical')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Very Critical
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route( 'datacenter-ca-list', 'critical' ) }}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Critical
                        </p>
                    </a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a href="{{route( 'datacenter-ca-list', config('constants.data_center_ca_status.reject_submission') ) }}" class="nav-link ">--}}
{{--                        <i class="nav-icon fas fa-file"></i>--}}
{{--                        <p> Rejected--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                </li>--}}
                <li class="nav-header">REPORTS</li>
                <li class="nav-item">
                    <a href="{{route('datacenter-ca-report')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Reports Export
                        </p>
                    </a>
                </li>

                <li class="nav-header">CONFIG</li>
                <li class="nav-item">
                    <a href="{{route('main-profile-delegation')}}" class="nav-link ">
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
