<!-- modal form rolling -->
<div class="modal fade" id="form-join-back-ba" role="basic" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Form Join Back BA</h4>
            </div>
            <div class="modal-body">
                <div>
                    <div class="form-group form-md-line-input">
                        <label class="control-label" for="form_control_1">Nama BA
                        </label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="" name="no_sdf" readonly
                                   v-model="ba.nama">
                        </div>
                    </div>
                    <div class="form-group form-md-line-input"
                         :class="{'has-error': error.emptyChildBirthDate.isError}"
                         v-show="ba.resign_reason === 'Hamil'"
                        >
                        <label class="control-label" for="form_control_1">Tanggal lahir Anak
                        </label>
                        <div class="">
                            <div class="input-group  date date-picker"  data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control" name="first_date" readonly v-model="ba.birthDateChild">
                                <span class="input-group-btn">
                                    <button class="btn default" type="button">
                                        <i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                            <span class=" input-error-validation" v-show="error.emptyChildBirthDate.isError">@{{ error.emptyChildBirthDate.message }}</span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input"
                         :class="{'has-error': error.emptyAkteKelahiran.isError}"
                         v-show="ba.resign_reason === 'Hamil'"
                         >
                        <label class="control-label" for="form_control_1">Akta Kelahiran</label>
                        <input type="file" class="form-control" name="foto_kelahiran" @change="setAkteKelahiran" />
                        <span class=" input-error-validation" v-show="error.emptyAkteKelahiran.isError">@{{ error.emptyAkteKelahiran.message }}</span>
                    </div>
                    <div class="form-group form-md-line-input" :class="{'has-error': error.emptyStore.isError}">
                        <label class="control-label" for="form_control_1">Toko Tujuan
                        </label>
                        <div class="">
                            <select id="store-ajax-join-back" name="store_id">
                                <option value="" selected="selected"></option>
                            </select>
                            <div class="form-control-focus"></div>
                        </div>
                        <span class="input-error-validation"
                              v-show="error.emptyStore.isError">@{{ error.emptyStore.message }}</span>
                    </div>
                    <div class="form-group form-md-line-input" v-show="needToReplaceBa"
                         :class="{'has-error': error.emptyReplacementBa.isError}">
                        <label class="control-label" for="form_control_1">Menggantikan BA
                        </label>
                        <div class="">
                            <div class="mt-radio-inline">
                                <label class="mt-radio mt-radio-outline"
                                       v-for="baReplace in baInWip"> @{{ baReplace.ba_name }}
                                    <input type="radio" :value="baReplace.ba_id" name="baReplacementWip"
                                           v-model="ba.replaceBaId">
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
                    <div class="form-group form-md-line-input"
                         :class="{'has-error': error.emptyNewJoinDate.isError}"
                        >
                        <label class="control-label" for="form_control_1">Join Date</label>
                        <div v-if="ba.resign_reason !== 'Hamil'"
                             class="input-group  date date-picker"  data-date-format="dd-mm-yyyy"
                             >
                            <input type="text" class="form-control" name="first_date" readonly v-model="ba.newJoinDate">
                             <span class="input-group-btn">
                                <button class="btn default" type="button">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            </span>

                            <span class=" input-error-validation" v-show="error.emptyNewJoinDate.isError">@{{ error.emptyNewJoinDate.message }}</span>
                        </div>
                        <div v-else>
                            <input type="text" class="form-control" name="first_date" readonly
                                   v-model="ba.newJoinDate">
                            <span class="info-span" v-show="ba.birthDateChild != ''  ">@{{ joinDateInfo  }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button type="submit" class="btn green" @click="joinBackBa"  :disabled="isFullStoreCapacity">Save</button>
            </div>
        </div>
    </div>
    <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- End Modal form Rolling -->