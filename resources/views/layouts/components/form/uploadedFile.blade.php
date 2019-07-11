<div class="form-group {{ $class }}">
    {{ Form::label($name, $label, ['class' => 'd-block']) }}
    {{ link_to_route('download', __('Download'), ['dir' => encrypt($dir), 'file' => encrypt($file)], ['class' => 'btn btn-primary '.( ! isset($file)?'disabled':''), 'title' => __('Download'), 'target' => '_blank']) }}
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