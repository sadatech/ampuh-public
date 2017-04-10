@extends('layouts.app')
<meta name="csrf-token" content="<?= csrf_token() ?>">


@section('additional-css')
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
@stop
@section('content')
    <div id="app">
        <div class="page-content">
            <div class="portlet light">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-line-chart font-purple-plum"></i>
                        <span class="caption-subject bold font-blue-hoki uppercase"> Filter Report</span>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row filter">
                        <div class="col-md-3">
                            <select id="filterName" class="select2-container--bootstrap.input-lg" ></select>
                        </div>
                        {{--<div class="col-md-3">--}}
                            {{--<select id="filterCustomerId" class="select2-container--bootstrap.input-lg" ></select>--}}
                        {{--</div>--}}
                        <div class="col-md-3">
                            <select id="filterStoreName" class="select2-container--bootstrap.input-lg" ></select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterProvince" class="select2-container--bootstrap.input-lg" ></select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterChannel" class="select2-container--bootstrap.input-lg" >
                                <option value="" selected></option>
                                <option value="MTKA Hyper/Super"> MTKA Hyper/Super</option>
                                <option value="Dept Store"> Dept Store</option>
                                <option value="Mensa"> Mensa</option>
                                <option value="Drug Store"> Drug Store</option>
                                <option value="GT/MTI"> GT/MTI</option>
                            </select>
                        </div>
                    </div>
                    <div class="row filter">
                        <div class="col-md-3">
                            <select id="filterBrand" class="select2-container--bootstrap.input-lg" >
                                <option value="" selected></option>
                                <option value="CONS"> CONS</option>
                                <option value="OAP"> OAP</option>
                                <option value="GAR"> GAR</option>
                                <option value="MYB"> MYB</option>
                                <option value="MIX"> MIX</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterAccount" class="select2-container--bootstrap.input-lg"></select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterRegion" class="select2-container--bootstrap.input-lg" >
                                <option value="" selected></option>
                                <option value="1"> 1</option>
                                <option value="2"> 2</option>
                                <option value="3"> 3</option>
                                <option value="4"> 4</option>
                                <option value="5"> 5</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterReo" class="select2-container--bootstrap.input-lg"></select>
                        </div>
                        {{--<div class="col-md-3">--}}
                            {{--<select id="filterMonth" class="select2-container--bootstrap.input-lg select2" v-model="selectedMonth">--}}
                                {{--<option v-for="month in months" :value="month.id" :selected="isCurrentMonth(month.id)"> @{{ month.name }}</option>--}}
                            {{--</select>--}}
                        {{--</div>--}}

                        {{--<div class="col-md-3">--}}
                            {{--<select id="filterCity" class="select2-container--bootstrap.input-lg" ></select>--}}
                        {{--</div>--}}
                    </div>
                    <div class="row filter">
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="filterBulan" placeholder="Month" v-model="currentMonth">
                        </div>
                    </div>
                    <div class="btn-group">
                        <a href="javascript:;" class="btn red-pink" @click.prevent="resetFilter">
                            <i class="fa fa-refresh"></i> Reset </a>
                        <a href="javascript:;" class="btn blue-hoki" @click.prevent="filteringReport">
                            <i class="fa fa-filter"></i> Filter </a>
                    </div>

                </div>
            </div>
            <div class="portlet light" v-show="showingReport">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-line-chart font-purple-plum"></i>
                        <span class="caption-subject bold font-blue-hoki uppercase"> Ba Configuration</span>
                    </div>
                    <div class="actions">
                        <a :href="exportReportUrl" class="btn green-dark" >
                            <i class="fa fa-cloud-download"></i> Download Excel </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-bordered" id="configBaTable">
                        <thead>
                        <tr>
                            <th> No </th>
                            <th> Year </th>
                            <th> Month </th>
                            <th> Nip </th>
                            <th> No Ktp </th>
                            <th> Provinsi </th>
                            <th> Nama BA </th>
                            <th> No Hp </th>
                            <th> Kota </th>
                            <th> Cabang Arina </th>
                            <th> Nama SS</th>
                            <th> Region </th>
                            <th> Brand </th>
                            <th> Store No </th>
                            <th> Customer Id </th>
                            <th> Store Name 1 </th>
                            <th> Store Name 2 </th>
                            <th> Channel </th>
                            <th> Account </th>
                            <th> Status Ba </th>
                            <th> Join Date </th>
                            <th> Join Date MDS </th>
                            <th> Agency </th>
                            <th> Size Baju </th>
                            <th> Keterangan </th>
                            <th> Masa Kerja </th>
                            <th> Class </th>
                            <th> Jenis Kelamin </th>
                            <th> Status Sp </th>
                            <th> Tanggal Sp </th>
                            <th> Hc </th>
                        </tr>
                        </thead>
                    </table>

                </div>
            </div>
        </div>
    </div>
        @stop
@section('additional-script')
    <script src="/js/vue.js"></script>
    <script src="/js/vue-resource.js"></script>
    <script src="/js/vue-table.js"></script>
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script>
        var tableColumns = [
            { title: 'NO', name: 'id', data: 'id'},
            { title: 'YEAR', name: 'year', data: 'year'},
            { title: 'MONTH', name: 'month', data: 'month', sortField: 'month'},
            { title: 'NIP', name: 'ba.nik', data: 'ba.nik', defaultContent: '-'},
            { title: 'NO KKTP', name: 'ba.no_ktp', data: 'ba.no_ktp', class: 'namewrapper', defaultContent: '-' },
            { title: 'PROVINCE', name: 'store.city.province_name', data: 'store.city.province_name', class: 'namewrapper', defaultContent: ' ' },
            { title: 'BA NAME', name: 'ba.name', data: 'ba.name', class: 'namewrapper', defaultContent: 'vacant' },
            { title: 'NO HP', name: 'ba.no_hp', data: 'ba.no_hp', class: 'namewrapper', defaultContent: '-' },
            { title: 'CITY', name: 'store.city.city_name', data: 'store.city.city_name', class: 'namewrapper', defaultContent: ' ' },
            { title: 'ARINA BRANCH', name: 'ba.arina_brand.cabang', data: 'ba.arina_brand.cabang', class: 'namewrapper', defaultContent: '-' },
            { title: 'SS NAME', name: 'store.reo.user.name', data: 'store.reo.user.name', class: 'namewrapper', defaultContent: ' '},
            { title: 'REGION', name: 'store.region_id', data: 'store.region.name', sortField: 'region_name', defaultContent: ' ' },
            { title: 'BRAND', name: 'brand.name', data: 'brand.name', defaultContent: ' ' },
            { title: 'STORE NO', name: 'store.store_no', data: 'store.store_no', class: 'namewrapper', defaultContent: ' ' },
            { title: 'CUSTOMER ID', name: 'store.customer_id', data: 'store.customer_id', defaultContent: ' ' },
            { title: 'STORE NAME 1', name: 'store.store_name_1', data: 'store.store_name_1', class: 'namewrapper', defaultContent: ' ' },
            { title: 'STORE NAME 2', name: 'store.store_name_2', data: 'store.store_name_2', class: 'namewrapper', defaultContent: ' ' },
            { title: 'CHANNEL', name: 'store.channel', data: 'store.channel', class: 'namewrapper', defaultContent: ' ' },
            { title: 'ACCOUNT', name: 'store.account.name', data: 'store.account.name', defaultContent: '', class: 'namewrapper' },
            { title: 'STATUS BA', name: 'ba.status', data: 'ba.status',class: 'namewrapper', defaultContent: '-' },
            { title: 'JOIN DATE', name: 'ba.join_date', data: 'ba.join_date', class: 'namewrapper', defaultContent: '-' },
            { title: 'JOIN DATE MDS', name: 'ba.join_date_mds', data: 'ba.join_date_mds', class: 'namewrapper', defaultContent: '-' },
            { title: 'AENCY', name: 'ba.agency.name', data: 'ba.agency.name', class: 'namewrapper', defaultContent: '-' },
            { title: 'UNIFORM SIZE', name: 'ba.uniform_size', data: 'ba.uniform_size', defaultContent: '-' },
            { title: 'STORE(s)', name: 'ba.description', data: 'ba.description', class: 'namewrapper', defaultContent: '-' },
            { title: 'WORKING TIME', name: 'ba.join_date', data: 'masa_kerja', class: 'namewrapper', defaultContent: '-' },
            { title: 'CLASS', name: 'ba.class', data: 'ba.class', defaultContent: '-' },
            { title: 'GENDER', name: 'ba.gender', data: 'ba.gender', defaultContent: '-' },
            { title: 'STATUS SP', name: 'ba.status_sp', data: 'ba.status_sp', class: 'namewrapper', defaultContent: ' ' },
            { title: 'DATE SP', name: 'ba.tanggal_sp', data: 'ba.tanggal_sp', class: 'namewrapper', defaultContent: ' ' },
            { title: 'HC', name: 'store_count', data: 'store_count', defaultContent: ' ' },
        ];
        new Vue({
            el: '#app',
            data: {
                showingReport: false,
                moreParams: ['month=' + moment().format('M')],
                exportReportUrl: '/exportBa/?month=' + moment().format('M'),
                filters: {},
                selectedMonth: '',
                months: [
                    {name:'January', id: 1 },
                    {name:'February', id: 2 },
                    {name:'March', id: 3 },
                    {name:'April', id: 4 },
                    {name:'May', id: 5 },
                    {name:'June', id: 6 },
                    {name:'July', id: 7 },
                    {name:'August', id: 8 },
                    {name:'September', id: 9 },
                    {name:'October', id: 10 },
                    {name:'November', id: 11 },
                    {name:'December', id: 12 }
                ],
                moreParamsPost: {
                    month: moment().format('M'),
                    year: moment().format('YYYY')
                },
                currentMonth: `${moment().format('MMMM')} ${moment().format('YYYY')}`
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
                        if($.fn.dataTable.isDataTable('#configBaTable')){
                            $('#configBaTable').DataTable().clear();
                            $('#configBaTable').DataTable().destroy();
                        }
                        $('#configBaTable').dataTable({
                            "fnCreatedRow": function( nRow, data ) {
                                $(nRow).attr('class', data.id);
                            },
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                url: '/baDataTable',
                                data: self.moreParamsPost,
                                type: 'POST'
                            },
                            columns: tableColumns
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
                    this.exportReportUrl = '/exportBa/?';
                    for (var filter in this.filters) {
                        this.exportReportUrl += filter + '=' + this.filters[filter] + '&';
                    }
                    console.log(this.exportReportUrl)
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
                    this.exportReportUrl = '/exportBa/?month=' + moment().format('M') + '&year=' + moment().format('YYYY');
                },
                isCurrentMonth: function (value) {
                    return (moment().format('M') == value) ? 'selected' : '';
                },
                monthFormat: function (value) {
                    if (isNaN(value)) {
                        return value;
                    }
                    return this.months.filter(function (month) {
                        return month.id == value;
                    }).map(function (month) {
                        return month.name
                    })
                },
                triggerReset: function () {
                    let filterId = ['#filterName', '#filterCustomerId', '#filterProvince', '#filterChannel', '#filterAccount', '#filterRegion', '#filterBrand', '#filterReo', '#filterStoreName']
                    filterId.map((id) => {
                        $(id).val('').trigger('change')
                    });
                }
            },
            watch: {
                currentMonth (val) {
                    let splited = val.split(' ')
                    let monthId = this.months.filter(item => item.name == splited[0]).map(item => item.id)[0]
                    this.moreParamsPost['month'] = monthId;
                    this.moreParamsPost['year'] = splited[2];
                    this.selected('month', monthId)
                    this.selected('year', splited[2])
                }
            },
            ready: function () {
                var self = this;
                this.selected('month', moment().format('M'));
                this.selected('year', moment().format('YYYY'));
                $(document).ready(function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $("#filterName").select2(self.setOptions('/baFilter', 'BA Name', function (params) {
                        return self.filterData('name', params.term);
                    }, function (data, params) {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.name}
                            })
                        }
                    }));
                    $('#filterName').on('select2:select', function () {
                        self.selected('name', $('#filterName').val());
                    })
//                    $('#filterCustomerId').select2(self.setOptions('/storeFilter', 'Customer Id', function (params) {
//                        return self.filterData('customerId', params.term);
//                    }, function (data, params) {
//                        return {
//                            results: $.map(data, function (obj) {
//                                return {id: obj.id, text: obj.customer_id}
//                            })
//                        }
//                    }));
//                    $(`#filterCustomerId`).on('select2:select', function () {
//                        self.selected('customerId', $('#filterCustomerId').val());
//                    })
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

                    $('#filterAccount').select2(self.setOptions('/accountFilter', 'Account', (params) => {
                        return self.filterData('account', params.term)
                    }, (data) => {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.name}
                            })
                        }
                    }));

                    $('#filterAccount').on('select2:select', () => {
                        self.selected('account', $('#filterAccount').val());
                    })

                    $('#filterMonth').select2({
                        width: '100%',
                        placeholder: 'Select Configuration Month'
                    })
                    $(`#filterMonth`).on('select2:select', function () {
                        self.selected('month', $('#filterMonth').val());
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

                    $('#filterRegion').select2({width: '100%', placeholder: 'Region'})

                    $('#filterRegion').on('select2:select', () => {
                        self.selected('region', $('#filterRegion').val())
                    })

                    $('#filterReo').select2(self.setOptions('/reoFilter', 'Nama SS', (params) => {
                        return self.filterData('namaReo', params.term)
                    }, (data) => {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.user.name}
                            })
                        }
                    }));

                    $('#filterReo').on('select2:select', () => {
                        self.selected('namaReo', $('#filterReo').val());
                    })

                    $('#filterBulan').datepicker({
                        format: "MM  yyyy",
                        startView: 1,
                        minViewMode: 1,
                        maxViewMode: 2,
                        keyboardNavigation: false,
                        calendarWeeks: true,
                        autoClose: true,
                        todayHighlight: true,
                        orientation: "bottom auto",
                    });
                })
        }
    })
    </script>

@endsection