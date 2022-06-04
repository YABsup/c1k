<?php ?>
<div class="calc-block-title">@lang('calc.send')</div>

<div class="input">
  <input type="number" value="0.0" id="input-amount-give"  onkeyup="amount_change('input-amount-give')" min="0.0" step="0.00000001">
</div>
<div class="min-max-warn" id="min-max-warn-give">@lang('calc.min')</div>
<div class="calc-block-text" id="give-country-title">@lang('calc.city')</div>


<div class="input" id="give-country" onchange="change_country('give-country')">
  <select>
    <option>Украина</option>
    <option>Россия</option>
  </select>
</div>

<div class="input" id="give-city"  onchange="change_city('give-city')">
  <select>
    <option>Киев</option>
    <option>Москва</option>
  </select>
</div>

<div class="calc-block-text" style="display: none">Валюта отдачи</div>

<div style="display: flex; display: none">
  <div id="give_filter_all" class="rounded active calc-block-filter active" onclick="give_filter_change_all()">All</div>
  <div id="give_filter_cash" class="rounded border calc-block-filter" onclick="give_filter_change_cash()">Cash</div>
  <div id="give_filter_usd" class="rounded border calc-block-filter" onclick="give_filter_change_usd()">USD</div>
  <div id="give_filter_coin" class="rounded border calc-block-filter" onclick="give_filter_change_coin()">Coin</div>
</div>

<div class="calc-block-text">@lang('calc.select')</div>

<div class="row" id="give_currrency_blocks">


  <!--?php for($i=0;$i<16;$i++){ ?>
    <div id="give_currency_<//?php echo $i; ?>" class="currency" onclick="give_currency_change(<//?php echo $i; ?>)">
      <div class="currency-logo">
        <img src="img/group-8.svg"
        class="Group-8">
      </div>
      <div class="currency-name">
        Приват24
      </div>
      <div class="currency-code">
        P24UAH
      </div>
      <div class="currency-selected">
        <img src="/img/fill-357.svg"
        class="Fill-357">
      </div>
    </div>
  <//?php } ?-->

</div>
