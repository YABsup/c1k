@component('mail.order.components.contacts',[
    'exchange'=>$exchange,
])
@endcomponent


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




@component('mail.order.components.wallet',[
    'exchange'=>$exchange,
])
@endcomponent

<br>
@if( $exchange->pair->city_id != 186 )
    Город: {{ $exchange->pair->city->name }} <br />
@endif


@if( 'C1kworldex@gmail.com' == $to_email)

    @if( $exchange->sepa != null )

        <?php
        $sepa_desc = [
            'purpose_of_payment' => 'Назначение платежа',
            'street' => 'Улица и номер дома',
            'city' => 'Город \ Населенный пункт',
            'postcode' => 'Почтовый индекс',
            'reason' => 'Скакой целью вы отправляете средства (селект со списком выбра)',
            'bank_owner' => 'Кому принадлежит банковский счет (селект со списком выбра)',
            'site' => 'Веб-сайт компании',
            'dir_name' => 'Имя директора',
            'company_address' => 'Адрес отправителя (компании)',
            'swift_bic'   => 'Swift/bic',
            'field_of_activity' => 'Cфера деятельности',
        ]
        ?>


        @foreach( $exchange->sepa as $sepa_key=>$sepa_value )
            {{ $sepa_desc[$sepa_key]??$sepa_key }}:{{$sepa_value}}
        @endforeach

    @endif

@endif


@if($exchange->side == 'buy')

    <?php

    $coef = 0;
    if( $exchange->pair->bid_coef > 1)
    {
        $coef = (round($exchange->pair->bid_coef*1-1, 8)*100).'% с обменного сервиса';
    }else{
        $coef = (round(1-$exchange->pair->bid_coef*1, 8)*100).'% с клиента';
    }

    ?>



    @lang('mail_order.side'): {{$exchange->pair->base_currency->name}} / {{$exchange->pair->quote_currency->name}} <br>
    Отдаете: {{ $exchange->amount_take*1}} {{$exchange->pair->base_currency->name}} <br>
    Получаете: {{ $exchange->amount_get*1}} {{$exchange->pair->quote_currency->name}} <br>


    @if( in_array($exchange->pair->base_currency->code, [139, 138, 140, 137, 125, 130, 108, 124, 127, 129, 126, 128]) )
        @if( !$user->verified)
            <br /><b> Для завершения обмена нужно <a href="https://c1k.world/account/card_verify">верифицировать карту</a> </b><br />
        @endif
    @endif

    @if( ( $exchange->pair->base_currency->currency_type == 'cash' ) || ( $exchange->pair->quote_currency->currency_type == 'cash' )  )
        Комиссия за проведение сделки: {{ $coef }} <br />
    @elseif( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->base_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  )
        Комиссия за проведение сделки: {{ $coef }} <br />
    @elseif( ( $exchange->pair->quote_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->quote_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  )
        Комиссия за проведение сделки: {{ $coef }} <br />
    @endif

    @if( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->base_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  )
        Будьте внимательны! Фиксация курса обмена в сделках не со стэйбл коинами (BTC, ETH, LTC и т.п) производится исключительно после отправки со стороны клиента, непосредственного захода к нам на кошелек и подтверждения обработки в сети (1-3 подтверждения)
        Фиксация курса, на основании которого клиент получает средства производится по бирже BINANCE </br />
    @endif
    @if( ( $exchange->pair->quote_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->quote_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  )
        Будьте внимательны! Фиксация курса обмена в сделках не со стэйбл коинами (BTC, ETH, LTC и т.п) производится исключительно после отправки со стороны клиента, непосредственного захода к нам на кошелек и подтверждения обработки в сети (1-3 подтверждения)
        Фиксация курса, на основании которого клиент получает средства производится по бирже BINANCE </br />
    @endif


@else

    <?php

    $coef = 0;
    if( $exchange->pair->ask_coef > 1)
    {
        $coef = (round( $exchange->pair->ask_coef*1 - 1, 8)*100).'% с клиента';
    }else{
        $coef = (round( 1 - $exchange->pair->ask_coef*1, 8)*100).'% с обменного сервиса';
    }

    ?>


    @lang('mail_order.side'): {{$exchange->pair->quote_currency->name}} / {{$exchange->pair->base_currency->name}}  <br>
    Отдаете: {{ $exchange->amount_take*1 }} {{$exchange->pair->quote_currency->name}} <br>
    Получаете: {{ $exchange->amount_get*1 }} {{$exchange->pair->base_currency->name}} <br>

    @if( in_array($exchange->pair->quote_currency->code, [139, 138, 140, 137, 125, 130, 108, 124, 127, 129, 126, 128]) )
        @if( !$user->verified)
            <br /><b> Для завершения обмена нужно <a href="https://c1k.world/account/card_verify">верифицировать карту</a> </b><br />
        @endif
    @endif

    @if( ( $exchange->pair->base_currency->currency_type == 'cash' ) || ( $exchange->pair->quote_currency->currency_type == 'cash' )  )
        Комиссия за проведение сделки: {{  $coef }} <br />
    @elseif( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->base_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  )
        Комиссия за проведение сделки: {{  $coef }} <br />
    @elseif( ( $exchange->pair->quote_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->quote_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) )  )
        Комиссия за проведение сделки: {{  $coef }} <br />
    @endif


    @if( ( $exchange->pair->quote_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->quote_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) ) && ( !in_array($exchange->pair->quote_currency->code, ['CASHUSD','CASHUAH','CASHRUB','CASHRUB', 'CASHEUR']) )  )
        Будьте внимательны! Фиксация курса обмена в сделках не со стэйбл коинами (BTC, ETH, LTC и т.п) производится исключительно после отправки со стороны клиента, непосредственного захода к нам на кошелек и подтверждения обработки в сети (1-3 подтверждения)
        Фиксация курса, на основании которого клиент получает средства производится по бирже BINANCE </br />
    @elseif( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( !in_array($exchange->pair->base_currency->code, ['USDT','USDC','TUSD','USDTTRC','USDTERC','USDTBEP20']) ) && ( !in_array($exchange->pair->quote_currency->code, ['CASHUSD','CASHUAH','CASHRUB','CASHRUB', 'CASHEUR']) )  )
        Будьте внимательны! Фиксация курса обмена в сделках не со стэйбл коинами (BTC, ETH, LTC и т.п) производится исключительно после отправки со стороны клиента, непосредственного захода к нам на кошелек и подтверждения обработки в сети (1-3 подтверждения)
        Фиксация курса, на основании которого клиент получает средства производится по бирже BINANCE </br />
    @elseif( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( in_array($exchange->pair->quote_currency->code, ['CASHUSD','CASHUAH','CASHRUB','CASHRUB', 'CASHEUR']) )  )
        Фиксация курса, на основании которого клиент получает указанную в заявке криптовалюту производится по бирже BINANCE и исключительно после пересчета обменным сервисом наличных. </br />
    @endif
@endif

<br>
Свяжитесь с оператором чата поддержки для получения реквизитов на оплату заявки. <br><br>

{{-- @isset($exchange->address_from)
@lang('mail_order.wallet'): {{$exchange->address_from}} <br>
@endisset
@isset($exchange->address_to)
@lang('mail_order.wallet'): {{$exchange->address_to}} <br>
@endisset --}}

@lang('mail_order.thanks') <br> <br>

{{-- @lang('mail_order.operator') <br> --}}

@lang('mail_order.start'): {{$exchange->created_at}} UTC+2<br>
@lang('mail_order.end'): {{$exchange->created_at->addMinutes(30)}} UTC+2 <br>

@if($exchange->side == 'buy')
    @if( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( in_array($exchange->pair->quote_currency->code, ['CASHUSD','CASHUAH','CASHRUB','CASHRUB', 'CASHEUR']) )  )
        Если криптовалюта будет получена позже 30 минут с момента создания заявки, курс обмена будет пересчитан по текущему курсу сайта на момент получения обменным сервисом криптовалюты, которая указана в заявке клиента.
    @endif
    @if( ( $exchange->pair->quote_currency->currency_type == 'crypto' ) && ( in_array($exchange->pair->base_currency->code, ['CASHUSD','CASHUAH','CASHRUB','CASHRUB', 'CASHEUR']) )  )
        В случае, если наличные средства со стороны клиента будут получены позже 30 минут с момента создания заявки, курс обмена будет пересчитан по текущему курсу сайта на момент получения обменным сервисом наличных средств, которые указаны в заявке клиента.
    @endif
@else
    @if( ( $exchange->pair->base_currency->currency_type == 'crypto' ) && ( in_array($exchange->pair->quote_currency->code, ['CASHUSD','CASHUAH','CASHRUB','CASHRUB', 'CASHEUR']) )  )
        В случае, если наличные средства со стороны клиента будут получены позже 30 минут с момента создания заявки, курс обмена будет пересчитан по текущему курсу сайта на момент получения обменным сервисом наличных средств, которые указаны в заявке клиента.
    @endif
    @if( ( $exchange->pair->quote_currency->currency_type == 'crypto' ) && ( in_array($exchange->pair->base_currency->code, ['CASHUSD','CASHUAH','CASHRUB','CASHRUB', 'CASHEUR']) )  )
        Если криптовалюта будет получена позже 30 минут с момента создания заявки, курс обмена будет пересчитан по текущему курсу сайта на момент получения обменным сервисом криптовалюты, которая указана в заявке клиента.
    @endif
@endif


@lang('mail_order.link'): <a href="https://c1k.world/exchange-order?uuid={{$exchange->uuid}}"> https://c1k.world/exchange-order&uuid={{ $exchange->uuid }} </a> <br>
<hr><br />
@lang('mail_order.confirm'): <a href="https://c1k.world/?step=2&uuid={{ $exchange->uuid }}&confirm={{$confirm}}"> https://c1k.world/?step=2&uuid={{ $exchange->uuid }} </a> <br>
@lang('mail_order.confirm'): <a href="https://c1k.world/?step=2&uuid={{ $exchange->uuid }}&confirm={{$confirm}}"> https://c1k.world/?step=2&uuid={{ $exchange->uuid }} </a> <br>

<hr />
<br>
@lang('mail_order.our_projects') <br>
Crypto Exchange: <a href="https://c1k.world">https://c1k.world</a> <br>
Crypto consulting: <a href="https://c1k-fin.world">https://c1k-fin.world</a> <br>
