@extends('layouts.app')
@section('content')

    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>BA Development
                    <small>configurations and headaccounts report</small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
            <!-- BEGIN PAGE TOOLBAR -->
            <div class="page-toolbar">
                <div id="dashboard-report-range" data-display-range="0" class="pull-right tooltips btn btn-fit-height green" data-placement="left" data-original-title="Change dashboard date range">
                    <i class="icon-calendar"></i>&nbsp;
                    <span class="thin uppercase hidden-xs"></span>&nbsp;
                    <i class="fa fa-angle-down"></i>
                </div>
                <!-- BEGIN THEME PANEL -->
                <div class="btn-group btn-theme-panel">
                    <a href="javascript:;" class="btn dropdown-toggle" data-toggle="dropdown">
                        <i class="icon-settings"></i>
                    </a>
                    <div class="dropdown-menu theme-panel pull-right dropdown-custom hold-on-click">
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <h3>HEADER</h3>
                                <ul class="theme-colors">
                                    <li class="theme-color theme-color-default active" data-theme="default">
                                        <span class="theme-color-view"></span>
                                        <span class="theme-color-name">Dark Header</span>
                                    </li>
                                    <li class="theme-color theme-color-light " data-theme="light">
                                        <span class="theme-color-view"></span>
                                        <span class="theme-color-name">Light Header</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-12 seperator">
                                <h3>LAYOUT</h3>
                                <ul class="theme-settings">
                                    <li> Theme Style
                                        <select class="layout-style-option form-control input-small input-sm">
                                            <option value="square">Square corners</option>
                                            <option value="rounded" selected="selected">Rounded corners</option>
                                        </select>
                                    </li>
                                    <li> Layout
                                        <select class="layout-option form-control input-small input-sm">
                                            <option value="fluid" selected="selected">Fluid</option>
                                            <option value="boxed">Boxed</option>
                                        </select>
                                    </li>
                                    <li> Header
                                        <select class="page-header-option form-control input-small input-sm">
                                            <option value="fixed" selected="selected">Fixed</option>
                                            <option value="default">Default</option>
                                        </select>
                                    </li>
                                    <li> Top Dropdowns
                                        <select class="page-header-top-dropdown-style-option form-control input-small input-sm">
                                            <option value="light">Light</option>
                                            <option value="dark" selected="selected">Dark</option>
                                        </select>
                                    </li>
                                    <li> Sidebar Mode
                                        <select class="sidebar-option form-control input-small input-sm">
                                            <option value="fixed">Fixed</option>
                                            <option value="default" selected="selected">Default</option>
                                        </select>
                                    </li>
                                    <li> Sidebar Menu
                                        <select class="sidebar-menu-option form-control input-small input-sm">
                                            <option value="accordion" selected="selected">Accordion</option>
                                            <option value="hover">Hover</option>
                                        </select>
                                    </li>
                                    <li> Sidebar Position
                                        <select class="sidebar-pos-option form-control input-small input-sm">
                                            <option value="left" selected="selected">Left</option>
                                            <option value="right">Right</option>
                                        </select>
                                    </li>
                                    <li> Footer
                                        <select class="page-footer-option form-control input-small input-sm">
                                            <option value="fixed">Fixed</option>
                                            <option value="default" selected="selected">Default</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END THEME PANEL -->
            </div>
            <!-- END PAGE TOOLBAR -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="index.html">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Dashboard</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 bordered">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-green-sharp">
                                <span data-counter="counterup" data-value="{{ $totalba  }}">0</span>
                            </h3>
                            <small>TOTAL BA</small>
                        </div>
                        <div class="icon">
                            <i class="icon-users"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 bordered">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-red-haze">
                                <span data-counter="counterup" data-value="{{ $totalstore }}">0</span>
                            </h3>
                            <small>TOTAL STORES</small>
                        </div>
                        <div class="icon">
                            <i class="icon-basket"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 bordered">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-blue-sharp">
                                <span data-counter="counterup" data-value="{{ $baIn }}"></span>
                            </h3>
                            <small>BA IN</small>
                        </div>
                        <div class="icon">
                            <i class="icon-user-follow"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="dashboard-stat2 bordered">
                    <div class="display">
                        <div class="number">
                            <h3 class="font-purple-soft">
                                <span data-counter="counterup" data-value="{{ $baOut }}"></span>
                            </h3>
                            <small>BA OUT</small>
                        </div>
                        <div class="icon">
                            <i class="icon-user-unfollow"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row">--}}
        {{--<div class="col-lg-6 col-xs-12 col-sm-12">--}}
        {{--<div class="portlet light bordered">--}}
        {{--<div class="portlet-title">--}}
        {{--<div class="caption">--}}
        {{--<span class="caption-subject bold uppercase font-dark">Revenue</span>--}}
        {{--<span class="caption-helper">distance stats...</span>--}}
        {{--</div>--}}
        {{--<div class="actions">--}}
        {{--<a class="btn btn-circle btn-icon-only btn-default" href="#">--}}
        {{--<i class="icon-cloud-upload"></i>--}}
        {{--</a>--}}
        {{--<a class="btn btn-circle btn-icon-only btn-default" href="#">--}}
        {{--<i class="icon-wrench"></i>--}}
        {{--</a>--}}
        {{--<a class="btn btn-circle btn-icon-only btn-default" href="#">--}}
        {{--<i class="icon-trash"></i>--}}
        {{--</a>--}}
        {{--<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="#"> </a>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="portlet-body">--}}
        {{--<div id="dashboard_amchart_1" class="CSSAnimationChart"></div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-lg-6 col-xs-12 col-sm-12">--}}
        {{--<div class="portlet light bordered">--}}
        {{--<div class="portlet-title">--}}
        {{--<div class="caption ">--}}
        {{--<span class="caption-subject font-dark bold uppercase">Finance</span>--}}
        {{--<span class="caption-helper">distance stats...</span>--}}
        {{--</div>--}}
        {{--<div class="actions">--}}
        {{--<a href="#" class="btn btn-circle green btn-outline btn-sm">--}}
        {{--<i class="fa fa-pencil"></i> Export </a>--}}
        {{--<a href="#" class="btn btn-circle green btn-outline btn-sm">--}}
        {{--<i class="fa fa-print"></i> Print </a>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--<div class="portlet-body">--}}
        {{--<div id="dashboard_amchart_3" class="CSSAnimationChart"></div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        {{--</div>--}}
        <div class="row">
            <div class="col-lg-{{(\Auth::user()->role == 'loreal') ? '12' : '6'}} col-xs-12 col-sm-12">
                <!-- BEGIN PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title tabbable-line">
                        <div class="caption">
                            <i class="icon-globe font-dark hide"></i>
                            <span class="caption-subject font-dark bold uppercase">Activities</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <!--BEGIN TABS-->
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1_1">
                                <div class="scroller" style="height: 339px;" data-always-visible="1" data-rail-visible="0">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th> Type </th>
                                            <th> Subject </th>
                                            <th> Date </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($activities->count() == 0)
                                            <tr>
                                                <td colspan="3" align="center"> No activities</td>
                                            </tr>
                                        @endif
                                        @foreach( $activities as $activity )
                                            <tr>
                                                <td> <i class="icon-layers"> </i> {{ explode("\\", $activity->type)[1] }} </td>
                                                <td> {{ $activity->activity }} </td>
                                                <td> {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }} </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--END TABS-->
                        </div>
                    </div>
                    <!-- END PORTLET-->
                </div>
            </div>
            @if(\Auth::user()->role != 'loreal')
                <div class="col-lg-6 col-xs-12 col-sm-12">
                    <!-- BEGIN PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title tabbable-line">
                            <div class="caption">
                                <i class="icon-globe font-dark hide"></i>
                                <span class="caption-subject font-dark bold uppercase">TO DO</span>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <!--BEGIN TABS-->
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1_1">
                                    <div class="scroller" style="height: 339px;" data-always-visible="1" data-rail-visible="0">
                                        <ul class="feeds">
                                            @foreach( $todos as $todo)
                                                <li>
                                                    <div class="col1">
                                                        <div class="cont">
                                                            <div class="cont-col1">
                                                                <div class="label label-sm label-success">
                                                                    <i class="{{ is_null($todo['icon']) ? 'fa fa-plus' : $todo['icon'] }}"></i>
                                                                </div>
                                                            </div>
                                                            <div class="cont-col2">
                                                                <div class="desc">
                                                                    {!! $todo['title']  !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--END TABS-->
                        </div>
                    </div>
                    <!-- END PORTLET-->
                </div>
            @endif
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection