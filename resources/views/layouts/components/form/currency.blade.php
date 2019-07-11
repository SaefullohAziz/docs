<div class="form-group {{ $class }}">
    {{ Form::label($name, $label) }}
    <div class="input-group">
        <div class="input-group-prepend">
            <div class="input-group-text">
                Rp.
            </div>
        </div>
    {{ Form::text($name, $value, array_merge(['class' => 'form-control currency '.($errors->has($name) ? 'is-invalid' : ''), 'placeholder' => $placeholder], $attributes)) }}
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