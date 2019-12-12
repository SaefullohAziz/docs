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

			{{ Form::open(['route' => ['admin.grant.update', $data->id], 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, $data->school_id, __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsInlineRadio(null, __('Menerima Syarat dan Ketentuan Pendistribusian Hibah Bahan Praktek Siswa'), 'requirement', ['2' => __('Yes'), '1' => __('No')], $data->requirement, ['required' => '']) }}
                            </fieldset>
                        </div>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $data->pic[0]->name, __('PIC Name'), ['required' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $data->pic[0]->position, __('PIC Position'), ['required' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $data->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'required' => '']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $data->pic[0]->email, __('PIC E-Mail'), ['required' => '']) }}
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
	});
</script>
@endsection