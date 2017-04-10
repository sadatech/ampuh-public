@extends('layouts.app')
@section('additional-css')
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
@stop
@section('content')
    <div class="page-content" id="app">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Acount</h1>
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
                <span class="active">Acount</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Acount</span>
                        </div>
                    </div>
                    <div class="portlet-body">

                        <div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group">
                                        <a class="btn purple-plum" data-toggle="modal" href="#addUser"> Add New User <i
                                                    class="fa fa-user"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="sample_editable_1">
                            <thead>
                            <tr>
                                <th> Id Acount#</th>
                                <th> Acount Name</th>
                                <th> Edit</th>
                                <th> Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--@foreach($acount as $key => $value)--}}

                            {{--<tr id="acount{{$value->id}}">--}}
                            {{--<td> {{ $value->id}} </td>--}}
                            {{--<td> {{ $value->name }} </td>--}}
                            {{--<td>--}}

                            {{--<button class="btn btn-warning btn-xs btn-detail open-modal" value="{{$value->id}}">Edit--}}
                            {{--<i class="fa fa-pencil"></i>--}}
                            {{--</button>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                            {{--<button class="btn btn-danger btn-xs btn-delete swit_alert" data-toggle="confirmation" data-singleton="true" value="{{$value->id}}">Delete--}}
                            {{--<i class="fa fa-trash-o"></i></button>--}}
                            {{--<!-- <a class="delete" href="javascript:;"> Delete </a> -->--}}
                            {{--</td>--}}
                            {{--</tr>--}}
                            {{--@endforeach--}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->

        <!--Add new user model-->
        <div class="modal fade" id="addUser" ole="basic" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-purple-plum bg-font-green-soft">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Add User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-body">
                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="form_control_1">Email
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="" name="name"
                                           v-model="input.name">
                                    <div class="form-control-focus"></div>
                                    <span class="help-block">User Name</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label" for="form_control_1">Email
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="" name="email"
                                           v-model="input.email">
                                    <div class="form-control-focus"></div>
                                    <span class="help-block">User Email</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label">Password
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    <input type="password" class="form-control" placeholder="" name="password"
                                           v-model="input.password">
                                    <div class="form-control-focus"></div>
                                    <span class="help-block">User Password</span>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input">
                                <label class="col-md-3 control-label"> User Role
                                    <span class="required">*</span>
                                </label>
                                <div class="col-md-9">
                                    <div class="mt-radio-inline">
                                        <label class="mt-radio mt-radio-outline"> Reo
                                            <input type="radio" value="1" name="test" @click="clickRole('reo')">
                                            <span></span>
                                        </label>
                                        <label class="mt-radio mt-radio-outline"> Arina
                                            <input type="radio" value="1" name="test" @click="clickRole('arina')">
                                            <span></span>
                                        </label>
                                        <label class="mt-radio mt-radio-outline">Loreal
                                            <input type="radio" value="1" name="test" @click="clickRole('loreal')">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div v-show="isReo">
                                <div class="form-group form-md-line-input">
                                    <label class="col-md-3 control-label" for="form_control_1">City
                                        <span class="required">*</span>
                                    </label>
                                    <div class="col-md-9">
                                        <select id="store-ajax" name="store_id">
                                            <option value="" selected="selected"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn green" id="btn-save" @click="addUser">Add User</button>
                        <input type="hidden" id="id_store" name="id_store" value="0">
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
@stop

@section('additional-script')
    <script src="/js/vue.js"></script>
    <script src="/js/vue-resource.js"></script>
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script>
        Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('[name="csrf-token"]').getAttribute('content');
        new Vue({
            el: '#app',
            data: {
                isReo: false,
                input: {
                    role: '',
                    email: '',
                    password: '',
                    name: '',
                }
            },
            methods: {
                clickRole (val) {
                    (val == 'reo') ? this.isReo = true : this.isReo = false;
                    this.input.role = val;
                },
                setOptions (url, placeholder, data, processResults) {
                    return {
                        ajax: {
                            url: url,
                            method: 'POST',
                            dataType: 'json',
                            delay: 250,
                            data: data,
                            processResults: processResults
                        },
                        minimumInputLength: 2,
                        width: '100%',
                        placeholder: placeholder
                    }
                },
                addUser () {
                    // TODO validasi dlu sebelum input datanya
                    this.$http.post('/addUser', this.input).then((response) = > {
                        console.log(response.body);
                },
                    (response) =
                >
                    {
                        console.log(response.body);
                    }
                )
                    ;
                }
            },
            ready: function () {
                var self = this;
                $(document).ready(function () {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#store-ajax').select2(self.setOptions('/cityFilter', 'Select City', function (params) {
                        return {
                            city: params.term
                        }
                    }, function (data, params) {
                        return {
                            results: $.map(data, function (obj) {
                                return {id: obj.id, text: obj.city_name}
                            })
                        }
                    }));
                });
            }
        });
    </script>
@stop