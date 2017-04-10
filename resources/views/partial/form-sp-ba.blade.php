<!-- modal form rolling -->
<div class="modal fade" id="form-sp-modal" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Konfirmasi Sp Ba</h4>
            </div>
            <div class="modal-body">
                <div id="intro" class="centerin " v-show="isIntro">
                    <label class="control-label">Pilih Toko Ba Dimana akan diberikan SP</label>
                    <div class="note note-info clickable mt-element-ribbon " v-for="store in baStore" @click="
                    setSpStore(store.id, store.store_name_1)">
                    <div class="ribbon ribbon-right ribbon-round ribbon-color-info ribbon-shadow uppercase" v-if="store.sp !== null">@{{ store.sp.status }}</div>
                    <h4 class="block">@{{ store.store_name_1 }}</h4>
                </div>
            </div>
        </div>
        <div id="form" v-show="confirmationSp">
            <div class="portlet light ">
                    <div class="note note-danger ">
                        <h4 class="block centerin special-font" v-show="canUpdateSp && !isSp3">Apa Anda Yakin Untuk memberikan @{{ statusSp }} Untuk Ba @{{ ba.nama }} pada Toko @{{ ba.tokoName }}</h4>
                        <div class="centerin" v-show="canUpdateSp && !isSp3">
                            <button type="button" class="btn dark btn-outline centerin" data-dismiss="modal">Tidak</button>
                            <button type="submit" class="btn red-sunglo" @click="giveSp" >Berikan SP</button>
                        </div>
                        <h4 class="block centerin special-font" v-show="!canUpdateSp">Status @{{ statusSp }} Ba @{{ ba.nama }} Masih Dalam Proses approval oleh tim Loreal </h4>
                        <div class="centerin" v-show="!canUpdateSp">
                            <button type="submit" class="btn green-meadow" data-dismiss="modal"  >Kembali</button>
                        </div>
                        <h4 class="block centerin special-font" v-show="isSp3">Status @{{ statusSp }} Ba @{{ ba.nama }} Adalah Status SP tingkat terakhir </h4>
                        <div class="centerin" v-show="isSp3">
                            <button type="submit" class="btn green-meadow" data-dismiss="modal"  >Kembali</button>
                        </div>
                    </div>
            </div>
        </div>
        <div id="notFound" v-show="hasError">
            <h2>Ga ada Toko </h2>
        </div>
    </div>
</div>
<!-- End Modal form Rolling -->