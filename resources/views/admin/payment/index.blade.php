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
				@if(auth()->guard('admin')->user()->can('create payments'))
					<a href="{{ route('admin.payment.create') }}" class="btn btn-icon btn-success" title="{{ __('Create') }}"><i class="fa fa-plus"></i></a>
				@endif
				<button class="btn btn-icon btn-secondary" title="{{ __('Filter') }}" data-toggle="modal" data-target="#filterModal"><i class="fa fa-filter"></i></button>
            	<button class="btn btn-icon btn-secondary" onclick="reloadTable()" title="{{ __('Refresh') }}"><i class="fa fa-sync"></i></i></button>
				@if(auth()->guard('admin')->user()->can('bin payments'))
					<a href="{{ route('admin.payment.bin') }}" class="btn btn-icon btn-danger" title="{{ __('Bin') }}"><i class="fas fa-trash"></i></a>
				@endif
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
								<th>{{ __('Payment Receipt') }}</th>
                                <th>{{ __('Bank Account Book') }}</th>
								<th>{{ __('NPWP') }}</th>
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
				@if (auth()->guard('admin')->user()->can('approval payments'))
					<button class="btn btn-light btn-sm" name="processData" title="{{ __('Process') }}">{{ __('Process') }}</button>
					<button class="btn btn-light btn-sm" name="approveData" title="{{ __('Approve') }}">{{ __('Approve') }}</button>
					<button class="btn btn-light btn-sm" name="sendData" title="{{ __('Send') }}">{{ __('Send') }}</button>
					<button class="btn btn-light btn-sm" name="refundData" title="{{ __('Refund') }}">{{ __('Refund') }}</button>
				@endif
				@if (auth()->guard('admin')->user()->can('delete payments'))
					<button class="btn btn-danger btn-sm" name="deleteData" title="{{ __('Delete') }}">{{ __('Delete') }}</button>
				@endif
			</div>
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
				"url": "{{ route('admin.payment.list') }}",
				"type": "POST",
				"data": function (d) {
		          d._token = "{{ csrf_token() }}";
		          d.school = $('select[name="school"]').val();
		          d.type = $('select[name="type"]').val();
		          d.status = $('select[name="status"]').val();
		        }
			},
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', 'searchable': false },
				{ data: 'created_at', name: 'created_at' },
                { data: 'school', name: 'schools.name' },
				{ data: 'type', name: 'payments.type' },
				{ data: 'payment_receipt', name: 'payments.payment_receipt' },
				{ data: 'bank_account_book', name: 'payments.bank_account_book' },
				{ data: 'npwp_number', name: 'payments.npwp_number' },
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
		  
		$('[name="processData"]').click(function(event) {
	    	if ($('[name="selectedData[]"]:checked').length > 0) {
	    		event.preventDefault();
	    		var selectedData = $('[name="selectedData[]"]:checked').map(function(){
	    			return $(this).val();
	    		}).get();
				swal({
			      	title: '{{ __("Are you sure you want to process selected data?") }}',
			      	text: '',
			      	icon: 'warning',
					buttons: ['{{ __("Cancel") }}', true],
			      	dangerMode: true,
			    })
			    .then((willReject) => {
			      	if (willReject) {
			      		$.ajax({
							url : "{{ route('admin.payment.process') }}",
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
			      	buttons: ['{{ __("Cancel") }}', true],
			      	dangerMode: true,
			    })
			    .then((willReject) => {
			      	if (willReject) {
			      		$.ajax({
							url : "{{ route('admin.payment.approve') }}",
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

		$('[name="sendData"]').click(function(event) {
	      	if ($('[name="selectedData[]"]:checked').length > 0) {
				$('#send-form [name="awb_number"]').val('');
				$('#send-form input[type="file"]').filestyle('clear');
				$('#send-form [name="koli"], #send-form [name="expedition"]').val(null).change();
				$('#sendModal').modal('show');
	      	} else {
	        	swal("{{ __('Please select a data..') }}", "", "warning");
	      	}
	    });

		$('[name="saveSend"]').click(function(event) {
			$('#send-form .invalid-feedback').remove();
	        event.preventDefault();
	        var selectedData = $('[name="selectedData[]"]:checked').map(function(){
	          	return $(this).val();
	        }).get();
			var formData = new FormData($('#send-form')[0]);
			for (var i = selectedData.length - 1; i >= 0; i--) {
				formData.append('selectedData[]', selectedData[i]);
			}
	        $.ajax({
	        	url : "{{ route('admin.payment.send') }}",
	        	type: "POST",
	        	dataType: "JSON",
				async: false,
				cache: false,
				contentType: false,
				processData: false,
				data: formData,
	        	success: function(data)
	        	{
	        		$('#sendModal').modal('hide');
	        		reloadTable();
	        	},
	        	error: function (jqXHR, textStatus, errorThrown)
	        	{
					$.each(JSON.parse(jqXHR.responseText).errors, function(name, value) {
              			$('#send-form [name="'+name+'"]').addClass('is-invalid');
              			$('[name="'+name+'"]').parent().append('<div class="invalid-feedback" role="alert"><strong>'+value[0]+'</strong></div>');
            		});
	        	}
	        });
	    });
		
		$('[name="refundData"]').click(function(event) {
	    	if ($('[name="selectedData[]"]:checked').length > 0) {
	    		event.preventDefault();
	    		var selectedData = $('[name="selectedData[]"]:checked').map(function(){
	    			return $(this).val();
	    		}).get();
				swal({
			      	title: '{{ __("Are you sure you want to refund selected payment?") }}',
			      	text: '',
			      	icon: 'warning',
			      	buttons: ['{{ __("Cancel") }}', true],
			      	dangerMode: true,
			    })
			    .then((willReject) => {
			      	if (willReject) {
			      		$.ajax({
							url : "{{ route('admin.payment.refund') }}",
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
					buttons: ['{{ __("Cancel") }}', true],
			      	dangerMode: true,
			    })
			    .then((willDelete) => {
			      	if (willDelete) {
			      		$.ajax({
							url : "{{ route('admin.payment.destroy') }}",
							type: "DELETE",
							dataType: "JSON",
							data: {"selectedData" : selectedData, "_token" : "{{ csrf_token() }}"},
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
			{{ Form::open(['route' => 'admin.payment.export', 'files' => true]) }}
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
					{{ Form::submit(__('Export'), ['class' => 'btn btn-primary']) }}
					{{ Form::button(__('Filter'), ['class' => 'btn btn-primary', 'onclick' => 'filter()']) }}
					{{ Form::button(__('Cancel'), ['class' => 'btn btn-secondary', ' data-dismiss' => 'modal']) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="sendModallLabel">{{ __('Send') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{{ Form::open(['url' => '#', 'files' => true, 'id' => 'send-form']) }}
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							{{ Form::bsSelect('col-sm-6', __('Koli'), 'koli', $kolis, null, __('Select'), ['placeholder' => __('Select')]) }}

							{{ Form::bsText('col-sm-6', __('Receipt/AWB Number'), 'awb_number', null, __('Receipt/AWB Number'), ['required' => '']) }}

							{{ Form::bsSelect('col-sm-6', __('Expedition'), 'expedition', $expeditions, null, __('Select'), ['placeholder' => __('Select')]) }}

							{{ Form::bsFile('col-sm-6', __('Proof of Receipt'), 'proof_of_receipt', null, [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
						</div>
					</div>
				</div>
				<div class="modal-footer bg-whitesmoke d-flex justify-content-center">
					{{ Form::button(__('Save'), ['class' => 'btn btn-primary', 'name' => 'saveSend']) }}
					{{ Form::button(__('Cancel'), ['class' => 'btn btn-secondary', ' data-dismiss' => 'modal']) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@endsection