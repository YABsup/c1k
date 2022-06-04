@extends('layouts.c1k-new')

@section('title') @lang('exchange.status') № {{ $exchange->id }} - {{ $exchange->status->desc }} @endsection

@section('head-add')
<link rel="stylesheet" href="{{ asset('css/application.css') }}"/>
@endsection

@section('content')
<div class="main">
    <div class="container">
        <div class="Fill" style="    width: 100%;
        place-content: center; text-align: -webkit-center;" >
        <div class="row">
            <div class="col col-sm-12">
                <h2>@lang('exchange.status') № {{ $exchange->id }} - <span>{{ $exchange->status->desc }}</span></h2>
            </div>
        </div>
        <div class="header__warning-text header__warning-text--application">@lang('exchange.alert')</div>
        <div class="row Fill">
            <div class="left-sheet">
                <div class="col col-sm-12">
                    <span>@lang('exchange.username')</span><span>{{ $exchange->first_name }}</span>
                    <span></span>
                </div>
                <div class="col col-sm-12">
                    <!--span>E-mail: </span><span><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="1b282d2a2c222b2f5b7c767a727735787476">[email&#160;protected]</a></span-->
                    <span>E-mail: </span><span>{{ $exchange->email }}</span>
                </div>

                <div class="col col-sm-12">
                    <span>Viber: </span><span>{{ $exchange->viber }}</span>
                </div>
                <div class="col col-sm-12">
                    <span>Telegram: </span><span>{{ $exchange->telegram }}</span>
                </div>
                <div class="col col-sm-12">
                    <span>WhatsApp </span><span>{{ $exchange->whatsup }}</span>
                </div>
                @isset($exchange->address_from)
                <div class="col col-sm-12">
                    <span>@lang('exchange.from_address') </span><span>{{ $exchange->address_from }}</span>
                </div>
                @endisset
                @isset($exchange->address_to)
                <div class="col col-sm-12">
                    <span>@lang('exchange.to_address') </span><span>{{ $exchange->address_to }}</span>
                </div>
                @endisset

                @if($exchange->side == 'buy')
                <div class="col col-sm-12">
                    <span>@lang('exchange.side')</span><span>{{ $exchange->pair->base_currency->name }}/{{ $exchange->pair->quote_currency->name }}</span>
                </div>
                <div class="col col-sm-12">
                    <span>@lang('exchange.summ')"{{ $exchange->pair->base_currency->name }}": </span><span>{{ $exchange->amount_take }}</span>
                </div>
                <div class="col col-sm-12">
                    <span>@lang('exchange.summ')"{{ $exchange->pair->quote_currency->name }}": </span><span>{{ $exchange->amount_get }}</span>
                </div>
                @else
                <div class="col col-sm-12">
                    <span>@lang('exchange.side')</span><span>{{ $exchange->pair->quote_currency->name }}/{{ $exchange->pair->base_currency->name }}</span>
                </div>
                <div class="col col-sm-12">
                    <span>@lang('exchange.summ')"{{ $exchange->pair->quote_currency->name }}": </span><span>{{ $exchange->amount_take }}</span>
                </div>
                <div class="col col-sm-12">
                    <span>@lang('exchange.summ')"{{ $exchange->pair->base_currency->name }}": </span><span>{{ $exchange->amount_get }}</span>
                </div>
                @endif
            </div>
            <div class="col col-sm-12 right-sheet">
                <!--  -->
                <div class="col col-sm-12 appl_center_block">
                    <!-- @lang('exchange.address') -->
                </div>
                <!--  -->
            </div>
        </div>
        <div class="row Fill">
            <div class="bottom-sheet">
                <div class="col col-sm-12">
                    <span>@lang('exchange.thankyou')</span>
                </div>
                <div class="col col-sm-12">

                    <span>@lang('exchange.call_manager')</span>

                </div>

                @if( $exchange->status->id == 2 )
                    @if( ( ( $exchange->pair->base_currency->code == 'ADVCUSD') && ( $exchange->side == 'buy') ) || ( ( $exchange->pair->quote_currency->code == 'ADVCUSD') && ( $exchange->side == 'sell') ) )
                    <div class="col col-sm-12">
                        <form method="POST" action="https://wallet.advcash.com/sci/">
                            {{ csrf_field() }}
                            <input type="hidden" name="ac_account_email" value="lenya2018@protonmail.com">
                            <input type="hidden" name="ac_sci_name" value="c1kworld">
                            <input type="hidden" name="ac_amount" value="{{ number_format($exchange->amount_take, 2, '.', '') }}">
                            <input type="hidden" name="ac_currency" value="USD">
                            <input type="hidden" name="ac_order_id" value="{{ $exchange->id }}">
                            <?php
                            $payload = "lenya2018@protonmail.com:c1kworld:".number_format($exchange->amount_take, 2, '.', '').":USD:yX789OM-8j:".$exchange->id;
                            $sign = hash("sha256", $payload);
                            ?>
                            <input type="hidden" name="ac_sign" value="{{ $sign }}">
                            <button  class="btn btn-danger" type="submit">Pay</button>
                        </form>
                    </div>
                    @endif
                @endif

@if($exchange->side == 'buy')
                @if( (
                strpos($exchange->pair->base_currency->code, 'CARD') !== false)
                || (strpos($exchange->pair->base_currency->code, 'P24') !== false)
                || (strpos($exchange->pair->base_currency->code, 'MONO') !== false)
                || (strpos($exchange->pair->base_currency->code, 'SBER') !== false)
                || (strpos($exchange->pair->base_currency->code, 'ACRUB') !== false)
                 )
                    @if( !$user->verified)
                        <div class="col col-sm-12">
                             @lang('exchange.verify1')<a  style="color:red" href="{{ route('account.card_verify') }}">@lang('exchange.verify2')
                         </div>
                    @endif
                @endif
                @if( (
                strpos($exchange->pair->base_currency->code, 'SKL') !== false)
                || (strpos($exchange->pair->base_currency->code, 'NTL') !== false)
                 )
                    @if( !$user->verified)
                        <div class="col col-sm-12">
                             @lang('exchange.verify1')<a  style="color:red" href="{{ route('account.skrill_verify') }}">@lang('exchange.verify2')
                         </div>
                    @endif
                @endif
@else
    @if( (
    strpos($exchange->pair->quote_currency->code, 'CARD') !== false)
    || (strpos($exchange->pair->quote_currency->code, 'P24') !== false)
    || (strpos($exchange->pair->quote_currency->code, 'MONO') !== false)
    || (strpos($exchange->pair->quote_currency->code, 'SBER') !== false)
    || (strpos($exchange->pair->quote_currency->code, 'ACRUB') !== false)
     )
        @if( !$user->verified)
            <div class="col col-sm-12">
                @lang('exchange.verify1')<a  style="color:red" href="{{ route('account.card_verify') }}">@lang('exchange.verify2')
             </div>
        @endif
    @endif
    @if( (
    strpos($exchange->pair->quote_currency->code, 'SKL') !== false)
    || (strpos($exchange->pair->quote_currency->code, 'NTL') !== false)
     )
        @if( !$user->verified)
            <div class="col col-sm-12">
                 @lang('exchange.verify_skrill')<a  style="color:red" href="{{ route('account.skrill_verify') }}">@lang('exchange.verify2')
             </div>
        @endif
    @endif

@endif


                <div class="col col-sm-12">
                    <span>@lang('exchange.date')</span><span>{{ $exchange->created_at }}</span>
                </div>
                <!-- <div class="col col-sm-12">
                <span>@lang('exchange.order.end')</span>
                <span>
                @lang('status.not_end')
            </span>
        </div> -->

    </div>
</div>
</div>
<div class="col col-sm-12 link-order word_wrap" style="word-break: break-all">
    <span>@lang('exchange.address')</span>

    <small>

        <a href="https://c1k.world/application/{{ $exchange->uuid }}">https://c1k.world/application/{{ $exchange->uuid }}</a>

    </small>
</div>
<div class="col col-sm-12 link-order word_wrap">
    @lang('exchange.send_review');
</div>

</div>
</div>
@endsection
