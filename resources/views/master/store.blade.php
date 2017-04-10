@extends('layouts.app')

@section('additional-css')
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css"/>
@stop

@section('content')
	<div class="page-content" id="app">
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
				<span class="active">Store</span>
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
							<span class="caption-subject font-red sbold uppercase">Store</span>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
							<div class="row">
								<div class="col-md-6">
									<a href="{{ route('createStore')}}" class="btn green" data-toggle="modal" id="btn-add"> Tambah <i class="fa fa-plus"></i></a>
                                    <a href="{{ route('masterStore') . (Request::exists('archived') ? '?alldata' : '?archived') }}"
                                       class="btn green-meadow"> {{(Request::exists('archived') ? 'All Data' : 'Archived') }}
                                        <i class="fa fa-archive"></i></a>

                                </div>
                            </div>
                        </div>
                        <table class="table table-striped table-hover table-bordered" id="stores_table">
                            <thead>
                            <tr>
                                <th> # </th>
                                <th> STORE NO</th>
                                <th> CUST ID</th>
                                <th> STORE NAME 1</th>
                                <th> STORE NAME 2</th>
                                <th> CHANNEL</th>
                                <th> ACCOUNT</th>
                                <th> CITY</th>
                                <th> REGION</th>
                                <th> SS</th>
                                <!-- <th> BA Allocation nyx </th> -->
                                <th> BA ALLOCATION OAP</th>
                                <th> BA ALLOCATION MYB</th>
                                <th> BA ALLOCATION GAR</th>
                                <th> BA ALLOCATION CONS</th>
                                <th> CREATED BY</th>
                                <th> CREATED AT</th>
                                <th> UPDATED BY</th>
                                <th> UPDATED AT</th>
                                @if(Request::exists('archived'))
                                <th> DELETED BY</th>
                                <th> DELETED AT</th>
                                @endif
                                <th> HOLD</th>
                                <th> EDIT</th>
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
	<!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
	<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<img src="/assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
					<span> &nbsp;&nbsp;Loading... </span>
				</div>
			</div>
		</div>
	</div>
	<!-- /.modal -->
	<!-- modal dialog -->
	<div class="modal fade" id="basic"  ole="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form"  id="frmTasks" name="frmTasks" onsubmit="return validateForm()" nonvalidate="">

                    <div class="modal-header bg-green-soft bg-font-green-soft">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Add Store</h4>
                    </div>
                    <div class="modal-body">
                        {!! csrf_field() !!}
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                            <div class="alert alert-success display-hide">
                                <button class="close" data-close="alert"></button>
                                Your form validation is successful!
                            </div>
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Store No" id="store_no"
                                           name="store_no" required>
                                    <span class="help-block">Input Store No</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Customer ID " id="customer_id"
                                           name="customer_id" required>
                                    <span class="help-block">Input Customer Id</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input has-success">
                                        <div class="input-icon">
                                            <input type="text" class="form-control" placeholder="Store Name 1 "
                                                   id="store_name_1" name="store_name_1" required>
                                            <span class="help-block">Input Store Name 1</span>
                                            <i class="fa fa-bell-o"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input has-success">
                                        <div class="input-icon">
                                            <input type="text" class="form-control" placeholder="Store Name 2 "
                                                   id="store_name_2" name="store_name_2" required>
                                            <span class="help-block">Input Store Name 2</span>
                                            <i class="fa fa-bell-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Channel " id="channel"
                                           name="channel" required>
                                    <span class="help-block">Input Channel</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label has-success">
                                <select class="form-control edited" id="account" name="account" required>
                                    <option value="">select account</option>
                                    @foreach($account as $val)
                                        <option value="{{$val->id}}" class="acount">{{$val->name}}</option>
                                    @endforeach
                                </select>
                                <!-- <label for="form_control_1">Account</label> -->
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label has-success">
                                <select class="form-control edited" id="kota" name="kota" required>
                                    <option value="">select kota</option>
                                    @foreach($city as $val)
                                        <option value="{{$val->id}}" class="kota">{{$val->city_name}}</option>
                                    @endforeach
                                </select>
                                <!-- <label for="form_control_1">Kota</label> -->
                            </div>
                            <div class="form-group form-md-line-input form-md-floating-label has-success">
                                <select class="form-control edited" id="region" name="region" required>
                                    <option value="">select region</option>
                                    @foreach($region as $val)
                                        <option value="{{$val->id}}" class="region">{{$val->name}}</option>
                                    @endforeach
                                </select>
                                <!-- <label for="form_control_1">Region</label> -->
                            </div>
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Supervisor " id="supervisor"
                                           name="supervisor" required>
                                    <span class="help-block">Input Supervisor</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Alokasi BA nyx "
                                           id="alokasi_ba_nyx" name="alokasi_ba_nyx" required>
                                    <span class="help-block">Input Alokasi BA nyx</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Alokasi BA oap "
                                           id="alokasi_ba_oap" name="alokasi_ba_oap" required>
                                    <span class="help-block">Input Alokasi BA oap</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Alokasi BA myb "
                                           id="alokasi_ba_myb" name="alokasi_ba_myb" required>
                                    <span class="help-block">Input Alokasi BA myb</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Alokasi BA gar "
                                           id="alokasi_ba_gar" name="alokasi_ba_gar" required>
                                    <span class="help-block">Input Alokasi BA gar</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                            <div class="form-group form-md-line-input has-success">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Alokasi BA cons "
                                           id="alokasi_ba_cons" name="alokasi_ba_cons" required>
                                    <span class="help-block">Input Alokasi BA cons</span>
                                    <i class="fa fa-bell-o"></i>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn green" id="btn-save">Save</button>
                        <input type="hidden" id="id_store" name="id_store" value="0">
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

    <script>
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
                            url: "store/" + id + "/delete",
                            type: 'POST',
                            dataType: "JSON",
                            data: {
                                "id": id,
                                "_method": 'DELETE',
                            },
                            success: function () {
                                swal("Success!", "Store Archived!", "success")
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
                            url: "store/" + id + "/restore",
                            type: 'POST',
                            dataType: "JSON",
                            data: {
                                "id": id,
                            },
                            success: function () {
                                swal("Success!", "Store Restored!", "success")
                                $("." + id).remove();
                            }
                        });
            });
        }

        $('#stores_table').dataTable({
            "fnCreatedRow": function( nRow, data ) {
                $(nRow).attr('class', data.id);
            },

            "processing": true,
            "serverSide": true,
            "ajax": {
                url: '{{ url('master/store/datatable/') . (Request::exists('archived') ? '?archived' : '')}}',
                method: 'POST'
            },
            columns: [
                {data: 'id', name: 'id', class: 'namewrapper', defaultContent: ''},
                {data: 'store_no', name: 'store_no', class: 'namewrapper', defaultContent: ''},
                {data: 'customer_id', name: 'customer_id', class: 'namewrapper', defaultContent: ''},
                {data: 'store_name_1', name: 'store_name_1', class: 'namewrapper', defaultContent: ''},
                {data: 'store_name_2', name: 'store_name_2', class: 'namewrapper', defaultContent: ''},
                {data: 'channel', name: 'channel', class: 'namewrapper', defaultContent: ''},
                {data: 'account.name', name: 'account.name', class: 'namewrapper', defaultContent: ''},
                {data: 'city.city_name', name: 'city.city_name', class: 'namewrapper', defaultContent: ''},
                {data: 'region.name', name: 'region.name', class: 'namewrapper', defaultContent: ''},
                {data: 'reo.user.name', name: 'reo.user.name', orderable: false, searchable: false, defaultContent: ''},
                {data: 'alokasi_ba_oap', name: 'alokasi_ba_oap', class: 'namewrapper', defaultContent: ''},
                {data: 'alokasi_ba_myb', name: 'alokasi_ba_myb', class: 'namewrapper', defaultContent: ''},
                {data: 'alokasi_ba_gar', name: 'alokasi_ba_gar', class: 'namewrapper', defaultContent: ''},
                {data: 'alokasi_ba_cons', name: 'alokasi_ba_cons', class: 'namewrapper', defaultContent: ''},
                {data: 'created_by.name', name: 'created_by.name', class: 'namewrapper',defaultContent:'', searchable: false},
                {data: 'created_at', name: 'created_at', class: 'namewrapper',defaultContent:''},
                {data: 'updated_by.name', name: 'updated_by.name', class: 'namewrapper',defaultContent:'', searchable: false},
                {data: 'updated_at', name: 'updated_at', class: 'namewrapper',defaultContent:''},
                    @if(Request::exists('archived'))
                {data: 'deleted_by.name', name: 'deleted_by.name', class: 'namewrapper',defaultContent:''},
                {data: 'deleted_at', name: 'deleted_at', class: 'namewrapper',defaultContent:''},
                    @endif

                { data: 'hold', name: 'hold' , orderable: false, searchable: false},
                {data: 'edit', name: 'edit', orderable: false, searchable: false, class: 'namewrapper'},
            ]
        });

    </script>
@endsection