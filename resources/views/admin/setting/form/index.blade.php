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
                {{ Form::open(['route' => 'admin.setting.form.store']) }}
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $title }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ __($setting['description']) }}</p>
                            
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-4">
                                    <ul class="nav nav-pills flex-column" id="formSettingTab" role="tablist">
                                        @foreach ($forms as $form)
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($loop->first?'active show':'') }}" id="form-setting-{{ $loop->iteration }}-tab" data-toggle="tab" href="#form-setting-{{ strtolower(str_replace(' ', '-', $form->name)) }}" role="tab" aria-controls="{{ strtolower(str_replace(' ', '-', $form->name)) }}" aria-selected="{{ ($loop->first?'true':'false') }}">{{ __($form->name) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <div class="tab-content no-padding" id="formSettingTabContent">
                                        @foreach ($forms as $form)
                                            <div class="tab-pane fade {{ ($loop->first?'active show':'') }}" id="form-setting-{{ strtolower(str_replace(' ', '-', $form->name)) }}" role="tabpanel" aria-labelledby="form-setting-{{ $loop->iteration }}-tab">
								                {{ Form::bsInlineRadio(null, __('Status?'), $form->status_slug, ['1' => __('Opened'), '0' => __('Closed')], setting($form->status_slug), ['required' => '']) }}
                                                <fieldset class="{{ $form->limiter_slug }}-set {{ (setting($form->status_slug)==1?'d-block':'d-none') }}">
                                                    <legend>{{ __('Limitation') }}</legend>
                                                    <div class="row">
                                                        {{ Form::bsSelect('col-12', __('Limiter'), $form->limiter_slug, $formLimiters, setting($form->limiter_slug), __('Select'), ['placeholder' => __('Select'), (setting($form->status_slug)==1?'required':'') => '']) }}

								                        {{ Form::bsSelectRange('col-sm-6 ' . (setting($form->limiter_slug)=='Quota'||setting($form->limiter_slug)=='Both'?'d-block':'d-none'), __('Quota'), $form->quota_limit_slug, 1, 100, setting($form->quota_limit_slug), __('Select'), ['placeholder' => __('Select'), (setting($form->limiter_slug)=='Quota'||setting($form->limiter_slug)=='Both'?'required':'') => '']) }}

								                        {{ Form::bsDatetime('col-sm-6 ' . (setting($form->limiter_slug)=='Datetime'||setting($form->limiter_slug)=='Both'?'d-block':'d-none'), __('Datetime'), $form->time_limit_slug, setting($form->time_limit_slug), __('Datetime'), [(setting($form->limiter_slug)=='Datetime'||setting($form->limiter_slug)=='Both'?'required':'') => '']) }}
                                                    </div>
                                                </fieldset>
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
            @foreach ($forms as $form)
                $('input[name="{{ $form->status_slug }}"]').click(function () {
                    if ($('input[name="{{ $form->status_slug }}"][value="1"]').is(':checked')) {
                        $('select[name="{{ $form->limiter_slug }}"]').prop('required', true);
                        $('.{{ $form->limiter_slug }}-set').removeClass('d-none').addClass('d-block');
                    } else if ($('input[name="{{ $form->status_slug }}"][value="0"]').is(':checked')) {
                        $('.{{ $form->limiter_slug }}-set input').prop('required', false).val('');
                        $('.{{ $form->limiter_slug }}-set select').prop('required', false);
                        $('.{{ $form->limiter_slug }}-set select').val(null).change();
                        $('.{{ $form->limiter_slug }}-set').removeClass('d-block').addClass('d-none');
                    }
                });

                $('select[name="{{ $form->limiter_slug }}"]').change(function () {
                    $('select[name="{{ $form->quota_limit_slug }}"]').val(null).change();
                    $('select[name="{{ $form->quota_limit_slug }}"]').prop('required', false);
                    $('input[name="{{ $form->time_limit_slug }}"]').val('').prop('required', false);
                    $('select[name="{{ $form->quota_limit_slug }}"], input[name="{{ $form->time_limit_slug }}"]').parent().removeClass('d-block').addClass('d-none');
                    if ($(this).val() == 'Quota') {
                        $('select[name="{{ $form->quota_limit_slug }}"]').prop('required', true);
                        $('select[name="{{ $form->quota_limit_slug }}"]').parent().removeClass('d-none').addClass('d-block');
                    } else if ($(this).val() == 'Datetime') {
                        $('input[name="{{ $form->time_limit_slug }}"]').val('').prop('required', true);
                        $('input[name="{{ $form->time_limit_slug }}"]').parent().removeClass('d-none').addClass('d-block');
                    } else if ($(this).val() == 'Both') {
                        $('select[name="{{ $form->quota_limit_slug }}"]').prop('required', true);
                        $('input[name="{{ $form->time_limit_slug }}"]').val('').prop('required', true);
                        $('select[name="{{ $form->quota_limit_slug }}"], input[name="{{ $form->time_limit_slug }}"]').parent().removeClass('d-none').addClass('d-block');
                    }
                });
            @endforeach
        });
    </script>
@endsection