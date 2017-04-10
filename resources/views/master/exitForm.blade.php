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
                <span class="active">Exit Form</span>
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
                                <div class="col-md-6">
                                    @if(Auth::user()->role == 'arina')
                                        <div class="btn-group">
                                            <a href="{{ url('configuration/exitForm/archive') }}" class="btn green"> Exit Form Archive
                                                <i class="fa fa-archive"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="masterBaTable">
                            <thead>
                            <tr>
                                <th> Aksi </th>
                                <th> Nik </th>
                                <th> Nama </th>
                                <th> Nama Toko</th>
                                <th> Account</th>
                                <th> Channel</th>
                                <th> Region</th>
                                <th> Tanggal Lahir </th>
                                <th> Provinsi </th>
                                <th> Kota </th>
                                <th> No Ktp </th>
                                <th> No Hp </th>
                                <th> Gender </th>
                                <th> Edukasi </th>
                                <th> Ukuran Seragam </th>
                                <th> Tanggal Masuk </th>
                                <th> Tanggal Masuk MDS</th>
                                <th> Nama Bank </th>
                                <th> Rekening </th>
                                <th> Brand </th>
                                <th> Mobile/Stay </th>
                                <th> Agency </th>
                                <th> Masa Kerja </th>
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
    @include('partial.form-resign-ba')
    @include('partial.form-join-back-ba')
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
        var brands = {};
        var stores = {};
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="csrf-token"]').getAttribute('content');
        var tableColumns = [
            {title: 'ACTION', name: 'aksi', data: 'aksi', orderable: false, searchable: false, class: 'namewrapper'},
            {title: 'NIK', name: 'nik', data: 'nik', sortField: 'nik'},
            {title: 'NAME', name: 'bas.name', data: 'name', sortField: 'baName', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'STORE', name: 'store.store_name_1', data: 'storeImplode', sortField: 'storeImplode', class:'namewrapper'},
            {title: 'ACCOUNT', name: 'store.account.name', data: 'accountImplode', sortField: 'accountImplode', class:'namewrapper'},
            {title: 'CHANNEL', name: 'store.channel', data: 'channelImplode', sortField: 'channelImplode', class:'namewrapper'},
            {title: 'REGION', name: 'city.region_id', data: 'city.region_id', sortField: 'cities.region_id', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'BIRTH DATE', name: 'birth_date', data: 'birth_date', sortField: 'birth_date'},
            {title: 'PROVINCE', name: 'city.province_name', data: 'city.province_name',sortField: 'cities.province_name', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'CITY', name: 'city.city_name', data: 'city.city_name', sortField: 'cities.city_name', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'NO KTP', name: 'no_ktp', data: 'no_ktp', sortField: 'no_ktp'},
            {title: 'NO HP', name: 'no_hp', data: 'no_hp', sortField: 'no_hp'},
            {title: 'GENDER', name: 'gender', data: 'gender', sortField: 'gender'},
            {title: 'EDUCATION', name: 'education', data: 'education', sortField: 'education'},
            {title: 'UNIFORM SIZE', name: 'uniform_size', data: 'uniform_size', sortField: 'uniform_size'},
            {title: 'JOIN DATE', name: 'join_date', data: 'join_date', sortField: 'join_date'},
            {title: 'JOIN DATE MDS', name: 'join_date_mds', data: 'join_date_mds', sortField: 'join_date_mds'},
            {title: 'BANK', name: 'bank_name', data: 'bank_name', sortField: 'bank_name'},
            {title: 'REKENING ', name: 'rekening', data: 'rekening', sortField: 'rekening'},
            {title: 'BRAND', name: 'brand.name', data: 'brand.name', sortField: 'brands.name'},
            {title: 'Mobile/Stay', name: 'status', data: 'status', sortField: 'status'},
            {title: 'AGENCY', name: 'agency.name', data: 'agency.name', sortField: 'agencies.name', class:'namewrapper'},
            {title: 'WORKING TIME', name: 'join_date', data: 'masa_kerja', sortField: 'agencies.name', class:'namewrapper'},
            {title: 'CLASS', name: 'class', data: 'class', sortField: 'class'},
            {title: 'STATUS', name: 'approval.id', data: 'approval.id', sortField: 'approves.id', callback:'statusBa'},
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
                    status: '',
                    resign_reason: '',
                    keteranganResign: '',
                    takeoutStore: false
                },
                error: {
                    emptyStore: {
                        isError: false,
                        message: 'Silahkan Pilih Data Toko Terlebih dahulu',
                    },
                    emptyNewBrand: {
                        isError: false,
                        message: 'Silahkan Pilih Brand Baru BA',
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
                        message: 'Silahkan Masukkan Tanggal Efektif Resign BA',
                    },
                    emptyAkteKelahiran: {
                        isError: false,
                        message: 'Silahkan Masukkan lampiran data Akte Kelahiran',
                    },
                    emptyChildBirthDate: {
                        isError: false,
                        message: 'Silahkan Masukkan Tanggal kelahiran anak',
                    },
                    emptyNewJoinDate: {
                        isError: false,
                        message: 'Silahkan Masukkan Tanggal Join Date Baru BA' ,
                    },
                    emptyResignInfo: {
                        isError: false,
                        message: 'Silahkan Masukkan Keterangan Resign BA' ,
                    }
                },
                statusSp: 'SP1',
                canUpdateSp: true,
                isSp3: false,
                clickedBaId: '',
                canUseOldJoinDate: false,
                rejoinData: new FormData(),
                baHistory: {},
                rotationStore: [144, 145, 146, 147, 148, 775, 776, 777, 778, 779],
                writeResign: false
            },
            computed: {
                activeUserId () {
                    return {{ Auth::user()->id }}
                },
                isReo () {
                    return {{ Auth::user()->role == 'reo' }}
                },
                readableDateFormat () {
                    return moment(this.ba.joinDate).format('DD-MMM-YY');
                },
                editLink () {
                    return '/master/ba/edit/' + this.clickedBaId;
                },
                joinDateInfo () {
                    if (this.canUseOldJoinDate) {
                        return 'Waktu cuti hamil masih dalam waktu toleransi 3 bulan dari tanggal lahir anak masih dapat menggunakan join date lama'
                    }
                    return 'Waktu cuti hamil telah melebihi waktu toleransi 3 bulan dari tanggal lahir anak, join date akan dirubah menjadi tanggal hari ini'
                }
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
                setNewStore: function (value) {
                    this.ba.newStore = value;
                    this.$http.get('/checkStoreBaAllocation/' + this.ba.newStore).then((response) => {
                        this.isFullStoreCapacity = response.body.length == 0;
                        this.brands = response.body;
                        console.log(response.body);
                    }, response => {
                            console.error(response.status)
                        })
                },
                validateRollingInput: function (validateReJoinData = false ) {
                    let decideBrand = (!this.needToReplaceBa) ? {data: 'newBrand', error: 'emptyNewBrand'} : {data: 'replaceBaId', error: 'emptyReplacementBa'}
                    let validate = [ {data: 'newStore', error: 'emptyStore'},
                                     {data: 'firstDate', error: 'emptyFirstDate'},
                                     {data: 'statusRolling', error: 'emptyRollingStatus'},
                                     decideBrand
                                   ];
                    if (validateReJoinData) {
                        // delete firstDate and statusRolling from the Array because we don't need that to validate rejoin data
                        validate.splice(1,2);
                        validate.push({data: 'alreadyAddAkte', error: 'emptyAkteKelahiran'}, {data: 'birthDateChild', error: 'emptyChildBirthDate'})
                    }
                    if (this.ba.statusRolling === 'takeout') {
                        // if rolling status is takeout there is no need another input just delete that from array
                        validate.splice(0,2);
                        validate.pop();
                    }
                    return validate.filter((item) => {
                                return this.ba[item.data] === '' || this.ba[item.data] === false
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
                        resign_reason: ''
                    }
                    this.needToReplaceBa = false
                    Object.assign(this.ba, freshBa);
                    $('#store-ajax').val('').trigger('change');
                },
                baResign: function () {
                    if (this.clearToResign()) {
                        this.ba.pengajuanRequest = moment(this.ba.pengajuanRequest, 'DD-MM-YYYY').format('YYYY-MM-DD');
                        this.ba.efektifResign = moment(this.ba.efektifResign, 'DD-MM-YYYY').format('YYYY-MM-DD');
                        this.ba.tokoUserId = this.baStore.map(obj => obj.id );
                        this.$http.post('/resignBa', this.ba).then((response) => {
                            $('#form-resign-modal').modal('hide');
                        this.resetBaForm();
                        toastr.info('Data akan dikirim menuju tim arina untuk proses approval', 'Berhasil');
                    }, (response) => {
                            console.error(response.status);
                        });
                    } else {
                        toastr.error('Gagal', 'Silahkan cek semua inputan terlebih dahulu');
                    }
                },
                clearToResign: function () {
                    let validate = [{data: 'alasanResign', error: 'emptyResignBa'},
                                    {data: 'pengajuanRequest', error: 'emptyAppliedResignDate'},
                                    {data: 'efektifResign', error: 'emptyEffectiveResignDate'}];
                    return validate.filter(item => {
                                return this.ba[item.data] === '';
                            }).map(item => {
                                return this.error[item.error].isError = true
                            }).length === 0
                },
                assignBaDetail (value) {
                    this.toggleError(false);
                    let {name: nama, status, brand: {name: brand, id: brandId}, store, id, join_date: joinDate, cuti_date: cutiDate, resign_reason} = value;
                    Object.assign(this.ba, {nama, brand, brandId, id, joinDate, cutiDate, status, resign_reason});
                    this.baStore = store;
                    this.ba.tokoUser = store.map(function (obj) {
                        return obj.store_name_1
                    });
                },
                itemClick (action) {
                    const id = this.clickedBaId;
                    if (action === 'ba-resign') {
                        $('#form-resign-modal').modal('show');
                        this.$http.get('/baStore/' + id).then(response =>  this.assignBaDetail(response.body),
                                                              response =>  console.error(response.body));
                    }
                    if (action === 'ba-resign-detail') {
                        this.$http.get('/ba/' + id).then((response) => {
                            swal({
                                title: 'Informasi Alasan Resign BA',
                                text:  'BA ' + response.body.name + ' Resign dengan Alasan ' + response.body.resign_reason,
                                type: 'info',
                                showConfirmButton: true
                            })
                        });
                    }
                    if (action === 'ba-join-back') {
                        $('#form-join-back-ba').modal('show');
                        this.$http.get('/baStore/' + id).then(response => this.dataStore(response.body, true),
                            response => this.dataStore(response.status));
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
                    if (value.store != undefined && value.store.length == 0  && !skipStoreCheck) {
                        this.toggleError(true);
                        return;
                    }
                    this.assignBaDetail(value);
                },
                watchUserInput (val, errorName) {
                    if (val !== '') {
                        this.error[errorName].isError = false
                    }
                },
                detectBaInWip: function () {
                    this.$http.get('/findBaInWip/' + this.ba.newStore).then((response) => {
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
                setAkteKelahiran (e) {
                    this.ba.alreadyAddAkte = true;
                    this.error.emptyAkteKelahiran.isError = false;
                    console.log(this.ba.alreadyAddAkte)
                    this.rejoinData.append('attachment', e.target.files[0])
                },
                checkJoinDateAvailability () {
                    var elapsedTime = moment.duration(moment().diff(moment(this.ba.birthDateChild)));
                    if (elapsedTime.months() <= 3 && elapsedTime.years() == 0) {
                        console.log(this.ba.joinDate)
                        this.ba.newJoinDate = moment(this.ba.joinDate).format('DD-MMM-YY');
                        this.canUseOldJoinDate = true;
                        return;
                    }
                    this.canUseOldJoinDate = false;
                    this.ba.newJoinDate = moment().format('DD-MMM-YY');
                },
                joinBackBa: function () {
                    let appends = {
                        id: this.ba.id,
                        newStore: this.ba.newStore,
                        replaceBaId: this.ba.replaceBaId,
                        newBrand: this.ba.newBrand,
                        newJoinDate: moment(this.ba.newJoinDate, 'DD-MM-YYYY').format('YYYY-MM-DD'),
                        birthDateChild: moment(this.ba.birthDateChild, 'DD-MM-YYYY').format('YYYY-MM-DD')
                    }
                    for (let append in appends) {
                        if (Array.isArray(appends[append])) {
                            appends[append].map((val, index) => { this.rejoinData.append(`${append}[${index}]`, val) });
                        } else {
                            this.rejoinData.append(append, appends[append])
                        }
                    }
                    if (this.clearToJoinBack(this.ba.resign_reason === 'Hamil')) {
                        this.$http.post('/joinBackBa', this.rejoinData).then((response) => {
                            toastr.info('Join Back Ba Berhasil Data akan dikirimkan menuju Tim Arina untuk Approval');
                            $('#form-join-back-ba').modal('hide');
                            this.resetBaForm();
                        }, (response) => {
                            console.error(response)
                        })
                    } else {
                        toastr.error('Gagal', 'Silahkan cek semua inputan terlebih dahulu');
                    }
                },
                clearToJoinBack (maternityLeave) {
                    let decideBrand = (!this.needToReplaceBa) ? {data: 'newBrand', error: 'emptyNewBrand'} : {data: 'replaceBaId', error: 'emptyReplacementBa'}
                    let validate = [ {data: 'newStore', error: 'emptyStore'}, decideBrand, {data: 'newJoinDate', error: 'emptyNewJoinDate'}];

                    if (maternityLeave) {
                        validate.push({data: 'alreadyAddAkte', error: 'emptyAkteKelahiran'}, {data: 'birthDateChild', error: 'emptyChildBirthDate'})
                    }

                    return validate.filter((item) => {
                            return this.ba[item.data] === '' || this.ba[item.data] === false
                        }).map((item) => {
                            return this.error[item.error].isError = true
                        }).length === 0;
                }
            },
            watch: {
                'ba.alasanResign': function (val) {
                    this.watchUserInput(val, 'emptyResignBa')
                },
                'ba.pengajuanRequest': function (val) {
                    this.watchUserInput(val, 'emptyAppliedResignDate')
                },
                'ba.efektifResign': function (val) {
                    this.watchUserInput(val, 'emptyEffectiveResignDate')
                },
                'ba.birthDateChild': function (val) {
                    this.watchUserInput(val, 'emptyChildBirthDate')
                    this.checkJoinDateAvailability()
                },
                'ba.newStore': function (val) {
                    this.watchUserInput(val, 'emptyStore')
                    // todo handle REO ketika ke ba mobile
//                    let isRotationStore = this.rotationStore.filter(item => item === parseInt(val)).length !== 0;
//                    if (isRotationStore) {
//                        this.ba.newReo.newStoreReo = 'Toko Rotasi'
//                        this.ba.newReo.id = 'rotasi'
//                        this.ba.newReo.userId = 'rotasi'
//                        return;
//                    }
//                    this.$http.get('/findReo/' + val).then( response => {
//                        this.ba.newReo.newStoreReo = response.body.namaReo
//                        this.ba.newReo.id = response.body.reoId
//                        this.ba.newReo.userId = response.body.userId
//                    }, response => console.error(response));
                },
                'ba.newBrand': function (val) {
                    this.watchUserInput(val, 'emptyNewBrand')
                    this.ba.replaceBaId = ''
                },
                'ba.replaceBaId': function (val) {
                    this.watchUserInput(val, 'emptyReplacementBa')
                    this.ba.newBrand = '';
                },
                'ba.newJoinDate': function (val) {
                    this.watchUserInput(val, 'emptyNewJoinDate')
                },
                'ba.keteranganResign': function (val) {
                    this.watchUserInput(val, 'emptyResignInfo')
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
                    $('#masterBaTable').dataTable({
                        "fnCreatedRow": function( nRow, data ) {
                            $(nRow).attr('class', data.id);
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '/masterBa',
                            data: {
                                requestType: 'resign'
                            },
                            type: 'POST',
                            dataType: 'json',
                        },
                        columns: tableColumns
                    })
                    $('#store-ajax-join-back').select2(self.setOptions('/storeFilter', 'Nama Toko', function (params) {
                        return {
                            storeName: params.term,
                            excludeBaStore: self.ba.id,
                            reoId: (self.isReo == 1) ? self.activeUserId : 'notReo'
                        }
                    }, function (data, params) {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.store_name_1}
                            })
                        }
                    }));
                    $('#store-ajax-join-back').on('select2:select', function () {
                        self.setNewStore($('#store-ajax-join-back').val());
                        self.detectBaInWip();
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
        function exitFormClick (id) {
            app.clickedBaId = id;
            app.itemClick('ba-resign');
        }

        function rejoinBa (id) {
            app.clickedBaId = id;
            app.itemClick('ba-join-back');
        }

        function resignBaDetail (id) {
            app.clickedBaId = id;
            app.itemClick('ba-resign-detail')
        }
    </script>
@stop
