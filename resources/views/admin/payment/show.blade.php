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

			{{ Form::open(['url' => '#', 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, $data->school_id, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $data->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

								{{ Form::bsText((empty($data->invoice)?'d-none':'d-block'), __('Invoice'), 'invoice', $data->invoice, __('Invoice'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Payment Date'), 'date', (empty($data->date)?null:date('d-m-Y', strtotime($data->date))), __('DD-MM-YYYY'), ['disabled' => '']) }}
                            </fieldset>
							@if ($data->subsidy->count())
								@if ($data->subsidy[0]->type == 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)')
								<fieldset>
									<legend>{{ __('AGP/FTP Only') }}</legend>
									{{ Form::bsText(null, __('NPWP Number'), 'npwp_number', $data->npwp_number, __('NPWP Number'), ['disabled' => '']) }}

									{{ Form::bsText(null, __('On Behalf of (NPWP)'), 'npwp_on_behalf_of', $data->npwp_on_behalf_of, __('On Behalf of (NPWP)'), ['disabled' => '']) }}

									{{ Form::bsTextarea(null, __('Address (NPWP)'), 'npwp_address', $data->npwp_address, __('Address (NPWP)'), ['disabled' => '']) }}

									{{ Form::bsUploadedFile(null, __('NPWP File'), 'npwp_file', 'payment/npwp', $data->npwp_file_, [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
								</fieldset>
								@endif
							@endif
							@if ($data->training->count())
								<fieldset>
									<legend>{{ __('Return Account Information') }}</legend>
									{{ Form::bsSelect(null, __('Bank Name'), 'receiver_bank_name', $bankSenders, $data->receiver_bank_name, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

									{{ Form::bsText(null, __('Bank Account Number'), 'receiver_bill_number', $data->receiver_bill_number, __('Bank Account Number'), ['disabled' => ''], [__('No punctuation or comma or strip. 10 digits minimum.')]) }}

									{{ Form::bsText(null, __('On Behalf of'), 'receiver_on_behalf_of', $data->receiver_on_behalf_of, __('On Behalf of'), ['disabled' => ''], [__('As per the name on the account')]) }}

									{{ Form::bsUploadedFile(null, __('Bank Account Book'), 'bank_account_book', 'payment/bank-account-book', $data->bank_account_book, [], [__('For the return process to run without problems, the photo must show the account number.'), __('File with PDF/JPG/PNG format up to 5MB.')]) }}
								</fieldset>
							@endif
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								{{ Form::bsCurrency(null, __('Payment Ammount'), 'total', $data->total, __('Payment Ammount'), ['disabled' => '']) }}
								
								{{ Form::bsSelect(($data->training()->count()?'d-none':'d-block'), __('Method'), 'method', $methods, $data->method, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

								{{ Form::bsSelect(($data->method=='ATM'||$data->training()->count()?'d-block':'d-none'), __('Bank Sender'), 'bank_sender', $bankSenders, $data->bank_sender, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

								{{ Form::bsUploadedFile(null, __('Payment Receipt'), 'payment_receipt', 'payment/payment-receipt', $data->payment_receipt, [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
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
									</tbody>
								</table>
							</fieldset>
						</div>
					@endif
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection