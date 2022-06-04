var LIST_OF_CURRENCIES = [];
var SELECTED_COUNTRY = {};
var CURRENTLY_SELECTED_TOP_CURRENCY = undefined;
var CURRENTLY_SELECTED_BOTTOM_CURRENCY = undefined;
var LAST_CHANGED = undefined;
var MIN_CRYPTO_AMOUNT = undefined;
var MIN_FIAT_AMOUNT = undefined;
var MAX_CRYPTO_AMOUNT = undefined;
var MAX_FIAT_AMOUNT = undefined;

// DOM elements selectors
var TOP_AMOUNT = '#left_currency';
var BOTTOM_AMOUNT = '#right_currency';
var PAIR = '#pair_id';
var SIDE = '#side';
var TOP_LIST = '#dropdown-exchange-left';
var BOTTOM_LIST = '#dropdown-exchange-right';
var TOP_LIST_AMOUNT ='#input-left_up';
var BOTTOM_LIST_AMOUNT = '#input-right_up';
var CHANGE_EXCHANGE_DIRECTION = '#exhange-direction-id';
var LIST_ITEM = '#home-exchange-form .dd-options li';
var MIN_ALERT = '#min-alert';
var MAX_ALERT = '#max-alert';
var CHART = '#title_chart';
var BTN_CHANGE = '#btn_exchange_id';
var IS_TOP = undefined;

$(function() {

var isMobile = {
     Android: function() {
       return navigator.userAgent.match(/Android/i);
     },
     BlackBerry: function() {
       return navigator.userAgent.match(/BlackBerry/i);
     },
     iOS: function() {
       return navigator.userAgent.match(/iPhone|iPad|iPod/i);
     },
     Opera: function() {
       return navigator.userAgent.match(/Opera Mini/i);
     },
     Windows: function() {
       return navigator.userAgent.match(/IEMobile/i);
     },
     any: function() {
       return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
     }
   };


  $('#cashless-tab-link').on('click', function () {
    $('#cash-tab-link').removeClass('active');
    $('#cashless-tab-link').addClass('active');

    $('#cash-tab').removeClass('active');
    $('#cashless-tab').addClass('active');

    $('#countries').hide();
    $('.choose-county-title').hide();
     if( isMobile.any() )
     {
       $('#cash-tab').hide();
       $('#cashless-tab').show();
     }

  });
  $('#cash-tab-link').on('click', function () {
    $('#cashless-tab-link').removeClass('active');
    $('#cash-tab-link').addClass('active');

    $('#cash-tab').addClass('active');
    $('#cashless-tab').removeClass('active');
    $('#countries').show();
    $('.choose-county-title').show();

     if( isMobile.any() )
     {
       $('#cash-tab').show();
       $('#cashless-tab').hide();
     }

  });

  var rates = new Rates(function() {
    $.each(rates.getCategories(), function(i, category) {
      if(category.title === 'Cashless') return;

      $("#countries").append(
        '<input type="radio" name="category" value="'+category.id + '" id="category-input-' + category.id+'" ' + (i === 0 ? 'checked' : '') + '>' +
        '<label for="category-input-' + category.id + '">' + category.title + '</label>'
      );
    });
    $("input[name='category']").on('click', function () {
      LIST_OF_CURRENCIES = fillListOfCurrencies();
      createDropdownList();
      updateWidget();
      displayTopChart(getTopListCurrencyPairs());
    });
    init();
  });

  /**
   * Make init display of widget. Reset all global vars and form lists due to actual information
    */
  function init() {
    LIST_OF_CURRENCIES = fillListOfCurrencies();
    createDropdownList();
    adjustEventListenerToChangeDirectionIcon();
    updateWidget();
    displayTopChart(getTopListCurrencyPairs());
  }

  /**
   * Method updates widget, destroys previous and build new one with new values
   * @param topId
   * @param bottomId
   */
  function updateWidget(top, bottom) {
    top = (top !== undefined) ? top : CURRENTLY_SELECTED_TOP_CURRENCY.possitionInListOfCurrenceis;
    bottom = (bottom !== undefined) ? bottom : CURRENTLY_SELECTED_BOTTOM_CURRENCY.possitionInListOfCurrenceis;
    $(TOP_LIST).ddslick('destroy');
    $(TOP_LIST).empty();
    $(BOTTOM_LIST).ddslick('destroy');
    $(BOTTOM_LIST).empty();
    $(TOP_LIST).ddslick({
      data: LIST_OF_CURRENCIES,
      selectText: '',
      defaultSelectedIndex: top,
      onSelected: function(data) {
        CURRENTLY_SELECTED_TOP_CURRENCY = LIST_OF_CURRENCIES[data.selectedIndex];
      }
    });
    $(BOTTOM_LIST).ddslick({
      data: LIST_OF_CURRENCIES,
      selectText: '',
      defaultSelectedIndex: bottom,
      onSelected: function(data){
        CURRENTLY_SELECTED_BOTTOM_CURRENCY = LIST_OF_CURRENCIES[data.selectedIndex];
      }
    });
    $(LIST_ITEM).click(function() {
      var nameOfSelectedCurrency = $(this).find('.dd-option-text').html();
      $.each(LIST_OF_CURRENCIES, function(index, value) {
        if(nameOfSelectedCurrency === value.text) {
          LAST_CHANGED = LIST_OF_CURRENCIES[index];
          return false;
        }
      });
      updateWidget()
    });
    adjustGlobalListenersToBlocks();
    var pairRates = getFilteredPairForSelectedCurrencies(CURRENTLY_SELECTED_TOP_CURRENCY.currency_id, CURRENTLY_SELECTED_BOTTOM_CURRENCY.currency_id);
    MIN_CRYPTO_AMOUNT = pairRates.min_crypto;
    MIN_FIAT_AMOUNT = pairRates.min_fiat;
    MAX_CRYPTO_AMOUNT = pairRates.max_crypto;
    MAX_FIAT_AMOUNT = pairRates.max_fiat;
    var isTopBase = pairRates.pair.base_id === CURRENTLY_SELECTED_TOP_CURRENCY.currency_id;
    var bottomDecimal = CURRENTLY_SELECTED_BOTTOM_CURRENCY.decimal;
    $(PAIR).val(pairRates.id); // set value to form hidden element
    // $(TOP_LIST_AMOUNT).val(1);
    $(TOP_LIST_AMOUNT).val(
      isTopBase
        ? 1
        : 1 + pairRates.pair.sell_fee_amount
    );
    $(BOTTOM_LIST_AMOUNT).val(
      isTopBase
        ? parseFloat((1 * pairRates.buy).toFixed(bottomDecimal)) + pairRates.pair.buy_fee_amount
        : parseFloat((1 / pairRates.sell).toFixed(bottomDecimal))
    );
    adjustEventListenersToFields($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), pairRates, isTopBase);
    detectAlertsOfMinAmounts($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), pairRates);
    detectAlertsOfMaxAmounts($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), pairRates);
    if(!detectAlertsOfMinAmounts($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), pairRates)) {
      detectAlertsOfMaxAmounts($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), pairRates);
    }
    $(BOTTOM_LIST_AMOUNT).val($(BOTTOM_LIST_AMOUNT).val() < 0 ? 0 : $(BOTTOM_LIST_AMOUNT).val());
    $(TOP_LIST_AMOUNT).val($(TOP_LIST_AMOUNT).val() < 0 ? 0 : $(TOP_LIST_AMOUNT).val());
    console.log('update end')
  }

  /**
   * Create dropdown list based on fetched LIST_OF_CURRENCIES
   * @return {Function} - this method returns nothing and just performs actions
   */
  function createDropdownList() {
    var defaultCurrencies = getFirstCryptoAndFiatIds();
    var CRYPTO = 0;
    var FIAT = 1;
    CURRENTLY_SELECTED_TOP_CURRENCY = LIST_OF_CURRENCIES[defaultCurrencies[CRYPTO]];
    CURRENTLY_SELECTED_BOTTOM_CURRENCY = LIST_OF_CURRENCIES[defaultCurrencies[FIAT]];
    setToInsisibleFieldsAmountsOfExchange();
  }

  /**
   * Get from list of all possible pair for selected country a list of
   * currencies and sort them into array of objects with only necessary key only
   * @return {Array} - item example { id: 1, name: "Bitcoin", type: "crypto" }
   */
  function fillListOfCurrencies() {
    SELECTED_COUNTRY = getSelectedCountry();
    var pairsByCountry = rates.getPairs().filter(function(item) {
      return item['category_id'] === SELECTED_COUNTRY['id'];
    });
    var currency_ids = [];
    var listOfPairs = pairsByCountry.reduce(function(list, item) {
      if (!currency_ids.includes(item.pair.base_id)) {
        currency_ids.push(item.pair.base_id)
        var curr = rates.getCurrencyById(item.pair.base_id);
        list.push({
          currency_id: curr.id,
          text: curr.allias,
          text_short: curr.title,
          type: 'crypto'
        });
      }
      if (!currency_ids.includes(item.pair.quote_id)) {
        currency_ids.push(item.pair.quote_id)
        var curr = rates.getCurrencyById(item.pair.quote_id);
        list.push({
          currency_id: curr.id,
          text: curr.allias,
          text_short: curr.title,
          type: 'fiat'
        });
      }
      return list;
    }, []);
    var listWithIndex = listOfPairs.map(function(item, index) {
      var copy = Object.assign({ }, item);
      copy.possitionInListOfCurrenceis = index;
      return copy;
    });
    var listOfAllCurrencies = rates._currencies;
    var listWithDecimals = listWithIndex.map(function(item, index) {
      var copy = Object.assign({ }, item);
      var decimal = undefined;
      $.each(listOfAllCurrencies, function(key, value) {
        if(value.allias === item.text) {
          decimal = value.decimal_places;
          return false;
        }
      });
      copy.decimal = decimal;
      return copy;
    });
    return listWithDecimals;
  }

  /**
   * Detect whether top currency is crypto currency
   * @param exchangeRates - pair rates
   * @return {boolean}
   */
  function isTopCryptoCurrency(exchangeRates) {
    return CURRENTLY_SELECTED_TOP_CURRENCY.currency_id === exchangeRates.pair.base_id;
  }

  /**
   * Detect whether bottom currency is crypto currency
   * @param exchangeRates - pair rates
   * @return {boolean}
   */
  function isBottomCryptoCurrency(exchangeRates) {
    return CURRENTLY_SELECTED_BOTTOM_CURRENCY.currency_id === exchangeRates.pair.base_id;
  }

  function displayTopChart(list) {
    $(CHART).empty();
    list.map(function(el){
      $(CHART).append(
        '<div class="title_chart_wrapper"><p class="title_chart">'+ el.name + '</p><p class="graph-tabs__span"><span class="pair-price" >'+ el.price  +'</span></p> </div>'
      )
    })

  }

  function countSymbolsInString(string, symbol) {
    var arr = string.slice('');
    var couunter = 0;
    for(var i = 0; i < arr.length; i ++) {
      if(string[i] === symbol) {
        couunter++;
      }
    }
    return couunter;
  }

  function detectAlertsOfMinAmounts(top, bottom, exchangeRates) {
    var topVal = parseFloat(top.val());
    $(BTN_CHANGE).prop("disabled", true);
    var validation = false;
    if($(MAX_ALERT).css('display') === 'block') {
      $(MAX_ALERT).css('display', 'none');
    }
    if(isTopCryptoCurrency(exchangeRates)) {
      if (topVal < MIN_CRYPTO_AMOUNT || top.val() === '') {
        $(MIN_ALERT).css('display', 'block');
        $('.min-alert-amount').html(MIN_CRYPTO_AMOUNT);
        $('.min-alert-amount-currency').html(CURRENTLY_SELECTED_TOP_CURRENCY.text);
        validation = true;
      }else {
        $(MIN_ALERT).css('display', 'none');
        $(BTN_CHANGE).prop("disabled", false);
        validation = false;
      }
    }else {
      if (topVal < MIN_FIAT_AMOUNT) {
        $(MIN_ALERT).css('display', 'block');
        $('.min-alert-amount').html(MIN_FIAT_AMOUNT);
        $('.min-alert-amount-currency').html(CURRENTLY_SELECTED_TOP_CURRENCY.text);
        validation = true;
      }else {
        $(MIN_ALERT).css('display', 'none');
        $(BTN_CHANGE).prop("disabled", false);
        validation = false;
      }
    }
    return validation;
  }

  function detectAlertsOfMaxAmounts(top, bottom, exchangeRates) {
    var topVal = parseFloat(top.val());
    var validation = false;
    $(BTN_CHANGE).prop("disabled", true);
    if($(MIN_ALERT).css('display') === 'block') {
      $(MIN_ALERT).css('display', 'none');
    }
    if(isTopCryptoCurrency(exchangeRates) || top.val() === '') {
      if (topVal > MAX_CRYPTO_AMOUNT) {
        $(MAX_ALERT).css('display', 'block');
        $('.min-alert-amount').html(MAX_CRYPTO_AMOUNT);
        $('.min-alert-amount-currency').html(CURRENTLY_SELECTED_TOP_CURRENCY.text);
        validation = true;
      }else {
        $(MAX_ALERT).css('display', 'none');
        $(BTN_CHANGE).prop("disabled", false);
        validation = false;
      }
    }else {
      if (topVal > MAX_FIAT_AMOUNT || top.val() === '') {
        $(MAX_ALERT).css('display', 'block');
        $('.min-alert-amount').html(MAX_FIAT_AMOUNT);
        $('.min-alert-amount-currency').html(CURRENTLY_SELECTED_TOP_CURRENCY.text);
        validation = true;
      }else {
        $(MIN_ALERT).css('display', 'none');
        $(BTN_CHANGE).prop("disabled", false);
        validation = false;
      }
    }
    return validation;
  }

  // EVENT HANDLERS

  /**
   * Detect which field was clicked last. Sets global IS_TOP to true or false
   */
  function adjustGlobalListenersToBlocks() {
    $(TOP_LIST).click(function () {
            IS_TOP = true;
        });
    $(BOTTOM_LIST).click(function () {
            IS_TOP = false;
        });
  }

  /**
   * Adjust keyup listeners to input fields and make some filtering for input values
   * @param top - top amount selector
   * @param bottom - bottom amount selector
   * @param exchangeRates - fetched exchange rates
   * @param isTopBase - is currency in top base in received rate
   */
  function adjustEventListenersToFields(top, bottom, exchangeRates, isTopBase) {
    var possibleEnterValues = ['.', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'Backspace'];
    var topDecimal = CURRENTLY_SELECTED_TOP_CURRENCY.decimal;
    var bottomDecimal = CURRENTLY_SELECTED_BOTTOM_CURRENCY.decimal;
    $(SIDE).val(isTopBase ? 'buy' : 'sell');
    top.keyup(function(e) {
      console.log(exchangeRates)
      if(possibleEnterValues.includes(e.key)) {
        if(countSymbolsInString(top.val(), '.') === 2) {
          top.val(e.target.value.slice(0, -1));
        }
      }else {
        top.val(e.target.value.slice(0, -1));
      }
      if(top.val().length === 0)  {
        bottom.val('');
      }else {
        bottom.val(
          isTopBase
            ? parseFloat((parseFloat(top.val()) * exchangeRates.buy).toFixed(bottomDecimal)) + exchangeRates.pair.buy_fee_amount
            : parseFloat((parseFloat(top.val()) / exchangeRates.sell).toFixed(bottomDecimal)) - exchangeRates.pair.sell_fee_amount
        );
        bottom.val(
          bottom.val() < 0
          ? 0
          : bottom.val()
        );

      }
      if(!detectAlertsOfMinAmounts($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), exchangeRates)) {
        detectAlertsOfMaxAmounts($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), exchangeRates);
      }
      setToInsisibleFieldsAmountsOfExchange();
    });
    bottom.keyup(function(e) {
      console.log(exchangeRates)
      if(possibleEnterValues.includes(e.key)) {
        if(countSymbolsInString(bottom.val(), '.') === 2) {
          bottom.val(e.target.value.slice(0, -1));
        }
      }else {
        bottom.val(e.target.value.slice(0, -1));
      }
      if(bottom.val().length === 0){
        top.val('');
      }else {
        top.val(
          isTopBase
            ? parseFloat((parseFloat(bottom.val()) / exchangeRates.buy).toFixed(topDecimal)) - + exchangeRates.pair.buy_fee_amount
            : parseFloat((parseFloat(bottom.val()) * exchangeRates.sell).toFixed(topDecimal)) + exchangeRates.pair.sell_fee_amount
        );
        top.val(
          top.val() < 0
            ? 0
            : top.val()
        );
      }
      if(!detectAlertsOfMinAmounts($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), exchangeRates)) {
        detectAlertsOfMaxAmounts($(TOP_LIST_AMOUNT), $(BOTTOM_LIST_AMOUNT), exchangeRates);
      }
      setToInsisibleFieldsAmountsOfExchange();
    });
  }

  /**
   * Set event listener to switch exchange direction
   */
  function adjustEventListenerToChangeDirectionIcon() {
    $(CHANGE_EXCHANGE_DIRECTION).click(function(e) {
      e.preventDefault();
      // switch values of top and bottom
      var tempTop = CURRENTLY_SELECTED_TOP_CURRENCY,
          tempBottom = CURRENTLY_SELECTED_BOTTOM_CURRENCY;
      CURRENTLY_SELECTED_TOP_CURRENCY = tempBottom;
      CURRENTLY_SELECTED_BOTTOM_CURRENCY = tempTop;
      updateWidget(CURRENTLY_SELECTED_TOP_CURRENCY.possitionInListOfCurrenceis, CURRENTLY_SELECTED_BOTTOM_CURRENCY.possitionInListOfCurrenceis);
    });
  }

  // DIFFERENT SETTERS

  /**
   * Set required fields in order to send collected data
   */
  function setToInsisibleFieldsAmountsOfExchange() {
    $(TOP_AMOUNT).val(CURRENTLY_SELECTED_TOP_CURRENCY.currency_id); // set value to form hidden element
    $(BOTTOM_AMOUNT).val(CURRENTLY_SELECTED_BOTTOM_CURRENCY.currency_id); // set value to form hidden element
  }

  // DIFFERENT GETTERS

  /**
   * Get list of top pairs that shall be displayed in top chart of application
   * @return {Array}
   */
  function getTopListCurrencyPairs() {
    var list = getAllAvailablePairRatesByCountry();
    var listOfTop = [];
    for(item in list) {
      if(list[item]['is_top'] === true) {
        var firstPart = getCurrencyById(list[item].pair.base_id).text_short;
        var secondPart = getCurrencyById(list[item].pair.quote_id).text_short;
        var name = firstPart + '/' + secondPart
        var pair = {
          name: name,
          price: list[item].sell_price
        };
        listOfTop.push(pair)
      }
    }
    return listOfTop;
  }

  /**
   * Receive from LIST_OF_CURRENCIES the indexes of first crypto and fiat currencies
   * @return {Array} - [ 1, 4 ]
   */
  function getFirstCryptoAndFiatIds() {
    var firstCryptoCurrency = LIST_OF_CURRENCIES.findIndex(function(value) {
      return value.type === 'crypto';
    });
    var firstFiatCurrency = LIST_OF_CURRENCIES.findIndex(function(value) {
      return value.type === 'fiat';
    });
    return [firstCryptoCurrency, firstFiatCurrency];
  }

  /**
   * Get the selected country button in order to display correct rates per country
   * @return {Object} - { id: 1, parent_id: null, title: "UAE", order: 1 }
   */
  function getSelectedCountry() {
    var cat_id = $("input[name='category']:checked").val();
    return rates.getCategoryById(parseInt(cat_id));
  }

  /**
   * Get list of all possible exchange rates for all countries
   * @return {Array} - list of all possible exchange rates
   */
  function getAllAvailableRates() {
    return rates._pairs_keys;
  }

  /**
   * Get list of available exchange rates by specified country
   * @return {Array} - list of currencies' ids [ '1_2', '1_4' ... ]
   */
  function getAvailableRatesByCountry() {
    var rawList = getAllAvailableRates().filter(function (rate) {
      return rate.startsWith(SELECTED_COUNTRY['id'], 0);
    });
    return rawList.map(function (item) {
      arr = item.split('_');
      symbol = arr[1]+'_'+arr[2];

      return symbol;
    });
  }

  /**
   * Get all possible pair for all countries with list of all info for each pair
   * @return {Object} - Object of objects where each represents full info about pair
   */
  function getAllAvailablePairRates() {
    return rates._pairs;
  }

  /**
   * Get all possible pair for specified country with list of all info for each pair
   * @return {Object} - Object of objects where each represents full info about pair
   */
  function getAllAvailablePairRatesByCountry() {
    var rawList = getAllAvailablePairRates();
    return Object.keys(rawList).reduce(function(list, pairItemName) {
      if(pairItemName.startsWith(SELECTED_COUNTRY['id'])) {
        // cut first number of country id in 1_2_0 so we have in key only currency pair
        //list[pairItemName.substring(2)] = rawList[pairItemName];

        arr = pairItemName.split('_');
        symbol = arr[1]+'_'+arr[2];

        list[symbol] = rawList[pairItemName];
      }
      return list;
    }, {});
  }

  /**
   * Get actual pair exchange for selected currencies or get another possible pair
   * @param topCurrency - id of currency in top selector
   * @param bottomCurrency - id of currency in bottom selector
   * @return {Object} - { id: 1, category_id: 1, pair: {…}, sell_price: 9000, buy_price: 8500, … }
   */
  function getPairForSelectedCurrencies(topCurrency, bottomCurrency) {
    var possiblePairOne = topCurrency + '_' + bottomCurrency;
    var possiblePairTwo = bottomCurrency + '_' + topCurrency;
    var listOfAllPossiblePairRates = getAllAvailablePairRatesByCountry();
    if(listOfAllPossiblePairRates.hasOwnProperty(possiblePairOne)) {
      return listOfAllPossiblePairRates[possiblePairOne];
    }else if(listOfAllPossiblePairRates.hasOwnProperty(possiblePairTwo)) {
      return listOfAllPossiblePairRates[possiblePairTwo];
    }else {
      var listOfRates = getAvailableRatesByCountry();
      var suitablePairKey = undefined;
      var suitablePairIndexInList = undefined;
      $.each(listOfRates, function(index, value) {
        if(value.includes(LAST_CHANGED.currency_id)) {
          suitablePairKey = listOfRates[index];
          suitablePairIndexInList = index;
          return false;
        }
      });
      var newBottomCurrencyId = suitablePairKey.replace('_', '').replace(LAST_CHANGED.currency_id, '');
      CURRENTLY_SELECTED_TOP_CURRENCY = (IS_TOP) ? LAST_CHANGED : getCurrencyById(newBottomCurrencyId)
      CURRENTLY_SELECTED_BOTTOM_CURRENCY = (!IS_TOP) ? LAST_CHANGED : getCurrencyById(newBottomCurrencyId)
      updateWidget(CURRENTLY_SELECTED_TOP_CURRENCY.possitionInListOfCurrenceis, CURRENTLY_SELECTED_BOTTOM_CURRENCY.possitionInListOfCurrenceis);
      return listOfAllPossiblePairRates[suitablePairKey];
    }
  }

  /**
   * Get currency from actual LIST_OF_CURRENCIES by its id
   * @param id - of searched currency
   * @return {Object}
   */
  function getCurrencyById(id) {
    var searchedCurrency = undefined;
    $.each(LIST_OF_CURRENCIES, function(index, value) {
      if(value.currency_id === parseInt(id)) {
        searchedCurrency = value;
        return false;
      }
    });
    return searchedCurrency;
  }

  /**
   * Get actual (only useful values) pair exchange for selected currencies or get another possible pair
   * @param topCurrency - id of currency in top selector
   * @param bottomCurrency - id of currency in bottom selector
   * @return {{buy: *, sell: *, pair: *}}
   */
  function getFilteredPairForSelectedCurrencies(topCurrency, bottomCurrency) {
    var pair = getPairForSelectedCurrencies(topCurrency, bottomCurrency);
    return {
      buy: pair['buy_price'],
      sell: pair['sell_price'],
      pair: pair['pair'],
      id: pair['id'],
      min_crypto: pair['min_amount_base'],
      min_fiat: pair['min_amount_quote'],
      max_crypto: pair['max_amount_base'],
      max_fiat: pair['max_amount_quote'],
    };
  }

});
