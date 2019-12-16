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

        <div class="row">
            @foreach ($settings as $setting)
                <div class="col-lg-6">
                    <div class="card card-large-icons shadow-sm">
                        <div class="card-icon bg-primary text-white">
                            <i class="{{ $setting['icon'] }}"></i>
                        </div>
                        <div class="card-body">
                            <h4>{{ __($setting['title']) }}</h4>
                            <p>{{ __($setting['description']) }}</p>
                            <a href="{{ $setting['url'] }}" class="card-cta">{{ __('Change Setting') }} <i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
	</div>
</div>
@endsection