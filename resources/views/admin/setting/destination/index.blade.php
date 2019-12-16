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
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Jump To</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills flex-column">
                            @foreach ($navs as $nav)
                                <li class="nav-item"><a href="{{ $nav['url'] }}" class="nav-link {{ ($setting['slug']==$nav['slug']?'active':'') }}">{{ __($nav['title']) }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $title }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">{{ __($setting['description']) }}</p>
                            <div class="row">
                                <div class="col-sm-6">
                                    {{ Form::open(['route' => 'admin.setting.destination.store']) }}
                                    <div class="card border">
                                        <div class="card-body">
                                            {{ Form::bsSelect(null, __('School to be assigned as destination'), 'school_id[]', $schools, old('school_id[]'), __('Select'), ['required' => '', 'multiple' => true]) }}
                                        </div>
                                        <div class="card-footer bg-whitesmoke text-md-center">
                                            {{ Form::submit(__('Assign'), ['class' => 'btn btn-primary']) }}
                                        </div>
                                    </div>
			                        {{ Form::close() }}
                                </div>
                                <div class="col-sm-6">
                                    {{ Form::open(['route' => 'admin.setting.destination.destroy', 'method' => 'delete']) }}
                                    <div class="card border">
                                        <div class="card-body">
                                            {{ Form::bsSelect(null, __('School that has been assigned as destination'), 'destination_id[]', $destinations, old('destination_id[]'), __('Select'), ['required' => '', 'multiple' => true]) }}
                                        </div>
                                        <div class="card-footer bg-whitesmoke text-md-center">
                                            {{ Form::submit(__('Delete'), ['class' => 'btn btn-danger']) }}
                                        </div>
                                    </div>
			                        {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-whitesmoke text-md-center">
                            {{ link_to(url()->previous(),__('Cancel'), ['class' => 'btn btn-secondary']) }}
                        </div>
                    </div>
			    {{ Form::close() }}
            </div>
        </div>
	</div>
</div>
@endsection

@section('script')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#table4data').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{{ route('admin.setting.destination.list') }}",
                    "type": "POST",
                    "data": function (d) {
                    d._token = "{{ csrf_token() }}";
                    d.type = $('select[name="type"]').val();
                    d.status = $('select[name="status"]').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', 'searchable': false },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'school', name: 'schools.name' },
                    { data: 'action', name: 'action', 'searchable': false },
                ],
                "order": [[ 1, 'desc' ]],
                "columnDefs": [
                {   
                    "targets": [ 0, -1 ], //last column
                    "orderable": false, //set not orderable
                },
                ],
                "drawCallback": function(settings) {
                    $('input[name="selectData"], input[name="selectedData[]"]').iCheck({
                        checkboxClass: 'icheckbox_flat-green',
                        radioClass: 'iradio_flat-green',
                        increaseArea: '20%' /* optional */
                    });
                    $('[name="selectData"]').on('ifChecked', function(event){
                        $('[name="selectedData[]"]').iCheck('check');
                    }).on('ifUnchecked', function(event){
                        $('[name="selectedData[]"]').iCheck('uncheck');
                    });
                },
            });
        });
        $(document).ready(function () {
            $('select[name="destination"]').change(function () {
                $('select[name="potential_staffs[]"], select[name="existing_staffs"]').html('<option value="">{{ __('Select') }}</option>');
                if ($(this).val() != '') {
                    $.ajax({
                        url : "{{ route('get.staff') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {'_token' : '{{ csrf_token() }}', 'destination' : $(this).val()},
                        success: function(data)
                        {
                            $.each(data.result, function(key, value) {
                                $('select[name="existing_staffs"]').append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            
                        }
                    });
                    $.ajax({
                        url : "{{ route('get.staff') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: {'_token' : '{{ csrf_token() }}', 'not_destination' : $(this).val()},
                        success: function(data)
                        {
                            $.each(data.result, function(key, value) {
                                $('select[name="potential_staffs[]"]').append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        },
                        error: function (jqXHR, textStatus, errorThrown)
                        {
                            
                        }
                    });
                }
            });
        });
    </script>
@endsection