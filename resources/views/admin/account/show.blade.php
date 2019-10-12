@extends('layouts.main')

@section('content')
<div class="row mt-sm-4">
	<div class="col-12 col-md-12 col-lg-5">
		<div class="card profile-widget">
			<div class="profile-widget-header">                     
				<img alt="image" src="{{ asset($data->avatar) }}" class="rounded-circle profile-widget-picture">
			</div>
			<div class="profile-widget-description">
				<div class="profile-widget-name">{{ $data->name }} <div class="text-muted d-inline font-weight-normal"><div class="slash"></div> {{ $data->email }}</div></div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-12 col-lg-7">

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

		<div class="card">
			<div class="card-header">
				<h4>Edit Profile</h4>
			</div>
			{{ Form::open(['route' => ['admin.account.update', $data->id], 'method' => 'put', 'files' => true]) }}
			<div class="card-body">
				<div class="row">
					{{ Form::bsText('col-sm-6', __('Username'), 'username', $data->username, __('Username'), ['required' => '']) }}

					{{ Form::bsText('col-sm-6', __('Name'), 'name', $data->name, __('Name'), ['required' => '']) }}

					{{ Form::bsEmail('col-sm-6', __('E-Mail'), 'email', $data->email, __('E-Mail'), ['required' => '']) }}

					{{ Form::bsFile('col-sm-6', __('Photo'), 'photo', null, [], [__('Photo with JPG/PNG format up to 5MB.')]) }}

					{{ Form::bsPassword('col-sm-6', __('Password'), 'password', __('Password')) }}

					{{ Form::bsPassword('col-sm-6', __('Password Confirmation'), 'password_confirmation', __('Password Confirmation')) }}
				</div>
			</div>
			<div class="card-footer text-right">
				{{ Form::submit(__('Save'), ['name' => 'submit', 'class' => 'btn btn-primary']) }}
			</div>
			{{ Form::close() }}
		</div>

	</div>
</div>
@endsection