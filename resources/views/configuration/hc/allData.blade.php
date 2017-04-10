@extends('layouts.app')

@section('additional-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.css">
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
    <style>
        th, td { white-space: nowrap; }
    </style>
@stop

@section('content')

    <div class="page-content" id="app">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Head Account</h1>
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Master</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Head Account</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">All Data</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-line-chart font-red"></i>
                            <span class="caption-subject bold font-red uppercase"> Head Account Filter </span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row filter">
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="filterBulan" placeholder="Month">
                            </div>
                            {{--<div class="col-md-3">--}}
                            {{--<select id="filterCity" class="select2-container--bootstrap.input-lg" ></select>--}}
                            {{--</div>--}}
                        </div>
                        <div class="btn-group">
                            <a href="javascript:;" class="btn red-pink" @click.prevent="resetFilter">
                                <i class="fa fa-refresh"></i> Reset </a>
                            <a href="javascript:;" class="btn blue-hoki" >
                                <i class="fa fa-filter"></i> Filter </a>
                        </div>

                    </div>
                </div>

                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Head Account</span>
                        </div>
                        <div class="actions">
                            <a href="{{ route('hcAlldataExport') }}" class="btn green-dark" >
                                <i class="fa fa-cloud-download"></i> Download Excel </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="masterBaTable">
                            <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th> Of Which Are </th>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th rowspan="2" style="vertical-align: middle"> </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th colspan="4" style="text-align: center"> BA </th>
                                <th rowspan="2" valign="middle">  </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th rowspan="2" style="vertical-align: middle">  </th>
                                <th rowspan="2" style="width: 120%; vertical-align: middle">  </th>
                            </tr>
                            <tr>
                                <th> Nik </th>
                                <th> L'Oreal Paris </th>
                                <th> Maybelline NY </th>
                                <th> Garnier </th>
                                <th> NYX </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>

@endsection

@section('additional-script')
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.js"></script>
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
    <script>

        $(document).ready(function () {
            $('#filterBulan').datepicker({
                format: "MM  yyyy",
                startView: 1,
                minViewMode: 1,
                maxViewMode: 2,
                keyboardNavigation: false,
                calendarWeeks: true,
                autoClose: true,
                todayHighlight: true,
                orientation: "bottom auto"
            });
            // Datatable setup
            $('#masterBaTable').removeAttr('width').dataTable({
                "fnCreatedRow": function (nRow, data) {
                    $(nRow).attr('class', data.id);
                },
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '{{ route('dtAll') }}',
                    methods: 'GET'
                },
                fixedColumns: true,
                columns: tableColumns
            });
        });

        let tableColumns = [
            {title: 'Nik', name: 'nik', data: 'nik', sortField: 'nik'},
            {title: 'Cost Center', name: 'store.account_id', data: 'costCenter', sortField: 'costCenter', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'Name', name: 'bas.name', data: 'name', sortField: 'baName', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'Position', name: 'position.name', data: 'position.name', sortField: 'baName', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'BA', name: 'store.account_id', data: 'ba', sortField: 'baName', searchAble: false, dataClass:'namewrapper', class:'namewrapper'},
            {title: 'Non BA', name: 'store.account_id', data: 'nonBa', sortField: 'baName', searchAble: false, dataClass:'namewrapper', class:'namewrapper'},
            {title: 'Distributor', name: 'store.account_id', data: 'distributor', sortField: 'baName', searchAble: false, dataClass:'namewrapper', class:'namewrapper'},
            {title: 'BA/BC/FC', name: 'gender', data: 'gender', sortField: 'gender'},
            {title: 'Birth Date', name: 'birth_date', data: 'birth_date', sortField: 'birth_date'},
            {title: 'Join Date', name: 'join_date', data: 'join_date', class:'namewrapper'},
            {title: 'Service Years', name: 'join_date', data: 'serviceYear', class:'namewrapper'},
            {title: 'Age', name: 'birth_date', data: 'age',sortField: 'cities.province_name', dataClass:'namewrapper', class:'namewrapper',defaultContent:''},
            {title: 'Masa Kerja', name: 'join_date', data: 'masa_kerja',sortField: 'cities.province_name', dataClass:'namewrapper', class:'namewrapper',defaultContent:''},
            {title: 'Service Years', name: 'join_date', data: 'serviceYears2',sortField: 'cities.province_name', dataClass:'namewrapper', class:'namewrapper',defaultContent:''},
            {title: 'Education', name: 'education', data: 'education', sortField: 'education'},
            {title: 'Division', name: 'division.name', data: 'division.name', sortField: 'division.name'},
            {title: 'Brand', name: 'brand.name', data: 'brand.name', sortField: 'brands.name'},
            {title: "L'Oreal Paris", name: 'brand_id', data: 'ba_loreal', sortField: 'brands.name'},
            {title: 'Maybelline', name: 'brand_id', data: 'ba_myb', sortField: 'brands.name'},
            {title: 'Garnier', name: 'brand_id', data: 'ba_gar', sortField: 'brands.name'},
            {title: 'NYX', name: 'brand_id', data: 'ba_loreal', sortField: 'brands.name'},
            {title: 'Area', name: 'area.name', data: 'area.name', sortField: 'brands.name'},
            {title: 'Channel', name: 'store.channel', data: 'channel', sortField: 'brands.name'},
            {title: 'Region', name: 'store.region_id', data: 'region', sortField: 'brands.name'},
            {title: 'Store', name: 'store.store_name_1', width: '30%', data: 'storeName', sortField: 'brands.name'},
        ]
    </script>
@stop
