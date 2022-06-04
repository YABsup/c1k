@extends('layouts.c1k-new')

@section('head-add')
<link rel="stylesheet" href="{{ asset('css/forms.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/account.css') }}"/>
<link rel="stylesheet" href="{{ asset('css/exchange.css') }}"/>
@endsection

@section('content')
<div class="main">
    <div class="container">
        <div class="row">
            @include('account/sidebar')
            <style>
            .Fill.withdrawal {
                width: 100%;
                max-width: 588px;
                min-height: 650px;
                margin: 0 auto;
                display: flex;
                justify-content: center;
            }
            .Fill.withdrawal h4 {
                font-family: MuseoSansCyrl, sans-serif;
                font-size: 36px;
                font-weight: 300;
                line-height: normal;
                letter-spacing: -0.58px;
                text-align: center;
                margin-bottom: 20px;
            }
            .Fill.withdrawal label{
                margin: 30px 0 10px;
                display: block;
                opacity: 0.8;
                font-family: MuseoSansCyrl, sans-serif;
                font-size: 16px;
                font-weight: 300;
                line-height: normal;
                letter-spacing: -0.08px;
                color: #ffffff;
            }
            .Fill.withdrawal .input{
                margin: 0;
            }
            .Fill.withdrawal .input,
            .Fill.withdrawal .input input{
                width: 100%;
            }
            .Fill.withdrawal span{
                opacity: 0.5;
                font-family: MuseoSansCyrl, sans-serif;
                font-size: 13px;
                font-weight: 300;
                line-height: normal;
                letter-spacing: -0.09px;
                padding-left: 20px;
                color: #ffffff;
            }
            .withdrawal-items{
                width: 100%;
                max-width: 380px;
            }

            .withdrawal-items button {
                margin: 40px auto 0;
                width: 100%;
                font-family: MuseoSansCyrl, sans-serif;
                font-size: 19px;
                font-weight: bold;
                line-height: 36px;
                letter-spacing: normal;
            }
            </style>
            <div class="Fill withdrawal">
                <div class="withdrawal-items">
                    @if( (count($withdraws) == 0) && ($user->balance > 100) )
                    <h4>@lang('withdraw.withdraw')</h4>
                    <form   method="POST" action="{{ route('account.create_withdraw') }}"  >
                        {{ csrf_field() }}
                        <label for="full_name">@lang('withdraw.withdraw')</label>
                        <div class="input">
                            <input id="full_name" type="text" name="fio" placeholder="" required>
                        </div>
                        <label for="telegram_contact">@lang('withdraw.contact')</label>
                        <div class="input">
                            <input id="telegram_contact" name="telegram" type="text" placeholder="@" required>
                        </div>
                        <label for="output_direction">@lang('withdraw.way')</label>
                        <div class="input">
                            <select id="get_currrency_blocks" name="currency" style="background: none">
                                <option value="ETH" class="currency"> Ethereum (ETH)</option>
                                <option value="USDT" class="currency"> Tether (USDT)</option>
                                <option value="BTC" class="currency"> Bitcoin (BTC)</option>
                            </select>
                        </div>
                        <span>@lang('withdraw.select_way')</span>
                        <label for="wallet-card_number">@lang('withdraw.account_number')</label>
                        <div class="input">
                            <input id="wallet-card_number" type="text" name="address" placeholder="XXXX - XXXX - XXXX - XXXX" required>
                        </div>
                        <button type="submit" class="btn btn-danger nav-link" style="background-color: #f33333; -webkit-appearance: none!important;">@lang('withdraw.send')</button>
                    </form>
                    @elseif($user->balance < 100)
                        <h4>@lang('withdraw.withdraw_min')</h4>
                    @elseif((count($withdraws) > 0) )
                        <h4>@lang('withdraw.withdraw_proccess')</h4>
                    @else
                        <h4>@lang('withdraw.withdraw')</h4>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-js')
<script>
$('#get_currrency_blocks').css({ "background": "url(/coin-logo/" + $('#get_currrency_blocks').val() + ".png) no-repeat left", 'background-size': 'contain', 'padding-left': '20px' })
$('#get_currrency_blocks').on('change', function () {
    $('#get_currrency_blocks').css({ "background": "url(/coin-logo/" + $('#get_currrency_blocks').val() + ".png) no-repeat left", 'background-size': 'contain', 'padding-left': '20px' })
})
</script>
@endsection
