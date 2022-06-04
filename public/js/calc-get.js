function get_filter_change_all()
{
  $('#get_filter_all').addClass('active').removeClass('border')
  $('#get_filter_cash').removeClass('active').addClass('border')
  $('#get_filter_usd').removeClass('active').addClass('border')
  $('#get_filter_coin').removeClass('active').addClass('border')
}
function get_filter_change_cash()
{
  $('#get_filter_all').removeClass('active').addClass('border')
  $('#get_filter_cash').addClass('active').removeClass('border')
  $('#get_filter_usd').removeClass('active').addClass('border')
  $('#get_filter_coin').removeClass('active').addClass('border')
}
function get_filter_change_usd()
{
  $('#get_filter_all').removeClass('active').addClass('border')
  $('#get_filter_cash').removeClass('active').addClass('border')
  $('#get_filter_usd').addClass('active').removeClass('border')
  $('#get_filter_coin').removeClass('active').addClass('border')
}
function get_filter_change_coin()
{
  $('#get_filter_all').removeClass('active').addClass('border')
  $('#get_filter_cash').removeClass('active').addClass('border')
  $('#get_filter_usd').removeClass('active').addClass('border')
  $('#get_filter_coin').addClass('active').removeClass('border')
}
function get_currency_change(currency_id)
{
    GET_CURRENCY_ID = currency_id
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
}
