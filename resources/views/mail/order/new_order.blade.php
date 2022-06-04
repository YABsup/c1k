@component('mail::message')
    # @lang('mail_order.status') {{ $exchange->id }} - {{$exchange->status->desc}}


    @lang('mail_order.first_name'): {{$exchange->first_name}}
    E-mail: {{ $exchange->email }}
    Viber: {{ $exchange->viber }}
    Telegram: {{ $exchange->telegram }}
    WhatsApp: {{ $exchange->whatsapp }}

    @if( $exchange->bank_name != null )
        Наименование банка: {{ $exchange->bank_name }}
    @endif

    @if( $exchange->bank_address != null )
        Юридический адрес банка: {{ $exchange->bank_address }}
    @endif

    @if( $exchange->bank_account != null )
        Полное имя, фамилия владельца счета: {{ $exchange->bank_account }}
    @endif

    @if( $exchange->bank_iban != null )
        Номер счета (IBAN): {{ $exchange->bank_iban }}
    @endif




    @if( (strpos($exchange->pair->symbol, 'CARD') !== false) || (strpos($exchange->pair->symbol, 'P24') !== false) || (strpos($exchange->pair->symbol, 'MONO') !== false) || (strpos($exchange->pair->symbol, 'SBER') !== false) || (strpos($exchange->pair->symbol, 'ACRUB') !== false) )
        @if( 'C1kworldex@gmail.com' == $to_email)
            @if($user->verified)
                **Пользователь прошол верификацию карты **
            @else
                **Пользователь НЕ прошол верификацию карты **
            @endif
        @else

        @endif
    @endif


    @isset($exchange->address_from)
        **Адресс/номер карты: {{$exchange->address_from }}**
    @endisset
    @isset($exchange->address_to)
        **Адресс/номер карты: {{$exchange->address_to }}**
    @endisset



    @if($exchange->side == 'buy')
        @lang('mail_order.side'): {{$exchange->pair->base_currency->name}} / {{$exchange->pair->quote_currency->name}}
        Отдаете:: {{$exchange->amount_take}} {{$exchange->pair->base_currency->name}}
        Получаете: {{$exchange->amount_get}} {{$exchange->pair->quote_currency->name}}

        @if( in_array($exchange->pair->base_currency->code, [139, 138, 140, 137, 125, 130, 108, 124, 127, 129, 126, 128]) )
            @if( !$user->verified)
                ** Для завершения обмена нужно <a href="https://c1k.world/account/card_verify">верифицировать карту</a> **
            @endif
        @endif

    @else
        @lang('mail_order.side'): {{$exchange->pair->quote_currency->name}} / {{$exchange->pair->base_currency->name}}
        Отдаете: {{$exchange->amount_take}} {{$exchange->pair->quote_currency->name}}
        Получаете: {{$exchange->amount_get}} {{$exchange->pair->base_currency->name}}

        @if( in_array($exchange->pair->quote_currency->code, [139, 138, 140, 137, 125, 130, 108, 124, 127, 129, 126, 128]) )
            @if( !$user->verified)
                ** Для завершения обмена нужно <a href="https://c1k.world/account/card_verify">верифицировать карту</a> **
            @endif
        @endif

    @endif

    @isset($exchange->address_from)
        @lang('mail_order.wallet'): {{$exchange->address_from}}
    @endisset
    @isset($exchange->address_to)
        @lang('mail_order.wallet'): {{$exchange->address_to}}
    @endisset

    @lang('mail_order.thanks')

    @lang('mail_order.operator')

    @lang('mail_order.start'): {{$exchange->created_at}}
    @lang('mail_order.end'): @lang('mail_order.status_not_end')
    @lang('mail_order.link'): [https://c1k.world/?step=2&uuid={{$exchange->uuid}}](https://c1k.world/?step=2&uuid={{$exchange->uuid}})
    ```


    @component('mail::button', ['url' => '<a href="https://c1k.world/?step=2&uuid={{$exchange->uuid}}&confirm={{$confirm}}">'])
        Confirm
    @endcomponent
    ```


    @lang('mail_order.our_projects')
    [Crypto Exchange](https://c1k.world">https://c1k.world)
    [Crypto consulting]https://c1k-fin.world">https://c1k-fin.world)
@endcomponent
