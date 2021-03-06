@extends('layouts.main')

@section('content')
<div class="row">
	<div class="col-12">

		@if (session('logged-in'))
			<div class="hero bg-primary text-white mb-4">
				<div class="hero-inner">
					<h2>{{ __('Welcome Back') }}, {{ auth()->user()->guard('admin')->name }}!</h2>
					<p class="lead"></p>
				</div>
			</div>
		@endif

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
			<div class="col-12">
				<div class="card shadow-sm">
					<div class="card-header">
						<h4 class="card-title">{{ __('Search School') }}</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-sm-6 offset-sm-3">
								<div class="form-group">
									{{ Form::select('search_school', [], null, ['style' => 'width: 100%;', 'class' => 'remote-select2', 'placeholder' => __('School')]) }}
								</div>
							</div>
							<div class="col-12">
								<div class="table-responsive">
									<table class="table table-sm table-striped search-school invisible">
										<thead>
											<tr>
												<th>{{ __('Name') }}</th>
												<th>{{ __('Level') }}</th>
												<th>{{ __('Status') }}</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="card shadow-sm">
					<div class="card-header">
						<h4 class="card-title">{{ __('Latest Comment') }}</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4">
								<ul class="nav nav-pills flex-column" id="schoolCommentTab" role="tablist">
									@foreach ($schoolComments as $schoolComment)
										<li class="nav-item">
											<a class="nav-link {{ $loop->first?'active':'' }} show" id="school-comment-{{ $loop->iteration }}-tab" data-toggle="tab" href="#school-comment-{{ $loop->iteration }}" role="tab" aria-controls="school-comment-{{ strtolower(str_replace(' ', '-', $schoolComment->name)) }}" aria-selected="{{ $loop->first?'true':'false' }}">{{ $schoolComment->name }}</a>
										</li>
									@endforeach
								</ul>
							</div>
							<div class="col-12 col-sm-12 col-md-8">
								<div class="tab-content no-padding" id="schoolCommentTabContent">
									@foreach ($schoolComments as $schoolComment)
										<div class="tab-pane fade {{ $loop->first?'active':'' }} show" id="school-comment-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="school-comment-{{ $loop->iteration }}-tab">
											<ul class="list-unstyled list-unstyled-border list-unstyled-noborder" style="height: 330px; overflow-y: scroll;">
												@foreach ($schoolComment->schoolComments as $comment)
													<li class="media">
														<img alt="image" class="rounded-circle {{ $loop->odd?'order-2 ml-2':'mr-2' }}" width="50" src="{{ asset($comment->staff->avatar) }}">
														<div class="media-body">
															<div class="media-title mt-0 mb-0">{{ $comment->staff->name }}</div>
															<div class="text-time mt-0 mb-0">{{ $comment->school->name }} &middot; {{ $comment->school->statusUpdate->status->name }}</div>
															<div class="text-time mt-0 mb-0">{{ $comment->created_at }}</div>
															<div class="media-description text-muted mt-0 mb-0">
																{!! html_entity_decode($comment->message) !!}
															</div>
														</div>
													</li>
												@endforeach
											</ul>
										</div>
									@endforeach
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="card shadow-sm">
					<div class="card-header">
						<h4 class="card-title">{{ __('Status Movement') }}</h4>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4">
								<ul class="nav nav-pills flex-column" id="statusMovementTab" role="tablist">
									@foreach ($statusMovements as $statusMovement)
										<li class="nav-item">
											<a class="nav-link {{ $loop->first?'active':'' }} show" id="status-movement-{{ $loop->iteration }}-tab" data-toggle="tab" href="#status-movement-{{ $loop->iteration }}" role="tab" aria-controls="status-movement-{{ strtolower(str_replace(' ', '-', $statusMovement->name)) }}" aria-selected="{{ $loop->first?'true':'false' }}">{{ $statusMovement->name }}</a>
										</li>
									@endforeach
								</ul>
							</div>
							<div class="col-12 col-sm-12 col-md-8">
								<div class="tab-content no-padding" id="statusMovementTabContent">
									@foreach ($statusMovements as $statusMovement)
										<div class="tab-pane fade {{ $loop->first?'active':'' }} show" id="status-movement-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="status-movement-{{ $loop->iteration }}-tab">
											<ul class="list-group" style="height: 330px; overflow-y: scroll;">
												@foreach ($statusMovement->statusUpdates as $statusUpdate)
													<li class="list-group-item flex-column align-items-start">
														<div class="d-flex w-100">
															<h6 class="mb-1">{{ link_to(route('admin.school.show', $statusUpdate->school->id), $statusUpdate->school->name) }}</h6>
														</div>
														<p class="mb-1">{{ $statusUpdate->status->name }}</p>
														<small class="text-muted">{{ $statusUpdate->created_at }} by {{ optional($statusUpdate->staff)->name }}</small>
													</li>
												@endforeach
											</ul>
										</div>
									@endforeach
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-8">
				<div class="card shadow-sm">
					<div class="card-header">
						<h4 class="card-title">{{ __('School Status') }}</h4>
						<div class="card-header-action">
							<button class="btn btn-primary" name="viewAllSchools">{{ __('View All') }}</button>
						</div>
					</div>
					<div class="card-body">
						<ul class="nav nav-tabs" id="schoolStatusesTab" role="tablist">
							@foreach ($schoolStatuses as $schoolStatus)
								<li class="nav-item">
									<a class="nav-link {{ $loop->first?'active show':'' }}" id="school-status-{{ $loop->iteration }}-tab" data-toggle="tab" href="#school-status-{{ $loop->iteration }}" role="tab" aria-controls="school-status-{{ strtolower(str_replace(' ', '-', $schoolStatus['name'])) }}" aria-selected="{{ $loop->first?'true':'false' }}">{{ $schoolStatus['name'] }}</a>
								</li>
							@endforeach
						</ul>
						<div class="tab-content" id="schoolStatusesTabContent">
							@foreach ($schoolStatuses as $schoolStatus)
								<div class="tab-pane fade {{ $loop->first?'active show':'' }}" id="school-status-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="school-status-{{ $loop->iteration }}-tab">
									<div class="table-responsive">
										<table class="table table-sm table-striped text-nowrap schoolStatusesTable">
											<thead>
												<tr>
													@foreach ($schoolStatus['statuses'] as $status)
														<th scope="col">{{ $status['name'] }}</th>
													@endforeach
												</tr>
											</thead>
											<tbody>
												@php
													$count = collect($schoolStatus['statuses'])->map(function ($item, $key) {
														return count($item['schools']);
													})->max();
												@endphp
												@for ($i=0; $i <= $count; $i++)
													<tr style="{{ ($i>4?'display: none;':'') }}">
														@for ($j=0; $j <= count($schoolStatus['statuses']); $j++)
															<td>{{ (empty($schoolStatus['statuses'][$j]['schools'][$i])?'':link_to(route('admin.school.show', $schoolStatus['statuses'][$j]['schools'][$i]['id']), $schoolStatus['statuses'][$j]['schools'][$i]['name'], ['target' => '_blank'])) }}</td>
														@endfor
													</tr>
												@endfor
											</tbody>
										</table>
									</div>
								</div>
							@endforeach
						</div>
                  	</div>
				</div>
			</div>

			<div class="col-sm-4">
				<div class="card shadow-sm">
					<div class="card-header">
						<h4 class="card-title">{{ __('School FTP') }}</h4>
					</div>
					<div class="card-body">
						<ul class="nav nav-tabs" id="schoolFtpTab" role="tablist">
							@foreach ($schoolFtps as $schoolFtp)
								<li class="nav-item">
									<a class="nav-link {{ $loop->first?'active show':'' }}" id="school-ftp-{{ $loop->iteration }}-tab" data-toggle="tab" href="#school-ftp-{{ $loop->iteration }}" role="tab" aria-controls="school-ftp-{{ strtolower(str_replace(' ', '-', $schoolFtp['name'])) }}" aria-selected="{{ $loop->first?'true':'false' }}">{{ __($schoolFtp['name']) }}</a>
								</li>
							@endforeach
						</ul>
						<div class="tab-content" id="schoolFtpTabContent">
							@foreach ($schoolFtps as $schoolFtp)
								<div class="tab-pane fade {{ $loop->first?'active show':'' }}" id="school-ftp-{{ $loop->iteration }}" role="tabpanel" aria-labelledby="school-ftp-{{ $loop->iteration }}-tab">
									<div class="table-responsive">
										<table class="table table-sm table-striped text-nowrap">
											<tbody>
												@foreach ($schoolFtp['schools'] as $school)
													<tr>
														<td>{{ $school['name'] }}</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							@endforeach
						</div>
                  	</div>
				</div>
			</div>

			<div class="col-12">
				<div class="card shadow-sm">
					<div class="card-header">
						<h4 class="card-title">{{ __('School Statistic') }}</h4>
						<div class="card-header-action">
						{{ Form::select('schoolChartView', ['Province' => __('Province'), 'Level' => __('Level')], null, ['class' => 'form-control']) }}
						</div>
					</div>
					<div class="card-body">
						<canvas id="schoolChart"></canvas>
					</div>
					<div class="card-footer bg-whitesmoke school-filter">
						<div class="row">
							{{ Form::bsSelect('col-sm-3', null, 'islands[]', $islands, null, __('All Island'), ['multiple' => '']) }}
							{{ Form::bsSelect('col-sm-3', null, 'provinces[]', $provinces, null, __('All Province'), ['multiple' => '']) }}
							{{ Form::bsSelect('col-sm-3', null, 'levels[]', $levels, null, __('All Level'), ['multiple' => '']) }}
							{{ Form::bsSelect('col-sm-3', null, 'statuses[]', $statuses, null, __('All Status'), ['multiple' => '']) }}
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="card shadow-sm">
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
			<div class="col-sm-6">
				<iframe src="https://www.google.com/maps/d/embed?mid=1s_v5py930HD8iqWPIawu_Kj7ktI" class="card card-body p-0" style="width: 100% ; min-height: 95% ;"></iframe>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
	<script>
		$(document).ready(function () {
			$('.remote-select2').select2({
				allowClear: true,
				ajax: {
					url: '{{ route('get.school') }}',
					type: "POST",
					dataType: 'json',
					data: function (params) {
						return {
							_token: '{{ csrf_token() }}',
							search: params.term,
						}
					},
					processResults: function (data) {
						return {
							results: data.result,
							pagination: {
								more: false
							}
						};
					},
				},
			});

			$('select[name="search_school"]').change(function () {
				$('.search-school tbody').html('');
				$('.search-school').removeClass('visible').addClass('invisible');
				if ($(this).val() != '') {
					$.ajax({
						url : "{{ route('get.school') }}",
						type: "POST",
						dataType: "JSON",
						data: {"_token" : "{{ csrf_token() }}", "school": $(this).val()},
						success: function(data)
						{
							$('.search-school tbody').append('<tr><td><a href="{{ url('admin/school') }}/'+data.result.id+'" target="_blank">'+data.result.name+'</a></td><td>'+data.result.status_update.status.level.name+'</td><td>'+data.result.status_update.status.name+'</td></tr>');
							$('.search-school').removeClass('invisible').addClass('visible');
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});
				}
			});

			$('[name="viewAllSchools"]').click(function () {
				$('.schoolStatusesTable tbody tr:nth-child(n+6)').toggle();
			});

			var schoolChart = new Chart($('#schoolChart'), {
				type: 'bar',
				data: {
					labels: [
						@foreach ($schoolPerProvince as $school)
							"{{ $school['name'] }}",
						@endforeach
					],
					datasets: [{
						label: '{{ __('Schools') }}',
						data: [{{ implode(', ', array_column($schoolPerProvince, 'schools_count')) }}],
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

			$('select[name="schoolChartView"]').change(function (event) {
				event.preventDefault();
				var islands = $('select[name="islands[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				var provinces = $('select[name="provinces[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				var levels = $('select[name="levels[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				var statuses = $('select[name="statuses[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				$.ajax({
					url : "{{ route('get.schoolChart') }}",
					type: "POST",
					dataType: "JSON",
					data: {"_token" : "{{ csrf_token() }}", "islands": islands, "provinces": provinces, "levels": levels, "statuses": statuses},
					success: function(data)
					{
						if (data.status == true) {
							schoolChart.destroy();
							if ($('select[name="schoolChartView"]').val() == 'Province') {
								schoolChart = new Chart($('#schoolChart'), {
									type: 'bar',
									data: {
										labels: [],
										datasets: [{
											label: '{{ __('Schools') }}',
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
								$.each(data.result.schoolPerProvince, function(key, item) {
									schoolChart.data.labels.push(item.name);
									schoolChart.data.datasets.forEach((dataset) => {
										dataset.data.push(item.schools_count);
									});
								});
							} else if ($('select[name="schoolChartView"]').val() == 'Level') {
								schoolChart = new Chart($('#schoolChart'), {
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
								$.each(data.result.schoolPerLevel, function(key, item) {
									schoolChart.data.labels.push(item.name);
									schoolChart.data.datasets.forEach((dataset) => {
										dataset.data.push(item.schools_count);
									});
								});
							}
							schoolChart.update();
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

			$('.school-filter [name="islands[]"], .school-filter [name="provinces[]"], .school-filter [name="levels[]"], .school-filter [name="statuses[]"]').change(function (event) {
				event.preventDefault();
				var islands = $('select[name="islands[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				var provinces = $('select[name="provinces[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				var levels = $('select[name="levels[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				var statuses = $('select[name="statuses[]"] option:selected').map(function(){
					return $(this).val();
				}).get();
				$.ajax({
					url : "{{ route('get.schoolChart') }}",
					type: "POST",
					dataType: "JSON",
					data: {"_token" : "{{ csrf_token() }}", "islands": islands, "provinces": provinces, "levels": levels, "statuses": statuses},
					success: function(data)
					{
						if (data.status == true) {
							schoolChart.data.labels = [];
							schoolChart.data.datasets.forEach((dataset) => {
								dataset.data = [];
							});
							schoolChart.data.datasets.data = [];
							if ($('select[name="schoolChartView"]').val() == 'Province') {
								$.each(data.result.schoolPerProvince, function(key, item) {
									schoolChart.data.labels.push(item.name);
									schoolChart.data.datasets.forEach((dataset) => {
										dataset.data.push(item.schools_count);
									});
								});
							} else if ($('select[name="schoolChartView"]').val() == 'Level') {
								$.each(data.result.schoolPerLevel, function(key, item) {
									schoolChart.data.labels.push(item.name);
									schoolChart.data.datasets.forEach((dataset) => {
										dataset.data.push(item.schools_count);
									});
								});
							}
							schoolChart.update();
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