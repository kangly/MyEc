/*!
 * Distpicker v2.0.5
 * https://fengyuanchen.github.io/distpicker
 * Copyright 2014-present Chen Fengyuan
 * Released under the MIT license
 * Date: 2018-12-01T09:59:25.126Z
 */
(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory(require('jquery')) :
        typeof define === 'function' && define.amd ? define(['jquery'], factory) :
            (factory(global.jQuery));
}(this, (function ($) { 'use strict';

$ = $ && $.hasOwnProperty('default') ? $['default'] : $;

function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
        throw new TypeError("Cannot call a class as a function");
    }
}

function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
        var descriptor = props[i];
        descriptor.enumerable = descriptor.enumerable || false;
        descriptor.configurable = true;
        if ("value" in descriptor) descriptor.writable = true;
        Object.defineProperty(target, descriptor.key, descriptor);
    }
}

function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
}

var DEFAULTS = {
    // Selects the districts automatically.
    // 0 -> Disable autoselect
    // 1 -> Autoselect province only
    // 2 -> Autoselect province and city only
    // 3 -> Autoselect all (province, city and district)
    autoselect: 0,
    // Show placeholder.
    placeholder: true,
    // Select value. Options: 'name' and 'code'
    valueType: 'name',
    // Defines the initial value of province.
    province: '—— 省 ——',
    // Defines the initial value of city.
    city: '—— 市 ——',
    // Defines the initial value of district.
    district: '—— 区 ——'
};

var WINDOW = typeof window !== 'undefined' ? window : {};
var NAMESPACE = 'distpicker';
var EVENT_CHANGE = 'change';

var DEFAULT_CODE = 100000;
var PROVINCE = 'province';
var CITY = 'city';
var DISTRICT = 'district';