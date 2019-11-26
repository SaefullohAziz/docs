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
                                    <ul class="nav nav-pills flex-column" id="formSettingTab" role="tablist">
                                        @foreach ($forms as $form)
                                            <li class="nav-item">
                                                <a class="nav-link {{ ($loop->first?'active show':'') }}" id="form-setting-{{ $loop->iteration }}-tab" data-toggle="tab" href="#form-setting-{{ strtolower(str_replace(' ', '-', $form->slug)) }}" role="tab" aria-controls="{{ strtolower(str_replace(' ', '-', $form->slug)) }}" aria-selected="{{ ($loop->first?'true':'false') }}">{{ __($form->name) }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-12 col-md-8">
                                    <div class="tab-content no-padding" id="formSettingTabContent">
                                        @foreach ($forms as $form)
                                            <div class="tab-pane fade {{ ($loop->first?'active show':'') }}" id="form-setting-{{ strtolower(str_replace(' ', '-', $form->slug)) }}" role="tabpanel" aria-labelledby="form-setting-{{ $loop->iteration }}-tab">
								                {{ Form::bsInlineRadio(null, __('Status?'), $form->status_slug, ['1' => __('Opened'), '0' => __('Closed')], setting($form->status_slug), ['required' => '']) }}
                                                <fieldset class="{{ $form->limiter_slug }}-set {{ (setting($form->status_slug)==1?'d-block':'d-none') }}">
                                                    <legend>{{ __('Limitation') }}</legend>
                                                    <hr>
                                                    <div class="row">
                                                        {{ Form::bsSelect('col-12', __('Limiter'), $form->limiter_slug, $formLimiters, setting($form->limiter_slug), __('Select'), ['placeholder' => __('Select'), (setting($form->status_slug)==1?'required':'') => ''], [$registerredSum[$form->name]['total']. " Was registerred since last setting"]) }}

								                        {{ Form::bsSelectRange('col-sm-6 ' . (setting($form->limiter_slug)=='Quota'||setting($form->limiter_slug)=='Both'?'d-block':'d-none'), __('Quota'), $form->quota_limit_slug, 1, 100, setting($form->quota_limit_slug), __('Select'), ['placeholder' => __('Select'), (setting($form->limiter_slug)=='Quota'||setting($form->limiter_slug)=='Both'?'required':'') => '']) }}

								                        {{ Form::bsDatetime('col-sm-6 ' . (setting($form->limiter_slug)=='Datetime'||setting($form->limiter_slug)=='Both'?'d-block':'d-none'), __('Datetime'), $form->time_limit_slug, setting($form->time_limit_slug), __('Datetime'), [(setting($form->limiter_slug)=='Datetime'||setting($form->limiter_slug)=='Both'?'required':'') => '']) }}
                                                    </div>

                                                    {{ Form::bsInlineRadio((setting($form->limiter_slug)=='Quota'||setting($form->limiter_slug)=='Both'?'d-block':'d-none'), __('Limit By School Based Condition ?'), $form->slug.'_school_based_limit', ['1' => __('Yes'), '0' => __('No')], (! empty(json_decode(setting($form->school_level_slug))) ? 1 : 0), ['required' => '']) }}
                                                    <fieldset class="{{$form->slug.'_school_based_limit'}}-set {{ (!empty(json_decode(setting($form->school_level_slug))) ?'d-block':'d-none') }}">
                                                        <legend>{{ __('School Based Limitation') }}</legend>
                                                        <hr>
                                                        <div class="row">
                                                            {{ Form::bsSelect('col-12', __('Limit by school level'), $form->school_level_slug.'[]', $schoolLevels, collect(json_decode(setting($form->school_level_slug)))->toArray(), __('Select'), ['placeholder' => __('Select'), (setting($form->status_slug)==1?'required':'') => '', 'multiple' => '']) }}

                                                            @foreach ($schoolLevels as $schoolLevel)
                                                                {{ Form::bsSelectRange('col-sm-6 ' . (key_exists($schoolLevel, collect(json_decode(setting($form->limit_by_level_slug)))->toArray() )?'d-block':'d-none'), __($schoolLevel), $form->limit_by_level_slug.'['.$schoolLevel.']', 1, 100, (key_exists($schoolLevel, collect(json_decode(setting($form->limit_by_level_slug)))->toArray() )) ? collect(json_decode(setting($form->limit_by_level_slug)))->toArray()[$schoolLevel] : null, __('Select'), ['placeholder' => __('Select'), ( key_exists($schoolLevel, collect(json_decode(setting($form->limit_by_level_slug)))->toArray() )?'required':'') => '']) }}
                                                            @endforeach
                                                        </div>

                                                            {{ Form::bsInlineRadio(null, __('Limit By School Implementation?'), $form->slug.'_school_implementation_limit', ['1' => __('Yes'), '0' => __('No')], (! empty(json_decode(setting($form->school_implementation_slug))) ? 1 : 0), ['required' => '']) }}

                                                                <fieldset class="{{$form->slug.'_school_implementation_limit'}}-set {{ (! empty(json_decode(setting($form->school_implementation_slug))) ?'d-block':'d-none') }}">
                                                                    <legend>{{ __('School implementation Limitation') }}</legend>
                                                                    <hr>
                                                                    <div class="row">

                                                                    {{ Form::bsSelect('col-12', __('Limit by school implementation'), $form->school_implementation_slug."[]", $schoolImplementations, collect(json_decode(setting($form->school_implementation_slug)))->toArray(), __('Select'), ['placeholder' => __('Select'), (setting($form->status_slug)==1?'required':'') => '', 'multiple' => '']) }}

                                                                    @foreach ($schoolImplementations as $implementation)
                                                                        {{ Form::bsSelectRange('col-sm-4 ' . (in_array($implementation, collect(json_decode(setting($form->school_implementation_slug)))->toArray() )?'d-block':'d-none'), __($implementation), $form->limit_by_implementation_slug.'['.$implementation.']', 1, 100, key_exists($implementation, collect(json_decode(setting($form->limit_by_implementation_slug)))->toArray() ) ? collect(json_decode(setting($form->limit_by_implementation_slug)))->toArray()[$implementation]:null, __('Select'), ['placeholder' => __('Select'), (array_keys(explode(':', in_array($implementation, collect(json_decode(setting($form->limit_by_implementation_slug)))->toArray()  )), $implementation)?'required':'') => ''],[$registerredSum[$form->name][$implementation], 'registerred']) }}
                                                                    @endforeach
                                                                    </div>
                                                                </fieldset>
                                                    </fieldset>
                                                </fieldset>

                                                <fieldset class="{{ $form->limiter_slug }}-set {{ (setting($form->status_slug)==1?'d-block':'d-none') }}">
                                                    <legend>{{ __('Prices') }}</legend>
                                                    <hr>
                                                    <div class="row">
                                                        {{ Form::bsText('col-12', __('Default (2 Participant)'), $form->default_participant_price_slug, setting($form->default_participant_price_slug), __('Exc : 8000000'), [(setting($form->status_slug)==1?'required':'') => '']) }}

                                                        {{ Form::bsText('col-12', __('Additional participant price'), $form->more_participant_slug, setting($form->more_participant_slug), __('Exc : 2000000'),[], [__("Leave blank to deactivate additional participants or fill in zero to make it free.")]) }}

                                                        {{ Form::bsText('col-12', __('Prices outside the conditions'), $form->unimplementation_scholl_price_slug, setting($form->unimplementation_scholl_price_slug), __('Exc : 8000000'), [(setting($form->status_slug)==1?'required':'') => '']) }}
								                       
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

                $('[name="{{$form->slug}}_school_based_limit"]').click(function () {
                    if ($(this).val() == 1)
                    {
                        $(".{{$form->slug.'_school_based_limit'}}-set").removeClass('d-none').addClass('d-block');
                        $('[name="{{$form->school_level_slug}}[]"]').prop('required', true).prop('disabled', false);
                    }
                    else {
                        $(".{{$form->slug.'_school_based_limit'}}-set").removeClass('d-block').addClass('d-none');
                        $('[name="{{$form->school_level_slug}}[]"]').prop('required', false).prop('disabled', true);
                    }
                });

                $('[name="{{$form->slug}}_school_implementation_limit"]').click(function () {
                    if ($(this).val() == 1)
                    {
                        $(".{{$form->slug.'_school_implementation_limit'}}-set").removeClass('d-none').addClass('d-block');
                        $('[name="{{$form->school_implementation_slug}}[]"]').prop('required', true).prop('disabled', false);
                    }
                    else {
                        $(".{{$form->slug.'_school_implementation_limit'}}-set").removeClass('d-block').addClass('d-none');
                        $('[name="{{$form->school_implementation_slug}}[]"]').prop('required', false).prop('disabled', true);
                    }
                });

                $('select[name="{{ $form->limiter_slug }}"]').change(function () {
                    $('select[name="{{ $form->quota_limit_slug }}"]').val(null).change();
                    $('select[name="{{ $form->quota_limit_slug }}"]').prop('required', false);
                    $('input[name="{{ $form->time_limit_slug }}"]').val('').prop('required', false);
                    $('select[name="{{ $form->quota_limit_slug }}"], input[name="{{ $form->time_limit_slug }}"]').parent().removeClass('d-block').addClass('d-none');
                    $('[name="{{ $form->slug }}_school_based_limit"]').closest('.form-group').removeClass('d-block').addClass('d-none');
                    if ($(this).val() == 'Quota') {
                        $('select[name="{{ $form->quota_limit_slug }}"]').prop('required', true);
                        $('select[name="{{ $form->quota_limit_slug }}"]').parent().removeClass('d-none').addClass('d-block');
                        $('[name="{{ $form->slug }}_school_based_limit"]').closest('.form-group').removeClass('d-none').addClass('d-block');
                    } else if ($(this).val() == 'Datetime') {
                        $('input[name="{{ $form->time_limit_slug }}"]').val('').prop('required', true);
                        $('input[name="{{ $form->time_limit_slug }}"]').parent().removeClass('d-none').addClass('d-block');
                    } else if ($(this).val() == 'Both') {
                        $('select[name="{{ $form->quota_limit_slug }}"]').prop('required', true);
                        $('input[name="{{ $form->time_limit_slug }}"]').val('').prop('required', true);
                        $('select[name="{{ $form->quota_limit_slug }}"], input[name="{{ $form->time_limit_slug }}"]').parent().removeClass('d-none').addClass('d-block');
                        $('[name="{{ $form->slug }}_school_based_limit"]').closest('.form-group').removeClass('d-none').addClass('d-block');
                    }
                });

                $('select[name="{{ $form->school_level_slug }}[]"]').change(function () {
                    let values = $(this).val();
                    @foreach ($schoolLevels as $schoolLevel)
                    if( ! inArray("<?= $schoolLevel; ?>", values)){
            			$('[name="{{ $form->limit_by_level_slug }}[<?= $schoolLevel; ?>]"]').val(null).change().prop('required', false);
            			$('[name="{{ $form->limit_by_level_slug }}[<?= $schoolLevel; ?>]"]').parent().removeClass('d-block').addClass('d-none');
                    }
                    @endforeach

                    values.forEach(function(value){
            			$('[name="{{ $form->limit_by_level_slug }}['+value+']"]').prop('required', true);
            			$('[name="{{ $form->limit_by_level_slug }}['+value+']"]').parent().removeClass('d-none').addClass('d-block');
            		});
                });

            	$('select[name="{{ $form->school_implementation_slug }}[]"]').change(function () {
            		let values = $(this).val();

            		@foreach ($schoolImplementations as $implementation)
                    if( ! inArray("<?= $implementation; ?>", values)){
            			$('[name="{{ $form->limit_by_implementation_slug }}[<?= $implementation; ?>]"]').val(null).change().prop('required', false);
            			$('[name="{{ $form->limit_by_implementation_slug }}[<?= $implementation; ?>]"]').parent().removeClass('d-block').addClass('d-none');
                    }
            		@endforeach

            		values.forEach(function(value){
            			$('[name="{{ $form->limit_by_implementation_slug }}['+value+']"]').prop('required', true);
            			$('[name="{{ $form->limit_by_implementation_slug }}['+value+']"]').parent().removeClass('d-none').addClass('d-block');
            		});
            	});
            @endforeach
        });

        function inArray(key, array) {
            var length = array.length;
            for(var i = 0; i < length; i++) {
                if(array[i] == key) return true;
            }
            return false;
        }
    </script>
@endsection