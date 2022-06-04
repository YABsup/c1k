
function give_filter_change_all()
{
  $('#give_filter_all').addClass('active').removeClass('border')
  $('#give_filter_cash').removeClass('active').addClass('border')
  $('#give_filter_usd').removeClass('active').addClass('border')
  $('#give_filter_coin').removeClass('active').addClass('border')
}
function give_filter_change_cash()
{
  $('#give_filter_all').removeClass('active').addClass('border')
  $('#give_filter_cash').addClass('active').removeClass('border')
  $('#give_filter_usd').removeClass('active').addClass('border')
  $('#give_filter_coin').removeClass('active').addClass('border')
}
function give_filter_change_usd()
{
  $('#give_filter_all').removeClass('active').addClass('border')
  $('#give_filter_cash').removeClass('active').addClass('border')
  $('#give_filter_usd').addClass('active').removeClass('border')
  $('#give_filter_coin').removeClass('active').addClass('border')
}
function give_filter_change_coin()
{
  $('#give_filter_all').removeClass('active').addClass('border')
  $('#give_filter_cash').removeClass('active').addClass('border')
  $('#give_filter_usd').removeClass('active').addClass('border')
  $('#give_filter_coin').addClass('active').removeClass('border')
}
function give_currency_change(currency_id)
{
    GIVE_CURRENCY_ID = currency_id
    GET_CURRENCY_ID = undefined
    AMOUNT_GIVE = undefined

    if( RATES['currencies'][GIVE_CURRENCY_ID]['name'].indexOf("Cash") == 0  )
    {
        show_country_block('give')
    }else{
        hide_country_block('give')
    }


    draw_pairs()
    draw_calc()
    amount_change('input-amount-get')
//  selected_city = $('#give-city select')[0].value
//  selected_country = $('#give-country select')[0].value

//  pairs = RATES['pairs'][selected_country][selected_city]
//  left = currency_id
//  right = Object.keys(pairs[left])[0]
//  //draw_pairs_left(pairs, left, right)
//  if( RATES['currencies'][GIVE_CURRENCY_ID]['name'].indexOf("Cash") == 0  )
//  {
//    show_country_block('give')
//  }else{
//    hide_country_block('give')
//  }
//  draw_pairs_right(pairs, left, right)

//  $('#calc-data-in').html('<div class="currency-logo"><img src="/coin-logo/'+RATES['currencies'][left]['code']+'.png" class="Group-8"></div><div class="currency-name">'+RATES['currencies'][left]['name']+'</div> <div class="currency-code">'+RATES['currencies'][left]['code'].replace("CASH","").replace("CARD","")+'</div>')
//  $('#calc-data-out').html('<div class="currency-logo"><img src="/coin-logo/'+RATES['currencies'][right]['code']+'.png" class="Group-8"></div><div class="currency-name">'+RATES['currencies'][right]['name']+'</div> <div class="currency-code">'+RATES['currencies'][right]['code'].replace("CASH","").replace("CARD","")+'</div>')

//  $('#input-amount-give')[0].value = pairs[left][right]['min']
//  $('#input-amount-get')[0].value = pairs[left][right]['min'] * pairs[left][right]['rate']
//
//
//  // $('#calc-data-out').
}
