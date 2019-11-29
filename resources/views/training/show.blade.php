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
                                <legend>{{ __('Data') }}</legend>
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $training->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(($training->type=='Basic (ToT)'||$training->type=='Adobe Photoshop'?'d-block':'d-none'), __('Implementation'), 'implementation', $implementations, $training->implementation, __('Select'), ['placeholder' => __('Select'), 'disabled' => ''], [__('This department is a department synchronized with ACP.')]) }}
                            </fieldset>
							<fieldset class="{{ (old('type')=='Basic (ToT)'?'d-block':'d-none') }}">
								<legend>{{ __('Basic (ToT)') }}</legend>
								{{ Form::bsText(null, __('Approval Code'), 'approval_code', $training->approval_code, __('Approval Code'), ['disabled' => ''], [__('Filled with AGP payment receipt number (example: MH0000001234).')]) }}

								{{ Form::bsCheckboxList(null, __('Room Type'), 'room_type[]', $roomTypes, ( ! empty($training->room_type)?explode($training->room_type, ', '):[]), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Room Size'), 'room_size', $training->room_size, __('Room Size'), ['disabled' => ''], [__('Example: 5x5x5 (LxWxH)'), __('In accordance with the provisions of the Axioo Construction Guidelines.')]) }}
							</fieldset>
							<fieldset class="{{ (old('type')=='Elektronika Dasar'?'d-block':'d-none') }}">
								<legend>{{ __('Basic Electronics') }}</legend>
								{{ Form::bsInlineRadio(null, __('Do you have assets?'), 'has_asset', ['2' => __('Already'), '1' => __('Not yet')], $training->has_asset, ['disabled' => '']) }}
							</fieldset>
							<fieldset class="{{ (old('type')=='IoT'?'d-block':'d-none') }}">
								<legend>{{ __('IoT') }}</legend>
								{{ Form::bsUploadedFile(null, __('Selection Result'), 'selection_result', 'training/selection-result', $training->selection_result) }}
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Other Data') }}</legend>
								{{ Form::bsUploadedFile(null, __('Commitment Letter'), 'commitment_letter', 'training/commitment-letter', $training->approval_letter_of_commitment_fee) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $training->pic[0]->name, __('PIC Name'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $training->pic[0]->position, __('PIC Position'), ['disabled' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $training->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $training->pic[0]->email, __('PIC E-Mail'), ['disabled' => '']) }}
							</fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset>
                                <legend>{{ __('Participant') }}</legend>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('No.') }}</th>
                                                <th>{{ __('Name') }}</th>
												<th>{{ __('Gender') }}</th>
												<th>{{ __('Position') }}</th>
												<th>{{ __('Phone Number') }}</th>
												<th>{{ __('E-Mail') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($training->participants as $participant)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $participant->name }}</td>
                                                    <td>{{ $participant->gender }}</td>
                                                    <td>{{ $participant->position }}</td>
                                                    <td>{{ $participant->phone_number }}</td>
                                                    <td>{{ $participant->email }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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