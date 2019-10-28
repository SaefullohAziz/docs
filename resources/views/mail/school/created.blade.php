@component('mail::message')
@component('mail::banner')
@endcomponent

Terima kasih telah melakukan pendaftaran sekolah binaan Axioo. Berikut detail <i>username</i> dan <i>password</i> anda untuk masuk ke sistem.

@component('mail::table')
|                   |                 |
| ----------------- | ----------------|
| <i>Username</i>   | {{ $username }} |
| <i>Password</i>   | {{ $password }} |
@endcomponent

Silahkan <i>login</i> melalui [www.axiooclassprogram.org](http://www.axiooclassprogram.org "www.axiooclassprogram.org") di menu <i>login</i> dengan menggunakan akun diatas.

@component('mail::signature')
@endcomponent
@endcomponent