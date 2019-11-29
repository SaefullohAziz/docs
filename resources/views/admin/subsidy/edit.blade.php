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

			{{ Form::open(['route' => ['admin.subsidy.update', $data->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                <legend>{{ __('Data') }}</legend>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, $data->school_id, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $data->type, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsFile(null, __('Submission Letter'), 'submission_letter', old('submission_letter'), [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
                            </fieldset>
                            <fieldset class="{{ ($data->type=='Student Starter Pack (SSP)'?'d-block':'d-none') }}">
                                <legend>{{ __('Student Starter Pack (SSP)') }}</legend>
                                <div class="row">
                                    {{ Form::bsSelect('col-12', __('Generation'), 'generation', $generations, old('generation'), __('Select'), ['placeholder' => __('Select')]) }}
                                </div>
                                {{ Form::bsSelect('null', __('Student'), 'student', [], old('student'), __('Select'), ['placeholder' => __('Select')]) }}
								<fieldset>
									<legend>{{ __('Selected Student') }}</legend>
									<ul class="list-group list-group-flush students">
                                        @foreach ($data->students as $student)
                                            <li class="student list-group-item d-flex justify-content-between align-items-center">
                                                <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                                                {{ $student->name }}
                                                <a href="javascript:void(0);" onclick="deleteStudent('{{ $student->id }}')" class="badge badge-danger badge-pill" title="{{ __('Delete') }}"><i class="fas fa-trash-alt"></i></a>
                                            </li>
                                        @endforeach
									</ul>
									@if ($errors->has('student_id'))
										<div class="text-danger">
											<strong>{{ $errors->first('student_id') }}</strong>
										</div>
									@endif
								</fieldset>
                            </fieldset>
							<fieldset class="{{ ($data->type=='Axioo Next Year Support'?'d-block':'d-none') }}">
								<legend>{{ __('Axioo Next Year Support') }}</legend>
								{{ Form::bsSelect('null', __('Student Year'), 'student_year', $studentYears, $data->student_year, __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsFile(null, __('Report'), 'report', old('report'), [], [__('File must have extension *.ZIP/*.RAR with size 5 MB or less.')]) }}
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $data->pic[0]->name, __('PIC Name'), ['required' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $data->pic[0]->position, __('PIC Position'), ['required' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $data->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $data->pic[0]->email, __('PIC E-Mail'), ['required' => '']) }}
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
        $('select[name="type"]').change(function () {
			$('select[name="generation"]').val(null).change();
			$('.students').html('');
			$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>');
			if ($(this).val() == 'Student Starter Pack (SSP)') {
	    		$('select[name="student"]').closest('fieldset').removeClass('d-none').addClass('d-block');
	    		$('select[name="student_year"]').closest('fieldset').removeClass('d-block').addClass('d-none');
	    		$('[name="report"], [name="student_year"]').prop('required', false);
	    	} else if ($(this).val() == 'Axioo Next Year Support') {
	    		$('select[name="student_year"]').closest('fieldset').removeClass('d-none').addClass('d-block');
	    		$('select[name="student"]').closest('fieldset').removeClass('d-block').addClass('d-none');
	    		$('[name="report"], [name="student_year"]').prop('required', true);
	    	} else {
	    		$('select[name="student"], select[name="student_year"]').closest('fieldset').removeClass('d-block').addClass('d-none');
	    		$('[name="report"], [name="student_year"]').prop('required', false);
	    	}
		});

		$('select[name="generation"]').change(function () {
			$('.students').html('');
			$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>');
	    	if ($(this).val() != '') {
	    		$.ajax({
					url : "{{ route('get.student.byGeneration') }}",
					type: "POST",
					dataType: "JSON",
					data: {'_token' : '{{ csrf_token() }}', 'school' : $('select[name="school_id"]').val(), 'generation' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(k, v) {
							$('.students').append('<li class="student list-group-item d-flex justify-content-between align-items-center"><input type="hidden" name="student_id[]" value="'+k+'">'+v+'<a href="javascript:void(0);" onclick="deleteStudent('+"'"+k+"'"+')" class="badge badge-danger badge-pill badge-sm" title="{{ __('Delete') }}"><i class="fas fa-trash-alt"></i></a></li>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						
					}
				});
	    	}
		});

		$('select[name="student"]').change(function () {
	    	if ($(this).val() != '') {
	    		if ($('[name="student_id[]"][value="'+$(this).val()+'"]').length) {
					swal('{{ __("Student have been selected.") }}', '', 'warning');
					$('select[name="student"]').val(null).change();
				} else {
					$.ajax({
						url : "{{ route('get.student') }}",
						type: "POST",
						dataType: "JSON",
						data: {'_token' : '{{ csrf_token() }}', 'student' : $(this).val()},
						success: function(data)
						{
							$('.students').append('<li class="student list-group-item d-flex justify-content-between align-items-center"><input type="hidden" name="student_id[]" value="'+data.result.id+'">'+data.result.name+'<a href="javascript:void(0);" onclick="deleteStudent('+"'"+data.result.id+"'"+')" class="badge badge-danger badge-pill" title="{{ __('Delete') }}"><i class="fas fa-trash-alt"></i></a></li>');
							$('select[name="student"]').val(null).change();
							$('select[name="student"] option[value="'+data.result.id+'"]').remove();
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});
				}
	    	}
	    });
	});

	function deleteStudent(id) {
		$('input[name="student_id[]"][value="'+id+'"]').closest('.student').remove();
		$('select[name="student"]').val(null).change();
		$.ajax({
			url : "{{ route('get.student') }}",
			type: "POST",
			dataType: "JSON",
			data: {'_token' : '{{ csrf_token() }}', 'student' : id },
			success: function(data)
			{
				$('select[name="student"]').append('<option value="'+data.result.id+'">'+data.result.name+'</option>');
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				
			}
		});
        return false;
	}
</script>
@endsection