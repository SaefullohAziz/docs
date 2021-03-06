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
				@if(auth()->guard('admin')->user()->can('create schools'))
					<a href="{{ route('admin.school.create') }}" class="btn btn-icon btn-success" title="{{ __('Create') }}"><i class="fa fa-plus"></i></a>
				@endif
				<button class="btn btn-icon btn-secondary" title="{{ __('Filter') }}" data-toggle="modal" data-target="#filterModal"><i class="fa fa-filter"></i></button>
            	<button class="btn btn-icon btn-secondary" onclick="reloadTable()" title="{{ __('Refresh') }}"><i class="fa fa-sync"></i></i></button>
				@if(auth()->guard('admin')->user()->can('bin schools'))
					<a href="{{ route('admin.school.bin') }}" class="btn btn-icon btn-danger" title="{{ __('Bin') }}"><i class="fas fa-trash"></i></a>
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
								<th>{{ __('Type') }}</th>
								<th>{{ __('Level') }}</th>
								<th>{{ __('Name') }}</th>
								<th>{{ __('Province') }}</th>
								<th>{{ __('Regency') }}</th>
								<th>{{ __('Headmaster') }}</th>
								<th>{{ __('PIC') }}</th>
								<th>{{ __('Status') }}</th>
								<th>{{ __('Code') }}</th>
								<th>{{ __('Action') }}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
			<div class="card-footer bg-whitesmoke">
				@if (auth()->guard('admin')->user()->can('delete schools'))
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
				"url": "{{ route('admin.school.list') }}",
				"type": "POST",
				"data": function (d) {
		          d._token = "{{ csrf_token() }}";
		          d.provinces = $('select[name="provinces[]"]').val();
		          d.regencies = $('select[name="regencies[]"]').val();
		          d.levels = $('select[name="levels[]"]').val();
		          d.statuses = $('select[name="statuses[]"]').val();
		        }
			},
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', 'searchable': false },
				{ data: 'created_at', name: 'created_at' },
				{ data: 'type', name: 'schools.type' },
				{ data: 'level_name', name: 'school_levels.name' },
				{ data: 'name', name: 'schools.name' },
				{ data: 'province', name: 'schools.province' },
				{ data: 'regency', name: 'schools.regency' },
				{ data: 'headmaster_name', name: 'schools.headmaster_name' },
				{ data: 'pic_name', name: 'pics.name' },
				{ data: 'status_name', name: 'school_statuses.name' },
				{ data: 'code', name: 'schools.code' },
				{ data: 'action', name: 'action' }
			],
			"order": [[ 1, 'desc' ]],
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

  		$('select[name="provinces[]"]').change(function(event) {
			event.preventDefault();
			var provinces = $('select[name="provinces[]"] option:selected').map(function(){
				return $(this).val();
			}).get();
			$('select[name="regencies[]"]').html('');
			if ($(this).val() != '') {
				$.ajax({
					url : "{{ route('get.regency') }}",
					type: "POST",
					dataType: "JSON",
					data: {'_token' : '{{ csrf_token() }}', 'provinces' : provinces},
					success: function(data)
					{
						$.each(data.result, function(key, value) {
							$('select[name="regencies[]"]').append('<option value="'+value+'">'+value+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="regencies[]"]').html('');
					}
				});
			}
		});

		$('select[name="levels[]"]').change(function(event) {
			event.preventDefault();
			var levels = $('select[name="levels[]"] option:selected').map(function(){
				return $(this).val();
			}).get();
			$('select[name="statuses[]"]').html('');
			if ($(this).val() != '') {
				$.ajax({
					url : "{{ route('get.schoolStatus') }}",
					type: "POST",
					dataType: "JSON",
					data: {'_token' : '{{ csrf_token() }}', 'levels' : levels},
					success: function(data)
					{
						$.each(data.result, function(key, value) {
							$('select[name="statuses[]"]').append('<option value="'+value+'">'+value+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="statuses[]"]').html('');
					}
				});
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
							url : "{{ route('admin.school.destroy') }}",
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
			{{ Form::open(['route' => 'admin.school.export', 'files' => true]) }}
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							{{ Form::bsSelect('col-sm-4', null, 'provinces[]', $provinces, null, __('All Province'), ['multiple' => '']) }}
							{{ Form::bsSelect('col-sm-4', null, 'regencies[]', [], null, __('All Regency'), ['multiple' => '']) }}
							{{ Form::bsSelect('col-sm-4', null, 'levels[]', $levels, null, __('All Level'), ['multiple' => '']) }}
							{{ Form::bsSelect('col-sm-4', null, 'statuses[]', [], null, __('All Status'), ['multiple' => '']) }}
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
@endsection