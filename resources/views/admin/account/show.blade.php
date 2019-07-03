@extends('layouts.main')

@section('content')
<div class="row mt-sm-4">
	<div class="col-12 col-md-12 col-lg-5">
		<div class="card profile-widget">
			<div class="profile-widget-header">                     
				<img alt="image" src="{{ asset('storage/admin/avatar/'.$user->avatar) }}" class="rounded-circle profile-widget-picture">
			</div>
			<div class="profile-widget-description">
				<div class="profile-widget-name">{{ $user->name }} <div class="text-muted d-inline font-weight-normal"><div class="slash"></div> {{ $user->email }}</div></div>
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
			{{ Form::open(['route' => ['admin.account.update', $user->id], 'method' => 'put', 'files' => true]) }}
			<div class="card-body">
				<div class="row">
					{{ Form::bsText('col-sm-6', 'Username:', 'username', $user->username, 'Username', ['required' => '']) }}
					{{ Form::bsText('col-sm-6', 'Name:', 'name', $user->name, 'Name', ['required' => '']) }}
					{{ Form::bsEmail('col-sm-6', 'E-Mail:', 'email', $user->email, 'E-Mail', ['required' => '']) }}
					{{ Form::bsFile('col-sm-6', 'Photo:', 'photo', null, [], ['Photo must have extension *.PNG/*.JPG with size 5 MB or less.']) }}
					{{ Form::bsPassword('col-sm-6', 'Password:', 'password', 'Password') }}
					{{ Form::bsPassword('col-sm-6', 'Password Confirmation:', 'password_confirmation', 'Password Confirmation') }}
				</div>
			</div>
			<div class="card-footer text-right">
				{{ Form::submit('Save', ['name' => 'submit', 'class' => 'btn btn-primary']) }}
			</div>
			{{ Form::close() }}
		</div>

	</div>
</div>
@endsection