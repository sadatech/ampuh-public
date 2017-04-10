@extends('layouts.app')

@section('additional-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.css">
@endsection

@section('content')

    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>BA</h1>
            </div>
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="index.html">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Master</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="#">Ba</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">Approval</span>
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
                            <span class="caption-subject font-red sbold uppercase">Approval</span>
                        </div>
                        <div class="divider"><br><br><br><br></div>
                        <div class="tabbable-custom nav-justified">
                            <ul class="nav nav-tabs nav-justified">
                                <li class="active">
                                    <a href="#ba_baru" class="active" data-toggle="tab"> BA Baru
                                        <span class="badge badge-default"> {{ $baNew }} </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="#ba_resign" data-toggle="tab"> Resign
                                        <span class="badge badge-default">{{ $baResign }}</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#ba_cuti_hamil" data-toggle="tab"> BA Rejoin
                                        <span class="badge badge-default">{{ $baRejoin }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="portlet-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="ba_baru">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="btn-group">
                                                    <a href="{{ url('master/ba') }}" class="btn green">
                                                        <i class="fa fa-arrow-left"></i> Back
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-striped table-hover table-bordered" id="ba_new_table">
                                        <thead>
                                        <tr>
                                            <th width="1"> NO#</th>
                                            <th> NIK</th>
                                            <th> Nama</th>
                                            <th> Tanggal Masuk</th>
                                            <th> Status</th>
                                            <th> Kelas</th>
                                            <th width="1"> Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane" id="ba_resign">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="btn-group">
                                                    <a href="{{ url('master/ba') }}" class="btn green">
                                                        <i class="fa fa-arrow-left"></i> Back
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-striped table-hover table-bordered" id="ba_resign_table">
                                        <thead>
                                        <tr>
                                            <th width="1"> NO#</th>
                                            <th> NIK</th>
                                            <th> Nama</th>
                                            <th> Tanggal Masuk</th>
                                            <th> Status</th>
                                            <th> Kelas</th>
                                            <th width="10"> Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="tab-pane" id="ba_cuti_hamil">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="btn-group">
                                                    <a href="{{ url('master/ba') }}" class="btn green">
                                                        <i class="fa fa-arrow-left"></i> Back
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table class="table table-striped table-hover table-bordered"
                                           id="ba_rejoin_table">
                                        <thead>
                                        <tr>
                                            <th width="1"> NO#</th>
                                            <th> NIK</th>
                                            <th> Nama</th>
                                            <th> Tanggal Masuk</th>
                                            <th> Status</th>
                                            <th> Kelas</th>
                                            <th width="10"> Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <!-- END PAGE BASE CONTENT -->
            </div>
        </div>
    </div>
@endsection

@section('additional-script')
    <script src="/assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/sweetalert2/5.3.5/sweetalert2.min.js"></script>
    <script>
        $("#ba-rejoin").pulsate({pause: 1000})
        var approval_resign_id = '{{ $approval_resign_id }}';
        var approval_newba_id = '{{ $approval_newBa_id }}';
        var approval_maternity_id = '{{ $approval_baMaternity_id }}';
        var approval_rejoin_id = '{{$approval_rejoin_id}}';

        $('#ba_rejoin_table').dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: '{{ url('master/ba/approval/datatable/') }}/' + approval_rejoin_id,
                method: 'POST'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nik', name: 'nik', class: 'namewrapper'},
                {data: 'name', name: 'name', class: 'namewrapper'},
                {data: 'join_date', name: 'join_date', class: 'namewrapper'},
                {data: 'status', name: 'status', class: 'namewrapper'},
                {data: 'class', name: 'class', class: 'namewrapper'},
                {data: 'approve', name: 'approve', orderable: false, searchable: false, class: 'namewrapper'}
            ]
        });

        $('#ba_cuti_table').dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: '{{ url('master/ba/approval/datatable/') }}/' + approval_maternity_id,
                method: 'POST'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nik', name: 'nik', class: 'namewrapper'},
                {data: 'name', name: 'name', class: 'namewrapper'},
                {data: 'join_date', name: 'join_date', class: 'namewrapper'},
                {data: 'status', name: 'status', class: 'namewrapper'},
                {data: 'class', name: 'class', class: 'namewrapper'},
                {data: 'approve', name: 'approve', orderable: false, searchable: false, class: 'namewrapper'}
            ]
        });

        $('#ba_sp_table').dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: '{{ url('master/ba/approval/datatable/') }}/sp',
                method: 'POST'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nik', name: 'nik', class: 'namewrapper'},
                {data: 'name', name: 'name', class: 'namewrapper'},
                {data: 'join_date', name: 'join_date', class: 'namewrapper'},
                {data: 'status', name: 'status', class: 'namewrapper'},
                {data: 'class', name: 'class', class: 'namewrapper'},
                {data: 'status_sp', name: 'status_sp', class: 'namewrapper'},
                {data: 'approve', name: 'approve', orderable: false, searchable: false, class: 'namewrapper'}
            ]
        });
        $('#ba_resign_table').dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: '{{ url('master/ba/approval/datatable/') }}/' + approval_resign_id,
                method: 'POST'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nik', name: 'nik', class: 'namewrapper'},
                {data: 'name', name: 'name', class: 'namewrapper'},
                {data: 'join_date', name: 'join_date', class: 'namewrapper'},
                {data: 'status', name: 'status', class: 'namewrapper'},
                {data: 'class', name: 'class', class: 'namewrapper'},
                {data: 'approve', name: 'approve', orderable: false, searchable: false, class: 'namewrapper'}
            ]
        });

        $('#ba_new_table').dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: '{{ url('master/ba/approval/datatable/') }}/' + approval_newba_id,
                method: 'POST'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'nik', name: 'nik', class: 'namewrapper'},
                {data: 'name', name: 'name', class: 'namewrapper'},
                {data: 'join_date', name: 'join_date', class: 'namewrapper'},
                {data: 'status', name: 'status', class: 'namewrapper'},
                {data: 'class', name: 'class', class: 'namewrapper'},
                {data: 'approve', name: 'approve', orderable: false, searchable: false, class: 'namewrapper'}
            ]
        });

        function approveSP(id) {
            swal({
                title: "Setujui SP ?",
                type: "question",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                preConfirm: function () {
                    return new Promise(function (resolve, reject) {
                        $.ajax({
                            url: "/master/ba/" + id + "/sp",
                            type: 'POST',
                            dataType: "JSON",
                            data: {
                                "id": id
                            },
                            success: function () {
                                swal({
                                    title: 'Success!',
                                    text: "BA Telah diberi SP",
                                    type: 'success',
                                }).then(function () {
                                    window.history.back();
                                })

                            }
                        });
                        resolve()
                    });
                }
            })
        }
        function approveMaternity(id) {
            swal({
                title: "Setujui BA Cuti Hamil ?",
                type: "question",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                preConfirm: function () {
                    return new Promise(function (resolve, reject) {
                        $.ajax({
                            url: "/master/ba/" + id + "/maternity",
                            type: 'POST',
                            dataType: "JSON",
                            data: {
                                "id": id
                            },
                            success: function () {
                                swal({
                                    title: 'Success!',
                                    text: "Berhasil Melakukan Persetujuan Cuti Hamil",
                                    type: 'success',
                                }).then(function () {
                                    window.history.back();
                                })

                            }
                        });
                        resolve()
                    });
                }
            })
        }
    </script>
@endsection
