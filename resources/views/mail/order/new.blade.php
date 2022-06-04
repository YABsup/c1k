@lang('mail_order.status') {{ $exchange->id }} - {{$exchange->status->desc}} <br>
@lang('mail_order.first_name'): {{$exchange->first_name}} <br>
E-mail: {{ $exchange->email }} <br>
Viber: {{ $exchange->viber }} <br>
Telegram: {{ $exchange->telegram }} <br>


@if($exchange->whatsapp)
    WhatsApp: {{ $exchange->whatsapp }} <br>
@endif


@if( (strpos($exchange->pair->symbol, 'CARD') !== false) || (strpos($exchange->pair->symbol, 'P24') !== false) || (strpos($exchange->pair->symbol, 'MONO') !== false) || (strpos($exchange->pair->symbol, 'SBER') !== false) || (strpos($exchange->pair->symbol, 'ACRUB') !== false) )
        @if( 'C1kworldex@gmail.com' == $to_email)
            @if($user->verified)
                <b>Пользователь прошол верификацию карты </b> <br />
            @else
                <b>Пользователь НЕ прошол верификацию карты </b> <br />
            @endif
        @else

        @endif
@endif


@isset($exchange->address_from)
<b>Адресс/номер карты: {{$exchange->address_from }}<b><br>
@endisset
@isset($exchange->address_to)
<b>Адресс/номер карты: {{$exchange->address_to }}<b><br>
@endisset


    <br>
    @if($exchange->side == 'buy')
    @lang('mail_order.side'): {{$exchange->pair->base_currency->name}} / {{$exchange->pair->quote_currency->name}} <br>
    Отдаете: {{$exchange->amount_take}} {{$exchange->pair->base_currency->name}} <br>
    Получаете: {{$exchange->amount_get}} {{$exchange->pair->quote_currency->name}} <br>

    @if( in_array($exchange->pair->base_currency->code, [139, 138, 140, 137, 125, 130, 108, 124, 127, 129, 126, 128]) )
        @if( !$user->verified)
            <br /><b> Для завершения обмена нужно <a href="https://c1k.world/account/card_verify">верифицировать карту</a> </b><br />
         @endif
    @endif

    @else
    @lang('mail_order.side'): {{$exchange->pair->quote_currency->name}} / {{$exchange->pair->base_currency->name}}  <br>
    Отдаете: {{$exchange->amount_take}} {{$exchange->pair->quote_currency->name}} <br>
    Получаете: {{$exchange->amount_get}} {{$exchange->pair->base_currency->name}} <br>

    @if( in_array($exchange->pair->quote_currency->code, [139, 138, 140, 137, 125, 130, 108, 124, 127, 129, 126, 128]) )
        @if( !$user->verified)
            <br /><b> Для завершения обмена нужно <a href="https://c1k.world/account/card_verify">верифицировать карту</a> </b><br />
            @endif
    @endif

    @endif

    @isset($exchange->address_from)
    @lang('mail_order.wallet'): {{$exchange->address_from}} <br>
    @endisset
    @isset($exchange->address_to)
    @lang('mail_order.wallet'): {{$exchange->address_to}} <br>
    @endisset

    @lang('mail_order.thanks') <br>

    @lang('mail_order.operator') <br>

    @lang('mail_order.start'): {{$exchange->created_at}} <br>
    @lang('mail_order.end'): @lang('mail_order.status_not_end') <br>
    @lang('mail_order.link'): <a href="https://c1k.world/?step=2&uuid={{$exchange->uuid}}"> https://c1k.world/?step=2&uuid={{ $exchange->uuid }} </a> <br>
    <hr><br />
    @lang('mail_order.confirm'): <a href="https://c1k.world/?step=2&uuid={{ $exchange->uuid }}&confirm={{$confirm}}"> https://c1k.world/?step=2&uuid={{ $exchange->uuid }} </a> <br>

    <hr />
    <br>
    @lang('mail_order.our_projects') <br>
    Crypto Exchange: <a href="https://c1k.world">https://c1k.world</a> <br>
    Crypto consulting: <a href="https://c1k-fin.world">https://c1k-fin.world</a> <br>
