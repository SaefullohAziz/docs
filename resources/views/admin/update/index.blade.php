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
            @foreach ($updates as $update)
                <div class="col-lg-6">
                    <div class="card card-large-icons">
                        <div class="card-icon bg-primary text-white">
                            <i class="{{ $update['icon'] }}"></i>
                        </div>
                        <div class="card-body">
                            <h4>{{ __($update['title']) }}</h4>
                            <p>{{ __($update['description']) }}</p>
                            <a href="{{ $update['url'] }}" class="card-cta">{{ __('Update') }} <i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
	</div>
</div>
@endsection