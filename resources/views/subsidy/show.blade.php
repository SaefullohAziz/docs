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
                                <legend>{{ __('Data') }}</legend>
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $subsidy->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
								
								{{ Form::bsUploadedFile(null, __('Submission Letter'), 'submission_letter', 'subsidy/submission-letter', $subsidy->submission_letter) }}
                            </fieldset>
                            <fieldset class="{{ ($subsidy->type=='Student Starter Pack (SSP)'?'d-block':'d-none') }}">
                                <legend>{{ __('Student Starter Pack (SSP)') }}</legend>
								<fieldset>
									<legend>{{ __('Selected Student') }}</legend>
									<ul class="list-group list-group-flush students">
                                        @foreach ($subsidy->students as $student)
                                            <li class="student list-group-item d-flex justify-content-between align-items-center">
                                                <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                                                {{ $student->name }}
                                            </li>
                                        @endforeach
									</ul>
								</fieldset>
                            </fieldset>
							<fieldset class="{{ ($subsidy->type=='Axioo Next Year Support'?'d-block':'d-none') }}">
								<legend>{{ __('Axioo Next Year Support') }}</legend>
								{{ Form::bsSelect('null', __('Student Year'), 'student_year', $studentYears, $subsidy->student_year, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
								
								{{ Form::bsUploadedFile(null, __('Report'), 'report', 'subsidy/report', $subsidy->report) }}
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $subsidy->pic[0]->name, __('PIC Name'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $subsidy->pic[0]->position, __('PIC Position'), ['disabled' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $subsidy->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $subsidy->pic[0]->email, __('PIC E-Mail'), ['disabled' => '']) }}
							</fieldset>
                        </div>
					</div>
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection