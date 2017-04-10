@extends('layouts.app')

@section('content')
    <div class="page-content" xmlns="http://www.w3.org/1999/html">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>ADD BA</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="/">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Master Data</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ url('master/ba') }}">BA</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Add</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered" id="form_wizard_1">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class=" icon-layers font-red"></i>
                            <span class="caption-subject font-red bold uppercase"> Add BA -
							<span class="step-title"> Step 1 of 2 </span>
						</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <form class="form-horizontal" action="/master/ba/add" id="submit_form"
                              enctype="multipart/form-data" method="POST">
                            {{ csrf_field() }}
                            <div class="form-wizard">
                                <div class="form-body">
                                    <ul class="nav nav-pills nav-justified steps">
                                        <li>
                                            <a href="#tab1" data-toggle="tab" class="step">
                                                <span class="number"> 1 </span>
                                                <span class="desc">
												<i class="fa fa-check"></i> BA Setup </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#tab2" data-toggle="tab" class="step">
                                                <span class="number"> 2 </span>
                                                <span class="desc">
													<i class="fa fa-check"></i> Store Setup </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div id="bar" class="progress progress-striped" role="progressbar">
                                        <div class="progress-bar progress-bar-success"></div>
                                    </div>
                                    <div class="tab-content">
                                        <div class="alert alert-danger display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            You have some form errors. Please check below.
                                        </div>
                                        <div class="alert alert-success display-none">
                                            <button class="close" data-dismiss="alert"></button>
                                            BA Berhasil diproses, menunggu persetujuan Arina!
                                        </div>
                                        <div class="tab-pane active" id="tab1">
                                            <h3 class="block">Provide BA details</h3>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">NIK
                                                    {{--<span class="required"> * </span>--}}
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="nik"
                                                           value="{{ old('nik')  }}"/>
                                                    {{--<span class="help-block"> Insert NIK of BA</span>--}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Name
                                                    {{--<span class="required"> * </span>--}}
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" name="name"/>
                                                    @if(isset($tipe))
                                                        <input type="hidden" class="form-control" name="wip"
                                                               value="{{ $wip_id }}"/>
                                                        <input type="hidden" class="form-control" name="tipe"
                                                               value="replacement"/>
                                                    @endif

                                                    @if(isset($rotasiReplacement))
                                                        <input type="hidden" name="rotasiReplacement"
                                                               value="{{ $rotasiReplacement }}">
                                                        <input type="hidden" name="wip" value="{{ $wip_id }}">
                                                    @endif
                                                    {{--<span class="help-block"> Insert name of BA</span>--}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Birth Date</label>
                                                <div class="col-md-3">
                                                    <div class="input-group date date-picker"
                                                         data-date-format="dd-mm-yyyy">
                                                        <input type="text" class="form-control" readonly
                                                               name="birthdate">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">No KTP
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="no_ktp"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">No HP
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="no_hp"/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Gender
                                                </label>
                                                <div class="col-md-9">
                                                    <div class="md-radio-inline">
                                                        <div class="md-radio">
                                                            <input type="radio" id="female" name="gender" checked
                                                                   value="female" class="md-radiobtn">
                                                            <label for="female">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Female</label>
                                                        </div>
                                                        <div class="md-radio">
                                                            <input type="radio" id="male" name="gender" value="male"
                                                                   class="md-radiobtn">
                                                            <label for="male">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Male</label>
                                                        </div>
                                                    </div>

                                                    <div id="form_gender_error"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Education</label>
                                                <div class="col-md-4">
                                                    <select name="education" id="list_edukasi" class="form-control">
                                                        <option value="">Choose education</option>
                                                        <option value="SLTA">SLTA</option>
                                                        <option value="DIPLOMA">DIPLOMA</option>
                                                        <option value="S1">S1</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Uniform Size</label>
                                                <div class="col-md-3">
                                                    <select name="uniform_size" id="list_baju" class="form-control">
                                                        <option value="">Choose Size</option>
                                                        <option value="S/7">S/7</option>
                                                        <option value="M/9">M/9</option>
                                                        <option value="L/11">L/11</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Join date</label>
                                                <div class="col-md-3">
                                                    <div class="input-group date date-picker"
                                                         data-date-format="dd-mm-yyyy">
                                                        <input type="text" class="form-control" readonly
                                                               name="join_date" value="{{ $joinDate }}">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <!-- /input-group -->
                                                    {{--<span class="help-block"> select a date </span>--}}
                                                </div>
                                            </div>
                                            @if(in_array(12, $storeAccount->toArray()))
                                                <div class="form-group">
                                                <label class="control-label col-md-3">Join Date MDS</label>
                                                <div class="col-md-3">
                                                    <div class="input-group date date-picker"
                                                         data-date-format="dd-mm-yyyy">
                                                        <input type="text" class="form-control" readonly
                                                               name="join_date_mds">
                                                        <span class="input-group-btn">
                                                            <button class="btn default" type="button">
                                                                <i class="fa fa-calendar"></i>
                                                            </button>
                                                        </span>
                                                    </div>
                                                    <!-- /input-group -->
                                                    {{--<span class="help-block"> select a date </span>--}}
                                                </div>
                                            </div>
                                            @endif
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Bank</label>
                                                <div class="col-md-9">
                                                    <div class="md-radio-inline">
                                                        <div class="md-radio">
                                                            <input type="radio" id="mandiri" name="bank_name"
                                                                   value="Mandiri" class="md-radiobtn">
                                                            <label for="mandiri">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> Mandiri</label>
                                                        </div>
                                                        <div class="md-radio">
                                                            <input type="radio" id="bni" name="bank_name" value="BNI"
                                                                   class="md-radiobtn">
                                                            <label for="bni">
                                                                <span></span>
                                                                <span class="check"></span>
                                                                <span class="box"></span> BNI</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Rekening
                                                    {{--<span class="required"> * </span>--}}
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="rekening"
                                                           id="rekening"/>
                                                    <span style="display: none"
                                                          class="help-block alert-danger alert-dismissible alert_mandiri">
                                                        <strong>Warning!</strong> Mandiri must be 13 digit
                                                    </span>
                                                    <span style="display: none"
                                                          class="help-block alert-danger alert-dismissible alert_bni">
                                                        <strong>Warning!</strong> BNI must be 10 digit
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Photo
                                                    {{--<span class="required"> * </span>--}}
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="file" class="form-control" name="pas_foto"
                                                           id="pas_foto"/>
                                                    <img id="preview" width="150px" style="display: none" height="150px"
                                                         class="img-responsive"
                                                         src="/assets/layouts/layout4/img/avatar9.jpg" alt="tes"/>
                                                    <div class="alert alert-danger alert-dismissible alert_pasfoto"
                                                         role="alert"
                                                         style="display: none">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        <strong>Warning!</strong> Only accept image (jpg,jpeg,png)
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">KTP Photo
                                                    {{--<span class="required"> * </span>--}}
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="file" class="form-control" name="foto_ktp"
                                                           id="foto_ktp"/>
                                                    <img id="previewktp" width="150px" style="display: none"
                                                         height="150px"
                                                         class="img-responsive"
                                                         src="/assets/layouts/layout4/img/avatar9.jpg" alt="tes"/>
                                                    <div class="alert alert-danger alert-dismissible alert_fotoktp"
                                                         role="alert"
                                                         style="display: none">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        <strong>Warning!</strong> Only accept image (jpg,jpeg,png)
                                                    </div>

                                                    {{--<span class="help-block"> Upload foto KTP</span>--}}
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Bank Account Photo
                                                    {{--<span class="required"> * </span>--}}
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="file" class="form-control" name="foto_tabungan"
                                                           id="foto_tabungan"/>
                                                    {{--<span class="help-block"> Upload foto Rekening</span>--}}
                                                    <img id="previewtabungan" width="150px" style="display: none"
                                                         height="150px"
                                                         class="img-responsive"
                                                         src="/assets/layouts/layout4/img/avatar9.jpg" alt="tes"/>
                                                    <div class="alert alert-danger alert-dismissible alert_tabungan"
                                                         role="alert"
                                                         style="display: none">
                                                        <button type="button" class="close" data-dismiss="alert"
                                                                aria-label="Close"><span
                                                                    aria-hidden="true">&times;</span></button>
                                                        <strong>Warning!</strong> Only accept image (jpg,jpeg,png)
                                                    </div>


                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Additional Description
                                                </label>
                                                <div class="col-md-5">
                                                    <textarea class="form-control" name="extra_keterangan"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab2">
                                            <h3 class="block">Provide Store details</h3>
                                            @if(isset($newRotasi) && !isset($rotasiReplacement))
                                                <div class="form-group">
                                                    <label class="control-label col-md-3"
                                                           for="list_agency">Store</label>
                                                    <div class="col-md-9">
                                                        <select name="store_id[]" id="list_agency" class="form-control">
                                                            <option value="">Choose rotation store</option>
                                                            @foreach($storeIds as $store)
                                                                <option value="{{$store->id}}">{{$store->store_name_1}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif(isset($storeIds))
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Store(s)</label>
                                                    <div class="col-md-9">
                                                        @foreach($storeIds as $storeId)
                                                            <input type="hidden" name="store_id[]"
                                                                   value="{{ $storeId }}">
                                                        @endforeach
                                                        <input type="text" class="form-control" name="store"
                                                               value="{{ $store }}" readonly>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Brand</label>
                                                <div class="col-md-9">
                                                    <input type="hidden" id="brand" name="brand_id"
                                                           value="{{ $brand->id }}">
                                                    <input type="text" class="form-control" name="brand"
                                                           value="{{ $brand->name }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Status</label>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control" name="status"
                                                           id="{{ $status }}" value="{{ $status }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-md-3">Agency</label>
                                                <div class="col-md-9">
                                                    <select name="agency_id" id="list_agency" class="form-control">
                                                        <option value="">Choose Agency</option>
                                                        @foreach($agencies as $agency)
                                                            <option value="{{$agency->id}}">{{$agency->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @if(isset($branchAro) && count($branchAro) > 1)
                                                <div class="form-group">
                                                    <label class="control-label col-md-3">Branch</label>
                                                    <div class="col-md-9">
                                                        <select name="arina_branch" id="list_agency" class="form-control">
                                                            <option value="">Choose Branch</option>
                                                            @foreach($branchAro as $branch)
                                                                <option value="{{$branch->branch_id}}">{{$branch->arina_branch->cabang}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            @elseif(isset($branchAro) && count($branchAro) == 1)
                                                <input type="hidden" id="brand" name="arina_branch"
                                                       value="{{ $branchAro->first()->branch_id }}">
                                            @endif


                                        </div>

                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <a href="javascript:;" class="btn default button-previous">
                                                <i class="fa fa-angle-left"></i> Back </a>
                                            <a href="javascript:;" class="btn btn-outline green button-next"> Continue
                                                <i class="fa fa-angle-right"></i>
                                            </a>
                                            <button type="submit" class="btn green button-submit"> Submit
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('additional-script')
    <script src="\js/validation_ba.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery-repeater/jquery.repeater.js" type="text/javascript"></script>
    {{--<script src="/assets/pages/scripts/form-repeater.min.js" type="text/javascript"></script>--}}
    <script !src="">
        function readUrl(input, ID) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#' + ID).attr('src', e.target.result);
                    $('#' + ID).show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        var self = this;
        $(document).ready(function () {

//            validasi bank
            var bank = "";
            $("input[name='bank_name']").change(function () {
                bank = $("input[name='bank_name']:checked").val();
                var jumlah = $('input#rekening').val().length;
                if (bank == 'Mandiri' && jumlah != 13) {
                    $(".alert_mandiri").show();
                    $(".alert_bni").hide();
                } else if (bank == 'BNI' && jumlah != 10) {
                    $(".alert_bni").show();
                    $(".alert_mandiri").hide();
                } else {
                    $(".alert_mandiri").hide();
                    $(".alert_bni").hide();
                }
            });
            $('input#rekening').keyup(function () {
                bank = $("input[name='bank_name']:checked").val();
                var jumlah = $('input#rekening').val().length;
                if (bank == 'Mandiri' && jumlah != 13) {
                    $(".alert_mandiri").show();
                } else if (bank == 'BNI' && jumlah != 10) {
                    $(".alert_bni").show();
                } else {
                    $(".alert_mandiri").hide();
                    $(".alert_bni").hide();
                }
            });

            $('#pas_foto').change(function () {
                var filename = $('#pas_foto').val();
                if (filename.substring(3, 11) == 'fakepath') {
                    filename = filename.substring(12);
                    var extension = filename.substr((filename.lastIndexOf('.') + 1));
                }
                var exts = ['jpeg', 'bmp', 'png', 'jpg'];
                if (exts.indexOf(extension) === -1) {
                    $(".alert_pasfoto").show();
                } else {
                    self.readUrl(this, 'preview');
                    $(".alert_pasfoto").hide();
                }
            });

            $('#foto_ktp').change(function () {
                var filename = $('#foto_ktp').val();
                if (filename.substring(3, 11) == 'fakepath') {
                    filename = filename.substring(12);
                    var extension = filename.substr((filename.lastIndexOf('.') + 1));
                }
                var exts = ['jpeg', 'bmp', 'png', 'jpg'];
                if (exts.indexOf(extension) === -1) {
                    $(".alert_fotoktp").show();
                } else {
                    self.readUrl(this, 'previewktp');
                    $(".alert_fotoktp").hide();
                }
            });

            $('#foto_tabungan').change(function () {
                var filename = $('#foto_tabungan').val();
                if (filename.substring(3, 11) == 'fakepath') {
                    filename = filename.substring(12);
                    var extension = filename.substr((filename.lastIndexOf('.') + 1));
                }
                var exts = ['jpeg', 'bmp', 'png', 'jpg'];
                if (exts.indexOf(extension) === -1) {
                    $(".alert_tabungan").show();
                } else {
                    self.readUrl(this, 'previewtabungan');
                    $(".alert_tabungan").hide();
                }
            });

            'use strict';
            $('input:radio[name="status"]').change(function () {
                if ($(this).val() == 'mobile') {
                    $('#tambahToko').show();

                } else {
                    $(".hapusToko").click();
                    $('#tambahToko').hide();
                }
            });
            window.outerRepeater = $('.mt-repeater').repeater({
                isFirstItemUndeletable: true,
                show: function () {
                    $("select[name^='group-a']").prop('disabled', false);
                    $(".toko").select2({
                        ajax: {
                            url: "/storeFilter",
                            method: "POST",
                            dataType: 'json',
                            data: function (params) {
                                return {
                                    storeName: params.term, // search term
                                    page: params.page,
                                    firstStore: $('#toko').val(),
                                    oneBrand: $('#brand').val()
                                };
                            },
                            processResults: function (data, params) {
                                // parse the results into the format expected by Select2
                                // since we are using custom formatting functions we do not need to
                                // alter the remote JSON data, except to indicate that infinite
                                // scrolling can be used
                                params.page = params.page || 1;
                                return {
                                    results: $.map(data, function (obj) {
                                        var toko = $('.toko').length;
                                        for (var i = 0; i < toko; i++) {
                                            if ($("select[name^='group-a[" + i + "][store]']").val() == obj.id) {
                                                return {id: obj.id, text: obj.store_name_1, disabled: true}
                                            }
                                        }
                                        return {id: obj.id, text: obj.store_name_1}
                                    }),
                                    pagination: {
                                        more: (params.page * 30) < data.total_count
                                    }
                                };
                            },
                            cache: true
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }, // let our custom formatter work
                        minimumInputLength: 1

                    });
                    $('.select2-container').removeAttr('style');
                    $('#toko').prop('disabled', true);

                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        });
    </script>

@endsection