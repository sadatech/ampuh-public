@extends('layouts.app')

@section('additional-css')
    <link href="/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.css" rel="stylesheet"
          type="text/css"/>

    <style type="text/css">
        .errorformclass {
            color:red;
            border: solid 1px red;
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
                <a href="#">Utilities</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="/master/store">Users</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">{{ (@$user) ?'Edit' : 'Tambah' }}</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <div id="toast"></div>
        <div class="row">
            <div class="col-md-32">
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-user"></i>
                            <span class="caption-subject sbold uppercase">{{ (@$user) ?'Edit' : 'Tambah' }} Users</span>
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <!-- BEGIN FORM-->
                        <form class="horizontal-form" action="#" id="usersform" method="post" enctype="multipart/-formdata">
                            {{ csrf_field() }}
                            <input type="hidden" name="_id" id="edit_id" value="{{@$user->id}}">
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
                                    <h3 class="form-section">Users</h3>
                                    <div id="namerow">
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div class="form-group">
                                                    <label>Username</label>
                                                    <input type="text" class="form-control" placeholder="Username" id="username" name="username"
                                                      value="{{ @$user->name }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" placeholder="Email" name="email" id="email"
                                                      value="{{ @$user->email }}"></div>
                                        </div>
                                    </div>
                                    <div id="passwordrow">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input type="password" class="form-control" placeholder="Password" name="passwords" id="passwords"
                                                      >
                                                </div>
                                        	</div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Re-type Password</label>
                                                    <input type="password" class="form-control" placeholder="Retype Password" name="passwordz" id="passwordz"
                                                      >
                                                </div>
                                        	</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 ">
                                            <div class="form-group">
                                                <label>Akses</label>
                                                <select class="form-control" name="akses" id="akses">
                                                	<option value="">Pilih Akses</option>
                                                	@if(@$find_user->role != "aro")
                                                        <option value="arina"
                                                    @if(@$user->role == "arina") 
                                                        selected
                                                    @endif
                                                        >Arina</option>
                                                    @endif
                                                    @if(@$find_user->role != "aro")
                                                	   <option value="loreal"
                                                    @if(@$user->role == "loreal") 
                                                        selected
                                                    @endif
                                                        >Loreal</option>
                                                    @endif
                                                    @if(@$find_user->role != "aro")
                                                       <option value="loreal_ho"
                                                    @if(@$user->role == "loreal_ho") 
                                                        selected
                                                    @endif
                                                        >Loreal HO</option>
                                                    @endif
                                                    @if(@$find_user->role == "aro")
                                                	   <option value="reo"
                                                    @if(@$user->role == "reo") 
                                                        selected
                                                    @endif
                                                        >Reo</option>
                                                    @endif
                                                    @if(@$find_user->role != "aro")
                                                        <option value="aro"
                                                    @if(@$user->role == "aro") 
                                                        selected
                                                    @endif
                                                        >Aro</option>
                                                    @endif
                                                    @if(@$find_user->role != "aro")
                                                	   <option value="invitation" 
                                                    @if(@$user->role == "invitation")
                                                        selected
                                                    @endif
                                                        >Invitation</option>
                                                    @endif
                                                </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="aro_branch" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Cabang</label>
                                                    <select class="form-control" name="branch" id="branch" multiple="multiple">
                                                        @if(@$user->role == "aro")
                                                        @foreach (@$branch as $branch_aro)
                                                            <option value="{{ $branch_aro->branch_id }}" selected> {{ $branch_aro->arina_branch->cabang }} </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="reo_store" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Provinsi</label>
                                                    <select class="form-control" name="province" id="province">
                                                        @if(@$user->role == "reo")
                                                        @foreach (@$store as $toko)
                                                            <option value="{{ $toko->city->id }}" selected> {{ $toko->city->province_name }} </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Kota</label>
                                                    <select class="form-control" multiple="multiple" name="cities" id="cities">
                                                        @if(@$user->role == "reo")
                                                        @foreach (@$store as $toko)
                                                            <option value="{{ $toko->city->id }}" selected> {{ $toko->city->city_name }} </option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="selectalltoko" style="display:none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                        <div class="form-group">
                                                            <div class="md-checkbox">
                                                                <input type="checkbox" id="checkbox1" class="md-check">
                                                                <label for="checkbox1">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Pilih Semua Toko </label>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Store</label>
                                                    <select class="form-control" name="store" id="store" multiple="multiple">
                                                        @if(@$user->role == "reo")
                                                        @foreach (@$store as $toko)
                                                            <option value="{{ @$toko->id }}" selected>{{@$toko->store_name_1}}</option>
                                                        @endforeach
                                                        @endif

                                                        
                                                    </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                            <label>Image</label>
                                            <div class="form-group">
                                            <div>
                                               <img @if(@$user) @if($user->file == null) src="../../../assets/pages/img/user.png" @else ../../../attachment/pasfoto/{{ @$user->file }} @endif @else src="../../../assets/pages/img/user.png" @endif id="profile_img" width="100" height="100">
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-1">
                                            <label>Recommended size 100 X 100</label>
                                            <div class="form-group">
											<div>
												<span id="buttonfiles" class="icon-btn" style="width:10%;background: #f0f0f0;
												color:#000;cursor: crosshair;border:solid 0,5px #000;border:solid 1px #aaaaaa;"><i class="fa fa-file-image-o"></i><div style="padding-top:5%;">Choose File</div></span>
											</div>
                                            <input type="file" name="img_profile" id="img_profile" value="{{ @$user->file }}" accept="image/*" style="opacity:0;" />
                                        </div>
                                    </div>
                                    <div>
                                        <div>
                                            <label></label>
                                            <div class="form-group">
                                            <span id="val" style="bottom:0;"></span>
                                       </div>
                                    </div>
                                    <div>
                                    </div>
                                </div>
                                <div class="row">
                                </div>
                            <!--/row-->

                            <div class="form-actions right">
                                <div id="loading" style="display:none;">
                                    
                                </div>
                                <button type="button" class="btn default">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </button>
                                <button type="submit" id="save" class="btn blue ladda-button" data-style="expand-left">
                                    <i class="fa fa-check" id="load_check"></i> Simpan
                                </button>
                            </div>
                        <!-- END FORM-->
                    </div>
                </form>
                </div>
            </div>
            <!-- BEGIN PAGE BASE CONTENT -->
        </div>


        @endsection

        @section('additional-script')

	    	<script src="/assets/global/plugins/form-validation/form-validation.min.js" type="text/javascript"></script>
            <script type="text/javascript">
            dynamicShow();
            if ($("#edit_id").val() == "") {
                console.log("kosong");
                let formData = new FormData();
                $("#usersform").validate({
                    errorClass: "errorformclass",
                    rules: {
                        username: "required",
                        email: "required",
                        passwords: {
                            required  : true,
                        },
                        passwordz: {
                            equalTo   : "#passwords",
                            required  : true,
                        },
                        akses: "required",
                        province: "required",
                        cities: "required",
                        store: "required",
                    },
                    messages: {
                        username: "Please enter username",
                        email: "Please enter your email",
                        password: "Please enter your password",
                        retype_password: "Password not match",
                        akses: "Please enter your Akses",
                        province: "Please enter Province",
                        cities: "Please enter Cities",
                        store: "Please enter Store",
                        
                    },
                    submitHandler: (form) => {
                        var base_url = window.location.origin;
                        var fields = $(":input").serializeArray();
                        formData.append('username',$("#username").val());
                        formData.append('email',$("#email").val());
                        formData.append('password',$("#passwords").val());
                        formData.append('retype_password',$("#passwordz").val());
                        formData.append('akses',$("#akses").val());
                        formData.append('edit_id',$("#edit_id").val());
                        formData.append('token',$("input[name=_token]").val());

                        formData.append('image_profile', $('input[type=file]')[0].files[0]);
                        if ($("#akses").val() == "reo") { formData.append('store_id',$("#store").val()); }
                        if ($("#akses").val() == "aro") { formData.append('branch_id',$("#branch").val()); }
                        var arr = {"token" : fields[1].value, "username" : fields[2].value, "email" : fields[3].value, "retype_password" : fields[5].value, "akses" : fields[6].value, "store_id" : $("#store").val()};
                        var id = "{{@$user->id}}";
                        $.ajax({
                            "url": ($("#edit_id").val() == "") ? "{{ url('utilities/users/addusers') }}" : "/utilities/user/" + id + "/edit",
                            "dataType": 'json',
                            "cache": false,
                            "contentType": false,
                            "processData": false,
                            "data" : formData,
                            "type": ($("#edit_id").val() == "") ? "POST" : "POST",
                            "headers" : {
                                'X-CSRF-TOKEN': fields[1].value
                            },
                            beforeSend : function() {
                                $("#save").prop('disabled', 'disabled');
                                $("#load_check").attr('class', 'fa fa-spinner');
                            },
                            success : function(data) {
                                console.log(data);
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
                                        $("#toast").addClass("show");
                                        $("#toast").text(data[i].content);
                                        setTimeout(function() {
                                            $("#toast").fadeOut(300, function() {
                                                $("#toast").remove();
                                                window.location.href = window.location.origin+'/utilities/users';
                                            });
                                        }, 2000);
                                    }
                                });
                            }
                        });
                    }
                });
            }else{
                console.log("ada");
                let formData = new FormData();
            $("#usersform").validate({
                errorClass: "errorformclass",
                rules: {
                    username: "required",
                    email: "required",
                    akses: "required",
                    province: "required",
                    cities: "required",
                    store: "required",
                },
                messages: {
                    username: "Please enter username",
                    email: "Please enter your email",
                    akses: "Please enter your Akses",
                    province: "Please enter Province",
                    cities: "Please enter Cities",
                    store: "Please enter Store",
                    
                },
                submitHandler: (form) => {
                    var base_url = window.location.origin;
                    var fields = $(":input").serializeArray();
                    formData.append('username',$("#username").val());
                    formData.append('email',$("#email").val());
                    formData.append('password',$("#passwords").val());
                    formData.append('retype_password',$("#passwordz").val());
                    formData.append('akses',$("#akses").val());
                    formData.append('edit_id',$("#edit_id").val());

                    formData.append('image_profile', $('input[type=file]')[0].files[0]);
                    if ($("#akses").val() == "reo") { formData.append('store_id',$("#store").val()); }
                    if ($("#akses").val() == "aro") { formData.append('branch_id',$("#branch").val()); }
                    var arr = {"token" : fields[1].value, "username" : fields[2].value, "email" : fields[3].value, "retype_password" : fields[5].value, "akses" : fields[6].value, "store_id" : $("#store").val()};
                    var id = "{{@$user->id}}";
                    $.ajax({
                        "url": ($("#edit_id").val() == "") ? "{{ url('utilities/users/addusers') }}" : "/utilities/user/" + id + "/edit",
                        "dataType": 'json',
                        "cache": false,
                        "contentType": false,
                        "processData": false,
                        "data" : formData,
                        "type": ($("#edit_id").val() == "") ? "POST" : "POST",
                        "headers" : {
                            'X-CSRF-TOKEN': fields[1].value
                        },
                        beforeSend : function() {
                                $("#save").prop('disabled', 'disabled');
                                $("#load_check").attr('class', 'fa fa-spinner');
                        },
                        success : function(data) {
                            console.log(data);
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
                                    $("#toast").addClass("show");
                                    $("#toast").text(data[i].content);
                                    setTimeout(function() {
                                        $("#toast").fadeOut(300, function() {
                                            $("#toast").remove();
                                            window.location.href = window.location.origin+'/utilities/users';
                                        });
                                    }, 2000);
                                }
                            });
                        }
                    });
                }
            });
            }
            	$("#akses").on("change",function() {
            		var id = $(this).val();
            		if (id == "reo") {
            			$("#reo_store").show();
                        $("#username").show();
                        $("#passwordrow").show();
                        $('#cities').prop('disabled', true);
                        $('#store').prop('disabled', true);
            		}else if(id == "invitation"){
                        $("#reo_store").hide();
                        $("#username").hide();
                        // $("#password").hide();
                        $("#passwordrow").hide();
                    }else if (id == "loreal" || id == "arina") {
                        $("#username").show();
                        $("#reo_store").hide();
                        $("#passwordrow").show();
                    }else if ($("#akses").val() == "aro") {
                        $("#aro_branch").show();
                        $("#reo_store").hide();
                    }
                    else{
                        $("#reo_store").hide();
                    };
            	})
	            $("#buttonfiles").click(function() {
					$("input[type='file']").trigger('click');
				});
				$("input[type='file']").change(function () {
					// $('#val').text(this.value.replace(/C:\\fakepath\\/i, ''));
				})

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
                $('#branch').select2({
                    width: '100%',
                    placeholder: 'Pilih Branch',
                    ajax: {
                        url: "{{ url('/branchFilter') }}",
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
                                    return {id: obj.id, text: obj.cabang}
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
                $("#province").on('change', function(event) {
                    event.preventDefault();
                    var id = $(this).val();
                    $.ajax({
                        url: "{{ url('/provinceFilter/allStore') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: {province_name: id},
                    })
                    .done(function(data) {
                        $.each(data, function(i) {
                            console.log(data[i]);
                            if (data[i].code == 1) {
                                $("#selectalltoko").show();
                            }
                            else{
                                $("#selectalltoko").hide();
                            }
                        });
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });
                    
                });
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
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#profile_img').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#img_profile").change(function(){
        readURL(this);
    });
    $("#checkbox1").click(function() {
        if($(this).is(':checked')){
            $.ajax({
                url: "{{ url('/provinceFilter/getStore') }}",
                type: 'POST',
                dataType: 'json',
                data: {province_name:$("#province").val()},
            })
            .done(function(data) {
                var data_store = "";
                var data_city = "";
                for (var i = data.length - 1; i >= 0; i--) {
                    data_store += "<option value='"+data[i]['id']+"' selected>"+data[i]['store_name_1']+"</option>";
                    data_city += "<option value='"+data[i]['city']['id']+"' selected>"+data[i]['city']['city_name']+"</option>";
                }
                $("#store").prop('disabled', false);
                $("#store").append(data_store);
                $("#cities").prop('disabled', true);
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                console.log("complete");
            });
            
        } else {
                $("#store").empty();
                $("#cities").prop('disabled', false);
        }
    });
    function dynamicShow() {
        if ($("#akses").val() == "reo") {
            $("#username").show();
            $("#reo_store").show();
            $("#passwordrow").show();
        }
        if ($("#akses").val() == "aro") {
            $("#aro_branch").show();
            $("#reo_store").hide();
        }
        if ($("#akses").val() == "invitation") {
            $("#reo_store").hide();
            $("#username").hide();
            $("#passwordrow").hide();
        }
        if ($("#akses").val() == "loreal" && $("#akses").val() == "arina") {
            $("#username").show();
            $("#reo_store").hide();
            $("#passwordrow").show();
        }
    }
            </script>
@endsection