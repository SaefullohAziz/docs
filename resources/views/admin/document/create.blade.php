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

			{{ Form::open(['route' => 'admin.school.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						<fieldset class="col-sm-6">
							<legend>{{ __('School Data') }}</legend>
							{{ Form::bsSelect(null, __('School'), 'school', $schools, old('school'), __('School'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsSelect(null, __('Implementation'), 'implementation', $implementation, old('implementation'), __('implementation'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsSelect(null, __('Category'), 'category', $category, old('category'), __('category'), ['placeholder' => __('Select'), 'required' => '']) }}

							{{ Form::bsFile(null, __('Document File'), 'document', old('document'), [], [__('Please use *.ZIP if any document files')]) }}
						</fieldset>

						<fieldset class="col-sm-6">
							<legend>{{ __('Submitter Data') }}</legend>
							{{ Form::bsInlineRadio(null, 'ACP P.I.C?', 'pic', ['yes' => __('Yes'), 'no' => __('No')], old('PIC'), ['required' => '']) }}

							<div class="pics">
								{{ Form::bsText(null, __('Full Name'), 'pic_name', old('pic_name'), __('pic_name'), ['placeholder' => __('Nama PIC'), 'disabled' => '']) }}

								{{ Form::bsText(null, __('Jabatan'), 'pic_position', old('pic_position'), __('pic_position'), ['placeholder' => __('Jabatan'), 'disabled' => '']) }}

								{{ Form::bsText(null, __('No_Hanphone'), 'pic_phone_number', old('pic_phone_number'), __('pic_phone_number'), ['placeholder' => __('No_Hanphone'), 'disabled' => '']) }}

								{{ Form::bsText(null, __('E-Mail'), 'pic_email', old('pic_email'), __('pic_email'), ['placeholder' => __('E-Mail'), 'disabled' => '']) }}
							</div>

						</fieldset>
					</div>
				</div>
				<div class="card-footer bg-whitesmoke text-center">
					{{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
					{{ link_to(url()->previous(),__('Cancel'), ['class' => 'btn btn-danger']) }}
				</div>
			{{ Form::close() }}

		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
		$('input[type="checkbox"], input[type="radio"]').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
	      	increaseArea: '20%' // optional
	    });
		
		$('.pics').hide();

		$('[name="pic"]').iCheck('disable');
		$('[name="school"]').change(function() {
			console.log('ok');
			if ($(this).val() == '') {
				$('[name="pic"]').iCheck('disable').iCheck('uncheck');
				$('.pic').hide(300);
				$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', false).val('');
			} else {
				$('[name="pic"]').iCheck('enable');
				if ($('input[name="pic"]:checked').val() == '1') {
				  	getPic();
				};
			}
		});

		$('input[name="pic"][value="1"]').on('ifChecked', function(){
			getPic();
		});
		$('input[name="pic"][value="0"]').on('ifChecked', function(event){
			$('.pic').show(300);
	    	$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', false).val('');
		});
	});

	function getPic() {
		$.ajax({
			url : "#",
			type: "POST",
			dataType: "JSON",
			data: {},
			success: function(data)
			{
			    $('.pics').show(300);
			    $('[name="pic_name"]').val(data.pic_name);
			    $('[name="pic_position"]').val(data.pic_position);
			    $('[name="pic_phone_number"]').val(data.pic_phone_number);
			    $('[name="pic_email"]').val(data.pic_email);
		    	$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', true);
			},
		    error: function (jqXHR, textStatus, errorThrown)
		    {
		    	$('.pics').hide(300);
			    $('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').val('');
			    swal("Gagal!", "", "warning");
			}
		});
	}
</script>
@endsection