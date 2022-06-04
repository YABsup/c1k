//* https://newmonitor.alogic.com.ua/api/rates/pairs/new *//

var RATES

var CITY_SELECT_HTML = ''

$(document).ready(function () {

  $.getJSON('https://newmonitor.alogic.com.ua/api/rates/pairs/new',{format: "json"})
  .done(function(data) {
    RATES = data

    selected_country = Object.keys(RATES['country'])[0];

    draw_countries(selected_country)
    cities = get_cities(selected_country)
    selected_city = Object.keys(cities)[0];
    draw_cities(cities, selected_city)

    pairs = RATES['pairs'][1][22]
    left = Object.keys(pairs)[0];
    right = Object.keys(pairs[left])[0];
    draw_pairs_left(pairs, left, right)
    draw_pairs_right(pairs, left, right)

  })
});

function get_pairs(country_id, city_id)
{
  var pairs = []
  $.each(RATES['pairs'][country_id][city_id], function(id, pair){
    pairs['id']=id
    pairs.push(pair)
  })
  console.log(pairs)
  console.log('dsada')
  return pairs
}

function get_cities(country_id)
{
  var cities = []
  $.each(RATES['cities'], function(id, city){
    if ( city['country'] == country_id)
    {
      city['id']=id
      cities.push(city)
    }
  })
  console.log(cities)
  return cities
}

function change_city(blockid)
{
  selected_city = $('#'+blockid+' select')[0].value
  selected_country = $('#give-country select')[0].value

  console.log(selected_city+' '+selected_country);

  pairs = RATES['pairs'][selected_country][selected_city]
  left = Object.keys(pairs)[0];
  right = Object.keys(pairs[left])[0];
  draw_pairs_left(pairs, left, right)
  draw_pairs_right(pairs, left, right)

  draw_pairs_left(pairs, left, right)
  draw_pairs_right(pairs, left, right)
  cities = get_cities(selected_country)
  draw_cities(cities, selected_city)
}

function change_country(blockid)
{
  selected_country = $('#'+blockid+' select')[0].value
  draw_countries(selected_country)
  cities = get_cities(selected_country)
  console.log(cities);
  selected_city_id = Object.keys(cities)[0];
  selected_city=cities[selected_city_id]
  draw_cities(cities, selected_city['id'])

  console.log(selected_country)
  console.log(selected_city['id'])

  pairs = RATES['pairs'][selected_country][selected_city['id']]
  left = Object.keys(pairs)[0];
  console.log(left)
  right = Object.keys(pairs[left])[0]
  draw_pairs_left(pairs, left, right)
  draw_pairs_right(pairs, left, right)
}

function draw_pairs_left(pairs, left, right)
{
  pairs2 = RATES['pairs'][4][186]


  console.log(pairs+pairs2)
  var SELECT_HTML = ''
  console.log('left='+left)
  $.each(pairs, function(id, data){

    SELECT_HTML = SELECT_HTML +'<div id="give_currency_'+id+'" class="currency" onclick="give_currency_change('+id+')">'
    +'            <div class="currency-logo">'
    +'              <img src="img/group-8.svg" class="Group-8"></div>'
    +'            <div class="currency-name">'+RATES['currencies'][id]['name']+'</div>'
    +'            <div class="currency-code">'+RATES['currencies'][id]['code']+'</div>'
    +'            <div class="currency-selected">'
    +'              <img src="/img/fill-357.svg"'
    +'              class="Fill-357">'
    +'            </div>'
    +'          </div>'
  })

  left = Object.keys(pairs)[0];
  right = Object.keys(pairs[left])[0];

  $('#give_currrency_blocks').html(SELECT_HTML)
  give_currency_change(left)
}

function draw_pairs_right(pairs, left, right)
{
  var SELECT_HTML = ''
  $.each(pairs[left], function(id, data){

    SELECT_HTML = SELECT_HTML +'<div id="get_currency_'+id+'" class="currency" onclick="get_currency_change('+id+')">'
    +'            <div class="currency-logo">'
    +'              <img src="img/group-8.svg" class="Group-8"></div>'
    +'            <div class="currency-name">'+RATES['currencies'][id]['name']+'</div>'
    +'            <div class="currency-code">'+RATES['currencies'][id]['code']+'</div>'
    +'            <div class="currency-selected">'
    +'              <img src="/img/fill-357.svg"'
    +'              class="Fill-357">'
    +'            </div>'
    +'          </div>'
  })

  $('#get_currrency_blocks').html(SELECT_HTML)
  get_currency_change(right)
}

function draw_cities(data, selected)
{
  var SELECT_HTML = ''
  $.each(data, function(id, entry){
    if( selected != entry['id'])
    {
      SELECT_HTML = SELECT_HTML + '<option value='+entry['id']+'>' + entry['name'] +'</option>'
    }else{
      SELECT_HTML = SELECT_HTML + '<option value='+entry['id']+' selected>' + entry['name'] +'</option>'
    }
  })
  $('#give-city').html('<select>'+SELECT_HTML+'</select>')
  $('#get-city').html('<select>'+SELECT_HTML+'</select>')
}

function draw_countries(selected)
{
  var SELECT_HTML = ''
  $.each(RATES['country'], function( id, country){
    if( id != 4)
    {
      if(selected != id)
      {
        SELECT_HTML = SELECT_HTML + '<option value='+id+'>' + country['name'] +'</option>'
      }else{
        SELECT_HTML = SELECT_HTML + '<option value='+id+' selected>' + country['name'] +'</option>'
      }
    }
  })
  $('#give-country').html('<select>'+SELECT_HTML+'</select>')
  $('#get-country').html('<select>'+SELECT_HTML+'</select>')

  hide_country_block('get')
  hide_country_block('give')
}

function hide_country_block(side)
{
  $('#'+side+'-country-title').hide()
  $('#'+side+'-country').hide()
  $('#'+side+'-city').hide()
}
function show_country_block(side)
{
  $('#'+side+'-country-title').show()
  $('#'+side+'-country').show()
  $('#'+side+'-city').show()
}
