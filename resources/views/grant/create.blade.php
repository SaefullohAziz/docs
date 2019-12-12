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

		<div class="card card-primary">

			{{ Form::open(['route' => 'grant.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
                                {{ Form::bsInlineRadio(null, __('Menerima Syarat dan Ketentuan Pendistribusian Hibah Bahan Praktek Siswa'), 'requirement', ['2' => __('Yes'), '1' => __('No')], old('requirement'), ['required' => '']) }}
                            </fieldset>
                        </div>
						<div class="col-sm-6">
                            <fieldset>
                                <legend>{{ __('Person in Charge (PIC)') }}</legend>
                                {{ Form::bsInlineRadio(null, __('Person in Charge?'), 'pic', ['2' => __('Yes'), '1' => __('Not')], old('pic'), ['required' => '']) }}
                                <div class="{{ (old('pic')==1?'d-block':'d-none') }}">
                                    {{ Form::bsText(null, __('PIC Name'), 'pic_name', old('pic_name'), __('PIC Name')) }}

                                    {{ Form::bsText(null, __('PIC Position'), 'pic_position', old('pic_position'), __('PIC Position')) }}

                                    {{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', old('pic_phone_number'), __('PIC Phone Number'), ['maxlength' => '13']) }}

                                    {{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', old('pic_email'), __('PIC E-Mail')) }}
                                </div>
                            </fieldset>
                        </div>
					</div>
					{{ Form::bsCheckbox(null, null, 'terms', 'Agree', __('Data filled above is true and can be accounted for.'), old('terms'), ['required' => '']) }}
				</div>
				<div class="card-footer bg-whitesmoke text-center">
					{{ Form::submit(__('Save'), ['name' => 'submit', 'class' => 'btn btn-primary']) }}
					{{ link_to(url()->previous(), __('Cancel'), ['class' => 'btn btn-danger']) }}
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
        $('input[name="pic"]').click(function () {
			if ($('input[name="pic"][value="2"]').is(':checked')) {
				getPic();
			} else if ($('input[name="pic"][value="1"]').is(':checked')) {
				$('input[name="pic_name"]').parent().parent('div').removeClass('d-none').addClass('d-block');
	    		$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', false).val('');
			}
		});
	});

    function getPic() {
		$.ajax({
			url : "{{ route('get.pic') }}",
			type: "POST",
			dataType: "JSON",
			data: {'_token' : '{{ csrf_token() }}'},
			success: function(data)
			{
			    $('[name="pic_name"]').val(data.result.name);
		        $('[name="pic_position"]').val(data.result.position);
			    $('[name="pic_phone_number"]').val(data.result.phone_number);
			    $('[name="pic_email"]').val(data.result.email);
		    	$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', true);
				$('input[name="pic_name"]').parent().parent('div').removeClass('d-none').addClass('d-block');
			},
		    error: function (jqXHR, textStatus, errorThrown)
		    {
			    swal("{{ __('Failed!') }}", "", "warning");
				$('input[name="pic_name"]').parent().parent('div').removeClass('d-block').addClass('d-none');
			}
		});
	}
</script>
@endsection