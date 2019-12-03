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

			{{ Form::open(['route' => ['payment.update', $data->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $data->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

								{{ Form::bsText((empty($data->invoice)?'d-none':'d-block'), __('Invoice'), 'invoice', $data->invoice, __('Invoice'), []) }}

								{{ Form::bsText(null, __('Payment Date'), 'date', (empty($data->date)?null:date('d-m-Y', strtotime($data->date))), 'DD-MM-YYYY', ['required' => '']) }}
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								{{ Form::bsCurrency(null, __('Payment Ammount'), 'total', $data->total, __('Payment Ammount'), ['required' => '']) }}
								
								{{ Form::bsSelect(($data->training()->count()?'d-none':'d-block'), __('Method'), 'method', $methods, $data->method, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(($data->method=='ATM'||$data->training()->count()?'d-block':'d-none'), __('Bank Sender'), 'bank_sender', $bankSenders, $data->bank_sender, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsFile(null, __('Payment Receipt'), 'payment_receipt', null, [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
							</fieldset>
                        </div>
					</div>
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

        $('select[name="type"]').change(function () {
			if ($(this).val() == 'Biaya Pengiriman Mikrotik') {
				$('input[name="invoice"]').prop('required', false).val('');
				$('input[name="invoice"]').closest('.form-group').removeClass('d-block').addClass('d-none');
	    	} else {
				$('input[name="invoice"]').prop('required', true).val('');
				$('input[name="invoice"]').closest('.form-group').removeClass('d-none').addClass('d-block');
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