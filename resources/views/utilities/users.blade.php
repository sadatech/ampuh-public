@extends('layouts.app')

@section('additional-css')
<link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .errorformclass {
            color:red;
            border: solid 1px red;
        }
        .numberOfBa{
            width: 64px;
            max-width: 64px;
        }
        #toast {
        display: none;
        min-width: 250px;
        margin-left: -125px;
        background-color: #27ae60;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        left: 50%;
        bottom: 30px;
        }
        #toast.show {
            visibility: visible;
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }
        @-webkit-keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }

        @keyframes fadein {
            from {bottom: 0; opacity: 0;}
            to {bottom: 30px; opacity: 1;}
        }

        @-webkit-keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
        @keyframes fadeout {
            from {bottom: 30px; opacity: 1;}
            to {bottom: 0; opacity: 0;}
        }
    </style>
    <!-- END PAGE LEVEL PLUGINS -->
@stop

@section('content')
<div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Users</h1>
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
                <a href="#">Utilities</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Users</span>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-blue"></i>
                            <span class="caption-subject font-blue sbold uppercase">Users</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                    	<div class="table-toolbar">
							<div class="row">
								<div class="col-md-6">
									<div class="btn-group">
										<a class="btn green" data-toggle="modal" href="users/create"> Add New <i class="fa fa-plus"></i></a>

									</div>
								</div>
							</div>
						</div>
                        <div class="modal fade" id="edit-toko" role="basic" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <a class="btn green" data-toggle="modal" id="add_store"> Add Store <i class="fa fa-plus"></i></a>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                        <h4 class="modal-title" id="#userid"></h4>
                                    </div>

                                    <div class="modal-body">
                                        <div class="alert alert-success" style="display:none;" id="alert">
                                          <strong>Data success updated!</strong>
                                        </div>
                                    </div>
                                    <div id="showform" class="modal-body">
                                    <form class="horizontal-form" action="#" id="addnewstore" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id_user" id="iduser">
                                        <div id="reo_store">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Provinsi</label>
                                                                <select class="form-control" name="province" id="province">

                                                                </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Kota</label>
                                                                <select class="form-control" multiple="multiple" name="cities" id="cities">

                                                                </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Store</label>
                                                                <select class="form-control" name="store" id="store" multiple="multiple">

                                                                </select>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn dark btn-outline" id="cancel">Cancel</button>
                                            <button type="submit" class="btn green">Save</button>
                                        </div>
                                    </form>
                                    </div>
                                    <div class="modal-body">
                                        <table id="listtoko" class="display" cellspacing="0" width="100%">
                                            <thead>
                                            <tr>
                                                <th> No </th>
                                                <th> Store Name  </th>
                                                <th> </th>
                                            </tr>
                                        </thead>
                                        <tbody id="rowtoko">
                                        </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn green">Save</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if (Session::has('deleteusers'))
                            <div class="alert alert-info">{{ Session::get('deleteusers') }}</div>
                        @endif
                        <table class="table table-striped table-hover table-bordered" id="usersTable">
                            <thead>
                            <tr>
                                <th width="1"> # </th>
                                <th> Username </th>
                                <th> Email </th>
                                <th> Role </th>
                                <th> Detail </th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('additional-script')
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>

    <script type="text/javascript">
    $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let formData = new FormData();
            $("#addnewstore").validate({
                errorClass: "errorformclass",
                rules: {
                    province: "required",
                    cities: "required",
                    store: "required",
                },
                messages: {
                    province: "Please enter Province",
                    cities: "Please enter Cities",
                    store: "Please enter Store",

                },
                submitHandler: (form) => {
                    var base_url = window.location.origin;
                    var fields = $(":input").serializeArray();
                    // console.log(fields[2].value);
                    formData.append('store_id',$("#store").val());
                    formData.append('id_user',$("#iduser").val());
                    $.ajax({
                        "url": "{{ url('utilities/users/addstore') }}",
                        "dataType": 'json',
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data" : formData,
                        "type": "POST",
                        "headers" : {
                            'X-CSRF-TOKEN': fields[1].value
                        },
                        success : function(data) {
                            $.each(data, function(i) {
                                if (data[i].code == 0) {
                                    $("#toast").addClass("show");
                                    $("#toast").css("background-color","#c0392b");
                                    $("#toast").text(data[i].content);
                                    setTimeout(function() {
                                        $("#toast").fadeOut(300, function() {
                                            $("#toast").remove();
                                            location.reload(true);
                                        });
                                    },2000);
                                }
                                else{
                                    $("#alert").show();
                                    $("#alert").text("Data Success Updated!");
                                    $("#toast").addClass("show");
                                    setTimeout(function() {
                                        window.location.href = window.location.origin+'/utilities/users';
                                    }, 1000);

                                }
                            });
                        }
                    });

                }
            })
            var listtoko = $("#listtoko").dataTable();
            var table = $('#usersTable').dataTable({
                "fnCreatedRow": function( nRow, data ) {
                    $(nRow).attr('class', data.id);
                },
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '{{ url('utilities/users/datatable') }}',
                    method: 'POST'
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'detail', name: 'detail' , orderable: false, searchable: false}
                ]
            });

            $('.date-picker').datepicker({
                autoclose: true
            });
            $(".store-ajax").select2({
                ajax: {
                    url: "{{ url('store/data') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data, page) {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.store_name_1}
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            });
            $(".opentoko").on('click', function() {
                var opentoko = $(this).data('id');
                $(".modal-body #userid").html( opentoko );
            });
            $('#edit-toko').on('show.bs.modal', function(e) {

            var id_user = e.relatedTarget.id;
            $('#iduser').val(id_user);
            $.ajax({
                url: "{{ url('utilities/users/showtoko') }}",
                type: 'POST',
                dataType: 'json',
                data: {id_user: id_user},
            })
            .done(function(data) {
                var datas = "";
                var no = 1;
                for (var i = data.length - 1; i >= 0; i--) {
                    datas += "<tr><td>"+no+++"</td>";
                    datas += "<td>"+data[i]['store_name_1']+"</td>";
                    datas += "<td><a href='#' class='deletestore' id='deletestore' onclick='deletestore("+data[i]['id']+")'><i class='fa fa-minus-circle' style='color:red;'></i></a></td>";
                }
                $("#rowtoko").html(datas);

            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });

        });
        $("#add_store").on( 'click', function () {
            $("#showform").show();
        });
        $("#cancel").on( 'click', function () {
            $("#showform").hide();
        });
        $("#showform").hide();
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
                $('#cities').change(function () {
                    $('#store').prop('disabled', false);
                });
                $("#password").keyup(function() {
                    $('#retype_password').prop('disabled', false);
                })

                $('#retype_password').prop('disabled', true);
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
                $("#store").select2({
                    width: '100%',
                    placeholder: 'Pilih Store',
                    ajax: {
                        url: "{{ url('/storeReoFilter') }}",
                        method: 'POST',
                        dataType: 'json',
                        data: function (params) {
                            return {
                                cities_name : $('#cities').val(),
                                term: params.term
                            }
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (obj) {
                                    return {id: obj.id, text: obj.store_name_1}
                                })
                            }
                        },
                        cache: true
                    }
                });
    });
    function deletestore(data) {
        var parent = $(this).parent();
        console.log(parent);
        if(confirm("Sure you want to delete this update?")){
            $.ajax({
                url: "{{ url('/utilities/users/deletestore') }}",
                type: 'POST',
                dataType: 'json',
                data: {id_toko:data},
                beforeSend:function() {
                    parent.animate({'backgroundColor':'#fb6c6c'},300);
                },
                success:function() {
                    console.log("sukses");
                    $("#alert").show();
                    $("#alert").text("Data Success Deleted!");
                    $("#toast").addClass("show");
                    setTimeout(function() {
                        window.location.href = window.location.origin+'/utilities/users';
                    }, 1000);
                }
            });
        }
        return false;
    }
	</script>
@endsection