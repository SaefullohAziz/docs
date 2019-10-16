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

			{{ Form::open(['route' => 'admin.account.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						{{ Form::bsSelect('col-sm-6', __('Type'), 'type', $types, old('type'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

						{{ Form::bsSelect('col-sm-6 ' . (old('type')=='Staff'?'d-none':'d-block'), __('School'), 'school_id', $schools, old('school_id'), __('Select'), ['placeholder' => __('Select')]) }}

						{{ Form::bsText('col-sm-6 ' . (old('type')=='School'?'d-none':'d-block'), __('Username'), 'username', old('username'), __('Username')) }}

						{{ Form::bsText('col-sm-6', __('Name'), 'name', old('name'), __('Name'), ['required' => '']) }}

						{{ Form::bsEmail('col-sm-6', __('E-Mail'), 'email', old('email'), __('E-Mail'), ['required' => '']) }}

						{{ Form::bsFile('col-sm-6', __('Photo'), 'photo', old('photo'), [], [__('Photo with JPG/PNG format up to 5MB.')]) }}

						{{ Form::bsPassword('col-sm-6', __('Password'), 'password', __('Password'), ['required' => '']) }}

						{{ Form::bsPassword('col-sm-6', __('Password Confirmation'), 'password_confirmation', __('Password Confirmation'), ['required' => '']) }}
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
		$('select[name="type"]').change(function () {
			$('input[name="username"]').prop('required', false).val('');
			$('select[name="school_id"]').prop('required', false);
			$('select[name="school_id"]').val(null).change();
			$('input[name="username"], select[name="school_id"]').parent().addClass('d-none').removeClass('d-block');
			if ($(this).val() == 'School') {
				$('select[name="school_id"]').prop('required', true);
				$('select[name="school_id"]').parent().addClass('d-block').removeClass('d-none');
			} else if ($(this).val() == 'Staff') {
				$('input[name="username"]').prop('required', true);
				$('input[name="username"]').parent().addClass('d-block').removeClass('d-none');
			}
		});
	});
</script>
@endsection