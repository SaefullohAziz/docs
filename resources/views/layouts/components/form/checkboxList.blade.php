<div class="form-group {{ $class }}">
	{{ Form::label(null, $label) }}
	@foreach ($options as $option)
		<div class="custom-control custom-checkbox">
			{{ Form::checkbox($name, $option, (in_array($option, $value)?true:false), array_merge(['class' => 'custom-control-input '.($errors->has($name) ? 'is-invalid' : ''), 'id' => $loop->iteration.'_'.$name], $attributes)) }}
			{{ Form::label($loop->iteration.'_'.$name, $option, ['class' => 'custom-control-label']) }}
		</div>
	@endforeach
	@foreach ($helpTexts as $helpText)
	    <small class="form-text text-muted">
		  {{ $helpText }}
		</small>
	@endforeach
	@if ($errors->has(str_replace('[]', '', $name)))
	    <small class="text-danger" role="alert">
	    	<strong>{{ $errors->first(str_replace('[]', '', $name)) }}</strong>
	    </small>
    @endif
</div>