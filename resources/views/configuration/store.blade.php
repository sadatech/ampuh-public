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
                            <select id="filterCustomerId" class="select2-container--bootstrap.input-lg" ></select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterStoreName" class="select2-container--bootstrap.input-lg" ></select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterProvince" class="select2-container--bootstrap.input-lg" ></select>
                        </div>
                        <div class="col-md-3">
                            <select id="filterReo" class="select2-container--bootstrap.input-lg"></select>
                        </div>
                    </div>
                    <div class="row filter">
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
                        <span class="caption-subject bold font-blue-hoki uppercase"> Store Configuration</span>
                    </div>
                    <div class="actions">
                        <a :href="exportStoreConfiguration" class="btn green-dark" >
                            <i class="fa fa-cloud-download"></i> Download Excel </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-hover table-bordered" id="configStoreTable">
                        <thead>
                        <tr>
                            <th> NO </th>
                            <th> YEAR </th>
                            <th> MONTH </th>
                            <th> STORE NO </th>
                            <th> CUSTOMER ID </th>
                            <th> STORE NAME 1 </th>
                            <th> STORE NAME 2 </th>
                            <th> CHANNEL </th>
                            <th> ACCOUNT </th>
                            <th> PROVINCE </th>
                            <th> ALOCATION BA OAP </th>
                            <th> ALOCATION BA MYB </th>
                            <th> ALOCATION BA GAR </th>
                            <th> ALOCATION BA CONS </th>
                            <th> ALOCATION BA MIX </th>
                            <th> ACTUAL BA OAP </th>
                            <th> ACTUAL BA MYB </th>
                            <th> ACTUAL BA GAR </th>
                            <th> ACTUAL BA CONS </th>
                            <th> ACTUAL BA MIX </th>
                            <th> CITY </th>
                            <th> NAME SS</th>
                            <th> REGION </th>
                        </tr>
                        </thead>
                    </table>

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
        new Vue({
            el: '#app',
            data: {
                showingReport: false,
                moreParams: ['month=' + moment().format('M')],
                exportStoreConfiguration: '/exportStore/?month=' + moment().format('M'),
                filters: { month: moment().format('M')},
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
                currentMonth: `${moment().format('MMMM')} ${moment().format('YYYY')}`
            },
            computed: {
              tableColumns: function () {
                  if (parseInt(moment().format('M')) === parseInt(this.filters.month)) {
                      return [
                          { name: 'id', data: 'id', class: 'namewrapper'},
                          { name: 'year', data: 'year', class: 'namewrapper', orderable: false, searchable: false},
                          { name: 'month', data: 'month', class: 'namewrapper', orderable: false, searchable: false},
                          { name: 'store_no', data: 'store_no', class: 'namewrapper' },
                          { name: 'customer_id', data: 'customer_id', class: 'namewrapper' },
                          { name: 'store_name_1', data: 'store_name_1', class: 'namewrapper' },
                          { name: 'store_name_2', data: 'store_name_2', class: 'namewrapper' },
                          { name: 'channel', data: 'channel', class: 'namewrapper' },
                          { name: 'account.name', data: 'account.name', class: 'namewrapper', defaultContent: '' },
                          { name: 'city.province_name', data: 'city.province_name', class: 'namewrapper' },
                          { name: 'alokasi_ba_oap', data: 'alokasi_ba_oap', class: 'namewrapper' },
                          { name: 'alokasi_ba_myb', data: 'alokasi_ba_myb', class: 'namewrapper' },
                          { name: 'alokasi_ba_gar', data: 'alokasi_ba_gar', class: 'namewrapper' },
                          { name: 'alokasi_ba_cons', data: 'alokasi_ba_cons', class: 'namewrapper' },
                          { name: 'alokasi_ba_mix', data: 'alokasi_ba_mix', class: 'namewrapper' },
                          { name: 'oap_count', data: 'oap_count', class: 'namewrapper' },
                          { name: 'myb_count', data: 'myb_count', class: 'namewrapper' },
                          { name: 'gar_count', data: 'gar_count', class: 'namewrapper' },
                          { name: 'cons_count', data: 'cons_count', class: 'namewrapper' },
                          { name: 'mix_count', data: 'mix_count', class: 'namewrapper' },
                          { name: 'city.city_name', data: 'city.city_name', class: 'namewrapper' },
                          { name: 'reo.user.name', data: 'reo.user.name', class: 'namewrapper', defaultContent: ''},
                          { name: 'region.name', data: 'region.name',  }
                      ]
                  }
                  return [
                      { name: 'id', data: 'id', class: 'namewrapper'},
                      { name: 'year', data: 'year', class: 'namewrapper', orderable: false, searchable: false},
                      { name: 'month', data: 'month', class: 'namewrapper', orderable: false, searchable: false},
                      { name: 'store.store_no', data: 'store.store_no', class: 'namewrapper' },
                      { name: 'store.customer_id', data: 'store.customer_id', class: 'namewrapper' },
                      { name: 'store.shipping_id', data: 'store.shipping_id', class: 'namewrapper'},
                      { name: 'store.store_name_1', data: 'store.store_name_1', class: 'namewrapper' },
                      { name: 'store.store_name_2', data: 'store.store_name_2', class: 'namewrapper' },
                      { name: 'store.channel', data: 'store.channel', class: 'namewrapper' },
                      { name: 'store.account.name', data: 'store.account.name', class: 'namewrapper', defaultContent: '' },
                      { name: 'store.city.province_name', data: 'store.city.province_name', class: 'namewrapper' },
                      { name: 'alokasi_ba_oap', data: 'alokasi_ba_oap', class: 'namewrapper' },
                      { name: 'alokasi_ba_myb', data: 'alokasi_ba_myb', class: 'namewrapper' },
                      { name: 'alokasi_ba_gar', data: 'alokasi_ba_gar', class: 'namewrapper' },
                      { name: 'alokasi_ba_cons', data: 'alokasi_ba_cons', class: 'namewrapper' },
                      { name: 'alokasi_ba_mix', data: 'alokasi_ba_mix', class: 'namewrapper' },
                      { name: 'oap_count', data: 'oap_count', class: 'namewrapper' },
                      { name: 'myb_count', data: 'myb_count', class: 'namewrapper' },
                      { name: 'gar_count', data: 'gar_count', class: 'namewrapper' },
                      { name: 'cons_count', data: 'cons_count', class: 'namewrapper' },
                      { name: 'mix_count', data: 'mix_count', class: 'namewrapper' },
                      { name: 'store.city.city_name', data: 'store.city.city_name', class: 'namewrapper' },
                      { name: 'store.reo.user.name', data: 'store.reo.user.name', class: 'namewrapper', defaultContent: ''},
                      { name: 'store.region_id', data: 'store.region_id' }
                  ]
              }
            },
            methods : {
                filteringReport: function () {
                    console.log(this.tableColumns)
                    this.showingReport = true;
                    this.moreParams = [];
                    for (var filter in this.filters) {
                        this.moreParams.push(filter + '=' + this.filters[filter]);
                    }
                    var self = this;
                    $(document).ready(function () {
                        if($.fn.dataTable.isDataTable('#configStoreTable')){
                            $('#configStoreTable').DataTable().clear();
                            $('#configStoreTable').DataTable().destroy();
                        }
                        $('#configStoreTable').dataTable({
                            "fnCreatedRow": function( nRow, data ) {
                                $(nRow).attr('class', data.id);
                            },
                            "processing": true,
                            "serverSide": true,
                            "ajax": {
                                url: '/configuration/storeData?' + self.moreParams.join('&'),
                                methods: 'GET'
                            },
                            columns: self.tableColumns
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
                    this.exportStoreConfiguration = '/exportStore/?';
                    for (var filter in this.filters) {
                        this.exportStoreConfiguration += filter + '=' + this.filters[filter] + '&';
                    }
                    console.log(this.exportStoreConfiguration)
                },
                filterData: function (search, term) {
                    let results = {};
                    if ($.isEmptyObject(this.filters)) {
                        return {
                            [search]: term
                        }
                    }

                    for (let filter in this.filters) {
                        results[filter] = this.filters[filter];
                        results[search] = term
                    }
                    return results;
                },
                resetFilter: function () {
                    let filterId = ['#filterCustomerId', '#filterProvince', '#filterChannel', '#filterAccount', '#filterRegion', '#filterReo', '#filterStoreName'];
                    filterId.map((id) => {
                        $(id).val('').trigger('change')
                    });
                    this.filters = { month: moment().format('M'), year: moment().format('YYYY')};
                    this.exportStoreConfiguration = '/exportStore/?month=' + moment().format('M') + '&year=' + moment().format('YYYY')
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
                }
            },
            watch: {
                currentMonth (val) {
                    let splited = val.split(' ')
                    let monthId = this.months.filter(item => item.name == splited[0]).map(item => item.id)[0]
                    this.selected('month', monthId)
                    this.selected('year', splited[2])
                }
            },
            ready: function () {
                let self = this;
                this.selected('month', moment().format('M'));
                this.selected('year', moment().format('YYYY'));
                $(document).ready(function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $("#filterName").select2(self.setOptions('/baFilter', 'Ba Name', function (params) {
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

                    $('#filterCustomerId').select2(self.setOptions('/storeFilter', 'Customer Id', function (params) {
                        return self.filterData('customerId', params.term);
                    }, function (data, params) {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.customer_id}
                            })
                        }
                    }));
                    $(`#filterCustomerId`).on('select2:select', function () {
                        self.selected('customerId', $('#filterCustomerId').val());
                    })


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

                    $('#filterReo').select2(self.setOptions('/reoFilter', 'Nama ROE', (params) => {
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

                    $('#filterChannel').select2({
                        width: '100%',
                        placeholder: 'Channel'
                    })

                    $('#filterChannel').on('select2:select', function () {
                        self.selected('channel', $('#filterChannel').val())
                    })

                    $('#filterRegion').select2({width: '100%', placeholder: 'Region'})

                    $('#filterRegion').on('select2:select', () => {
                        self.selected('region', $('#filterRegion').val())
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