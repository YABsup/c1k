<br />
@if( $exchange->side == 'buy' )


    @isset($exchange->address_from)
        @if( $exchange->pair->base_currency->currency_type == 'electronic'  )
            Адрес email "{{$exchange->pair->quote_currency->name}}", с которого отправляете средства: {{$exchange->address_from }}<br>
        @else
            Адресс/номер карты, с которого отправляете средства {{$exchange->address_from }}<br>
        @endif
    @endisset

    @isset( $exchange->address_to )
        @if( $exchange->pair->quote_currency->currency_type == 'electronic'  )
            Адрес email "{{$exchange->pair->quote_currency->name}}", на который получаете средства: {{$exchange->address_to }}<br>
        @elseif( in_array( $exchange->pair->quote_currency->code, ['SEPAEUR'])  )
            Номер счета "{{$exchange->pair->quote_currency->name}}", на который получаете средства: {{$exchange->address_to }}<br>
        @elseif( $exchange->pair->quote_currency->currency_type == 'card'  )
            Карта "{{$exchange->pair->quote_currency->code}}", на которую получаете средства: {{$exchange->address_to }}<br>
        @else
            Адресс/номер карты, на который получаете средства: {{$exchange->address_to }}<br>
        @endif
    @endisset

@else

    @isset($exchange->address_from)
        @if( $exchange->pair->quote_currency->currency_type == 'electronic'  )
            Адрес email "{{$exchange->pair->quote_currency->name}}", с которого отправляете средства: {{$exchange->address_from }}<br>
        @elseif( $exchange->pair->quote_currency->currency_type == 'card'  )
            Карта "{{$exchange->pair->quote_currency->name}}", с которой отправляете средства: {{$exchange->address_from }}<br>
        @else
            Адресс/номер карты, с которого отправляете средства: {{$exchange->address_from }}<br>
        @endif
    @endisset

    @isset( $exchange->address_to )
        @if( $exchange->pair->base_currency->currency_type == 'crypto' )
            Адрес кошелька "{{$exchange->pair->base_currency->name}}", на который получаете средства: {{$exchange->address_to }}<br>
        @else
            Адресс/номер карты, на который получаете средства: {{$exchange->address_to }}<br>
        @endif
    @endisset

@endif
