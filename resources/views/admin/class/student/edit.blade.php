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

			{{ Form::open(['url' => route('admin.class.student.update', ['studentClass' => $studentClass->id, 'student' => $data->id]), 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Personal Data') }}</legend>
								{{ Form::bsText(null, __('Full Name') . ' *', 'name', $data->name, __('Full Name'), ['required' => '']) }}

								{{ Form::bsText(null, __('Nickname') . ' *', 'nickname', $data->nickname, __('Nickname'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Province') . ' *', 'province', $provinces, $data->province, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('NISN') . ' *', 'nisn', $data->nisn, __('NISN'), ['required' => '']) }}

								{{ Form::bsEmail(null, __('E-Mail') . ' *', 'email', $data->email, __('E-Mail'), ['required' => ''], [__('Required to use Gmail')]) }}

								{{ Form::bsSelect(null, __('Gender') . ' *', 'gender', ['Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan'], $data->gender, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Parents Data') }}</legend>
								{{ Form::bsText(null, __('Father Name'), 'father_name', $data->father_name, __('Father Name')) }}

								{{ Form::bsSelect(null, __('Father Education'), 'father_education', $parentEducations, $data->father_education, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsSelect(null, __('Father Earning'), 'father_earning', $parentEarnings, $data->father_earning, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsText(null, __('Father Earning Nominal'), 'father_earning_nominal', $data->father_earning_nominal, __('Ex: 10000000 (without dot)')) }}

								{{ Form::bsText(null, __('Mother Name') . ' *', 'mother_name', $data->mother_name, __('Mother Name'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Mother Education'), 'mother_education', $parentEducations, $data->mother_education, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsSelect(null, __('Mother Earning'), 'mother_earning', $parentEarnings, $data->mother_earning, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsText(null, __('Mother Earning Nominal'), 'mother_earning_nominal', $data->mother_earning_nominal, __('Ex: 10000000 (without dot)')) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Trustee Data') }}</legend>
								{{ Form::bsText(null, __('Trustee Name'), 'trustee_name', $data->trustee_name, __('Trustee Name')) }}

								{{ Form::bsSelect(null, __('Trustee Education'), 'trustee_education', $parentEducations, $data->trustee_education, __('Select'), ['placeholder' => __('Select')]) }}
							</fieldset>
						</div>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Additional Data') }}</legend>
								{{ Form::bsSelect(null, __('Economy Status'), 'economy_status', $economyStatuses, $data->economy_status, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsSelect(null, __('Religion') . ' *', 'religion', $religions, $data->religion, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Blood Type') . ' *', 'blood_type', $bloodTypes, $data->blood_type, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsInlineRadio(null, __('Special Need') . ' *', 'special_need', ['Ya' => 'Ya', 'Tidak' => 'Tidak'], $data->special_need, ['required' => '']) }}

								{{ Form::bsSelect(null, __('Distance to School'), 'mileage', $mileages, $data->mileage, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsText(null, __('Amount of Distance (km)'), 'distance', $data->distance, __('Amount of Distance (km)')) }}

								{{ Form::bsText(null, __('Last Diploma Number'), 'diploma_number', $data->diploma_number, __('Last Diploma Number')) }}

								{{ Form::bsText(null, __('Height (cm)') . ' *', 'height', $data->height, __('Height (cm)'), ['required' => '']) }}

								{{ Form::bsText(null, __('Weight (kg)') . ' *', 'weight', $data->weight, __('Weight (kg)'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('What order are you in the family?'), 'child_order', $childOrders, $data->child_order, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsSelect(null, __('Number of siblings'), 'sibling_number', $siblingNumbers, $data->sibling_number, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsSelect(null, __('Number of Stepbrothers'), 'stepbrother_number', $siblingNumbers, $data->stepbrother_number, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsSelect(null, __('Number of siblings raised'), 'step_sibling_number', $siblingNumbers, $data->step_sibling_number, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsText(null, __('Date of Birth') . ' *', 'dateofbirth', date('d-m-Y', strtotime($data->dateofbirth)), __('DD-MM-YYYY'), ['required' => '']) }}

								{{ Form::bsTextarea(null, __('Address') . ' *', 'address', $data->address, __('Address'), ['required' => '']) }}

								{{ Form::bsTextarea(null, __('Father Address'), 'father_address', $data->father_address, __('Father Address')) }}

								{{ Form::bsTextarea(null, __('Trustee Address'), 'trustee_address', $data->trustee_address, __('Trustee Address')) }}

								{{ Form::bsPhoneNumber(null, __('Phone Number') . ' *', 'phone_number', $data->phone_number, __('Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsFile(null, __('Photo'), 'photo', $data->photo, [], [__('Photo with JPG/PNG format up to 5MB.')]) }}
							</fieldset>	
							<fieldset>
								<legend>{{ __('Score Data') }}</legend>
								{{ Form::bsText(null, __('Basic Computer'), 'computer_basic_score', $data->computer_basic_score, __('Ex: 10')) }}

								{{ Form::bsText(null, __('Intelligence'), 'intelligence_score', $data->intelligence_score, __('Ex: 10')) }}

								{{ Form::bsText(null, __('Reasoning'), 'reasoning_score', $data->reasoning_score, __('Ex: 10')) }}

								{{ Form::bsText(null, __('Analogy'), 'analogy_score', $data->analogy_score, __('Ex: 10')) }}

								{{ Form::bsText(null, __('Numerical & Accuracy'), 'numerical_score', $data->numerical_score, __('Ex: 10')) }}
							</fieldset>
						</div>
					</div>
					{{ Form::bsCheckbox(null, null, 'terms', 'Agree', __('Data filled above is true and can be accounted for.'), old('terms'), ['required' => '']) }}
				</div>
				<div class="card-footer bg-whitesmoke text-center">
					{{ Form::submit(__('Save'), ['name' => 'submit', 'class' => 'btn btn-primary']) }}
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
		var cleavePN = new Cleave('[name="phone_number"]', {
			phone: true,
			phoneRegionCode: 'id'
		});

		$('[name="dateofbirth"]').keypress(function(e) {
            e.preventDefault();
        }).daterangepicker({
        	locale: {format: 'DD-MM-YYYY'},
        	singleDatePicker: true,
			showDropdowns: true,
      	});
	});
</script>
@endsection