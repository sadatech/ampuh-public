@extends('layouts.app')

@section('additional-css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/apps/css/ticket.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .numberOfBa {
            width: 64px;
            max-width: 64px;
        }
    </style>
    <!-- END PAGE LEVEL PLUGINS -->
@stop

@section('content')

    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Support</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Utilities</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Support</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PROFILE SIDEBAR -->
                <div class="profile-sidebar">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet bordered">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <img src="/assets/pages/media/profile/profile_user.jpg" class="img-responsive" alt="">
                        </div>
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name"> Marcus Doe</div>
                            <div class="profile-usertitle-job"> Developer</div>
                        </div>
                        <div class="profile-usermenu">
                            <ul class="nav">
                                <li>
                                    <a href="{{ url('utilities/support') }}">
                                        <i class="icon-home"></i> Ticket List </a>
                                </li>
                                <li class="active">
                                    <a href="{{ url('utilities/support_staff') }}">
                                        <i class="icon-settings"></i> Support Staff </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MENU -->
                    </div>
                    <!-- END PORTLET MAIN -->
                </div>
                <!-- END BEGIN PROFILE SIDEBAR -->
                <!-- BEGIN TICKET LIST CONTENT -->
                <div class="app-ticket app-ticket-list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Ticket SUPPORT STAFF</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                           id="suport_staff">
                                        <thead>
                                        <tr>
                                            <th>
                                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                    <input type="checkbox" class="group-checkable"
                                                           data-set="#sample_1 .checkboxes"/>
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th> ID #</th>
                                            <th> Name</th>
                                            <th> Email</th>
                                            <th> Role</th>
                                            <th> Join Date</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PROFILE CONTENT -->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>

@endsection

@section('additional-script')
    <script type="text/javascript">
        {{--function here--}}
        $(document).ready(function () {
            var table = $('#suport_staff').dataTable({
                "fnCreatedRow": function (nRow, data) {
                    $(nRow).attr('class', data.id);
                },
                "processing": true,
                "serverSide": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 75, 100],
                "ajax": {
                    url: '{{ url('utilities/openticket/datatablestaff') }}',
                    method: 'POST'
                },
                columns: [
                    {data: 'cek', name: 'cek', orderable: false},
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name', class: 'namewrapper'},
                    {data: 'email', name: 'email', class: 'namewrapper'},
                    {data: 'role', name: 'role', class: 'namewrapper'},
                    {data: 'created_at', name: 'created_at', class: 'namewrapper'}
                ]
            });

        });
    </script>
@endsection