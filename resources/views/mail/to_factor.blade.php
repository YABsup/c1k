@component('mail::message',['user'=>$user,'to_factor'=>$to_factor])
# 2fa code

{{ $to_factor }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
