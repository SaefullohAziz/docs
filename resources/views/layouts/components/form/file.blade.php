<div class="form-group {{ $class }}">
    {{ Form::label($name, $label) }}
    {{ Form::file($name, array_merge(['value' => $value, 'class' => 'form-control filestyle '.($errors->has($name) ? 'is-invalid' : ''), 'data-btnClass' => 'btn btn-primary', 'data-input' => 'false', 'data-badge' => 'true'], $attributes)) }}
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
	@if ($ajax)
	    <div class="invalid-feedback" role="alert">
	    	<strong></strong>
	    </div>
    @endif
</div>