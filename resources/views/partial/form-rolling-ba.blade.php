<!-- modal form rolling -->
<div class="modal fade" id="form-rolling-modal" role="basic" aria-hidden="true" data-focus-on="input:first"
     data-backdrop="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Form Rolling BA</h4>
            </div>
            <div class="modal-body">
                <div id="intro" class="centerin " v-show="isIntro">
                    <label class="control-label">Pilih Toko BA Bekerja</label>
                    <div class="note note-info clickable" v-for="store in baStore" @click="
                    setStore(store.id, store.store_name_1)">
                    <h4 class="block">@{{ store.store_name_1 }}</h4>
                </div>
                <div  class="note clickable bg-green-jungle bg-font-green-jungle"
                     @click="setStore(987654, ba.tokoUser, true)">
                    <h4 class="block">Rolling Semua Toko</h4>
                </div>
                <div class="note  clickable bg-red-intense bg-font-red-intense" @click="setStore(012344321, 'Penambahan Toko baru')">
                    <h4 class="block">Tambah Toko Baru BA Bekerja</h4>
                 </div>
        </div>
        <div id="form" v-show="isFillingForm">
            <div class="form-group form-md-line-input">
                <label class="control-label" for="form_control_1">Nama BA
                </label>
                <div class="">
                    <input type="text" class="form-control" placeholder="" name="no_sdf" readonly v-model="ba.nama">
                </div>
            </div>

            <div class="form-group form-md-line-input">
                <label class="control-label" for="form_control_1">Toko yang ditinggalkan
                </label>
                <div class="">
                    <input type="text" class="form-control" placeholder="" name="no_sdf" readonly v-model="ba.tokoName">
                </div>
            </div>

            <div class="form-group form-md-line-input">
                <label class="control-label" for="form_control_1">Brand
                </label>
                <div class="">
                    <input type="text" class="form-control" placeholder="" name="no_sdf" readonly v-model="ba.brand">
                </div>
            </div>

            <div class=" form-group form-md-line-input"
                 :class="{'has-error': error.emptyRollingStatus.isError}"
                 v-show="ba.tokoId != 012344321"
            >
                <label class="control-label" for="form_control_1">Tipe Rolling</label>
                <div class="clearfix" v-if="ba.status === 'mobile'">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default" @click="setRollingStatus('vacant')">
                        <input type="radio" class="toggle"> Vacant </label>
                        <label class="btn btn-default" @click="setRollingStatus('hold')">
                        <input type="radio" class="toggle">Hold </label>
                        <label class="btn btn-default" @click="setRollingStatus('takeout')">
                        <input type="radio" class="toggle"> Takeout Stay  </label>
                        <label class="btn btn-default" @click="setRollingStatus('takeoutMobile')">
                        <input type="radio" class="toggle"> Takeout Mobile  </label>
                        <label class="btn btn-default" @click="setRollingStatus('switch')">
                        <input type="radio" class="toggle"> Switch </label>
                    </div>
                </div>

                <div class="clearfix" v-if="ba.status === 'stay'">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default" @click="setRollingStatus('vacant')">
                        <input type="radio" class="toggle"> Vacant </label>
                        <label class="btn btn-default" @click="setRollingStatus('hold')">
                        <input type="radio" class="toggle">Hold </label>
                        <label class="btn btn-default" @click="setRollingStatus('takeoutMobile')">
                        <input type="radio" class="toggle"> Takeout </label>
                        <label class="btn btn-default" @click="setRollingStatus('switch')">
                        <input type="radio" class="toggle"> Switch </label>
                    </div>
                </div>
                <span class="input-error-validation"
                      v-show="error.emptyRollingStatus.isError">@{{ error.emptyRollingStatus.message }}</span>
            </div>

            <div v-show="ba.statusRolling === 'switch'">
                <div class="form-group form-md-line-input" :class="{'has-error': error.emptySwitchBa.isError}">
                    <label class="control-label" for="form_control_1">BA yang ingin di switch
                    </label>
                    <div class="">
                        <select id="ba-ajax" name="store_id" data-tabindex="2">
                            <option value="" selected="selected"></option>
                        </select>
                        <div class="form-control-focus"></div>
                    </div>
                    <span class="input-error-validation"
                          v-show="error.emptySwitchBa.isError">@{{ error.emptySwitchBa.message }}</span>
                </div>

            </div>

            <div v-show="ba.statusRolling !== 'takeout' && ba.statusRolling !== 'switch'">
                <div class="form-group form-md-line-input" :class="{'has-error': error.emptyStore.isError}">
                    <label class="control-label" for="form_control_1">Toko Tujuan
                    </label>
                    <div class="">
                        <select id="store-ajax" name="store_id" data-tabindex="2">
                            <option value="" selected="selected"></option>
                        </select>
                        <div class="form-control-focus"></div>
                    </div>
                    <span class="input-error-validation"
                          v-show="error.emptyStore.isError">@{{ error.emptyStore.message }}</span>
                </div>

                <div v-if="ba.bulkRolling && ba.bulkRollingPair >= 1">
                    <div class="form-group form-md-line-input"
                         :class="{'has-error': error.emptyReplacementBa.isError}"
                         v-if="ba.bulk.replaces[0] != null"
                    >
                        <label class="control-label" for="form_control_1">Menggantikan Ba
                        </label>
                        <div class="">
                            <div class="mt-radio-inline">
                                <label class="mt-radio mt-radio-outline"
                                       v-for="baReplace in ba.bulk.replaces[0]"> @{{ baReplace.ba_name }}
                                    <input type="radio" :value="baReplace.wip_id" :name="baReplacementWip(0)"
                                           v-model="ba.bulk.wip[0]">
                                    <span></span>
                                </label>
                            </div>
                            <span class=" input-error-validation"
                                  v-show="error.emptyReplacementBa.isError">@{{ error.emptyReplacementBa.message }}</span>
                        </div>
                    </div>

                    <div v-for="n in ba.bulkRollingPair">
                        <div class="form-group form-md-line-input" :class="{'has-error': error.emptyStore.isError}">
                            <label class="control-label" for="form_control_1">Toko Tujuan
                            </label>
                            <div class="">
                                <select :id="storeAjax(n)" name="store_id" data-tabindex="4" :disabled="ba.bulk.wip[0] == undefined">
                                    <option value="" selected="selected"></option>
                                </select>
                                <div class="form-control-focus"></div>
                            </div>
                            <span class="input-error-validation"
                                  v-show="error.emptyStore.isError">@{{ error.emptyStore.message }}</span>
                       </div>

                        <div class="form-group form-md-line-input"
                             :class="{'has-error': error.emptyReplacementBa.isError}"
                             v-if="ba.bulk.replaces[n+1] != null"
                        >
                            <label class="control-label" for="form_control_1">Menggantikan Ba
                            </label>
                            <div class="">
                                <div class="mt-radio-inline">
                                    <label class="mt-radio mt-radio-outline"
                                           v-for="baReplace in ba.bulk.replaces[n+1]"> @{{ baReplace.ba_name }}
                                        <input type="radio" :value="baReplace.wip_id" :name="baReplacementWip(n+1)"
                                               v-model="ba.bulk.wip[n+1]">
                                        <span></span>
                                    </label>
                                </div>
                                <span class=" input-error-validation"
                                      v-show="error.emptyReplacementBa.isError">@{{ error.emptyReplacementBa.message }}</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div v-else>
                    {{--<div class="form-group form-md-line-input">--}}
                        {{--<label class="control-label" for="form_control_1">Nama Supervisor Toko Tujuan--}}
                        {{--</label>--}}
                        {{--<div class="">--}}
                            {{--<input type="text" class="form-control" placeholder="" readonly v-model="ba.newReo.newStoreReo">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group form-md-line-input" v-show="needToReplaceBa"
                         :class="{'has-error': error.emptyReplacementBa.isError}">
                        <label class="control-label" for="form_control_1">Menggantikan Ba
                        </label>
                        <div class="">
                            <div class="mt-radio-inline">
                                <label class="mt-radio mt-radio-outline"
                                       v-for="baReplace in baInWip"> @{{ baReplace.ba_name }}
                                    <input type="radio" :value="baReplace.ba_id" name="baReplacementWip"
                                           v-model="ba.replaceBaId" v-if="!ba.bulkRolling">
                                    <input type="radio" :value="baReplace.wip_id" name="baReplacementWip"
                                           v-model="ba.bulk.wip[0]" v-if="ba.bulkRolling">
                                    <span></span>
                                </label>
                            </div>
                            <span class=" input-error-validation"
                                  v-show="error.emptyReplacementBa.isError">@{{ error.emptyReplacementBa.message }}</span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input" :class="{'has-error': error.emptyNewBrand.isError}"
                         v-show="!needToReplaceBa && ba.newStore !== '' && !isFullStoreCapacity">
                        <label class="control-label" for="form_control_1">Brand tujuan
                        </label>
                        <div class="">
                            <div class="mt-radio-list">
                                <label class="mt-radio mt-radio-outline" v-for="brand in brands"> @{{ brand.name }}
                                    <input type="radio" :value="brand.id" name="test" v-model="ba.newBrand">
                                    <span></span>
                                </label>
                            </div>
                            <span class="input-error-validation"
                                  v-show="error.emptyNewBrand.isError">@{{ error.emptyNewBrand.message }}</span>
                        </div>
                    </div>
                    <div class="mt-element-ribbon bg-grey-steel" v-show="isFullStoreCapacity"
                     style="background: #f7f7f7 !important">
                    <div class="ribbon ribbon-left ribbon-vertical-left ribbon-shadow ribbon-border-dash-vert ribbon-color-danger uppercase">
                        <div class="ribbon-sub ribbon-bookmark"></div>
                        <i class="fa fa-exclamation"></i>
                    </div>
                    <h3 class="ribbon-content">Kebutuhan Ba di Toko ini sudah terpenuhi</h3>
                </div>
                </div>

                <div class="form-group form-md-line-input" :class="{'has-error': error.emptyFirstDate.isError}">
                    <label class="control-label" for="form_control_1">Efektif Perolingan
                    </label>
                    <div class="">
                        <div class="input-group  date date-picker" data-date-format="dd-mm-yyyy">
                            <input type="text" class="form-control" name="first_date" readonly v-model="ba.firstDate">
                            <span class="input-group-btn">
                                                                        <button class="btn default" type="button">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </button>
                                                                    </span>
                        </div>
                        <div class="form-control-focus"></div>
                        <span class=" input-error-validation"
                              v-show="error.emptyFirstDate.isError">@{{ error.emptyFirstDate.message }}</span>
                    </div>
                </div>

                <div class="form-group form-md-line-input"
                     :class="{'has-error': error.emptyFirstDate.isError}"
                     v-show="ba.joinDateMds.show"
                    >
                    <label class="control-label" for="form_control_1">Join Date MDS
                    </label>
                    <div class="">
                        <div class="input-group  date date-picker" data-date-format="dd-mm-yyyy">
                            <input type="text" class="form-control" name="first_date" readonly v-model="ba.joinDateMds.joinDate">
                            <span class="input-group-btn">
                                                                        <button class="btn default" type="button">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </button>
                                                                    </span>
                        </div>
                        <div class="form-control-focus"></div>
                        <span class=" input-error-validation"
                              v-show="error.emptyFirstDate.isError">@{{ error.emptyFirstDate.message }}</span>
                    </div>
                </div>

            </div>
        </div>
        <div id="notFound" v-show="hasError">
            <h2>Ga ada Toko </h2>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
        <button type="submit" class="btn green" @click="roleBa"  :disabled="isFullStoreCapacity">Save</button>
    </div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- End Modal form Rolling -->