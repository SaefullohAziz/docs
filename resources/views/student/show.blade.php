@extends('layouts.main')

@section('content')
<div class="row mt-sm-4">
	<div class="col-12 col-md-12 col-lg-4">
		<div class="card profile-widget">
			<div class="profile-widget-header">                     
				<img alt="image" src="{{ asset('storage/student/photo/'.$student->photo) }}" class="rounded-circle profile-widget-picture">
			</div>
			<div class="profile-widget-description">
				<div class="profile-widget-name">{{ $student->name }} <div class="text-muted d-inline font-weight-normal"><div class="slash"></div> {{ $student->email }}</div></div>
                <dl class="row">
                    <dt class="col-4 text-truncate">{{ __('School') }}</dt>
                    <dd class="col-8 text-truncate">{{ $student->school->name }}</dd>
                    <dt class="col-4 text-truncate">{{ __('Generation') }}</dt>
                    <dd class="col-8 text-truncate">{{ $student->generation }}</dd>
                    <dt class="col-4 text-truncate">{{ __('School Year') }}</dt>
                    <dd class="col-8 text-truncate">{{ $student->school_year }}</dd>
                    <dt class="col-4 text-truncate">{{ __('Department') }}</dt>
                    <dd class="col-8 text-truncate">{{ $student->department }}</dd>
                </dl>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-12 col-lg-8">

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

		<div class="card">
			<div class="card-header">
				<h4>{{ __("Edit Student") }}</h4>
			</div>
			{{ Form::open(['route' => ['student.update', $student->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Personal Data') }}</legend>
								{{ Form::bsText(null, __('Full Name'), 'name', $student->name, __('Full Name'), ['required' => '']) }}

								{{ Form::bsText(null, __('Nickname'), 'nickname', $student->nickname, __('Nickname'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Province'), 'province', $provinces, $student->province, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('School Year'), 'school_year', $schoolYears, $student->school_year, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('NISN'), 'nisn', $student->nisn, __('NISN'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Department'), 'department', $departments, $student->department, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsEmail(null, __('E-Mail'), 'email', $student->email, __('E-Mail'), ['required' => ''], [__('Required to use Gmail')]) }}

								{{ Form::bsSelect(null, __('Gender'), 'gender', ['Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan'], $student->gender, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Grade'), 'grade', $grades, $student->grade, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Generation'), 'generation', $generations, $student->generation, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Parents Data') }}</legend>
								{{ Form::bsText(null, __('Father Name'), 'father_name', $student->father_name, __('Father Name'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Father Education'), 'father_education', $parentEducations, $student->father_education, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Father Earning'), 'father_earning', $parentEarnings, $student->father_earning, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Father Earning Nominal'), 'father_earning_nominal', $student->father_earning_nominal, __('Ex: 10000000 (without dot)'), ['required' => '']) }}

								{{ Form::bsText(null, __('Mother Name'), 'mother_name', $student->mother_name, __('Mother Name'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Mother Education'), 'mother_education', $parentEducations, $student->mother_education, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Mother Earning'), 'mother_earning', $parentEarnings, $student->mother_earning, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Mother Earning Nominal'), 'mother_earning_nominal', $student->mother_earning_nominal, __('Ex: 10000000 (without dot)'), ['required' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Trustee Data') }}</legend>
								{{ Form::bsText(null, __('Trustee Name'), 'trustee_name', $student->trustee_name, __('Trustee Name'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Trustee Education'), 'trustee_education', $parentEducations, $student->trustee_education, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}
							</fieldset>
						</div>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Additional Data') }}</legend>
								{{ Form::bsSelect(null, __('Economy Status'), 'economy_status', $economyStatuses, $student->economy_status, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Religion'), 'religion', $religions, $student->religion, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Blood Type'), 'blood_type', $bloodTypes, $student->blood_type, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsInlineRadio(null, __('Special Need'), 'special_need', ['Ya' => 'Ya', 'Tidak' => 'Tidak'], $student->special_need, ['required' => '']) }}

								{{ Form::bsSelect(null, __('Distance to School'), 'mileage', $mileages, $student->mileage, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Amount of Distance (km)'), 'distance', $student->distance, __('Amount of Distance (km)'), ['required' => '']) }}

								{{ Form::bsText(null, __('Last Diploma Number'), 'diploma_number', $student->diploma_number, __('Last Diploma Number'), ['required' => '']) }}

								{{ Form::bsText(null, __('Height (cm)'), 'height', $student->height, __('Height (cm)'), ['required' => '']) }}

								{{ Form::bsText(null, __('Weight (kg)'), 'weight', $student->weight, __('Weight (kg)'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('What order are you in the family?'), 'child_order', $childOrders, $student->child_order, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Number of siblings'), 'sibling_number', $siblingNumbers, $student->sibling_number, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Number of Stepbrothers'), 'stepbrother_number', $siblingNumbers, $student->stepbrother_number, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsSelect(null, __('Number of siblings raised'), 'step_sibling_number', $siblingNumbers, $student->step_sibling_number, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Date of Birth'), 'dateofbirth', date('d-m-Y', strtotime($student->dateofbirth)), __('DD-MM-YYYY'), ['required' => '']) }}

								{{ Form::bsTextarea(null, __('Address'), 'address', $student->address, __('Address'), ['required' => '']) }}

								{{ Form::bsTextarea(null, __('Father Address'), 'father_address', $student->father_address, __('Father Address'), ['required' => '']) }}

								{{ Form::bsTextarea(null, __('Trustee Address'), 'trustee_address', $student->trustee_address, __('Trustee Address'), ['required' => '']) }}

								{{ Form::bsPhoneNumber(null, __('Phone Number'), 'phone_number', $student->phone_number, __('Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsFile(null, __('Photo'), 'photo', $student->photo, [], [__('Photo with JPG/PNG format up to 5MB.')]) }}
							</fieldset>	
							<fieldset>
								<legend>{{ __('Score Data') }}</legend>
								{{ Form::bsText(null, __('Basic Computer'), 'computer_basic_score', $student->computer_basic_score, __('Ex: 10'), ['required' => '']) }}

								{{ Form::bsText(null, __('Intelligence'), 'intelligence_score', $student->intelligence_score, __('Ex: 10'), ['required' => '']) }}

								{{ Form::bsText(null, __('Reasoning'), 'reasoning_score', $student->reasoning_score, __('Ex: 10'), ['required' => '']) }}

								{{ Form::bsText(null, __('Analogy'), 'analogy_score', $student->analogy_score, __('Ex: 10'), ['required' => '']) }}

								{{ Form::bsText(null, __('Numerical & Accuracy'), 'numerical_score', $student->numerical_score, __('Ex: 10'), ['required' => '']) }}
							</fieldset>
						</div>
					</div>
                    {{ Form::bsCheckbox(null, null, 'terms', 'Agree', __('Data filled above is true and can be accounted for.'), old('terms'), ['required' => '']) }}
				</div>
                <div class="card-footer bg-whitesmoke text-right">
                    {{ Form::submit(__('Save'), ['name' => 'submit', 'class' => 'btn btn-primary']) }}
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