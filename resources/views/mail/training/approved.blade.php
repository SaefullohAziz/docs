@component('mail::message')
Yth. Bpk / Ibu Pimpinan

{{ $school }}

Terima kasih anda telah melakukan registrasi.

Email ini adalah BUKTI <i>BOOKING</i> anda dalam training ini. Silakan selesaikan transaksi anda sesuai dengan informasi dibawah ini.

@component('mail::panel')
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

* Tagihan akan otomatis dibuat untuk setiap pendaftaran training. 
* Tagihan harus segera dibayarkan dan dikonfirmasikan sebelum (hangus otomatis) setelah tiga jam sejak pendaftaran. 
* Setelah pendaftaran training anda hangus, slot yang tadinya terisi akan dibuka kembali untuk peserta lain. 
* Pembayaran <i>Commitment Fee</i> dapat dilakukan dengan mentransfer ke rekening dan sesuai nominal yang tertera diatas. 
* Harap cantumkan Kode <i>Booking</i> di berita acara saat transfer untuk mempercepat proses. 
* Konfirmasi pembayaran dapat dilakukan melalui [{{ route('payment.index') }}]({{ route('payment.index') }}) atau klik tombol dibawah ini.

@component('mail::button', ['url' => route('payment.index')])
{{ __('Confirm') }}
@endcomponent

@component('mail::signature')
@endcomponent
@endcomponent
