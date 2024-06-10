import Vue from 'vue';

export function notify(message, type = 'default', position = 'bottom-right', options = {}) {
    Vue.$toast.open({
        message,
        type: type,
        duration: 3000,
        position: (screen.width < 992) ? 'bottom' : position,
        ...options,
    });
}

export function trans(langKey, replace = {}) {
    let line = window.singlebag.langs[langKey];

    for (let key in replace) {
        line = line.replace(`:${key}`, replace[key]);
    }

    return line ?? langKey;
}

export function formatPrice(amount = 0) {
    let currency = window.singlebag.currency;

    if (currency) {
        if (currency['position'] == 0) {
            return currency['symbol'] + "" + formatMoney(amount);
        } else {
            return amount + "" + currency['symbol'];
        }
    }

    return amount;
}

function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
    try {
        
        if(isNaN(amount)) {
            amount = parseInt(amount.replace(/[,]/g,''));
        }
        
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign +
            (j ? i.substr(0, j) + thousands : '') +
            i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) +
            (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
    }
};


export function isEmpty(value) {
    return $.isEmptyObject(value);
}

export function chunk(array, size) {
    let chunkedArray = [];
    let index = 0;
    while (index < array.length) {
        chunkedArray.push(array.slice(index, size + index));
        index += size;
    }

    return chunkedArray;
}

export function isMobileNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
      evt.preventDefault();;
    } else {
      return true;
    }
}
