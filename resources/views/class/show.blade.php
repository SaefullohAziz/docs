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

			{{ Form::open(['url' => '#', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
                                {{ Form::bsSelect(null, __('Department'), 'department_id', $departments, $data->department_id, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsText(null, __('Generation'), 'generation', $data->generation, __('Generation'), ['disabled' => '']) }}
                            </fieldset>
                        </div>
						<div class="col-sm-6">
							<fieldset>
                                {{ Form::bsText(null, __('School Year'), 'school_year', $data->school_year, __('School Year'), ['disabled' => '']) }}

                                {{ Form::bsText(null, __('Grade'), 'grade', $data->grade, __('Grade'), ['disabled' => '']) }}
                            </fieldset>
					</div>
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>

</script>
@endsection