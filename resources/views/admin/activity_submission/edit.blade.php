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

			{{ Form::open(['route' => ['admin.activity.update', $activity->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                <legend>{{ __('Data') }}</legend>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, $activity->school_id, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $activity->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsText(null, __('Date'), 'date', $activity->date, __('DD-MM-YYYY'), ['required' => '']) }}

                                @if ($activity->until_date)
		                            {{ Form::bsText(null, __('Until date'), 'until_date', $activity->until_date, __('DD-MM-YYYY'), ['required' => '']) }}
                                @endif

                            	{{ Form::bsFile(null, __('Submission Letter'), 'submission_letter', $activity->submission_letter, ['' => ''], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}

                            	@if ($activity->amount_of_student)
	                            	{{ Form::bsText(null, __('Amount of Student'), 'amount_of_student', $activity->amount_of_student, __('Amount of Student'), ['required' => '']) }}
	                            @endif

	                            @if ($activity->amount_of_teacher)
	                            	{{ Form::bsText(null, __('Amount of Teacher'), 'amount_of_teacher', $activity->amount_of_teacher, __('Amount of Teacher'), ['required' => '']) }}
	                            @endif

	                            @if ($activity->amount_of_acp_student)
	                            	{{ Form::bsText(null, __('Amount of ACP-Student'), 'amount_of_acp_student', $activity->amount_of_acp_student, __('Amount of ACP-Student'), ['required' => '']) }}
	                            @endif

	                            @if ($activity->participant)
	                            	{{ Form::bsFile(null, __('Partcipant'), 'participant', $activity->participant, ['' => ''], [__('File with xls/xlsx/xlsm format up to 5MB.'), __('For participant list data.')]) }}
	                            @endif

	                            @if ($activity->activity)
	                            	{{ Form::bsText(null, __('Activity'), 'activity', $activity->activity, __('activity'), ['required' => '']) }}
	                            @endif
	                            
	                            @if ($activity->period)
	                            	{{ Form::bsText(null, __('Period'), 'period', $activity->period, __('ex: Q1 2019'), ['required' => '']) }}
                            	@endif

	                            {{ Form::bsTextarea(null, __('Detail'), 'detail', ( ! $activity->detail ) ? '-' : $activity->detail , __('Details')) }}

                            </fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $activity->pic[0]->name, __('PIC Name'), ['required' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $activity->pic[0]->position, __('PIC Position'), ['required' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $activity->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $activity->pic[0]->email, __('PIC E-Mail'), ['required' => '']) }}
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
</script>
@endsection