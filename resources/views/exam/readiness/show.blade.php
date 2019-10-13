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

@section('script')
<script>
	$(document).ready(function () {

		$('select[name="exam_type"]').change(function() {
			$('select[name="exam_sub_types[]"]').attr('name', 'exam_sub_type').select2();
			$('[name="exam_sub_type"], [name="ma_status"], [name="confirmation_of_readiness"], [name="execution"], [name="reference_school"]').prop('required', false).prop('disabled', true).closest('form-group').addClass('d-none');
			$('[name="exam_sub_type"]').prop('required', false).prop('disabled', true).prop('multiple', false).select2();
			$('[name="exam_sub_type"]').html('<option value=""></option>');
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
							$('#exam_sub_type').append('<option value="'+value+'"> '+value+' </option>');
						});
						$('#exam_sub_type').parent().show(300);
						$('#exam_sub_type').prop('required', true).prop('disabled', false);
						$('[name="exam_type"]').closest('.form-text').text(data.result.description);
					} else {
						$('#exam_sub_type').parent().hide(300);
						$('#exam_sub_type').prop('required', false).prop('disabled', true);
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					$('#exam_sub_type').parent().hide(300);
					$('#exam_sub_type').prop('required', false);
				}
			});
			$('#exam_sub_type').closest('.form-group').removeClass('d-none');
			$('input[name="ma_status"], input[name="confirmation_of_readiness"], input[name="execution"]').prop('required', false).iCheck('uncheck');
			$('input[name="ma_status"], input[name="confirmation_of_readiness"], input[name="execution"]').closest('.form-group').hide(300);
			if ($(this).val() == 'MTCNA') {
				$('input[name="ma_status"]').prop('required', true).prop('disabled', false).iCheck('uncheck');
				$('input[name="ma_status"]').closest('.form-group').removeClass('d-none');
				$('input[name="ma_status"]').closest('.form-group').show(300);
			} 
			else if ($(this).val() == 'Remidial Axioo' || $(this).val() == 'Axioo')
			{
				$('[name="exam_sub_type"] option[value=""]').remove();
				$('[name="exam_sub_type"]').prop('multiple', true).attr('name', 'exam_sub_types[]').select2();
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


        $('select[name="generation"]').change(function () {
			$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>');
	    	if ($(this).val() != '') {
	    		$.ajax({
					url : "{{ route('get.student') }}",
					type: "POST",
					dataType: "JSON",
					data: {'_token' : '{{ csrf_token() }}', 'ssp' : true, 'school' : $('select[name="school_id"]').val(), 'generation' : $(this).val()},
					success: function(data)
					{
						$.each(data.result, function(k, v) {
						 	$('select[name="student"]').append('<option value="'+k+'">'+v+'</option>');
						});
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('.sub-type option[value=""]').remove();
						$('select[name="student"]').html('<option value="">{{ __('Select') }}</option>').attr('name', 'exam_sub_type[]').select2();
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
		});
	});

    function deleteStudent(id) {
		$('input[name="student_id[]"][value="'+id+'"]').closest('.student').remove();
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