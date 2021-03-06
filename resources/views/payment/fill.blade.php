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

			{{ Form::open(['route' => ['payment.confirm', $data->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $data->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(($data->type=='Subsidi'?'d-block':'d-none'), __('Repayment'), 'repayment', $repayments, $data->repayment, __('Select'), ['placeholder' => __('Select'), ( ! empty($data->repayment)?'disabled':'') => '']) }}

								{{ Form::bsText(($data->training->count()?'d-none':'d-block'), __('Invoice'), 'invoice', $data->invoice, __('Invoice'), [( ! empty($data->invoice)?'disabled':'') => '']) }}

								{{ Form::bsText(null, __('Payment Date'), 'date', (empty($data->date)?null:date('d-m-Y', strtotime($data->date))), __('DD-MM-YYYY'), ['required' => '']) }}
                            </fieldset>
							@if ($data->subsidy->count())
								@if ($data->subsidy[0]->type == 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)')
								<fieldset>
									<legend>{{ __('AGP/FTP Only') }}</legend>
									{{ Form::bsText(null, __('NPWP Number'), 'npwp_number', $data->npwp_number, __('NPWP Number'), [( ! empty($data->npwp_number)?'disabled':'') => '', (empty($data->npwp_number)?'required':'') => '']) }}

									{{ Form::bsText(null, __('On Behalf of (NPWP)'), 'npwp_on_behalf_of', $data->npwp_on_behalf_of, __('On Behalf of (NPWP)'), [( ! empty($data->npwp_on_behalf_of)?'disabled':'') => '', (empty($data->npwp_on_behalf_of)?'required':'') => '']) }}

									{{ Form::bsTextarea(null, __('Address (NPWP)'), 'npwp_address', $data->npwp_address, __('Address (NPWP)'), [( ! empty($data->npwp_address)?'disabled':'') => '', (empty($data->npwp_address)?'required':'') => '']) }}

									{{ Form::bsFile(null, __('NPWP File'), 'npwp_file', null, [(empty($data->npwp_file)?'required':'') => ''], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
								</fieldset>
								@endif
							@endif
							@if ($data->training->count())
								<fieldset>
									<legend>{{ __('Return Account Information') }}</legend>
									{{ Form::bsSelect(null, __('Bank Name'), 'receiver_bank_name', $bankSenders, $data->receiver_bank_name, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

									{{ Form::bsText(null, __('Bank Account Number'), 'receiver_bill_number', $data->receiver_bill_number, __('Bank Account Number'), [( ! empty($data->receiver_bill_number)?'disabled':'') => '', (empty($data->receiver_bill_number)?'required':'') => ''], [__('No punctuation or comma or strip. 10 digits minimum.')]) }}

									{{ Form::bsText(null, __('On Behalf of'), 'receiver_on_behalf_of', $data->receiver_on_behalf_of, __('On Behalf of'), [( ! empty($data->receiver_on_behalf_of)?'disabled':'') => '', (empty($data->receiver_on_behalf_of)?'required':'') => ''], [__('As per the name on the account')]) }}

									{{ Form::bsFile(null, __('Bank Account Book'), 'bank_account_book', null, [(empty($data->bank_account_book)?'required':'') => ''], [__('For the return process to run without problems, the photo must show the account number.'), __('File with PDF/JPG/PNG format up to 5MB.')]) }}
								</fieldset>
							@endif
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								{{ Form::bsCurrency(null, __('Payment Ammount'), 'total', $data->total, __('Payment Ammount'), ['required' => '', ( ! empty($data->total)?'disabled':'') => ''], [__('Nominal must be paid.')]) }}
								
								{{ Form::bsSelect(($data->training->count()?'d-none':'d-block'), __('Method'), 'method', $methods, $data->method, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsSelect(($data->method=='ATM'||$data->training->count()?'d-block':'d-none'), __('Bank Sender'), 'bank_sender', $bankSenders, $data->bank_sender, __('Select'), ['placeholder' => __('Select')]) }}

                                {{ Form::bsCurrency(($data->repayment=='Paid in installment' || old('repayment')=='Paid in installment'?'d-block':'d-none'), __('Installment Ammount'), 'installment_ammount', null, __('Installment Ammount'), [], [__('Nominal to be paid in installment.')]) }}

								{{ Form::bsFile(null, __('Payment Receipt'), 'payment_receipt', null, [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
							</fieldset>
                        </div>
					</div>
					@if ($data->installments()->count() > 0)
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
										@foreach ($data->installments as $installment)
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
											<td><b>Rp. {{ $data->total-$data->installment->sum('total') }}</b></td>
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