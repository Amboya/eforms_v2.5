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
                    <a href="{{route('petty-cash-list', 'needs_me')}}" class="nav-link ">
                        <i class="nav-icon fas fa-laptop"></i>
                        <p> My Attention</p><span class="badge badge-success right ml-2">{{$totals_needs_me}}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Categories
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('petty-cash-list', 'all')}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> All</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.new_application') ) }}"
                               class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> New
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('petty-cash-list', 'pending')}}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Open
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.closed') ) }}"
                               class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Closed
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.rejected') ) }}"
                               class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Rejected
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.cancelled') ) }}"
                               class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Cancelled
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.void') ) }}"
                               class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Void
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.audited') ) }}"
                               class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Audited
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route( 'petty-cash-list', config('constants.petty_cash_status.queried') ) }}"
                               class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Queried
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">REPORTS</li>
                <li class="nav-item">
                    <a href="{{route('petty.cash.filtered.report')}}" class="nav-link ">
                        <i class="nav-icon fas fa-bars"></i>
                        <p> Filtered Reports
                        </p>
                    </a>
                </li>
                @if (Auth::user()->type_id == config('constants.user_types.developer') ||
                Auth::user()->profile_id == config('constants.user_profiles.EZESCO_007') ||
                Auth::user()->profile_id == config('constants.user_profiles.EZESCO_014'))
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file-download"></i>
                            <p>
                                Closed Petty Cash
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                {{--                        <a href="{{route('petty-cash-report', config('constants.all'))}}" class="nav-link ">--}}
                                {{--                            <i class="nav-icon fas fa-file"></i>--}}
                                {{--                            <p> All Reports--}}
                                {{--                            </p>--}}
                                {{--                        </a>--}}
                            </li>
                            <li class="nav-item">
                                <a href="{{route('petty-cash-report', config('constants.petty_cash_status.exported'))}}"
                                   class="nav-link ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Exported
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('petty-cash-report', config('constants.petty_cash_status.not_exported'))}}"
                                   class="nav-link ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Not Exported
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('petty-cash-report', config('constants.petty_cash_status.export_failed'))}}"
                                   class="nav-link ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Export Failed
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @if (Auth::user()->type_id == config('constants.user_types.developer'))
                        <li class="nav-item">
                            <a href="{{route('petty-cash-record','all')}}" class="nav-link ">
                                <i class="nav-icon fas fa-file-download"></i>
                                <p> All Petty Cash Records
                                </p>
                            </a>
                        </li>
                    @endif
                @endif

                <li class="nav-header">CONFIGURATIONS</li>

                <li class="nav-item">
                    <a href="{{route('main-profile-delegation')}}" class="nav-link ">
                        <i class="fas fa-cog nav-icon"></i>
                        <p> Profile Delegation </p>
                    </a>
                </li>

                @if (Auth::user()->type_id == config('constants.user_types.developer'))
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
                                <a href="{{route('petty.cash.workflow')}}" class="nav-link ">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p> All Work Flows
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

{{--                                <li class="nav-header">TOTALS</li>--}}
{{--                                <li class="nav-item">--}}
{{--                                    <a href="#" class="nav-link">--}}
{{--                                        <i class="nav-icon far fa-chart-bar"></i>--}}
{{--                                        <p> Personal--}}
{{--                                            <i class="fas fa-angle-left right"></i>--}}
{{--                                        </p>--}}
{{--                                    </a>--}}
{{--                                    <ul class="nav nav-treeview">--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="#" class="nav-link ">--}}
{{--                                                <i class="nav-icon far fa-circle"></i>--}}
{{--                                                <p> Daily--}}
{{--                                                </p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="#" class="nav-link ">--}}
{{--                                                <i class="nav-icon far fa-circle"></i>--}}
{{--                                                <p> Monthly--}}
{{--                                                </p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                        <li class="nav-item">--}}
{{--                                            <a href="#" class="nav-link ">--}}
{{--                                                <i class="nav-icon far fa-circle"></i>--}}
{{--                                                <p> Yearly--}}
{{--                                                </p>--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}

{{--                                @if (Auth::user()->type_id == config('constants.user_types.developer'))--}}
{{--                                    <li class="nav-item">--}}
{{--                                        <a href="#" class="nav-link">--}}
{{--                                            <i class="nav-icon  far fa-chart-bar"></i>--}}
{{--                                            <p> Directorates--}}
{{--                                                <i class="fas fa-angle-left right"></i>--}}
{{--                                            </p>--}}
{{--                                        </a>--}}
{{--                                        <ul class="nav nav-treeview">--}}
{{--                                            <li class="nav-item">--}}
{{--                                                <a href="#" class="nav-link ">--}}
{{--                                                    <i class="nav-icon fas fa-circle"></i>--}}
{{--                                                    <p> Daily--}}
{{--                                                    </p>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li class="nav-item">--}}
{{--                                                <a href="#" class="nav-link ">--}}
{{--                                                    <i class="nav-icon fas fa-circle"></i>--}}
{{--                                                    <p> Monthly--}}
{{--                                                    </p>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li class="nav-item">--}}
{{--                                                <a href="#" class="nav-link ">--}}
{{--                                                    <i class="nav-icon fas fa-circle"></i>--}}
{{--                                                    <p> Yearly--}}
{{--                                                    </p>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                            <li class="nav-item">--}}
{{--                                                <a href="{{route('petty-cash-reports-index')}}" class="nav-link ">--}}
{{--                                                    <i class="nav-icon fas fa-file"></i>--}}
{{--                                                    <p> All Time--}}
{{--                                                    </p>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                @endif--}}

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->

</aside>


<!-- SEARCH MODAL-->
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
            <form role="form-new" method="post" action="{{route('petty.cash.workflow.search')}}">
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
<!-- /.NEW modal -->


