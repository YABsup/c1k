var CASHLESS_LIST_OF_CURRENCIES = [];
var CASHLESS_SELECTED_COUNTRY = {};
var CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY = undefined;
var CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY = undefined;
var CASHLESS_LAST_CHANGED = undefined;
var CASHLESS_MIN_CRYPTO_AMOUNT = undefined;
var CASHLESS_MIN_FIAT_AMOUNT = undefined;
var CASHLESS_MAX_CRYPTO_AMOUNT = undefined;
var CASHLESS_MAX_FIAT_AMOUNT = undefined;

// DOM elements selectors
var CASHLESS_TOP_AMOUNT = '#cashless-left_currency';
var CASHLESS_BOTTOM_AMOUNT = '#cashless-right_currency';
var CASHLESS_PAIR = '#cashless-pair_id';
var CASHLESS_SIDE = '#cashless-side';
var CASHLESS_TOP_LIST = '#cashless-dropdown-exchange-left';
var CASHLESS_BOTTOM_LIST = '#cashless-dropdown-exchange-right';
var CASHLESS_TOP_LIST_AMOUNT ='#cashless-input-left_up';
var CASHLESS_BOTTOM_LIST_AMOUNT = '#cashless-input-right_up';
var CASHLESS_CHANGE_EXCHANGE_DIRECTION = '#cashless-exhange-direction-id';
var CASHLESS_LIST_ITEM = '#cashless-home-exchange-form .dd-options li';
var CASHLESS_MIN_ALERT = '#cashless-min-alert';
var CASHLESS_MAX_ALERT = '#cashless-max-alert';
var CASHLESS_CHART = '#cashless-title_chart';
var CASHLESS_BTN_CHANGE = '#cashless-btn_exchange_id';
var CASHLESS_IS_TOP = undefined;

$(function() {
  var rates = new Rates(function() {
    init();
  });

  /**
   * Make init display of widget. Reset all global vars and form lists due to actual information
    */
  function init() {
    CASHLESS_LIST_OF_CURRENCIES = fillListOfCurrencies();
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
    top = (top !== undefined) ? top : CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.possitionInListOfCurrenceis;
    bottom = (bottom !== undefined) ? bottom : CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY.possitionInListOfCurrenceis;
    $(CASHLESS_TOP_LIST).ddslick('destroy');
    $(CASHLESS_TOP_LIST).empty();
    $(CASHLESS_BOTTOM_LIST).ddslick('destroy');
    $(CASHLESS_BOTTOM_LIST).empty();
    $(CASHLESS_TOP_LIST).ddslick({
      data: CASHLESS_LIST_OF_CURRENCIES,
      selectText: '',
      defaultSelectedIndex: top,
      onSelected: function(data) {
        CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY = CASHLESS_LIST_OF_CURRENCIES[data.selectedIndex];
      }
    });
    $(CASHLESS_BOTTOM_LIST).ddslick({
      data: CASHLESS_LIST_OF_CURRENCIES,
      selectText: '',
      defaultSelectedIndex: bottom,
      onSelected: function(data){
        CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY = CASHLESS_LIST_OF_CURRENCIES[data.selectedIndex];
      }
    });
    $(CASHLESS_LIST_ITEM).click(function() {
      var nameOfSelectedCurrency = $(this).find('.dd-option-text').html();
      $.each(CASHLESS_LIST_OF_CURRENCIES, function(index, value) {
        if(nameOfSelectedCurrency === value.text) {
          CASHLESS_LAST_CHANGED = CASHLESS_LIST_OF_CURRENCIES[index];
          return false;
        }
      });
      updateWidget()
    });
    adjustGlobalListenersToBlocks();
    var pairRates = getFilteredPairForSelectedCurrencies(CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.currency_id, CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY.currency_id);
    CASHLESS_MIN_CRYPTO_AMOUNT = pairRates.min_crypto;
    CASHLESS_MIN_FIAT_AMOUNT = pairRates.min_fiat;
    CASHLESS_MAX_CRYPTO_AMOUNT = pairRates.max_crypto;
    CASHLESS_MAX_FIAT_AMOUNT = pairRates.max_fiat;
    var isTopBase = pairRates.pair.base_id === CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.currency_id;
    var bottomDecimal = CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY.decimal;

    $(CASHLESS_PAIR).val(pairRates.id); // set value to form hidden element
    // $(CASHLESS_TOP_LIST_AMOUNT).val(1);
    $(CASHLESS_TOP_LIST_AMOUNT).val(
      isTopBase
        ? 1
        : 1 + pairRates.pair.sell_fee_amount
    );
    $(CASHLESS_BOTTOM_LIST_AMOUNT).val(
      isTopBase
        ? parseFloat((1 * pairRates.buy)) + pairRates.pair.buy_fee_amount
        : parseFloat((1 / pairRates.sell))
    );
    adjustEventListenersToFields($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), pairRates, isTopBase);
    detectAlertsOfMinAmounts($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), pairRates);
    detectAlertsOfMaxAmounts($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), pairRates);
    if(!detectAlertsOfMinAmounts($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), pairRates)) {
      detectAlertsOfMaxAmounts($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), pairRates);
    }
    $(CASHLESS_BOTTOM_LIST_AMOUNT).val($(CASHLESS_BOTTOM_LIST_AMOUNT).val() < 0 ? 0 : $(CASHLESS_BOTTOM_LIST_AMOUNT).val());
    $(CASHLESS_TOP_LIST_AMOUNT).val($(CASHLESS_TOP_LIST_AMOUNT).val() < 0 ? 0 : $(CASHLESS_TOP_LIST_AMOUNT).val());
    console.log('update end')
  }

  /**
   * Create dropdown list based on fetched CASHLESS_LIST_OF_CURRENCIES
   * @return {Function} - this method returns nothing and just performs actions
   */
  function createDropdownList() {
    var defaultCurrencies = getFirstCryptoAndFiatIds();
    var CRYPTO = 0;
    var FIAT = 1;
    CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY = CASHLESS_LIST_OF_CURRENCIES[defaultCurrencies[CRYPTO]];
    CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY = CASHLESS_LIST_OF_CURRENCIES[defaultCurrencies[FIAT]];
    setToInsisibleFieldsAmountsOfExchange();
  }

  /**
   * Get from list of all possible pair for selected country a list of
   * currencies and sort them into array of objects with only necessary key only
   * @return {Array} - item example { id: 1, name: "Bitcoin", type: "crypto" }
   */
  function fillListOfCurrencies() {
    CASHLESS_SELECTED_COUNTRY = getSelectedCountry();

    var pairsByCountry = rates.getPairs().filter(function(item) {
      return item['category_id'] === CASHLESS_SELECTED_COUNTRY['id'];
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
    return CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.currency_id === exchangeRates.pair.base_id;
  }

  /**
   * Detect whether bottom currency is crypto currency
   * @param exchangeRates - pair rates
   * @return {boolean}
   */
  function isBottomCryptoCurrency(exchangeRates) {
    return CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY.currency_id === exchangeRates.pair.base_id;
  }

  function displayTopChart(list) {
    $(CASHLESS_CHART).empty();
    list.map(function(el){
      $(CASHLESS_CHART).append(
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
    $(CASHLESS_BTN_CHANGE).prop("disabled", true);
    var validation = false;
    if($(CASHLESS_MAX_ALERT).css('display') === 'block') {
      $(CASHLESS_MAX_ALERT).css('display', 'none');
    }
    if(isTopCryptoCurrency(exchangeRates)) {
      if (topVal < CASHLESS_MIN_CRYPTO_AMOUNT || top.val() === '') {
        $(CASHLESS_MIN_ALERT).css('display', 'block');
        $('.min-alert-amount').html(CASHLESS_MIN_CRYPTO_AMOUNT);
        $('.min-alert-amount-currency').html(CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.text);
        validation = true;
      }else {
        $(CASHLESS_MIN_ALERT).css('display', 'none');
        $(CASHLESS_BTN_CHANGE).prop("disabled", false);
        validation = false;
      }
    }else {
      if (topVal < CASHLESS_MIN_FIAT_AMOUNT) {
        $(CASHLESS_MIN_ALERT).css('display', 'block');
        $('.min-alert-amount').html(CASHLESS_MIN_FIAT_AMOUNT);
        $('.min-alert-amount-currency').html(CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.text);
        validation = true;
      }else {
        $(CASHLESS_MIN_ALERT).css('display', 'none');
        $(CASHLESS_BTN_CHANGE).prop("disabled", false);
        validation = false;
      }
    }
    return validation;
  }

  function detectAlertsOfMaxAmounts(top, bottom, exchangeRates) {
    var topVal = parseFloat(top.val());
    var validation = false;
    $(CASHLESS_BTN_CHANGE).prop("disabled", true);
    if($(CASHLESS_MIN_ALERT).css('display') === 'block') {
      $(CASHLESS_MIN_ALERT).css('display', 'none');
    }
    if(isTopCryptoCurrency(exchangeRates) || top.val() === '') {
      if (topVal > CASHLESS_MAX_CRYPTO_AMOUNT) {
        $(CASHLESS_MAX_ALERT).css('display', 'block');
        $('.min-alert-amount').html(CASHLESS_MAX_CRYPTO_AMOUNT);
        $('.min-alert-amount-currency').html(CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.text);
        validation = true;
      }else {
        $(CASHLESS_MAX_ALERT).css('display', 'none');
        $(CASHLESS_BTN_CHANGE).prop("disabled", false);
        validation = false;
      }
    }else {
      if (topVal > CASHLESS_MAX_FIAT_AMOUNT || top.val() === '') {
        $(CASHLESS_MAX_ALERT).css('display', 'block');
        $('.min-alert-amount').html(CASHLESS_MAX_FIAT_AMOUNT);
        $('.min-alert-amount-currency').html(CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.text);
        validation = true;
      }else {
        $(CASHLESS_MIN_ALERT).css('display', 'none');
        $(CASHLESS_BTN_CHANGE).prop("disabled", false);
        validation = false;
      }
    }
    return validation;
  }

  // EVENT HANDLERS

  /**
   * Detect which field was clicked last. Sets global CASHLESS_IS_TOP to true or false
   */
  function adjustGlobalListenersToBlocks() {
    $(CASHLESS_TOP_LIST).click(function () {
            CASHLESS_IS_TOP = true;
        });
    $(CASHLESS_BOTTOM_LIST).click(function () {
            CASHLESS_IS_TOP = false;
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
    var topDecimal = CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.decimal;
    var bottomDecimal = CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY.decimal;
    $(CASHLESS_SIDE).val(isTopBase ? 'buy' : 'sell');
    top.keyup(function(e) {
      console.log(exchangeRates)
      if(possibleEnterValues.includes(e.key) || (possibleEnterValues.includes(e.target.value.charAt(event.target.selectionStart - 1))) ) {
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
      if(!detectAlertsOfMinAmounts($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), exchangeRates)) {
        detectAlertsOfMaxAmounts($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), exchangeRates);
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
      if(!detectAlertsOfMinAmounts($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), exchangeRates)) {
        detectAlertsOfMaxAmounts($(CASHLESS_TOP_LIST_AMOUNT), $(CASHLESS_BOTTOM_LIST_AMOUNT), exchangeRates);
      }
      setToInsisibleFieldsAmountsOfExchange();
    });
  }

  /**
   * Set event listener to switch exchange direction
   */
  function adjustEventListenerToChangeDirectionIcon() {
    $(CASHLESS_CHANGE_EXCHANGE_DIRECTION).click(function(e) {
      e.preventDefault();
      // switch values of top and bottom
      var tempTop = CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY,
          tempBottom = CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY;
      CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY = tempBottom;
      CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY = tempTop;
      updateWidget(CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.possitionInListOfCurrenceis, CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY.possitionInListOfCurrenceis);
    });
  }

  // DIFFERENT SETTERS

  /**
   * Set required fields in order to send collected data
   */
  function setToInsisibleFieldsAmountsOfExchange() {
    $(CASHLESS_TOP_AMOUNT).val(CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.currency_id); // set value to form hidden element
    $(CASHLESS_BOTTOM_AMOUNT).val(CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY.currency_id); // set value to form hidden element
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
   * Receive from CASHLESS_LIST_OF_CURRENCIES the indexes of first crypto and fiat currencies
   * @return {Array} - [ 1, 4 ]
   */
  function getFirstCryptoAndFiatIds() {
    var firstCryptoCurrency = CASHLESS_LIST_OF_CURRENCIES.findIndex(function(value) {
      return value.type === 'crypto';
    });
    var firstFiatCurrency = CASHLESS_LIST_OF_CURRENCIES.findIndex(function(value) {
      return value.type === 'fiat';
    });
    return [firstCryptoCurrency, firstFiatCurrency];
  }

  /**
   * Get the selected country button in order to display correct rates per country
   * @return {Object} - { id: 1, parent_id: null, title: "UAE", order: 1 }
   */
  function getSelectedCountry() {
    let cashlessCategory = {};

    $.each(rates.getCategories(), function(i, category) {
      if(category.title === 'Cashless') cashlessCategory = category;
    });

    return cashlessCategory;
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
      return rate.startsWith(CASHLESS_SELECTED_COUNTRY['id'], 0);
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
      if(pairItemName.startsWith(CASHLESS_SELECTED_COUNTRY['id'])) {
        // cut first number of country id in 1_2_0 so we have in key only currency pair

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
        if(value.includes(CASHLESS_LAST_CHANGED.currency_id)) {
          suitablePairKey = listOfRates[index];
          suitablePairIndexInList = index;
          return false;
        }
      });

      var newBottomCurrencyId = suitablePairKey.replace('_', '').replace(CASHLESS_LAST_CHANGED.currency_id, '');



      CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY = (CASHLESS_IS_TOP) ? CASHLESS_LAST_CHANGED : getCurrencyById(newBottomCurrencyId)
      CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY = (!CASHLESS_IS_TOP) ? CASHLESS_LAST_CHANGED : getCurrencyById(newBottomCurrencyId)
      updateWidget(CASHLESS_CURRENTLY_SELECTED_TOP_CURRENCY.possitionInListOfCurrenceis, CASHLESS_CURRENTLY_SELECTED_BOTTOM_CURRENCY.possitionInListOfCurrenceis);
      return listOfAllPossiblePairRates[suitablePairKey];
    }
  }

  /**
   * Get currency from actual CASHLESS_LIST_OF_CURRENCIES by its id
   * @param id - of searched currency
   * @return {Object}
   */
  function getCurrencyById(id) {
    var searchedCurrency = undefined;
    $.each(CASHLESS_LIST_OF_CURRENCIES, function(index, value) {
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
