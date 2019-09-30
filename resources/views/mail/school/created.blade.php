@component('mail::message')
@component('mail::banner')
@endcomponent

Terima kasih telah melakukan pendaftaran sekolah binaan Axioo. Untuk pengecekan status pendaftaran, bisa langsung mengunjungi tautan dibawah ini:

@component('mail::button', ['url' => $url.'/?code='.$code])
Cek Status
@endcomponent

<center>Atau</center>

Kunjungi: {{ link_to($url, $url, ['title' => config('app.name')]) }} dan masukkan kode {{ ($code) }} pada kolom yang disediakan.

@component('mail::signature')
@endcomponent
@endcomponent