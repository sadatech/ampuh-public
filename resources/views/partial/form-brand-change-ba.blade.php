<!-- modal form rolling -->
<div class="modal fade" id="form-change-brand-modal" role="basic" aria-hidden="true" data-focus-on="input:first"
     data-backdrop="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Form Rolling BA</h4>
            </div>
            <div class="modal-body">
                <div id="intro" class="centerin " v-show="isIntro">
                    <label class="control-label">Pilih Brand Tujuan BA pindah</label>
                    <div class="note note-info clickable" v-for="store in baStore" @click="
                    setStore(store.id, store.store_name_1)">
                    <h4 class="block">@{{ store.store_name_1 }}</h4>
                </div>
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