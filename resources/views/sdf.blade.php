@extends('layouts.app')

@section('additional-css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet"
          type="text/css"/>
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

    <div id="app">
        <div class="page-content">
            <!-- BEGIN PAGE HEAD-->
            <div class="page-head">
                <!-- BEGIN PAGE TITLE -->
                <div class="page-title">
                    <h1>SDF</h1>
                </div>
                <!-- END PAGE TITLE -->
            </div>
            <!-- END PAGE HEAD-->
            <!-- BEGIN PAGE BREADCRUMB -->
            <ul class="page-breadcrumb breadcrumb">
                <li>
                    <a href="index.html">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <a href="#">Configuration</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span class="active">SDF</span>
                </li>
            </ul>
            <!-- END PAGE BREADCRUMB -->
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light portlet-fit bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-settings font-blue"></i>
                                <span class="caption-subject font-blue sbold uppercase">SDF</span>
                            </div>
                            <div class="actions">
                                <a href="/sdf/export{{ (Request::exists('archived') ? '?archived':'') }}" class="btn green-dark" >
                                    <i class="fa fa-cloud-download"></i> Download Excel </a>
                            </div>
                        </div>
                        {{--<div class="portlet-body">--}}

                            {{--<div class="caption">--}}
                                {{--<i class="fa fa-line-chart font-purple-plum"></i>--}}
                                {{--<span class="caption-subject bold font-blue-hoki uppercase"> Filter SDF</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        <div class="portlet-body">
                            {{--<div class="row filter">--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<select id="filterStoreName" class="select2-container--bootstrap.input-lg"></select>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<select id="filterProvince" class="select2-container--bootstrap.input-lg"></select>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<select id="filterChannel" class="select2-container--bootstrap.input-lg">--}}
                                        {{--<option value="" selected></option>--}}
                                        {{--<option value="MTKA Hyper/Super"> MTKA Hyper/Super</option>--}}
                                        {{--<option value="Dept Store"> Dept Store</option>--}}
                                        {{--<option value="Mensa"> Mensa</option>--}}
                                        {{--<option value="Drug Store"> Drug Store</option>--}}
                                        {{--<option value="GT/MTI"> GT/MTI</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<select id="filterBrand" class="select2-container--bootstrap.input-lg">--}}
                                        {{--<option value="" selected></option>--}}
                                        {{--<option value="CONS"> CONS</option>--}}
                                        {{--<option value="OAP"> OAP</option>--}}
                                        {{--<option value="GAR"> GAR</option>--}}
                                        {{--<option value="MYB"> MYB</option>--}}
                                        {{--<option value="MIX"> MIX</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="btn-group">--}}
                                {{--<a href="javascript:;" class="btn red-pink" @click.prevent="resetFilter">--}}
                                    {{--<i class="fa fa-refresh"></i> Reset </a>--}}
                                {{--<a href="javascript:;" class="btn blue-hoki" @click.prevent="filteringReport" >--}}
                                    {{--"--}}
                                    {{--<i class="fa fa-filter"></i> Search </a>--}}
                            {{--</div>--}}

                            {{--<hr />--}}
                            {{--</div>--}}
                            {{--</div>--}}

                            {{--end filter sdf--}}

                            <div class="table-toolbar" style="margin-top: 10px">
                                <div class="row">
                                    <div class="col-md-6">

                                        @if(Auth::user()->role == 'loreal')
                                            <a class="btn green" href="{{ route('newSdf') }}"> SDF Baru
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        @endif

                                        <a href="{{ url('sdf/') . (Request::exists('archived') ? '?alldata' : '?archived') }}"
                                           class="btn green-meadow"> {{(Request::exists('archived') ? 'All Data' : 'History') }}
                                            <i class="fa fa-archive"></i>
                                        </a>

                                    </div>
                                </div>
                            </div>

                            <table class="table table-striped table-hover table-bordered" id="sdfTable">
                                <thead>
                                <tr>
                                    <th width="1"> #</th>
                                    <th> NO SDF </th>
                                    <th> STORE </th>
                                    <th> CITY </th>
                                    <th> BRAND </th>
                                    <th> Number Of BA</th>
                                    <th> REQUEST DATE</th>
                                    <th> FIRST DAY</th>
                                    <th> CREATED BY</th>
                                    {{--<th> Created At</th>--}}
                                    @if(Request::exists('archived'))
                                        <th>FULLFILLED AT</th>
                                    @endif
                                    @if(!Request::exists('archived') )
                                        @if(\Auth::user()->role !== 'aro')
                                            <th width="1"> UPDATE </th>
                                            <th width="1"> HOLD </th>
                                        @endif
                                    @endif
                                    {{--<th> Ubah Status BA</th>--}}
                                    <th> <i class="fa fa-download"></i></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <!-- END PAGE BASE CONTENT -->
            </div>
        </div>
        <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <img src="/assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                        <span> &nbsp;&nbsp;Loading... </span>
                    </div>
                </div>
            </div>
        </div>

        @endsection

        @section('additional-script')
            <script src="/js/vue.js"></script>
            <script src="/js/vue-resource.js"></script>
            <script src="/js/vue-table.js"></script>
            <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
            <script src="/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
                    type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
                    type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
                    type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
                    type="text/javascript"></script>
            <script src="/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
            <script src="http://cdn.rawgit.com/ashl1/datatables-rowsgroup/v1.0.0/dataTables.rowsGroup.js"></script>
            <script type="text/javascript">
                function stay(id, brandId) {
                    swal({
                        title: "Anda yakin akan merubah status BA menjadi Stay ?",
                        type: "info",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    }, function () {

                        $.ajax(
                            {
                                url: "sdf/" + id + "/" + brandId + "/stay",
                                type: 'POST',
                                dataType: "JSON",
                                data: {
                                    "id": id,
                                    "brand_id": brandId,
                                    "_method": 'PUT',
                                },
                                success: function () {
                                    swal("Success!", "BA berhasil dirubah menjadi Stay!", "success");
                                    var url = "{{'sdf'}}";
                                    window.location = url;
                                }
                            });
                    });
                }

                function Archive(id) {
                    swal({
                        title: "Are you sure to archive ?",
                        type: "info",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true
                    }, function () {
                        $.ajax(
                            {
                                url: "sdf/" + id + "/delete",
                                type: 'POST',
                                dataType: "JSON",
                                data: {
                                    "id": id,
                                    "_method": 'DELETE',
                                },
                                success: function () {
                                    swal("Success!", "SDF Archived!", "success")
                                    $("." + id).remove();
                                }
                            });
                    });
                }

                function hold(id) {
                    swal({
                        title: "Are you sure to hold ?",
                        type: "warning",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        confirmButtonText: "Hold",
                        confirmButtonColor: "#DD6B55",
                        showLoaderOnConfirm: true
                    }, function () {
                        $.ajax(
                            {
                                url: "sdf/" + id + "/hold",
                                type: 'POST',
                                dataType: "JSON",
                                data: {
                                    "id": id,
                                },
                                success: function () {
                                    swal(
                                        'Hold!',
                                        'SDF has been on hold.',
                                        'success'
                                    )
                                },
                                error: function() {
                                    swal.close();
                                }
                            });
                    });
                }
                function unhold(id) {
                    swal({
                        title: "Are you sure to release sdf ?",
                        type: "warning",
                        showCancelButton: true,
                        closeOnConfirm: false,
                        confirmButtonText: "Release",
                        confirmButtonColor: "#DD6B55",
                        showLoaderOnConfirm: true
                    }, function () {
                        $.ajax(
                            {
                                url: "sdf/" + id + "/unhold",
                                type: 'POST',
                                dataType: "JSON",
                                data: {
                                    "id": id,
                                },
                                success: function (data) {
                                    swal(
                                        'Released!',
                                        'SDF has been released.',
                                        'success'
                                    )
                                    location.reload();
                                },
                                error: function() {
                                    swal.close();
                                }
                            });
                    });
                }
                $(document).ready(function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var table = $('#sdfTable').dataTable({
                        "fnCreatedRow": function (nRow, data) {
                            $(nRow).attr('class', data.id);
                            if( data.numberOfBa == 0.5 ) $(nRow).attr('style', 'background: #E9EDEF !important')
                            if( data.numberOfBa == 0.333333 ) $(nRow).attr('style', 'background: #E5E5E5 !important')
                            if( data.numberOfBa == 0.25 ) $(nRow).attr('style', 'background: #bfcad1 !important')

                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '{{ url('sdf/datatable') . (Request::exists('archived') ? '?archived' : '') }}',
                            method: 'POST'
                        },
                        columns: [
                            {data: 'idDetail', name: 'sdfs.token'},
                            {data: 'sdfDetail', name: 'sdfs.no_sdf'},
                            {data: 'store_name_1', name: 'stores.store_name_1', class: 'namewrapper'},
                            {data: 'city', name: 'cities.city_name', class: 'namewrapper'},
                            {data: 'brandName', name: 'brands.name'},
                            {data: 'numberOfBa', name: 'sdf_brands.numberOfBa'},
                            {data: 'request_date', name: 'sdfs.request_date'},
                            {data: 'first_date', name: 'sdfs.first_date'},
                            {
                                data: 'created_name',
                                name: 'created_name',
                                defaultContent: '',
                                class: 'namewrapper',
                                searchable: false
                            },

                                @if(Request::exists('archived'))
                            {
                                data: 'deleted_at', name: 'deleted_at', defaultContent: '', searchable: false
                            },
                                @endif
                                @if(!Request::exists('archived'))
                                @if(\Auth::user()->role !== 'aro')
                            {data: 'update', name: 'update', orderable: false, searchable: false},
                            {data: 'hold', name: 'hold', orderable: false, searchable: false},
                                @endif
                                @endif
                            {data: 'download', name: 'download', orderable: false, searchable: false}
                        ],

                        rowsGroup: [
                            'sdfs.token:name',
                            0,
                            2
                        ],
                    });

                    $('.date-picker').datepicker({
                        autoclose: true
                    });
                    $(".store-ajax").select2({
                        ajax: {
                            url: "{{ url('store/data') }}",
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page
                                };
                            },
                            processResults: function (data, page) {
                                return {
                                    results: $.map(data, function (obj) {
                                        return {id: obj.id, text: obj.store_name_1}
                                    })
                                };
                            },
                            cache: true
                        },
                        minimumInputLength: 2,
                    });

                });


                function mobile(id) {
                    window.location = `/newSdf/?${window.btoa(id)}`;
                }

                var tableColumns=[
                    {data: 'idDetail', name: 'sdfs.token'},
                    {data: 'sdfDetail', name: 'sdfs.no_sdf'},
                    {data: 'store_name_1', name: 'stores.store_name_1', class: 'namewrapper'},
                    {data: 'city', name: 'cities.city_name', class: 'namewrapper'},
                    {data: 'brandName', name: 'brands.name'},
                    {data: 'numberOfBa', name: 'sdf_brands.numberOfBa'},
                    {data: 'request_date', name: 'sdfs.request_date'},
                    {data: 'first_date', name: 'sdfs.first_date'},
                    {
                        data: 'created_name',
                        name: 'created_name',
                        defaultContent: '',
                        class: 'namewrapper',
                        searchable: false
                    },

                        @if(Request::exists('archived'))
                    {
                        data: 'deleted_at', name: 'deleted_at', defaultContent: '', searchable: false
                    },
                        @endif
                        @if(!Request::exists('archived'))
                        @if(\Auth::user()->role !== 'aro')
                    {data: 'update', name: 'update', orderable: false, searchable: false},
                    {data: 'hold', name: 'hold', orderable: false, searchable: false},
                        @endif
                        @endif
                    {data: 'download', name: 'download', orderable: false, searchable: false}
                ];
                new Vue({
                    el: '#app',
                    data: {
                        showingReport: false,
                        filters: {},
                    },
                    methods: {
                        filteringReport: function () {
                            this.showingReport = true;
                            this.moreParams = [];
                            this.moreParamsPost  = {}
                            for (let filter in this.filters) {
                                this.moreParams.push(filter + '=' + this.filters[filter]);
                                this.moreParamsPost[filter] = this.filters[filter];
                            }
                            var self = this;
                            $(document).ready(function () {
                                console.log(self.moreParamsPost);
                                if($.fn.dataTable.isDataTable('#sdfTable')){
                                    $('#sdfTable').DataTable().clear();
                                    $('#sdfTable').DataTable().destroy();
                                }
                                var dataTable= $('#sdfTable').dataTable({
                                    bRetrieve: true,
                                    "fnCreatedRow": function (nRow, data) {
                                        $(nRow).attr('class', data.id);
                                        if( data.numberOfBa == 0.5 ) $(nRow).attr('style', 'background: #E9EDEF !important')
                                        if( data.numberOfBa == 0.333333 ) $(nRow).attr('style', 'background: #E5E5E5 !important')
                                        if( data.numberOfBa == 0.25 ) $(nRow).attr('style', 'background: #bfcad1 !important')

                                    },
                                    "processing": true,
                                    "serverSide": true,

                                    "ajax": {
                                        url: '{{ url('sdf/datatable') . (Request::exists('archived') ? '?archived' : '') }}',
                                        method: 'POST',
                                        data: self.moreParamsPost
                                    },
                                    columns: tableColumns
                                })
                                dataTable.fnDraw();
                            })
                        },
                        setOptions (url, placeholder, data, processResults) {
                            return {
                                ajax: {
                                    url: url,
                                    method: 'POST',
                                    dataType: 'json',
                                    delay: 250,
                                    data: data,
                                    processResults: processResults
                                },
                                minimumInputLength: 2,
                                width: '100%',
                                placeholder: placeholder
                            }
                        },
                        selected: function (key, val) {
                            this.filters[key] = val;
                        },
                        filterData: function (search, term) {
                            var results = {};
                            if ($.isEmptyObject(this.filters)) {
                                return {
                                    [search]: term
                                }
                            }

                            for (var filter in this.filters) {
                                results[filter] = this.filters[filter];
                                results[search] = term
                            }
                            return results;
                        },
                        resetFilter: function () {
                            this.triggerReset()
                            this.filters = { month: moment().format('M'),  year: moment().format('YYYY')};
                        },
                        triggerReset: function () {
                            let filterId = ['#filterStoreName','#filterProvince', '#filterChannel', '#filterBrand']
                            filterId.map((id) => {
                                $(id).val('').trigger('change')
                            });
                        }
                    },
                    ready: function () {
                        var self = this;
                        $(document).ready(function () {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $('#filterStoreName').select2(self.setOptions('/storeFilter', 'Store Name', function (params) {
                                return self.filterData('storeName', params.term);
                            }, function (data, params) {
                                return {
                                    results: $.map(data, function (obj) {
                                        return {id: obj.id, text: obj.store_name_1}
                                    })
                                }
                            }));
                            $(`#filterStoreName`).on('select2:select', function () {
                                self.selected('storeName', $('#filterStoreName').val());
                            })
                            $('#filterProvince').select2(self.setOptions('/provinceFilter', 'Province', function (params) {
                                return self.filterData('province', params.term);
                            }, function (data, params) {
                                return {
                                    results: $.map(data, function (obj) {
                                        return {id: obj.province_name, text: obj.province_name}
                                    })
                                }
                            }));
                            $(`#filterProvince`).on('select2:select', function () {
                                self.selected('province', $('#filterProvince').val());
                            })

                            $('#filterChannel').select2({
                                width: '100%',
                                placeholder: 'Channel'
                            })

                            $('#filterChannel').on('select2:select', function () {
                                self.selected('channel', $('#filterChannel').val())
                            })

                            $('#filterBrand').select2({
                                width: '100%',
                                placeholder: 'Brand'
                            })

                            $('#filterBrand').on('select2:select', function () {
                                self.selected('brand', $('#filterBrand').val())
                            })
                        })
                    }
                })
            </script>

    {{--filter copy dari semmy--}}
@endsection