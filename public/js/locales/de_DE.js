$.fn.priceFormat.defaults = {
    prefix: ' ',
    suffix: '',
    centsSeparator: ',',
    thousandsSeparator: '.',
    limit: false,
    centsLimit: 2,
    clearPrefix: false,
    clearSufix: false,
    allowNegative: false,
    insertPlusSign: false,
    clearOnEmpty: false,
    leadingZero: true
};

numeral.defaultFormat('0,0.00');
