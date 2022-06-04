function Rates(onDone) {
    this.loading = true;

    this._currencies_keys = [];
    this._currencies = {};

    this._pairs_keys = [];
    this._pairs = {};

    this._categories_keys = [];
    this._categories = {};

    $.ajax("/api/rates/pairs")
        .done(function(pairs) {
            this.loading = false;
            this.normalizePairs(pairs['pairs'], pairs['currencies'], pairs['categories']);
            onDone();
        }.bind(this));
}

Rates.prototype.normalizePairs = function(pairs, currencies, categories) {

    this._currencies_keys = currencies.map(function(curr) {
        this._currencies[curr.id] = curr;
        return curr.id;
    }.bind(this));

    this._pairs_keys = pairs.map(function(pair) {
        var key = this.getPairId(pair.pair.base_id, pair.pair.quote_id, pair.category_id);
        this._pairs[key] = pair;
        return key;
    }.bind(this));

    this._categories_keys = categories.map(function(cat) {
        this._categories[cat.id] = cat;
        return cat.id;
    }.bind(this));

    this.deepFreeze(this._categories)
    this.deepFreeze(this._categories_keys)
    this.deepFreeze(this._pairs)
    this.deepFreeze(this._pairs_keys)
    this.deepFreeze(this._currencies)
    this.deepFreeze(this._currencies_keys)
}

Rates.prototype.getPairId = function(base_id, quote_id, category_id) {
    return category_id + "_" + base_id + "_" + quote_id;
}

/* Returns true is pair for given category, base and quote currency exist */
Rates.prototype.isPairExist = function(category_id, base_id, quote_id) {
    var key = this.getPairId(base_id, quote_id, category_id);

    if(this._pairs[key] === undefined) {
        return false;
    } else {
        return true;
    }
}

/* Returns sorted list of categories with given parent_id */
Rates.prototype.getCategories = function(parent_id=null) {
    return this._categories_keys
        .filter(function(cat_id) {
            return this._categories[cat_id].parent_id === parent_id;
        }.bind(this))
        .map(function(cat_id) {
            return this._categories[cat_id];
        }.bind(this))
}

Rates.prototype.getCategoryById = function(cat_id) {
    return this._categories[cat_id];
}

Rates.prototype.getPairs = function() {
    return this._pairs_keys
        .map(function(pair_id) {
            return this._pairs[pair_id]
        }.bind(this))
}

// Returns pair by base and quoute currency ids and category
Rates.prototype.getPairByCategoryAndCurrencies = function(base_id, quote_id, category_id) {
    var key = this.getPairId(base_id, quote_id, category_id);
    return this._pairs[key]
}

Rates.prototype.getCurrencyById = function(curr_id) {
    return this._currencies[curr_id]
}

Rates.prototype.getCurrencies = function() {
    return this._currencies_keys
        .map(function(key) {
            return this._currencies[key]
        });
}

Rates.prototype.deepFreeze = function(o) {
    Object.freeze(o);

    Object.getOwnPropertyNames(o).forEach(function (prop) {
        if (o.hasOwnProperty(prop)
        && o[prop] !== null
        && (typeof o[prop] === "object" || typeof o[prop] === "function")
        && !Object.isFrozen(o[prop])) {
            this.deepFreeze(o[prop]);
        }
    }.bind(this));

    return o;
};
