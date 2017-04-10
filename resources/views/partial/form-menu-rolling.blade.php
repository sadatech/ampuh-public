<div class="modal fade" id="modal-basic-menu" role="basic" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title centerin">Silahkan Pilih Aksi yang ingin dilakukan</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{--<div class="col-md-6">--}}
                        {{--<button class="btn blue  btn-block green-haze teal-button pisahin" @click=" itemClick('ba-cuti');" data-dismiss="modal" data-backdrop=""  type="button"  >--}}
                            {{--<img src="/assets/hamil.svg" alt="" width="50px" height="50px">--}}
                            {{--<span class="clearfix special-font">Izin Hamil </span>--}}
                        {{--</button>--}}
                    {{--</div>--}}
                    <div class="col-md-6">
                        <a class="btn red  bg-red-intense bg-font-red-intense btn-block" type="button" @click="itemClick('ba-rolling')" data-dismiss="modal" data-backdrop="">
                            <img src="/assets/users.svg" alt="" width="50px" height="50px">
                            <span class="clearfix special-font">Rolling BA </span>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a class="btn yellow-crusta  btn-block bg-yellow-crusta bg-font-yellow-crusta pisahin"
                            type="button" data-dismiss="modal" data-backdrop=""
                            @click="itemClick('change-brand')"
                        >
                        <img src="/assets/buffer.svg" alt="" width="50px" height="50px">
                        <span class="clearfix special-font">Ubah Brand </span>
                        </a>
                    </div>
                </div>
                <div class="row">

                </div>
            </div>
        </div>
    </div>
</div>