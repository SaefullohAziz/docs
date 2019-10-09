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

			{{ Form::open(['route' => 'attendance.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, old('type'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsSelect((empty(old('type'))||old('type')=='Visitasi'?'d-block':'d-none'), __('Destination'), 'destination', $destinations, old('destination'), __('Select'), ['placeholder' => __('Select')]) }}

                                {{ Form::bsCheckboxList((empty(old('type'))||old('type')=='Visitasi'?'d-block':'d-none'), __('Participant'), 'participant[]', $participants) }}

                                {{ Form::bsSelect((empty(old('type'))||old('type')=='Audiensi'?'d-block':'d-none'), __('Transportation'), 'transportation', $transportations, old('transportation'), __('Select'), ['placeholder' => __('Select')]) }}
                            </fieldset>
                            <fieldset class="{{ (empty(old('type'))||old('type')=='Audiensi'?'d-block':'d-none') }}">
								<legend>{{ __('Participant') }}</legend>
								{{ Form::bsSelect(null, __('Participant'), 'select_participant', [], old('select_participant'), __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
								<fieldset>
									<legend>{{ __('Selected Participant') }}</legend>
									<ul class="list-group list-group-flush participants">

									</ul>
									@if ($errors->has('participant_id'))
										<div class="text-danger">
											<strong>{{ $errors->first('participant_id') }}</strong>
										</div>
									@endif
								</fieldset>
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset class="{{ (empty(old('type'))||old('type')=='Audiensi'?'d-block':'d-none') }}">
								<legend>{{ __('Arrival Information') }}</legend>
								{{ Form::bsText((empty(old('type'))||old('type')=='Audiensi'?'d-block':'d-none'), __('Date'), 'date', old('date'), __('Date'), []) }}

								{{ Form::bsSelect((empty(old('type'))||old('type')=='Audiensi'?'d-block':'d-none'), __('Arrival Point'), 'arrival_point', $arrivalPoints, old('arrival_point'), __('Select'), ['placeholder' => __('Select')]) }}

								{{ Form::bsSelect((empty(old('type'))||old('type')=='Audiensi'?'d-block':'d-none'), __('Contact Person'), 'contact_person', [], old('contact_person'), __('Select'), ['placeholder' => __('Select')]) }}
							</fieldset>
							<fieldset class="{{ (empty(old('type'))||old('type')=='Audiensi'?'d-block':'d-none') }}">
								<legend>{{ __('Return Information') }}</legend>
								{{ Form::bsText((empty(old('type'))||old('type')=='Audiensi'?'d-block':'d-none'), __('Until Date'), 'until_date', old('until_date'), __('Until Date'), []) }}
							</fieldset>
							<fieldset>
								<legend></legend>
								{{ Form::bsFile((empty(old('type'))||old('type')=='Visitasi'?'d-block':'d-none'), __('Submission Letter'), 'submission_letter', old('submission_letter'), [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
							</fieldset>
                        </div>
					</div>
				</div>
				<div class="card-footer bg-whitesmoke text-center">
					{{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
					{{ link_to(route('attendance.index'),__('Cancel'), ['class' => 'btn btn-danger']) }}
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
		$('input[name="date"], input[name="until_date"]').keypress(function(e) {
            e.preventDefault();
        }).daterangepicker({
        	locale: {format: 'DD-MM-YYYY'},
        	singleDatePicker: true,
      	});

      	$('select[name="school_id"]').change(function () {
      		$('.participants').html('');
			$('select[name="select_participant"]').prop('disabled', true).html('<option value="">{{ __('Select') }}</option>');
      		if ($(this).val() != '') {
      			if ($('select[name="type"]').val() == 'Audiensi') {
					selectParticipant();
      			}
      		}
		});

        $('select[name="type"]').change(function () {
        	$('select[name="destination"], select[name="transportation"], select[name="arrival_point"], select[name="contact_person"]').val(null).change();
       		$('input[name="submission_letter"], input[name="date"], input[name="until_date"]').val('');
        	$('input[name="participant[]"]').prop('checked', false);
        	$('.participants').html('');
			$('select[name="select_participant"]').prop('disabled', true).html('<option value="">{{ __('Select') }}</option>');
        	if ($(this).val() != '') {
        		if ($(this).val() == 'Audiensi') {
        			$('select[name="destination"], input[name="submission_letter"]').parent().addClass('d-none').removeClass('d-block');
        			$('input[name="participant[]"]').parent().parent().addClass('d-none').removeClass('d-block');
        			$('select[name="destination"], input[name="submission_letter"]').prop('required', false);
        			$('select[name="transportation"], select[name="arrival_point"], select[name="contact_person"]').parent().addClass('d-block').removeClass('d-none');
        			$('select[name="select_participant"], input[name="date"], input[name="until_date"]').parent().parent().addClass('d-block').removeClass('d-none');
        			$('select[name="transportation"], select[name="arrival_point"], select[name="contact_person"], input[name="date"], input[name="until_date"]').prop('required', true);
        			if ($('select[name="school_id"]').val() != '') {
        				selectParticipant();
        			}
        		} else if ($(this).val() == 'Visitasi') {
        			$('select[name="transportation"], select[name="arrival_point"], select[name="contact_person"]').parent().addClass('d-none').removeClass('d-block');
        			$('select[name="select_participant"], input[name="date"], input[name="until_date"]').parent().parent().addClass('d-none').removeClass('d-block');
        			$('select[name="transportation"], select[name="arrival_point"], select[name="contact_person"], input[name="date"], input[name="until_date"]').prop('required', false);
        			$('select[name="destination"], input[name="submission_letter"]').parent().addClass('d-block').removeClass('d-none');
        			$('input[name="participant[]"]').parent().parent().addClass('d-block').removeClass('d-none');
        			$('select[name="destination"], input[name="submission_letter"]').prop('required', true);
        		}
        	}
		});

		$('select[name="select_participant"]').change(function () {
	    	if ($(this).val() != '') {
	    		if ($('[name="participant_id[]"][value="'+$(this).val()+'"]').length) {
					swal('{{ __("Participant have been selected.") }}', '', 'warning');
					$('select[name="select_participant"]').val(null).change();
				} else {
					$.ajax({
						url : "{{ route('get.teacher') }}",
						type: "POST",
						dataType: "JSON",
						data: {'_token' : '{{ csrf_token() }}', 'teacher' : $(this).val()},
						success: function(data)
						{
							$('.participants').append('<li class="participant list-group-item d-flex justify-content-between align-items-center"><input type="hidden" name="participant_id[]" value="'+data.result.id+'">'+data.result.name+'<a href="javascript:void(0);" onclick="deleteParticipant('+"'"+data.result.id+"'"+')" class="badge badge-danger badge-pill" title="{{ __('Delete') }}"><i class="fas fa-trash-alt"></i></a></li>');
							$('select[name="contact_person"]').append('<option value="'+data.result.id+'">'+data.result.name+'</option>');
							$('select[name="select_participant"]').val(null).change();
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});
				}
	    	}
	    });
	});

	function selectParticipant() {
		$('select[name="select_participant"]').prop('disabled', false);
		$.ajax({
			url : "{{ route('get.teacher') }}",
			type: "POST",
			dataType: "JSON",
			data: {'_token' : '{{ csrf_token() }}', 'school' : $('select[name="school_id"]').val()},
			success: function(data)
			{
				$.each(data.result, function(key, value) {
					$('select[name="select_participant"]').append('<option value="'+value.id+'">'+value.name+'</option>');
				});
			},
			error: function (jqXHR, textStatus, errorThrown)
			{
				swal("{{ __('Failed!') }}", "", "warning");
			}
		});
	}\
</script>
@endsection