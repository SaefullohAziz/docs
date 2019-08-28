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

			{{ Form::open(['route' => ['admin.school.update', $school->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
						<fieldset class="col-sm-6">
							<legend>{{ __('School Data') }}</legend>
							{{ Form::bsSelect(null, __('Type'), 'type', ['Negeri' => 'Negeri', 'Swasta' => 'Swasta'], $school->type, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsText(null, __('Name'), 'name', $school->name, __('Name'), ['required' => '']) }}

							{{ Form::bsTextarea(null, __('Address'), 'address', $school->address, __('Address'), ['required' => '']) }}

							{{ Form::bsSelect(null, __('Province'), 'province', $provinces, $school->province, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsSelect(null, __('Regency'), 'regency', $regencies, $school->regency, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsSelect(null, __('Police Number'), 'police_number', $policeNumbers, $school->police_number, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsText(null, __('Since'), 'since', $school->since, __('Since'), ['maxlength' => '4', 'required' => '']) }}

							{{ Form::bsPhoneNumber(null, __('School Phone Number'), 'school_phone_number', $school->school_phone_number, __('School Phone Number'), ['maxlength' => '13', 'required' => '']) }}

							{{ Form::bsEmail(null, __('School E-Mail'), 'school_email', $school->school_email, __('School E-Mail'), ['required' => '']) }}

							{{ Form::bsText(null, __('School Website (URL)'), 'school_web', $school->school_web, __('School Website (URL)'), ['required' => '']) }}

							{{ Form::bsText(null, __('Total Student'), 'total_student', $school->total_student, __('Total Student'), ['required' => '']) }}

							{{ Form::bsCheckboxList(null, __('Department'), 'department[]', $departments, explode(', ', $school->department)) }}

							{{ Form::bsSelect(null, __('ISO Certificate'), 'iso_certificate', $isoCertificates, $school->iso_certificate, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsInlineRadio(null, __('Mikrotik Academy'), 'mikrotik_academy', ['Sudah' => 'Sudah', 'Belum' => 'Belum'], $school->mikrotik_academy, ['required' => '']) }}
						</fieldset>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Headmaster Data') }}</legend>
								{{ Form::bsText(null, __('Headmaster Name'), 'headmaster_name', $school->headmaster_name, __('Headmaster Name'), ['required' => ''], ['Complete with an academic degree and or degree of expertise.']) }}

								{{ Form::bsPhoneNumber(null, __('Headmaster Phone Number'), 'headmaster_phone_number', $school->headmaster_phone_number, __('Headmaster Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsEmail(null, __('Headmaster E-Mail'), 'headmaster_email', $school->headmaster_email, __('Headmaster E-Mail'), ['required' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('PIC Data') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $school->pic[0]->name, __('PIC Name'), ['required' => ''], [__('Complete with an academic degree and or degree of expertise.')]) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $school->pic[0]->position, __('PIC Position'), ['required' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $school->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsEmail(null, __('PIC E-Mail'), 'pic_email', $school->pic[0]->email, __('PIC E-Mail'), ['required' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Reference') }}</legend>
								{{ Form::bsCheckboxList(null, __('Reference'), 'reference[]', $references, explode(', ', $school->reference)) }}
							</fieldset>
							<fieldset class="dealer-data {{ (in_array('Dealer', explode(', ', $school->reference))?'d-block':'d-none') }}">
								<legend>{{ __('Dealer Data') }}</legend>
								{{ Form::bsText(null, __('Dealer Name'), 'dealer_name', $school->dealer_name, __('Dealer Name')) }}

								{{ Form::bsPhoneNumber(null, __('Dealer Phone Number'), 'dealer_phone_number', $school->dealer_phone_number, __('Dealer Phone Number'), ['maxlength' => '13']) }}

								{{ Form::bsEmail(null, __('Dealer E-Mail'), 'dealer_email', $school->dealer_email, __('Dealer E-Mail')) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Other Data') }}</legend>
								{{ Form::bsInlineRadio(null, 'Apakah Kepala Sekolah telah mempelajari proposal ACP?', 'proposal', ['Sudah' => 'Sudah', 'Belum' => 'Belum'], $school->mikrotik_academy, ['required' => '']) }}

								{{ Form::bsFile(null, __('Requirement Document'), 'document', old('document'), [], [__('File must have extension *.ZIP/*.RAR with size 5 MB or less.')]) }}

								{{ Form::bsFile(null, __('School Photo'), 'photos[]', old('photos[]'), ['multiple' => ''], [__("Don't compress photos on RAR/ZIP. Because, some photos can be uploaded at once. Maximum size is 5 MB per file.")]) }}
							</fieldset>
						</div>
					</div>
				</div>
				<div class="card-footer bg-whitesmoke text-center">
					{{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
					{{ link_to(url()->previous(), __('Cancel'), ['class' => 'btn btn-danger']) }}
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
		$('select[name="province"]').change(function() {
			$('select[name="regency"]').html('<option value="">Select</option>');
			if ($(this).val() != '') {
				$.ajax({
					url : "{{ route('get.regencyByProvince') }}",
					type: "POST",
					dataType: "JSON",
					cache: false,
					data: {'_token' : '{{ csrf_token() }}', 'province' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(key, value) {
							$('select[name="regency"]').append('<option value="'+value+'">'+value+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="regency"]').html('<option value="">Select</option>');
					}
				});
			}
		});

		$('input[name="reference[]"][value="Dealer"]').on('change', evt => {
		    if ($(evt.target).is(':checked')) {
				$('.dealer-data input').prop('required', true).val('');
				$('.dealer-data').removeClass('d-none').addClass('d-block');
			} else {
				$('.dealer-data input').prop('required', false).val('');
				$('.dealer-data').removeClass('d-block').addClass('d-none');
			}
		});
	});
</script>
@endsection