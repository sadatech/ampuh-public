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
                <span class="active">SP Ba</span>
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
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Data SP BA</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ url('/documentSp') }}" class="btn green"> SP Document </a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="spBaTable">
                            <thead>
                            <tr>
                                <th> Aksi </th>
                                <th> Nik </th>
                                <th> Nama </th>
                                <th> Nama Toko</th>
                                <th> Account</th>
                                <th> Channel</th>
                                <th> Provinsi </th>
                                <th> Kota </th>
                                <th> Gender </th>
                                <th> Class </th>
                                <th> Status</th>
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
        const tableColumns = [
            {title: 'ACTION', name: 'aksi', data: 'aksi', orderable: false, searchable: false, class: 'namewrapper'},
            {title: 'NIK', name: 'nik', data: 'nik', sortField: 'nik'},
            {title: 'NAME', name: 'bas.name', data: 'name', sortField: 'baName', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'STORE', name: 'storeImplode', data: 'storeImplode', sortField: 'storeImplode', class:'namewrapper',searchable: false},
            {title: 'ACCOUNT', name: 'accountImplode', data: 'accountImplode', sortField: 'accountImplode', class:'namewrapper',searchable: false},
            {title: 'CHANNEL', name: 'channelImplode', data: 'channelImplode', sortField: 'channelImplode', class:'namewrapper',searchable: false},
            {title: 'PROVINCE', name: 'city.province_name', data: 'city.province_name',sortField: 'cities.province_name', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'CITY', name: 'city.city_name', data: 'city.city_name', sortField: 'cities.city_name', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'GENDER', name: 'gender', data: 'gender', sortField: 'gender'},
            {title: 'CLASS', name: 'class', data: 'class', sortField: 'class'},
            {title: 'STATUS', name: 'approval.id', data: 'approval.id', sortField: 'approves.id', callback:'statusBa',searchable: false}
        ]

        var app = new Vue({
            el: '#app',
            data: {
                brands: [],
                isFullStoreCapacity: false,
                isIntro: true,
                isFillingForm: false,
                hasError: false,
                confirmationSp: false,
                needToReplaceBa: false,
                baStore: [],
                baInWip: [],
                ba: {
                    nama: '',
                    tokoId: '',
                    tokoName: '',
                    brand: '',
                    brandId: '',
                    newBrand: '',
                    id: '',
                    newStore: '',
                    isVacant: false,
                    replaceBaId: '',
                    firstDate: '',
                    tokoUser: '',
                    alasanResign: '',
                    efektifResign: '',
                    pengajuanRequest: '',
                    joinDate: '',
                    tokoUserId: [],
                    newJoinDate: '',
                    birthDateChild: '',
                    cutiDate: '',
                    statusRolling: '',
                    alreadyAddAkte: false,
                    status: ''
                },
                error: {
                    emptyStore: {
                        isError: false,
                        message: 'Silahkan Pilih Data Toko Terlebih dahulu',
                    },
                    emptyFirstDate: {
                        isError: false,
                        message: 'Silahkan Masukkan data efektif perrolingan Ba',
                    },
                    emptyNewBrand: {
                        isError: false,
                        message: 'Silahkan Pilih Brand Baru Ba',
                    },
                    emptyReplacementBa: {
                        isError: false,
                        message: 'Silahkan Pilih  BA yang akan di gantikan dengan perrolingan ini',
                    },
                    emptyResignBa: {
                        isError: false,
                        message: 'Silahkan Masukkan Alasan BA Resign',
                    },
                    emptyAppliedResignDate: {
                        isError: false,
                        message: 'Silahkan Masukkan Tanggal Pengajuan Resign',
                    },
                    emptyEffectiveResignDate: {
                        isError: false,
                        message: 'Silahkan Masukkan Tanggal Efektif Resign Ba',
                    },
                    emptyAkteKelahiran: {
                        isError: false,
                        message: 'Silahkan Masukkan lampiran data Akte Kelahiran',
                    },
                    emptyChildBirthDate: {
                        isError: false,
                        message: 'Silahkan Masukkan Tanggal kelahiran anak',
                    },
                    emptyRollingStatus: {
                        isError: false,
                        message: 'Silahkan Pilih Status Perollingan BA' ,
                    }
                },
                statusSp: 'SP1',
                canUpdateSp: true,
                isSp3: false,
                clickedBaId: '',
                canUseOldJoinDate: false,
                rejoinData: new FormData(),
                baHistory: {}
            },
            methods: {
                toggleError: function (error) {
                    this.hasError = error;
                    this.isIntro = !error;
                },
                toggleFillingForm: function (fillingForm) {
                    this.isFillingForm = fillingForm;
                    this.confirmationSp = !fillingForm
                    this.isIntro = !fillingForm;
                    this.hasError = !fillingForm;
                },
                toggleConfirmationSp: function (fillingForm) {
                    this.confirmationSp = fillingForm;
                    this.isFillingForm = !fillingForm;
                    this.isIntro = !fillingForm;
                    this.hasError = !fillingForm;
                },
                toggleIntro: function (intro) {
                    this.isFillingForm = !intro;
                    this.confirmationSp = !intro;
                    this.isIntro = intro;
                    this.hasError = !intro;
                },
                setStore: function (id, name) {
                    this.toggleFillingForm(true);
                    this.ba.tokoId = id;
                    this.ba.tokoName = name;
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
                        placeholder: placeholder,
                    }
                },
                assignBaDetail (value) {
                    this.toggleError(false);
                    let {name: nama, status, brand: {name: brand, id: brandId}, store, id, join_date: joinDate, cuti_date: cutiDate} = value;
                    Object.assign(this.ba, {nama, brand, brandId, id, joinDate, cutiDate, status});
                    this.baStore = store;
                    this.ba.tokoUser = store.map(function (obj) {
                        return obj.store_name_1
                    });
                },
                itemClick (id) {
                    this.$http.get('/ba/' + id).then(response => this.spData(response.body),
                        response => console.error(response))
                },
                generateSwal (title, text, type, confirmText) {
                    return swal({
                        title: title,
                        text: text,
                        type: type,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Tidak'
                    })
                },
                spData: function (ba) {
                    if (ba.status_sp == 'SP3') {
                        swal({
                            title: 'Gagal',
                            text:  ba.status_sp + ' Adalah Tingakatan SP paling Tinggi',
                            type: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        })
                        return;
                    }
                    if (ba.sp_approval !== 'pending') {
                        let allowedSp = ['SP1', 'SP2', 'SP3'];
                        switch (ba.status_sp) {
                            case 'SP1':
                                allowedSp.splice(0, 1);
                                break;
                            case 'SP2':
                                allowedSp.splice(0, 2);
                                break;
                        }
                        let spReduce = allowedSp.reduce((initial, item) => {
                            initial[item] = item;
                            return initial;
                        }, {});
                        swal({
                            title: 'Pilih Tipe Sp',
                            input: 'select',
                            inputOptions: spReduce,
                            inputPlaceholder: 'Tipe Sp',
                            showCancelButton: true,
                            inputValidator: function (value) {
                                return new Promise(function (resolve, reject) {
                                    if (value !== '') {
                                        resolve()
                                    } else {
                                        reject('Silahkan Pilih Tipe SP')
                                    }
                                })
                            }
                        }).then((result) => {
                            let text = 'Apa Anda Yakin Untuk Memberikan ' + result +  ' Kepada BA ' + ba.name;
                            this.statusSp = result;

                            swal({
                                title: `Konfirmasi Pemberian  ${result}`,
                                text: text,
                                input: 'text',
                                showCancelButton: true,
                                inputValidator  (value) {
                                    return new Promise(function (resolve, reject) {
                                        if (value) {
                                            resolve()
                                        } else {
                                            reject('Silahkan Isi Keterangan SP Terlebih Dahulu')
                                        }
                                    })
                                }
                            }).then( (result) => {
                                this.giveSp(ba.id, result);
                            }, result => console.error('canceling'))
                        })
                        return;
                    }
                    swal({
                        title: 'Gagal',
                        text: 'Status ' + ba.status_sp + ' Ba Belum di Approve Oleh Tim Loreal',
                        type: 'error',
                        timer: 2000,
                        showConfirmButton: false
                    })
                },
                dataStore: function (value, skipStoreCheck = false) {
                    if (value.constructor !== Object) {
                        this.toggleError(true);
                        return;
                    }
                    if (value.store != undefined && value.store.length == 0  && !skipStoreCheck) {
                        this.toggleError(true);
                        return;
                    }
                    this.assignBaDetail(value);
                },
                giveSp: function (id, spReason) {
                    this.$http.post('/master/spBa', {ba: id, statusSp: this.statusSp, keteranganSp: spReason}, '').then((response) => {
                        swal({
                            type: 'success',
                            html: 'Pemberian SP berhasil'
                        })
                    }, (response) => {
                        console.error('Error ' + response.message)
                    })
                },
                watchUserInput (val, errorName) {
                    if (val !== '') {
                        this.error[errorName].isError = false
                    }
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

                    $('#spBaTable').dataTable({
                        "fnCreatedRow": function( nRow, data ) {
                            $(nRow).attr('class', data.id);
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '/masterBa',
                            type: 'POST',
                            data: {
                                requestType: 'sp'
                            },
                            dataType: 'json'
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

                    $('#form-sp-modal').on('hidden.bs.modal', function () {
                        self.toggleIntro(true);
                    });
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
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

        function spBaClick (id) {
            app.itemClick(id)
        }
    </script>
@stop
