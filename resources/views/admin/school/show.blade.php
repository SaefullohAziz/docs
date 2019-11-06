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

			{{ Form::open(['url' => '#', 'files' => true, 'method' => 'put']) }}
				<div class="card-body">
					<div class="row">
						<fieldset class="col-sm-6">
							<legend>{{ __('School Data') }}</legend>
							{{ Form::bsSelect(null, __('Type'), 'type', ['Negeri' => 'Negeri', 'Swasta' => 'Swasta'], $data->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsText(null, __('Name'), 'name', $data->name, __('Name'), ['disabled' => '']) }}

							{{ Form::bsTextarea(null, __('Address'), 'address', $data->address, __('Address'), ['disabled' => '']) }}

							{{ Form::bsSelect(null, __('Province'), 'province', $provinces, $data->province, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsSelect(null, __('Regency'), 'regency', $regencies, $data->regency, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsSelect(null, __('Police Number'), 'police_number', $policeNumbers, $data->police_number, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsText(null, __('Since'), 'since', $data->since, __('Since'), ['maxlength' => '4', 'disabled' => '']) }}

							{{ Form::bsPhoneNumber(null, __('School Phone Number'), 'school_phone_number', $data->school_phone_number, __('School Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

							{{ Form::bsEmail(null, __('School E-Mail'), 'school_email', $data->school_email, __('School E-Mail'), ['disabled' => '']) }}

							{{ Form::bsText(null, __('School Website (URL)'), 'school_web', $data->school_web, __('School Website (URL)'), ['disabled' => '']) }}

							{{ Form::bsText(null, __('Total Student'), 'total_student', $data->total_student, __('Total Student'), ['disabled' => '']) }}

							{{ Form::bsCheckboxList(null, __('Department'), 'department[]', $departments, explode(', ', $data->department), ['disabled' => '']) }}

							{{ Form::bsSelect(null, __('ISO Certificate'), 'iso_certificate', $isoCertificates, $data->iso_certificate, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

							{{ Form::bsInlineRadio(null, __('Mikrotik Academy'), 'mikrotik_academy', ['Sudah' => 'Sudah', 'Belum' => 'Belum'], $data->mikrotik_academy, ['disabled' => '']) }}
						</fieldset>
						<div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Headmaster Data') }}</legend>
								{{ Form::bsText(null, __('Headmaster Name'), 'headmaster_name', $data->headmaster_name, __('Headmaster Name'), ['disabled' => ''], [__('Complete with an academic degree and or degree of expertise.')]) }}

								{{ Form::bsPhoneNumber(null, __('Headmaster Phone Number'), 'headmaster_phone_number', $data->headmaster_phone_number, __('Headmaster Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsEmail(null, __('Headmaster E-Mail'), 'headmaster_email', $data->headmaster_email, __('Headmaster E-Mail'), ['disabled' => '']) }}
							</fieldset>
							@foreach ($data->pic as $pic)
								<fieldset>
									<legend>{{ __('PIC ' .$loop->iteration. ' Data') }}</legend>
									{{ Form::bsText(null, __('PIC Name'), 'pic['.$loop->index.'][name]', $pic->name, __('PIC Name'), ['disabled' => ''], [__('Complete with an academic degree and or degree of expertise.')]) }}

									{{ Form::bsText(null, __('PIC Position'), 'pic['.$loop->index.'][position]', $pic->position, __('PIC Position'), ['disabled' => '']) }}

									{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic['.$loop->index.'][phone_number]', $pic->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

									{{ Form::bsEmail(null, __('PIC E-Mail'), 'pic['.$loop->index.'][email]', $pic->email, __('PIC E-Mail'), ['disabled' => '']) }}
								</fieldset>
							@endforeach
							<fieldset>
								<legend>{{ __('Reference') }}</legend>
								{{ Form::bsCheckboxList(null, __('Reference'), 'reference[]', $references, explode(', ', $data->reference), ['disabled' => '']) }}
							</fieldset>
							@if (in_array('Dealer', explode(', ', $data->reference)))
								<fieldset class="dealer-data">
									<legend>{{ __('Dealer Data') }}</legend>
									{{ Form::bsText(null, __('Dealer Name'), 'dealer_name', $data->dealer_name, __('Dealer Name'), ['disabled' => '']) }}

									{{ Form::bsPhoneNumber(null, __('Dealer Phone Number'), 'dealer_phone_number', $data->dealer_phone_number, __('Dealer Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

									{{ Form::bsEmail(null, __('Dealer E-Mail'), 'dealer_email', $data->dealer_email, __('Dealer E-Mail'), ['disabled' => '']) }}
								</fieldset>
							@endif
							<fieldset>
								<legend>{{ __('Other Data') }}</legend>
								{{ Form::bsInlineRadio(null, 'Apakah Kepala Sekolah telah mempelajari proposal ACP?', 'proposal', ['Sudah' => 'Sudah', 'Belum' => 'Belum'], $data->mikrotik_academy, ['disabled' => '']) }}
								
								{{ Form::bsUploadedFile(null, __('Requirement Document'), 'document', 'school/document', $data->document, [], [__('File must have extension *.ZIP/*.RAR with size 5 MB or less.')]) }}
							</fieldset>
						</div>
					</div>
				</div>
			{{ Form::close() }}

		</div>

		<div class="card card-primary" id="school-documents">
			<div class="card-header">
				<h4>{{ __('Document') }}</h4>
				<div class="card-header-action">
                    <div class="btn-group">
						<div class="dropdown">
							<a href="#" data-toggle="dropdown" class="btn btn-sm btn-warning dropdown-toggle">{{ (session('documentCategory')?session('documentCategory'):__('All Categories')) }}</a>
							<div class="dropdown-menu">
								@foreach ($documentCategories as $key => $category)
									<a href="{{ route('admin.school.document.filter', ['school' => $data->id, 'token' => base64_encode($category)]) }}" class="dropdown-item">{{ $category }}</a>
								@endforeach
								<div class="dropdown-divider"></div>
								<a href="{{ route('admin.school.document.filter', ['school' => $data->id, 'token' => base64_encode('')]) }}" class="dropdown-item">{{ __('All Categories') }}</a>
							</div>
						</div>
                    	<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#addDocumentModal">{{ __('Add') }}</button>
						<button class="btn btn-warning btn-sm" name="deleteDocument" title="{{ __('Delete') }}">{{ __('Delete') }}</button>
                    </div>
                </div>
			</div>
			<div class="card-body">
				<div class="row">
					@foreach ($data->documents as $document)
						<div class="col-sm-3 col-lg-2">
							<div class="card card-warning">
								<input type="checkbox" class="position-absolute mt-1 ml-2" name="schoolDocuments[]" value="{{ $document->id }}" id="photo-{{ $loop->iteration }}">
								<svg class="mt-3 card-img-top svg-inline--fa fa-archive fa-w-16" viewBox="0 0 582 270" xmlns="http://www.w3.org/2000/svg">
									<g>
										<title>background</title>
										<rect x="-1" y="-1" width="584" height="272" fill="none"/>
									</g>
									<g>
										<title>Archive</title>
										<path d="m179 236.43c0 9.1661 7.15 16.571 16 16.571h192c8.85 0 16-7.4054 16-16.571v-149.14h-224v149.14zm80-109.79c0-3.4179 2.7-6.2143 6-6.2143h52c3.3 0 6 2.7964 6 6.2143v4.1429c0 3.4179-2.7 6.2143-6 6.2143h-52c-3.3 0-6-2.7964-6-6.2143v-4.1429zm144-105.64h-224c-8.85 0-16 7.4054-16 16.571v24.857c0 4.5571 3.6 8.2857 8 8.2857h240c4.4 0 8-3.7286 8-8.2857v-24.857c0-9.1661-7.15-16.571-16-16.571z" fill="currentColor" stroke="null"/>
									</g>
									</svg>
								<div class="card-body">
									<p class="card-text text-truncate" data-toggle="tooltip" data-placement="top" title="{{ $document->category }}">{{ $document->category }}</p>
									<div class="btn-group d-flex justify-content-center" role="group" aria-label="Basic example">
										<a href="{{ route('download', ['dir' => encrypt('document'), 'file' => encrypt($document->filename)]) }}" class="btn btn-sm btn-warning" title="{{ __('Download') }}" target="_blank"><i class="fa fa-download"></i></a>
									</div>
								</div>
							</div>
						</div>
					@endforeach
					@if ($data->documents->count() == 0)
						<div class="col-12 text-center">{{ __('There is no document.') }}</div>
					@endif
				</div>
			</div>
		</div>

		<div class="card card-primary" id="school-photos">
			<div class="card-header">
				<h4>{{ __('Gallery') }}</h4>
				<div class="card-header-action">
                    <div class="btn-group">
						<div class="dropdown">
							<a href="#" data-toggle="dropdown" class="btn btn-sm btn-warning dropdown-toggle">{{ (session('photoCategory')?session('photoCategory'):__('All Categories')) }}</a>
							<div class="dropdown-menu">
								@foreach ($photoCategories as $key => $category)
									<a href="{{ route('admin.school.photo.filter', ['school' => $data->id, 'token' => base64_encode($category)]) }}" class="dropdown-item">{{ $category }}</a>
								@endforeach
								<div class="dropdown-divider"></div>
								<a href="{{ route('admin.school.photo.filter', ['school' => $data->id, 'token' => base64_encode('')]) }}" class="dropdown-item">{{ __('All Categories') }}</a>
							</div>
						</div>
                    	<button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#addPhotoModal">{{ __('Add') }}</button>
						<button class="btn btn-warning btn-sm" name="deletePhoto" title="{{ __('Delete') }}">{{ __('Delete') }}</button>
                    </div>
                </div>
			</div>
			<div class="card-body">
				<div class="gallery-block cards-gallery">
					<div class="row">
						@foreach ($data->photos as $photo)
							<div class="col-md-6 col-lg-4">
								<div class="card border-0 transform-on-hover">
									<input type="checkbox" class="position-absolute mt-1 ml-1" name="photoGallery[]" value="{{ $photo->id }}" id="photo-{{ $loop->iteration }}">
									<a class="lightbox" href="{{ asset('storage/school/photo/'.$photo->name) }}">
										<img src="{{ asset('storage/school/photo/'.$photo->name) }}" alt="{{ $photo->description }}" class="card-img-top">
									</a>
								</div>
							</div>
						@endforeach
						@if ($data->photos->count() == 0)
							<div class="col-12 text-center">{{ __('There is no photo.') }}</div>
						@endif
					</div>
				</div>
			</div>
		</div>

		<div class="card card-primary chat-box" id="mychatbox">
			<div class="card-header">
				<h4>{{ __('Comments') }}</h4>
			</div>
			<div class="card-body chat-content">
				@foreach ($data->comments as $comment)
					@if ($comment->staff->id == auth()->guard('admin')->user()->id)
						<div class="chat-item chat-right" style="">
							<img src="{{ asset($comment->staff->avatar) }}">
							<div class="chat-details">
								<div class="chat-text">{!! html_entity_decode($comment->message) !!}</div>
								<div class="chat-time">{{ $comment->created_at }}</div>
							</div>
						</div>
						@continue
					@endif
					<div class="chat-item chat-left" style="">
						<img src="{{ asset('storage/avatar/'.$comment->staff->avatar) }}">
						<div class="chat-details">
							<div class="chat-text">{ !! html_entity_decode($comment->message) !! }</div>
							<div class="chat-time">{{ $comment->created_at }}</div>
						</div>
					</div>
				@endforeach
				@if ($data->comments->count() == 0)
					<div class="text-center">{{ __('There is no comment.') }}</div>
				@endif
			</div>
			<div class="card-footer">
				{{ Form::open(['route' => ['admin.school.comment.store', $data->id], 'files' => true]) }}
					{{ Form::bsTextarea(null, __('Message'), 'message', old('message'), __('Type a message'), ['class' => 'summernote-simple', 'required' => '']) }}
					<div class="text-center mt-4">
						{{ Form::submit(__('Send'), ['class' => 'btn btn-primary']) }}
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function () {
		$('[data-toggle="tooltip"]').tooltip();

		baguetteBox.run('.cards-gallery', { 
			animation: 'slideIn',
			captions: function(element) {
				return element.getElementsByTagName('img')[0].alt;
			}
		});

		$('[name="saveDocument"]').click(function(event) {
			$('#add-document-form [name="category"], #add-document-form [name="filename"]').removeClass('is-invalid');
			event.preventDefault();
			var formData = new FormData($('#add-document-form')[0]);
			$.ajax({
				url : "{{ route('admin.school.document.store', $data->id) }}",
				type: "POST",
				contentType: false, // The content type used when sending data to the server.
				cache: false, // To unable request pages to be cached
				processData: false,
				dataType: "JSON",
				data: formData,
				success: function(data)
				{
					if (data.status == true) {
						$('#addDocumentModal').modal('hide');
						window.location.reload();
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					$.each(JSON.parse(jqXHR.responseText).errors, function(name, value) {
						$('#add-document-form [name="'+name+'"]').addClass('is-invalid');
						$('#add-document-form [name="'+name+'"]').parent().find('.invalid-feedback strong').html(value[0]);
					});
				}
			});
		});

		$('[name="deleteDocument"]').click(function(event) {
			if ($('[name="schoolDocuments[]"]:checked').length > 0) {
				event.preventDefault();
				var selectedData = $('[name="schoolDocuments[]"]:checked').map(function(){
					return $(this).val();
				}).get();
				swal({
			      	title: '{{ __("Are you sure want to delete this data?") }}',
			      	text: '',
			      	icon: 'warning',
			      	buttons: ['{{ __("Cancel") }}', true],
			      	dangerMode: true,
			    })
			    .then((willDelete) => {
			      	if (willDelete) {
			      		$.ajax({
							url : "{{ route('admin.school.document.destroy') }}",
							type: "DELETE",
							dataType: "JSON",
							data: {"selectedData" : selectedData, "_token" : "{{ csrf_token() }}"},
							success: function(data)
							{
								if (data.status == true) {
									window.location.reload();
								}
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								if (JSON.parse(jqXHR.responseText).status) {
									swal("{{ __('Failed!') }}", '{{ __("Data cannot be deleted.") }}', "warning");
								} else {
									swal(JSON.parse(jqXHR.responseText).message, "", "error");
								}
							}
						});
			      	}
    			});
			} else {
				swal("{{ __('Please select a data..') }}", "", "warning");
			}
		});

		$('[name="savePhoto"]').click(function(event) {
			$('#add-photo-form [name="category"], #add-photo-form [name="photos[]"]').removeClass('is-invalid');
			event.preventDefault();
			var formData = new FormData($('#add-photo-form')[0]);
			$.ajax({
				url : "{{ route('admin.school.photo.store', $data->id) }}",
				type: "POST",
				contentType: false, // The content type used when sending data to the server.
				cache: false, // To unable request pages to be cached
				processData: false,
				dataType: "JSON",
				data: formData,
				success: function(data)
				{
					if (data.status == true) {
						$('#addPhotoModal').modal('hide');
						window.location.reload();
					}
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
					$.each(JSON.parse(jqXHR.responseText).errors, function(name, value) {
						$('#add-photo-form [name="'+name+'"]').addClass('is-invalid');
						$('#add-photo-form [name="'+name+'"]').parent().find('.invalid-feedback strong').html(value[0]);
						if (name == 'photos') {
							$('#add-photo-form [name="photos[]"]').addClass('is-invalid');
							$('#add-photo-form [name="photos[]"]').parent().find('.invalid-feedback strong').html(value[0]);
						}
						for (i = 0; i < name.length; i++) { 
							if (name == 'photos.'+i) {
								$('#add-photo-form [name="photos[]"]').addClass('is-invalid');
								$('#add-photo-form [name="photos[]"]').parent().find('.invalid-feedback strong').html(value[0]);
							}
						}
					});
				}
			});
		});

		$('[name="deletePhoto"]').click(function(event) {
			if ($('[name="photoGallery[]"]:checked').length > 0) {
				event.preventDefault();
				var selectedData = $('[name="photoGallery[]"]:checked').map(function(){
					return $(this).val();
				}).get();
				swal({
			      	title: '{{ __("Are you sure want to delete this data?") }}',
			      	text: '',
			      	icon: 'warning',
			      	buttons: ['{{ __("Cancel") }}', true],
			      	dangerMode: true,
			    })
			    .then((willDelete) => {
			      	if (willDelete) {
			      		$.ajax({
							url : "{{ route('admin.school.photo.destroy') }}",
							type: "DELETE",
							dataType: "JSON",
							data: {"selectedData" : selectedData, "_token" : "{{ csrf_token() }}"},
							success: function(data)
							{
								if (data.status == true) {
									window.location.reload();
								}
							},
							error: function (jqXHR, textStatus, errorThrown)
							{
								if (JSON.parse(jqXHR.responseText).status) {
									swal("{{ __('Failed!') }}", '{{ __("Data cannot be deleted.") }}', "warning");
								} else {
									swal(JSON.parse(jqXHR.responseText).message, "", "error");
								}
							}
						});
			      	}
    			});
			} else {
				swal("{{ __('Please select a data..') }}", "", "warning");
			}
		});
	});
</script>

<!-- Modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addDocumentModallLabel">{{ __('Add Document') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{{ Form::open(['url' => '#', 'files' => true, 'id' => 'add-document-form']) }}
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							{{ Form::bsSelect('col-12', __('Category'), 'category', $documentCategories, old('category'), __('Select'), ['placeholder' => __('Select')], [], true) }}

							{{ Form::bsFile('col-12', __('File'), 'filename', old('filename'), [], [__("File with PDF/JPG/PNG format up to 5MB.")], true) }}
						</div>
					</div>
				</div>
				<div class="modal-footer bg-whitesmoke d-flex justify-content-center">
					{{ Form::button(__('Save'), ['class' => 'btn btn-primary', 'name' => 'saveDocument']) }}
					{{ Form::button(__('Cancel'), ['class' => 'btn btn-secondary', ' data-dismiss' => 'modal']) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="addPhotoModal" tabindex="-1" role="dialog" aria-labelledby="addPhotoModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPhotoModallLabel">{{ __('Add Photo') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			{{ Form::open(['url' => '#', 'files' => true, 'id' => 'add-photo-form']) }}
				<div class="modal-body">
					<div class="container-fluid">
						<div class="row">
							{{ Form::bsSelect('col-12', __('Category'), 'category', $photoCategories, old('category'), __('Select'), ['placeholder' => __('Select')], [], true) }}

							{{ Form::bsFile('col-12', __('School Photo'), 'photos[]', old('photos[]'), [], [__("Photo with JPG/PNG format up to 5MB.")], true) }}

							{{ Form::bsTextarea('col-12', __('Description'), 'description[]', old('description'), __('Description')) }}
						</div>
					</div>
				</div>
				<div class="modal-footer bg-whitesmoke d-flex justify-content-center">
					{{ Form::button(__('Save'), ['class' => 'btn btn-primary', 'name' => 'savePhoto']) }}
					{{ Form::button(__('Cancel'), ['class' => 'btn btn-secondary', ' data-dismiss' => 'modal']) }}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@endsection