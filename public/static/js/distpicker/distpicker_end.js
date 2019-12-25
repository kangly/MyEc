var Distpicker =
    /*#__PURE__*/
    function () {
        function Distpicker(element, options) {
            _classCallCheck(this, Distpicker);

            this.$element = $(element);
            this.options = $.extend({}, DEFAULTS, $.isPlainObject(options) && options);
            this.placeholders = $.extend({}, DEFAULTS);
            this.ready = false;
            this.init();
        }

        _createClass(Distpicker, [{
            key: "init",
            value: function init() {
                var _this = this;

                var options = this.options;
                var $selects = this.$element.find('select');
                var length = $selects.length;
                var data = {};
                $selects.each(function (i, select) {
                    return $.extend(data, $(select).data());
                });
                $.each([PROVINCE, CITY, DISTRICT], function (i, type) {
                    if (data[type]) {
                        options[type] = data[type];
                        _this["$".concat(type)] = $selects.filter("[data-".concat(type, "]"));
                    } else {
                        _this["$".concat(type)] = length > i ? $selects.eq(i) : null;
                    }
                });
                this.bind(); // Reset all the selects (after event binding)

                this.reset();
                this.ready = true;
            }
        }, {
            key: "bind",
            value: function bind() {
                var _this2 = this;

                if (this.$province) {
                    this.$province.on(EVENT_CHANGE, this.onChangeProvince = $.proxy(function () {
                        _this2.output(CITY);

                        _this2.output(DISTRICT, true);
                    }, this));
                }

                if (this.$city) {
                    this.$city.on(EVENT_CHANGE, this.onChangeCity = $.proxy(function () {
                        return _this2.output(DISTRICT, true);
                    }, this));
                }
            }
        }, {
            key: "unbind",
            value: function unbind() {
                if (this.$province) {
                    this.$province.off(EVENT_CHANGE, this.onChangeProvince);
                }

                if (this.$city) {
                    this.$city.off(EVENT_CHANGE, this.onChangeCity);
                }
            }
        }, {
            key: "output",
            value: function output(type) {
                var triggerEvent = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
                var options = this.options,
                    placeholders = this.placeholders;
                var $select = this["$".concat(type)];

                if (!$select || !$select.length) {
                    return;
                }

                var code;
                switch (type) {
                    case PROVINCE:
                        code = DEFAULT_CODE;
                        break;
                    case CITY:
                        code = this.$province && (this.$province.find(':selected').data('code') || '');
                        break;
                    case DISTRICT:
                        code = this.$city && (this.$city.find(':selected').data('code') || '');
                        break;
                }

                var districts = this.getDistricts(code);
                var value = options[type];
                var data = [];
                var matched = false;

                if ($.isPlainObject(districts)) {
                    $.each(districts, function (i, name) {
                        var selected = name === value || i === String(value);

                        if (selected) {
                            matched = true;
                        }

                        data.push({
                            name: name,
                            selected: selected,
                            code: i,
                            value: options.valueType === 'name' ? name : i
                        });
                    });
                }

                if (!matched) {
                    var autoselect = options.autoselect || options.autoSelect;

                    if (data.length && (type === PROVINCE && autoselect > 0 || type === CITY && autoselect > 1 || type === DISTRICT && autoselect > 2)) {
                        data[0].selected = true;
                    } // Save the unmatched value as a placeholder at the first output

                    if (!this.ready && value) {
                        placeholders[type] = value;
                    }
                } // Add placeholder option

                if (options.placeholder) {
                    data.unshift({
                        code: '',
                        name: placeholders[type],
                        value: '',
                        selected: false
                    });
                }

                if (data.length) {
                    $select.html(this.getList(data));
                } else {
                    $select.empty();
                }

                if (triggerEvent) {
                    $select.trigger(EVENT_CHANGE);
                }
            } // eslint-disable-next-line class-methods-use-this

        }, {
            key: "getList",
            value: function getList(data) {
                var list = [];
                $.each(data, function (i, n) {
                    var attrs = ["data-code=\"".concat(n.code, "\""), "data-text=\"".concat(n.name, "\""), "value=\"".concat(n.value, "\"")];

                    if (n.selected) {
                        attrs.push('selected');
                    }

                    list.push("<option ".concat(attrs.join(' '), ">").concat(n.name, "</option>"));
                });
                return list.join('');
            } // eslint-disable-next-line class-methods-use-this

        }, {
            key: "getDistricts",
            value: function getDistricts() {
                var code = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : DEFAULT_CODE;
                return DISTRICTS[code] || null;
            }
        }, {
            key: "reset",
            value: function reset(deep) {
                if (!deep) {
                    this.output(PROVINCE);
                    this.output(CITY);
                    this.output(DISTRICT);
                } else if (this.$province) {
                    this.$province.find(':first').prop('selected', true).end().trigger(EVENT_CHANGE);
                }
            }
        }, {
            key: "destroy",
            value: function destroy() {
                this.unbind();
            }
        }], [{
            key: "setDefaults",
            value: function setDefaults(options) {
                $.extend(DEFAULTS, $.isPlainObject(options) && options);
            }
        }]);

        return Distpicker;
    }();

if ($.fn) {
    var AnotherDistpicker = $.fn.distpicker;

    $.fn.distpicker = function jQueryDistpicker(option) {
        for (var _len = arguments.length, args = new Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
            args[_key - 1] = arguments[_key];
        }

        var result;
        this.each(function (i, element) {
            var $element = $(element);
            var isDestroy = option === 'destroy';
            var distpicker = $element.data(NAMESPACE);

            if (!distpicker) {
                if (isDestroy) {
                    return;
                }

                var options = $.extend({}, $element.data(), $.isPlainObject(option) && option);
                distpicker = new Distpicker(element, options);
                $element.data(NAMESPACE, distpicker);
            }

            if (typeof option === 'string') {
                var fn = distpicker[option];

                if ($.isFunction(fn)) {
                    result = fn.apply(distpicker, args);

                    if (isDestroy) {
                        $element.removeData(NAMESPACE);
                    }
                }
            }
        });
        return typeof result === 'undefined' ? this : result;
    };

    $.fn.distpicker.Constructor = Distpicker;
    $.fn.distpicker.setDefaults = Distpicker.setDefaults;

    $.fn.distpicker.noConflict = function noConflict() {
        $.fn.distpicker = AnotherDistpicker;
        return this;
    };
}

if (WINDOW.document) {
    $(function () {
        $("[data-toggle=\"".concat(NAMESPACE, "\"]")).distpicker();
    });
}
})));