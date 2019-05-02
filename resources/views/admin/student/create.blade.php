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

			{{ Form::open(['route' => 'admin.student.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Personal Data') }}</legend>
								{{ Form::bsText(null, __('Full Name'), 'name', old('name'), __('Full Name'), ['required' => '']) }}

								{{ Form::bsText(null, __('Nickname'), 'nickname', old('nickname'), __('Nickname'), ['required' => '']) }}

								{{ Form::bsSelect(null, __('School'), 'school_id', $schools, old('school_id'), 'Select', ['placeholder' => 'Select', 'required' => '']) }}

								{{ Form::bsSelect(null, __('Province'), 'province', $provinces, old('province'), 'Select', ['placeholder' => 'Select', 'required' => '']) }}

								{{ Form::bsSelect(null, __('School Year'), 'school_year', $schoolYears, old('school_year'), 'Select', ['placeholder' => 'Select', 'required' => '']) }}

								{{ Form::bsText(null, __('NISN'), 'nisn', old('nisn'), __('NISN'), ['required' => '']) }}
							</fieldset>
						</div>
						<div class="col-sm-6"></div>
					</div>
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