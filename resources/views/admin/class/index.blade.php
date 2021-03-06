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
				@if(auth()->guard('admin')->user()->can('create student_classes'))
					<a href="{{ route('admin.class.create') }}" class="btn btn-icon btn-success" title="{{ __('Create') }}"><i class="fa fa-plus"></i></a>
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
								<th>{{ __('Generation') }}</th>
								<th>{{ __('School Year') }}</th>
								<th>{{ __('Department') }}</th>
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
				@if (auth()->guard('admin')->user()->can('open student_classes'))
					<button class="btn btn-default btn-sm" name="openData" title="{{ __('Open Class') }}">{{ __('Open') }}</button>
				@endif
				@if (auth()->guard('admin')->user()->can('close student_classes'))
					<button class="btn btn-default btn-sm" name="closeData" title="{{ __('Close Class') }}">{{ __('Close') }}</button>
				@endif
				@if (auth()->guard('admin')->user()->can('delete student_classes'))
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
				"url": "{{ route('admin.class.list') }}",
				"type": "POST",
				"data": function (d) {
		          d._token = "{{ csrf_token() }}";
				  d.school = $('select[name="school"]').val();
				  d.generation = $('select[name="generation"]').val();
				  d.schoolYear = $('select[name="school_year"]').val();
				  d.department = $('select[name="department"]').val();
		        }
			},
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', 'searchable': false },
				{ data: 'created_at', name: 'created_at' },
				{ data: 'school', name: 'schools.name' },
				{ data: 'generation', name: 'student_classes.generation' },
				{ data: 'school_year', name: 'student_classes.school_year' },
				{ data: 'department', name: 'departments.name' },
				{ data: 'status', name: 'student_classes.closed_at' },
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

		$('select[name="level"]').change(function() {
			if ($(this).val() != '') {
				$('select[name="school"]').html('<option value="">Select</option>');
				$.ajax({
					url : "{{ route('get.school') }}",
					type: "POST",
					dataType: "JSON",
					cache: false,
					data: {'_token' : '{{ csrf_token() }}', 'level' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(key, value) {
							$('select[name="school"]').append('<option value="'+key+'">'+value+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="school"]').html('<option value="">Select</option>');
					}
				});
			}
		});

		$('select[name="school"]').change(function() {
			if ($(this).val() != '') {
				$('select[name="generation"], select[name="school_year"], select[name="department"]').html('<option value="">Select</option>');
				$.ajax({
					url : "{{ route('get.generation') }}",
					type: "POST",
					dataType: "JSON",
					cache: false,
					data: {'_token' : '{{ csrf_token() }}', 'school' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(key, value) {
							$('select[name="generation"]').append('<option value="'+key+'">'+value+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="generation"]').html('<option value="">Select</option>');
					}
				});
				$.ajax({
					url : "{{ route('get.schoolYear') }}",
					type: "POST",
					dataType: "JSON",
					cache: false,
					data: {'_token' : '{{ csrf_token() }}', 'school' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(key, value) {
							$('select[name="school_year"]').append('<option value="'+key+'">'+value+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="school_year"]').html('<option value="">Select</option>');
					}
				});
				$.ajax({
					url : "{{ route('get.department') }}",
					type: "POST",
					dataType: "JSON",
					cache: false,
					data: {'_token' : '{{ csrf_token() }}', 'school' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(key, value) {
							$('select[name="department"]').append('<option value="'+key+'">'+value+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="department"]').html('<option value="">Select</option>');
					}
				});
			}
		});

		$('[name="openData"]').click(function(event) {
			if ($('[name="selectedData[]"]:checked').length > 0) {
				event.preventDefault();
				var selectedData = $('[name="selectedData[]"]:checked').map(function(){
					return $(this).val();
				}).get();
				swal({
					title: '{{ __("Are you sure want to open this class?") }}',
					text: '',
					icon: 'warning',
					buttons: ['{{ __("Cancel") }}', true],
					dangerMode: true,
				})
				.then((willDo) => {
					if (willDo) {
						$.ajax({
							url : "{{ route('admin.class.open') }}",
							type: "POST",
							dataType: "JSON",
							data: {"selectedData" : selectedData, "_token" : "{{ csrf_token() }}"},
							success: function(data)
							{
								swal("{{ __('Success!') }}", "{{ __("Class has been opened.") }}", "success");
								reloadTable();
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								if (JSON.parse(jqXHR.responseText).status) {
									swal("{{ __('Failed!') }}", "{{ __("Data cannot been opened.") }}", "warning");
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

		$('[name="closeData"]').click(function(event) {
			if ($('[name="selectedData[]"]:checked').length > 0) {
				event.preventDefault();
				var selectedData = $('[name="selectedData[]"]:checked').map(function(){
					return $(this).val();
				}).get();
				swal({
			      	title: '{{ __("Are you sure want to close this class?") }}',
			      	text: '',
			      	icon: 'warning',
			      	buttons: ['{{ __("Cancel") }}', true],
			      	dangerMode: true,
			    })
			    .then((willDo) => {
			      	if (willDo) {
			      		$.ajax({
							url : "{{ route('admin.class.close') }}",
							type: "POST",
							dataType: "JSON",
							data: {"selectedData" : selectedData, "_token" : "{{ csrf_token() }}"},
							success: function(data)
							{
								swal("{{ __('Success!') }}", "{{ __("Data has be closed.") }}", "success");
								reloadTable();
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								if (JSON.parse(jqXHR.responseText).status) {
									swal("{{ __('Failed!') }}", "{{ __("Data cannot be closed.") }}", "warning");
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
							url : "{{ route('admin.class.destroy') }}",
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
									swal("{{ __('Failed!') }}", "{{ __("Data cannot be deleted.") }}", "warning");
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
			{{ Form::open(['route' => 'admin.class.export', 'files' => true]) }}
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							{{ Form::bsSelect('col-sm-4', __('Level'), 'level', $levels, null, __('Select'), ['placeholder' => __('Select')]) }}
							{{ Form::bsSelect('col-sm-4', __('School'), 'school', $schools, null, __('Select'), ['placeholder' => __('Select')]) }}
							{{ Form::bsSelect('col-sm-4', __('Generation'), 'generation', $generations, null, __('Select'), ['placeholder' => __('Select')]) }}
							{{ Form::bsSelect('col-sm-4', __('School Year'), 'school_year', $schoolYears, null, __('Select'), ['placeholder' => __('Select')]) }}
							{{ Form::bsSelect('col-sm-4', __('Department'), 'department', $departments, null, __('Select'), ['placeholder' => __('Select')]) }}
							{{ Form::bsSelect('col-sm-4', __('SSP Status'), 'ssp_status', ['1' => __('Yes'), '0' => __('Not')], null, __('Select'), ['placeholder' => __('Select')], [__('This is only used for export.')]) }}
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