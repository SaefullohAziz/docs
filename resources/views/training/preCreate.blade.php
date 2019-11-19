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

			{{ Form::open(['route' => 'training.preCreate', 'files' => true]) }}
				<div class="card-body">
                    {{ Form::bsSelect(null, __('Type'), 'type', $types, old('type'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}
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
			$.ajax({
				url : "{{ route('get.trainingSettingResult') }}",
				type: "POST",
				dataType: "JSON",
				data: {'_token' : '{{ csrf_token() }}', 'type' : $(this).val()},
				success: function(data)
				{
					console.log(data.result);
					if (data.result.quota == 0 ) {
						swal('{{ __("No more slot on this training.") }}', 'Please try again later!', 'error');
						$('select[name="type"]').val(null).change();
					}
					if (data.result.until_date) {
						$('select[name="type"]').next().next().remove();
						$('select[name="type"]').next().after('<span>This training open until <font class="text-danger">' + data.result.until_date + '</font></span>');
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					swal(textStatus, '', 'error');
				}
			});
		});
		$('select[name="participant"]').change(function () {
			if ($(this).val() != '') {
				if ($('[name="participant_id[]"][value="'+$(this).val()+'"]').length) {
					swal('{{ __("Participant have been selected.") }}', '', 'warning');
					$('select[name="participant"]').val(null).change();
				} else {
					$.ajax({
						url : "{{ route('get.teacher') }}",
						type: "POST",
						dataType: "JSON",
						data: {'_token' : '{{ csrf_token() }}', 'teacher' : $(this).val()},
						success: function(data)
						{
							if (data.result.teaching_status != 'yes') {
								swal('{{ __("Participant must active teaching status, try to update on teacher menu.") }}', '', 'warning');
								$('select[name="participant"]').val(null).change();
							} else {
								$('.participants').append('<li class="participant list-group-item d-flex justify-content-between align-items-center"><input type="hidden" name="participant_id[]" value="'+data.result.id+'">'+data.result.name+'<a href="javascript:void(0);" onclick="deleteParticipant('+"'"+data.result.id+"'"+')" class="badge badge-danger badge-pill" title="{{ __('Delete') }}"><i class="fas fa-trash-alt"></i></a></li>');
								$('select[name="participant"]').val(null).change();
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});
				}
			}
		});
	});
</script>
@endsection