<div class="form-group {{ $class }}">
    {{ Form::label(null, $label, ['class' => 'control-label']) }}
    {{ Form::hidden($name, $value, $attributes) }}
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