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

			{{ Form::open(['route' => ['payment.confirm', $payment->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $payment->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(($payment->type=='Subsidi'?'d-block':'d-none'), __('Repayment'), 'repayment', $repayments, $payment->repayment, __('Select'), ['placeholder' => __('Select'), ( ! empty($payment->repayment)?'disabled':'') => '']) }}

								{{ Form::bsText(null, __('Invoice'), 'invoice', $payment->invoice, __('Invoice'), [( ! empty($payment->invoice)?'disabled':'') => '']) }}

								{{ Form::bsText(null, __('Payment Date'), 'date', (empty($payment->date)?null:date('d-m-Y', strtotime($payment->date))), __('DD-MM-YYYY'), ['required' => '']) }}
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								{{ Form::bsCurrency(null, __('Payment Ammount'), 'total', $payment->total, __('Payment Ammount'), ['required' => '', ( ! empty($payment->total)?'disabled':'') => ''], [__('Nominal must be paid.')]) }}
								
								{{ Form::bsSelect(null, __('Method'), 'method', $methods, $payment->method, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(($payment->method=='ATM'?'d-block':'d-none'), __('Bank Sender'), 'bank_sender', $bankSenders, $payment->bank_sender, __('Select'), ['placeholder' => __('Select')]) }}

                                {{ Form::bsCurrency(($payment->repayment=='Paid in installment'?'d-block':'d-none'), __('Installment Ammount'), 'installment_ammount', null, __('Installment Ammount'), [], [__('Nominal to be paid in installment.')]) }}

								{{ Form::bsFile(null, __('Payment Receipt'), 'payment_receipt', null, [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
							</fieldset>
                        </div>
					</div>
					@if ($payment->installment()->count() > 0)
						<div class="row">
							<fieldset class="col-12">
								<legend>{{ __('Installment') }}</legend>
								<table class="table table-sm">
									<thead>
										<tr>
											<th>{{ __('No.')}}</th>
											<th>{{ __('Date') }}</th>
											<th>{{ __('Nominal') }}</th>
											<th>{{ __('Method') }}</th>
											<th>{{ __('Bank Sender') }}</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($payment->installment as $installment)
											<tr>
												<td>{{ $loop->iteration }}</td>
												<td>{{ date('d-m-Y', strtotime($installment->date)) }}</td>
												<td>Rp. {{ $installment->total }}</td>
												<td>{{ $installment->method }}</td>
												<td>{{ $installment->bank_sender }}</td>
											</tr>
										@endforeach
										<tr>
											<td colspan="3"></td>
											<td><b>{{ __('Remaining payment') }}</b></td>
											<td><b>Rp. {{ $payment->total-$payment->installment->sum('total') }}</b></td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</div>
					@endif
				</div>
				<div class="card-footer bg-whitesmoke text-center">
					{{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
					{{ link_to(url()->previous(),__('Cancel'), ['class' => 'btn btn-danger']) }}
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
		$('[name="date"]').keypress(function(e) {
            e.preventDefault();
        }).daterangepicker({
        	locale: {format: 'DD-MM-YYYY'},
        	singleDatePicker: true,
      	});

		var cleaveC = new Cleave('.currency', {
        	numeral: true,
            numeralThousandsGroupStyle: 'thousand'
        });

        $('select[name="repayment"]').change(function () {
			if ($(this).val() == 'Paid in installment') {
				$('input[name="installment_ammount"]').prop('required', true).val('');
				$('input[name="installment_ammount"]').closest('.form-group').removeClass('d-none').addClass('d-block');
	    	} else {
				$('input[name="installment_ammount"]').prop('required', false).val('');
				$('input[name="installment_ammount"]').closest('.form-group').removeClass('d-block').addClass('d-none');
			}
		});

		$('select[name="method"]').change(function () {
			if ($(this).val() == 'ATM') {
				$('select[name="bank_sender"]').prop('required', true);
				$('select[name="bank_sender"]').val(null).change();
				$('select[name="bank_sender"]').closest('.form-group').removeClass('d-none').addClass('d-block');
			} else {
				$('select[name="bank_sender"]').prop('required', false);
				$('select[name="bank_sender"]').val(null).change();
				$('select[name="bank_sender"]').closest('.form-group').removeClass('d-block').addClass('d-none');
			}
		});
	});
</script>
@endsection