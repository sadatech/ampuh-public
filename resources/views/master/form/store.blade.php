@extends('layouts.app')

@section('additional-css')
    <link href="/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css" rel="stylesheet"
          type="text/css"/>
@stop

@section('content')

    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Store</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="index.html">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Master Data</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/master/store">Toko</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">{{ (@$store) ?'Edit' : 'Tambah' }}</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <div class="row">
            <div class="col-md-32">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-basket"></i>
                            <span class="caption-subject sbold uppercase">{{ (@$store) ?'Edit' : 'Tambah' }} Toko</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form class="horizontal-form" role="form" action="{{ url('master/store') }}" method="post">
                            {{ csrf_field() }}
                            @if(@$store)
                                {{ method_field('put') }}
                                <input type="hidden" name="_id" value="{{$store->id}}">
                            @endif
                            <div class="form-body">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            <!--/row-->
                                @if(!@$store)
                                    <h3 class="form-section">SDF</h3>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <label>No. SDF</label>
                                                <input type="text" class="form-control" placeholder="" name="no_sdf"
                                                       value="{{ old('no_sdf') }}"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tangal Permintaan</label>
                                                <div class="input-group  date date-picker"
                                                     data-date-format="yyyy-mm-dd">
                                                    <input type="text" class="form-control" name="request_date" readonly
                                                           value="{{ date('Y-m-d') }}">
                                                    <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tanggal Masuk</label>
                                                <div class="input-group  date date-picker"
                                                     data-date-format="yyyy-mm-dd">
                                                    <input type="text" class="form-control" name="first_date" readonly
                                                           value="{{ old('first_date') }}">
                                                    <span class="input-group-btn">
                                                    <button class="btn default" type="button">
                                                        <i class="fa fa-calendar"></i>
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                            @endif
                            <!--/row-->
                                <h3 class="form-section">Toko</h3>
                               @include('partial.form-new-store')

                            <div class="form-actions right">
                                <button type="button" class="btn default">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </button>
                                <button type="submit" class="btn blue">
                                    <i class="fa fa-check"></i> Simpan
                                </button>
                            </div>
                        <!-- END FORM-->
                    </div>
                </div>
            </div>
            <!-- BEGIN PAGE BASE CONTENT -->
        </div>


        @endsection

        @section('additional-script')
            <script src="/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"
                    type="text/javascript"></script>
            <script src="/assets/pages/scripts/components-bootstrap-tagsinput.min.js" type="text/javascript"></script>
            <script type="text/javascript">
                $.fn.select2.defaults.set("theme", "bootstrap");
                $('#channel').select2({
                    placeholder: 'Pilih Channel',
                    width: '100%',
                });
                $('#account').select2({
                    width: '100%',
                    placeholder: 'Pilih Account',
                    ajax: {
                        url: "{{ url('master/account?json') }}",
                        dataType: 'json',
                        data: function (params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function (data, page) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });
                $('#province').select2({
                    width: '100%',
                    placeholder: 'Pilih Provinsi',
                    ajax: {
                        url: "{{ url('/provinceFilter') }}",
                        method: 'POST',
                        dataType: 'json',
                        data: function (params) {
                            return {
                                term: params.term
                            }
                        },
                        processResults: function (data) {

                            return {
                                results: $.map(data, function (obj) {
                                    return {id: obj.province_name, text: obj.province_name}
                                })
                            }
                        },
                        cache: true
                    }
                });
                var isError = {{ (is_null(old('province'))) ? 'false':'true'}};
                if (isError) {
                    $('#cities').prop('disabled', false);
                }
                $('#province').change(function () {
                    $('#cities').prop('disabled', false);
                });

                $('#cities').select2({
                    width: '100%',
                    placeholder: 'Pilih Kota',
                    ajax: {
                        url: "{{ url('/cityFilter') }}",
                        method: 'POST',
                        dataType: 'json',
                        data: function (params) {
                            return {
                                province_name: $('#province').val(),
                                term: params.term
                            }
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (obj) {
                                    return {id: obj.id, text: obj.city_name}
                                })
                            }
                        },
                        cache: true
                    }
                });
                $('#cities').change(function () {
                    $('#supervisor').prop('disabled', false);
                });
                $('#supervisor').select2({
                    width: '100%',
                    placeholder: 'Pilih Supervisor',
                    ajax: {
                        url: "{{ url('/reoFilter') }}",
                        method: 'POST',
                        dataType: 'json',
                        data: function (params) {
                            return {
                                city: $('#cities').val(),
                                term: params.term
                            }
                        },
                        processResults: function (data) {

                            return {
                                results: $.map(data, function (obj) {
                                    return {id: obj.id, text: obj.user.name}
                                })
                            }
                        },
                        cache: true
                    }
                });

            </script>
@endsection