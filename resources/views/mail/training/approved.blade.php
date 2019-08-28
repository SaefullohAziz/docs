@component('mail::message')
Yth. Bpk / Ibu Pimpinan

{{ $school }}

Terima kasih anda telah melakukan registrasi.

Email ini adalah BUKTI BOOKING anda dalam training ini. Silakan selesaikan transaksi anda sesuai dengan informasi dibawah ini.

@component('mail::panel')
{{ __('Booking Code') }} : **{{ $bookingCode }}**  
{{ __('Nominal') }} : Rp. {{ $nominal }}  
{{ __('Booking Time') }} : {{ $bookingTime }}  
{{ __('Expired Time') }} : {{ $expiredTime }}  
@endcomponent

@component('mail::panel')
Informasi Rekening ({{ $bank }}):  
No. Rekening : {{ $bankAccountNumber }}  
A/N : {{ $bankAccountOnBehalfOf}}  
@endcomponent

@component('mail::table')
| **{{ __('Code') }}** | **{{ __('Detail') }}**   |
| :------------------: |:-------------------------|
|                      | **Training {{ $type }}** |
| {{ $bookingCode }}   | {{ $school }}            |
|                      | PIC: {{ $pic }}          |
@endcomponent

* Kode booking adalah kode yang didapatkan setiap kali mendaftarkan diri di training Axioo Class Program, yang selanjutnya dapat digunakan untuk konfirmasi pembayaran. 
* Kode booking tidak dapat digunakan (hangus otomatis) setelah tiga jam sejak pendaftaran. 
* Setelah kode booking hangus, slot yang tadinya terisi akan dibuka kembali untuk peserta lain. 
* Pembayaran Commitment Fee dapat dilakukan dengan mentransfer ke rekening dan sesuai nominal yang tertera diatas. 
* Kode booking, diharapkan dapat diisi di berita acara saat transfer. 
* Konfirmasi pembayaran dapat dilakukan melalui [{{ route('payment.index') }}]({{ route('payment.index') }}) atau klik tombol dibawah ini.

@component('mail::button', ['url' => route('payment.index')])
{{ __('Confirm') }}
@endcomponent

@component('mail::signature')
@endcomponent
@endcomponent
