@extends('layouts.app')

@section('additional-css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/apps/css/ticket.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet"
          type="text/css"/>

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
                <h1>Support</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{url('/')}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Utilities</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Support</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
            {{--@if (Session::has('message1'))--}}
            {{--<div class="alert alert-info">{{ Session::get('message') }}</div>--}}
            {{--@endif--}}
            <!-- BEGIN PROFILE SIDEBAR -->
                <div class="profile-sidebar">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet bordered">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <img src="/assets/pages/media/profile/profile_user.jpg" class="img-responsive" alt="">
                        </div>

                        <!-- END SIDEBAR USERPIC -->
                        <!-- SIDEBAR USER TITLE -->
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name"> {{ @Auth::user()->name }}</div>
                            <div class="profile-usertitle-job"> {{ @Auth::user()->role }}</div>
                        </div>
                        <!-- END SIDEBAR USER TITLE -->
                        <!-- SIDEBAR MENU -->
                        <div class="profile-usermenu">
                            <ul class="nav">
                                <li class="active">
                                    <a href="{{ url('utilities/support') }}">
                                        <i class="icon-home"></i> Ticket List </a>
                                </li>
                                <li>
                                    <a href="{{ url('utilities/support_staff') }}">
                                        <i class="icon-settings"></i> Support Staff </a>
                                </li>
                            </ul>
                        </div>
                        <!-- END MENU -->
                    </div>
                    <!-- END PORTLET MAIN -->
                </div>
                <!-- END BEGIN PROFILE SIDEBAR -->
                <!-- BEGIN TICKET LIST CONTENT -->
                <div class="app-ticket app-ticket-list">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">TICKET LIST</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    @if(@Auth::user()->role != 'developer')
                                        <div class="table-toolbar">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="btn-group">
                                                        <button id="add_ticket" class="btn sbold green"> Add New
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    <a href="{{ url('utilities/support') . (Request::exists('archived') ? '?alldata' : '?archived') }}"
                                                       class="btn green-meadow"> {{(Request::exists('archived') ? 'All Data' : 'Archived') }}
                                                        <i class="fa fa-archive"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column"
                                           id="support">
                                        <thead>
                                        <tr>
                                            <th>
                                                <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                    <input type="checkbox" class="group-checkable"
                                                           data-set="#sample_1 .checkboxes"/>
                                                    <span></span>
                                                </label>
                                            </th>
                                            <th> ID #</th>
                                            <th> Title</th>
                                            <th> Cust. Name</th>
                                            <th> Cust. Email</th>
                                            <th> Waktu Lapor</th>
                                            <th> Due Date</th>
                                            <th> Status</th>
                                            @if(@Auth::user()->role != 'developer')
                                                <th> Action</th>
                                            @endif
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PROFILE CONTENT -->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>


    <!-- modal dialog -->
    <div class="modal fade" id="modal_add_ticket" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" action="{{ url('utilities/openticket') }}" method="POST"
                      enctype="multipart/form-data">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Open Ticket</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="form-group">
                                <label for="form_control_1">Title</label>
                                <input type="text" required class="form-control" id="form_control_1" name="title">
                                <!-- <span class="help-block">Some help goes here...</span> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                Message
                                <textarea style="width:100%" class="ticket-reply-msg" row="10"
                                          name="message"
                                          required></textarea>
                            </div>
                        </div>
                        {{--onclick=""--}}
                        <div class="form-dialog">
                            <div id="select_file" class="btn green fileinput-button">
                                <i class="glyphicon glyphicon-folder-open"></i>
                                <span>&nbsp;&nbsp;image file</span>
                            </div>
                            <input class='file' type="file" style="display: none " class="form-control"
                                   name="attachment"
                                   id="images_up" placeholder="Please choose your image">
                            <div id="my_file"></div>
                            <span class="help-block"></span>
                        </div>
                        <div class="alert alert-danger alert-dismissible" role="alert" style="display: none">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <strong>Warning!</strong> Anda hanya bisa menambahkan file gambar (jpg,jpeg,png)
                        </div>
                        <div class="form-dialog">
                            <img id="preview" src="/assets/pages/img/picture.png" width="200" height="200"
                                 class="img-responsive" alt="your image" style="display: none"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn green">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


@endsection

@section('additional-script')
    <script src="/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        function readUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#preview').attr('src', e.target.result);
                    $('#preview').show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function Archive(id) {
            swal({
                title: "Are you sure to archive ?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax(
                    {
                        url: "ticket/" + id + "/delete",
                        type: 'POST',
                        dataType: "JSON",
                        data: {
                            "id": id,
                            "_method": 'DELETE',
                        },
                        success: function () {
                            swal("Success!", "Ticket Archived!", "success")
                            $("." + id).remove();
                        }
                    });
            });
        }

        function Restore(id) {
            swal({
                title: "Are you sure to restore ?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax(
                    {
                        url: "ticket/" + id + "/restore",
                        type: 'POST',
                        dataType: "JSON",
                        data: {
                            "id": id,
                        },
                        success: function () {
                            swal("Success!", "Ticket Restored!", "success")
                            $("." + id).remove();
                        }
                    });
            });
        }

        var self = this;
        $(document).ready(function () {
            @if (Session::has('message'))
                   swal("Sukses", "Ticket Berhasil di kirim", "success");
            @endif

            $('#select_file').on('click', function () {
                $('#images_up').trigger('click');
                $('#images_up').change(function () {
                    var filename = $('#images_up').val();
                    if (filename.substring(3, 11) == 'fakepath') {
                        filename = filename.substring(12);
                        var ext = filename.split(".")[1];
                    }
                    var exts = ['jpeg', 'bmp', 'png', 'jpg'];
                    if (exts.indexOf(ext) === -1) {
                        $(".alert").show();
                    } else {
                        self.readUrl(this);
                        $(".alert").hide();
                        $('#my_file').html(filename);
                    }
                });
            });
            $('#add_ticket').click(function () {
                $('#modal_add_ticket').modal('show');
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var table = $('#support').dataTable({
                "fnCreatedRow": function (nRow, data) {
                    $(nRow).attr('class', data.id);
                },
                "processing": true,
                "serverSide": true,
                "pageLength": 5,
                "lengthMenu": [5, 10, 25, 50, 75, 100],
                "ajax": {
                    url: '{{ url('utilities/openticket/datatable') .(Request::exists('archived') ? '?archived' : '') }}',
                    method: 'POST'
                },
                columns: [
                    {data: 'cek', name: 'cek', orderable: false},
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title', class: 'namewrapper'},
                    {
                        data: 'user.name',
                        name: 'user.name',
                        class: 'namewrapper'
                    },
                    {data: 'user.email', name: 'user.email', class: 'namewrapper'},
                    {data: 'created_at', name: 'created_at', class: 'namewrapper'},
                    {data: 'due_date', name: 'due_date', "defaultContent": "", class: 'namewrapper'},
                    {data: 'status', name: 'status', "defaultContent": "", class: 'namewrapper'},
                        @if(@Auth::user()->role != 'developer')
                    {data: 'archive', name: 'archive', "defaultContent": "", orderable: false, searchable: false}
                    @endif
                ]
            });

        });
        {{--function here--}}
    </script>
@endsection