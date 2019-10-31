<div class="form-group {{ $class }}">
	@if ($label)
    	{{ Form::label($name, $label) }}
	@endif
    {{ Form::selectRange($name, $start, $finish, $value, array_merge(['class' => 'form-control select2 '.($errors->has($name) ? 'is-invalid' : ''), 'data-placeholder' => $dataPlaceholder, 'style' => 'width: 100%;'], $attributes)) }}
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