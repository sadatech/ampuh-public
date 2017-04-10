@extends('layouts.app')

@section('content')
    <div class="page-content" xmlns="http://www.w3.org/1999/html">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Edit Ba</h1>
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
                            <span class="caption-subject font-dark sbold uppercase">Edit Ba</span>
                        </div>
                        <div class="actions">
                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                <button class="btn green-dark" id="editBa"> Edit Ba</button>
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
                                               data-original-title="Enter NIK"> {{ $ba->nik }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Name</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="name" data-type="text"
                                               data-original-title="Enter Name"> {{ $ba->name }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> No HP</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="no_hp" data-type="text"
                                               data-original-title="Enter Nomor HP"> {{ $ba->no_hp }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> No KTP</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="no_ktp" data-type="text"
                                               data-original-title="Enter Nomor KTP"> {{ $ba->no_ktp }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Bank</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="bank_name" data-type="select" data-pk="1"
                                               data-value="" data-original-title="Bank"> {{ $ba->bank_name}} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> No Rekening</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="rekening" data-type="text" data-pk="1"
                                                data-original-title="Enter Nomor Rekening"> {{ $ba->rekening }} </a><br>
                                            <span id="msg"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Status</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="status" data-type="select"
                                               data-original-title="Status"> {{ title_case($ba->status) }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Join Date</td>
                                        <td style="width:50%">
                                            <a href="#" id="join_date" data-type="date" data-pk="1" data-url="/post"
                                               data-title="Join Date">{{ $ba->join_date->format('d/m/Y') }}</a>
                                        </td>
                                    </tr>
                                    @if($ba->join_date_mds != null && $ba->join_date_mds != '0000-00-00')
                                        <tr>
                                            <td style="width:15" width="1" class="col-md-1"> Jin Date MDS</td>
                                            <td style="width:50%">
                                                <a href="#" id="join_date_mds" data-type="date" data-pk="1"
                                                   data-url="/post"
                                                   data-title="Join Date MDS">{{ $ba->join_date_mds->format('d/m/Y') }}</a>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Agency</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="agency_id" data-type="select" data-pk="1"
                                               data-original-title="Agency"> {{ $ba->agency->name }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Class</td>
                                        <td style="width:50%">

                                            @if(\Carbon\Carbon::now()->month != 7)
                                                {{$ba->class}}
                                            @else
                                                <a href="javascript:;" id="class" data-type="select" data-pk="1"
                                                   data-original-title="Class"> {{ $ba->class }} </a>
                                            @endif

                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Brand</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="brand_id" data-type="select" data-pk="1"
                                               data-original-title="Brand"> {{ title_case($ba->brand->name) }} </a>
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
                                               data-original-title="Uniform Size"> {{ $ba->uniform_size }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Gender</td>
                                        <td>
                                            <a href="javascript:;" id="gender" data-type="select" data-pk="1"
                                               data-value=""
                                               data-original-title="Gender"> {{ title_case($ba->gender) }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Education</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="edukasi" data-type="select"
                                               data-original-title="Edukation"> {{ $ba->education }} </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Birth Date</td>
                                        <td style="width:50%">
                                            <a href="#" id="birth_date" data-type="date" data-pk="1" data-url="/post"
                                               data-title="Birth Date">{{ $ba->birth_date->format('d/m/Y') }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Pas Photo</td>
                                        <td>
                                            <a class="fancybox-effects-a"
                                               href="/attachment/pasfoto/{{ $ba->pas_foto }}">
                                                <img style="width: 30%"
                                                     src="/attachment/pasfoto/{{ $ba->pas_foto }}"
                                                     alt="" class="img-thumbnail"/>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td valign="middle">Photo KTP</td>
                                        <td>
                                            <a class="fancybox-effects-a"
                                               href="/attachment/ktp/{{ $ba->foto_ktp }}">
                                                <img style="width: 30%"
                                                     src="/attachment/ktp/{{ $ba->foto_ktp }}"
                                                     alt="" class="img-thumbnail"/>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="middle">Photo Tabungan</td>
                                        <td>
                                            <a class="fancybox-effects-a"
                                               href="/attachment/tabungan/{{ $ba->foto_tabungan }}">
                                                <img style="width: 30%"
                                                     src="/attachment/tabungan/{{ $ba->foto_tabungan }}"
                                                     alt="" class="img-thumbnail"/>
                                            </a>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td style="width:15" width="1" class="col-md-1"> Additional Information</td>
                                        <td style="width:50%">
                                            <a href="javascript:;" id="extra_keterangan" data-type="textarea"
                                               data-original-title="Additional Information"> {{ $ba->extra_keterangan }} </a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
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
    @include('partial.form-editable-ba', ['channel' => @$ba->store->first()->channel])
@endsection