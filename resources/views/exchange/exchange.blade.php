@extends('layouts.c1k-new')

@section('title')
@lang('exchange.cash.title')
@endsection
@section('head-add')
<link rel="stylesheet" href="{{ asset('css/exchange.css') }}"/>
@endsection
@section('new-footer-add')
<script>
$(function() {
    var exchange_rate ={{ $params['rate'] }};
    var exchange_side = "{{ $request->side }}";
    var exchange_give_decimal_places = 8;


    function recountExg() {

        var recount_ex = parseFloat($('#input-left_up').val());
        if(!recount_ex){
            recount_ex = 0
        }

        if(exchange_side == "buy") {
            res_recount_ex = recount_ex * exchange_rate;
        } else {
            res_recount_ex = recount_ex / exchange_rate;
        }

        $('#input-right_up').val(res_recount_ex.toFixed(exchange_give_decimal_places));

    }

    $('#input-left_up').keyup(recountExg);

    recountExg();

});
</script>
<script src="js/maskinput.js"></script>
<script src="js/validFormChange.js"></script>
<script src='https://google.com/recaptcha/api.js'></script>
@endsection


@section('xlam')

change-text-title = Обмен ETH → P24UAH
category_pair_id = 146
side = buy
amount_take = 0.5
change-text__title_cur = ETH
change-text-condition = <span>Курс обмена: <b>1</b> ETH к <b>7227.62132214</b> P24UAH </span>
change-text__title_cur2 = P24UAH

@endsection


@section('content')
<div class="main">
    <div class="container">
        <div class="Fill" style="    width: 100%;
        place-content: center; text-align: -webkit-center;" >
        <!-- @lang('exchange.exchange') -->
        <div class="change-text-title">
            <h4> {{ $params['cur1'] }} → {{ $params['cur2'] }}</h4>
        </div>
        <p class="text-danger text-center">* @lang('exchange.ahtung')</p>
        <form method="POST" action="/application">
            <input type="hidden" name="side" value="{{ $request->side }}" />
            <input type="hidden" name="category_pair_id" value="{{ $request->pair_id }}" />

            {{ csrf_field() }}

            <div class="row row_magrin_z">
                <div class="col-lg-12 change-text-title"></div>
                <div class="col-lg-2"></div>
                <div class="col-lg-8 ">
                    <div class="col-md-12 col-sm-12 row">
                        <div class="col-md-2 col-sm-4 change-text-left">
                            <p class="change-text__title">@lang('exchange.send')</p>
                        </div>
                        <div class="col-md-8 col-sm-4 col-xs-6 change-text-condition">
                            <div class=" field-orderform-sell_amount required">
                                <div class="col-md-12 field">
                                    <input name="amount_take" class="form-control recon1" type="text" value="{{ $params['amount_give'] }}" id="input-left_up" readonly>
                                </div>
                                <div class="hidden">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 change-text-left col-sm-4 ">
                            <p class="change-text__title_cur">{{ $params['cur1'] }}</p>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 change-text-condition">
                        <span>@lang('exchange.rate')<b>1</b> {{ $pair->base_currency->name }} / {{ $params['rate'] }} {{ $pair->quote_currency->name }} </b>  </span>
                    </div>
                    <div class="col-md-12 col-sm-12 row">
                        <div class="col-md-2 col-sm-4 change-text-left">
                            <p class="change-text__title">@lang('exchange.get')</p>
                        </div>
                        <div class="col-md-8 col-sm-4 col-xs-6 change-text-condition">
                            <div class=" field-orderform-sell_amount required">
                                <div class="col-md-12 field">
                                    <input name="amount_get" class="form-control recon2" type="text" id="input-right_up"  readonly>
                                    <label class="xchange-label__up label_change_r" for="input-right_up"></label>
                                </div>
                                <div class="hidden">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 change-text-left col-sm-4 ">
                            <p class="change-text__title_cur2">{{ $params['cur2'] }}</p>
                        </div>
                    </div>

                    @if( strpos($params['cur2'], 'Cash') === 0 )
                    <!--div class="col-md-12 row text-person_data">
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <label class="col-form-label" for="inputAddressFrom">Номер вашего кошелька/счета (отправителя)</label>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-6">
                            <input type="text" class="form-control" name="address_from" id="inputAddressFrom"  placeholder="Номер вашего кошелька/счета (отправителя)"  required>
                        </div>
                    </div-->
                    @endif

                    @if( strpos($params['cur1'], 'Cash' ) === 0 )
                    <div class="col-md-12 row text-person_data">
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <label class="col-form-label" for="inputAddressTo">@lang('exchange.account_number')</label>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-6">
                            <input required type="text" class="form-control" name="address_to" id="inputAddressTo"  placeholder="@lang('exchange.account_number')"  required>
                        </div>
                    </div>
                    @endif


                    <div class="clearfix"></div>
                    <div class="col-md-12 change-text-title-person ">
                        <h4>@lang('exchange.personal')</h4>
                    </div>

                    <div class="col-md-12 row text-person_data">
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <label class="col-form-label" for="inputFirstName">@lang('exchange.username')</label>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-6">
                            <input type="text" class="form-control" name="first_name" id="inputFirstName"  placeholder="@lang('exchange.personal.username')"  required  @guest value="{{ $request['first_name'] }}" @else value="{{ \Auth::user()->name }}" disaled @endguest>
                        </div>

                    </div>

                    <div class="col-md-12 row text-person_data">
                        <div class="col-md-2 col-sm-4 col-xs-6">

                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-6">
                            <p class="col-form-label" style="display: none" for="inputFirstName">@lang('exchange.personal_required')</p>
                        </div>
                    </div>
                    <div class="col-md-12 row text-person_data">
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <label class="col-form-label" for="inputViberName">Viber</label>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-6">
                            <input type="text" class="form-control" name="viber" id="inputViberName"  placeholder="Viber"  >
                        </div>
                    </div>
                    <div class="col-md-12 row text-person_data">
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <label class="col-form-label" for="inputTelegramName">Telegram</label>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-6">
                            <input type="text" class="form-control" name="telegram" id="inputTelegramName"  placeholder="Telegram"  >
                        </div>
                    </div>
                    <div class="col-md-12 row text-person_data">
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <label class="col-form-label" for="inputWhatsAppName">WhatsApp</label>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-6">
                            <input type="text" class="form-control" name="whatsapp" id="inputWhatsAppName"  placeholder="Whatsapp"  >
                        </div>
                    </div>
                    <div class="col-md-12 row text-person_data">

                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <label class="col-form-label" for="inputEmail">Email</label>
                        </div>

                        <div class="col-md-8 col-sm-8 col-xs-6">
                            <input type="email" class="form-control" name="email" id="inputEmail"  pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$" required  @guest value="{{ $request['email']}}" disaled @else value="{{ \Auth::user()->email }}" disaled @endguest>
                        </div>

                        <div class="postn_msg">
                            <!-- <div id="v" class="exchange_mgs_mis">@lang('exchange.personal.email_error')</div> -->
                        </div>
                    </div>


                    <div class="col-md-12 row exchange_recaptcha">
                        <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                    </div>

                    <div class="col-md-12 row orderform-rules">
                        <label class="form-check-label">
                            <input name="checkbox" type="checkbox" class="form-check-input" id="xhange_checkbox">
                            @lang('exchange.oferta_checkbox')
                        </label>
                        <div class="hint-block">
                            @lang('exchange.oferta')<a href="/oferta">@lang('exchange.oferta_link')</a>@lang('exchange.oferta2')
                        </div>
                        <button type="submit" class="btn btn-primary button-change-form" id="btn_change">
                            @lang('exchange.go')
                        </button>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </form>
        <div class="msg-filling-some-field" id="msgSomeField">
            <!-- <span>@lang('exchange.some_field')</span> -->
        </div>
    </div>
</div>
</div>
@endsection
