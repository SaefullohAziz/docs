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
							<legend>{{ __('Data') }}</legend>
							<fieldset>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, $examReadiness->school_id, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'exam_type', $types, $examReadiness->exam_type, __('Select'), ['placeholder' => __('Select'), 'disabled' => ''], ['']) }}

                                {{ Form::bsText(null, __('Sub Type'), 'exam_sub_type', $examReadiness->sub_exam_type, __('Select'), ['disabled' => '']) }}

                                @if ($examReadiness->ma_status)
									{{ Form::bsInlineRadio(null, __('Ma Status?'), 'ma_status', ['Sudah' => __('Already'), 'Belum' => __('Not yet')], $examReadiness->ma_status, ['disabled' => '', 'disabled' => '']) }}
								@endif

                                @if ($examReadiness->execution)
									{{ Form::bsInlineRadio(null, __('Execution?'), 'execution', ['Mandiri' => __('Self'), 'Bergabung' => __('Together')], $examReadiness->execution, ['disabled' => '', 'disabled' => '']) }}
								@endif

								@if ($examReadiness->reference_school)
                                	{{ Form::bsSelect(null, __('School Reference'), 'reference_school', $reference_schools , $examReadiness->reference_school, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
                                @endif

                            </fieldset>
                        </div>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								<div class="{{ ( ! empty($examReadiness->school_id)?'d-block':'d-none') }}">
									{{ Form::bsText(null, __('PIC Name'), 'pic_name', $examReadiness->pic[0]->name, __('PIC Name'), ['disabled' => '']) }}

									{{ Form::bsText(null, __('PIC Position'), 'pic_position', $examReadiness->pic[0]->position, __('PIC Position'), ['disabled' => '']) }}

									{{ Form::bsText(null, __('PIC Phone Number'), 'pic_phone_number', $examReadiness->pic[0]->phone_number, __('PIC Phone Number'), ['disabled' => '']) }}

									{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $examReadiness->pic[0]->email, __('PIC E-Mail'), ['disabled' => '']) }}
								</div>
                            </fieldset>
						</div>
					</div>
					<div class="col-12">
                            <fieldset>
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
											@foreach ($examReadiness->student as $student)
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

					<div class="card-footer bg-whitesmoke text-center">
						{{ link_to(url()->previous(), __('Cancel'), ['class' => 'btn btn-danger']) }}
					</div>
				{{ Form::close() }}

			</div>
		</div>
	</div>
</div>
@endsection