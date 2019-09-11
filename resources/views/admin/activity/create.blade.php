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
			{{ Form::open(['route' => 'admin.activity.store', 'files' => true]) }}
			<div class="card-body">
				<div class="row">
                    <div class="col-sm-6">
                        <fieldset>
                        	{{ Form::bsSelect(null, __('School'), 'school_id', $schools, old('school_id'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                        	{{ Form::bsSelect(null, __('Type'), 'type', $types, old('type'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                            {{ Form::bsText(null, __('Date'), 'date', old('date'), __('DD-MM-YYYY'), ['required' => '']) }}

                            {{ Form::bsText(null, __('Until Date'), 'until_date', old('until_date'), __('DD-MM-YYYY'), ['required' => '']) }}

                            {{ Form::bsFile(null, __('Submission Letter'), 'submission_letter', old('submission_letter'), ['required' => ''], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}

                            {{ Form::bsText(null, __('Amount of Student'), 'amount_of_student', old('amount_of_student'), __('Amount of Student'), ['required' => '']) }}

                            {{ Form::bsText(null, __('Amount of Teacher'), 'amount_of_teacher', old('amount_of_teacher'), __('Amount of Teacher'), ['required' => '']) }}

                            {{ Form::bsText(null, __('Amount of ACP-Student'), 'amount_of_acp_student', old('amount_of_acp_student'), __('Amount of ACP-Student'), ['required' => '']) }}

                            {{ Form::bsFile(null, __('Partcipant'), 'participant', old('participant'), ['required' => ''], [__('File with xls/xlsx/xlsm format up to 5MB.'), __('For participant list data.')]) }}

                            {{ Form::bsText(null, __('Activity'), 'activity', old('activity'), __('activity'), ['required' => '']) }}
                            
                            {{ Form::bsText(null, __('Period'), 'period', old('period'), __('ex: Q1 2019'), ['required' => '']) }}

                            {{ Form::bsTextarea(null, __('Detail'), 'detail', old('detail'), __('Details')) }}

                        </fieldset>
                    </div>
                    <div class="col-sm-6">
                		<fieldset>
							<legend>{{ __('Person in Charge (PIC)') }}</legend>
							{{ Form::bsInlineRadio(null, __('Person in Charge?'), 'pic', ['2' => __('Yes'), '1' => __('Not')], old('pic'), ['disabled' => '', 'required' => '']) }}
							<div class="{{ ( ! empty(old('type'))?'d-block':'d-none') }}">
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', old('pic_name'), __('PIC Name')) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', old('pic_position'), __('PIC Position')) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', old('pic_phone_number'), __('PIC Phone Number'), ['maxlength' => '13']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', old('pic_email'), __('PIC E-Mail')) }}
							</div>
						</fieldset>
                    </div>
                </div>
			</div>
			<div class="card-footer bg-whitesmoke text-center">
				<div class="row">
                    <div class="col">
						{{ Form::submit(__('Save'), ['class' => 'btn btn-primary']) }}
						{{ link_to(url()->previous(),__('Cancel'), ['class' => 'btn btn-danger']) }}
				     </div>
                </div>
			</div>
			{{ Form::close() }}
		</div>

	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
		$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"], [name="activity"], [name="participant"]').closest('.form-group').removeClass('d-block').addClass('d-none');
		$('[name="school_id"]').change(function (){
			if ($(this).val() != '') {
				$('input[name="pic"]').prop('disabled', false).prop('required', true);
				if ($('input[name="pic"][value="2"]').is(':checked')) {
                    getPic();
                }
			}
		});

		$('[name="date"], [name="until_date"]').keypress(function(e) {
            e.preventDefault();
        }).daterangepicker({
        	locale: {format: 'DD-MM-YYYY'},
        	singleDatePicker: true,
      	});

      	$('[name="type"]').change(function(e) {
      		switch($(this).val()) {
			  case 'Kunjungan_industri':
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"], [name="participant"]').closest('.form-group').removeClass('d-none').addClass('d-block');
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"], [name="participant"]').prop('disabled', false).prop('required', true);
			    	$('[name="until_date"], [name="amount_of_student"], [name="period"], [name="activity"]').closest('.form-group').removeClass('d-block').addClass('d-none');
			    	$('[name="until_date"], [name="amount_of_student"], [name="period"], [name="activity"]').prop('required', false).prop('disabled', true);
			    break;
			  case 'Axioo_Mengajar':
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"], [name="participant"]').closest('.form-group').removeClass('d-block').addClass('d-none');
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"], [name="participant"]').prop('required', false).prop('disabled', true);
			    	$('[name="activity"], [name="until_date"], [name="period"]').closest('.form-group').removeClass('d-none').addClass('d-block');
			    	$('[name="activity"], [name="until_date"], [name="period"]').prop('disabled', false).prop('required', true);
			    break;
			  default:
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"], [name="participant"], [name="activity"]').closest('.form-group').removeClass('d-block').addClass('d-none');
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"], [name="participant"], [name="activity"]').prop('required', false).prop('disabled', true);
			    	$('[name="until_date"], [name="submission_letter"], [name="amount_of_student"], [name="period"]').closest('.form-group').removeClass('d-none').addClass('d-block');
			    	$('[name="until_date"], [name="submission_letter"], [name="amount_of_student"], [name="period"]').prop('disabled', false).prop('required', true);
			}
      	});

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
			data: {'_token' : '{{ csrf_token() }}', 'school' : $('[name="school_id"]').val()},
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