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
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, $data->school_id, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $data->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(($data->type=='Visitasi'?'d-block':'d-none'), __('Destination'), 'destination', $destinations, $data->destination, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsCheckboxList(($data->type=='Visitasi'?'d-block':'d-none'), __('Participant'), 'participant[]', $participants, (empty($data->participant)?[]:explode(', ', $data->participant)), ['disabled' => '']) }}

                                {{ Form::bsSelect(($data->type=='Audiensi'?'d-block':'d-none'), __('Transportation'), 'transportation', $transportations, $data->transportation, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
                            </fieldset>
                            <fieldset class="{{ ($data->type=='Audiensi'?'d-block':'d-none') }}">
                            	<legend>{{ __('Selected Participant') }}</legend>
                            	<ul class="list-group list-group-flush participants">
                            		@foreach ($data->participants as $participant)
                            		<li class="participant list-group-item d-flex justify-content-between align-items-center">
                            			<input type="hidden" name="participant_id[]" value="{{ $participant->id }}">
                            			{{ $participant->name }}
                            		</li>
                            		@endforeach
                            	</ul>
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset class="{{ ($data->type=='Audiensi'?'d-block':'d-none') }}">
								<legend>{{ __('Arrival Information') }}</legend>
								{{ Form::bsText(($data->type=='Audiensi'?'d-block':'d-none'), __('Date'), 'date', (empty($data->date)?null:date('d-m-Y', strtotime($data->date))), __('Date'), ['disabled' => '']) }}

								{{ Form::bsSelect(($data->type=='Audiensi'?'d-block':'d-none'), __('Arrival Point'), 'arrival_point', $arrivalPoints, $data->arrival_point, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

								{{ Form::bsSelect(($data->type=='Audiensi'?'d-block':'d-none'), __('Contact Person'), 'contact_person', $contactPersons, optional($contactPerson)->id, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}
							</fieldset>
							<fieldset class="{{ ($data->type=='Audiensi'?'d-block':'d-none') }}">
								<legend>{{ __('Return Information') }}</legend>
								{{ Form::bsText(($data->type=='Audiensi'?'d-block':'d-none'), __('Until Date'), 'until_date', (empty($data->until_date)?null:date('d-m-Y', strtotime($data->until_date))), __('Until Date'), ['disabled' => '']) }}
							</fieldset>
							<fieldset>
								<legend></legend>
								{{ Form::bsUploadedFile(($data->type=='Visitasi'?'d-block':'d-none'), __('Submission Letter'), 'submission_letter', 'attendance/submission-letter', $data->submission_letter, [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
							</fieldset>
                        </div>
					</div>
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection