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

			{{ Form::open(['route' => 'admin.subsidy.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                <legend>{{ __('Data') }}</legend>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, old('school_id'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'type', $types, old('type'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsFile(null, __('Submission Letter'), 'submission_letter', old('submission_letter'), ['required' => ''], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
                            </fieldset>
                            <fieldset class="{{ (old('type')=='Student Starter Pack (SSP)'?'d-block':'d-none') }}">
                                <legend>{{ __('Student Starter Pack (SSP)') }}</legend>
                                <div class="row">
                                    {{ Form::bsSelect('col-sm-6', __('Generation'), 'generation', $generations, old('generation'), __('Select'), ['placeholder' => __('Select')]) }}

                                    {{ Form::bsSelect('col-sm-6', __('Grade'), 'grade', $grades, old('grade'), __('Select'), ['placeholder' => __('Select')]) }}
                                </div>
                                {{ Form::bsSelect('null', __('Student'), 'student', [], old('student'), __('Select'), ['placeholder' => __('Select')]) }}
								<fieldset>
									<legend>{{ __('Selected Student') }}</legend>
									<ul class="list-group list-group-flush students">
										
									</ul>
									@if ($errors->has('student_id'))
										<div class="text-danger">
											<strong>{{ $errors->first('student_id') }}</strong>
										</div>
									@endif
								</fieldset>
                            </fieldset>
							<fieldset class="{{ (old('type')=='Axioo Next Year Support'?'d-block':'d-none') }}">
								<legend>{{ __('Axioo Next Year Support') }}</legend>
								{{ Form::bsSelect('null', __('Student Year'), 'student_year', $studentYears, old('student_year'), __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsFile(null, __('Report'), 'report', old('report'), [], [__('File must have extension *.ZIP/*.RAR with size 5 MB or less.')]) }}
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsInlineRadio(null, __('Person in Charge?'), 'pic', ['2' => __('Yes'), '1' => __('Not')], old('pic'), [( ! empty(old('pic'))?'':'disabled') => '', 'required' => '']) }}
								<div class="{{ ( ! empty(old('type'))?'d-block':'d-none') }}">
									{{ Form::bsText(null, __('PIC Name'), 'pic_name', old('pic_name'), __('PIC Name')) }}

									{{ Form::bsText(null, __('PIC Position'), 'pic_position', old('pic_position'), __('PIC Position')) }}

									{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', old('pic_phone_number'), __('PIC Phone Number'), ['maxlength' => '13']) }}

									{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', old('pic_email'), __('PIC E-Mail')) }}
								</div>
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
		$('select[name="school_id"]').change(function () {
			$('input[name="pic"]').prop('disabled', true);
			if ($(this).val() != '') {
				$('input[name="pic"]').prop('disabled', false);
			}
		});

        $('select[name="type"]').change(function () {
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
			$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>');
	    	if ($(this).val() != '') {
	    		$.ajax({
					url : "{{ route('get.student.byGeneration') }}",
					type: "POST",
					dataType: "JSON",
					data: {'_token' : '{{ csrf_token() }}', 'school' : $('select[name="school"]').val(), 'generation' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(k, v) {
						 	$('select[name="student"]').append('<option value="'+k+'">'+v+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>');
					}
				});
	    	}
		});

		$('select[name="grade"]').change(function () {
			$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>');
	    	if ($(this).val() != '') {
	    		$.ajax({
					url : "{{ route('get.student.byGrade') }}",
					type: "POST",
					dataType: "JSON",
					data: {'_token' : '{{ csrf_token() }}', 'school' : $('select[name="school"]').val(), 'grade' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(k, v) {
						 	$('select[name="student"]').append('<option value="'+k+'">'+v+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>');
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
						url : "{{ route('get.student.by') }}",
						type: "POST",
						dataType: "JSON",
						data: {'_token' : '{{ csrf_token() }}', 'student' : $(this).val()},
						success: function(data)
						{
							$('.students').append('<li class="student list-group-item d-flex justify-content-between align-items-center"><input type="hidden" name="student_id[]" value="'+data.result.id+'">'+data.result.name+'<a href="javascript:void()" onclick="deleteStudent('+"'"+data.result.id+"'"+')" class="badge badge-danger badge-pill" title="{{ __('Delete') }}"><i class="fas fa-trash-alt"></i></a></li>');
							$('select[name="student"]').val(null).change();
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});
				}
	    	}
	    });

		$('input[name="pic"]').click(function () {
			if ($('input[name="pic"][value="2"]').is(':checked')) {
				getPic();
			} else if ($('input[name="pic"][value="1"]').is(':checked')) {
				$('input[name="pic_name"]').parent().parent('div').removeClass('d-none').addClass('d-block');
	    		$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', false).val('');
			}
		})
	});

	function deleteStudent(id) {
		$('input[name="student_id[]"][value="'+id+'"]').closest('.student').remove();
        return false;
	}

	function getPic() {
		$.ajax({
			url : "{{ route('get.picBySchool') }}",
			type: "POST",
			dataType: "JSON",
			data: {'_token' : '{{ csrf_token() }}', 'school' : $('[name="school_id"]').val()},
			success: function(data)
			{
			    $('[name="pic_name"]').val(data.result.name);
		        $('[name="pic_position"]').val(data.result.position);
			    $('[name="pic_phone_number"]').val(data.result.phone_number);
			    $('[name="pic_email"]').val(data.result.email);
		    	$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', true);
				$('input[name="pic_name"]').parent().parent('div').removeClass('d-none').addClass('d-block');
			},
		    error: function (jqXHR, textStatus, errorThrown)
		    {
			    swal("{{ __('Failed!') }}", "", "warning");
				$('input[name="pic_name"]').parent().parent('div').removeClass('d-block').addClass('d-none');
			}
		});
	}
</script>
@endsection