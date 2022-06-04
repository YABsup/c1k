@lang('mail_order.status') {{ $exchange->id }} - {{$exchange->status->desc}} <br>
@lang('mail_order.first_name'): {{$exchange->first_name}} <br>
E-mail: {{ $exchange->email }} <br>



@if($exchange->viber)
    Viber: {{ $exchange->viber }} <br>
@endif
@if($exchange->telegram)
    Telegram: {{ $exchange->telegram }} <br>
@endif
@if($exchange->whatsapp)
    WhatsApp: {{ $exchange->whatsapp }} <br>
@endif
