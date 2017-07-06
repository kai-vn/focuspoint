var AjaxKit =
{
    depends:{},
    init:function()
    {
        this.addTabProcessor('#left-tabs-container');
        this.storeSwitchChecker();
        this.getActiveTab();
    },
/* processing buttons */
    resetDefaults:function()
    {
        if (confirm('are you sure?'))
        {
            var self = this;
            var namespace = $$('.ajaxkit-tab.active')[0].getAttribute('data-ajax-namespace');
            var ajax_tab_ajax_info_container_id = 'ajax-tab-'+namespace+'-container';

            success_func = function(transport)
            {
                try
                {
                    $(ajax_tab_ajax_info_container_id).remove();
                    self.getActiveTab();
                    self.saveTabs($$('.ajaxkit-tab.changes-tab'), 'Data was successfully reset');
                }
                catch(e)
                {}
            }
            this.ajaxProcessor({action:'resetDefaults', namespace:namespace, store:$('store_switcher').value}, success_func)
        }
    },
    saveTab:function()
    {
        this.saveTabs($$('.ajaxkit-tab.active'), 'The values of current tab were successfully saved. Please flush all the cache (System -> Cache management).');
    },
    saveAllTabs:function()
    {
        this.saveTabs($$('.ajaxkit-tab.changes-tab'), 'The values of all tabs were successfully saved');
    },

    saveTabs:function(tabs, msg)
    {
        var self = this;
        var send_data = {};

        tabs._each(function(el)
        {
            var namespace = el.getAttribute('data-ajax-namespace');
            var ajax_tab_ajax_info_container_id = 'ajax-tab-'+namespace+'-container';
            send_data[namespace] = {};

            $(ajax_tab_ajax_info_container_id).select('input, select')._each(function(value_el)
            {
                if (!value_el.hasClassName('no-save'))
                {
                    var name = value_el.name;

                    if (value_el.hasClassName('multiple'))
                    {
                        var value = [];
                        value_el.select("option:selected")._each(function(selected_el)
                        {
                            value.push(selected_el.value);
                        })
                    }
                    else
                    {
                        var value = value_el.value;
                    }

                    if (undefined != name && undefined != value)
                    {
                        var type = value_el.hasClassName('use-default-store-checkbox') ? 'use_default' : 'value';

                        if (undefined == send_data[namespace][type])
                        {
                            send_data[namespace][type] = {};
                        }

                        send_data[namespace][type][name] = value
                    }
                }
            })
        });

        var post = {action:'saveTabs', store:$('store_switcher').value, tabs:JSON.stringify(send_data)};
        success_func = function(transport)
        {
            self.clearTabContainer();
            self.showMessage(msg, 'success')
        }
        self.ajaxProcessor(post, success_func);
    },

/* processing buttons */
    clearTabContainer:function()
    {
        $('meigee-ajaxkit-content').innerHTML = '';
        $$('.ajaxkit-tab.changes-tab').invoke('removeClassName', 'changes-tab');
        this.getActiveTab();
    },

    storeSwitchChecker:function()
    {
        var self = this;
        $('store_switcher').setAttribute('data-old-value', $('store_switcher').value);
        Event.observe($('store_switcher'), 'change', function(eve)
        {
            if (confirm( "Please confirm site switching. All data that hasn't been saved will be lost."))
            {
                $('store_switcher').setAttribute('data-old-value', $('store_switcher').value);
                self.clearTabContainer();
            }
            else
            {
                $('store_switcher').value = $('store_switcher').getAttribute('data-old-value');
            }
        });
    },

    getActiveTab:function()
    {
        this.tabProcessor($$('.ajaxkit-tab.active')[0]);
    },

    addTabProcessor:function(container)
    {
        var self = this;
        var elements = $$(container + ' .ajaxkit-tab');
        for (i=0; i<elements.length; i++)
        {
            Event.observe(elements[i], 'click', function(eve)
            {
                self.tabProcessor(this)
            });
        }
    },
    tabProcessor:function(el)
    {
        var self = this;
        var namespace = el.getAttribute('data-ajax-namespace');
        var ajax_tab_ajax_info_container_id = 'ajax-tab-'+namespace+'-container';

        if (el.hasClassName('ajax'))
        {
            if (!$$('#meigee-ajaxkit-content #'+ajax_tab_ajax_info_container_id).length)
            {
                success_func = function(transport)
                {
                    try
                    {
                        var json = transport.responseText.evalJSON(true);
                        var div = document.createElement('div');
                        div.innerHTML = json.html;
                        div.id = ajax_tab_ajax_info_container_id;
                        div.addClassName('meigee-ajaxkit-tab-content');

                        if ('0' == json.store_id+'')
                        {
                            $('meigee-ajaxkit-reset-defaults').removeClassName('display-none');
                        }
                        else
                        {
                            $('meigee-ajaxkit-reset-defaults').addClassName('display-none');
                        }

                        for (depend in json.depends)
                        {
                            self.depends[depend] = {};
                            for (depend_el in json.depends[depend])
                            {
                                self.depends[depend][depend_el] = json.depends[depend][depend_el];
                            }
                        }
                        self.newHorizontalTabProcessor(div);
                        $('meigee-ajaxkit-content').appendChild(div);
                        self.showDependElements();
                    }
                    catch(e)
                    {}
                }
                this.ajaxProcessor({action:'getTabHtml', namespace:namespace, store:$('store_switcher').value}, success_func)
            }
        }

        $$('.ajaxkit-tab ').invoke('removeClassName', 'active');
        el.addClassName('active');

        $$('#meigee-ajaxkit-content .meigee-ajaxkit-tab-content').invoke('removeClassName', 'active');
        $$('#meigee-ajaxkit-content #'+ajax_tab_ajax_info_container_id)[0].addClassName('active');
    },


    newHorizontalTabProcessor:function(tab_el)
    {
        tab_el.select('.tab')._each(function(horisontal_tab)
        {
            Event.observe(horisontal_tab, 'click', function(eve)
            {
                var horizontal_tabs_wrapper = horisontal_tab.up('.horizontal-tabs-wrapper');
                horizontal_tabs_wrapper.select('.tab.active, .tab-content.active').invoke('removeClassName', 'active');

                horisontal_tab.addClassName('active');
                var tab_name = horisontal_tab.getAttribute('data-tab-name');
                horizontal_tabs_wrapper.select('.tab-content[data-tab-name='+tab_name+']').invoke('addClassName', 'active');
            });
        });
        this.setElementTrigger(tab_el);
        this.initElementQuickView(tab_el);
    },

    setElementTrigger: function(parentElement)
    {
        var self = this;
        parentElement.select('input, select')._each(function(el)
        {
            Event.observe(el, 'change', function(eve)
            {
                self.elementTrigger(el);
            });

            if (el.hasClassName('on-off-selector'))
            {
                value =  (el.checked ? 1 : 0);
                el.value = value;
            }
            self.disableTab(el);
        })
    },
    elementTrigger: function(element)
    {
        if (element.hasClassName('on-off-selector'))
        {
            value =  (element.checked ? 1 : 0);
            element.up('.input_checkbox').select('._input_checkbox_value')[0].value = (element.checked ? 1 : 0);
            element.value = value;
        }

        if (element.type == 'checkbox')
        {
            element.value =  (element.checked ? 1 : 0);
        }

        var default_store_checkbox = element.up('.option-wrapper').select('.use-default-store-checkbox');
        if (default_store_checkbox.length && !element.hasClassName('use-default-store-checkbox'))
        {
            default_store_checkbox[0].checked = false;
            default_store_checkbox[0].value = 0;
        }

        if (element.type == 'radio')
        {
            element.up('.option-wrapper').select('.meigee-thumb').invoke('removeClassName', 'active');
            element.up('.meigee-radio').select('.meigee-thumb').invoke('addClassName', 'active');
        }
        this.showDependElements();
        this.showElementQuickViewValues(element);
        this.disableTab(element);
        $$('.ajaxkit-tab.active').invoke('addClassName', 'changes-tab');
    },

    disableTab: function(element)
    {
        if (element.getAttribute('data-status') == '1')
        {
            if (element.value > 0)
            {
                element.up('.meigee-ajaxkit-tab-content').select('.horizontal-tabs-wrapper').invoke('removeClassName', 'display-none');
            }
            else
            {
                element.up('.meigee-ajaxkit-tab-content').select('.horizontal-tabs-wrapper').invoke('addClassName', 'display-none');
            }
        }
    },

    initElementQuickView: function(tab)
    {
        var self = this;
        tab.select('input, select')._each(function(el)
        {
            self.showElementQuickViewValues(el);
        });
    },

    showDependElements: function()
    {
        for (depend_el in this.depends)
        {
            if ($('AjaxKit-'+depend_el+'-element'))
            {
                closed_el = true;
                var depend_el_count = 0;
                var depend_el_agreement_count = 0;

                for (depend_var_el in this.depends[depend_el])
                {
                    depend_el_count++;
                    if ($('AjaxKit-'+depend_var_el))
                    {
                        var depend_var_el_value = $('AjaxKit-'+depend_var_el).value;
                        var agreement = this.depends[depend_el][depend_var_el].findAll(function(val)
                        {
                            return val == depend_var_el_value;
                        });
                        if (agreement.length > 0)
                        {
                            depend_el_agreement_count++;
                        }
                    }
                }

                if (depend_el_count == depend_el_agreement_count)
                {
                    $('AjaxKit-'+depend_el+'-element').up('.option-wrapper').removeClassName('display-none');
                }
                else
                {
                    $('AjaxKit-'+depend_el+'-element').up('.option-wrapper').addClassName('display-none');
                }
            }
        }
    },

    showElementQuickViewValues: function(element)
    {
        var self = this;
        var name = element.name;
        var value = element.value;

        element.hasClassName('')

        element.up('.meigee-ajaxkit-tab-content').select('.meigee-ajaxkit-preview')._each(function(preview_el)
        {
            preview_el.select('.option-preview[data-option="'+name+'"]').invoke('addClassName', 'display-none');
            preview_el.select('.option-preview[data-option="'+name+'"][data-value="'+value+'"]').invoke('removeClassName', 'display-none');
            preview_el.select('.option-preview-value[data-option="'+name+'"]')._each(function(el)
            {
                el.innerHTML = value;
            });

            if (preview_el.select('.meigee-ajaxkit-preview-interactive').length == 0)
            {
                var div = document.createElement('div');
                div.addClassName('meigee-ajaxkit-preview-interactive');
                preview_el.appendChild(div);
            }

            var interactive_elements = preview_el.select('.meigee-ajaxkit-preview-interactive')[0];

            preview_el.select('.option-preview-interactive[data-option="'+name+'"]')._each(function(el)
            {
                if (interactive_elements.select('.option-preview-interactive-clone[data-option="'+name+'"]').length == 0)
                {
                    el.setAttribute('onclick', 'AjaxKit.clickedInteractiveElement(this)');

                    var cloned_el = el.clone(true);
                    cloned_el.removeClassName('option-preview-interactive');
                    cloned_el.addClassName('option-preview-interactive-clone');
                    interactive_elements.appendChild(cloned_el);
                }

                var clone = preview_el.select('.option-preview-interactive-clone[data-option="'+name+'"]')[0];
                if (value > 0)
                {
                    clone.addClassName('display-none');
                    el.removeClassName('display-none');
                }
                else
                {
                    clone.removeClassName('display-none');
                    el.addClassName('display-none');
                }
            });
        });
    },

    clickedInteractiveElement: function(el)
    {
        var option = el.getAttribute('data-option');
        var option_el = $('AjaxKit-'+option);
        if (option_el && option_el.hasClassName('on-off-selector'))
        {
            option_el.checked = !option_el.checked;
            option_el.value = option_el.checked ? 1 : 0;
        }
        this.elementTrigger(option_el);
    },

    ajaxProcessor:function(parameters, success_func)
    {
        new Ajax.Request($('ajax-url').value,
            {
                method: 'Post',
                asynchronous: false,
                parameters: parameters,
                onSuccess: function(transport)
                {
                    success_func(transport);
                }
            });
    }

    , showMessage: function(txt, type)
    {
        var html = '<ul class="messages"><li class="'+type+'-msg"><ul><li>' + txt + '</li></ul></li></ul>';
        $('messages').update(html);
    }
}

document.observe("dom:loaded", function()
{
    AjaxKit.init();
});
