<div class="form-group {{ $class }}">
	{{ Form::label(null, $label) }}
	<div class="custom-control custom-checkbox">
		{{ Form::checkbox($name, $option, ($option==$value?true:false), array_merge(['class' => 'custom-control-input '.($errors->has($name) ? 'is-invalid' : ''), 'id' => $name], $attributes)) }}
		{{ Form::label($name, $text, ['class' => 'custom-control-label']) }}
	</div>
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