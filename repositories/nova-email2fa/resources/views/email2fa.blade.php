@slot('header')
@component('mail::header', ['url' => config('app.url')])
    {{ config('app.name') }}
@endcomponent
@endslot

@component('mail::message')
{{ $email_2fa }}
@endcomponent
