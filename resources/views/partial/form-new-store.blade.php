<div class="row">
    <div class="col-md-12 ">
        <div class="form-group">
            <label>Store No</label>
            <input type="text" class="form-control"
                   value="{{  (@$store) ? $store->store_no : old('store_no') }}"
                   placeholder="Store No"
                   id="store_no"
                   name="store_no"
                   v-model="store.store_no"
                   required
            >
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 ">
        <div class="form-group">
            <label>Customer ID</label>
            <input data-role="tagsinput" type="text" class="form-control"
                   style="width: 100% !important;display: inline-block"
                   value="{{ (@$store) ? $store->customer_id : old('customer_id') }}"
                   v-model="store.customer_id"
                   placeholder="Customer ID " id="customer_id"
                   name="customer_id"
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
                   value="{{ (@$store) ? $store->store_name_1 : old('store_name_1') }}"
                   placeholder="Nama Toko 1 " id="store_name_1" name="store_name_1"
                   v-model="store.store_name_1"
                   required>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Toko 2</label>
            <input type="text" class="form-control"
                   value="{{ (@$store) ? $store->store_name_2 :old('store_name_2') }}"
                   placeholder="Nama Toko 2 "
                   id="store_name_2" name="store_name_2"
                   v-model="store.store_name_2"
            >
        </div>
    </div>
    <!--/span-->
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Channel</label>
            <select id="channel" name="channel" class="form-control  select2"
                    v-model="store.channel"
                    required>
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
<!--/span-->
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Account</label>
            <select class="form-control"
                    id="account"
                    name="account"
                    v-model="store.account"
                    required>
                <option value="">Pilih account</option>
                @foreach($account as $val)
                    <option value="{{$val->id}}"
                            {{ ($val->id != old('account')) ?: 'selected' }}
                            {{ (@$store->account_id != $val->id ) ?: 'selected' }}
                            class="acount">{{$val->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <!--/span-->
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Provinsi</label>
            <select class="form-control"
                    id="province"
                    name="province"
                    v-model="store.province"
                    required>
                @if(@$store)
                    <option value="{{ ($store->city->province_name) ? $store->city->province_name : '' }}">{{ ($store->city->province_name) ? $store->city->province_name : 'Pilih Provinsi' }}</option>
                @else
                    <option value="{{ (!old('province') ) ?'': old('province') }}">{{ (old('province')) ? old('province') : 'Pilih Provinsi' }}</option>
                @endif
            </select>
        </div>
    </div>
    <!--/span-->
    <div class="col-md-6">
        <div class="form-group">
            <label>Kota</label>
            <select class="form-control edited"
                    id="cities"
                    name="city"
                    v-model="store.city"
                    {{ (@$store->city_id) ? '' : 'disabled' }} required>
                @if(@$store)
                    <option value="{{ (@$store->city_id) ? @$store->city_id : '' }}">{{ (@$store->city_id) ? $store->city->city_name : 'Pilih Kota' }}</option>
                @endif
            </select>
        </div>
    </div>
    <!--/span-->
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Region</label>
            <input type="text" class="form-control" readonly v-model="store.region">
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
                <input type="number" {{ (@$store) ? "disabled" : "" }}
                       value="{{ (@$store) ? $store->alokasi_ba_oap : old('alokasi_ba_oap') }}"
                       class="form-control"
                       placeholder="Alokasi BA OAP "
                       id="alokasi_ba_oap"
                       v-model="store.alokasi_ba_oap"
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
                <input type="number" {{ (@$store) ? "disabled" : "" }}
                       value="{{ (@$store) ? $store->alokasi_ba_myb : old('alokasi_ba_myb') }}"
                       class="form-control"
                       placeholder="Alokasi BA MYB "
                       id="alokasi_ba_myb"
                       v-model="store.alokasi_ba_myb"
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
                <input type="number" {{ (@$store) ? "disabled" : "" }}
                       value="{{ (@$store) ? $store->alokasi_ba_gar : old('alokasi_ba_gar') }}"
                       class="form-control"
                       placeholder="Alokasi BA GAR "
                       id="alokasi_ba_gar"
                       v-model="store.alokasi_ba_gar"
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
                <input type="number" {{ (@$store) ? "disabled" : "" }}
                       value="{{ (@$store) ? $store->alokasi_ba_cons : old('alokasi_ba_cons') }}"
                       class="form-control"
                       placeholder="Alokasi BA CONS "
                       id="alokasi_ba_cons"
                       v-model="store.alokasi_ba_cons"
                       name="alokasi_ba_cons">
            </div>
        </div>
    </div>
</div>