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

			{{ Form::open(['route' => 'admin.class.store', 'files' => true]) }}
				<div class="card-body">
					<div class="row">
						<div class="col-sm-6">
							<fieldset>
                                {{ Form::bsSelect(null, __('School'), 'school_id', $schools, old('school_id'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsSelect(null, __('Department'), 'department_id', [], old('department_id'), __('Select'), ['placeholder' => __('Select'), 'required' => '']) }}

                                {{ Form::bsText(null, __('Generation'), 'generation', null, __('Generation'), ['disabled' => '']) }}
                            </fieldset>
                        </div>
						<div class="col-sm-6">
							<fieldset>
                                {{ Form::bsText(null, __('School Year'), 'school_year', $schoolYear, __('School Year'), ['disabled' => '']) }}

                                {{ Form::bsText(null, __('Grade'), 'grade', 'Kelas 10', __('Grade'), ['disabled' => '']) }}
                            </fieldset>
					</div>
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
        $('select[name="school_id"]').change(function() {
			$('select[name="department_id"]').html('<option value="">Select</option>').prop('disabled', false);
			if ($(this).val() != '') {
				$.ajax({
					url : "{{ route('get.departmentBySchool') }}",
					type: "POST",
					dataType: "JSON",
					cache: false,
					data: {'_token' : '{{ csrf_token() }}', 'school': $(this).val()},
					success: function(data)
					{
                        if (data.status == true) {
                            $.each(data.result, function(key, value) {
                                $('select[name="department_id"]').append('<option value="'+key+'">'+value+'</option>');
                            });
                            if (Object.keys(data.result).length == 1) {
                                $.each(data.result, function(k, v) {
                                    $('select[name="department_id"]').val(k).change();
                                });;
                                $('select[name="department_id"]').prop('disabled', true);
                            }
                        }
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('select[name="department_id"]').html('<option value="">Select</option>');
					}
				});
			}
		});

		$('select[name="department_id"]').change(function() {
			$('input[name="generation"]').val('');
			if ($(this).val() != '') {
				$.ajax({
					url : "{{ route('get.generationFromClass') }}",
					type: "POST",
					dataType: "JSON",
					cache: false,
					data: {'_token' : '{{ csrf_token() }}', 'school': $('select[name="school_id"]').val(), 'department': $(this).val()},
					success: function(data)
					{
                        if (data.status == true) {
							$('input[name="generation"]').val(data.result);
                        }
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						$('input[name="generation"]').val('');
					}
				});
			}
		});
	});
</script>
@endsection