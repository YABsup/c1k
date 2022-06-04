//* https://newmonitor.alogic.com.ua/api/rates/pairs/new *//

var RATES

var COUNTRY_ID = undefined
var CITY_ID = undefined
var GIVE_CURRENCY_ID = undefined
var GET_CURRENCY_ID = undefined

var GIVE_CURRENCY_CODE = undefined
var GET_CURRENCY_CODE = undefined
var FIRST_RUN = true


var PAIR_ID = undefined

var CURRENT_RATE = undefined
var AMOUNT_GIVE = undefined
var AMOUNT_GET = undefined
var MIN_BASE_AMOUNT = undefined
var MAX_BASE_AMOUNT = undefined
var MIN_QUOTE_AMOUNT = undefined
var MAX_QUOTE_AMOUNT = undefined
var RESERV = undefined

function get_query(){
    var url = location.search;
    var qs = url.substring(url.indexOf('?') + 1).split('&');
    for(var i = 0, result = {}; i < qs.length; i++){
        qs[i] = qs[i].split('=');
        result[qs[i][0]] = decodeURIComponent(qs[i][1]);
    }
    return result;
}

$(document).ready(function () {

    var $_GET = get_query();
    if( 'cur_from' in $_GET )
    {
        GIVE_CURRENCY_CODE = $_GET['cur_from']
    }
    if( 'cur_to' in $_GET )
    {
        GET_CURRENCY_CODE = $_GET['cur_to']
    }


  $.getJSON('/api/rates/pairs/new',{format: "json"})
  .done(function(data) {
    RATES = data

    draw_countries()
    draw_cities()
    draw_pairs()
    draw_calc()
    amount_change('input-amount-give')

    FIRST_RUN = false
  })
});
function draw_countries()
{
  var HTML = ''
  $.each(RATES['country'], function( id, country){
    if( id != 4)
    {
      if( COUNTRY_ID == undefined )
      {
        COUNTRY_ID = id
      }
      selected = ''
      if(COUNTRY_ID == id)
      {
        selected = 'selected'
      }
      HTML = HTML + '<option value='+id+' ' + selected+'>' + country['name'] +'</option>'
    }
  })
  $('#give-country').html('<select>'+HTML+'</select>')
  $('#get-country').html('<select>'+HTML+'</select>')

  //  hide_country_block('get')
  //  hide_country_block('give')
}
function draw_cities()
{
  var HTML = ''
  $.each(RATES['cities'], function(id, city){
    if ( city['country'] == COUNTRY_ID)
    {
      if( CITY_ID == undefined  )
      {
        CITY_ID = id
       if( COUNTRY_ID == 1 )
       {
    	CITY_ID=22
       }
      }
      selected = ''
      if( CITY_ID == id )
      {
        selected = 'selected'
      }
      HTML = HTML + '<option value='+id+' '+selected+'>' + city['name'] +'</option>'
    }
  })
  $('#give-city').html('<select>'+HTML+'</select>')
  $('#get-city').html('<select>'+HTML+'</select>')
}

function change_country(blockid)
{
  COUNTRY_ID = $('#'+blockid+' select')[0].value
  CITY_ID = undefined
  GIVE_CURRENCY_ID = undefined
  GET_CURRENCY_ID = undefined
  draw_countries()
  draw_cities()
  draw_pairs()
  draw_calc()
}

function change_city(blockid)
{
  CITY_ID = $('#'+blockid+' select')[0].value
  GIVE_CURRENCY_ID = undefined
  GET_CURRENCY_ID = undefined
  draw_cities()
  draw_pairs()
  draw_calc()
}

function draw_pairs()
{
  var HTML = ''
  $.each(RATES['pairs'], function(id, data){
    enable = false

    $.each(data, function(checkid, checkdata){
      $.each(checkdata, function(checkid, checkdata2){

        if( ( checkdata2['city']  == CITY_ID) || ( checkdata2['city']  == 186 ) ) {
          enable = true
        }
      })
    })

    if( enable == true )
    {

        if( FIRST_RUN && ( GIVE_CURRENCY_CODE != undefined ) )
        {
            $.each(RATES['currencies'], function(id_s, data_s){
                if( data_s['code'] == GIVE_CURRENCY_CODE )
                {
                    GIVE_CURRENCY_ID = id_s
                }
            });
        }

      if( GIVE_CURRENCY_ID == undefined)
      {
        GIVE_CURRENCY_ID = id
        GET_CURRENCY_ID = undefined
      }
      selected = ''
      if( GIVE_CURRENCY_ID == id )
      {
        selected = 'active'
      }

      HTML = HTML +'<div id="give_currency_'+id+'" class="currency '+selected+'" onclick="give_currency_change('+id+')">'
      +'            <div class="currency-logo">'
      +'              <img src="/coin-logo/'+RATES['currencies'][id]['code']+'.png" class="Group-8"></div>'
      +'            <div class="currency-name">'+RATES['currencies'][id]['name']+'</div>'
      +'            <div class="currency-code">'+RATES['currencies'][id]['code'].replace("CASH","").replace("CARD","")+'</div>'
      +'            <div class="currency-selected">'
      +'              <img src="/img/fill-357.svg"'
      +'              class="Fill-357">'
      +'            </div>'
      +'          </div>'
    }

  })
  $('#give_currrency_blocks').html(HTML)

  var HTML = ''

  $.each(RATES['pairs'], function(left_currency_id, left_currency_pairs){
    if( left_currency_id == GIVE_CURRENCY_ID)
    {
      $.each(left_currency_pairs, function(right_currency_id, right_currency_pairs){

        $.each(right_currency_pairs, function(id,data){
          if( ( data['city']  == CITY_ID) || ( data['city']  == 186 ) ) {

            if( GET_CURRENCY_ID == undefined)
            {
              GET_CURRENCY_ID = right_currency_id
            }
            selected = ''
            if( GET_CURRENCY_ID == right_currency_id )
            {
              selected = 'active'

              PAIR_ID = data['id']
              CURRENT_RATE = data['rate']

              MIN_BASE_AMOUNT = data['min'] //ok
              MAX_BASE_AMOUNT = data['max'] //ok

              MIN_QUOTE_AMOUNT = data['min'] / CURRENT_RATE  //
              MAX_QUOTE_AMOUNT = data['reserv']*1

              RESERV = data['reserv'] * 1 //ok
            }
            data['reserv'] = data['reserv'] * 1
            HTML = HTML +'<div id="get_currency_'+right_currency_id+'" class="currency '+ selected +'" onclick="get_currency_change('+right_currency_id+')">'
            +'            <div class="currency-logo">'
            +'              <img src="/coin-logo/'+RATES['currencies'][right_currency_id]['code']+'.png" class="Group-8"></div>'
            +'            <div class="currency-name">'+RATES['currencies'][right_currency_id]['name']+'</div>'
            +'            <div class="currency-code">'+data['reserv']+'&nbsp;'+RATES['currencies'][right_currency_id]['code'].replace("CASH","").replace("CARD","")+'</div>'
            +'            <div class="currency-selected">'
            +'              <img src="/img/fill-357.svg"'
            +'              class="Fill-357">'
            +'            </div>'
            +'          </div>'
          }
        })
      })
    }
  })
  $('#get_currrency_blocks').html(HTML)
}
function draw_calc()
{

  if( AMOUNT_GIVE == undefined)
  {
    AMOUNT_GIVE = RATES['pairs'][GIVE_CURRENCY_ID][GET_CURRENCY_ID][0]['min']
  }

  if(PAIR_ID > 1000000)
  {
    $('#submit-form #pair_id')[0].value = PAIR_ID - 1000000
    $('#submit-form #side')[0].value = 'sell'

    //AMOUNT_GET = Math.floor(  AMOUNT_GIVE / CURRENT_RATE * 100000000 ) / 100000000
  }else{
    $('#submit-form #pair_id')[0].value = PAIR_ID
    $('#submit-form #side')[0].value = 'buy'
    //AMOUNT_GET = Math.ceil( AMOUNT_GIVE * CURRENT_RATE * 100000000 ) / 100000000
  }
  $('#submit-form #left_currency')[0].value = GIVE_CURRENCY_ID
  $('#submit-form #right_currency')[0].value = GET_CURRENCY_ID

  $('#submit-form #input-left_up')[0].value = AMOUNT_GIVE
  $('#submit-form #input-right_up')[0].value = AMOUNT_GET


  $('#input-amount-give')[0].value = AMOUNT_GIVE
  $('#input-amount-get')[0].value = AMOUNT_GET

  $quote_currency_text = RATES['pairs'][GIVE_CURRENCY_ID][GET_CURRENCY_ID][0]['quote_currency'].replace("CASH","").replace("CARD","")
  $base_currency_text = RATES['pairs'][GIVE_CURRENCY_ID][GET_CURRENCY_ID][0]['base_currency'].replace("CASH","").replace("CARD","")


  var card_pairs = [ 137, 138, 139, 140, 142, 125, 130, 134, 136, 108, 124, 127, 129, 126, 128, ];
  if( card_pairs.indexOf( GIVE_CURRENCY_ID ) == -1 )
  {
      $('#need_verify').hide();
  }else{
      $('#need_verify').show();
  }
  
  var skrill_pairs = [ 66, 67, 68, 76, 77 ];
  if( skrill_pairs.indexOf( GIVE_CURRENCY_ID ) == -1 )
  {
      $('#need_skrill_verify').hide();
  }else{
      $('#need_skrill_verify').show();
  }

  let cash_currency = [178,179,180,181,182,183]
  if( (cash_currency.indexOf(GET_CURRENCY_ID) == -1) && (cash_currency.indexOf(GIVE_CURRENCY_ID) == -1) )
  {
      $('#minimal_cash').hide();
  }else{
      $('#minimal_cash').show();
  }

  if( GET_CURRENCY_ID == '125')
  {
      $('#add_commision').show();
  }else{
      $('#add_commision').hide();
  }

  // if( ( GET_CURRENCY_ID == 51) || ( GET_CURRENCY_ID == 52) || ( GET_CURRENCY_ID == 53 ))
  // {
  //     $('#pm_money_verify').show();
  // }else{
  //     $('#pm_money_verify').hide();
  // }

  $('#info-current-rate').html(' '+CURRENT_RATE+' '+ $quote_currency_text +' = 1 '+ $base_currency_text);
  if( (RATES['currencies'][GIVE_CURRENCY_ID]['name'].indexOf("Cash") == 0) || ( RATES['currencies'][GET_CURRENCY_ID]['name'].indexOf("Cash") == 0 ) )
  {
    $('#submit-form')[0].action = "/exchange"
  }else{
    $('#submit-form')[0].action = "/cashless_exchange"
  }


  //  $('#input-amount-give')[0].value = pairs[left][right]['min']
  //  $('#input-amount-get')[0].value = pairs[left][right]['min'] * pairs[left][right]['rate']

  //$('#calc-data-in').html(
  $('#calc-data-in').html('<div class="currency-logo"><img src="/coin-logo/'+RATES['currencies'][GIVE_CURRENCY_ID]['code']+'.png" class="Group-8"></div><div class="currency-name" style="width: 50%;">'+RATES['currencies'][GIVE_CURRENCY_ID]['name']+'</div> <div class="currency-code">'+AMOUNT_GIVE+' '+RATES['currencies'][GIVE_CURRENCY_ID]['code'].replace("CASH","").replace("CARD","")+'</div>')
  $('#calc-data-out').html('<div class="currency-logo"><img src="/coin-logo/'+RATES['currencies'][GET_CURRENCY_ID]['code']+'.png" class="Group-8"></div><div class="currency-name" style="width: 50%;">'+RATES['currencies'][GET_CURRENCY_ID]['name']+'</div> <div class="currency-code">'+AMOUNT_GET+' '+RATES['currencies'][GET_CURRENCY_ID]['code'].replace("CASH","").replace("CARD","")+'</div>')

  $exchange_give_disable = false
  $exchange_get_disable = false

  if( AMOUNT_GIVE < MIN_BASE_AMOUNT){
    $('#min-max-warn-give')[0].textContent = "* min "+ MIN_BASE_AMOUNT;
    $('#min-max-warn-give').show();
    $('#min-max-warn-data').show();
        $('#min-max-warn-data')[0].textContent = "* min "+ MIN_BASE_AMOUNT + " "+RATES['currencies'][GIVE_CURRENCY_ID]['code']
    $exchange_give_disable = true
  }else if( AMOUNT_GIVE > MAX_BASE_AMOUNT){
    $('#min-max-warn-give')[0].textContent = "* max "+ MAX_BASE_AMOUNT;
    $('#min-max-warn-give').show();
    $('#min-max-warn-data')[0].textContent = "* max "+ MAX_BASE_AMOUNT + " "+RATES['currencies'][GET_CURRENCY_ID]['code']
    $('#min-max-warn-data').show();
  }else{
    $('#min-max-warn-give').hide();
    $exchange_give_disable = false
  }

  if( AMOUNT_GET < MIN_QUOTE_AMOUNT){
    $('#min-max-warn-get')[0].textContent = "* min "+ MIN_QUOTE_AMOUNT;
    $('#min-max-warn-get').show();
    $('#min-max-warn-data')[0].textContent = "* min "+ MIN_QUOTE_AMOUNT + " "+RATES['currencies'][GIVE_CURRENCY_ID]['code'];
    $('#min-max-warn-data').show();
    $exchange_disable = true
  }else if( AMOUNT_GET > RESERV){
    $('#min-max-warn-get')[0].textContent = "* max "+ RESERV;
    $('#min-max-warn-get').show();
    $('#min-max-warn-data')[0].textContent = "* max "+ RESERV + " "+RATES['currencies'][GET_CURRENCY_ID]['code'];
    $('#min-max-warn-data').show();
    $exchange_get_disable = true
  }else{
    $('#min-max-warn-get').hide();
    $exchange_get_disable = false
  }

  if($exchange_get_disable && $exchange_give_disable)
  {
  $('#min-max-warn-data').hide();
    }

  if( ($exchange_get_disable == true) || ($exchange_give_disable == true ) )
  {
    $('#check-oferta').prop('disabled',true)
    $('#check-oferta')[0].checked = false
    $('#submit-exchange').prop('disabled',true)
  }else{
    $('#check-oferta').prop('disabled',false)
    $('#check-oferta')[0].checked = false
    $('#submit-exchange').prop('disabled',true)
  }


}


function amount_change(blockid)
{

  if( blockid == 'input-amount-give' )
  {
    AMOUNT_GIVE = $('#input-amount-give')[0].value;
    if(PAIR_ID > 1000000)
    {
      //$('#submit-form #pair_id')[0].value = PAIR_ID - 1000000
      //$('#submit-form #side')[0].value = 'sell'
      AMOUNT_GET = Math.floor(  AMOUNT_GIVE / CURRENT_RATE * 100000000 ) / 100000000
    }else{
      //$('#submit-form #pair_id')[0].value = PAIR_ID
      //$('#submit-form #side')[0].value = 'buy'
      AMOUNT_GET = Math.ceil( AMOUNT_GIVE * CURRENT_RATE * 100000000 ) / 100000000
    }
  }else{
    AMOUNT_GET = $('#input-amount-get')[0].value
    if(PAIR_ID > 1000000)
    {
      AMOUNT_GIVE = Math.floor(  AMOUNT_GET * CURRENT_RATE * 100000000 ) / 100000000
    }else{
      AMOUNT_GIVE = Math.ceil( AMOUNT_GET / CURRENT_RATE * 100000000 ) / 100000000
    }
  }
  draw_calc()
}


function hide_country_block(side)
{

  //  $('#'+side+'-country-title').hide()
  //  $('#'+side+'-country').hide()
  //  $('#'+side+'-city').hide()
}
function show_country_block(side)
{
  //  $('#'+side+'-country-title').show()
  //  $('#'+side+'-country').show()
  //  $('#'+side+'-city').show()
}
