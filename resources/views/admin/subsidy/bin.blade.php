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
								<th>{{ __('Report') }}</th>
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
				@if (auth()->guard('admin')->user()->can('restore subsidies'))
					<button class="btn btn-warning btn-sm" name="restoreData" title="{{ __('Restore') }}">{{ __('Restore') }}</button>
				@endif
				@if (auth()->guard('admin')->user()->can('force_delete subsidies'))
					<button class="btn btn-danger btn-sm" name="deleteData" title="{{ __('Delete Permanently') }}">{{ __('Delete Permanently') }}</button>
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
				"url": "{{ route('admin.subsidy.binList') }}",
				"type": "POST",
				"data": function (d) {
		          d._token = "{{ csrf_token() }}";
		        }
			},
			columns: [
				{ data: 'DT_RowIndex', name: 'DT_RowIndex', 'searchable': false },
				{ data: 'created_at', name: 'created_at' },
                { data: 'school', name: 'schools.name' },
				{ data: 'type', name: 'subsidies.type' },
				{ data: 'submission_letter', name: 'subsidies.submission_letter' },
				{ data: 'report', name: 'subsidies.report' },
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

        $('[name="restoreData"]').click(function(event) {
            if ($('[name="selectedData[]"]:checked').length > 0) {
                event.preventDefault();
                var selectedData = $('[name="selectedData[]"]:checked').map(function(){
                    return $(this).val();
                }).get();
                swal({
                    title: '{{ __("Are you sure want to restore this data?") }}',
                    text: '',
                    icon: 'warning',
                    buttons: ['{{ __("Cancel") }}', true],
                    dangerMode: true,
                })
                .then((willRestore) => {
                    if (willRestore) {
                        $.ajax({
                            url : "{{ route('admin.subsidy.restore') }}",
                            type: "POST",
                            dataType: "JSON",
                            data: {"selectedData" : selectedData, "_token" : "{{ csrf_token() }}"},
                            success: function(data)
                            {
                                reloadTable();
                            },
                            error: function (jqXHR, textStatus, errorThrown)
                            {
                                if (JSON.parse(jqXHR.responseText).status) {
                                    swal("{{ __('Failed!') }}", '{{ __("Data cannot be restored.") }}', "warning");
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
                    title: '{{ __("Are you sure want to delete permanently this data?") }}',
                    text: '{{ __("After this, data cannot be restored.") }}',
                    icon: 'warning',
                    buttons: ['{{ __("Cancel") }}', true],
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url : "{{ route('admin.subsidy.destroyPermanently') }}",
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
</script>
@endsection