<div class="form-group {{ $class }}">
    {{ Form::label($name, $label) }}
    {{ Form::select($name, $option, $value, array_merge(['class' => 'form-control select2 '.($errors->has($name) ? 'is-invalid' : ''), 'data-placeholder' => $dataPlaceholder, 'style' => 'width: 100%;'], $attributes)) }}
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