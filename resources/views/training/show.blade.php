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
					<fieldset class="requirement-for-basic d-none">
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
					<fieldset class="requirement-for-seagate d-none">
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
					<fieldset class="requirement-for-iot d-none">
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
					<fieldset class="requirement-for-microsoft d-none">
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
					<fieldset class="requirement-for-electronic d-none">
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
					<fieldset class="requirement-for-adobe-photoshop d-none">
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
					<div class="row">
                        <div class="col-sm-6">
                            <fieldset>
                                <legend>{{ __('Data') }}</legend>
                                {{ Form::bsSelect(null, __('Type'), 'type', $types, $training->type, __('Select'), ['placeholder' => __('Select'), 'disabled' => '']) }}

                                {{ Form::bsSelect(($training->type=='Basic (ToT)'||$training->type=='Adobe Photoshop'?'d-block':'d-none'), __('Implementation'), 'implementation', $implementations, $training->implementation, __('Select'), ['placeholder' => __('Select'), 'disabled' => ''], [__('This department is a department synchronized with ACP.')]) }}
                            </fieldset>
							<fieldset class="{{ (old('type')=='Basic (ToT)'?'d-block':'d-none') }}">
								<legend>{{ __('Basic (ToT)') }}</legend>
								{{ Form::bsText(null, __('Approval Code'), 'approval_code', $training->approval_code, __('Approval Code'), ['disabled' => ''], [__('Filled with AGP payment receipt number (example: MH0000001234).')]) }}

								{{ Form::bsCheckboxList(null, __('Room Type'), 'room_type[]', $roomTypes, ( ! empty($training->room_type)?implode($training->room_type, ', '):[]), ['disabled' => '']) }}

								{{ Form::bsText(null, __('Room Size'), 'room_size', $training->room_size, __('Room Size'), ['disabled' => ''], [__('Example: 5x5x5 (LxWxH)'), __('In accordance with the provisions of the Axioo Construction Guidelines.')]) }}
							</fieldset>
							<fieldset class="{{ (old('type')=='Elektronika Dasar'?'d-block':'d-none') }}">
								<legend>{{ __('Basic Electronics') }}</legend>
								{{ Form::bsInlineRadio(null, __('Do you have assets?'), 'has_asset', ['2' => __('Already'), '1' => __('Not yet')], $training->has_asset, ['disabled' => '']) }}
							</fieldset>
							<fieldset class="{{ (old('type')=='IoT'?'d-block':'d-none') }}">
								<legend>{{ __('IoT') }}</legend>
								{{ Form::bsUploadedFile(null, __('Selection Result'), 'selection_result', 'training/selection-result', $training->selection_result) }}
							</fieldset>
                        </div>
                        <div class="col-sm-6">
							<fieldset>
								<legend>{{ __('Other Data') }}</legend>
								{{ Form::bsUploadedFile(null, __('Commitment Letter'), 'commitment_letter', 'training/commitment-letter', $training->approval_letter_of_commitment_fee) }}
							</fieldset>
							<fieldset>
								<legend>{{ __('Person in Charge (PIC)') }}</legend>
								{{ Form::bsText(null, __('PIC Name'), 'pic_name', $training->pic[0]->name, __('PIC Name'), ['disabled' => '']) }}

								{{ Form::bsText(null, __('PIC Position'), 'pic_position', $training->pic[0]->position, __('PIC Position'), ['disabled' => '']) }}

								{{ Form::bsPhoneNumber(null, __('PIC Phone Number'), 'pic_phone_number', $training->pic[0]->phone_number, __('PIC Phone Number'), ['maxlength' => '13', 'disabled' => '']) }}

								{{ Form::bsText(null, __('PIC E-Mail'), 'pic_email', $training->pic[0]->email, __('PIC E-Mail'), ['disabled' => '']) }}
							</fieldset>
                        </div>
                        <div class="col-12">
                            <fieldset>
                                <legend>{{ __('Participant') }}</legend>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('No.') }}</th>
                                                <th>{{ __('Name') }}</th>
												<th>{{ __('Gender') }}</th>
												<th>{{ __('Position') }}</th>
												<th>{{ __('Phone Number') }}</th>
												<th>{{ __('E-Mail') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($training->trainingParticipant as $trainingParticipant)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $trainingParticipant->participant->name }}</td>
                                                    <td>{{ $trainingParticipant->participant->gender }}</td>
                                                    <td>{{ $trainingParticipant->participant->position }}</td>
                                                    <td>{{ $trainingParticipant->participant->phone_number }}</td>
                                                    <td>{{ $trainingParticipant->participant->email }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
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
        
	});
</script>
@endsection