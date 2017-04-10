@extends('layouts.app')
@section('additional-css')
    <link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.css?v=2.1.5" media="screen"/>
@stop
@section('content')
    <div class="page-content" xmlns="http://www.w3.org/1999/html">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Approve BA</h1>
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
                <a href="{{ url('master/ba') }}">Ba</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Approval</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject font-dark sbold uppercase">Approve BA
                                @if ($isResign) Resign
                                @elseif (Request::segment(5) == 'rejoin') Rejoin
                                @endif
                            </span>
                        </div>
                        <div class="actions">
                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                <button class="btn green" id="approve" > Approve
                                    @if ($isResign) Resign
                                    @elseif (Request::segment(5) == 'rejoin') Rejoin</button>
                                @endif<button class="btn" id="reject" > Reject </button>
                            </div>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table id="user" class="table table-bordered table-striped">
                                    <tbody>
                                    <tr>
                                        <td width="1" class="col-md-1"> NIK</td>
                                        <td style="width: 50%">
                                            <a href="javascript:;" id="nik" data-type="text"
                                               data-original-title="Masukan NIK"> {{ $bas->nik }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Name</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="name" data-type="text"
                                               data-original-title="Masukan NIK"> {{ $bas->name }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> No HP</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="no_hp" data-type="text"
                                               data-original-title="Masukan Nomor HP"> {{ $bas->no_hp }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> No KTP</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="no_ktp" data-type="text"
                                               data-original-title="Masukan Nomor KTP"> {{ $bas->no_ktp }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Bank</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="bank_name" data-type="select" data-pk="1"
                                               data-value="" data-original-title="Bank"> {{ $bas->bank_name}} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> No Rekening</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="rekening" data-type="number"
                                               data-original-title="Masukan Nomor Rekening"> {{ $bas->rekening }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Status</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="status" data-type="select"
                                               data-original-title="Status"> {{ $bas->status }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Join Date</td>
                                        <td style="width:50%">
                                            <a href="#" id="join_date" data-type="date" data-pk="1" data-url="/post"
                                               data-title="Tanggal Masuk">{{ $bas->join_date->format('d/m/Y') }}</a>
                                        </td>
                                    </tr>
                                    @if($bas->join_date_mds != null && $bas->join_date_mds != '0000-00-00')
                                        <tr>
                                            <td style="width:15" width="1" class="col-md-1"> Tanggal Masuk MDS</td>
                                            <td style="width:50%">
                                                <a href="#" id="join_date_mds" data-type="date" data-pk="1" data-url="/post"
                                                   data-title="Tanggal Masuk MDS">{{ $bas->join_date_mds->format('d/m/Y') }}</a>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Agency</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="agency_id" data-type="select" data-pk="1"
                                               data-original-title="Agensi"> {{ $bas->agency->name }} </a>
                                        </td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table id="user" class="table table-bordered table-striped">
                                    <tbody>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Uniform Size</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="uniform_size" data-type="select" data-pk="1"
                                               data-original-title="Ukuran Seragam"> {{ $bas->uniform_size }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Gender</td>
                                        <td>
                                            <a href="javascript:;" id="gender" data-type="select" data-pk="1"
                                               data-value=""
                                               data-original-title="Pilih Jenis Kelamin"> {{ $bas->gender }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Education</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="edukasi" data-type="select"
                                               data-original-title="Edukasi"> {{ $bas->education }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Birth Date</td>
                                        <td style="width:50%">
                                            <a href="#" id="birth_date" data-type="date" data-pk="1" data-url="/post"
                                               data-title="Tanggal Lahir">{{ $bas->birth_date->format('d/m/Y') }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Pas Photo</td>
                                        <td>
                                            <a class="fancybox-effects-a"
                                               href="/attachment/pasfoto/{{ $bas->pas_foto }}">
                                                <img style="width: 30%"
                                                     src="/attachment/pasfoto/{{ $bas->pas_foto }}"
                                                     alt="" class="img-thumbnail"/>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td valign="middle">Photo KTP</td>
                                        <td>
                                            <a class="fancybox-effects-a"
                                               href="/attachment/ktp/{{ $bas->foto_ktp }}">
                                                <img style="width: 30%"
                                                     src="/attachment/ktp/{{ $bas->foto_ktp }}"
                                                     alt="" class="img-thumbnail"/>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Photo Tabungan</td>
                                        <td>
                                            <a class="fancybox-effects-a"
                                               href="/attachment/tabungan/{{ $bas->foto_tabungan }}">
                                                <img style="width: 30%"
                                                     src="/attachment/tabungan/{{ $bas->foto_tabungan }}"
                                                     alt="" class="img-thumbnail"/>
                                            </a>
                                        </td>
                                    </tr>
                                    @if( $bas->extra_keterangan != null)
                                        <tr>
                                            <td style="width:15" width="1" class="col-md-1"> Keterangan Tambahan</td>
                                            <td style="width:50%">
                                                <a href="javascript:;" id="extra_keterangan" data-type="textarea"
                                                   data-original-title="Keterangan Tambahan"> {{ $bas->extra_keterangan }} </a>
                                            </td>
                                        </tr>
                                    @endif


                                    </tbody>
                                </table>
                            </div>
                            @if(!empty($wip))
                                <div class="col-md-12">
                                    <table id="user" class="table table-bordered table-striped">
                                        <tbody>
                                        <tr>
                                            <td style="width:15" width="1" class="col-md-1"> Alasan Resign</td>
                                            <td style="width:50%">
                                                {{$bas->exitForm->alasan}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:15" width="1" class="col-md-1"> Keterangan Resign</td>
                                            <td style="width:50%">
                                                {{$bas->resign_info}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:15" width="1" class="col-md-1"> Tanggal Mengajukan</td>
                                            <td style="width:50%">
                                                {{$bas->exitForm->filling_date->format('d/m/Y')}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:15" width="1" class="col-md-1"> Tanggal Efektif</td>
                                            <td style="width:50%">
                                                {{$bas->exitForm->effective_date->format('d/m/Y')}}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            @if(Request::segment(5) == 'rejoin' && $bas->resign_reason == 'Hamil')
                                <hr style="border-bottom: #000000; padding: 1px">
                                <div class="col-md-12">
                                    <table id="user" class="table table-bordered table-striped">
                                        <tbody>
                                        <tr>
                                            <td style="width:15" width="1" class="col-md-1"> Tanggal Lahir Anak</td>
                                            <td style="width:50%">
                                                {{$bas->anak_lahir_date->format('d-m-Y')}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td valign="middle">Foto Akte Kelahiran</td>
                                            <td>
                                                <img src="/attachment/akte/{{ $bas->foto_akte }}" alt=""
                                                     style="width: 25%" class="img-thumbnail">
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <!-- END PAGE BASE CONTENT -->
            </div>
        </div>
    </div>

@endsection

@section('additional-script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.js"></script>
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/jquery.mockjax.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-editable/bootstrap-editable/js/bootstrap-editable.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-editable/inputs-ext/address/address.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-editable/inputs-ext/wysihtml5/wysihtml5.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-typeahead/bootstrap3-typeahead.min.js"
            type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script type="text/javascript" src="/js/jquery.fancybox.js?v=2.1.5"></script>
    <script src="/js/ba-approval.js" type="text/javascript"></script>
    <script>
        jQuery(document).ready(function () {
            $(".fancybox-effects-a").fancybox({
                helpers: {
                    title: {
                        type: 'outside'
                    },
                    overlay: {
                        speedOut: 0
                    }
                }
            });

            @if(Auth::user()->role !== 'arina' OR $isResign == true OR Request::segment(5) == 'rejoin')
            //ga bisa edit
            $('#user .editable').editable('toggleDisabled');
            @endif
        });
    </script>
@endsection