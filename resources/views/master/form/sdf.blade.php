@extends('layouts.app')
@section('additional-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.css">
    <link href="/assets/global/plugins/bootstrap-toastr/toastr.min.css" rel="stylesheet" type="text/css" />
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
                            <span class="caption-subject sbold uppercase">Tambah SDF</span></div>
                    </div>
                    <div class="portlet-body form">
                        <!--/row-->
                        <h3 class="form-section">SDF</h3>
                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="form-group" :class="{'has-error': empty.no_sdf.error}">
                                    <label>No. SDF</label>
                                    <input type="text" class="form-control" placeholder="" name="no_sdf"
                                           v-model="sdf.no_sdf">
                                    <span class="input-error-validation" v-show="empty.no_sdf.error">@{{ empty.no_sdf.message }}</span>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tangal Permintaan</label>
                                    <div class="input-group  date date-picker" data-date-format="dd/mm/yyyy">
                                        <input type="text" class="form-control" name="request_date" readonly
                                               v-model="sdf.request_date"
                                               >
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
                                <div class="form-group" :class="{'has-error': empty.first_date.error}">
                                    <label>Tanggal Masuk</label>
                                    <div class="input-group  date date-picker" data-date-format="dd/mm/yyyy">
                                        <input type="text" class="form-control" name="first_date" readonly
                                               v-model="sdf.first_date">
                                        <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                    </div>
                                    <span class="input-error-validation" v-show="empty.first_date.error">@{{ empty.first_date.message }}</span>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" :class="{'has-error': empty.attachment.error}"  >
                                    <label class="control-label" for="form_control_1">SDF Attachment</label>
                                    <input type="file" class="form-control"  name="foto_kelahiran" @change="setSdfAttachment(1, $event)" />
                                </div>
                                <span class="input-error-validation" v-show="empty.attachment.error">@{{ empty.attachment.message }}</span>
                            </div>
                        </div>
                        <div class="row" v-show="isChoosingStore">
                            <div class="centerin" style="padding: 20px; flex-direction: column ">
                                <button type="submit"
                                        class="btn  blue"
                                         @click="setStoreStatus('newStore')"
                                         style="width: 23%;"
                                         >
                                        <img src="/assets/store.svg" alt="" width="30px" height="20px">
                                        <span>Toko Baru </span>
                                </button>
                                <button type="submit"
                                        class="btn  blue"
                                        @click="setStoreStatus('oldStore')"
                                        style="width: 23%"
                                        >
                                        <img src="/assets/store.svg" alt="" width="30px" height="20px">
                                        <span>Toko Lama </span>
                                </button>
                            </div>
                        </div>
                        <div v-show="oldStore">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input" :class="{'has-error': empty.store.error}">
                                        <label class="control-label" for="form_control_1">Toko
                                        </label>
                                        <div class="">
                                            <select id="store-ajax" name="store_id[]" multiple>
                                            </select>
                                            <div class="form-control-focus"></div>
                                        </div>
                                        <span class="input-error-validation" v-show="empty.store.error">@{{ empty.store.message }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row" v-show="chosenStore.length !== 0 ">
                                <h3 class="form-section">Penambahan BA</h3>
                                <span class="input-error-validation" v-show="empty.brand.error">@{{ empty.brand.message }}</span>
                                <div v-if="!isMultipleStores">
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
                                                    min="0"
                                                    :max="allocation.count"
                                                    :disabled="allowedPairStore != ''"
                                                    >
                                                </div>
                                                <div v-if="allowedPairStore == ''">
                                                    {{--<span class="info-span"--}}
                                                          {{--v-show="!error[allocation.name]['showWarning'] "--}}
                                                           {{-->--}}
                                                            {{--Brand @{{ allocation.name }} Di toko ini memiliki @{{ allocation.count }} tempat untuk penambahan BA Baru Tanpa merubah Alokasi--}}
                                                    {{--</span>--}}

                                                    {{--<span class="warning-span"--}}
                                                          {{--v-show="error[allocation.name]['showWarning']"--}}
                                                          {{-->--}}
                                                         {{--@{{ error[allocation.name]['message'] }}--}}
                                                    {{--</span>--}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <div v-if="isMultipleStores">
                                        <div class="col-md-6 " v-for="allocation in brandAllocationStatic">
                                            <div class="form-group">
                                                <div class="input-group">
                                                <span class="input-group-addon">
                                                @{{ allocation.name }}
                                                </span>
                                                    <input type="number"
                                                           v-model.number="sdf[allocation.name]"
                                                           class="form-control"
                                                            @keyup="oneForEachBrand(allocation.name)"
                                                            min="0"
                                                            >
                                                </div>
                                                {{--<span class="info-span">--}}
                                                    {{--Alokasi Ba akan dikalkulasikan secara otomatis untuk setiap tokonya--}}
                                                {{--</span>--}}
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div v-show="newStore">
                            <div v-for="(item, index) in newSdfCount">
                                <div v-show="index > 0">
                                    <h3 class="form-section">SDF</h3>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group" :class="{'has-error': empty.no_sdf.error}">
                                                <label>No. SDF</label>
                                                <input type="text" class="form-control" placeholder="" name="no_sdf"
                                                       v-model="newStoreData[index].no_sdf">
                                                <span class="input-error-validation" v-show="empty.no_sdf.error">@{{ empty.no_sdf.message }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tangal Permintaan</label>
                                                <div class="input-group  date date-picker" data-date-format="yyyy-mm-dd">
                                                    <input type="text" class="form-control" name="request_date" readonly
                                                           v-model="newStoreData[index].request_date"
                                                           disabled
                                                    >
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
                                            <div class="form-group" :class="{'has-error': empty.first_date.error}">
                                                <label>Tanggal Masuk</label>
                                                <div class="input-group  date date-picker" data-date-format="yyyy-mm-dd">
                                                    <input type="text" class="form-control" name="first_date" readonly
                                                           v-model="newStoreData[index].first_date"
                                                            disabled>
                                                    <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                                </div>
                                                <span class="input-error-validation" v-show="empty.first_date.error">@{{ empty.first_date.message }}</span>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group" :class="{'has-error': empty.attachment.error}"  >
                                                <label class="control-label" for="form_control_1">SDF Attachment</label>
                                                <input type="file" class="form-control" name="foto_kelahiran" @change="setSdfAttachment(index +1, $event)" />
                                            </div>
                                            <span class="input-error-validation" v-show="empty.attachment.error">@{{ empty.attachment.message }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label>Customer ID</label>
                                            <input data-role="tagsinput" type="text" class="form-control" style="width: 100% !important;display: inline-block"
                                                   v-model="newStoreData[index].customer_id"
                                                   placeholder="Customer ID " id="customer_id"
                                                   name="customer_id"
                                                   @keyup="generateStoreNo(index)"
                                                   required>
                                            {{--<input type="text" class="form-control" value="{{ (@$store) ? $store->customer_id : old('customer_id') }}" placeholder="Customer ID " id="customer_id" name="customer_id" required> </div>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Toko 1</label>
                                            <input type="text" class="form-control"
                                                   placeholder="Nama Toko 1 " id="store_name_1" name="store_name_1"
                                                   v-model="newStoreData[index].store_name_1"
                                                   required>
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Toko 2</label>
                                            <input type="text" class="form-control"
                                                   placeholder="Nama Toko 2 "
                                                   id="store_name_2" name="store_name_2"
                                                   v-model="newStoreData[index].store_name_2"
                                            >
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!-- Channel -->
                                <div class="row">
                                    <div class="col-md-12"
                                        v-if="allowedPairStore == ''"
                                    >
                                        <div class="form-group">
                                            <label>Channel</label>
                                            <select id="channel" name="channel" class="form-control  select2 channel"
                                                    v-model="newStoreData[index].channel"
                                                    :disabled="index > 0 "
                                                    >
                                                <option value="Dept Store" {{ (@$store->channel == 'Dept Store' ) ? 'selected' :''  }}>
                                                    Dept Store
                                                </option>
                                                <option value="MENSA" {{ (@$store->channel == 'MENSA' ) ? 'selected' :''  }}>
                                                    MENSA
                                                </option>
                                                <option value="Drug Store" {{ (@$store->channel == 'Drug Store' ) ? 'selected' :''  }}>
                                                    Drug Store
                                                </option>
                                                <option value="GT/MTI" {{ (@$store->channel == 'GT/MTI' ) ? 'selected' :''  }}>
                                                    GT/MTI
                                                </option>
                                                <option value="MTKA Hyper/Super" {{ (@$store->channel == 'MTKA Hyper/Super' ) ? 'selected' :''  }}>
                                                    MTKA Hyper/Super
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <!-- Account -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Account</label>
                                            <div>
                                                <select class="form-control account"
                                                        :id="account(index)"
                                                        name="account"
                                                        v-model="newStoreData[index].account"
                                                >
                                                    @foreach($account as $val)
                                                        <option value="{{$val->id}}"
                                                                {{ ($val->id != old('account')) ?: 'selected' }}
                                                                {{ (@$store->account_id != $val->id ) ?: 'selected' }}
                                                                class="acount">{{$val->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{--<div v-else>--}}
                                                {{--<input type="text" class="form-control" readonly v-model="newStoreData[index].account">--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <!-- Province dan City -->
                                <div class="row">
                                    <div class="col-md-6"
                                        >
                                        <div class="form-group" >
                                            <label>Provinsi</label>
                                            <div>
                                                <select class="form-control province"
                                                        :id="province(index)"
                                                        name="province"
                                                        v-model="newStoreData[index].province"
                                                >
                                                    @if(@$store)
                                                        <option value="{{ ($store->city->province_name) ? $store->city->province_name : '' }}">{{ ($store->city->province_name) ? $store->city->province_name : 'Pilih Provinsi' }}</option>
                                                    @else
                                                        <option value="{{ (!old('province') ) ?'': old('province') }}">{{ (old('province')) ? old('province') : 'Pilih Provinsi' }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            {{--<div v-else>--}}
                                                {{--<input type="text" class="form-control" readonly v-model="newStoreData[index].province">--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                    <!--/span-->
                                    <div class="col-md-6"
                                        >
                                        <div class="form-group">
                                            <label>Kota</label>
                                            <div>
                                                <select class="form-control edited cities"
                                                        :id="cities(index)"
                                                        name="city"
                                                        v-model="newStoreData[index].city"
                                                        {{ (@$store->city_id) ? '' : 'disabled' }} required>
                                                    @if(@$store)
                                                        <option value="{{ (@$store->city_id) ? @$store->city_id : '' }}">{{ (@$store->city_id) ? $store->city->city_name : 'Pilih Kota' }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                            {{--<div v-else>--}}
                                                {{--<input type="text" class="form-control" readonly v-model="newStoreData[index].city">--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                    <!--/span-->
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Region</label>
                                            <input type="text" class="form-control" readonly v-model="newStoreData[index].region">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 ">
                                        <div class="form-group">
                                            <label>Store No</label>
                                            <input type="text" class="form-control"
                                                   placeholder="Store No"
                                                   id="store_no"
                                                   name="store_no"
                                                   v-model="newStoreData[index].store_no"
                                            >
                                        </div>
                                    </div>
                                </div>
                                <h3 class="form-section">Alokasi BA</h3>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                               OAP
                                            </span>
                                                <input type="number"
                                                       class="form-control"
                                                       placeholder="Alokasi BA OAP "
                                                       id="alokasi_ba_oap"
                                                       v-model="newStoreData[index].alokasi_ba_oap"
                                                       :disabled="index > 0 || allowedPairStore != ''"
                                                       min="0"
                                                       name="alokasi_ba_oap">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                               MYB
                                            </span>
                                                <input type="number"
                                                       class="form-control"
                                                       placeholder="Alokasi BA MYB "
                                                       id="alokasi_ba_myb"
                                                       v-model="newStoreData[index].alokasi_ba_myb"
                                                       :disabled="index > 0 || allowedPairStore != ''"
                                                       min="0"
                                                       name="alokasi_ba_myb">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                               GAR
                                            </span>
                                                <input type="number"
                                                       class="form-control"
                                                       placeholder="Alokasi BA GAR "
                                                       id="alokasi_ba_gar"
                                                       :disabled="index > 0 || allowedPairStore != ''"
                                                       min="0"
                                                       v-model="newStoreData[index].alokasi_ba_gar"
                                                       name="alokasi_ba_gar">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                               CONS
                                            </span>
                                                <input type="number"
                                                       class="form-control"
                                                       placeholder="Alokasi BA CONS "
                                                       id="alokasi_ba_cons"
                                                       v-model="newStoreData[index].alokasi_ba_cons"
                                                       :disabled="index > 0 || allowedPairStore != ''"
                                                       min="0"
                                                       name="alokasi_ba_cons">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <div class="input-group">
                                            <span class="input-group-addon">
                                               MIX
                                            </span>
                                                <input type="number"
                                                       class="form-control"
                                                       placeholder="Alokasi BA MIX "
                                                       id="alokasi_ba_mix"
                                                       v-model="newStoreData[index].alokasi_ba_mix"
                                                       :disabled="index > 0 || allowedPairStore != ''"
                                                       min="0"
                                                       name="alokasi_ba_mix">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions right">
                            <button type="button" class="btn default"
                                    @click="resetOptionStore"
                                    v-show="!isChoosingStore"
                                    >
                                <i class="fa fa-arrow-left"></i> Pilih Ulang Opsi Toko SDF
                            </button>
                            <button type="submit"
                                    class="btn green-meadow"
                                    v-show="canAddMoreStore"
                                    @click="setStoreStatus('newStore', true)">
                            <i class="fa fa-check"></i> Tambah Toko Baru
                            </button>
                            <button type="submit"
                                    class="btn blue"
                                    :disabled="moreThanOne || chosenStore.length === 0 && oldStore || proccessInput"
                                    @click="submitNewBaSdf">
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
    <script src="/assets/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="csrf-token"]').getAttribute('content');
        new Vue({
            el: '#app',
            data: {
                isFullStoreCapacity: false,
                chosenStore: [],
                allocations: [],
                exceedLimit: false,
                sdf: {
                    no_sdf: '',
                    first_date: '',
                    request_date: moment().format('DD/MM/YYYY'),
                    CONS: 0,
                    OAP: 0,
                    GAR: 0,
                    MYB: 0,
                    MIX: 0,
                    store_id: [],
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
                    MIX: {
                        showWarning: false,
                        message: 'Penambahan Ba Baru di Brand MIX akan merubah Alokasi BA Brand MIX di Store ini '
                    },
                },
                empty: {
                    no_sdf: {
                        error: false,
                        message: 'Silahkan Masukkan Nomor Sdf'
                    },
                    first_date: {
                        error: false,
                        message: 'Silahkan Masukkan First Date Ba'
                    },
                    attachment: {
                        error: false,
                        message: 'Silahkan Masukkan Attachment Lembaran Sdf'
                    },
                    store: {
                        error: false,
                        message: 'Silahkan Pilih Toko Tempat BA Akan Bekerja'
                    },
                    brand: {
                        error: false,
                        message: 'Silahkan Pilih Brand Ba akan Bekerja'
                    }
                },
                oldStore: false,
                newStore: false,
                isChoosingStore: true,
                isMultipleStores: false,
                brandAllocationStatic: [
                                {'count': 0, 'id': 1, 'name': 'CONS'},
                                {'count': 0, 'id': 2, 'name': 'OAP'},
                                {'count': 0, 'id': 4, 'name': 'GAR'},
                                {'count': 0, 'id': 5, 'name': 'MYB'},
                                {'count': 0, 'id': 6, 'name': 'MIX'}
                              ],
                store: {
                    request_date: moment().format('DD/MM/YYYY'),
                    store_no: '',
                    customer_id: '',
                    store_name_1: '',
                    store_name_2: '',
                    channel: '',
                    account: '',
                    province: '',
                    city: '',
                    region: '',
                    supervisor: '',
                    alokasi_ba_oap: '',
                    alokasi_ba_myb: '',
                    alokasi_ba_gar: '',
                    alokasi_ba_cons: '',
                    alokasi_ba_mix: '',
                },
                sdfData: new FormData(),
                moreThanOne: false,
                alreadyAddAttachment: false,
                newSdfCount: [0],
                newStoreData: [{no_sdf: '', first_date: '', request_date: moment().format('YYYY-MM-DD'), attachment: '',
                                store_no: '', customer_id: '', store_name_1: '', store_name_2: '', channel: '', account: '', province: '', city: '', region: '',
                                alokasi_ba_oap: '', alokasi_ba_gar: '', alokasi_ba_myb: '', alokasi_ba_cons: '', alokasi_ba_mix: ''}],
                chosenAccount: {id: '', nama: '', latestId: '', region: ''},
                proccessInput: false,
                allowedPairStore: '',
                storePairId: '',
                pairBrandSdf: []
            },
            computed: {
              canAddMoreStore () {
                  return (this.newStore && this.allowedPairStore != '') ? this.allowedPairStore > 1 : this.newStore
              },
            },
            methods: {
                setOptions (url, placeholder, data, processResults, moreConstraint = {}) {
                    let defaultData =  { ajax: {url: url, method: 'POST', dataType: 'json', delay: 250, data: data, processResults: processResults},
                                        minimumInputLength: 2, width: '100%', placeholder: placeholder};
                    return Object.assign({}, defaultData, moreConstraint);
                },
                setNewStore (value) {
                    this.chosenStore  = (value !== null) ? value : [];
                    this.proccessInput = false;
                    if(this.chosenStore.length <= 1) {
                        this.isMultipleStores = false;
                        this.moreThanOne = false;
                    } else {
                        this.isMultipleStores = true
                        this.allocations = []
                    }
                    this.$http.get('/allocationInStore/' + this.chosenStore).then((response) => {
                        if(!this.isMultipleStores) this.allocations = response.body[0];
                    }, (response) => console.error(response.status))
                },
                setStoreStatus (value, mobileNewStore = false) {
                    this.isChoosingStore = false;
                    this[value] = !this[value];
                    if (this.chosenStore.length === 1) {
                        this.moreThanOne = false;
                    }
                    if (value === 'newStore' && !mobileNewStore) {
                        this.initSelect2(0);
                    }
                    if (value == 'newStore' && mobileNewStore) {
                        this[value] = !this[value];
                        this.newSdfCount.push(this.newSdfCount[(this.newSdfCount.length - 1)] + 1);
                        Object.assign(this.newStoreData[0], {no_sdf: this.sdf.no_sdf, first_date: this.sdf.first_date, request_date: this.sdf.request_date, attachment: ''})

                        let {channel, alokasi_ba_oap, alokasi_ba_gar, alokasi_ba_myb, alokasi_ba_cons, region, alokasi_ba_mix} = this.newStoreData[0];

                        this.newStoreData.push({no_sdf: '', first_date: this.sdf.first_date, request_date: this.sdf.request_date, attachment: '',
                                                store_no: '', customer_id: '', store_name_1: '', store_name_2: '', channel, account: '', province: '', city: '', region,
                                                alokasi_ba_oap, alokasi_ba_gar, alokasi_ba_myb, alokasi_ba_cons, alokasi_ba_mix})

                        setTimeout( () => this.initSelect2(this.newSdfCount.length - 1), 500)

                    }
                },
                checkAllocation (name, count) {
                    this.moreThanOne = false;
                    this.error[name]['showWarning'] = false;
                    if (parseInt(this.sdf[name]) > parseInt(count)) {
                        this.error[name]['showWarning'] = true;
                    }
                },
                setNewStoreData (key, value) {
                  this.store[key] = value;
                },
                submitNewBaSdf () {
                    this.proccessInput = this.clearToSubmit();
                    if(this.newStore) {
                        Object.assign(this.newStoreData[0], {no_sdf: this.sdf.no_sdf, first_date: this.sdf.first_date, request_date: this.sdf.request_date});
                        let count = 1;
                        this.newStoreData.map(store => {
                            for (let data  in store) {
                                this.append(`${count}[${data}]`, store[data])
                            }
                            if (this.storePairId != '') this.append(`${count}[sdfPairId]`, this.storePairId)
                            count++;
                        });
                        this.append('size', this.newStoreData.length);
                        this.$http.post('/master/store', this.sdfData).then((response) => {
                            console.log(response)
                            window.location = '/sdf'
                        }, ({body}) => {
                            let dataCombine = '';
                            for (let data in body) {
                                for (let innerData in body[data]) {
                                    dataCombine += body[data][innerData] + '<br />';
                                }
                            }
                            toastr.error(dataCombine, 'Gagal');
                            this.proccessInput = false;
                        })
                        return
                    }
                    if (this.clearToSubmit() && this.oldStore) {
                        if (this.allowedPairStore != '' && this.chosenStore.length > this.allowedPairStore) {
                            toastr.error('Gagal', `Penambahan Partner SDF ini hanya bisa menambah ${ this.allowedPairStore} toko`);
                            return;
                        }
                        this.sdf.store_id = this.chosenStore;
                        this.sdfData.append('attachment', this.sdfData.get('1[attachment]'));
                        this.sdfData.append('size', this.chosenStore.length);
                        if (this.isMultipleStores || this.allowedPairStore != '') {
                           let brands = [{data: this.sdf.CONS, name: 'CONS'} , {data: this.sdf.GAR, name: 'GAR'}, {data: this.sdf.MYB, name:'MYB'},
                                         {data: this.sdf.MYB , name: 'MYB'}, {data: this.sdf.MIX , name: 'MIX'}];
                           let passBaMobileCheck = brands.filter(item => item.data !== '' && item.data > 0)
                                                         .map(item =>  this.hasMobileBa(item.name));
                           if (passBaMobileCheck.includes(false)) {
                               this.proccessInput = false;
                               return;
                           }
                        }
                        for (let data in this.sdf) {
                            if (data instanceof Array) {
                                this.append(data, JSON.stringify(this.sdf[data]));
                            } else {
                                this.append(data, this.sdf[data]);
                            }
                        }
                        if (this.storePairId != '') this.append(`sdfPairId`, this.storePairId)
                        this.$http.post('/baSdf', this.sdfData).then((response) => {
                            window.location = '/sdf'
                            console.log(response.body)
                        }, (response) => {
                            console.log(response)
                            this.proccessInput = false;
                        })
                        return;
                    }
                    toastr.error('Gagal', 'Silahkan cek semua inputan terlebih dahulu');
                },
                clearToSubmit () {
                    let clear = true;
                    if (this.isEmpty(this.sdf.no_sdf)) {
                        this.empty.no_sdf.error = true;
                        clear = false;
                    } if (this.isEmpty(this.sdf.first_date)) {
                        this.empty.first_date.error = true;
                        clear = false;
                    } if (this.chosenStore.length === 0  && this.oldStore) {
                        this.empty.store.error = true;
                        clear = false;
                    } if (!this.alreadyAddAttachment) {
                        this.empty.attachment.error = true;
                        clear = false;
                    } if (this.noBrandInputed()) {
                        this.empty.brand.error = true;
                        clear = false;
                    }
                    return clear;
                },
                noBrandInputed () {
                    return this.sdf.CONS == 0 && this.sdf.OAP == 0 && this.sdf.GAR == 0 && this.sdf.MYB == 0 && this.sdf.MIX == 0;
                },
                isEmpty (val) {
                    return (val === '');
                },
                setSdfAttachment (index, e) {
                    this.alreadyAddAttachment = true;
                    this.append(`${index}[attachment]`, e.target.files[0])
                },
                append (key, value) {
                    this.sdfData.append(key, value)
                },
                oneForEachBrand (brandName) {
                    this.moreThanOne = false;
                    if (this.sdf[brandName] > 1 && this.isMultipleStores) {
                        this.moreThanOne = true;
                        swal({
                            title: 'Gagal',
                            text:  'Alokasi Ba di Mobile Store hanya bisa menambah 1 Ba per Brand ',
                            type: 'error',
                            timer: 1000,
                            showConfirmButton: false
                        })
                    }
                    if (this.isMultipleStores) {
                        this.hasMobileBa(brandName)
                    }
                },
                hasMobileBa (brandName) {
                    this.$http.post('/hasMobileBaCheck', {brand: brandName, stores: this.chosenStore}).then((response) => {
                        if (!response.body.success) {
                            this.moreThanOne = true;
                            swal({
                                title: 'Gagal',
                                text:  response.body.error,
                                type: 'error',
                                timer: 1000,
                                showConfirmButton: false
                            })
                            return true;
                        }
                        this.proccessInput = false;
                        return false;
                        }, response => console.error(response.body));
                },
                decideStoreParams (params) {
                    let newParams = {
                        storeName: params.term
                    };
                    if (this.chosenStore.length > 0 ) {
                        newParams['firstStore'] = this.chosenStore[0]
                    }
                    return newParams;
                },
                syncMobileNewStore (newData) {
                    this.newStoreData.map((item) => Object.assign(item, newData))
                },
                generateStoreNo (index) {
                    if (this.newStoreData[index].channel == 'Dept Store' || this.newStoreData[index].channel == 'GT/MTI'
                        || this.newStoreData[index].channel == 'Drug Store'  && this.newStoreData[index].account != '' ) {
                        this.generateStoreNoFromAccount(this.chosenAccount.id, index)
                        return;
                    }
                    let storeNumber = this.newStoreData[index].customer_id.split('-').filter((item) => !isNaN(item));
                    let storeNoNumber = ( storeNumber[index] !== undefined ) ? storeNumber[index] : this.newStoreData[index].customer_id;
                    this.newStoreData[index].store_no = this.newStoreData[index].channel.replace(/\s/g, '') + this.newStoreData[index].region + storeNoNumber;
                },
                generateStoreNoFromAccount (val, index = 0) {
                    if (this.chosenAccount.id != val && this.newStoreData[0].region != '') {
                        this.$http.get(`/master/store/account/${val}/${this.newStoreData[0].region}`).then( ({data}) => {
                            Object.assign(this.chosenAccount, {id: val, nama: data.accountName, latestId: data.latestId, region: data.region });
                            this.newStoreData[index].store_no = this.chosenAccount.nama.replace(/\s/g, '') + data.region + data.latestId;
                        }, response => console.error(response))
                    }
                    if (this.chosenAccount.id != '' ) {
                        if (index != 0) {
                            const newID = this.pad('000', parseInt(this.chosenAccount.latestId) + (this.newSdfCount.length - 1), true)
                            this.newStoreData[index].store_no = this.chosenAccount.nama.replace(/\s/g, '') + this.chosenAccount.region + newID;
                            return
                        }
                        this.newStoreData[index].store_no = this.chosenAccount.nama.replace(/\s/g, '') + this.chosenAccount.region + this.chosenAccount.latestId;
                    }
                },
                pad (pad, str, padLeft) {
                    if (typeof str === 'undefined')
                        return pad;
                    if (padLeft) {
                        return (pad + str).slice(-pad.length);
                    } else {
                        return (str + pad).substring(0, pad.length);
                    }
                },
                sdfSameBrand (brandId, sdfBrand) {
                    return (brandId == sdfBrand.brand_id) ? 1 : 0;
                },
                changeFormatDate (date) {
                    return moment(date).format('DD/MM/YYYY')
                },
                resetOptionStore () {
                    this.isChoosingStore = ! this.isChoosingStore;
                    this.oldStore = false;
                    this.newStore = false;
                },
                initSelect2 (index) {
                    $(`#account${index}`).select2({
                        width: '100%',
                        placeholder: 'Pilih Account',
                    });
                    $(`#account${index}`).on('select2:select', () => {
                        Object.assign(this.newStoreData[index], {account: $(`#account${index}`).val() })
                        if (this.newStoreData[index].channel == 'Dept Store' || this.newStoreData[index].channel == 'GT/MTI'
                            || this.newStoreData[index].channel == 'Drug Store') {
                            let val =  $(`#account${index}`).val()
                            this.generateStoreNoFromAccount(val, index);
                        }
//                        this.syncMobileNewStore({account:  $(`#account${index}`).val()});
                    })
                    $(`#province${index}`).select2({
                        width: '100%',
                        placeholder: 'Pilih Provinsi',
                        ajax: {
                            url: "{{ url('/provinceFilter') }}",
                            method: 'POST',
                            dataType: 'json',
                            data: function (params) {
                                return {
                                    term: params.term
                                }
                            },
                            processResults: function (data) {
                                return {
                                    results: $.map(data, function (obj) {
                                        return {id: obj.province_name, text: obj.province_name}
                                    })
                                }
                            },
                            cache: true
                        }
                    });
                    $(`#province${index}`).change(function () {
                        $(`#cities${index}`).prop('disabled', false);
                    });
                    $(`#province${index}`).on('select2:select', () => {
                        Object.assign(this.newStoreData[index], {province: $(`#province${index}`).val()})
//                        this.syncMobileNewStore({province:  $(`#province${index}`).val()});
                    })
                    $(`#cities${index}`).select2({
                        width: '100%',
                        placeholder: 'Pilih Kota',
                        ajax: {
                            url: "{{ url('/cityFilter') }}",
                            method: 'POST',
                            dataType: 'json',
                            data: function (params) {
                                return {
                                    province_name: $(`#province${index}`).val(),
                                    term: params.term
                                }
                            },
                            processResults: function (data) {
                                return {
                                    results: $.map(data, function (obj) {
                                        return {id: obj.id, text: obj.city_name}
                                    })
                                }
                            },
                            cache: true
                        }
                    });
                    $(`#cities${index}`).on('select2:select', () => {
                        Object.assign(this.newStoreData[index], {city: $(`#cities${index}`).val()})
                        this.$http.get('/cityRegion/' + this.newStoreData[index].city).then(response => {
                            this.syncMobileNewStore({region: response.body});
                            if (this.newStoreData[index].channel == 'Dept Store' || this.newStoreData[index].channel == 'GT/MTI'
                                || this.newStoreData[index].channel == 'Drug Store'  && this.newStoreData[index].account != '' ) {
                                this.generateStoreNoFromAccount(this.newStoreData[index].account);
                                return;
                            }
                            let storeNumber = this.newStoreData[index].customer_id.split('-').filter((item) => !isNaN(item));
                            let storeNoNumber = ( storeNumber[index] !== undefined ) ? storeNumber[index] : this.newStoreData[index].customer_id;
                            this.newStoreData[index].store_no = this.newStoreData[index].channel.replace(/\s/g, '') + this.newStoreData[index].region + storeNoNumber;
                        }, response => console.error(response))

//                        this.syncMobileNewStore({city:  $(`#cities${index}`).val()});
                    })
                },
                account (index) {
                    return `account${index}`;
                },
                province (index) {
                    return `province${index}`;
                },
                cities (index) {
                    return `cities${index}`;
                }
            },
            watch: {
              'sdf.no_sdf' (value) {
                  if (!this.isEmpty(this.sdf.no_sdf)) {
                      this.empty.no_sdf.error = false;
                  }
                  if (this.newStoreData.length > 1) {
                      Object.assign(this.newStoreData[0], {no_sdf: value})
                  }
              },
              'sdf.first_date' (value) {
                  if (!this.isEmpty(this.sdf.first_date)) {
                      this.empty.first_date.error = false;
                  }
                  if (this.newStoreData.length > 1) {
                      Object.assign(this.newStoreData[0], {first_date: value})
                  }
              },
              'sdf.request_date' (value) {
                  if (this.newStoreData.length > 1) {
                      Object.assign(this.newStoreData[0], {request_date: value})
                  }
              },
              'chosenStore' () {
                  if (this.chosenStore.length !== 0 ) {
                      this.empty.store.error = false;
                  }
              },
              'alreadyAddAttachment' () {
                  if (this.alreadyAddAttachment) {
                     this.empty.attachment.error = false;
                  }
              },
              'newStoreData[0].city' () {
//                this.$http.get('/cityRegion/' + this.newStoreData[0].city).then(response => {
//                    this.syncMobileNewStore({region: response.body});
//                    if (this.newStoreData[0].channel == 'Dept Store' || this.newStoreData[0].channel == 'GT/MTI' || this.newStoreData[0].channel == 'Drug Store'  && this.newStoreData[0].account != '' ) {
//                        this.generateStoreNoFromAccount(this.newStoreData[0].account);
//                        return;
//                    }
//                    let storeNumber = this.newStoreData[0].customer_id.split('-').filter((item) => !isNaN(item));
//                    let storeNoNumber = ( storeNumber[0] !== undefined ) ? storeNumber[0] : this.newStoreData[0].customer_id;
//                    this.newStoreData[0].store_no = this.newStoreData[0].channel.replace(/\s/g, '') + this.newStoreData[0].region + storeNoNumber;
//                }, response => console.error(response))
              },
              'newStoreData[0].alokasi_ba_oap' (allocation) {
                  this.syncMobileNewStore({alokasi_ba_oap: allocation});
              },
              'newStoreData[0].alokasi_ba_myb' (allocation) {
                this.syncMobileNewStore({alokasi_ba_myb: allocation});
              },
              'newStoreData[0].alokasi_ba_gar' (allocation) {
                this.syncMobileNewStore({alokasi_ba_gar: allocation});
              },
              'newStoreData[0].alokasi_ba_cons' (allocation) {
                this.syncMobileNewStore({alokasi_ba_cons: allocation});
              },
              'newStoreData[0].mixcons' (allocation) {
                this.syncMobileNewStore({alokasi_ba_mix: allocation});
              },
              'newStoreData[0].channel' (val) {
                  let storeNumber = this.newStoreData[0].customer_id.split('-').filter((item) => !isNaN(item));
                  let storeNoNumber = ( storeNumber[0] !== undefined ) ? storeNumber[0] : this.newStoreData[0].customer_id;
                  this.newStoreData[0].store_no = this.newStoreData[0].channel.replace(/\s/g, '') + this.newStoreData[0].region + storeNoNumber;
                  if (val == 'Dept Store' || this.newStoreData[0].channel == 'GT/MTI' || this.newStoreData[0].channel == 'Drug Store'  && this.newStoreData[0].account != '') {
                      this.generateStoreNoFromAccount(this.newStoreData[0].account);
                  }
              },
              'newStoreData[0].account' (val) {
//                  if (this.newStoreData[0].channel == 'Dept Store' || this.newStoreData[0].channel == 'GT/MTI' || this.newStoreData[0].channel == 'Drug Store') {
//                      this.generateStoreNoFromAccount(val);
//                  }
              },
              'newStoreData[0].region' (val) {
                  if (this.newStoreData[0].channel == 'Dept Store' || this.newStoreData[0].channel == 'GT/MTI' || this.newStoreData[0].channel == 'Drug Store') {
                      this.generateStoreNoFromAccount(this.newStoreData[0].account);
                  }
              }
            },
            ready () {
                if (window.location.search != undefined && window.location.search != '') {
                    let encodedId = window.location.search.substring(1, (window.location.search.length - 2));
                    this.storePairId = window.atob(encodedId)
                    this.$http.get(`/sdf/${this.storePairId}`).then(({data}) => {
                        if (data != undefined || data != null) {
                            this.pairBrandSdf = data.brand;
                            console.log(data.brand);
                            if (data.brand.length > 1) {
//                                swal({
//                                    title: 'Pilih Brand Tujuan',
//                                    input: 'radio',
//                                    inputOptions: {a: 'a'},
//                                    inputValidator: function (result) {
//                                        return new Promise(function (resolve, reject) {
//                                            if (result) {
//                                                resolve()
//                                            } else {
//                                                reject('You need to select something!')
//                                            }
//                                        })
//                                    }
//                                }).then(function (result) {
//                                    swal({
//                                        type: 'success',
//                                        html: 'You selected: ' + result
//                                    })
//                                })
                            }
                            this.allowedPairStore = Math.floor(1 / data.brand[0].pivot.numberOfBa) - 1;
                            let dataSdf = { no_sdf: '', first_date: this.changeFormatDate(data.first_date), request_date: this.changeFormatDate(data.request_date), attachment: '',
                                         store_no: '', customer_id: '', store_name_1: '', store_name_2: '', channel: data.store.channel,
                                         account: data.store.account_id, province: data.store.city.province_name, city: data.store.city.id, region: data.store.region_id,
                                         alokasi_ba_oap: this.sdfSameBrand(2, data.brand[0].pivot), alokasi_ba_gar: this.sdfSameBrand(4, data.brand[0].pivot), alokasi_ba_myb: this.sdfSameBrand(5, data.brand[0].pivot),
                                         alokasi_ba_cons: this.sdfSameBrand(1, data.brand[0].pivot), alokasi_ba_mix: this.sdfSameBrand(6, data.brand[0].pivot)
                                       };
                            let { alokasi_ba_oap, alokasi_ba_gar, alokasi_ba_cons, alokasi_ba_myb, alokasi_ba_mix} = dataSdf;
                            let pushData = [{count: alokasi_ba_cons, id: 1, name: 'CONS', store_id: data.store_id},
                                            {count: alokasi_ba_oap, id: 2, name: 'OAP', store_id: data.store_id},
                                            {count: alokasi_ba_gar, id: 4, name: 'GAR', store_id: data.store_id},
                                            {count: alokasi_ba_myb, id: 5, name: 'MYB', store_id: data.store_id},
                                            {count: alokasi_ba_mix, id: 6, name: 'MIX', store_id: data.store_id}];
                            this.allocations.push(pushData)
                            let assignSdf = {no_sdf: '', first_date: this.changeFormatDate(data.first_date), request_date: this.changeFormatDate(data.request_date),
                                             CONS: alokasi_ba_cons, OAP: alokasi_ba_oap, GAR: alokasi_ba_gar, MYB: alokasi_ba_myb, MIX: alokasi_ba_mix};
                            Object.assign(this.sdf, assignSdf);
                            Object.assign(this.newStoreData[0], dataSdf)
                        }
                    }, response => console.error(response));
                }
                const self = this;
                $('#store-ajax').select2(self.setOptions('/storeFilter', 'Nama Toko', params => this.decideStoreParams(params),  (data, params) => {
                    return {
                        results: $.map(data, function (obj) {
                            return {id: obj.id, text: obj.store_name_1}
                        })
                    }
                }));
                $('#store-ajax').on('select2:select', function () {
                    self.setNewStore($('#store-ajax').val());
                })
                $('#store-ajax').on('select2:unselect', function () {
                    self.setNewStore($('#store-ajax').val());
                })
                // New Store Javascript
                $.fn.select2.defaults.set("theme", "bootstrap");
                $('#channel').select2({
                    placeholder: 'Pilih Channel',
                    width: '100%',
                });
                $('#channel').on('select2:select', () => {
                    this.syncMobileNewStore({channel: $('#channel').val()});
                })

                /*
                $('#account').select2({
                    width: '100%',
                    placeholder: 'Pilih Account',
                });
                $('#account').on('select2:select', () => {
                    this.syncMobileNewStore({account:  $('#account').val()});
                })

                $('#province').select2({
                    width: '100%',
                    placeholder: 'Pilih Provinsi',
                    ajax: {
                        url: "{{ url('/provinceFilter') }}",
                        method: 'POST',
                        dataType: 'json',
                        data: function (params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function (data) {

                            return {
                                results: $.map(data, function (obj) {
                                    return {id: obj.province_name, text: obj.province_name}
                                })
                            }
                        },
                        cache: true
                    }
                });
                var isError = {{ (is_null(old('province'))) ? 'false':'true'}};
                if (isError) {
                    $('#cities').prop('disabled', false);
                }
                $('#province').change(function () {
                    $('#cities').prop('disabled', false);
                });
                $('#province').on('select2:select', () => {
                    this.syncMobileNewStore({province:  $('#province').val()});
                })

                $('#cities').select2({
                    width: '100%',
                    placeholder: 'Pilih Kota',
                    ajax: {
                        url: "{{ url('/cityFilter') }}",
                        method: 'POST',
                        dataType: 'json',
                        data: function (params) {
                            return {
                                province_name: $('.province').val(),
                                term: params.term
                            }
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (obj) {
                                    return {id: obj.id, text: obj.city_name}
                                })
                            }
                        },
                        cache: true
                    }
                });
                $('#cities').on('select2:select', () => {
                    this.syncMobileNewStore({city:  $('#cities').val()});
                })
                */

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
    </script>
@endsection