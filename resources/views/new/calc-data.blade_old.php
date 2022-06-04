<?php ?>
<div class="calc-block-title">@lang('calc.data')</div>

<div class="calc-block-text" >@lang('calc.rate')<span id="info-current-rate"></span></div>


<div class="currency" id="calc-data-in" style="margin-left: 0px;">

</div>
<div class="currency" id="calc-data-out"  style="margin-left: 0px;">

</div>


<div class="Fill-531">

</div>

@guest
<div class="calc-block-text">@lang('calc.personal_long')</div>
@else
<div class="calc-block-text">@lang('calc.personal')</div>
@endguest


<form id="submit-form" method="POST" action="/newexchange">



@guest
<div class="input">
  <input type="text" name="first_name" placeholder="@lang('calc.username')">
</div>
<div class="input">
  <input type="email" name="email" placeholder="Email">
</div>

@else
<div class="input">
  <input type="text" name="first_name" placeholder="Имя Фамилия"  value="{{ \Auth::user()->name }}" disabled>
</div>
<div class="input">
  <input type="email" name="email" placeholder="Email" value="{{ \Auth::user()->email }}" disabled>
</div>
@endguest

<!-- <div class="input">
  <input type="text" name="contact" placeholder="Контакт">
</div> -->

<input name="pair_id" id="pair_id" type="text" hidden="">
<input name="side" id="side" type="text" hidden="">
<input name="left_currency" type="text" id="left_currency" hidden="">
<input name="right_currency" type="text" id="right_currency" hidden="">
<input name="amount_give" type="text" id="input-left_up" value="" hidden="">
<input name="amount_get" type="text" id="input-right_up" hidden="">
{{ csrf_field() }}

<div id="minimal_cash" class="calc-block-text" style="display: none">
  <a style="color: #f33333;">@lang('calc.10k')</a>
</div>
<div id="add_commision" class="calc-block-text" style="display: none">
  <a style="color: #f33333;">@lang('calc.add_commision')</a>
</div>


<div id="need_verify" class="calc-block-text" style="display: none">
  <a href="{{ route('card_verify_info') }}" style="color: #f33333;"><u>@lang('calc.verify')</u></a>
</div>

<div id="need_skrill_verify" class="calc-block-text" style="display: none">
  <a href="{{ route('skrill_verify_info') }}" style="color: #f33333;"><u>@lang('calc.verify')</u></a>
</div>

<div id="min-max-warn-data" class="calc-block-text" style="display: none">
  <a style="color: #f33333;"></a>
</div>
<!-- <div id="pm_money_verify" class="calc-block-text" style="display: none">
  <span style="color: #f33333;"><u>Комиссию за отправку на не верифицированный аккаунт Perfect Money оплачивает получатель</u></a>
</div> -->
<div class="calc-block-text">
  <input type="checkbox" onclick="$('#submit-exchange').prop('disabled', function(i, v) { return !v; })" id="check-oferta">@lang('calc.oferta')<a href="{{ route('oferta') }}" style="color: #f33333;"><u>@lang('calc.oferta_link')</u></a>
</div>


<button id="submit-exchange" type="submit" class="btn btn-danger" disabled style="background-color: #f33333; width: 100%; height: 54px;">@lang('calc.go')</button>
</form>
