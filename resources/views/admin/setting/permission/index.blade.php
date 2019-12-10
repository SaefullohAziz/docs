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

                <div class="alert alert-success alert-dismissible show fade setting-alert d-none">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ __($setting['description']) }}</p>
                            
                        <div class="table-responsive">
                            <table class="table table-sm table-striped" id="table4data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Assigned Roles') }}</th>
                                        <th>{{ __('Action') }}</th>
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
</div>
@endsection

@section('script')
    <script>
        var table;
        $(document).ready(function () {
            table = $('#table4data').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                    "url": "{{ route('admin.setting.permission.list') }}",
                    "type": "POST",
                    "data": function (d) {
                        d._token = "{{ csrf_token() }}";
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', 'searchable': false },
                    { data: 'name', name: 'name' },
                    { data: 'roles', name: 'roles.name', 'searchable': false, 'orderable': false },
                    { data: 'action', name: 'action', 'searchable': false },
                ],
                "order": [[ 1, 'desc' ]],
                "lengthChange": false,
                "info" : false,
                "dom": '<"d-flex justify-content-start"f>rt<"d-flex justify-content-center"p>',
                "columnDefs": [
                {   
                    "targets": [ 0, -1 ], //last column
                    "orderable": false, //set not orderable
                },
                ],
            });

            $('[name="savePermission"]').click(function (event) {
                $.ajax({
                    url : "{{ route('admin.setting.permission.store') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: $('#edit-permission-form').serialize(),
                    success: function(data)
                    {
                        if (data.status == true) {
                            $('.setting-alert .alert-body').append(data.message);
                            $('.setting-alert').removeClass('d-none').addClass('d-block');
                        }
                        $('#editPermissionModal').modal('hide');
                        reloadTable();
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                    }
                });
            });
        });

        function reloadTable() {
            table.ajax.reload(null,false); //reload datatable ajax
        }

        function editPermission(id) {
            $('#edit-permission-form [name="permission"]').val('');
            $('#edit-permission-form [name="roles[]"]').val(null).change();
            $('.edit-permission-title').html('');
            $('.setting-alert .alert-body').html('<button class="close" data-dismiss="alert"><span>&times;</span></button>');
            $('.setting-alert').removeClass('d-block').addClass('d-none');
            $.ajax({
	        	url : "{{ route('get.permission') }}",
	        	type: "POST",
	        	dataType: "JSON",
				data: {"_token" : "{{ csrf_token() }}", "permission" : id},
	        	success: function(data)
	        	{
                    $('.edit-permission-title').html(data.result.name);
                    $('#edit-permission-form [name="permission"]').val(data.result.id);
                    var roles = (data.result.roles).map(function (role) {
                        return role.id;
                    });
                    $('#edit-permission-form [name="roles[]"]').val(roles).change();
                    $('#editPermissionModal').modal('show');
	        	},
	        	error: function (jqXHR, textStatus, errorThrown)
	        	{
	        	}
	        });
        }
    </script>

    <!-- Modal -->
    <div class="modal fade" id="editPermissionModal" tabindex="-1" role="dialog" aria-labelledby="editPermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPermissionModallLabel">{{ __('Permission') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{ Form::open(['url' => '#', 'files' => true, 'id' => 'edit-permission-form']) }}
                    <div class="modal-body">
                        <div class="container-fluid">
                            <fieldset class="row">
                                <legend class="edit-permission-title"></legend>
								{{ Form::bsHidden('d-none', null, 'permission', null, null) }}
                                {{ Form::bsSelect('col-12', __('Role'), 'roles[]', $roles, null, __('Select'), ['multiple' => '']) }}
                            </fieldset>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke d-flex justify-content-center">
                        {{ Form::button(__('Save'), ['class' => 'btn btn-primary', 'name' => 'savePermission']) }}
                        {{ Form::button(__('Cancel'), ['class' => 'btn btn-secondary', ' data-dismiss' => 'modal']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection