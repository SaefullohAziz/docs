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

			{{ Form::open(['route' => 'admin.exam.readiness.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, old('school_id'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'exam_type', $types, old('exam_type'), __('Select'), ['placeholder' => __('Select'), 'required' => ''], ['']) }}

                                {{ Form::bsSelect((old('sub_exam_type')?'d-block':'d-none'), __('Sub Type'), (is_array(old('sub_exam_type'))?'sub_exam_type[]':'sub_exam_type'), [], null, __('Select'), ['placeholder' => __('Select'), 'required' => '', (is_array(old('sub_exam_type'))?'multiple':'') => '']) }}

								{{ Form::bsInlineRadio((old('ma_status')?'d-block':'d-none'), __('Ma Status?'), 'ma_status', ['Sudah' => __('Already'), 'Belum' => __('Not yet')], old('ma_status'), [( ! empty(old('ma_status'))?'':'disabled') => '', 'required' => '']) }}

								{{ Form::bsInlineRadio((old('execution')?'d-block':'d-none'), __('Execution?'), 'execution', ['Mandiri' => __('Self'), 'Bergabung' => __('Together')], old('execution'), [( ! empty(old('execution'))?'':'disabled') => '', 'required' => '']) }}

                                {{ Form::bsSelect((old('reference_school')?'d-block':'d-none'), __('School Reference'), 'reference_school', $referenceSchools, old('reference_school'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsInlineRadio((old('confirmation_of_readiness')?'d-block':'d-none'), __('Important!'), 'confirmation_of_readiness', ['1' => __('Ready to provide transportation and accommodation for the MikroTik Trainer team')], old('confirmation_of_readiness'), [( ! empty(old('confirmation_of_readiness'))?'':'disabled') => '', 'required' => '']) }}

                            </fieldset>
                            <fieldset>
                                <legend>{{ __('Student') }}</legend>
                                <div class="row">
                                    {{ Form::bsSelect('col-12', __('Generation'), 'generation', $generations, old('generation'), __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
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
		$('select[name="school_id"]').change(function () {
			$('[name="student_id[]"]').closest('.list-group-item').remove();
			$('select[name="generation"], input[name="pic"]').prop('disabled', true);
            $('select[name="generation"]').val(null).change();
			if ($(this).val() != '') {
				$('select[name="generation"], input[name="pic"]').prop('disabled', false);
                if ($('input[name="pic"][value="2"]').is(':checked')) {
                    getPic();
                }
			}
		});

		$('select[name="exam_type"]').change(function() {
			$('select[name="sub_exam_type[]"]').attr('name', 'sub_exam_type').select2();
			$('[name="sub_exam_type"], [name="ma_status"], [name="confirmation_of_readiness"], [name="execution"], [name="reference_school"]').prop('required', false).prop('disabled', true).closest('form-group').addClass('d-none');
			$('[name="sub_exam_type"]').prop('required', false).prop('disabled', true).prop('multiple', false).select2();
			$('[name="sub_exam_type"]').html('<option value=""></option>');
			$.ajax({
				url : "{{ route('get.subExam') }}",
				type: "POST",
				dataType: "JSON",
				data: {
			        '_token' : '{{ csrf_token() }}', 'type' : $('select[name="exam_type"]').val()
			    },
				success: function(data)
				{
					if (data.status == true) {
						$.each(data.result, function(key, value) {
							$('#sub_exam_type').append('<option value="'+value+'"> '+value+' </option>');
						});
						$('#sub_exam_type').parent().show(300);
						$('#sub_exam_type').prop('required', true).prop('disabled', false);
						$('[name="exam_type"]').closest('.form-text').text(data.result.description);
					} else {
						$('#sub_exam_type').parent().hide(300);
						$('#sub_exam_type').prop('required', false).prop('disabled', true);
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					$('#sub_exam_type').parent().hide(300);
					$('#sub_exam_type').prop('required', false);
				}
			});
			$('#sub_exam_type').closest('.form-group').removeClass('d-none');
			$('input[name="ma_status"], input[name="confirmation_of_readiness"], input[name="execution"]').prop('required', false).iCheck('uncheck');
			$('input[name="ma_status"], input[name="confirmation_of_readiness"], input[name="execution"]').closest('.form-group').hide(300);
			if ($(this).val() == 'MTCNA') {
				$('input[name="ma_status"]').prop('required', true).prop('disabled', false).iCheck('uncheck');
				$('input[name="ma_status"]').closest('.form-group').removeClass('d-none').show(300);
			} 
			else if ($(this).val() == 'Remidial Axioo' || $(this).val() == 'Axioo')
			{
				$('[name="sub_exam_type"] option[value=""]').remove();
				$('[name="sub_exam_type"]').prop('multiple', true).attr('name', 'sub_exam_type[]').select2();
			}
		});

		$('input[name="ma_status"]').on('click', function(){
			if ($(this).val() == 'Belum') 
			{
				$('[name="execution"]').closest('.form-group').removeClass('d-none');
				$('[name="execution"]').parent().show(200);
				$('[name="execution"]').prop('required', true).prop('disabled', false);
			} 
			else if ($(this).val() == 'Sudah')
			{
				$('[name="execution"]').closest('.form-group').addClass('d-none');
				$('[name="execution"]').parent().hide(200);
				$('[name="execution"]').prop('required', false).prop('disabled', true);
			}
		});

		$('[name="execution"]').on('click', function(){
			if ($(this).val() == 'Bergabung') 
			{
				$('select[name="reference_school"], input[name="confirmation_of_readiness"]').closest('.form-group').removeClass('d-none');
				$('select[name="reference_school"], input[name="confirmation_of_readiness"]').parent().show(200);
				$('select[name="reference_school"], input[name="confirmation_of_readiness"]').prop('required', true).prop('disabled', false);
			} 
			else if  ($(this).val() == 'Mandiri')
			{
				$('select[name="reference_school"], input[name="confirmation_of_readiness"]').closest('.form-group').addClass('d-none');
				$('select[name="reference_school"], input[name="confirmation_of_readiness"]').parent().hide(200);
				$('select[name="reference_school"], input[name="confirmation_of_readiness"]').prop('required', false).prop('disabled', true);
			}
		});

		$('select[name="generation"]').change(function (){
			$('.students').html('');
			$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>');
			$('select[name="student"]').val(null).change();
			$('[name="student_id[]"]').closest('.list-group-item').remove();
			if ($(this).val() != '') {
				$.ajax({
					url : "{{ route('get.student') }}",
					type: "POST",
					dataType: "JSON",
					data: {
						'_token' : '{{ csrf_token() }}',
						'generation' : $(this).val(),
						'school' : $('select[name="school_id"]').val(),
					},
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

        $('input[name="pic"]').click(function () {
			if ($('input[name="pic"][value="2"]').is(':checked')) {
				getPic();
			} else if ($('input[name="pic"][value="1"]').is(':checked')) {
				$('input[name="pic_name"]').parent().parent('div').removeClass('d-none').addClass('d-block');
	    		$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', false).val('');
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

	function getPic() {
		$.ajax({
			url : "{{ route('get.pic') }}",
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