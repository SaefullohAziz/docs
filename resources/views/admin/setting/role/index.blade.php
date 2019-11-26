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
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Jump To</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills flex-column">
                            @foreach ($navs as $nav)
                                <li class="nav-item"><a href="{{ $nav['url'] }}" class="nav-link {{ ($setting['slug']==$nav['slug']?'active':'') }}">{{ __($nav['title']) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                {{ Form::open(['route' => 'admin.setting.training.store']) }}
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $title }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ __($setting['description']) }}</p>

                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        {{ Form::label('potential_staffs[]', __('Potential Users')) }}
                                        {{ Form::select('potential_staffs[]', [], null, ['class' => 'custom-select', 'size' => '10', 'multiple' => '']) }}
                                    </div>
                                </div>
                                <div class="col-sm-2 d-flex align-items-center">
                                    <div class="form-group">
                                        {{ Form::label('role', __('Role')) }}
                                        {{ Form::select('role', $roles, null, ['class' => 'custom-select', 'placeholder' => __('Select')]) }}
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        {{ Form::label('existing_staffs', __('Existing Users')) }}
                                        {{ Form::select('existing_staffs', [], null, ['class' => 'custom-select', 'size' => '10', 'multiple' => '', 'disabled' => '']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-whitesmoke text-md-center">
                            {{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
                            {{ link_to(url()->previous(),__('Cancel'), ['class' => 'btn btn-secondary']) }}
                        </div>
                    </div>
			    {{ Form::close() }}
            </div>
        </div>
	</div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('select[name="role"]').change(function () {
                $('select[name="potential_staffs[]"], select[name="existing_staffs"]').html('<option value="">{{ __('Select') }}</option>');
                if ($(this).val() != '') {
                    $.ajax({
                        url : "{{ route('get.staff') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {'_token' : '{{ csrf_token() }}', 'role' : $(this).val()},
                        success: function(data)
                        {
                            $.each(data.result, function(key, value) {
                                $('select[name="existing_staffs"]').append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            
                        }
                    });
                    $.ajax({
                        url : "{{ route('get.staff') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {'_token' : '{{ csrf_token() }}', 'not_role' : $(this).val()},
                        success: function(data)
                        {
                            $.each(data.result, function(key, value) {
                                $('select[name="potential_staffs[]"]').append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            
                        }
                    });
                }
            });
        });
    </script>
@endsection