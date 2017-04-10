<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="nav-item start {{ Request::is('/') ? 'active open' : '' }} ">
                <a href="/" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li class="heading">
                <h3 class="uppercase">Konfigurasi</h3>
            </li>
            {{--@if(\Auth::user()->role != 'reo')--}}
            <li class="nav-item {{ Request::is('sdf') ? 'active' : '' }} ">
                <a href="{{ url('sdf') }}" class="nav-link nav-toggle">
                    <i class="icon-layers"></i>
                    <span class="title">SDF</span>
                </a>
            </li>
            {{--@endif--}}
            @if(\Auth::user()->role != 'loreal')
                <li class="nav-item {{ Request::is('configuration/wip') ? 'active' : '' }} ">
                    <a href="{{ url('configuration/wip') }}" class="nav-link nav-toggle">
                        <i class="icon-hourglass"></i>
                        <span class="title">WIP</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('configuration/exitForm') ? 'active' : '' }} ">
                    <a href="{{ url('configuration/exitForm') }}" class="nav-link nav-toggle">
                        <i class="icon-logout"></i>
                        <span class="title">Exit Form</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('configuration/turnover') ? 'active' : '' }} ">
                    <a href="{{ url('configuration/turnover') }}" class="nav-link nav-toggle">
                        <i class="icon-logout"></i>
                        <span class="title">Turn Over</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('configuration/rolling') ? 'active' : '' }} ">
                    <a href="{{ url('configuration/rolling') }}" class="nav-link nav-toggle">
                        <i class="icon-refresh"></i>
                        <span class="title">Update Konfigurasi</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('configuration/sp') ? 'active' : '' }} ">
                    <a href="{{ url('configuration/sp') }}" class="nav-link nav-toggle">
                        <i class="icon-envelope-letter"></i>
                        <span class="title">SP</span>
                    </a>
                </li>
                <li class="nav-item  ">
                    <a href="" class="nav-link nav-toggle">
                        <i class="icon-doc"></i>
                        <span class="title">Import Data</span>
                    </a>
                </li>
                <li class="heading">
                    <h3 class="uppercase">Master Data</h3>
                </li>
                <li class="nav-item {{ Request::is('master/ba') ? 'active' : '' }} ">
                    <a href="{{ route('masterBa') }}" class="nav-link nav-toggle">
                        <i class="fa fa-female"></i>
                        <span class="title">BA</span>
                        @if(Auth::user()['role'] != 'reo')
                            <?php
                            $approveid = (Auth::user()['role'] == 'arina') ? [0, 3] : [1, 4];
                            $totalba = App\Ba::whereIn('approval_id', $approveid)->count();
                            ?>
                            @if($totalba > 0)
                                <span class="badge badge-success">{{ $totalba }}</span>
                            @endif
                        @endif
                    </a>
                </li>
                <li class="nav-item {{ Request::is('master/account') ? 'active' : '' }} ">
                    <a href="{{ route('masterAccount') }}" class="nav-link nav-toggle">
                        <i class="fa fa-tags"></i>
                        <span class="title">Account</span>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('master/store') ? 'active' : '' }} ">
                    <a href="{{ route('masterStore') }}" class="nav-link nav-toggle">
                        <i class="icon-basket"></i>
                        <span class="title">Stores</span>
                    </a>
                </li>
            @endif
            <li class="heading">
                <h3 class="uppercase">Report</h3>
            </li>
            {{--<li class="nav-item {{ Request::is('aroperformance') ? 'active' : '' }} ">
                <a href="{{ url('aroperformance') }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">ARO Performance</span>
                </a>
            </li>--}}
            <li class="nav-item {{ Request::is('configuration/ba') ? 'active' : '' }} ">
                <a href="{{ url('configuration/ba') }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">Konfigurasi BA</span>
                </a>
            </li>
            <li class="nav-item {{ Request::is('configuration/store') ? 'active' : '' }} ">
                <a href="{{ url('configuration/store') }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">Konfigurasi Store</span>
                </a>
            </li>
            @if(Auth::user()->role != 'loreal')
            {{--<li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-bar-chart"></i>
                    <span class="title">Head Account</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="nav-item  ">
                        <a href="{{ route('hcAll') }}" class="nav-link ">
                            <span class="title">All Data</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="{{ route('hcCPD') }}" class="nav-link ">
                            <span class="title">IN OUT CPD</span>
                        </a>
                    </li>
                    <li class="nav-item  ">
                        <a href="layout_sidebar_fixed.html" class="nav-link ">
                            <span class="title">IN OUT MDS</span>
                        </a>
                    </li>
                </ul>
            </li>--}}
            @endif

            <li class="heading">
                <h3 class="uppercase">Utilitas</h3>
            </li>
            <li class="nav-item  ">
                <a href="{{ url('utilities/users') }}" class="nav-link nav-toggle">
                    <i class="icon-user"></i>
                    <span class="title">User</span>
                </a>
            </li>
            @if(Auth::user()->role != 'loreal')
            <li class="nav-item  ">
                <a href="{{ url('utilities/support') }}" class="nav-link nav-toggle">
                    <i class="icon-diamond"></i>
                    <span class="title">Support</span>
                </a>
            </li>
            @endif
            <li class="nav-item  ">
                <a href="javascript:;" class="nav-link nav-toggle">
                    <i class="icon-puzzle"></i>
                    <span class="title">Audit Trail</span>
                    <span class="arrow"></span>
                </a>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->