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

		<div class="row">
			<div class="col-sm-6">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">{{ __('Student Statistic') }}</h4>
						<div class="card-header-action">
						{{ Form::select('studentChartView', ['Department' => __('Department'), 'Level' => __('Level')], null, ['class' => 'form-control']) }}
						</div>
					</div>
					<div class="card-body">
						<canvas id="studentChart"></canvas>
					</div>
					<div class="card-footer bg-whitesmoke student-filter">
						<div class="row">
							{{ Form::bsSelect('col-12', null, 'departments[]', $departments, null, __('All Department'), ['multiple' => '']) }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
	<script>
		$(document).ready(function () {
			var studentChart = new Chart($('#studentChart'), {
				type: 'bar',
				data: {
					labels: [
						@foreach ($studentPerDepartment as $student)
							"{{ $student['name'] }}",
						@endforeach
					],
					datasets: [{
						label: '{{ __('Students') }}',
						data: [{{ implode(', ', array_column($studentPerDepartment, 'students_count')) }}],
						borderWidth: 2,
						backgroundColor: '#6777ef',
						borderColor: '#6777ef',
						borderWidth: 2.5,
						pointBackgroundColor: '#ffffff',
						pointRadius: 4
					}]
				},
				options: {
					responsive: true,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								drawBorder: false,
								color: '#f2f2f2',
							},
							ticks: {
								beginAtZero: true,
								stepSize: 150
							}
						}],
						xAxes: [{
							ticks: {
								autoSkip: false
							},
							gridLines: {
								display: false
							}
						}]
					},
					plugins: {
						colorschemes: {
							scheme: 'office.Module6',
						}
					}
				}
			});

			$('select[name="studentChartView"]').change(function (event) {
				event.preventDefault();
				var departments = $('select[name="departments[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				$.ajax({
					url : "{{ route('get.studentChart') }}",
					type: "POST",
					dataType: "JSON",
					data: {"_token" : "{{ csrf_token() }}", "departments": departments},
					success: function(data)
					{
						if (data.status == true) {
							studentChart.destroy();
							if ($('select[name="studentChartView"]').val() == 'Department') {
								studentChart = new Chart($('#studentChart'), {
									type: 'bar',
									data: {
										labels: [],
										datasets: [{
											label: '{{ __('Students') }}',
											data: [],
											borderWidth: 2,
											backgroundColor: '#6777ef',
											borderColor: '#6777ef',
											borderWidth: 2.5,
											pointBackgroundColor: '#ffffff',
											pointRadius: 4
										}]
									},
									options: {
										legend: {
											display: false
										},
										scales: {
											yAxes: [{
												gridLines: {
													drawBorder: false,
													color: '#f2f2f2',
												},
												ticks: {
													beginAtZero: true,
													stepSize: 150
												}
											}],
											xAxes: [{
												ticks: {
													autoSkip: false
												},
												gridLines: {
													display: false
												}
											}]
										},
										plugins: {
											colorschemes: {
												scheme: 'office.Module6',
											}
										}
									}
								});
								$.each(data.result.studentPerDepartment, function(key, item) {
									studentChart.data.labels.push(item.name);
									studentChart.data.datasets.forEach((dataset) => {
										dataset.data.push(item.students_count);
									});
								});
							} else if ($('select[name="studentChartView"]').val() == 'Level') {
								studentChart = new Chart($('#studentChart'), {
									type: 'pie',
									data: {
										labels: [],
										datasets: [{
											label: '{{ __('Schools') }}',
											data: [],
										}]
									},
									options: {
										responsive: true,
										legend: {
											position: 'bottom',
										},
									}
								});
								$.each(data.result.studentPerLevel, function(key, item) {
									studentChart.data.labels.push(item.name);
									studentChart.data.datasets.forEach((dataset) => {
										dataset.data.push(item.students_count);
									});
								});
							}
							studentChart.update();
						}
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						if (JSON.parse(jqXHR.responseText).status) {
							swal("{{ __('Failed!') }}", '{{ __("Data cannot be updated.") }}', "warning");
						} else {
							swal(JSON.parse(jqXHR.responseText).message, "", "error");
						}
					}
				});
			});

			$('.student-filter [name="departments[]"]').change(function (event) {
				event.preventDefault();
				var departments = $('select[name="departments[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				$.ajax({
					url : "{{ route('get.studentChart') }}",
					type: "POST",
					dataType: "JSON",
					data: {"_token" : "{{ csrf_token() }}", "departments": departments},
					success: function(data)
					{
						if (data.status == true) {
							studentChart.data.labels = [];
							studentChart.data.datasets.forEach((dataset) => {
								dataset.data = [];
							});
							if ($('select[name="studentChartView"]').val() == 'Department') {
								$.each(data.result.studentPerDepartment, function(key, item) {
									studentChart.data.labels.push(item.name);
									studentChart.data.datasets.forEach((dataset) => {
										dataset.data.push(item.students_count);
									});
								});
							} else if ($('select[name="studentChartView"]').val() == 'Level') {
								$.each(data.result.studentPerLevel, function(key, item) {
									studentChart.data.labels.push(item.name);
									studentChart.data.datasets.forEach((dataset) => {
										dataset.data.push(item.students_count);
									});
								});
							}
							studentChart.update();
						}
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						if (JSON.parse(jqXHR.responseText).status) {
							swal("{{ __('Failed!') }}", '{{ __("Data cannot be updated.") }}', "warning");
						} else {
							swal(JSON.parse(jqXHR.responseText).message, "", "error");
						}
					}
				});
			});
		});
	</script>
@endsection