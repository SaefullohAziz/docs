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

			{{ Form::open(['route' => ['admin.teacher.update', $data->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
								<legend></legend>
								{{ Form::bsSelect(null, __('School'), 'school_id', $schools, $data->school_id, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('NIP'), 'nip', $data->nip, __('NIP')) }}

								{{ Form::bsText(null, __('Full Name'), 'name', $data->name, __('Full Name'), ['required' => '']) }}

								{{ Form::bsText(null, __('Date of Birth'), 'date_of_birth', '01-01-1990', __('Date Of Birth'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('Gender'), 'gender', ['Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan'], $data->gender, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

								{{ Form::bsText(null, __('Position'), 'position', $data->position, __('Position'), ['required' => '']) }}

								{{ Form::bsInlineRadio(null, __('Teaching Status?'), 'teaching_status', ['yes' => __('Yes'), 'No' => __('No')], $data->teaching_status, ['required' => '']) }}
							</fieldset>
						</div>
						<div class="col-sm-6">
							<fieldset>
								<legend></legend>
								{{ Form::bsEmail(null, __('E-Mail'), 'email', $data->email, __('E-Mail'), ['required' => ''], [__('Required to use Gmail')]) }}

								{{ Form::bsPhoneNumber(null, __('Phone Number'), 'phone_number', $data->phone_number, __('Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsTextarea(null, __('Address'), 'address', $data->address, __('Address')) }}

								{{ Form::bsFile(null, __('Photo'), 'photo', old('photo'), [], [__('Photo with JPG/PNG format up to 5MB.')]) }}
							</fieldset>
						</div>
					</div>
					{{ Form::bsCheckbox(null, null, 'terms', 'Agree', __('Data filled above is true and can be accounted for.'), old('terms'), ['required' => '']) }}
				</div>
				<div class="card-footer bg-whitesmoke text-center">
					{{ Form::submit(__('Save'), ['name' => 'submit', 'class' => 'btn btn-primary']) }}
					{{ link_to(url()->previous(), __('Cancel'), ['class' => 'btn btn-danger']) }}
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
		var cleavePN = new Cleave('[name="phone_number"]', {
			phone: true,
			phoneRegionCode: 'id'
		});
	});
</script>
@endsection