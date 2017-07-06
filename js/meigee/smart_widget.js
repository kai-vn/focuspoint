
SmartWidgetSingleton = false;

SmartWidget =
{
    option_elements:{}
    , dataHidedOn:{}
    , getWidgetId: function(theme)
    {
        return theme + '-widget-content';
    }

    ,getParam: function(item)
    {
        return item.getAttribute('data-param');
    }
    , init: function()
    {
        var self = this;
        self.dataHidedOn = {};
        if (!SmartWidgetSingleton)
        {
            $$('select[id$=_meigee_theme]').each(function(el)
            {
                if('parameters[meigee_theme]' == el.name)
                {
                    el.select('option').each(function(option_el)
                    {
                        var theme = option_el.value
                        var widget_id = self.getWidgetId(theme);
                        if ($$('#'+widget_id).length > 0 && !option_el.getAttribute('data-processing'))
                        {
                            self.initElement(theme);
                            option_el.setAttribute('data-processing', 1);
                        }
                    });

                    if (2 > el.select('option').length)
                    {
                        el.up('tr').hide();
                    }
                    else
                    {
                        Event.observe(el, 'change', function(eve)
                        {
                            var widget_id = self.getWidgetId(this.value);
                            self.showSmartWidget(widget_id);
                        });
                    }
                    var widget_id = self.getWidgetId(el.value);
                    self.showSmartWidget(widget_id);
                }
            });
        }
        SmartWidgetSingleton = true;
    }

    ,showSmartWidget: function(widget_id)
    {
        $$('.theme-widget-content').each(function(el)
        {
            el.up('tr').hide();
        });
        $$('#'+widget_id)[0].up('tr').show();
    }

    , initElement: function(theme)
    {
        var self = this;
        self.option_elements[theme] = {};
        this.processingParameters(theme);

        this.hideOptions();
        this.processingTitle2();

        $('widget_options').select('select').each(function(option)
        {
            Event.observe(option, 'change', function(eve)
            {
                self.hideOptions();
            });
        });
    }
    , processingParameters: function(theme)
    {
        var self = this;
        widget_id = self.getWidgetId(theme);
        var selectors = $('widget_options').select('select[name^=parameters]');

        $(widget_id).select('.item').each(function(item)
        {
            var param = self.getParam(item);

            if (param)
            {
                var item_holder = $(widget_id).select('.items-container .'+param+'-holder');
                self.option_elements[theme][param] = {item:item, item_holder:false, 'option':[]};

                item.setAttribute('data-theme', theme);

                if (typeof item_holder[0] != 'undefined')
                {
                    var element_clone = Element.clone(item, true);
                    item_holder[0].appendChild(element_clone).hide();
                    self.option_elements[theme][param].item_holder = element_clone;
                }
                selectors.each(function(option)
                {
                    if (option.name.indexOf(param) > 0)
                    {
                        option.setAttribute('data-param', param);
                        self.option_elements[theme][param].option.push(option);
                    }
                });


                if (typeof self.option_elements[theme][param].clicked == 'undefined')
                {
                    Event.observe(item, 'click', function(eve)
                    {
                        self.processingItem(item);
                    });

                    Event.observe(self.option_elements[theme][param].item_holder, 'click', function(eve)
                    {
                        self.processingItem(item);
                    });

                    self.option_elements[theme][param].clicked = true;
                }
            }

            if(item.getAttribute('data-hided-on'))
            {
                item.getAttribute('data-hided-on').split(' ').each(function(item_hided_on_str)
                {
                    if (item_hided_on_str)
                    {
                        var item_hided_on_arr = item_hided_on_str.split(':');
                        if (item_hided_on_arr.length == 2)
                        {
                            if ('undefined' == typeof self.dataHidedOn[item_hided_on_arr[0]])
                            {
                                self.dataHidedOn[item_hided_on_arr[0]] = {};
                            }
                            if ('undefined' == typeof self.dataHidedOn[item_hided_on_arr[0]][item_hided_on_arr[1]])
                            {
                                self.dataHidedOn[item_hided_on_arr[0]][item_hided_on_arr[1]] = [];
                            }

                            self.dataHidedOn[item_hided_on_arr[0]][item_hided_on_arr[1]].push(item);
                        }
                    }
                });
            }

        });

        self.prepareDataHidedOn();
    }
    , hideOptions: function()
    {
        var self = this;
        for (theme in self.option_elements)
        {
            for (item in self.option_elements[theme])
            {
                self.option_elements[theme][item].option.each(function(option)
                {
                    setTimeout(function()
                    {
                        option.up('tr').hide();
                    }, 10);
                })
            }
        }
    }
    ,prepareDataHidedOn: function()
    {
        var self = this;
        var fieldset = $('widget_options_thememanager_widget_products').select('.fieldset.fieldset-wide')
        fieldset_id = fieldset[0].id;

        for (selector_name in self.dataHidedOn)
        {
            var field_el = $(fieldset_id + '_' + selector_name);

            self.processingDataHidedOn(fieldset_id);
            Event.observe(field_el, 'change', function(eve)
            {
                self.processingDataHidedOn(fieldset_id);
            });
        }
    }

    ,processingDataHidedOn: function(fieldset_id)
    {
        var self = this;
        for (selector_name in self.dataHidedOn)
        {
            var field_el = $(fieldset_id + '_' + selector_name);
            for (value_type in self.dataHidedOn[selector_name])
            {
                self.dataHidedOn[selector_name][value_type].each(function (el) {
                    el.show();
                });
            }

        }
        for (selector_name in self.dataHidedOn)
        {
            var field_el = $(fieldset_id + '_' + selector_name);
            if ('undefined' != typeof self.dataHidedOn[selector_name][field_el.value])
            {
                self.dataHidedOn[selector_name][field_el.value].each(function(el)
                {
                    el.hide();
                });
            }
        }
    }

    ,processingTitle2: function()
    {
        var self = this;
        var is_show = false;

        for (theme in self.option_elements)
        {
            var widget_id = self.getWidgetId(theme);
            var title2 = $(widget_id).select('.title-2')[0];

            for (item in self.option_elements[theme])
            {
                if (!self.option_elements[theme][item].clicked)
                {
                    is_show = true;
                }
            }
            if (is_show)
            {
                title2.show();
            }
            else
            {
                title2.hide();
            }
        }
    }
    ,processingItem: function(item)
    {
        var self = this;
        var theme = item.getAttribute('data-theme');
        var param = self.getParam(item);
        var select_value = 1;

        if (self.option_elements[theme][param].clicked)
        {
            self.option_elements[theme][param].clicked = false;
            self.option_elements[theme][param].item.hide();
            self.option_elements[theme][param].item_holder.show();
            select_value = 0;
            this.processingTitle2();
        }
        else
        {
            self.option_elements[theme][param].clicked = true;
            self.option_elements[theme][param].item.show();
            self.option_elements[theme][param].item_holder.hide();
            select_value = 1;
            this.processingTitle2();
        }

        self.option_elements[theme][param].option.each(function(arr_el)
        {
            $(arr_el.select('option')).each(function(option){
                option.removeAttribute('selected');
            });
            arr_el.select('option[value='+select_value+']')[0].setAttribute('selected', true);
        });

        var separator = false;
        var last_separator = false;
        var hided = true;
        var older_hided = true;

        if (item.up(0).hasClassName('use-separator'))
        {
            item.up(0).select('.item, .separator').each(function(item_sep)
            {
                if (item_sep.hasClassName('separator'))
                {
                    separator = item_sep;
                }
                else
                {
                    hided = 'none' == item_sep.style.display;
                }
                if (separator)
                {
                    separator.show();
                    if (hided || older_hided)
                    {
                        separator.hide();
                    }
                }
                older_hided = hided;
                last_separator = item_sep;
            });

            if (last_separator.hasClassName('separator'))
            {
                last_separator.hide();
            }
        }
    }
}
