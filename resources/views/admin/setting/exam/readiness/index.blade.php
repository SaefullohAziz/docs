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
                        <h4>{{ __('Jump To') }}</h4>
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
                                <div class="col-12 col-sm-12 col-md-4">
                                    <ul class="nav nav-pills flex-column" id="examReadinessSettingTab" role="tablist">
                                        @foreach ($examReadinesses as $examReadiness)
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($loop->first?'active show':'') }}" id="exam-readiness-setting-{{ $loop->iteration }}-tab" data-toggle="tab" href="#exam-readiness-setting-{{ strtolower(str_replace(' ', '-', $examReadiness->slug)) }}" role="tab" aria-controls="{{ strtolower(str_replace(' ', '-', $examReadiness->slug)) }}" aria-selected="{{ ($loop->first?'true':'false') }}">{{ __($examReadiness->name) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <div class="tab-content no-padding" id="examReadinessSettingTabContent">
                                        @foreach ($examReadinesses as $examReadiness)
                                            <div class="tab-pane fade {{ ($loop->first?'active show':'') }}" id="exam-readiness-setting-{{ strtolower(str_replace(' ', '-', $examReadiness->slug)) }}" role="tabpanel" aria-labelledby="exam-readiness-setting-{{ $loop->iteration }}-tab">
								                {{ Form::bsInlineRadio(null, __('Status?'), $examReadiness->is_opened_slug, ['1' => __('Opened'), '0' => __('Closed')], setting($examReadiness->is_opened_slug), ['required' => '']) }}

                                                {{ Form::bsSelectRange((setting($examReadiness->is_opened_slug)=='1'?'d-block':'d-none'), __('Student Year'), $examReadiness->student_year_slug, 1, 3, setting($examReadiness->student_year_slug), __('Select'), ['placeholder' => __('Select')]) }}

								                {{ Form::bsInlineRadio((setting($examReadiness->is_opened_slug)=='1'?'d-block':'d-none'), __('SSP Student Only?'), $examReadiness->ssp_limiter_slug, ['1' => __('Yes'), '0' => __('No')], setting($examReadiness->ssp_limiter_slug)) }}

                                                {{ Form::bsSelect((setting($examReadiness->is_opened_slug)=='1'?'d-block':'d-none'), __('Department Limiter'), $examReadiness->department_limiter_slug, $departments, setting($examReadiness->department_limiter_slug), __('Select'), ['multiple' => '']) }}
                                            </div>
                                        @endforeach
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
            @foreach ($examReadinesses as $examReadiness)
                $('input[name="{{ $examReadiness->is_opened_slug }}"]').click(function () {
                    if ($('input[name="{{ $examReadiness->is_opened_slug }}"][value="1"]').is(':checked')) {
                        $('select[name="{{ $examReadiness->student_year_slug }}"], input[name="{{ $examReadiness->ssp_limiter_slug }}"], select[name="{{ $examReadiness->department_limiter_slug }}"]').closest('.form-group').removeClass('d-none').addClass('d-block');
                    } else if ($('input[name="{{ $examReadiness->is_opened_slug }}"][value="0"]').is(':checked')) {
                        $('input[name="{{ $examReadiness->ssp_limiter_slug }}"]').prop('checked', false);
                        $('select[name="{{ $examReadiness->student_year_slug }}"], select[name="{{ $examReadiness->department_limiter_slug }}"]').val(null).change();
                        $('select[name="{{ $examReadiness->student_year_slug }}"], input[name="{{ $examReadiness->ssp_limiter_slug }}"], select[name="{{ $examReadiness->department_limiter_slug }}"]').closest('.form-group').removeClass('d-block').addClass('d-none');
                    }
                });
            @endforeach
        });
    </script>
@endsection