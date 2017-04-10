@extends('layouts.app')

@section('additional-css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css"/>
    <style type="text/css">
        .numberOfBa {
            width: 64px;
            max-width: 64px;
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
                <h1>Notification</h1>
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
                <span class="active">Notification</span>
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
                            <i class="icon-settings font-blue"></i>
                            <span class="caption-subject font-blue sbold uppercase">Notification</span>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="modal fade" id="basic" role="basic" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-hidden="true"></button>
                                        <h4 class="modal-title">Add New SDF BA</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ url('sdf/new/ba') }}" method="post" class="form-horizontal"
                                              id="sdf_form">
                                            {{ csrf_field() }}
                                            <div class="form-body">
                                                <div class="alert alert-danger display-hide">
                                                    <button class="close" data-close="alert"></button>
                                                    You have some form errors. Please check below.
                                                </div>
                                                <div class="alert alert-success display-hide">
                                                    <button class="close" data-close="alert"></button>
                                                    Your form validation is successful!
                                                </div>
                                                <div class="form-group form-md-line-input">
                                                    <label class="col-md-3 control-label" for="form_control_1">No. SDF
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control" placeholder=""
                                                               name="no_sdf">
                                                        <div class="form-control-focus"></div>
                                                        <span class="help-block">enter No SDF</span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input">
                                                    <label class="col-md-3 control-label" for="form_control_1">Store
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9">
                                                        <select class="store-ajax" name="store_id">
                                                            <option value="" selected="selected"></option>
                                                        </select>
                                                        <div class="form-control-focus"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input">
                                                    <label class="col-md-3 control-label" for="form_control_1">Request
                                                        Date
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9">
                                                        <div class="input-group  date date-picker"
                                                             data-date-format="yyyy-mm-dd">
                                                            <input type="text" class="form-control" name="request_date"
                                                                   readonly>
                                                            <span class="input-group-btn">
																	<button class="btn default" type="button">
																		<i class="fa fa-calendar"></i>
																	</button>
																</span>
                                                        </div>
                                                        <!-- /input-group -->
                                                        <span class="help-block"> Select date </span>
                                                        <div class="form-control-focus"></div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input">
                                                    <label class="col-md-3 control-label" for="form_control_1">First
                                                        Date
                                                        <span class="required">*</span>
                                                    </label>
                                                    <div class="col-md-9">
                                                        <div class="input-group  date date-picker"
                                                             data-date-format="yyyy-mm-dd">
                                                            <input type="text" class="form-control" name="first_date"
                                                                   readonly>
                                                            <span class="input-group-btn">
																	<button class="btn default" type="button">
																		<i class="fa fa-calendar"></i>
																	</button>
																</span>
                                                        </div>
                                                        <div class="form-control-focus"></div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close
                                        </button>
                                        <button type="submit" class="btn green">Save</button>
                                    </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="notifTable">
                            <thead>
                            <tr>
                                <th width="1"> #</th>
                                <th> Title</th>
                                <th> Message</th>
                                <th> Type</th>
                                <th width="10"> Detail</th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>

@endsection

@section('additional-script')
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/moment.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"
            type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function Sdf(id, role, status) {
            var role = role;
            var status = status;
            var my_url = "{{url('utilities/notification/')}}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            var read;
            if (status == 'new') {
                read = 0;
            } else {
                read = 1;
            }
            $.ajax(
                {
                    url: my_url + "/" + id,
                    type: 'PUT',
                    dataType: "JSON",

                    data: {
                        "read": 1
//                        "_method": 'DELETE',
                    },
                    success: function (data) {
                        console.log(role);
                        if (role == 'aro') {
                            if (status == 'new') {
                                window.location = "{{url('sdf')}}";
                            } else {
                                window.location = "{{url('configuration/wip')}}";
                            }
                        } else if (role == 'reo') {

                        } else {
                            if (status == 'new' || status == 'edit' || status == 'info') {
                                window.location = "{{url('master/ba/approval#ba_baru')}}"
                            } else if (status == 'resign') {
                                window.location = "{{url('master/ba/approval#ba_resign')}}"
                            } else if (status == 'hamil') {
                                window.location = "{{url('master/ba/approval#ba_cuti_hamil')}}"
                            } else if (status == 'sp') {
                                window.location = "{{url('master/ba/approval#ba_sp')}}"
                            }
                        }

                    },
                    error: function (data) {
                        console.log(data)
                    }
                });

        }


        function detail(id) {
            swal({
                title: "Are you sure to archive ?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax(
                    {
                        url: "sdf/" + id + "/delete",
                        type: 'POST',
                        dataType: "JSON",
                        data: {
                            "id": id,
                            "_method": 'DELETE',
                        },
                        success: function () {
                            swal("Success!", "SDF Archived!", "success")
                            $("." + id).remove();
                        }
                    });
            });
        }
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('#notifTable').dataTable({
                "fnCreatedRow": function (nRow, data) {
                    $(nRow).attr('class', data.id);
                },
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: '{{ url('utilities/notification/datatable') }}',
                    method: 'POST'
                },
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'message', name: 'message'},
                    {data: 'status', name: 'status'},
                    {data: 'detail', name: 'detail', orderable: false, searchable: false}
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
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function (data, page) {
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

        });
    </script>
@endsection