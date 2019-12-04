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

		@if ($subsidyPayments->count()+$trainingPayments->count() > 0)
			<div class="card card-warning">
				<div class="card-header">
					{{ __('Bill') }}
				</div>
				<ul class="list-group list-group-flush">
					@foreach ($subsidyPayments as $subsidyPayment)
						<li class="list-group-item d-flex justify-content-between align-items-center">
							{{ __('Subsidy') }} {{ $subsidyPayment->subsidy[0]->type }} &middot; {{ __($subsidyPayment->subsidy[0]->subsidyStatus->status->name) }} {{ __('at') }} {{ date('d-m-Y', strtotime($subsidyPayment->subsidy[0]->subsidyStatus->created_at)) }}
							{{ link_to_route('payment.fill',__('Fill'), ['payment' => $subsidyPayment->id], ['class' => 'badge badge-primary badge-pill']) }}
						</li>
					@endforeach
					@foreach ($trainingPayments as $trainingPayment)
						<li class="list-group-item d-flex justify-content-between align-items-center">
							{{ __('Training') }} {{ $trainingPayment->training[0]->type }} &middot; {{ __('Expired') }} {{ __('at') }} {{ date('d-m-Y H:i:s', strtotime($trainingPayment->training[0]->trainingStatus->created_at . '+3 hours')) }}
							{{ link_to_route('payment.fill',__('Fill'), ['payment' => $trainingPayment->id], ['class' => 'badge badge-primary badge-pill']) }}
						</li>
					@endforeach
				</ul>
			</div>
		@endif

		<div class="card card-primary">
			<div class="card-header">
				<a href="{{ route('payment.create') }}" class="btn btn-icon btn-success" title="{{ __('Create') }}"><i class="fa fa-plus"></i></a>
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
				"url": "{{ route('payment.list') }}",
				"type": "POST",
				"data": function (d) {
		          d._token = "{{ csrf_token() }}";
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