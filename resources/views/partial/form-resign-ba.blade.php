<div class="modal fade" id="form-resign-modal" role="basic" aria-hidden="true" tabindex="-1"
     xmlns="http://www.w3.org/1999/html">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Form Resign BA</h4>
            </div>
            <div class="modal-body">
                <div id="form" >
                    <div class="form-group form-md-line-input">
                        <label class="control-label" for="form_control_1">Nama BA
                        </label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="" name="no_sdf" readonly v-model="ba.nama">
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="control-label" for="form_control_1">Nama Store
                        </label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="" name="no_sdf" readonly v-model="ba.tokoUser">
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="control-label" for="form_control_1">Brand
                        </label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="" name="no_sdf" readonly v-model="ba.brand">
                        </div>
                    </div>
                    <div class="form-group form-md-line-input">
                        <label class="control-label" for="form_control_1">Join Date
                        </label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="" name="no_sdf" readonly :value="readableDateFormat">
                        </div>
                    </div>
                    <div class="form-group form-md-line-input" :class="{'has-error': error.emptyResignInfo.isError}" >
                        <label class="control-label" for="form_control_1">Alasan Resign
                        </label>
                        <div v-show="!writeResign">
                            <select class="form-control" v-model="ba.alasanResign">
                                <option value="" selected="selected"> Pilih Alasan Resign</option>
                                <option value="1"> Cut/Diresignkan</option>
                                <option value="2"> Hamil</option>
                                <option value="3"> Keperluan Keluarga/Keperluan Pribadi</option>
                                <option value="4"> Sakit</option>
                                <option value="5"> Mendapat Pekerjaan Baru</option>
                            </select>
                        </div>
                        <span class="input-error-validation" v-show="error.emptyResignBa.isError">@{{ error.emptyResignBa.message }}</span>
                    </div>

                    <div class="form-group form-md-line-input" :class="{'has-error': error.emptyResignBa.isError}" >
                        <label class="control-label" for="form_control_1">Keterangan Resign
                        </label>
                            <input type="text" class="form-control" placeholder="Keterangan Resign" name="no_sdf"  v-model="ba.keteranganResign" >
                        <span class="input-error-validation" v-show="error.emptyResignInfo.isError">@{{ error.emptyResignInfo.message }}</span>
                    </div>

                    <div class="form-group form-md-line-input" :class="{'has-error': error.emptyAppliedResignDate.isError}">
                        <label class="control-label" for="form_control_1">Tanggal Mengajukan Resign
                        </label>
                        <div class="">
                            <div class="input-group  date date-picker"  data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control" name="first_date" readonly v-model="ba.pengajuanRequest">
                                <span class="input-group-btn">
                                                                        <button class="btn default" type="button">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </button>
                                                                    </span>
                            </div>
                            <div class="form-control-focus"> </div>
                            <span class="input-error-validation" v-show="error.emptyAppliedResignDate.isError">@{{ error.emptyAppliedResignDate.message }}</span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input" :class="{'has-error': error.emptyEffectiveResignDate.isError}">
                        <label class="control-label" for="form_control_1">Tanggal Efektif Resign
                        </label>
                        <div class="">
                            <div class="input-group  date date-picker"  data-date-format="dd-mm-yyyy">
                                <input type="text" class="form-control" name="first_date" readonly v-model="ba.efektifResign">
                                <span class="input-group-btn">
                                                                        <button class="btn default" type="button">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </button>
                                                                    </span>
                            </div>
                            <div class="form-control-focus"> </div>
                            <span class="input-error-validation" v-show="error.emptyEffectiveResignDate.isError">@{{ error.emptyEffectiveResignDate.message }}</span>
                        </div>
                    </div>
                    <div class="form-group form-md-line-input" v-show="ba.alasanResign == 1">
                        <label class="mt-checkbox mt-checkbox-outline" > Takeout Toko
                            <input type="checkbox" v-model="ba.takeoutStore" name="test">
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                <button type="submit" class="btn red-sunglo" @click="baResign">Submit Resign Form</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>