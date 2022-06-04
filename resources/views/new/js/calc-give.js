


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
  $('#give_currrency_blocks .currency.active').removeClass('active')
  $('#give_currency_'+currency_id).addClass('active')

  selected_city = $('#give-city select')[0].value
  selected_country = $('#give-country select')[0].value

  pairs = RATES['pairs'][selected_country][selected_city]
  left = currency_id
  right = Object.keys(pairs[left])[0]
  //draw_pairs_left(pairs, left, right)
  if( RATES['currencies'][currency_id]['name'].indexOf("Cash") == 0  )
  {
    show_country_block('give')
  }else{
    hide_country_block('give')
  }
  draw_pairs_right(pairs, left, right)
}
