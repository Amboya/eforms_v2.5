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
                    <a href="{{route('petty-cash-home')}}" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">PETTY CASH</li>
                <li class="nav-item">
                    <a href="{{route('petty-cash-list', 'all')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> All</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('petty-cash-list', 'needs_me')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Needs You</p><span class="badge badge-success right ml-2">{{$totals_needs_me}}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.new_application') ) }}"
                       class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> New
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('petty-cash-list', 'pending')}}" class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Open
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.closed') ) }}"
                       class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Closed
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.rejected') ) }}"
                       class="nav-link ">
                        <i class="nav-icon fas fa-file"></i>
                        <p> Rejected
                        </p>
                    </a>
                </li>
                @if (Auth::user()->type_id == config('constants.user_types.developer') ||
Auth::user()->profile_id == config('constants.user_profiles.EZESCO_007') ||
Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014'))
                    <li class="nav-header">REPORTS</li>
                    <li class="nav-item">
{{--                        <a href="{{route('petty-cash-report', config('constants.all'))}}" class="nav-link ">--}}
{{--                            <i class="nav-icon fas fa-file"></i>--}}
{{--                            <p> All Reports--}}
{{--                            </p>--}}
{{--                        </a>--}}
                    </li>
                    <li class="nav-item">
                        <a href="{{route('petty-cash-report', config('constants.petty_cash_status.exported'))}}" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> Reports Exported
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('petty-cash-report', config('constants.petty_cash_status.not_exported'))}}" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> Reports Not Exported
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('petty-cash-report', config('constants.petty_cash_status.export_failed'))}}" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> Reports Export Failed
                            </p>
                        </a>
                    </li>
                    @if (Auth::user()->type_id == config('constants.user_types.developer'))
                        <li class="nav-item">
                            <a href="{{route('petty-cash-record','all')}}" class="nav-link ">
                                <i class="nav-icon fas fa-file"></i>
                                <p> All Petty Cash Records
                                </p>
                            </a>
                        </li>
                    @endif
                @endif

                <li class="nav-header">CONFIG</li>
                <li class="nav-item">
                    <a href="{{route('main-profile-delegation')}}" class="nav-link ">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p> Profile Delegation </p>
                    </a>
                </li>

                @if (Auth::user()->type_id == config('constants.user_types.developer'))
                    <li class="nav-item">
                        <a href="{{route('petty.cash.workflow')}}" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> Petty Cash Work Flow
                            </p>
                        </a>
                    </li>
                @endif

                <li class="nav-header">TOTALS</li>

                @if (Auth::user()->type_id == config('constants.user_types.developer'))
                    <li class="nav-item">
                        <a href="{{route('petty-cash-reports-index')}}" class="nav-link ">
                            <i class="nav-icon fas fa-file"></i>
                            <p> By Directorates
                            </p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

</aside>
