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

			{{ Form::open(['url' => '#', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, $data->school_id, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'exam_type', $types, $data->exam_type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(($data->sub_exam_type?'d-block':'d-none'), __('Sub Type'), (is_array($data->sub_exam_type)?'sub_exam_type[]':'sub_exam_type'), $subTypes, $data->sub_type, __('Select'), ['placeholder' => __('Select'), (is_array($data->sub_type)?'multiple':'') => '', 'disabled' => '']) }}

								{{ Form::bsInlineRadio(($data->ma_status?'d-block':'d-none'), __('Ma Status?'), 'ma_status', ['Sudah' => __('Already'), 'Belum' => __('Not yet')], $data->ma_status, ['disabled' => '']) }}

								{{ Form::bsInlineRadio(($data->execution?'d-block':'d-none'), __('Execution?'), 'execution', ['Mandiri' => __('Self'), 'Bergabung' => __('Together')], $data->execution, ['disabled' => '']) }}

                                {{ Form::bsSelect((old('reference_school')?'d-block':'d-none'), __('School Reference'), 'reference_school', $referenceSchools, $data->reference_school, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
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
                            <fieldset>
                                <legend>{{ __('Student') }}</legend>
								<div class="table-responsive">
									<table class="table table-sm table-striped" id="table4data">
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
											@foreach ($data->student as $student)
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
					<div class="card-footer bg-whitesmoke text-center">
						{{ link_to(url()->previous(), __('Cancel'), ['class' => 'btn btn-danger']) }}
					</div>
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection