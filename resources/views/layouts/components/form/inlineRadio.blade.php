<div class="form-group {{ $class }}">
	{{ Form::label(null, $label, ['class' => 'd-flex']) }}
	@foreach ($options as $option)
		<div class="custom-control custom-radio custom-control-inline">
			{{ Form::radio($name, $option, ($option==$value?true:false), array_merge(['class' => 'custom-control-input '.($errors->has($name) ? 'is-invalid' : ''), 'id' => $loop->iteration.'_'.$name], $attributes)) }}
			{{ Form::label($loop->iteration.'_'.$name, $option, ['class' => 'custom-control-label']) }}
		</div>
	@endforeach
	@foreach ($helpTexts as $helpText)
	    <small class="form-text text-muted">
		  {{ $helpText }}
		</small>
	@endforeach
	@if ($errors->has($name))
	    <div class="invalid-feedback" role="alert">
	    	<strong>{{ $errors->first($name) }}</strong>
	    </div>
    @endif
</div>