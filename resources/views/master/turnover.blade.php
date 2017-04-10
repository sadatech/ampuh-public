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
                <span class="active">Turn Over Ba</span>
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
                                <select id="filterArea" class="select2-container--bootstrap.input-lg" ></select>
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="filterBulan" placeholder="Month" v-model="currentMonth">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" id="filterTahun" placeholder="Year" v-model="currentYear">
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
                            <span class="caption-subject font-red sbold uppercase">Data Turn Over BA</span>
                        </div>
                        <div class="actions">
                            <a :href="exportReportUrl" class="btn green-dark" >
                                <i class="fa fa-cloud-download"></i> Download Excel </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="turnOverBaTable">
                            <thead>
                            <tr>
                                <th> Aksi </th>
                                <th> Month </th>
                                <th> Name </th>
                                <th> Region </th>
                                <th> Kota </th>
                                <th> Store No </th>
                                <th> Cust ID </th>
                                <th> Store Name 1 </th>
                                <th> Join Date </th>
                                <th> Join Date MDS</th>
                                <th> Resign Date </th>
                                {{--<th> Last Day di Toko </th>--}}
                                <th> Alasan Resign </th>
                                <th> Nama Reo </th>
                                <th> Keterangan </th>
                                <th> Channel </th>
                                <th> Account </th>
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
            {title: 'MONTH', name: 'resign_at', data: 'month'},
            {title: 'NAME', name: 'name', data: 'name'},
            {title: 'REGION', data: 'city.region_id', name: 'city.region_id', class: 'namewrapper', "defaultContent": "" , orderable: false},
            {title: 'CITY', name: 'city.city_name', data: 'city.city_name', sortField: 'cities.city_name', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'STORE NO', name: 'history.store.store_no', data: 'historyStoreNumber', sortField: 'historyStoreNumber', class:'namewrapper',orderable: false},
            {title: 'CUST ID', name: 'history.store.customer_id', data: 'historyCustomerId', sortField: 'historyCustomerId', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'STORE NAME 1', name: 'history.store.store_name_1', data: 'historyStore', sortField: 'historyStore', class:'namewrapper',orderable: false},
//            {title: 'Store Name 1', name: 'history.store.store_name_1', data: 'history.store.store_name_1', sortField: 'stores.store_name_1', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'JOIN DATE', name: 'join_date', data: 'join_date', sortField: 'join_date'},
            {title: 'JOIN DATE MDS', name: 'join_date_mds', data: 'join_date_mds', sortField: 'join_date_mds'},
            {title: 'RESIGN DATE', name: 'resign_at', data: 'resign_at', sortField: 'resign_at'},
            {title: 'RESIGN REASON', name: 'resign_reason', data: 'resign_reason', sortField: 'resign_reason'},
            {title: 'SS', name: 'history.store.reo.user.name', data: 'historyReo', sortField: 'historyReo',"defaultContent": "" , dataClass:'namewrapper', class:'namewrapper'},
            {title: 'DESCRIPTION', name: 'resign_info', data: 'resign_info', sortField: 'resign_info'},
            {title: 'CHANNEL', name: 'history.store.channel', data: 'historyChannel', sortField: 'historyChannel', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'ACCOUNT', name: 'history.store.account.name', data: 'historyaccount', sortField: 'historyaccount',"defaultContent": "" , dataClass:'namewrapper', class:'namewrapper'},
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
                    resign_reason: ''
                },
                error: {
                    emptyStore: {
                        isError: false,
                        message: 'Silahkan Pilih Data Toko Terlebih dahulu',
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
                        message: 'Silahkan Masukkan alasan resign BA'
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
                writeResign: false,
                filters: {},
                currentMonth: '',
                currentYear: '',
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
                exportReportUrl: '/exportTurnOver/?requestType=turnover&'
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
                        this.ba.tokoUserId = this.baStore.map(function (obj) {
                            return obj.id
                        });
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
                    Object.assign(this.ba, {nama, brand, brandId, id, joinDate, cutiDate, status, resign_reason, newJoinDate: moment(joinDate).format('DD-MM-YYYY')});
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
                },
                selected (key, val) {
                    this.filters[key] = val;

                    this.exportReportUrl = '/exportTurnOver/?requestType=turnover&'

                    for (let filter in this.filters) {
                        this.exportReportUrl += filter + '=' + this.filters[filter] + '&';
                    }
                },
                filteringReport () {
                    this.filters['requestType'] = 'turnover';
                    if($.fn.dataTable.isDataTable('#turnOverBaTable')){
                        $('#turnOverBaTable').DataTable().clear();
                        $('#turnOverBaTable').DataTable().destroy();
                    }
                    $('#turnOverBaTable').dataTable({
                        "fnCreatedRow": function( nRow, data ) {
                            $(nRow).attr('class', data.id);
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '/masterBa',
                            data: this.filters,
                            type: 'POST',
                            dataType: 'json',
                        },
                        columns: tableColumns
                    })
                },
                resetFilter () {
                    $('#filterArea').val('').trigger('change')
                    this.currentMonth = ''
                    this.currentYear = ''
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
                currentMonth (val) {
                    if (val !== '') {
                        let splited = val.split(' ')
                        let monthId = this.months.filter(item => item.name == splited[0]).map(item => item.id)[0]
                        this.selected('monthTurnOver', monthId)
                        this.selected('yearTurnOver', splited[2])
                    }
                },
                currentYear (val) {
                    if (val !== '') {
                        this.selected('yearTurnOver', val)
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
                    // Datatable setup
                    $('#turnOverBaTable').dataTable({
                        "fnCreatedRow": function( nRow, data ) {
                            $(nRow).attr('class', data.id);
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '/masterBa',
                            data: {
                                requestType: 'turnover'
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

                    $('#filterTahun').datepicker({
                        format: "yyyy",
                        startView: 1,
                        minViewMode: 2,
                        maxViewMode: 2,
                        keyboardNavigation: false,
                        calendarWeeks: true,
                        autoClose: true,
                        todayHighlight: true,
                        orientation: "bottom auto",
                    });

                    $('#filterArea').select2(self.setOptions('/areaFilter', 'Area', (params) => {
                        return {
                            area: params.term
                        }
                    }, (data) => {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.cabang}
                            })
                        }
                    }));

                    $('#filterArea').on('select2:select', () => {
                        self.selected('area', $('#filterArea').val());
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
