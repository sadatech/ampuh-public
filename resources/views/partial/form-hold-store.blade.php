<div id="app">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">{{ $store->store_name_1 }}</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <h4></h4>

                <table width="100%" class="table table-hover table-bordered">
                    <tr>
                        <td colspan="2" style="text-align: center; font-weight: bold; font-size: 16px;">BA yang bekerja di toko {{ $store->store_name_1 }} </td>
                    </tr>
                    @if($store->haveBa->count() == 0)
                        <tr>
                            <td colspan="2" bgcolor="#e7ecf1" align="center"><strong> Tidak ada BA </strong></td>
                        </tr>
                    @endif
                    @foreach($store->haveBa as $ba)
                        <tr>
                            <td>{{ $loop->iteration}} </td>
                            <td>{{ $ba->name }} </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @include('partial.form-resign-ba')
        @include('partial.form-rolling-ba')
    </div>
    <script src="/js/vue.js"></script>
    <script src="/js/vue-resource.js"></script>
    <script src="/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
    <script>
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
                        message: 'Silahkan Pilih Status Perollingan BA',
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
                itemClick (action, id) {
                    if (action === 'rolling') {
                        $('#form-rolling-modal').modal('show');
                        this.$http.get('/baStore/' + id).then(response => this.assignBaDetail(response.body),
                            response => console.error('Error You Bitch'));
                    }
                },
                assignBaDetail (value) {
                    if (value.length === 0) {
                        return this.toggleError(true);
                    }
                    let {name: nama, brand: {name: brand, id: brandId}, store, id, join_date: joinDate, cuti_date: cutiDate} = value;
                    Object.assign(this.ba, {nama, brand, brandId, id, joinDate, cutiDate});
                    this.baStore = store;
                    this.ba.tokoUser = store.map(function (obj) {
                        return obj.store_name_1
                    });
                },
                roleBa: function () {
                    if (this.validateRollingInput()) {
                        this.ba.pengajuanRequest = moment().format('YYYY-MM-DD');
                        this.$http.post('/rollingBa', this.ba).then((response) => {
                            toastr.success('Rolling Ba Berhasil', 'Sukses');
                            $('#form-rolling-modal').modal('hide');
                            this.resetBaForm();
                        }, (response) => {
                            console.error(response.status);
                        })
                    } else {
                        toastr.error('Gagal', 'Silahkan cek semua inputan terlebih dahulu');
                    }
                },
                validateRollingInput: function () {
                    let decideBrand = (!this.needToReplaceBa) ? {
                            data: 'newBrand',
                            error: 'emptyNewBrand'
                        } : {data: 'replaceBaId', error: 'emptyReplacementBa'}
                    let validate = [{data: 'newStore', error: 'emptyStore'},
                        {data: 'firstDate', error: 'emptyFirstDate'},
                        {data: 'statusRolling', error: 'emptyRollingStatus'},
                        decideBrand
                    ];
                    return validate.filter((item) => {
                            return this.ba[item.data] === '' || this.ba[item.data] === false
                        }).map((item) => {
                            return this.error[item.error].isError = true
                        }).length === 0;
                },
                watchUserInput (val, errorName) {
                    if (val !== '') {
                        this.error[errorName].isError = false
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
                setRollingStatus (val) {
                    this.ba.statusRolling = val;
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
            },
            watch: {
                'ba.newStore': function (val) {
                    this.watchUserInput(val, 'emptyStore')
                },
                'ba.firstDate': function (val) {
                    this.watchUserInput(val, 'emptyFirstDate')
                },
                'ba.newBrand': function (val) {
                    this.watchUserInput(val, 'emptyNewBrand')
                },
                'ba.replaceBaId': function (val) {
                    this.watchUserInput(val, 'emptyReplacementBa')
                },
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
                'ba.statusRolling': function (val) {
                    this.watchUserInput(val, 'emptyRollingStatus')
                }
            },
            ready () {
                console.log('Readyyy')
                $(document).ready(() => {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#form-rolling-modal').on('hidden.bs.modal', function () {
                        this.toggleIntro(true);
                        this.resetBaForm();
                    });
                    $('#store-ajax').select2(this.setOptions('/storeFilter', 'Nama Toko', function (params) {
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
                        this.setNewStore($('#store-ajax').val());
                        this.detectBaInWip();
                    })
                })
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
        });
        function rollingClick(id) {
            app.itemClick('rolling', id)
        }
        $( "#hold" ).click(function() {
            $.post( "/master/store/{{ $store->id }}/hold?{{ $request->exists('sdf') ? 'sdf':'' }}")
                .done(function( data ) {
                    alert(data.message);
                    if(data.status == true) {
                        $('#ajax').modal('hide')
                    }
                });
        });

    </script>

