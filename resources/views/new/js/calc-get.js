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
  $('#get_currrency_blocks .currency.active').removeClass('active')
  $('#get_currency_'+currency_id).addClass('active')

  if( RATES['currencies'][currency_id]['name'].indexOf("Cash") == 0  )
  {
    show_country_block('get')
  }else{
    hide_country_block('get')
  }
}
