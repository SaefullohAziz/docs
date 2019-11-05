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

        <div class="alert alert-danger alert-dismissible show fade">
			<div class="alert-body">
				<button class="close" data-dismiss="alert">
					<span>&times;</span>
				</button>
				{{ __('You must determine the department to be implemented and the second PIC.') }}
			</div>
		</div>

		<div class="card card-primary">
			<div class="card-header">
				<h4>{{ __('Requirements') }}</h4>
			</div>

			{{ Form::open(['route' => 'school.set.store', 'files' => true]) }}
				<div class="card-body">
                    <div class="row">
                        <fieldset class="col-sm-6">
                            <legend>{{ __('Department') }}</legend>
                            {{ Form::bsSelect(null, __('Implemented Department'), 'department', $departments, old('department'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsText((old('department')=='Lain-Lain'?'d-block':'d-none'), __('Other Department'), 'other_department', old('other_department'), __('Other Department'), [], [__("Only 1 department is permitted. Don't use commas.")]) }}
                        </fieldset>
                        <fieldset class="col-sm-6">
							<legend>{{ __('Second PIC Data') }}</legend>
							{{ Form::bsText(null, __('PIC Name'), 'name', old('name'), __('PIC Name'), ['required' => ''], [__('Complete with an academic degree and or degree of expertise.')]) }}

							{{ Form::bsText(null, __('PIC Position'), 'position', old('position'), __('PIC Position'), ['required' => '']) }}

							{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'phone_number', old('phone_number'), __('PIC Phone Number'), ['maxlength' => '13', 'required' => '']) }}

							{{ Form::bsEmail(null, __('PIC E-Mail'), 'email', old('email'), __('PIC E-Mail'), ['required' => '']) }}
                        </fieldset>
                    </div>
				</div>
				<div class="card-footer bg-whitesmoke text-center">
					{{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        $('select[name="department"]').change(function () {
            $('input[name="other_department"]').val('');
            $('input[name="other_department"]').parent().removeClass('d-block').addClass('d-none');
            if ($(this).val() == 'Lain-Lain') {
                $('input[name="other_department"]').parent().removeClass('d-none').addClass('d-block');
            } 
       });
    });
</script>
@endsection