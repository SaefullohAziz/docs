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
                                {{ Form::bsInlineRadio(null, __('Menerima Syarat dan Ketentuan Pendistribusian Hibah Bahan Praktek Siswa'), 'requirement', ['2' => __('Yes'), '1' => __('No')], $data->requirement, ['disabled' => '']) }}

								{{ Form::bsTextarea(null, __('Address'), 'address', $data->school->address, __('Address'), ['disabled' => '']) }}
                            </fieldset>
                        </div>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $data->pic[0]->name, __('PIC Name'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $data->pic[0]->position, __('PIC Position'), ['disabled' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $data->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $data->pic[0]->email, __('PIC E-Mail'), ['disabled' => '']) }}
							</fieldset>
                        </div>
					</div>
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection