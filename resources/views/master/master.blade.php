@extends('layouts.app')

@section('content')

<div class="page-content">
	<!-- BEGIN PAGE HEAD-->
	<div class="page-head">
		<!-- BEGIN PAGE TITLE -->
		<div class="page-title">
			<h1>Region</h1>
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
			<span class="active">Region</span>
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
						<span class="caption-subject font-red sbold uppercase">Region</span>
					</div>
				</div>
				<div class="portlet-body">
					<div class="table-toolbar">
						<div class="row">
							<div class="col-md-6">
								<div class="btn-group">
									<a class="btn green" data-toggle="modal" href="#basic"> Add New <i class="fa fa-plus"></i></a>

								</div>
							</div>
						</div>
					</div>
					<table class="table table-striped table-hover table-bordered" id="sample_editable_1">
						<thead>
							<tr>
								<th> Id Acount# </th>
								<th> Acount Name </th>
								<th> Edit </th>
								<th> Delete </th>
							</tr>
						</thead>
						<tbody>
							
							</tbody>
						</table>
					</div>
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
			</div>
		</div>
		<!-- END PAGE BASE CONTENT -->
	</div>

	<!-- modal dialog -->
	<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form"  action="{{ url('addacount') }}" method="POST">

					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Add Acount</h4>
					</div>
					<div class="modal-body"> 
						{!! csrf_field() !!}
						<div class="form-body">
							<div class="form-group form-md-line-input form-md-floating-label">
								<input type="text" class="form-control" id="form_control_1" name="name">
								<label for="form_control_1">Acount Name</label>
								<!-- <span class="help-block">Some help goes here...</span> -->
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

	<!-- edit data -->
	<div class="modal fade" id="edit_data" tabindex="-1" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" id="frmTasks" method="POST">
					<input name="_method" type="hidden" value="PUT">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Edit Acount</h4>
					</div>
					<div class="modal-body"> 
						{!! csrf_field() !!}
						<div class="form-body">
							<div class="form-group form-md-line-input form-md-floating-label">
								<input type="text" class="form-control" id="name_acount" name="name">
								<label for="form_control_1">Acount Name</label>
								<input type="hidden" id="id_acount" name="id" value="0">
								<!-- <span class="help-block">Some help goes here...</span> -->
							</div>
						</div>


					</div>
					<div class="modal-footer">
						<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
						<button type="submit" class="btn green" id="save-edit">Save</button>
					</div>
				</form>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	@endsection

	@section('additional-script')
	<script>
		$(document).ready(function(){

			var url = "{{url('acount')}}";

			// sweet alert
			$('.swit_alert').click(function(){
				swal({
					title: "Are you sure?",
					text: "You will not be able to recover this imaginary file!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-danger",
					confirmButtonText: "Yes, delete it!",
					cancelButtonText: "No, cancel plx!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm) {
					if (isConfirm) {
						$.ajaxSetup({
    		headers: {
    			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		}
    	})
    	var id = $('.swit_alert').val();
    	// url: url + '/' + id;
    	// console.log(url);
    	
    	$.ajax({

    		type: "DELETE",
    		url: url + '/' + id,
    		success: function (data) {
    			console.log(data);

    			$("#acount" + id).remove();
    		},
    		error: function (data) {
    			console.log('Error:', data);
    		}
    	});
						swal("Deleted!", "Your imaginary file has been deleted.", "success");
					} else {
						swal("Cancelled", "Your imaginary file is safe :)", "error");
					}
				});
			});

    //display modal form for task editing
    $('.open-modal').click(function(){

    	var id = $(this).val();
    	console.log(id);
    	$.get(url + '/' + id, function (data) {
            //success data
            console.log(data);
            $('#id_acount').val(data.id);
            $('#name_acount').val(data.name);
            $('#name_acount').addClass('edited');
            $('#edit_data').modal('show');
        }) 
    });

    //save edit data
    $("#save-edit").click(function (e) {
    	$.ajaxSetup({
    		headers: {
    			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		}
    	})

    	e.preventDefault(); 

    	var formData = {
    		name: $('#name_acount').val()
    	}

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#save-edit').val();

        var type = "PUT"; //for creating new resource
        var id = $('#id_acount').val();
        var my_url = url+'/'+id;
        // console.log(formData);


        $.ajax({

        	type: type,
        	url: my_url,
        	data: formData,
        	dataType: 'json',
        	success: function (data) {
        		console.log(data);
        		var acount = '<tr id="acount' + data.id + '"><td>' + data.id + '</td><td>' + data.name + '</td>';
        		acount += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data.id + '">Edit<i class="fa fa-pencil"></i></button></td>';
        		acount += '<td><button class="btn btn-danger btn-xs btn-delete swit_alert" value="' + data.id + '">Delete<i class="fa fa-trash-o"></i></button></td></tr>';


        		$("#acount" + id).replaceWith( acount );


        		$('#frmTasks').trigger("reset");

        		$('#edit_data').modal('hide')
        	},
        	error: function (data) {
        		console.log('Error:', data);
        	}
        });

    });

    $('.delete-task').click(function(){
    	$.ajaxSetup({
    		headers: {
    			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    		}
    	})
    	var id = $(this).val();
    	// url: url + '/' + id;
    	// console.log(url);
    	
    	$.ajax({

    		type: "DELETE",
    		url: url + '/' + id,
    		success: function (data) {
    			console.log(data);

    			$("#acount" + id).remove();
    		},
    		error: function (data) {
    			console.log('Error:', data);
    		}
    	});
    });

});
</script>
@endsection