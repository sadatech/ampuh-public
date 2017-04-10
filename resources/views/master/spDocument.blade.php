@extends('layouts.app')

@section('additional-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.css">
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
@stop

@section('content')

    <div class="page-content" id="app">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>BA</h1>
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
                <span class="active">Sp Document Ba</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
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
                                <select id="filterSP" class="select2-container--bootstrap.input-lg" >
                                    <option >Tipe Sp</option>
                                    <option value="SP1">SP1</option>
                                    <option value="SP2">SP2</option>
                                    <option value="SP3">SP3</option>
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

            </div>

            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Data Document Sp BA</span>
                        </div>
                        <div class="actions">
                            <a :href="exportReportUrl" class="btn green-dark" >
                                <i class="fa fa-cloud-download"></i> Download Excel </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-hover table-bordered" id="documentSpTable">
                            <thead>
                            <tr>
                                <th> Id </th>
                                <th> Nama Ba </th>
                                <th> Toko  </th>
                                <th> Status Sp </th>
                                <th> Tanggal SP </th>
                                <th> Keterangan Sp</th>
                                <th> Download Surat Sp </th>
                                <th> Upload Surat Sp </th>
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
    <script src="/js/vue.js"></script>
    <script src="/js/vue-resource.js"></script>
    <script src="/js/vue-table.js"></script>
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
    <script>
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="csrf-token"]').getAttribute('content');
        let tableColumns = [
            {title: 'Id', name: 'id', data: 'id'},
            {title: 'Nama Ba', name: 'name', data: 'name', class:'namewrapper'},
            {title: 'Toko', name: 'store.store_name_1', data: 'stores', class: 'namewrapper'},
            {title: 'Status SP', name: 'status_sp', data: 'status_sp', class: 'namewrapper'},
            {title: 'Tanggal SP', name: 'tanggal_sp', data: 'tanggal_sp', class: 'namewrapper'},
            {title: 'Keterangan Sp', name: 'keterangan_sp', data: 'keterangan_sp', class:'namewrapper'},
            {title: 'Download Surat Sp', name: 'download', data: 'download', orderable: false, searchable: false, class: 'namewrapper'},
            {title: 'Upload Surat Sp', name: 'upload', data: 'upload', orderable: false, searchable: false, class: 'namewrapper'},
        ]

        let app = new Vue({
            el: '#app',
            data () {
                return {
                    clickedBaId: '',
                    filters: {},
                    currentMonth: '',
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
                    exportReportUrl: '/exportSpData/?'
                }
            },
            methods: {
                uploadSp (id) {
                    swal({
                        title: 'Pilih Gambar Sp',
                        input: 'file',
                        inputAttributes: {
                            accept: 'image/*'
                        }
                    }).then( file => {
                        var reader = new FileReader
                        reader.readAsDataURL(file)
                        let formData = new FormData
                        formData.append('attachment', file)
                        this.$http.post(`uploadSuratSP/${id}`, formData).then(response => {
                            $('#documentSpTable').DataTable().ajax.reload();
                            swal({
                                title: 'Berhasil',
                                type: 'success',
                                text: 'Berhasil upload data SP Ba',
                                timer: 1500
                            })
                        }, response => console.error(response))
                    })
                },
                selected (key, val) {
                    this.filters[key] = val

                    this.exportReportUrl = '/exportSpData/?'

                    for (let filter in this.filters) {
                        this.exportReportUrl += filter + '=' + this.filters[filter] + '&';
                    }
                },
                filteringReport () {
                    if($.fn.dataTable.isDataTable('#documentSpTable')){
                        $('#documentSpTable').DataTable().clear();
                        $('#documentSpTable').DataTable().destroy();
                    }
                    $('#documentSpTable').dataTable({
                        "fnCreatedRow": function( nRow, data ) {
                            $(nRow).attr('class', data.id);
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '/documentSpData',
                            type: 'POST',
                            data: this.filters,
                            dataType: 'json',
                        },
                        columns: tableColumns
                    })
                },
                resetFilter () {
                    $('#filterSP').val('').trigger('change')
                    this.currentMonth = ''
                }
            },
            watch: {
                currentMonth (val) {
                    let splited = val.split(' ')
                    let monthId = this.months.filter(item => item.name == splited[0]).map(item => item.id)[0]
                    this.selected('monthSp', monthId)
                    this.selected('yearSp', splited[2])
                }
            },
            ready: function () {
                const self = this;
                $(document).ready(function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
                    // Datatable setup
                    $('#documentSpTable').dataTable({
                        "fnCreatedRow": function( nRow, data ) {
                            $(nRow).attr('class', data.id);
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '/documentSpData',
                            type: 'POST',
                            dataType: 'json',
                        },
                        columns: tableColumns
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

                    $('#filterSP').select2({
                        placeholder: 'Pilih Tipe Sp',
                        width: '100%',
                    });

                    $('#filterSP').on('select2:select', () => {
                        self.selected('tipeSp', $('#filterSP').val());
                    })
                });
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "positionClass": "toast-top-right",
                    "onclick": null,
                    "showDuration": "1000",
                    "hideDuration": "1000",
                    "timeOut": "3000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            }
        })

        function uploadClick (id) {
            app.uploadSp(id);
        }
    </script>
@stop
