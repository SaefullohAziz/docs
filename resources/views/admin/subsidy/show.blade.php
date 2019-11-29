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
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, $data->school_id, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $data->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

								{{ Form::bsUploadedFile(null, __('Submission Letter'), 'submission_letter', 'subsidy/submission-letter', $data->submission_letter) }}
                            </fieldset>
							<fieldset class="{{ ($data->type=='Axioo Next Year Support'?'d-block':'d-none') }}">
								<legend>{{ __('Axioo Next Year Support') }}</legend>
								{{ Form::bsSelect('null', __('Student Year'), 'student_year', $studentYears, $data->student_year, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
								
								{{ Form::bsUploadedFile(null, __('Report'), 'report', 'subsidy/report', $data->report) }}
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $data->pic[0]->name, __('PIC Name'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $data->pic[0]->position, __('PIC Position'), ['disabled' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $data->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $data->pic[0]->email, __('PIC E-Mail'), ['disabled' => '']) }}
							</fieldset>
                        </div>
						<div class="col-12">
                            <fieldset class="{{ ($data->type=='Student Starter Pack (SSP)'?'d-block':'d-none') }}">
                                <legend>{{ __('Student') }}</legend>
								<div class="table-responsive">
									<table class="table table-sm table-striped">
										<thead>
											<tr>
												<th>#</th>
												<th>{{ __('Name') }}</th>
												<th>{{ __('Generation') }}</th>
												<th>{{ __('School Year') }}</th>
												<th>{{ __('Department') }}</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($data->students as $student)
												<tr>
													<td>{{ $loop->iteration }}</td>
													<td>{{ $student->name }}</td>
													<td>{{ $student->class->generation }}</td>
													<td>{{ $student->class->school_year }}</td>
													<td>{{ $student->class->department->name }}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</div>
                            </fieldset>
						</div>
					</div>
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection