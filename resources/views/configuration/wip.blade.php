@extends('layouts.app')

@section('additional-css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .numberOfBa{
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
                    <h1>WIP</h1>
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
                    <span class="active">WIP</span>
                </li>
            </ul>
            <!-- END PAGE BREADCRUMB -->
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="row">
                {{--<div class="col-md-12">--}}

                    {{--<div class="portlet light">--}}
                        {{--<div class="portlet-title">--}}
                            {{--<div class="caption">--}}
                                {{--<i class="fa fa-hourglass-half font-purple-plum"></i>--}}
                                {{--<span class="caption-subject bold font-blue-hoki uppercase"> Filter WIP</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                        {{--<div class="portlet-body">--}}
                            {{--<div class="row filter">--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<select id="filterStoreName" class="select2-container--bootstrap.input-lg"></select>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-3">--}}
                                    {{--<input type="text" class="form-control" id="filterBulan" placeholder="Month">--}}
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

                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="btn-group">--}}
                                {{--<a href="javascript:;" class="btn red-pink" @click.prevent="resetFilter">--}}
                                    {{--<i class="fa fa-refresh"></i> Reset </a>--}}
                                {{--<a href="javascript:;" class="btn blue-hoki" @click.prevent="filteringReport" >--}}
                                    {{--"--}}
                                    {{--<i class="fa fa-filter"></i> Search </a>--}}
                            {{--</div--}}

                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-blue"></i>
                            <span class="caption-subject font-blue sbold uppercase">WIP</span>
                        </div>
                        <div class="actions">
                            <a href="{{ route('wipExport') }}" class="btn green-dark" >
                                <i class="fa fa-cloud-download"></i> Download Excel </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-hover table-bordered" id="wipTable">
                            <thead>
                            <tr>
                                <th bgcolor="green"> NO </th>
                                <th> STORE </th>
                                <th> REGION </th>
                                <th> CITY </th>
                                <th> BRAND </th>
                                <th> ACCOUNT </th>
                                <th> CHANNEL </th>
                                <th> STATUS </th>
                                <th> REQUEST DATE </th>
                                <th> FIRST DAY </th>
                                {{--<th> Tanggal Kirim Kandidat </th>--}}
                                <th> INTERVIEW DATE</th>
                                <th> INTERVIEW STATUS </th>
                                <th> CANDIDATE </th>
                                <th> DESCRIPTION </th>
                                <th> HC </th>
                                @if(Auth::user()->role == 'aro')
                                    <th> EDIT </th>
                                @endif
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
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
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <script src="/js/wip.datatable.{{ (Auth::user()->role == 'aro') ? 'aro' : 'default' }}.js" type="text/javascript"></script>

    <script type="text/javascript">



        $(document).ready(function() {
            $('.date-picker').datepicker({
                autoclose: true
            });
            $(".store-ajax").select2({
                ajax: {
                    url: "{{ url('store/data') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, page) {
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
                            if($.fn.dataTable.isDataTable('#wipTable')){
                                $('#wipTable').DataTable().clear();
                                $('#wipTable').DataTable().destroy();
                            }
                            $('#wipTable').dataTable({
                                "fnCreatedRow": function( nRow, data ) {
                                    $(nRow).attr('class', data.id);
                                },
                                "processing": true,
                                "serverSide": true,
                                "ajax": {
                                    url: '{{ url('sdf/datatable') . (Request::exists('archived') ? '?archived' : '') }}',
                                    data: self.moreParamsPost,
                                    type: 'POST'
                                },
                                columns: oTable
                            })
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

                        $('#filterBrand').select2(self.setOptions('/provinceFilter', 'ARO', function (params) {
                            return self.filterData('province', params.term);
                        }, function (data, params) {
                            return {
                                results: $.map(data, function (obj) {
                                    return {id: obj.province_name, text: obj.province_name}
                                })
                            }
                        }));

                        $('#filterBrand').on('select2:select', function () {
                            self.selected('brand', $('#filterBrand').val())
                        })
                    })
                }
            })
        });
    </script>
@endsection