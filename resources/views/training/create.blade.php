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

			{{ Form::open(['route' => 'training.store', 'files' => true]) }}
				<div class="card-body">
					@if ($type == 'Basic (ToT)')
						<fieldset>
							<legend>{{ __('Registration Requirements') }}</legend>
							<ol>
								<li>Merupakan Sekolah Binaan Axioo Class Program.</li>
								<li>Peserta training berjumlah 2 (dua) guru.</li>
								<li>Merupakan sekolah binaan Axioo Class Program (level B), atau telah menyelesaikan administrasi bagi sekolah yang mengajukan program akselerasi AGP/FTP.</li>
								<li>Mengirimkan 2 (dua) orang peserta training, dikhususkan kepada Kaprodi / Kajur serta Guru Produktif.</li>
								<li>Peserta training membawa Laptop masing-masing, dengan spesifikasi minimum : ukuran layar 11.6”, processor Dual Core, 2Gb RAM, Wifi, Win 7 32 bit, Office 2010, IE 10, Adobe Plugin & Active X, .net framework 4.</li>
							</ol>

							Mohon segera melakukan pendaftaran dan konfirmasi kedatangan untuk mendapatkan slot peserta pelatihan. Sehubungan jumlah kursi yang terbatas, konfirmasi setelah penutupan pendaftaran dan atau jika terjadi over quota peserta, akan secara otomatis didaftarkan untuk jadwal pelatihan periode berikutnya.
						</fieldset>
					@elseif ($type == 'Seagate')
						<fieldset>
							<legend>{{ __('Registration Requirements') }}</legend>
							<ol>
								<li>Merupakan Sekolah Binaan Axioo Class Program.</li>
								<li>Peserta training berjumlah 2 (dua) guru.</li>
								<li>Merupakan sekolah binaan Axioo Class Program (level B), atau telah menyelesaikan administrasi bagi sekolah yang mengajukan program akselerasi AGP/FTP.</li>
								<li>Mengirimkan 2 (dua) orang peserta training, dikhususkan Guru Produktif yang mengajar di kelas ACP.</li>
								<li>Peserta training membawa Laptop masing-masing, dengan spesifikasi minimum : ukuran layar 11.6”, processor Dual Core, 2Gb RAM, Wifi, Win 7 32 bit, Office 2010, IE 10, Adobe Plugin & Active X, .net framework 4.</li>
								<li>Telah membayar Commitment Fee.</li>
								<li>Biaya transportasi dan akomodasi ditanggung oleh masing-masing peserta.</li>
							</ol>

							Mohon segera melakukan pendaftaran dan konfirmasi kedatangan untuk mendapatkan slot peserta pelatihan. Sehubungan jumlah kursi yang terbatas, konfirmasi setelah penutupan pendaftaran dan atau jika terjadi over quota peserta, akan secara otomatis didaftarkan untuk jadwal pelatihan periode berikutnya.
						</fieldset>
					@elseif ($type == 'IoT')
						<fieldset>
							<legend>{{ __('Registration Requirements') }}</legend>
							<ol>
								<li>Merupakan Sekolah Binaan Axioo Class Program.</li>
								<li>Peserta training berjumlah 2 (dua) guru.</li>
								<li>Merupakan sekolah binaan Axioo Class Program (level B), atau telah menyelesaikan administrasi bagi sekolah yang mengajukan program akselerasi AGP/FTP.</li>
								<li>Mengirimkan 2 (dua) orang peserta training, dikhususkan Guru Produktif yang mengajar di kelas ACP.</li>
								<li>Peserta training membawa Laptop masing-masing, dengan spesifikasi minimum : ukuran layar 11.6”, processor Dual Core, 2Gb RAM, Wifi, Win 7 32 bit, Office 2010, IE 10, Adobe Plugin & Active X, .net framework 4.</li>
								<li style="font-size: 18px;"><b>Telah mengerjakan tes seleksi. Soal dapat diunduh {{ link_to_route('download',  'disini', ['dir' => encrypt('file'), 'file' => encrypt('Preliminary-Test-Axioo-Class-Program-Rev.pdf')], ['title' => 'Download soal tes seleksi']) }}</b></li>
								<li>Telah membayar Commitment Fee.</li>
								<li>Biaya transportasi dan akomodasi ditanggung oleh masing-masing peserta.</li>
							</ol>

							Mohon segera melakukan pendaftaran dan konfirmasi kedatangan untuk mendapatkan slot peserta pelatihan. Sehubungan jumlah kursi yang terbatas, konfirmasi setelah penutupan pendaftaran dan atau jika terjadi over quota peserta, akan secara otomatis didaftarkan untuk jadwal pelatihan periode berikutnya.
						</fieldset>
					@elseif ($type == 'Microsoft Software Fundamental' || $type == 'Microsoft Network Fundamental')
						<fieldset>
							<legend>{{ __('Registration Requirements') }}</legend>
							<ol>
								<li>Merupakan Sekolah Binaan Axioo Class Program.</li>
								<li>Peserta training berjumlah 2 (dua) guru.</li>
								<li>Peserta wajib memiliki account microsoft : @hotmail, outlook atau live</li>
								<li>Menyiapkan transportasi dan akomodasi peserta selama mengikuti kegiatan training.</li>
								<li>Peserta training diwajibkan membawa perlengkapan masing-masing.</li>
								<li>Peserta wajib hadir tepat waktu selama kegiatan training. Ketertinggalan materi dikarenakan keterlambatan kehadiran di luar tanggung jawab Trainer dan Panitia.</li>
								<li>Peserta training diwajibkan membawa perlengkapan masing-masing : 
								- 1 unit Laptop dengan spesifikasi minimum : ukuran layar minimal 11.6”, processor Dual Core,      2Gb RAM, Wifi, Win 7 32 bit, Office 2010, IE 10, Adobe Plugin & Active X, .net framework 4 dan Kabel roll panjang minimal 5 meter, 4 stop kontak.</li>
								<li>Menyelesaikan pembayaran Commitment Fee for Advance Training sebesar Rp 3.000.000 (tiga juta rupiah) per sekolah.</li>
							</ol>

							Training ini bersifat gratis, Commitment Fee akan dikembalikan 100% ke sekolah apabila kedua peserta perwakilan sekolah hadir mengikuti keseluruhan sessi training dari hari pertama hingga hari terakhir. Sebaliknya, apabila kedua atau salah satu peserta terdaftar tidak hadir saat pelaksanaan training dan atau tidak mengikuti salah satu sessi training, maka Commitment Fee dinyatakan hangus sesuai dengan ketentuan di Surat Komitmen Trainning.
						</fieldset>
					@elseif ($type == 'Elektronika Dasar')
						<fieldset>
							<legend>{{ __('Registration Requirements') }}</legend>
							<ol>
								<li>Merupakan Sekolah Binaan Axioo Class Program.</li>
								<li>Sudah memahami pengetahuan komponen dasar elektronika seperti cara membaca resistor dan menentukan nilai kapasitor, dan lainnya. Serta memahami pengatahuan dasar peralatan elektronika dasar avo meter/multitester dan lainnya.</li>
								<li>Dapat mengoperasikan komputer dan </i>smartphone</i>.</li>
								<li>Peserta training wajib membawa perangkat bahan praktek laptop mati lebih dari dari satu.</li>
								<li>Menyiapkan transportasi dan akomodasi peserta selama mengikuti kegiatan training.</li>
							</ol>

							Training ini bersifat gratis, Commitment Fee akan dikembalikan 100% ke sekolah apabila kedua peserta perwakilan sekolah hadir mengikuti keseluruhan sessi training dari hari pertama hingga hari terakhir. Sebaliknya, apabila kedua atau salah satu peserta terdaftar tidak hadir saat pelaksanaan training dan atau tidak mengikuti salah satu sessi training, maka Commitment Fee dinyatakan hangus sesuai dengan ketentuan di Surat Komitmen Trainning.
						</fieldset>
					@elseif ($type == 'Adobe Photoshop')
						<fieldset>
							<legend>{{ __('Registration Requirements') }}</legend>
							<ol>
								<li>Merupakan Sekolah Binaan Axioo Class Program.</li>
								<li>Menyiapkan transportasi dan akomodasi peserta selama mengikuti kegiatan training.</li>
								<li>Wajib Mengirimkan 2 (dua) orang peserta training pada periode training bulan Agustus 2018, apabila tidak bisa mengikuti training pada periode bulan Agustus 2018 maka sekolah bisa mengikuti training kembali di tahun berikutnya.</li>
								<li>Peserta Training diwajibkan sudah memahami pengetahuan editing gambar, pembuatan efek, atau sejenisnya dengan menggunakan adobe photoshop.</li>
								<li>Peserta training wajib membawa notebook dengan spesifikasi minimum : ukuran layar 14”, processor Core i3, 2Gb RAM, Free 6 GB Hardisk, Wifi, Win 7 Pro 64/32 bit, Office 2010, IE 10, Adobe Plugin & Active X, .net framework 4.5.2 , Sudah Terinstal Adobe Photoshop CS 6.</li>
								<li>Menyelesaikan pembayaran Commitment Fee for Advance Training sebesar Rp 3.000.000 (tiga juta rupiah) per sekolah. </li>
							</ol>

							Training ini bersifat gratis, Commitment Fee akan dikembalikan 100% ke sekolah apabila kedua peserta perwakilan sekolah hadir mengikuti keseluruhan sessi training dari hari pertama hingga hari terakhir. Sebaliknya, apabila kedua atau salah satu peserta terdaftar tidak hadir saat pelaksanaan training dan atau tidak mengikuti salah satu sessi training, maka Commitment Fee dinyatakan hangus sesuai dengan ketentuan di Surat Komitmen Trainning.
						</fieldset>
					@elseif ($type == 'Dicoding' || $type == 'LS-Cable' || $type == 'Surveillance')
						<fieldset class="requirement-for-most-training d-none">
							<legend>{{ __('Registration Requirements') }}</legend>
							<ol>
								<li>Merupakan Sekolah Binaan Axioo Class Program.</li>
								<li>Menyiapkan transportasi dan akomodasi peserta selama mengikuti kegiatan training.</li>
								<li>Peserta training diwajibkan membawa perlengkapan masing-masing.</li>
								<li>Menyelesaikan pembayaran Commitment Fee for Advance Training sebesar Rp 3.000.000 (tiga juta rupiah) per sekolah. </li>
							</ol>

							Training ini bersifat gratis, Commitment Fee akan dikembalikan 100% ke sekolah apabila kedua peserta perwakilan sekolah hadir mengikuti keseluruhan sessi training dari hari pertama hingga hari terakhir. Sebaliknya, apabila kedua atau salah satu peserta terdaftar tidak hadir saat pelaksanaan training dan atau tidak mengikuti salah satu sessi training, maka Commitment Fee dinyatakan hangus sesuai dengan ketentuan di Surat Komitmen Trainning.
						</fieldset>
					@endif

					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                <legend>{{ __('Data') }}</legend>
								{{ Form::bsHidden('d-none', null, 'type', $type, null) }}

								{{ Form::bsHidden('d-none', null, 'implementation', $implementation, null) }}
                            </fieldset>
							<fieldset class="{{ ($type=='Basic (ToT)'?'d-block':'d-none') }}">
								<legend>{{ __('Basic (ToT)') }}</legend>
								{{ Form::bsText(null, __('Approval Code'), 'approval_code', old('approval_code'), __('Approval Code'), [], [__('Filled with AGP payment receipt number (example: MH0000001234).')]) }}

								{{ Form::bsCheckboxList(null, __('Room Type'), 'room_type[]', $roomTypes) }}

								{{ Form::bsText(null, __('Room Size'), 'room_size', old('room_size'), __('Room Size'), [], [__('Example: 5x5x5 (LxWxH)'), __('In accordance with the provisions of the Axioo Construction Guidelines.')]) }}
							</fieldset>
							<fieldset class="{{ ($type=='Elektronika Dasar'?'d-block':'d-none') }}">
								<legend>{{ __('Basic Electronics') }}</legend>
								{{ Form::bsInlineRadio(null, __('Do you have assets?'), 'has_asset', ['2' => __('Already'), '1' => __('Not yet')], old('has_asset'), []) }}
							</fieldset>
							<fieldset class="{{ ($type=='IoT'?'d-block':'d-none') }}">
								<legend>{{ __('IoT') }}</legend>
								{{ Form::bsFile(null, __('Selection Result'), 'selection_result', old('selection_result'), [], [__('File with PDF/JPG/PNG format up to 5MB.')]) }}
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Participant') }}</legend>
								{{ Form::bsSelect(null, __('Participant'), 'participant', $participants, old('participant'), __('Select'), ['placeholder' => __('Select')]) }}
								<fieldset>
									<legend>{{ __('Selected Participant') }}</legend>
									<ul class="list-group list-group-flush participants">

									</ul>
									@if ($errors->has('participant_id'))
										<div class="text-danger">
											<strong>{{ $errors->first('participant_id') }}</strong>
										</div>
									@endif
								</fieldset>
							</fieldset>
							<fieldset>
								<legend>{{ __('Other Data') }}</legend>
								{{ Form::bsFile(null, __('Commitment Letter'), 'approval_letter_of_commitment_fee', old('approval_letter_of_commitment_fee'), ['required' => ''], [__('File with PDF format up to 5MB.')]) }}
							</fieldset>
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
		$('select[name="participant"]').change(function () {
	    	if ($(this).val() != '') {
	    		if ($('[name="participant_id[]"][value="'+$(this).val()+'"]').length) {
					swal('{{ __("Participant have been selected.") }}', '', 'warning');
					$('select[name="participant"]').val(null).change();
				} else {
					$.ajax({
						url : "{{ route('get.teacher') }}",
						type: "POST",
						dataType: "JSON",
						data: {'_token' : '{{ csrf_token() }}', 'teacher' : $(this).val()},
						success: function(data)
						{
							if (data.result.teaching_status != 'yes') {
								swal('{{ __("Participant must active teaching status, try to update on teacher menu.") }}', '', 'warning');
								$('select[name="participant"]').val(null).change();
							} else {
								$('.participants').append('<li class="participant list-group-item d-flex justify-content-between align-items-center"><input type="hidden" name="participant_id[]" value="'+data.result.id+'">'+data.result.name+'<a href="javascript:void(0);" onclick="deleteParticipant('+"'"+data.result.id+"'"+')" class="badge badge-danger badge-pill" title="{{ __('Delete') }}"><i class="fas fa-trash-alt"></i></a></li>');
								$('select[name="participant"]').val(null).change();
							}
						},
						error: function (jqXHR, textStatus, errorThrown)
						{
							
						}
					});
				}
	    	}
	    });

		$('input[name="pic"]').click(function () {
			if ($('input[name="pic"][value="2"]').is(':checked')) {
				getPic();
			} else if ($('input[name="pic"][value="1"]').is(':checked')) {
				$('input[name="pic_name"]').parent().parent('div').removeClass('d-none').addClass('d-block');
	    		$('[name="pic_name"], [name="pic_position"], [name="pic_phone_number"], [name="pic_email"]').prop('required', true).prop('disabled', false).val('');
			}
		})
	});

	function deleteParticipant(id) {
		$('input[name="participant_id[]"][value="'+id+'"]').closest('.participant').remove();
        return false;
	}

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