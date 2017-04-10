@extends('layouts.app')

@section('additional-css')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="/assets/pages/css/profile.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/apps/css/ticket.min.css" rel="stylesheet" type="text/css"/>
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="/css/jquery.fancybox.css?v=2.1.5" media="screen"/>
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
                <a href="{{url("/")}}">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Utilities</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{url('/utilities/support')}}">Support</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Ticket Detail</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN TICKET LIST CONTENT -->
                <div class="app-ticket app-ticket-details">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet light bordered">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption caption-md">
                                        <i class="icon-globe theme-font hide"></i>
                                        <span class="caption-subject font-blue-madison bold uppercase">Ticket Details</span>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="ticket-id bold font-blue">#{{$ticket->id}}</div>
                                            <div class="ticket-title bold uppercase font-blue">{{$ticket->title}}</div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="ticket-cust">
                                                <span class="bold">Customer:</span> {{$ticket->user->name}} (
                                                <a href="mailto:customer@gmail.com">{{$ticket->user->email}})</a>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="ticket-date">
                                                <span class="bold">Waktu Pelaporan:</span> {{\Carbon\Carbon::parse($ticket->created_at)->format('d-M-Y')}}
                                            </div>
                                        </div>
                                    </div>
                                    <?php $no = 0;
                                    ?>
                                    @foreach($ticket->detail as $key => $value)
                                        <div class="row thumbnail {{$value->role == 1 ? '' : 'well'}}"
                                             style="padding: 10px;margin-top:-15px">
                                            @if($value->role == 1)
                                                <div class="col-md-1">
                                                    <img alt="" class="img-circle" style="margin-left: 10px"
                                                         src="/assets/layouts/layout4/img/avatar9.jpg"/>
                                                </div>
                                                <div class="col-md-11">
                                                    <div class="ticket-msg">
                                                        @if(($value->role == 1 && @Auth::user()->role != 'developer') || ($value->role == 0 && @Auth::user()->role == 'developer') )

                                                            <div style="float: right">
                                                                <small>
                                                                    <cite>
                                                                        <i class="fa fa-calendar-check-o"></i> {{\Carbon\Carbon::parse($value->created_at)->format('d-M-Y H:i:s')}}
                                                                    </cite></small>
                                                            </div>
                                                            <div style="float: right;margin-right:15px">
                                                                <a href="#" data-id="{{$value->id}}" class="edit"
                                                                   style="text-decoration: none;color:#000">
                                                                    <i class="fa fa-pencil"></i> Edit
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if($value->role == 1)
                                                            <h3>
                                                                <i class="icon-note"></i> {{$ticket->user->name}}
                                                                Message
                                                            </h3>
                                                        @else
                                                            <h3>
                                                                <i class="icon-action-redo"></i> Reply Developer</h3>
                                                        @endif
                                                        {{$value->message}}
                                                        @if($value->attachment != '')
                                                            <div class="row">
                                                                <div class="col-xs-6 col-md-3 thumbnail"
                                                                     style="margin-left: 15px;margin-top: 10px">
                                                                    <a class="fancybox-effects-a"
                                                                       href="/attachment/support/{{$value->attachment}}"
                                                                       title="{{$value->message}}">
                                                                        <img width="125px" height="125px"
                                                                             src="/attachment/support/{{$value->attachment}}"
                                                                             alt=""/>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>

                                            @else
                                                {{--for developer--}}
                                                <div class="col-md-11 text-right">
                                                    <div class="ticket-msg">
                                                        @if(($value->role == 1 && @Auth::user()->role != 'developer') || ($value->role == 0 && @Auth::user()->role == 'developer') )

                                                            <div style="float: left">
                                                                <small>
                                                                    <cite>
                                                                        <i class="fa fa-calendar-check-o"></i> {{\Carbon\Carbon::parse($value->created_at)->format('d-M-Y H:i:s')}}
                                                                    </cite></small>
                                                            </div>
                                                            <div style="float: left; margin-left:20px">
                                                                <a href="#" data-id="{{$value->id}}" class="edit"
                                                                   style="text-decoration: none;color:#000">
                                                                    <i class="fa fa-pencil"></i> Edit
                                                                </a>
                                                            </div>
                                                        @endif
                                                        @if($value->role == 1)
                                                            <h3>
                                                                <i class="icon-note"></i> {{$ticket->user->name}}
                                                                Message
                                                            </h3>
                                                        @else
                                                            <h3>
                                                                <i class="icon-action-redo"></i> Reply Developer</h3>
                                                        @endif
                                                        {{$value->message}}
                                                        @if($value->attachment != '')
                                                            <div class="row ">
                                                                <div class="col-md-9"></div>
                                                                <div class="col-xs-6 col-md-3 thumbnail "
                                                                     style="float: left">
                                                                    <a class="fancybox-effects-a"
                                                                       href="/attachment/support/{{$value->attachment}}"
                                                                       title="{{$value->message}}">
                                                                        <img width="135px" height="135px"
                                                                             src="/attachment/support/{{$value->attachment}}"
                                                                             alt=""/>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <img alt="" class="img-circle" style="margin-left: 10px"
                                                         src="/assets/layouts/layout4/img/avatar9.jpg"/>
                                                </div>
                                                {{--for developer--}}
                                            @endif
                                        </div>
                                        <?php
                                        $no++;
                                        ?>
                                    @endforeach
                                    <div class="ticket-line"></div>
                                    {{--                                    @if($ticket->status != 'Completed')--}}
                                    <form class="form-group" action="{{url('/utilities/replyTicket/'.$ticket->id)}}"
                                          method="post" enctype="multipart/form-data">
                                        {!! csrf_field() !!}
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h3>
                                                    <i class="icon-action-redo"></i> Ticket Reply</h3>
                                                <textarea class="ticket-reply-msg" row="10" name="message"
                                                          required></textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h3>
                                                    <i class="icon-user-follow"></i> Assign to</h3>
                                                <select class="ticket-assign" name="role">
                                                    @if(@Auth::user()->role != 'developer')
                                                        <option value="1">Developer</option>
                                                    @else
                                                        <option value="0">{{$ticket->user->name}}</option>
                                                    @endif
                                                </select>
                                            </div>

                                            @if(@Auth::user()->role == 'developer')

                                                <div class="col-md-3">
                                                    <h3 class="ticket-margin">
                                                        <i class="icon-calendar"></i> Due Date</h3>
                                                    <input class="form-control form-control-inline input-small date-picker"
                                                           size="16" type="text" value="" name="due_date"/></div>
                                                <div class="col-md-3">
                                                    <h3 class="ticket-margin">
                                                        <i class="icon-info"></i> Status</h3>
                                                    <select class="ticket-status" name="status">
                                                        <option value="Pending">Pending</option>
                                                        <option value="Processed">Processed</option>
                                                        <option value="Completed">Completed</option>
                                                    </select>
                                                </div>
                                            @endif
                                            <div class="col-md-3">
                                                <h3 class="ticket-margin">
                                                    <i class="fa fa-file-image-o"></i> Attachment</h3>
                                                <div class="form-dialog">
                                                    <div id="add_file" class="btn btn red">
                                                        <i class="fa fa-plus"></i>
                                                        <span>add file</span>
                                                    </div>
                                                    <input class='file' type="file" style="display: none "
                                                           class="form-control"
                                                           name="attachment"
                                                           id="images_add" placeholder="Please choose your image">
                                                    <div id="my_file"></div>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>

                                        </div>
                                        @if($ticket->status == 'Completed')
                                            <input type="hidden" name="status" value="Reopen">
                                        @endif
                                        <button class="btn {{$ticket->status == 'Completed' ? 'btn-circle red-thunderbird':'btn-square uppercase bold green'}}"
                                                type="submit"> {{$ticket->status == 'Completed' ? 'Re Open':'Submit'}}
                                            @if($ticket->status == 'Completed')<i class="icon-action-redo"></i>@endif
                                        </button>
                                    </form>
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

    {{--for edit message--}}
    <!-- modal dialog -->
    <div class="modal fade" id="modal_edit_ticket" tabindex="-1" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form role="form" action="" method="POST"
                      enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PUT">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Edit Message</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-12">
                                <i class="glyphicon glyphicon-envelope"></i> Message
                                <textarea style="width:100%" class="ticket-reply-msg" id="message" row="10"
                                          name="message"
                                          required></textarea>
                            </div>
                        </div>

                        <div class="form-dialog">
                            <div id="select_file" class="btn green fileinput-button">
                                <i class="fa fa-plus"></i>
                                <span>change file</span>
                            </div>
                            <input class='file' type="file" style="display: none " class="form-control"
                                   name="attachment"
                                   id="images_up" placeholder="Please choose your image">
                            <div id="my_file"></div>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-dialog">
                            <div class="row" style="margin-left: 5px">
                                <div class="col-md-4 thumbnail">
                                    <img id="preview" src="" alt=""/>
                                </div>
                            </div>
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
    <script type="text/javascript" src="/js/jquery.fancybox.js?v=2.1.5"></script>
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
        var self = this;

        $(document).ready(function () {
            @if (Session::has('edit'))
                 swal("Sukses", "Pesan Berhasil di edit", "success");
            @endif
            @if (Session::has('reply'))
                 swal("Sukses", "Pesan Berhasil di kirim", "success");
            @endif
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

            $('#add_file').on('click', function () {
                $('#images_add').trigger('click');
                $('#images_add').change(function () {
                    var filename = $('#images_add').val();
                    if (filename.substring(3, 11) == 'fakepath') {
                        filename = filename.substring(12);
                        var ext = filename.split(".")[1];
                    }
                    var exts = ['jpeg', 'bmp', 'png', 'jpg'];
                    if (exts.indexOf(ext) === -1) {
                        swal("Warning", "Hanya untuk file gambar (jpg,jpeg,png)", "error");
                        $('#my_file').html('');
                    } else {
                        $('#my_file').html(filename);
                    }
                });
            });

            $('#select_file').click(function () {
                $('#images_up').trigger('click');
                $('#images_up').change(function () {
                    var filename = $('#images_up').val();
                    if (filename.substring(3, 11) == 'fakepath') {
                        filename = filename.substring(12);
                    }
                    self.readUrl(this);
                    $('#my_file').html(filename);
                });
            });
            $('.edit').click(function () {
                var url = '/utilities/dataReplyTicket/';
                var id = $(this).data("id");
                console.log(url + id);
                $.get(url + id, function (data) {
                    console.log(data);
                    $('#message').val(data.message);
                    $('#message').addClass('edited');
                    $('#modal_edit_ticket').modal('show');
                    var urledit = "{{url('/utilities/editReplyTicket/')}}";
                    $('form').attr('action', urledit + "/" + data.id + "/" + data.ticket_id);
                    $('#preview').attr("src", "/attachment/support/" + data.attachment);
                    $('#link_prev').attr("href", "/attachment/support/" + data.attachment);
                });
            });

            $('#reopen').on('click', function (e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                e.preventDefault();
                var formData = {
                    status: 'Reopen'
                }
                var type = "PUT";
                var my_url = "{{url('utilities/reopen')}}" + "/" + "{{$value->ticket_id}}";
//                console.log(my_url);
//                return;
                $.ajax({

                    type: type,
                    url: my_url,
                    data: formData,
                    dataType: 'json',
                    success: function (data) {
                        console.log(data);
                        swal({
                            title: 'succes!',
                            text: 'Ticket Berhasil di buka',
                            type: 'success'
                        }, function () {
                            window.location.href = '{{url('utilities/detail/'. $value->ticket_id)}}';
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        swal("Error!", "Ticket gagal di buka, coba lagi", "error");
                    }
                });
            });
        });
    </script>
@endsection