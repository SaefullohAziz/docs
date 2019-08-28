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

			{{ Form::open(['route' => 'student.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Personal Data') }}</legend>
								{{ Form::bsText(null, __('Full Name'), 'name', old('name'), __('Full Name'), ['required' => '']) }}

								{{ Form::bsText(null, __('Nickname'), 'nickname', old('nickname'), __('Nickname'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Province'), 'province', $provinces, old('province'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('School Year'), 'school_year', $schoolYears, old('school_year'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('NISN'), 'nisn', old('nisn'), __('NISN'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Department'), 'department', $departments, old('department'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsEmail(null, __('E-Mail'), 'email', old('email'), __('E-Mail'), ['required' => ''], [__('Required to use Gmail')]) }}

								{{ Form::bsSelect(null, __('Gender'), 'gender', ['Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan'], old('gender'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Grade'), 'grade', $grades, old('grade'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Generation'), 'generation', $generations, old('generation'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Parents Data') }}</legend>
								{{ Form::bsText(null, __('Father Name'), 'father_name', old('father_name'), __('Father Name'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Father Education'), 'father_education', $parentEducations, old('father_education'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Father Earning'), 'father_earning', $parentEarnings, old('father_earning'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Father Earning Nominal'), 'father_earning_nominal', old('father_earning_nominal'), __('Ex: 10000000 (without dot)'), ['required' => '']) }}

								{{ Form::bsText(null, __('Mother Name'), 'mother_name', old('mother_name'), __('Mother Name'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Mother Education'), 'mother_education', $parentEducations, old('mother_education'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Mother Earning'), 'mother_earning', $parentEarnings, old('mother_earning'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Mother Earning Nominal'), 'mother_earning_nominal', old('mother_earning_nominal'), __('Ex: 10000000 (without dot)'), ['required' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Trustee Data') }}</legend>
								{{ Form::bsText(null, __('Trustee Name'), 'trustee_name', old('trustee_name'), __('Trustee Name'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Trustee Education'), 'trustee_education', $parentEducations, old('trustee_education'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}
							</fieldset>
						</div>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Additional Data') }}</legend>
								{{ Form::bsSelect(null, __('Economy Status'), 'economy_status', $economyStatuses, old('economy_status'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Religion'), 'religion', $religions, old('religion'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Blood Type'), 'blood_type', $bloodTypes, old('blood_type'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsInlineRadio(null, __('Special Need'), 'special_need', ['Ya' => 'Ya', 'Tidak' => 'Tidak'], old('special_need'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Distance to School'), 'mileage', $mileages, old('mileage'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Amount of Distance (km)'), 'distance', old('distance'), __('Amount of Distance (km)'), ['required' => '']) }}

								{{ Form::bsText(null, __('Last Diploma Number'), 'diploma_number', old('diploma_number'), __('Last Diploma Number'), ['required' => '']) }}

								{{ Form::bsText(null, __('Height (cm)'), 'height', old('height'), __('Height (cm)'), ['required' => '']) }}

								{{ Form::bsText(null, __('Weight (kg)'), 'weight', old('weight'), __('Weight (kg)'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('What order are you in the family?'), 'child_order', $childOrders, old('child_order'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Number of siblings'), 'sibling_number', $siblingNumbers, old('sibling_number'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Number of Stepbrothers'), 'stepbrother_number', $siblingNumbers, old('stepbrother_number'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Number of siblings raised'), 'step_sibling_number', $siblingNumbers, old('step_sibling_number'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Date of Birth'), 'dateofbirth', old('dateofbirth'), __('DD-MM-YYYY'), ['required' => '']) }}

								{{ Form::bsTextarea(null, __('Address'), 'address', old('address'), __('Address'), ['required' => '']) }}

								{{ Form::bsTextarea(null, __('Father Address'), 'father_address', old('father_address'), __('Father Address'), ['required' => '']) }}

								{{ Form::bsTextarea(null, __('Trustee Address'), 'trustee_address', old('trustee_address'), __('Trustee Address'), ['required' => '']) }}

								{{ Form::bsPhoneNumber(null, __('Phone Number'), 'phone_number', old('phone_number'), __('Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsFile(null, __('Photo'), 'photo', old('photo'), [], [__('Photo with JPG/PNG format up to 5MB.')]) }}
							</fieldset>	
							<fieldset>
								<legend>{{ __('Score Data') }}</legend>
								{{ Form::bsText(null, __('Basic Computer'), 'computer_basic_score', old('computer_basic_score'), __('Ex: 10'), ['required' => '']) }}

								{{ Form::bsText(null, __('Intelligence'), 'intelligence_score', old('intelligence_score'), __('Ex: 10'), ['required' => '']) }}

								{{ Form::bsText(null, __('Reasoning'), 'reasoning_score', old('reasoning_score'), __('Ex: 10'), ['required' => '']) }}

								{{ Form::bsText(null, __('Analogy'), 'analogy_score', old('analogy_score'), __('Ex: 10'), ['required' => '']) }}

								{{ Form::bsText(null, __('Numerical & Accuracy'), 'numerical_score', old('numerical_score'), __('Ex: 10'), ['required' => '']) }}
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
      	});
	});
</script>
@endsection