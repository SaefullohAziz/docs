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

			{{ Form::open(['route' => ['admin.account.school.update', $data->id], 'method' => 'put', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
                        {{ Form::bsText('col-sm-6', __('Username'), 'username', $data->username, __('Username'), ['disabled' => '']) }}

                        {{ Form::bsText('col-sm-6', __('Name'), 'name', $data->name, __('Name'), ['disabled' => '']) }}

                        {{ Form::bsEmail('col-sm-6', __('E-Mail'), 'email', $data->email, __('E-Mail'), ['required' => '']) }}

                        {{ Form::bsFile('col-sm-6', __('Photo'), 'photo', null, [], [__('Photo with JPG/PNG format up to 5MB.')]) }}

                        {{ Form::bsPassword('col-sm-6', __('Password'), 'password', __('Password')) }}

                        {{ Form::bsPassword('col-sm-6', __('Password Confirmation'), 'password_confirmation', __('Password Confirmation')) }}
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