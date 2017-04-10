@extends('layouts.app')

@section('additional-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.css">
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
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
                <span class="active">Rolling BA</span>
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
                            <span class="caption-subject font-red sbold uppercase">Data BA</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                @if(Auth::user()->role == 'reo')
                                    <div class="col-md-6">
                                        <div class="btn-group">
                                            <button class="btn green" @click="goToApprove()">Approval @{{ rollingData }}
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="masterBaTable">
                            <thead>
                            <tr>
                                <th> AKSI</th>
                                <th> NIK</th>
                                <th> NAMA</th>
                                <th> NAMA TOKO</th>
                                <th> ACCOUNT</th>
                                <th> CHANNel</th>
                                <th> TANGGAL LAHIR</th>
                                <th> PROVINSI</th>
                                <th> KOTA</th>
                                <th> NO KTP</th>
                                <th> NO HP</th>
                                <th> GENDER</th>
                                <th> EDUKASI</th>
                                <th> UKURAN SERAGAM</th>
                                <th> TANGGAL MASUK</th>
                                <th> TANGGAL MASUK MDS</th>
                                <th> NAMA BANK</th>
                                <th> REKENING</th>
                                <th> BRAND</th>
                                <th> MOBILE/STAY</th>
                                <th> AGENCY</th>
                                <th> MASA KERJA</th>
                                <th> CLASS</th>
                                <th> STATUS</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    @include('partial.form-rolling-ba')
    @include('partial.form-brand-change-ba')
    @include('partial.form-menu-rolling')


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
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
    <script>
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="csrf-token"]').getAttribute('content');
        let tableColumns = [
            {title: 'ACTION', name: 'aksi', data: 'aksi', orderable: false, searchable: false, class: 'namewrapper'},
            {title: 'NIK', name: 'nik', data: 'nik', sortField: 'nik'},

            {title: 'NAME', name: 'bas.name', data: 'name', sortField: 'baName', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'STORE NAME', name: 'store.store_name_1', data: 'storeImplode', sortField: 'storeImplode', class:'namewrapper'},
            {title: 'ACCOUNT', name: 'store.account.name', data: 'accountImplode', sortField: 'accountImplode', class:'namewrapper'},
            {title: 'CHANNEL', name: 'store.channel', data: 'channelImplode', sortField: 'channelImplode', class:'namewrapper'},

            {title: 'BIRTH DATE', name: 'birth_date', data: 'birth_date', sortField: 'birth_date'},
            {
                title: 'PROVINCE',
                name: 'city.province_name',
                data: 'city.province_name',
                sortField: 'cities.province_name',
                dataClass: 'namewrapper',
                class: 'namewrapper'
            },
            {
                title: 'CITY',
                name: 'city.city_name',
                data: 'city.city_name',
                sortField: 'cities.city_name',
                dataClass: 'namewrapper',
                class: 'namewrapper'
            },
            {title: 'NO KTP', name: 'no_ktp', data: 'no_ktp', sortField: 'no_ktp'},
            {title: 'NO HP', name: 'no_hp', data: 'no_hp', sortField: 'no_hp'},
            {title: 'GENDER', name: 'gender', data: 'gender', sortField: 'gender'},
            {title: 'EDUKATIOn', name: 'education', data: 'education', sortField: 'education'},
            {title: 'UNIFORM SIZE', name: 'uniform_size', data: 'uniform_size', sortField: 'uniform_size'},
            {title: 'JOIN DATE', name: 'join_date', data: 'join_date', sortField: 'join_date'},
            {title: 'JOIN DATE MDS', name: 'join_date_mds', data: 'join_date_mds', sortField: 'join_date'},
            {title: 'BANK NAME', name: 'bank_name', data: 'bank_name', sortField: 'bank_name'},
            {title: 'REKENING ', name: 'rekening', data: 'rekening', sortField: 'rekening'},
            {title: 'BRAND', name: 'brand.name', data: 'brand.name', sortField: 'brands.name'},
            {title: 'MOBILE/STAY', name: 'status', data: 'status', sortField: 'status'},
            {
                title: 'AGENCY',
                name: 'agency.name',
                data: 'agency.name',
                sortField: 'agencies.name',
                class: 'namewrapper'
            },
            {
                title: 'WORKING TIME',
                name: 'join_date',
                data: 'masa_kerja',
                sortField: 'agencies.name',
                class: 'namewrapper'
            },
            {title: 'CLASS', name: 'class', data: 'class', sortField: 'class'},
            {title: 'STATUS', name: 'approval.id', data: 'approval.id', sortField: 'approves.id', callback: 'statusBa'},
        ]

        let app = new Vue({
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
                    pengajuanRequest: '',
                    joinDate: '',
                    tokoUserId: [],
                    newJoinDate: '',
                    statusRolling: '',
                    status: '',
                    newReo: {
                        newStoreReo: 'Pilih Toko untuk Melihat Nama Supervisor',
                        id: 0,
                        userId: 0
                    },
                    switch: {
                        id: '',
                    },
                    joinDateMds: {
                        show: false,
                        joinDate: ''
                    },
                    bulkRolling: false,
                    bulkRollingPair: 0,
                    bulk: {
                        storesid: [],
                        replaces: [],
                        wip: []
                    }
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
                    emptyRollingStatus: {
                        isError: false,
                        message: 'Silahkan Pilih Status Perollingan BA',
                    },
                    emptySwitchBa: {
                        isError: false,
                        message: 'Silahkan Pilih BA yang akan di switch ',
                    },
                    emptySwitchStore: {
                        isError: false,
                        message: 'Silahkan Pilih Toko BA yang akan di switch ',
                    }
                },
                statusSp: 'SP1',
                canUpdateSp: true,
                isSp3: false,
                clickedBaId: '',
                canUseOldJoinDate: false,
                rejoinData: new FormData(),
                baHistory: {},
                rollingApproval: '',
                rotationStore: [144, 145, 146, 147, 148, 775, 776, 777, 778, 779],
                switchStore: [],
                oldStoreAccount: '',
                constraintBrand: false
            },

            computed: {
                activeUserId () {
                    return {{ Auth::user()->id }}
                },

                isReo () {
                    return {{ Auth::user()->role == 'reo' }}
                },

                isAro () {
                    return {{ Auth::user()->role == 'aro' }}
                },

                readableDateFormat () {
                    return moment(this.ba.joinDate).format('DD-MMM-YY');
                },

                editLink () {
                    return '/master/ba/edit/' + this.clickedBaId;
                },

                rollingData () {
                    return (this.rollingApproval !== 0 && this.rollingApproval !== '') ? `( ${this.rollingApproval} )` : ' '
                },
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

                toggleIntro: function (intro) {
                    this.isFillingForm = !intro;
                    this.isIntro = intro;
                    this.hasError = !intro;
                },

                setStore: function (id, name, bulk = false) {
                    this.toggleFillingForm(true);
                    this.ba.tokoId = id;
                    if (bulk) {
                        this.ba.tokoName = name.reduce((acc, val, index, array) => {
                            acc += val;
                            if (array.length - 1 !== index) {
                                acc += ' , '
                            }
                            return acc;
                        }, ' ');
                        this.ba.bulkRolling = true;
                    } else {
                        this.ba.tokoName = name;
                        this.constraintBrand = this.ba.brandId
                    }

                    if (name == 'Penambahan Toko baru') {
                        this.ba.statusRolling = name
                    }
                    if (id == '012344321' && id !== 987654) {
                        this.$http.get(`/master/store/${id}`).then(({data}) => {
                                this.oldStoreAccount = data.account_id
                            },
                            (response) => console.error(response))
                    }
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

                setNewStore: function (value) {
                    this.ba.newStore = value;
                    this.ba.bulk.wip = []
                    this.$http.get('/checkStoreBaAllocation/' + this.ba.newStore).then((response) => {
                        this.isFullStoreCapacity = response.body.length == 0;
                        this.brands = response.body;
                    }, response => console.error(response.status))
                },

                setBaReplacement: function (value) {
                    this.ba.replaceBaId = value;
                },

                detectBaInWip: function () {
                    this.$http.get(`/findBaInWip/${this.ba.newStore}/${this.ba.brandId}`).then((response) => {
                        if (response.body.length != 0) {
                            this.baInWip = response.body
                            this.needToReplaceBa = true;
                            return;
                        }
                        this.needToReplaceBa = false;
                    }, (response) => {
                        console.error(response.status);
                    });
                },

                detectBulkBaInWip (newStore, index) {
                    this.ba.bulk.storesid.$set(index, newStore);
                    this.$http.get(`/findBaInWip/${newStore}`).then((response) => {
                        if (response.body.length != 0) {
                            this.ba.bulk.replaces.$set(index, response.body);
                            this.needToReplaceBa = true;
                            return;
                        }
                        this.needToReplaceBa = false;
                    }, (response) => {
                        console.error(response.status);
                    });
                },

                roleBa: function () {
                    if (this.validateRollingInput()) {
                        this.ba.pengajuanRequest = moment().format('YYYY-MM-DD');
                        this.ba.firstDate = moment(this.ba.firstDate, 'DD-MM-YYYY').format('YYYY-MM-DD');

                        if (this.ba.joinDateMds.show) {
                            this.ba.joinDateMds.joinDate = moment(this.ba.joinDateMds.joinDate, 'DD-MM-YYYY').format('YYYY-MM-DD')
                        }
                        this.$http.post('/rollingBa', this.ba).then((response) => {
                            if (this.activeUserId !== this.ba.newReo.userId && this.ba.newReo.userId !== 0 && this.isReo) {
                                toastr.success('Rolling Ba Berhasil', `Rolling akan efektif ketika Supervisor ${this.ba.newReo.newStoreReo} menyetujui perollingan`);
                            } else {
                                toastr.success('Rolling Ba Berhasil', 'Sukses');
                            }
                            $('#form-rolling-modal').modal('hide');
                            this.resetBaForm();
                            this.toggleIntro(true);
                        }, (response) => {
                            console.error(response.status);
                            toastr.error('Gagal', 'Terjadi Kesalahan ketika menghubungi server');
                            // do some information gathering when this thing happen

                        })
                    } else {
                        toastr.error('Gagal', 'Silahkan cek semua inputan terlebih dahulu');
                    }
                },

                validateRollingInput: function (validateReJoinData = false) {
                    let decideBrand = (!this.needToReplaceBa) ?
                            {data: 'newBrand', error: 'emptyNewBrand'} :
                            {data: 'replaceBaId', error: 'emptyReplacementBa'}

                    let validate = [{data: 'newStore', error: 'emptyStore'},
                        {data: 'firstDate', error: 'emptyFirstDate'},
                        {data: 'statusRolling', error: 'emptyRollingStatus'},
                        decideBrand
                    ];
                    if (validateReJoinData) {
                        // delete firstDate and statusRolling from the Array because we don't need that to validate rejoin data
                        validate.splice(1, 2);
                        validate.push({
                            data: 'alreadyAddAkte',
                            error: 'emptyAkteKelahiran'
                        }, {data: 'birthDateChild', error: 'emptyChildBirthDate'})
                    }
                    if (this.ba.statusRolling === 'takeout') {
                        // if rolling status is takeout there is no need another input just delete that from array
                        validate.splice(0, 2);
                        validate.pop();
                    }
                    if (this.ba.statusRolling === 'switch') {
                        if (this.ba.switch.id === '') {
                            this.error.emptySwitchBa.isError = true;
                            return false;
                        }
                        return true;
                    }
                    if (this.ba.bulkRolling) {
                        // removing unused validate rules when bulk rolling
                        validate.splice(0, 1)
                        validate.pop()

                        validate.push({data: this.ba.bulkRollingPair + 1 === this.ba.bulk.storesid.length, error: 'emptyStore'})
                        validate.push({data: this.ba.bulkRollingPair + 1 === this.ba.bulk.wip.length, error: 'emptyReplacementBa'})
                    }
                    return validate.filter((item) => {
                            return this.ba[item.data] === '' || this.ba[item.data] === false || item.data === false
                        }).map((item) => {
                            return this.error[item.error].isError = true
                        }).length === 0;
                },

                resetBaForm: function () {
                    let freshBa = {
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
                        switch: {
                            id: '',
                        },
                        joinDateMds: {
                            show: false,
                            joinDate: ''
                        },
                        newReo: {
                            newStoreReo: 'Pilih Toko untuk Melihat Nama Supervisor',
                            id: 0,
                            userId: 0
                        },
                        bulk: {
                            storesid: [],
                            replaces: [],
                            wip: []
                        },
                        bulkRollingPair: 0,
                        bulkRolling: false
                    };
                    this.switchStore = []
                    this.needToReplaceBa = false
                    Object.assign(this.ba, freshBa);
                    $('#store-ajax').val('').trigger('change');
                    $('#ba-ajax').val('').trigger('change');
                },

                assignBaDetail (value) {
                    this.toggleError(false);
                    let {name: nama, status, brand: {name: brand, id: brandId}, store, id, join_date: joinDate, cuti_date: cutiDate} = value;
                    Object.assign(this.ba, {nama, brand, brandId, id, joinDate, cutiDate, status});
                    this.oldStoreAccount = value.store.filter(item => item.id === this.ba.tokoId).map(item => item.account_id)[0];
                    this.baStore = store;
                    this.ba.tokoUser = store.map(function (obj) {
                        return obj.store_name_1
                    });
                },

                itemClick (action) {
                    const id = this.clickedBaId;
                    if (action === 'ba-rolling') {
                        $('#form-rolling-modal').modal('show');
                        this.$http.get('/baStore/' + id).then(response => this.dataStore(response.body, true),
                            response => this.dataStore(response.status));
                    }
                    if (action === 'change-brand') {
                        $('#form-change-brand-modal').modal('show');
                        this.$http.get(`/master/ba/availableBrand/${id}`).then(response => console.log('On Proccess'),
                            response => console.error(response))
                    }
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

                dataStore: function (value, skipStoreCheck = false) {
                    if (value.constructor !== Object) {
                        this.toggleError(true);
                        return;
                    }
                    if (value.store != undefined && value.store.length == 0 && !skipStoreCheck) {
                        this.toggleError(true);
                        return;
                    }
                    this.assignBaDetail(value);
                },

                setRollingStatus (val) {
                    this.ba.statusRolling = val;
                    if (val === 'switch') {
                        this.initSwitchSelect2()
                    }
                },

                length (val) {
                    return this.decideStatus(val).length
                },

                watchUserInput (val, errorName) {
                    if (val !== '') {
                        this.error[errorName].isError = false
                    } else if (val === false ) {
                        this.error[errorName].isError = false
                    }
                },

                isRotationBa () {
                    return this.ba.status === 'rotasi'
                },

                goToApprove () {
                    window.location = "/rolling/approval"
                },

                initSwitchSelect2 () {
                    $('#ba-ajax').select2(this.setOptions('/baFilter', 'Nama Ba', (params) => {
                        return {
                            name: params.term,
                            currentId: this.ba.id,
                            clearEmptyStoreBa: true
                        }
                    }, (data, params) => {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.name}
                            })
                        }
                    }));
                    $('#ba-ajax').on('select2:select', () => {
                        this.setSwitchId($('#ba-ajax').val())
                    })
                },

                setSwitchId (baId) {
                    this.ba.switch.id = baId;
                },

                accountCheck () {
                    if (this.oldStoreAccount != 12) {
                        this.$http.get(`/master/store/${this.ba.newStore}`).then(({data}) => {
                            this.ba.joinDateMds.show = data.account_id === 12;
                        }, (response) => console.error(response))
                    }
                },

                checkBulkRolling (storeId) {
                    if (this.ba.tokoId === 987654) {
                        this.detectBulkBaInWip(storeId, 0);
                        this.$http.get(`/wip/${storeId}`)
                            .then(({data}) => {
                                const pairCount = Math.floor(1 / data['head_count']) - 1;
                                this.ba.bulkRollingPair = pairCount;
                                if (pairCount >= 1) {
                                    for (let i = 0; i < pairCount; i++) {
                                        setTimeout(() => this.initBulkStoreSelect(this.storeAjax(i), data['head_count'], i), 200);
                                    }
                                }
                            }, (response) => console.error(response));
                    }
                },

                initStoreSelect (isBulkRolling = false) {
                    $('#store-ajax').select2(this.setOptions('/storeFilter', 'Nama Toko', (params) => {
                        if (isBulkRolling) {
                            return {
                                storeName: params.term,
                                excludeBaStoreBrand: {store: this.ba.tokoId, brand: this.ba.brandId},
                                onlyInWip: true,
                                aroId: (this.isAro == 1) ? this.activeUserId : 'notAro'
                            }
                        }
                        if (this.ba.statusRolling === 'Penambahan Toko baru') {
                            return {
                                storeName: params.term,
                                excludeBaStoreBrand: {store: this.ba.tokoId, brand: this.ba.brandId},
                                sameAllocationPlusOne: this.ba.id,
                                onlyInWip: true,
                                constraintBrand: this.constraintBrand,
                                aroId: (this.isAro == 1) ? this.activeUserId : 'notAro'
                            }
                        }
                        return {
                            storeName: params.term,
                            excludeBaStoreBrand: {store: this.ba.tokoId, brand: this.ba.brandId},
                            sameAllocation: this.ba.id,
                            constraintBrand: this.constraintBrand,
                            aroId: (this.isAro == 1) ? this.activeUserId : 'notAro'
                        }
                    }, (data, params) => {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.store_name_1}
                            })
                        }
                    }));
                    $('#store-ajax').on('select2:select', () => {
                        this.setNewStore($('#store-ajax').val());
                        this.detectBaInWip();
                        this.accountCheck();
                        this.checkBulkRolling($('#store-ajax').val());
                    })
                },

                initBulkStoreSelect (storeId, headCount, index) {
                    $(`#${storeId}`).select2(this.setOptions('/storeFilter', 'Nama Toko', (params) => {
                        return {
                            storeName: params.term,
                            excludeBaStoreBrand: {store: this.ba.tokoId, brand: this.ba.brandId},
                            limitHeadCount: headCount,
                            sameBrand: this.ba.bulk.wip[0],
                            preventDuplicate: this.ba.bulk.storesid
                        }
                    }, (data, params) => {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.store_name_1}
                            })
                        }
                    }));
                    $(`#${storeId}`).on('select2:select', () => {
                        // index + 1 karena index 0 udah di ambil sama toko pertama
                        this.detectBulkBaInWip($(`#${storeId}`).val(), index + 1);
                    })
                },

                storeAjax (n) {
                    return `store-ajax-${n}`;
                },

                baReplacementWip (n) {
                    return `baReplacementWip-${n}`;
                }
            },

            watch: {
                'ba.newStore': function (val) {
                    this.watchUserInput(val, 'emptyStore')
                    let isRotationStore = this.rotationStore.filter(item => item === parseInt(val)).length !== 0;
                    if (isRotationStore) {
                        this.ba.newReo.newStoreReo = 'Toko Rotasi'
                        this.ba.newReo.id = 'rotasi'
                        this.ba.newReo.userId = 'rotasi'
                        return;
                    }
                    if (val != 987654 && val != '012344321' && val != '') {
                        this.$http.get('/findReo/' + val).then(response => {
                            this.ba.newReo.newStoreReo = response.body.namaReo
                            this.ba.newReo.id = response.body.reoId
                            this.ba.newReo.userId = response.body.userId
                        }, response => console.error(response));
                    }
                },

                'ba.firstDate': function (val) {
                    this.watchUserInput(val, 'emptyFirstDate')
                },

                'ba.newBrand': function (val) {
                    this.watchUserInput(val, 'emptyNewBrand')
                    this.ba.replaceBaId = ''
                },

                'ba.replaceBaId': function (val) {
                    this.watchUserInput(val, 'emptyReplacementBa')
                    this.ba.newBrand = '';
                },

                'ba.statusRolling': function (val) {
                    this.watchUserInput(val, 'emptyRollingStatus')
                },

                'ba.switch.id': function (val) {
                    this.watchUserInput(val, 'emptySwitchBa')
                },

                'ba.switch.storeId': function (val) {
                    this.watchUserInput(val, 'emptySwitchStore')
                },

                'ba.tokoId' (val)  {
                    if (val !== 987654) {
                        setTimeout(() => this.initStoreSelect(), 200);
                    } else {
                        setTimeout(() => this.initStoreSelect(true), 200);
                    }
                },

                'ba.bulk.wip' () {
                    const clearToPost = this.ba.bulk.wip.length === this.ba.bulkRollingPair + 1;
                    if (clearToPost) {
                        this.watchUserInput(clearToPost, 'emptyReplacementBa')
                    }
                },

                'ba.bulk.storesid' () {
                    const clearToPost = this.ba.bulk.storesid.length === this.ba.bulkRollingPair + 1;

                    if (clearToPost) {
                        this.watchUserInput(clearToPost, 'emptyStore')
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
                    $.fn.modal.Constructor.prototype.enforceFocus = function () {
                    };
                    // Datatable setup
                    $('#masterBaTable').dataTable({
                        "fnCreatedRow": function (nRow, data) {
                            $(nRow).attr('class', data.id);
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '/masterBa',
                            data: {
                                requestType: 'rolling'
                            },
                            type: 'POST',
                            dataType: 'json',
                        },
                        columns: tableColumns
                    })
                    $('#form-rolling-modal').on('hidden.bs.modal', function () {
                        self.toggleIntro(true);
                        self.resetBaForm();
                    });
                });
                this.$http.get('/findRollingApproval').then(response => this.rollingApproval = response.body.length, response => console.error(response))
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

        function rollingBaClick(id) {
            app.clickedBaId = id;
            app.itemClick('ba-rolling')
        }
    </script>
@stop
