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
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $data->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(null, __('Status'), 'status', $statuses, $data->status, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

								{{ Form::bsText(null, __('Date'), 'date', date('l, d-m-Y',strtotime($data->date)), __('-'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Until Date'), 'date', (empty($data->until_date)?null:date('l, d-m-Y', strtotime($data->until_date))), __('-'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Time'), 'time', (empty($data->time)?null:$data->time), __('-'), ['disabled' => '']) }}

								{{ Form::bsText((empty($data->amount_of_teacher)?'d-none':'d-block'), __('Amount Of Teacher'), 'amount_of_teacher', (empty($data->amount_of_teacher)?null:$data->amount_of_teacher), __('DD-MM-YYYY'), ['disabled' => '']) }}

								{{ Form::bsText((empty($data->amount_of_student)?'d-none':'d-block'), __('Amount of Student'), 'amount_of_student', (empty($data->amount_of_student)?null:$data->amount_of_student), __('-'), ['disabled' => '']) }}

								{{ Form::bsText((empty($data->amount_of_reguler_student)?'d-none':'d-block'), __('Amount Of Reguler Student'), 'amount_of_reguler_student', (empty($data->amount_of_reguler_student)?null:$data->amount_of_reguler_student), __('-'), ['disabled' => '']) }}

								{{ Form::bsText((empty($data->amount_of_acp_student)?'d-none':'d-block'), __('Amount Of ACP Student'), 'amount_of_acp_student', (empty($data->amount_of_acp_student)?null:$data->amount_of_acp_student), __('Amount Of ACP Student'), ['disabled' => '']) }}

								{{ Form::bsText((empty($data->activity)?'d-none':'d-block'), __('Activity'), 'activity', (empty($data->activity)?null:$data->activity), __('Activity'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Period'), 'period', (empty($data->period)?null:$data->period), __('Period'), ['disabled' => '']) }}

								{{ Form::bsTextarea(null, __('Detail'), 'detail', (empty($data->detail)?null:$data->detail), __('Detail'), ['disabled' => '']) }}

								{{ Form::bsUploadedFile(($data->submission_letter?'d-block':'d-none'), __('Submission Letter'), 'submission_letter', 'activity/submission-letter', $data->submission_letter) }}

								{{ Form::bsUploadedFile(($data->participant?'d-block':'d-none'), __('Participant'), 'participant', 'activity/participant', $data->participant) }}
                            </fieldset>
                        </div>
                        <div class="col-sm-6">
                            <fieldset>
                                <legend>{{ __('PIC') }}</legend>
                                <hr>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', (empty($data->pic)?null:$data->pic[0]->name), __('PIC Name'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', (empty($data->pic)?null:$data->pic[0]->position), __('PIC Position'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Phone Number'), 'pic_phone_number', (empty($data->pic)?null:'(+62)'.$data->pic[0]->phone_number), __('PIC Phone Number'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC email'), 'pic_email', (empty($data->pic)?null:$data->pic[0]->email), __('PIC email'), ['disabled' => '']) }}

                            </fieldset>
                        </div>
					</div>
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
		
	});
</script>
@endsection