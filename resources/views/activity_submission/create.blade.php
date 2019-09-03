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
			{{ Form::open(['route' => 'activity.store', 'files' => true]) }}
			<div class="card-body">
				<div class="row">
                    <div class="col-sm-6">
                        <fieldset>
                        	{{ Form::bsSelect(null, __('Type'), 'type', $types, old('type'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                            {{ Form::bsText(null, __('Date'), 'date', old('date'), __('DD-MM-YYYY'), ['required' => '']) }}

                            {{ Form::bsText(null, __('Until date'), 'until_date', old('until_date'), __('DD-MM-YYYY'), ['required' => '']) }}

                            {{ Form::bsSelect(null, __('Destination'), 'destination', $schools, auth::user()->school_id, __('Select'), ['placeholder' => __('Select'), 'readonly' => '']) }}

                            {{ Form::bsText(null, __('Amount of Student'), 'amount_of_student', old('amount_of_student'), __('Amount of Student'), ['required' => '']) }}

                            {{ Form::bsText(null, __('Amount of Teacher'), 'amount_of_teacher', old('amount_of_teacher'), __('Amount of Teacher'), ['required' => '']) }}

                            {{ Form::bsText(null, __('Amount of ACP-Student'), 'amount_of_acp_student', old('amount_of_acp_student'), __('Amount of ACP-Student'), ['required' => '']) }}

                            {{ Form::bsText(null, __('Amount of Reguler-Student'), 'amount_of_reguler_student', old('amount_of_reguler_student'), __('Amount of Reguler-Student'), ['required' => '']) }}

                            {{ Form::bsText(null, __('Activity'), 'activity', old('activity'), __('activity'), ['required' => '']) }}
                            
                            {{ Form::bsText(null, __('Period'), 'period', old('period'), __('ex: Q1 2019'), ['required' => '']) }}

                            {{ Form::bsTextarea(null, __('Detail'), 'detail', old('detail'), __('Details'), ['required' => '']) }}

                        </fieldset>
                    </div>
                </div>
			</div>
			<div class="card-footer bg-whitesmoke text-center">
				<div class="row">
                    <div class="col-sm-6">
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
		$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"], [name="activity"]').closest('.form-group').removeClass('d-block').addClass('d-none');

		$('[name="date"], [name="until_date"]').keypress(function(e) {
            e.preventDefault();
        }).daterangepicker({
        	locale: {format: 'DD-MM-YYYY'},
        	singleDatePicker: true,
      	});

      	$('[name="type"]').change(function(e) {
      		switch($(this).val()) {
			  case 'Kunjungan_industri':
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"]').closest('.form-group').removeClass('d-none').addClass('d-block');
			    break;
			  case 'Axioo_Mengajar':
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"]').closest('.form-group').removeClass('d-block').addClass('d-none');
			    	$('[name="activity"]').closest('.form-group').removeClass('d-none').addClass('d-block');
			    break;
			  default:
			    	$('[name="amount_of_teacher"], [name="amount_of_acp_student"], [name="amount_of_reguler_student"]').closest('.form-group').removeClass('d-block').addClass('d-none');
			}
      	})
    });
</script>
@endsection