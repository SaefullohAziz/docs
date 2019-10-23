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

		@if (session('import_file'))
			@foreach (session('import_file')['message'] as $message)
				<div class="alert alert-danger alert-dismissible show fade">
				<div class="alert-body">
					<button class="close" data-dismiss="alert">
						<span>&times;</span>
					</button>
					@php echo $message @endphp
				</div>
			</div>
			@endforeach
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
				<a href="{{ route('class.student.create', $studentClass->id) }}" class="btn btn-icon btn-success" title="{{ __('Create') }}"><i class="fa fa-plus"></i></a>
        		<button class="btn btn-icon btn-primary" title="{{ __('Import') }}" data-toggle="modal" data-target="#importModal">{{ __('Import') }}</button>
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
								<th>{{ __('Name') }}</th>
								<th>{{ __('School') }}</th>
								<th>{{ __('NISN') }}</th>
								<th>{{ __('Department') }}</th>
								<th>{{ __('E-Mail') }}</th>
								<th>{{ __('Generation') }}</th>
								<th>{{ __('School Year') }}</th>
								<th>{{ __('Phone Number') }}</th>
								<th>{{ __('Action') }}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
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
				"url": "{{ route('class.student.list', $studentClass->id) }}",
				"type": "POST",
				"data": function (d) {
		          d._token = "{{ csrf_token() }}";
				  d.generation = $('select[name="generation"]').val();
				  d.schoolYear = $('select[name="school_year"]').val();
				  d.department = $('select[name="department"]').val();
				  d.sspStatus = $('select[name="ssp_status"]').val();
		        }
			},
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', 'searchable': false },
				{ data: 'created_at', name: 'created_at' },
				{ data: 'name', name: 'students.name' },
				{ data: 'school', name: 'schools.name' },
				{ data: 'nisn', name: 'students.nisn' },
				{ data: 'department', name: 'departments.name' },
				{ data: 'email', name: 'students.email' },
				{ data: 'generation', name: 'student_classes.generation' },
				{ data: 'school_year', name: 'student_classes.school_year' },
				{ data: 'phone_number', name: 'students.phone_number' },
				{ data: 'action', name: 'action' }
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
	<div class="modal-dialog modal-sm" role="document">
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
							{{ Form::bsSelect('col-12', __('SSP Status'), 'ssp_status', ['1' => __('Yes'), '0' => __('Not')], null, __('Select'), ['placeholder' => __('Select')]) }}
						</div>
					</div>
				</div>
				<div class="modal-footer bg-whitesmoke d-flex justify-content-center">
					<!-- {{ Form::submit(__('Export'), ['class' => 'btn btn-primary']) }} -->
					{{ Form::button(__('Filter'), ['class' => 'btn btn-primary', 'onclick' => 'filter()']) }}
					{{ Form::button(__('Cancel'), ['class' => 'btn btn-secondary', ' data-dismiss' => 'modal']) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="importModallLabel">{{ __('Import') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{{ Form::open(['route' => ['class.student.import', $studentClass->id], 'files' => true]) }}
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							{{ Form::bsUploadedFile('d-block', __('Download Template'), 'template', 'file', 'student_import_template.xlsx') }}

							{{ Form::bsFile(null, __('Import File'), 'import_file', old('import_file'), [], [__('Import file with xlx/xlsx format up to 5MB.')]) }}
						</div>
					</div>
				</div>
				<div class="modal-footer bg-whitesmoke d-flex justify-content-center">
					{{ Form::submit(__('Import'), ['class' => 'btn btn-primary']) }}
					{{ Form::button(__('Cancel'), ['class' => 'btn btn-secondary', ' data-dismiss' => 'modal']) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@endsection