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
							<legend>School Data</legend>
							{{ Form::bsSelect(null, 'Type:', 'type', ['Negeri' => 'Negeri', 'Swasta' => 'Swasta'], $school->type, 'Select', ['placeholder' => 'Select', 'disabled' => '']) }}

							{{ Form::bsText(null, 'Name:', 'name', $school->name, 'Name', ['disabled' => '']) }}

							{{ Form::bsTextarea(null, 'Address:', 'address', $school->address, 'Address', ['disabled' => '']) }}

							{{ Form::bsSelect(null, 'Province:', 'province', $provinces, $school->province, 'Select', ['placeholder' => 'Select', 'disabled' => '']) }}

							{{ Form::bsSelect(null, 'Regency:', 'regency', $regencies, $school->regency, 'Select', ['placeholder' => 'Select', 'disabled' => '']) }}

							{{ Form::bsSelect(null, 'Police Number:', 'police_number', $policeNumbers, $school->police_number, 'Select', ['placeholder' => 'Select', 'disabled' => '']) }}

							{{ Form::bsText(null, 'Since:', 'since', $school->since, 'Since', ['maxlength' => '4', 'disabled' => '']) }}

							{{ Form::bsPhoneNumber(null, 'School Phone Number:', 'school_phone_number', $school->school_phone_number, 'School Phone Number', ['maxlength' => '13', 'disabled' => '']) }}

							{{ Form::bsEmail(null, 'School E-Mail:', 'school_email', $school->school_email, 'School E-Mail', ['disabled' => '']) }}

							{{ Form::bsText(null, 'School Website (URL):', 'school_web', $school->school_web, 'School Website (URL)', ['disabled' => '']) }}

							{{ Form::bsText(null, 'Total Student:', 'total_student', $school->total_student, 'Total Student', ['disabled' => '']) }}

							{{ Form::bsCheckboxList(null, 'Department', 'department[]', $departments, explode(', ', $school->department), ['disabled' => '']) }}

							{{ Form::bsSelect(null, 'ISO Certificate:', 'iso_certificate', $isoCertificates, $school->iso_certificate, 'Select', ['placeholder' => 'Select', 'disabled' => '']) }}

							{{ Form::bsInlineRadio(null, 'Mikrotik Academy:', 'mikrotik_academy', ['Sudah', 'Belum'], $school->mikrotik_academy, ['disabled' => '']) }}
						</fieldset>
						<div class="col-sm-6">
							<fieldset>
								<legend>Headmaster Data</legend>
								{{ Form::bsText(null, 'Headmaster Name:', 'headmaster_name', $school->headmaster_name, 'Headmaster Name', ['disabled' => ''], ['Complete with an academic degree and or degree of expertise.']) }}

								{{ Form::bsPhoneNumber(null, 'Headmaster Phone Number:', 'headmaster_phone_number', $school->headmaster_phone_number, 'Headmaster Phone Number', ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsEmail(null, 'Headmaster E-Mail:', 'headmaster_email', $school->headmaster_email, 'Headmaster E-Mail', ['disabled' => '']) }}
							</fieldset>
							<fieldset>
								<legend>PIC Data</legend>
								{{ Form::bsText(null, 'PIC Name:', 'pic_name', $school->pic[0]->name, 'PIC Name', ['disabled' => ''], ['Complete with an academic degree and or degree of expertise.']) }}

								{{ Form::bsText(null, 'PIC Position:', 'pic_position', $school->pic[0]->position, 'PIC Position', ['disabled' => '']) }}

								{{ Form::bsPhoneNumber(null, 'PIC Phone Number:', 'pic_phone_number', $school->pic[0]->phone_number, 'PIC Phone Number', ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsEmail(null, 'PIC E-Mail:', 'pic_email', $school->pic[0]->email, 'PIC E-Mail', ['disabled' => '']) }}
							</fieldset>
							<fieldset>
								<legend>Reference</legend>
								{{ Form::bsCheckboxList(null, 'Reference', 'reference[]', $references, explode(', ', $school->reference), ['disabled' => '']) }}
							</fieldset>
							@if (in_array('Dealer', explode(', ', $school->reference)))
								<fieldset class="dealer-data">
									<legend>Dealer Data</legend>
									{{ Form::bsText(null, 'Dealer Name:', 'dealer_name', $school->dealer_name, 'Dealer Name', ['disabled' => '']) }}

									{{ Form::bsPhoneNumber(null, 'Dealer Phone Number:', 'dealer_phone_number', $school->dealer_phone_number, 'Dealer Phone Number', ['maxlength' => '13', 'disabled' => '']) }}

									{{ Form::bsEmail(null, 'Dealer E-Mail:', 'dealer_email', $school->dealer_email, 'Dealer E-Mail', ['disabled' => '']) }}
								</fieldset>
							@endif
							<fieldset>
								<legend>Other Data</legend>
								{{ Form::bsInlineRadio(null, 'Apakah Kepala Sekolah telah mempelajari proposal ACP?', 'proposal', ['Sudah', 'Belum'], $school->mikrotik_academy, ['disabled' => '']) }}

								<div class="form-group">
									{{ Form::label(null, 'Requirement Document', ['class' => 'd-block']) }}
									{{ link_to_route('download', 'Download', ['dir' => encrypt('school/document'), 'file' => encrypt($school->document)], ['class' => 'btn btn-primary '.(isset($school->document)?'disabled':''), 'title' => 'Download', 'target' => '_blank']) }}
									<small class="form-text text-muted">
										File must have extension *.ZIP/*.RAR with size 5 MB or less.
									</small>
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