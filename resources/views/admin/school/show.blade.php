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
						<fieldset class="col-sm-6">
							<legend>{{ __('School Data') }}</legend>
							{{ Form::bsSelect(null, __('Type'), 'type', ['Negeri' => 'Negeri', 'Swasta' => 'Swasta'], $school->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsText(null, __('Name'), 'name', $school->name, __('Name'), ['disabled' => '']) }}

							{{ Form::bsTextarea(null, __('Address'), 'address', $school->address, __('Address'), ['disabled' => '']) }}

							{{ Form::bsSelect(null, __('Province'), 'province', $provinces, $school->province, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsSelect(null, __('Regency'), 'regency', $regencies, $school->regency, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsSelect(null, __('Police Number'), 'police_number', $policeNumbers, $school->police_number, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsText(null, __('Since'), 'since', $school->since, __('Since'), ['maxlength' => '4', 'disabled' => '']) }}

							{{ Form::bsPhoneNumber(null, __('School Phone Number'), 'school_phone_number', $school->school_phone_number, __('School Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

							{{ Form::bsEmail(null, __('School E-Mail'), 'school_email', $school->school_email, __('School E-Mail'), ['disabled' => '']) }}

							{{ Form::bsText(null, __('School Website (URL)'), 'school_web', $school->school_web, __('School Website (URL)'), ['disabled' => '']) }}

							{{ Form::bsText(null, __('Total Student'), 'total_student', $school->total_student, __('Total Student'), ['disabled' => '']) }}

							{{ Form::bsCheckboxList(null, __('Department'), 'department[]', $departments, explode(', ', $school->department), ['disabled' => '']) }}

							{{ Form::bsSelect(null, __('ISO Certificate'), 'iso_certificate', $isoCertificates, $school->iso_certificate, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsInlineRadio(null, __('Mikrotik Academy'), 'mikrotik_academy', ['Sudah' => 'Sudah', 'Belum' => 'Belum'], $school->mikrotik_academy, ['disabled' => '']) }}
						</fieldset>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Headmaster Data') }}</legend>
								{{ Form::bsText(null, __('Headmaster Name'), 'headmaster_name', $school->headmaster_name, __('Headmaster Name'), ['disabled' => ''], [__('Complete with an academic degree and or degree of expertise.')]) }}

								{{ Form::bsPhoneNumber(null, __('Headmaster Phone Number'), 'headmaster_phone_number', $school->headmaster_phone_number, __('Headmaster Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsEmail(null, __('Headmaster E-Mail'), 'headmaster_email', $school->headmaster_email, __('Headmaster E-Mail'), ['disabled' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('PIC Data') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $school->pic[0]->name, __('PIC Name'), ['disabled' => ''], [__('Complete with an academic degree and or degree of expertise.')]) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $school->pic[0]->position, __('PIC Position'), ['disabled' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $school->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsEmail(null, __('PIC E-Mail'), 'pic_email', $school->pic[0]->email, __('PIC E-Mail'), ['disabled' => '']) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Reference') }}</legend>
								{{ Form::bsCheckboxList(null, __('Reference'), 'reference[]', $references, explode(', ', $school->reference), ['disabled' => '']) }}
							</fieldset>
							@if (in_array('Dealer', explode(', ', $school->reference)))
								<fieldset class="dealer-data">
									<legend>{{ __('Dealer Data') }}</legend>
									{{ Form::bsText(null, __('Dealer Name'), 'dealer_name', $school->dealer_name, __('Dealer Name'), ['disabled' => '']) }}

									{{ Form::bsPhoneNumber(null, __('Dealer Phone Number'), 'dealer_phone_number', $school->dealer_phone_number, __('Dealer Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

									{{ Form::bsEmail(null, __('Dealer E-Mail'), 'dealer_email', $school->dealer_email, __('Dealer E-Mail'), ['disabled' => '']) }}
								</fieldset>
							@endif
							<fieldset>
								<legend>{{ __('Other Data') }}</legend>
								{{ Form::bsInlineRadio(null, 'Apakah Kepala Sekolah telah mempelajari proposal ACP?', 'proposal', ['Sudah' => 'Sudah', 'Belum' => 'Belum'], $school->mikrotik_academy, ['disabled' => '']) }}

								<div class="form-group">
									{{ Form::label(null, __('Requirement Document'), ['class' => 'd-block']) }}
									{{ link_to_route('download', __('Download'), ['dir' => encrypt('school/document'), 'file' => encrypt($school->document)], ['class' => 'btn btn-primary '.( ! isset($school->document)?'disabled':''), 'title' => __('Download'), 'target' => '_blank']) }}
									<small class="form-text text-muted">
										{{ __('File must have extension *.ZIP/*.RAR with size 5 MB or less.') }}
									</small>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
			{{ Form::close() }}

		</div>

		<div class="card card-primary">
			<div class="card-header">
				<h4>{{ __('Gallery') }}</h4>
			</div>
			<div class="card-body">
				<div class="gallery gallery-md">
					@foreach ($school->photo as $photo)
						<div class="gallery-item" data-image="{{ asset('storage/school/photo/'.$photo->name) }}" data-title="Image {{ $loop->iteration }}"></div>
					@endforeach
				</div>
			</div>
		</div>

		<div class="card card-primary chat-box" id="mychatbox">
			<div class="card-header">
				<h4>{{ __('Comments') }}</h4>
			</div>
			<div class="card-body chat-content">
				@foreach ($school->comment as $comment)
					@if ($comment->staff->id == Auth::guard('admin')->user()->id)
						<div class="chat-item chat-right" style="">
							<img src="{{ asset('storage/avatar/'.$comment->staff->avatar) }}">
							<div class="chat-details">
								<div class="chat-text">{!! html_entity_decode($comment->message) !!}</div>
								<div class="chat-time">{{ $comment->created_at }}</div>
							</div>
						</div>
						@continue
					@endif
					<div class="chat-item chat-left" style="">
						<img src="{{ asset('storage/avatar/'.$comment->staff->avatar) }}">
						<div class="chat-details">
							<div class="chat-text">{ !! html_entity_decode($comment->message) !! }</div>
							<div class="chat-time">{{ $comment->created_at }}</div>
						</div>
					</div>
				@endforeach
				@if ($school->comment->count() == 0)
					<div class="text-center">{{ __('There is no comment.') }}</div>
				@endif
			</div>
			<div class="card-footer">
				{{ Form::open(['route' => ['admin.school.comment.store', $school->id], 'files' => true]) }}
					{{ Form::bsTextarea(null, __('Message'), 'message', old('message'), __('Type a message'), ['class' => 'summernote-simple', 'required' => '']) }}
					<div class="text-center mt-4">
						{{ Form::submit(__('Send'), ['class' => 'btn btn-primary']) }}
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
@endsection