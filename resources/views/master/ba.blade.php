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
                <span class="active">BA</span>
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
                        <div class="actions">
                            <a href="{{ route('baExport') }}" class="btn green-dark" >
                                <i class="fa fa-cloud-download"></i> Download Excel </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                    @if(Auth::user()->role == 'aro')
                                        <div class="btn-group">
                                            <button onclick="newBa()" class="btn green"> Add New
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    @else
                                        <a href="{{ url('/master/ba/approval') }}" class="btn green"> Approval ({{ $totalbayangbelomdiapprove }})
                                        </a>
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
        @include('partial.form-menu-ba')
        @include('partial.history-ba')

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
        let brands = {};
        let stores = {};
        let wipStore = [];
        let store;
        let brand;
        let sdf;
        let hasOwnProperty = Object.prototype.hasOwnProperty;

        function isEmpty(obj) {

            // null and undefined are "empty"
            if (obj == null) return true;

            // Assume if it has a length property with a non-zero value
            // that that property is correct.
            if (obj.length > 0)    return false;
            if (obj.length === 0)  return true;

            // If it isn't an object at this point
            // it is empty, but it can't be anything *but* empty
            // Is it empty?  Depends on your application.
            if (typeof obj !== "object" || obj.constructor !== Array) return true;

            // Otherwise, does it have any properties of its own?
            // Note that this doesn't handle
            // toString and valueOf enumeration bugs in IE < 9
            for (var key in obj) {
                if (hasOwnProperty.call(obj, key)) return false;
            }

            return true;
        }
        function newBa(){
            var inputOptions = new Promise(function (resolve) {
                resolve({
                    'sdf': 'BA Baru',
                    'replacement': 'Replacement',
                    'rotasi': 'Rotasi'
                })
            });

            swal({
                title: 'Tipe',
                input: 'radio',
                inputOptions: inputOptions,
                inputValidator: function (result) {
                    return new Promise(function (resolve, reject) {
                        if (result) {
                            resolve()
                        } else {
                            reject('You need to select something!')
                        }
                    })
                }
            }).then(function (result) {
                if(result == 'replacement'){
                    var wip;
                    var brand;
                    $.ajax(
                        {
                            url: "/store/available",
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                if (isEmpty(data.data) == true) {
                                    // Speed up calls to hasOwnProperty
                                    swal('Tidak Ada Toko', '', 'error');
                                } else {
                                    var inputOptions = new Promise(function (resolve) {
                                        $.each(data.data, function (key, value) {
                                            var id = value.store.id;
                                            var name = value.store.store_name_1;
                                            wip = value.id;
                                            brand = value.brand_id;
                                            stores[id] = name;
                                            wipStore.push({storeId: id, wipId: value.id, brandId: value.brand_id})
                                        });
                                        resolve(stores)
                                    })


                                    swal({
                                        title: 'Select Store',
                                        input: 'select',
                                        inputOptions: inputOptions,
                                        inputValidator: function (result) {
                                            return new Promise(function (resolve, reject) {
                                                if (result) {
                                                    resolve()
                                                } else {
                                                    reject('Anda harus memilih salah satu!')
                                                }
                                            })
                                        }
                                    }).then(function (result) {
                                        const storeDetail = wipStore.find((item) => item.storeId == result)
                                        const param = storeDetail.storeId + '/' + storeDetail.brandId + '/' + storeDetail.wipId + '/' + 'replacement';
                                        window.location = '{{ url('master/ba/add/')  }}/' + window.btoa(param);
                                    })
                                }
                            }
                        });
                } else if (result === 'rotasi') {
                    $.ajax({
                        url: "/master/ba/rotasiWip",
                        type: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            if (data.length === 0) {
                                wip = 0 ;
                                window.location = '{{ url('master/ba/add/')  }}/' + window.btoa('rotasi' + '/' + wip);
                                return;
                            }
                            let inputOptions = new Promise((resolve) => {
                                data.map(value => {
                                    wip = value.id;
                                    stores[value.ba.id] = value.ba.name
                                });
                                stores[0] = 'Tambah Ba Rotasi Baru';
                                resolve(stores)
                            })
                            swal({
                                title: 'BA Rotasi yang Digantikan',
                                input: 'select',
                                inputOptions: inputOptions,
                                inputValidator: function (result) {
                                    return new Promise(function (resolve, reject) {
                                        if (result) {
                                            resolve()
                                        } else {
                                            reject('Anda harus memilih salah satu!')
                                        }
                                    })
                                }
                            }).then(function (result) {
                                if (result === '0' ) {
                                    wip = 0;
                                    window.location = '{{ url('master/ba/add/')  }}/' + window.btoa('rotasi' + '/' + wip);
                                    return;
                                }
                                window.location = '{{ url('master/ba/add/')  }}/' + window.btoa('rotasi' + '/' + wip + '/' + result)
                            })
                        }
                    });

                } else {
                    var selected_store;
                    $.ajax(
                        {
                            url: "/master/store/sdf/reo",
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                if (isEmpty(data.data) == true) {
                                    // Speed up calls to hasOwnProperty
                                    swal('Tidak Ada Toko', '', 'error');
                                } else {
                                    var inputOptions = new Promise(function (resolve) {
                                        $.each(data.data, function (key, value) {
                                            var id = value.id;
                                            var name = value.store_name;
                                            stores[id] = name;
                                        });
                                        resolve(stores)
                                    })


                                    swal({
                                        title: 'Select Store',
                                        input: 'select',
                                        inputOptions: inputOptions,
                                        inputValidator: function (result) {
                                            return new Promise(function (resolve, reject) {
                                                if (result) {
                                                    resolve()
                                                } else {
                                                    reject('Anda harus memilih salah satu!')
                                                }
                                            })
                                        }
                                    }).then(function (result) {
                                        store = {store_id: result};
                                        $.ajax(
                                            {
                                                url: "/master/store/brand/sdf",
                                                data: store,
                                                type: 'POST',
                                                dataType: 'json',
                                                success: function (data) {

                                                    if (isEmpty(data.brands) == true) {
                                                        // Speed up calls to hasOwnProperty
                                                        swal('Tidak ada brand yang membutuhkan', '', 'error');
                                                    } else {
                                                        var listBrands = new Promise(function (resolve) {
                                                            brands = Object.assign({});
                                                            brand = Object.assign({});
                                                            $.each(data.brands, function (key, value) {
                                                                var id = value.id;
                                                                var name = value.name;
                                                                brands[id] = name;
                                                            });
                                                            resolve(brands)
                                                        })
                                                        swal({
                                                            title: 'Select Brands',
                                                            input: 'radio',
                                                            inputOptions: listBrands,
                                                            inputValidator: function (result) {
                                                                return new Promise(function (resolve, reject) {
                                                                    if (result) {
                                                                        resolve()
                                                                        brands = Object.assign({});
                                                                    } else {
                                                                        reject('Anda harus memilih salah satu!')
                                                                    }
                                                                })
                                                            }
                                                        }).then(function (result) {
                                                            brand = result;
                                                            var param = store.store_id + '/' + brand;
                                                            window.location = '{{ url('master/ba/add/')  }}/' + window.btoa(param);
                                                        })
                                                        //End of select brands
                                                    }
                                                }
                                            });
                                        //End of select brands
                                    })
                                }
                            }
                        });
                    {{--swal({--}}
                    {{--title: 'No SDF',--}}
                    {{--input: 'text',--}}
                    {{--showLoaderOnConfirm: true,--}}
                    {{--preConfirm: function (nosdf) {--}}
                    {{--sdf = nosdf;--}}
                    {{--return new Promise(function (resolve, reject) {--}}
                    {{--$.ajax(--}}
                    {{--{--}}
                    {{--url: "/sdf/exists",--}}
                    {{--type: 'POST',--}}
                    {{--dataType: "JSON",--}}
                    {{--data: {--}}
                    {{--"no_sdf": nosdf,--}}
                    {{--},--}}
                    {{--success: function (data) {--}}
                    {{--if (data.status == false && data.message) {--}}
                    {{--swal(--}}
                    {{--'Oops...',--}}
                    {{--data.message,--}}
                    {{--'error'--}}
                    {{--);--}}
                    {{--}--}}
                    {{--else if (data.status != false) {--}}
                    {{--// inputOptions can be an object or Promise--}}
                    {{--var inputOptions = new Promise(function (resolve) {--}}
                    {{--brands = Object.assign({});--}}
                    {{--$.each(data.brands, function (key, value) {--}}
                    {{--var id = value.id;--}}
                    {{--var name = value.name;--}}
                    {{--brands[id] = name;--}}
                    {{--});--}}
                    {{--console.log(brands)--}}
                    {{--resolve(brands)--}}
                    {{--})--}}

                    {{--swal({--}}
                    {{--title: 'Select Brands',--}}
                    {{--input: 'radio',--}}
                    {{--inputOptions: inputOptions,--}}
                    {{--inputValidator: function (result) {--}}
                    {{--return new Promise(function (resolve, reject) {--}}
                    {{--if (result) {--}}
                    {{--resolve()--}}
                    {{--brands = Object.assign({});--}}
                    {{--} else {--}}
                    {{--reject('Anda harus memilih salah satu!')--}}
                    {{--}--}}
                    {{--})--}}
                    {{--}--}}
                    {{--}).then(function (result) {--}}
                    {{--var param = sdf + '/' + result;--}}
                    {{--window.location = '{{ url('master/ba/add/')  }}/' + window.btoa(param);--}}
                    {{--})--}}
                    {{--//End of select brands--}}
                    {{--} else {--}}
                    {{--reject('Nomor SDF tidak ada!')--}}
                    {{--}--}}
                    {{--}--}}
                    {{--});--}}
                    {{--})--}}
                    {{--}--}}
                    {{--});--}}
                }
            });
        }
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="csrf-token"]').getAttribute('content');
        var tableColumns = [
            {title: 'ACTION', name: 'aksi', data: 'aksi', orderable: false, searchable: false, class: 'namewrapper'},
            {title: 'NIK', name: 'nik', data: 'nik', sortField: 'nik'},
            {title: 'NAME', name: 'bas.name', data: 'name', sortField: 'baName', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'STORE NAME', name: 'storeImplode', data: 'storeImplode', sortField: 'storeImplode', class:'namewrapper',searchable: false},
            {title: 'ACCOUNT', name: 'accountImplode', data: 'accountImplode', sortField: 'accountImplode', class:'namewrapper',searchable: false},
            {title: 'CHANNEL', name: 'channelImplode', data: 'channelImplode', sortField: 'channelImplode', class:'namewrapper',searchable: false},
            {title: 'BIRTH DATE', name: 'birth_date', data: 'birth_date', sortField: 'birth_date'},
            {title: 'PROVINCE', name: 'city.province_name', data: 'city.province_name',sortField: 'cities.province_name', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'CITY', name: 'city.city_name', data: 'city.city_name', sortField: 'cities.city_name', dataClass:'namewrapper', class:'namewrapper'},
            {title: 'NO KTP', name: 'no_ktp', data: 'no_ktp', sortField: 'no_ktp'},
            {title: 'NO HP', name: 'no_hp', data: 'no_hp', sortField: 'no_hp'},
            {title: 'GENDER', name: 'gender', data: 'gender', sortField: 'gender'},
            {title: 'EDUKATION', name: 'education', data: 'education', sortField: 'education'},
            {title: 'UNIFORM SIZE', name: 'uniform_size', data: 'uniform_size', sortField: 'uniform_size'},
            {title: 'JOIN DATE', name: 'join_date', data: 'join_date', sortField: 'join_date'},
            {title: 'JOIN DATE MDS', name: 'join_date_mds', data: 'join_date_mds', sortField: 'join_date'},
            {title: 'BANK NAME', name: 'bank_name', data: 'bank_name', sortField: 'bank_name'},
            {title: 'REKENING ', name: 'rekening', data: 'rekening', sortField: 'rekening'},
            {title: 'BRAND', name: 'brand.name', data: 'brand.name', sortField: 'brands.name'},
            {title: 'MOBILE/STAY', name: 'status', data: 'status', sortField: 'status'},
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
                setBaReplacement: function (value) {
                    this.ba.replaceBaId = value;
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
                    return validate.filter((item) => {
                            return this.ba[item.data] === '' || this.ba[item.data] === false
                        }).map((item) => {
                            return this.error[item.error].isError = true
                        }).length === 0;
                },
                itemClick (action) {
                    const id = this.clickedBaId;
                    if (action === 'ba-join-back') {
                        $('#form-join-back-ba').modal('show');
                        this.$http.get('/baStore/' + id).then(response => this.dataStore(response.body, true),
                            response => this.dataStore(response.status));
                    }
                    if (action === 'ba-cuti') {
                        this.$http.get('/ba/' + id).then((response) => {
                            this.dataStore(response.body, true)
                            let text = 'Apa Anda Yakin Untuk Memberikan izin cuti hamil Kepada BA ' + response.body.name
                            this.generateSwal('Konfirmasi', text, 'warning', 'Ya Berikan Cuti Hamil').then(() => this.processMaternityLeave(response.body.id))
                        }, (response) => {
                            this.dataStore(response.status)
                        });
                    }
                    if (action === 'ba-resign-detail') {
                        this.$http.get('/ba/' + id).then((response) => {
                            swal({
                                title: 'Informasi Alasan Resign Ba',
                                text:  'Ba ' + response.body.name + ' Resign dengan Alasan ' + response.body.resign_reason,
                                type: 'info',
                                showConfirmButton: true
                            })
                        });
                    }
                    if (action === 'history-ba') {
                        this.$http.get('/baStore/' + id).then(response => this.dataStore(response.body, true),
                            response => this.dataStore(response.status));
                        this.$http.get('/historyBa/' + id).then(response => {
                                $('#history-ba').modal('show');
                                this.baHistory = response.body
                            }, response => console.error(response))
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
                joinBackBa: function () {
                    let appends = {
                        id: this.ba.id,
                        newStore: this.ba.newStore,
                        replaceBaId: this.ba.replaceBaId,
                        newBrand: this.ba.newBrand,
                        newJoinDate: moment(this.ba.newJoinDate).format('YYYY-MM-DD'),
                        birthDateChild: moment(this.ba.birthDateChild).format('YYYY-MM-DD')
                    }
                    for (let append in appends) {
                        this.rejoinData.append(append, appends[append])
                    }
                    if (this.validateRollingInput(true)) {
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
                processMaternityLeave (id) {
                    this.$http.get('/maternityLeave/' + id).then((response) => {
                        toastr.info('Izin Cuti Hamil Berhasil Data akan dikirimkan menuju Tim Arina untuk Approval');
                    }, (response) => {
                        console.error(response)
                    })
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
                idHistory (val) {
                    return '#' + val
                },
                historyInfo (val) {
                    switch (val) {
                        case 'new':
                            return this.ba.nama + ' Mulai Bekerja pada Toko'
                        case 'cuti':
                            return this.ba.nama + ' Mengambil Cuti Hamil Dan Meninggalkan Toko'
                        case 'rejoin':
                            return this.ba.nama + ' Masuk Kembali Bekerja Pada Toko'
                        case 'rolling_out':
                            return this.ba.nama + ' Dirolling Dari Toko'
                        case 'rolling_in':
                            return this.ba.nama + ' Masuk Dari hasil Rolling ke Toko'
                        case 'resign':
                            return this.ba.nama + ' Telah Resign Meninggalkan Toko'
                    }
                },
                length (val) {
                    return this.decideStatus(val).length
                },
                decideColor (val) {
                    return this.decideStatus(val, false)
                },
                decideStatus (val, actual = true) {
                    let history = this.baHistory[val]
                    if (history['new'] != undefined) {
                        return (actual) ? history['new'] : 'green-dark'
                    } if (history['cuti'] != undefined) {
                        return (actual) ? history['cuti'] : 'grey-silver'
                    } if (history['rejoin'] != undefined) {
                        return (actual) ? history['rejoin'] : 'blue-steel'
                    } if (history['rolling_out'] != undefined) {
                        return (actual) ? history['rolling_out'] : 'purple-plum'
                    } if (history['rolling_in'] != undefined) {
                        return (actual) ? history['rolling_in'] : 'blue-oleo'
                    } if (history['resign'] != undefined) {
                        return (actual) ? history['resign'] : 'red-soft'
                    }
                },
                watchUserInput (val, errorName) {
                    if (val !== '') {
                        this.error[errorName].isError = false
                    }
                },
            },
            watch: {
                'ba.firstDate': function (val) {
                    this.watchUserInput(val, 'emptyFirstDate')
                },
                'ba.newBrand': function (val) {
                    this.watchUserInput(val, 'emptyNewBrand')
                },
                'ba.replaceBaId': function (val) {
                    this.watchUserInput(val, 'emptyReplacementBa')
                },
                'ba.pengajuanRequest': function (val) {
                    this.watchUserInput(val, 'emptyAppliedResignDate')
                },
                'ba.birthDateChild': function (val) {
                    this.watchUserInput(val, 'emptyChildBirthDate')
                    this.checkJoinDateAvailability()
                },
            },
            ready: function () {
                let self = this;
                $(document).ready(function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
                    // Datatable setup
                    var table = $('#masterBaTable').dataTable({
                        "fnCreatedRow": function( nRow, data ) {
                            $(nRow).attr('class', data.id);
                        },
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            url: '/masterBa',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                requestType: 'master'
                            }
                        },
                        columns: tableColumns
                    })

                    $('#form-rolling-modal').on('hidden.bs.modal', function () {
                        self.toggleIntro(true);
                        self.resetBaForm();
                    });
                    $('#form-sp-modal').on('hidden.bs.modal', function () {
                        self.toggleIntro(true);
                    });
                    $('#store-ajax').select2(self.setOptions('/storeFilter', 'Nama Toko', function (params) {
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
                    $('#store-ajax').on('select2:select', function () {
                        self.setNewStore($('#store-ajax').val());
                        self.detectBaInWip();
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

                    $('#status-ajax').select2()
                    $('#status-ajax').on('select2:select', function () {
                        self.setRollingStatus($('#status-ajax').val())
                    })
                    $("#modal-basic-menu").on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal')
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
        function testClick (id) {
            $('#modal-basic-menu').modal('show');
            app.clickedBaId = id;
            app.itemClick('assignBa');
        }

        function joinBackBa (id) {
            app.clickedBaId = id;
            app.itemClick('ba-join-back');
        }

        function resignBaDetail (id) {
            app.clickedBaId = id;
            app.itemClick('ba-resign-detail')
        }
    </script>
@stop
