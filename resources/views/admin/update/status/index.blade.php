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
                                <li class="nav-item"><a href="{{ $nav['url'] }}" class="nav-link {{ ($update['slug']==$nav['slug']?'active':'') }}">{{ __($nav['title']) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                {{ Form::open(['route' => 'admin.update.status.store']) }}
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $title }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ __($update['description']) }}</p>
                            
                            <fieldset>
                                <legend>{{ __('Choose School') }}</legend>
                                <div class="row">
                                    <div class="col-sm-4">
                                        {{ Form::bsSelect(null, __('Level'), 'level', $levels, old('level'), __('Select'), ['placeholder' => __('Select')]) }}
                                    </div>
                                    <div class="col-sm-4">
                                        {{ Form::bsSelect(null, __('Status'), 'status', [], old('status'), __('Select'), ['placeholder' => __('Select')]) }}
                                    </div>
                                    <div class="col-sm-4">
                                        {{ Form::bsSelect(null, __('School'), 'school', $schools, old('school'), __('Select'), ['placeholder' => __('Select')]) }}
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="selected-schools">
                                <legend>{{ __('Selected Schools') }}</legend>
                                <ul class="nav nav-tabs" id="selectedSchoolsTab" role="tablist">
                                </ul>
                                <div class="tab-content" id="selectedSchoolsTabContent">
                                </div>
                            </fieldset>
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
            $('select[name="level"]').change(function () {
                $('select[name="status"]').html('<option value="">{{ __('Select') }}</option>');
                if ($(this).val() != '') {
                    $.ajax({
                        url : "{{ route('get.schoolStatus') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {'_token' : '{{ csrf_token() }}', 'level' : $(this).val()},
                        success: function(data)
                        {
                            $.each(data.result, function(key, value) {
                                $('select[name="status"]').append('<option value="'+key+'">'+value+'</option>');
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                           
                        }
                    });
                } 
            });

            var count = 0;
            $('select[name="school"]').change(function () {
                if ($(this).val() != '') {
                    if ($('#selected-school-'+$(this).val()).length) {
                        swal("{{ __('School have been selected.') }}", "", "warning");
                    } else {
                        count++;
                        $.ajax({
                            url : "{{ route('get.schoolStatusUpdate') }}",
                            type: "POST",
                            dataType: "JSON",
                            data: {'_token' : '{{ csrf_token() }}', 'school' : $(this).val()},
                            success: function(data)
                            {
                                $('#selectedSchoolsTab').append('<li class="nav-item"><a class="nav-link '+(count==1?'active show':'')+'" id="selected-school-'+count+'-tab" data-toggle="tab" href="#selected-school-'+data.result.school.id+'" role="tab" aria-controls="selected-school-'+count+'" aria-selected="'+(count==1?'true':'false')+'">'+data.result.school.name+'</a></li>');
                                $('#selectedSchoolsTabContent').append('<div class="tab-pane fade '+(count==1?'active show':'')+'" id="selected-school-'+data.result.school.id+'" role="tabpanel" aria-labelledby="selected-school-'+count+'-tab"><input type="hidden" name="school_id[]" value="'+data.result.school.id+'"><div class="form-group "><label for="status_id[]">Status</label><select class="form-control select2" data-placeholder="{{ __('Select') }}" style="width: 100%;" name="status_id[]"><option selected="selected" value="">{{ __('Select') }}</option></select></div></div>');
                                $.each(data.result.statuses, function(key, value) {
                                    $('#selected-school-'+data.result.school.id+' select[name="status_id[]"]').append('<option value="'+key+'">'+value+'</option>');
                                });
                            },
                            error: function (jqXHR, textStatus, errorThrown)
                            {
                            
                            }
                        });
                    }
                } 
            });
        });
    </script>
@endsection