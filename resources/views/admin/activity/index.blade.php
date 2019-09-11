@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-12">

		@if (session('alert-success'))
			<div class="alert alert-success alert-dismissible show fade">
				<div class="alert-body">
					<button class="close" data-dismiss="alert">
						<span>&times;</span>
					</button>
					{{ session('alert-success') }}
				</div>
			</div>
		@endif

		@if (session('alert-danger'))
			<div class="alert alert-danger alert-dismissible show fade">
				<div class="alert-body">
					<button class="close" data-dismiss="alert">
						<span>&times;</span>
					</button>
					{{ session('alert-danger') }}
				</div>
			</div>
		@endif

		<div class="card card-primary">
			<div class="card-header">
				@if (auth()->guard('admin')->user()->can('create activities'))
					<a href="{{ route('admin.activity.create') }}" class="btn btn-icon btn-success" title="{{ __('Create') }}"><i class="fa fa-plus"></i></a>
				@endif
				<button class="btn btn-icon btn-secondary" title="{{ __('Filter') }}" data-toggle="modal" data-target="#filterModal"><i class="fa fa-filter"></i></button>
            	<button class="btn btn-icon btn-secondary" onclick="reloadTable()" title="{{ __('Refresh') }}"><i class="fa fa-sync"></i></i></button>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-sm table-striped" id="table4data">
						<thead>
							<tr>
								<th>
									<div class="checkbox icheck"><label><input type="checkbox" name="selectData"></label></div>
								</th>
								<th>{{ __('Created At') }}</th>
								<th>{{ __('School') }}</th>
								<th>{{ __('Type') }}</th>
								<th>{{ __('Submission Letter') }}</th>
								<th>{{ __('PIC') }}</th>
								<th>{{ __('Date') }}</th>
								<th>{{ __('Until') }}</th>
								<th>{{ __('Status') }}</th>
								<th>{{ __('Action') }}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer bg-whitesmoke">
				@if (auth()->guard('admin')->user()->can('approval activities'))
					<!-- <button class="btn btn-light btn-sm" name="cancelData" title="{{ __('Cancel') }}">{{ __('Cancel') }}</button> -->
					<!-- <button class="btn btn-light btn-sm" name="rejectData" title="{{ __('Reject') }}">{{ __('Reject') }}</button> -->
					<button class="btn btn-light btn-sm" name="approveData" title="{{ __('Approve') }}">{{ __('Approve') }}</button>
				@endif
				@if (auth()->guard('admin')->user()->can('delete activities'))
					<button class="btn btn-danger btn-sm" name="deleteData" title="{{ __('Delete') }}">{{ __('Delete') }}</button>
				@endif
			</div>
			{{ Form::close() }}
		</div>

	</div>
</div>
@endsection

@section('script')
<script>
	var table;
	$(document).ready(function() {
		table = $('#table4data').DataTable({
			processing: true,
			serverSide: true,
			"ajax": {
				"url": "{{ route('admin.activity.list') }}",
				"type": "POST",
				"data": function (d) {
		          d._token = "{{ csrf_token() }}";
		          d.type = $('select[name="type"]').val();
		          d.status = $('select[name="status"]').val();
		          d.school = $('select[name="school"]').val();
		        }
			},
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', 'searchable': false },
				{ data: 'created_at', name: 'created_at' },
				{ data: 'school', name: 'schools.name' },
				{ data: 'type', name: 'type' },
                { data: 'submission_letter', name: 'submission_letter' },
                { data: 'pic_name', name: 'pics.name' },
				{ data: 'date', name: 'date' },
				{ data: 'until_date', name: 'until_date' },
				{ data: 'status', name: 'statuses.name' },
				{ data: 'action', name: 'action', 'searchable': false },
			],
			"columnDefs": [
			{   
          		"targets": [ 0, -1 ], //last column
          		"orderable": false, //set not orderable
      		},
      		],
      		"drawCallback": function(settings) {
      			$('input[name="selectData"], input[name="selectedData[]"]').iCheck({
      				checkboxClass: 'icheckbox_flat-green',
      				radioClass: 'iradio_flat-green',
      				increaseArea: '20%' /* optional */
      			});
      			$('[name="selectData"]').on('ifChecked', function(event){
      				$('[name="selectedData[]"]').iCheck('check');
      			}).on('ifUnchecked', function(event){
      				$('[name="selectedData[]"]').iCheck('uncheck');
      			});
      		},
  		});
	});

	// Approve data action
	$('[name="approveData"]').click(function(event) {
    	if ($('[name="selectedData[]"]:checked').length > 0) {
    		event.preventDefault();
    		var selectedData = $('[name="selectedData[]"]:checked').map(function(){
    			return $(this).val();
    		}).get();
			swal({
		      	title: '{{ __("Are you sure you want to approve selected data?") }}',
		      	text: '',
		      	icon: 'warning',
			    buttons: true,
			  	dangerMode: true,
		    })
		    .then((willAprove) => {
		      	if (willAprove) {
			      	$.ajax({
						url : "{{ route('admin.activity.approve') }}",
						type: "POST",
						dataType: "JSON",
						data: {"_token" : "{{ csrf_token() }}", "selectedData" : selectedData},
						success: function(data)
						{
							reloadTable();
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							if (JSON.parse(jqXHR.responseText).status) {
								swal("{{ __('Failed!') }}", '{{ __("Data cannot be updated.") }}', "warning");
							} else {
								swal(JSON.parse(jqXHR.responseText).message, "", "error");
							}
						}
					});
		      	}
			});
    	} else {
    		swal("{{ __('Please select a data..') }}", "", "warning");
    	}
    });

	// $('[name="cancelData"]').click(function(event) {
 //      	if ($('[name="selectedData[]"]:checked').length > 0) {
	//         $('#cancel-form [name="description"]').val('');
	//         $("#cancel-form input").keypress(function (e) {
	//           	if(e.which == 13)  // the enter key code
	//           	{
	//             	$('[name="saveCancel"]').click();
	//             	return false;  
	//           	}
	//         });
	// 		$('#cancelModal').modal('show');
 //      	} else {
 //        	swal("{{ __('Please select a data..') }}", "", "warning");
 //      	}
 //    });

 //    $('[name="saveCancel"]').click(function(event) {
 //        event.preventDefault();
 //        var selectedData = $('[name="selectedData[]"]:checked').map(function(){
 //          	return $(this).val();
 //        }).get();
 //        $.ajax({
 //        	url : "{{ route('admin.subsidy.cancel') }}",
 //        	type: "POST",
 //        	dataType: "JSON",
 //        	data: {"_token" : "{{ csrf_token() }}", "selectedData" : selectedData, "description" : $('#cancel-form [name="description"]').val()},
 //        	success: function(data)
 //        	{
 //        		$('#cancelModal').modal('hide');
 //        		reloadTable();
 //        	},
 //        	error: function (jqXHR, textStatus, errorThrown)
 //        	{
 //        		reloadTable();
 //        	}
 //        });
 //    });

	// $('[name="rejectData"]').click(function(event) {
 //    	if ($('[name="selectedData[]"]:checked').length > 0) {
 //    		event.preventDefault();
 //    		var selectedData = $('[name="selectedData[]"]:checked').map(function(){
 //    			return $(this).val();
 //    		}).get();
	// 		swal({
	// 	      	title: '{{ __("Are you sure you want to reject selected data?") }}',
	// 	      	text: '',
	// 	      	icon: 'warning',
	// 	      	buttons: true,
	// 	      	dangerMode: true,
	// 	    })
	// 	    .then((willReject) => {
	// 	      	if (willReject) {
	// 	      		$.ajax({
	// 					url : "{{ route('admin.subsidy.reject') }}",
	// 					type: "POST",
	// 					dataType: "JSON",
	// 					data: {"_token" : "{{ csrf_token() }}", "selectedData" : selectedData},
	// 					success: function(data)
	// 					{
	// 						reloadTable();
	// 					},
	// 					error: function (jqXHR, textStatus, errorThrown)
	// 					{
	// 						if (JSON.parse(jqXHR.responseText).status) {
	// 							swal("{{ __('Failed!') }}", '{{ __("Data cannot be updated.") }}', "warning");
	// 						} else {
	// 							swal(JSON.parse(jqXHR.responseText).message, "", "error");
	// 						}
	// 					}
	// 				});
	// 	      	}
	// 		});
 //    	} else {
 //    		swal("{{ __('Please select a data..') }}", "", "warning");
 //    	}
 //    });
	$('[name="deleteData"]').click(function(event) {
		if ($('[name="selectedData[]"]:checked').length > 0) {
			event.preventDefault();
			var selectedData = $('[name="selectedData[]"]:checked').map(function(){
				return $(this).val();
			}).get();
			swal({
		      	title: '{{ __("Are you sure want to delete this data?") }}',
		      	text: '',
		      	icon: 'warning',
		      	buttons: true,
		      	dangerMode: true,
		    })
		    .then((willDelete) => {
		      	if (willDelete) {
		      		$.ajax({
						url : "{{ route('admin.activity.destroy') }}",
						type: "DELETE",
						dataType: "JSON",
						data: {"_token" : "{{ csrf_token() }}", "selectedData" : selectedData},
						success: function(data)
						{
							reloadTable();
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							if (JSON.parse(jqXHR.responseText).status) {
								swal("{{ __('Failed!') }}", '{{ __("Data cannot be deleted.") }}', "warning");
							} else {
								swal(JSON.parse(jqXHR.responseText).message, "", "error");
							}
						}
					});
		      	}
			});
		} else {
			swal("{{ __('Please select a data..') }}", "", "warning");
		}
	});


	function reloadTable() {
	    table.ajax.reload(null,false); //reload datatable ajax
	    $('[name="selectData"]').iCheck('uncheck');
	}

	function filter() {
		reloadTable();
		$('#filterModal').modal('hide');
	}
</script>

<!-- Modal -->
<div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="filterModallLabel">{{ __('Filter') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{{ Form::open(['url' => '#', 'files' => true]) }}
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							{{ Form::bsSelect('col-sm-4', __('School'), 'school', $schools, null, __('Select'), ['placeholder' => __('Select')]) }}
							{{ Form::bsSelect('col-sm-4', __('Type'), 'type', $types, null, __('Select'), ['placeholder' => __('Select')]) }}
							{{ Form::bsSelect('col-sm-4', __('Status'), 'status', $statuses, null, __('Select'), ['placeholder' => __('Select')]) }}
						</div>
					</div>
				</div>
				<div class="modal-footer bg-whitesmoke d-flex justify-content-center">
					{{ Form::button(__('Filter'), ['class' => 'btn btn-primary', 'onclick' => 'filter()']) }}
					{{ Form::button(__('Cancel'), ['class' => 'btn btn-secondary', ' data-dismiss' => 'modal']) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@endsection