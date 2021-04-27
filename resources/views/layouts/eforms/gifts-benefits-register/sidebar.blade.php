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
                    <a href="{{route('gifts-benefits-home')}}" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">KILOMETER ALLOWANCE</li>
                <li class="nav-item">
                    <a href="{{route('gifts-benefits-list', 'all')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> All</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('gifts-benefits-list', 'needs_me')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Needs You</p><span  class="badge badge-success right ml-2">{{$totals_needs_me}}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route( 'gifts-benefits-list', config('constants.kilometer_allowance_status.new_application') ) }}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> New
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('gifts-benefits-list', 'pending')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Open
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route( 'gifts-benefits-list', config('constants.kilometer_allowance_status.closed') ) }}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Closed
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route( 'gifts-benefits-list', config('constants.kilometer_allowance_status.rejected') ) }}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Rejected
                        </p>
                    </a>
                </li>
{{--                <li class="nav-header">REPORTS</li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a href="{{route('gifts-benefits-report')}}" class="nav-link ">--}}
{{--                        <i class="nav-icon fas fa-file"></i>--}}
{{--                        <p> Reports Export--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                </li>--}}

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
