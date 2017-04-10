@extends('layouts.app')
@section('additional-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.css">
@stop
@section('content')

    <div class="page-content" id="app">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Ba</h1>
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
                <a href="#">Sdf</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Ba Baru</a>
                <i class="fa fa-circle"></i>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <div class="row">
            <div class="col-md-32">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-basket"></i>
                            <span class="caption-subject sbold uppercase">Tambah Ba</span></div>
                    </div>
                    <div class="portlet-body form">
                        <!--/row-->
                        <h3 class="form-section">SDF</h3>
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label>No. SDF</label>
                                    <input type="text" class="form-control" placeholder="" name="no_sdf" v-model="sdf.no_sdf">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tangal Permintaan</label>
                                    <div class="input-group  date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control" name="request_date" readonly
                                               value="{{ date('Y-m-d') }}">
                                        <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Masuk</label>
                                    <div class="input-group  date date-picker" data-date-format="yyyy-mm-dd">
                                        <input type="text" class="form-control" name="first_date" readonly v-model="sdf.first_date"
                                               value="{{ old('first_date') }}">
                                        <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-md-line-input"  >
                                    <label class="control-label" for="form_control_1">Toko
                                    </label>
                                    <div class="">
                                        <select id="store-ajax" name="store_id" >
                                            <option value="" selected="selected"></option>
                                        </select>
                                        <div class="form-control-focus"> </div>
                                    </div>
                                    {{--:class="{'has-error': error.emptyStore.isError}"--}}
                                    {{--<span class="input-error-validation" v-show="error.emptyStore.isError">@{{ error.emptyStore.message }}</span>--}}
                                </div>
                            </div>
                        </div>
                        <!--/row-->

                        <div class="row" v-show="!isFullStoreCapacity && chosenStore !== '' ">
                            <h3 class="form-section" >Penambahan BA</h3>
                            <div class="col-md-6 " v-for="allocation in allocations">
                                <div class="form-group">
                                    <div class="input-group">
                                            <span class="input-group-addon">
                                               @{{ allocation.name }}
                                            </span>
                                        <input type="number"
                                                v-model.number="sdf[allocation.name]"
                                                @keyup="checkAllocation(allocation.name, allocation.count)"
                                                class="form-control"
                                                placeholder="Alokasi BA OAP "
                                                min="0"
                                                :max="allocation.count"
                                         >
                                    </div>
                                    <span class="info-span"
                                          v-show="!error[allocation.name]['showWarning']"
                                        >
                                        Brand @{{ allocation.name }} Di toko ini memiliki @{{ allocation.count }} tempat untuk penambahan BA Baru Tanpa merubah Alokasi
                                    </span>
                                    <span class="warning-span"
                                          v-show="error[allocation.name]['showWarning']"
                                        >
                                        @{{ error[allocation.name]['message'] }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions right">
                            <button type="button" class="btn default" >
                                <i class="fa fa-arrow-left"></i> Kembali
                            </button>
                            <button type="submit"
                                    class="btn blue"
                                    @click="submitNewBaSdf"
                            >
                                <i class="fa fa-check"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- BEGIN PAGE BASE CONTENT -->
        </div>
    </div>


@endsection

    @section('additional-script')
            <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.js"></script>
            <script src="/js/vue.js"></script>
        <script src="/js/vue-resource.js"></script>
        <script>
            $.fn.select2.defaults.set("theme", "bootstrap");
            Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="csrf-token"]').getAttribute('content');
            new Vue({
                el: '#app',
                data: {
                    isFullStoreCapacity: false,
                    chosenStore: '',
                    allocations: [],
                    exceedLimit: false,
                    sdf: {
                        no_sdf: '',
                        first_date: '',
                        request_date: moment().format('YYYY-MM-DD'),
                        CONS: 0,
                        OAP: 0,
                        GAR: 0,
                        MYB: 0,
                        store_id: '',
                    },
                    error: {
                        CONS: {
                            showWarning: false,
                            message: 'Penambahan Ba Baru di Brand CONS akan merubah Alokasi BA Brand Cons di Store ini '
                        },
                        OAP: {
                            showWarning: false,
                            message: 'Penambahan Ba Baru di Brand OAP akan merubah Alokasi BA Brand Oap di Store ini '
                        },
                        GAR: {
                            showWarning: false,
                            message: 'Penambahan Ba Baru di Brand GAR akan merubah Alokasi BA Brand Gar di Store ini '
                        },
                        MYB: {
                            showWarning: false,
                            message: 'Penambahan Ba Baru di Brand MYB akan merubah Alokasi BA Brand MYB di Store ini '
                        },
                    }

                },
                methods: {
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
                    setNewStore (value) {
                        this.chosenStore = value
                        this.$http.get('/allocationInStore/' + this.chosenStore).then((response) => {
                            this.allocations = response.body
                        }, (response) => {
                            console.error(response.status)
                        })
                    },
                    checkAllocation (name, count) {
                        this.error[name]['showWarning'] = false;
                        if (parseInt(this.sdf[name]) > parseInt(count)) {
                            this.error[name]['showWarning'] = true;
                        }
                    },
                    submitNewBaSdf () {
                        this.sdf.store_id = this.chosenStore;
                        this.$http.post('/baSdf', this.sdf).then((response) => {
                            console.log(response)
                            window.location = '/sdf';
                        }, (response) => {
                            console.log(response)
                        })
                    },
                    clearToSubmit () {

                    }
                },
                ready () {
                    var self = this
                    $('#store-ajax').select2(self.setOptions('/storeFilter', 'Nama Toko', function (params) {
                        return {
                            storeName: params.term,
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
                    })

                }
            })

            </script>
@endsection