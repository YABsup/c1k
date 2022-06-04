@extends('layouts.c1k-new')

@section('title')
@lang('exchange.cashless.title')
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

                <div class="clearfix"></div>
                <div class="col-md-12 change-text-title-person ">
                    <svg version="1.1" height="50" width="50" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
                        <g><path d="M898.2,132.5H101.8c-50.7,0-91.8,44-91.8,98.3v538.3c0,54.3,41.1,98.3,91.8,98.3h796.3c50.7,0,91.8-44,91.8-98.3V230.8C990,176.5,948.9,132.5,898.2,132.5z M898.1,739.1c0,20.4-16.7,36.9-37.3,36.9H139.2c-20.6,0-37.3-16.5-37.3-36.9V260.9c0-20.4,16.7-36.9,37.3-36.9h721.6c20.6,0,37.3,16.5,37.3,36.9V739.1z" fill="#fff"/><path d="M789.3,346.9H639.1c-9.3,0-16.8,7-16.8,15.5v30.1c0,8.6,7.5,15.5,16.8,15.5h150.1c9.3,0,16.8-6.9,16.8-15.5v-30.1C806.1,353.9,798.6,346.9,789.3,346.9z" fill="#fff"/><path d="M789.3,469.4H639.1c-9.3,0-16.8,7-16.8,15.5v30.1c0,8.6,7.5,15.5,16.8,15.5h150.1c9.3,0,16.8-7,16.8-15.5v-30.1C806.1,476.4,798.6,469.4,789.3,469.4z" fill="#fff"/><path d="M789.3,592.2H639.1c-9.3,0-16.8,7-16.8,15.5v30.1c0,8.6,7.5,15.5,16.8,15.5h150.1c9.3,0,16.8-6.9,16.8-15.5v-30.1C806.1,599.1,798.6,592.2,789.3,592.2z" fill="#fff"/><path d="M346.3,365.6c40.5,0,73.4,32.9,73.4,73.4c0,19.6-9.8,39.5-26.8,54.7c-14.4,12.8-20.2,32.8-14.6,51.2c5.6,18.4,21.1,31.8,40.1,34.5c1.6,0.3,21.3,3.7,39.5,12.9c8.4,4.3,15,9,18.9,13.7c3.4,4.1,4.1,7.4,4.1,11v19.1H211.7v-19.1c0-3.7,0-13.4,22.9-24.9c18.1-9.1,37.6-12.4,39.3-12.6c19.1-2.6,34.7-15.9,40.4-34.4c5.7-18.5-0.1-38.5-14.5-51.4c-17-15.2-26.8-35.1-26.8-54.7C273,398.5,305.9,365.6,346.3,365.6 M346.3,316.2c-67.8,0-122.8,55-122.8,122.8c0,36.4,18.1,69.1,43.3,91.6c0,0-104.6,14.7-104.6,86.5v49.4c0,10.5,8.6,19.1,19.1,19.1h329.9c10.6,0,19.1-8.5,19.1-19.1v-49.4c0-71-104.6-86.5-104.6-86.5c25.2-22.5,43.3-55.2,43.3-91.6C469.1,371.2,414.1,316.2,346.3,316.2L346.3,316.2z" fill="#fff"/></g>
                    </svg>
                    <h4>@lang('exchange.personal')</h4>
                </div>

                <div class="col-md-12 row text-person_data">
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <label class="col-form-label" for="inputFirstName">@lang('exchange.username')</label>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-6">
                        <input type="text" class="form-control" name="first_name" id="inputFirstName"  placeholder="@lang('exchange.username')"  required  @guest value="{{ $request['first_name'] }}" @else value="{{ \Auth::user()->name }}" disaled @endguest>
                    </div>
                    <!-- <div class="postn_msg">
                            <div id="vl" class="exchange_mgs_mis">@lang('exchange.first_name.error')</div>
                    </div> -->
                </div>

                @if( strpos($params['cur1'], 'Cash' ) === 0 )
                <div class="col-md-12 row text-person_data">
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <label class="col-form-label" for="inputAddressFrom">@lang('exchange.address_from')</label>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-6">
                        <input type="text" class="form-control" name="address_from" id="inputAddressFrom"  placeholder="@lang('exchange.address_from')">
                    </div>
                </div>
                @endif

                <div class="col-md-12 row text-person_data">
                    <div class="col-md-2 col-sm-4 col-xs-6">
                        <label class="col-form-label" for="inputAddressTo">@lang('exchange.account_number')</label>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-6">
                        <input required type="text" class="form-control" name="address_to" id="inputAddressTo"  placeholder="@lang('exchange.account_number')"  required>
                    </div>
                </div>


                 <div class="col-md-12 row text-person_data">
                    <div class="col-md-2 col-sm-4 col-xs-6">
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-6">
                        <p class="col-form-label"  style="display: none" for="inputFirstName">@lang('exchange.personal_required')</p>
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
                        <input type="email" class="form-control" name="email" id="inputEmail" pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$" required  @guest value="{{ $request['email'] }}" disaled @else  value="{{ \Auth::user()->email }}" disaled @endguest>
                    </div>
                    <!-- <div class="postn_msg">
                         <div id="v" class="exchange_mgs_mis">@lang('exchange.personal.email.error')</div>
                    </div> -->
                </div>


                <div class="exchange_recaptcha">
                    <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}"></div>
                </div>

              <script src='https://google.com/recaptcha/api.js'></script>

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
