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
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $activity->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(null, __('Status'), 'status', $statuses, $activity->status, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

								{{ Form::bsText(null, __('Date'), 'date', date('l, d-m-Y',strtotime($activity->date)), __('-'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Until Date'), 'date', (empty($activity->until_date)?null:date('l, d-m-Y', strtotime($activity->until_date))), __('-'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Time'), 'time', (empty($activity->time)?null:$activity->time), __('-'), ['disabled' => '']) }}

                            </fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								{{ Form::bsText((empty($activity->amount_of_teacher)?'d-none':'d-block'), __('Amount Of Teacher'), 'amount_of_teacher', (empty($activity->amount_of_teacher)?null:$activity->amount_of_teacher), __('DD-MM-YYYY'), ['disabled' => '']) }}

								{{ Form::bsText((empty($activity->amount_of_student)?'d-none':'d-block'), __('Amount Of Student'), 'amount_of_student', (empty($activity->amount_of_student)?null:$activity->amount_of_student), __('-'), ['disabled' => '']) }}

								{{ Form::bsText((empty($activity->amount_of_reguler_student)?'d-none':'d-block'), __('Amount Of Reguler Student'), 'amount_of_reguler_student', (empty($activity->amount_of_reguler_student)?null:$activity->amount_of_reguler_student), __('-'), ['disabled' => '']) }}

								{{ Form::bsText((empty($activity->amount_of_acp_student)?'d-none':'d-block'), __('Amount Of ACP Student'), 'amount_of_acp_student', (empty($activity->amount_of_acp_student)?null:$activity->amount_of_acp_student), __('Amount Of ACP Student'), ['disabled' => '']) }}

								{{ Form::bsText((empty($activity->activity)?'d-none':'d-block'), __('Activity'), 'activity', (empty($activity->activity)?null:$activity->activity), __('Activity'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Period'), 'period', (empty($activity->period)?null:$activity->period), __('Period'), ['disabled' => '']) }}

								{{ Form::bsTextarea(null, __('Detail'), 'detail', (empty($activity->detail)?null:$activity->detail), __('Detail'), ['disabled' => '']) }}

								<?php if ($activity->submission_letter): ?>
									<a href="{{ route('download', ['dir' => encrypt('activity/submission-letter'), 'file' => encrypt($activity->submission_letter)]) }}" class="btn btn-light col-12 '.( ! isset($activity->submission_letter)?'disabled':'').'" title="{{ __('Download') }}" target="_blank"><i class="fa fa-fw fa-file"></i><span class="ml-3">{{ __('Submission Letter Download') }}</span></a>
								<?php endif ?>

								<?php if ($activity->submission_letter && $activity->participant): ?>
									<div class="my-4"></div>
								<?php endif ?>

								<?php if ($activity->participant): ?>
									<a href="{{ route('download', ['dir' => encrypt('activity/participant'), 'file' => encrypt($activity->participant)]) }}" class="btn btn-light col-12 '.( ! isset($activity->participant)?'disabled':'').'" title="{{ __('Download') }}" target="_blank"><i class="fa fa-fw fa-file"></i><span class="ml-3">{{ __('Participant Download') }}</span></a>
								<?php endif ?>

							</fieldset>
                        </div>
					</div>
					<div class="row">
                        <div class="col">
                            <fieldset>
                                <legend>{{ __('P.I.C') }}</legend>
                                <hr>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', (empty($activity->pic)?null:$activity->pic[0]->name), __('PIC Name'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', (empty($activity->pic)?null:$activity->pic[0]->position), __('PIC Position'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Phone Number'), 'pic_phone_number', (empty($activity->pic)?null:'(+62)'.$activity->pic[0]->phone_number), __('PIC Phone Number'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC email'), 'pic_email', (empty($activity->pic)?null:$activity->pic[0]->email), __('PIC email'), ['disabled' => '']) }}

                            </fieldset>
                        </div>
                    </div>
                    <center>
                    	{{ link_to(url()->previous(),__('Back'), ['class' => 'btn btn-danger']) }}
                    </center>
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