AjaxKitMain =
{
    loadedHtml:{},
    submodules:{},
    submoduleButtons:{},
    loadJsText:[],
    totalLoadedImages:0,
    jsLoadedQuantity:{},
    jsLoadedFunctionality:{},
    mainObserve:function(){},
    mainDocumentObserve:function(){},
    loadJsObjects:[],
    loadJsObjectsNum:0,


    addSubmodule: function(name, initf, configf)
    {
        this.submodules[name] = {init:initf, config:configf};
    },

    initSubmodules: function()
    {
        if (typeof document.AjaxKitSingleton == "undefined")
        {
            var self = this;
            document.observe("dom:loaded", function()
            {
                if (typeof AjaxKitConfig != "undefined")
                {
                    for (submodule in self.submodules)
                    {
                        self.submodules[submodule].config.config = AjaxKitConfig[submodule];
                        setTimeout(self.submodules[submodule].init, 10);
                    }
                }
            });

            document.onkeydown = function(evt)
            {
                evt = evt || window.event;
                if (evt.keyCode == 27)
                {
                    AjaxKitMain.closePopup();
                }
            };
            document.AjaxKitSingleton = true;
        }
    },

    observeReplase:function(to_surrogate)
    {
        if (to_surrogate)
        {
            AjaxKitMain.mainObserve = Event.observe;
            AjaxKitMain.mainDocumentObserve = document.observe;
            Event.observe = AjaxKitMain.surrogateObserve;
            document.observe = AjaxKitMain.surrogateDocumentObserve;
        }
        else
        {
            Event.observe = AjaxKitMain.mainObserve;
            document.observe = AjaxKitMain.mainDocumentObserve;
        }
    }
    , surrogateObserve: function(el, eventName, handler)
    {
        if ('load' == eventName)
        {
            var reg = /function\s{0,}\((.*?){0,}\)/gi;
            AjaxKitMain.loadJsText.push({el:el, eventName:eventName, handler:handler});
            AjaxKitMain.runLoadJs();
        }
        else
        {
            AjaxKitMain.mainObserve(el, eventName, handler);
        }
    }
    , surrogateDocumentObserve: function(eventName, el)
    {
        if ('dom:loaded' == eventName)
        {
            el();
        }
        else
        {
            AjaxKitMain.mainDocumentObserve(eventName, el);
        }
    }
    , ajaxProcessor:function(processor, action, values, success_func, asynchronous, url)
    {
        if ("undefined" == typeof asynchronous)
        {
            asynchronous = true;
        }

        if ("undefined" == typeof success_func)
        {
            success_func = function(){};
        }
        values.parent = AjaxKitConfig.main.parent;
        values.parent.url = location.href;
        var parameters = {
                            action:action
                            , isAjax:1
                            , submodule:processor.submodule
                            , values:JSON.stringify(values)
                        };

        if ("undefined" == typeof url)
        {
            url = AjaxKitConfig.main.url;
        }
        else
        {
            parameters.useObserver = 1;
        }

            new Ajax.Request(url,
            {
                method: 'Post',
                asynchronous: asynchronous,
                parameters: parameters,
                onSuccess: function(transport)
                {
                    try
                    {
                        var obj = transport.responseText.evalJSON(true);
                        success_func(obj);
                    }
                    catch(e)
                    {}
                }
            });
        }
        , appendHtmlJsChilds: function(destination_element, element, is_innerHTML)
        {
        var self = this;

        if (is_innerHTML)
        {
            destination_element.innerHTML = element;
	        var destination_js_element = destination_element;
        }
        else
        {
            destination_element.appendChild(element);
	        var destination_js_element = element;
        }

        AjaxKitMain.totalLoadedImages++;
        AjaxKitMain.loadJsText = [];
        destination_element.select('img').each(function(img)
        {
            AjaxKitMain.totalLoadedImages++;
            if (img.complete)
            {
                self.loadedImage();
            }
            else
            {
                img.onload=function(){ self.loadedImage();  }
                img.onerror=function(){ self.loadedImage(); }
            }
        });
        self.loadedImage();

        this.observeReplase(true);
        this.runJs(destination_js_element);
        this.observeReplase(false);
        destination_element.select('a[href*=/uenc/]')._each(function(el)
        {
            var reg = /\/uenc\/(.*?)\//gi;
            el.href = el.href.replace(reg, '/uenc/'+AjaxKitConfig.main.uenc+'/');
        });
    }

    , runJs: function(conteiner)
    {
        conteiner.select('script')._each(function(el)
        {
            if (!el.getAttribute('AjaxKit-Singlton'))
            {
                var text = el.text;
                var reg = /\/\/\]\]>/gi;
                text = text.replace(reg, ' ');
                var reg = /\/\/<\!\[CDATA\[/gi;
                text = text.replace(reg, ' ');
                ev(text);
                el.setAttribute('AjaxKit-Singlton', 1);
            }
        });
    }
    , loadedImage: function()
    {
        var self = this;
        AjaxKitMain.totalLoadedImages--;
        self.runLoadJs();
    }
    , runLoadJs: function()
    {
        var self = this;
        if (0 == AjaxKitMain.totalLoadedImages)
        {
            self.loadJsText.each(function(js)
            {
                try
                {
                    js.handler.call(js.el, js.eventName, js.el);
                }
                catch(e) {};
            })
        }
    }
    , loadedJsCssTmpName: function(el)
    {
        if ('script' == el.name)
        {
            return el.name + '--' + el.attributes.src;
        }
        if ('link' == el.name)
        {
            return el.name + '--' + el.attributes.href;
        }
        return '';
    }
    , loadJsCss: function(json, load_operation_name, onload_obj, onload_method_name)
    {
        var self = this;

        self.jsLoadedQuantity[load_operation_name] = 1;
        self.jsLoadedFunctionality[load_operation_name] = {obj:onload_obj, method_name:onload_method_name};

        if (!self.loadedHtml[load_operation_name])
        {
            $$('head')[0].insertAdjacentHTML('beforeend', json.head_html);
            self.loadedHtml[load_operation_name] = true;
        }

        var all_loaded_base_js_css = {};
        AjaxKitConfig.main.js_css.head_js_css.each(function(loaded_base_js_css)
        {
            all_loaded_base_js_css[self.loadedJsCssTmpName(loaded_base_js_css)] = true;
        });

        self.loadJsObjects = [];
        self.loadJsObjectsNum = 0;
        json.head_js_css.each(function(js_css)
        {
	    var script =document.createElement(js_css.name);
            var script_select = js_css.name;

            for (attr in js_css.attributes)
            {
                script.setAttribute(attr, js_css.attributes[attr]);
                script_select +='['+attr+'='+js_css.attributes[attr]+']';
            }

            var tmp_name = self.loadedJsCssTmpName(js_css);

            if ($$(script_select).length == 0 && 'undefined' == typeof all_loaded_base_js_css[tmp_name])
            {
                //$$('head')[0].appendChild(script);
                if ('script' == js_css.name)
                {
                    script.setAttribute("onload", 'AjaxKitMain.jsLoaded(\''+load_operation_name+'\', this)');
                    script.setAttribute("onerror", 'AjaxKitMain.jsLoaded(\''+load_operation_name+'\', this)');
                    //script.setAttribute("oncomplete", 'AjaxKitMain.jsLoaded(\''+load_operation_name+'\', this)');
                    self.jsLoadedQuantity[load_operation_name]++;
		    self.loadJsObjects.push(script);
                }
                else
		{
		  $$('head')[0].appendChild(script);
		}
            }
        });
        AjaxKitMain.jsLoaded(load_operation_name);
    }

    
    , loadJs: function()
    {
        var self = this;
        if ('undefined' != typeof self.loadJsObjects[self.loadJsObjectsNum])
        {
            $$('head')[0].appendChild(self.loadJsObjects[self.loadJsObjectsNum]);
            self.loadJsObjectsNum++;
        }
    }
    
    

    , jsLoaded: function(operation, js)
    {
        var self = this;
        self.loadJs();

        if (!self.jsLoadedQuantity[operation])
        {
            return false;
        }
        self.jsLoadedQuantity[operation]--;

        if (0 >= self.jsLoadedQuantity[operation])
        {
            var obj = self.jsLoadedFunctionality[operation].obj;
            var method_name = self.jsLoadedFunctionality[operation].method_name;
            func = obj[method_name];
            func(obj);
        }
    }
    , addHtmlPopup: function(popup_html, reinit, popup_destination)
    {
        if (!popup_html)
            return;

        if (!popup_destination)
        {
            popup_destination = $$('body')[0];
        }
        if (undefined == reinit)
        {
            var reinit = true;
        }

        var self = this;
        self.closePopup();

        var div =document.createElement("div");
        div.id = "AddToCart-popup";
        div.innerHTML = popup_html;

        var click_actions =
        {
            'close-popup-overlay':{click:function(el)
            {
                self.closePopup();
            }}

            , 'close-popup':{click:function(el)
            {
                self.closePopup();
            }}
            , 'rewrite-to-url': {click:function(el)
            {
                window.location.href = el.getAttribute('data-url');
            }}
            , 'close-popup-timer': {onload:function(el)
            {
                var sec = parseInt(el.innerHTML)-1;

                if (sec <= 0)
                {
                    return;
                }
                var timer_func = function(sec)
                {
                    sec--
                    if(sec >= 0)
                    {
                        el.innerHTML = sec;
                        if ($('AddToCart-popup'))
                        {
                            setTimeout(timer_func, 1000, sec);
                        }
                    }
                    else
                    {
                        self.closePopup();
                    }
                }
                timer_func(sec);
            }}
        }
        self.appendHtmlJsChilds(popup_destination, div);

        for (class_name in click_actions)
        {
            div.select('.'+class_name).each(function(el)
            {
                if (click_actions[class_name].click)
                {
                    el.setAttribute('data-action', class_name);
                    Event.observe(el, 'click', function(eve)
                    {
                        var cn = el.getAttribute('data-action');
                        var func = click_actions[cn].click;
                        func(el);
                    });
                }
                if (click_actions[class_name].onload)
                {
                    var func = click_actions[class_name].onload;
                    func(el);


                }
            });
        }
        if (reinit)
        {
            AjaxKitMain.reinitSubmodules();
        }

        return div;
    }
    , closePopup: function()
    {
        $$('#AddToCart-popup').invoke('remove');
    }

    , resetSidebarBlocks: function(class_name, content, remove_ajax_func, _self)
    {
        var self = this;

        if (false !== content)
        {
            if ($$('.block.'+class_name).length > 0)
            {
                var is_destination_finded = true;
                var tmp_block_div = document.createElement("div");
                tmp_block_div.innerHTML = content;
                var block_cart_html = tmp_block_div.select('.block.' + class_name).length ? tmp_block_div.select('.block.' + class_name)[0].innerHTML : false;

                $$('.block.' + class_name).each(function (el)
                {
                    if (block_cart_html)
                    {
                        el.innerHTML = block_cart_html;
                        self.runJs(el);
                    }
                    else
                    {
                        el.remove();
                    }
                });
            }
            else
            {
                var is_destination_finded = false;
                ['block-cart', 'block-compare', 'ajaxkit-block'].each(function (older_child) {
                    if (!is_destination_finded && $$('.block.' + older_child).length)
                    {
                        $$('.block.' + older_child).each(function (el) {
                            el.insert({'after': content});
                            self.runJs(el);
                        });
                        is_destination_finded = true;
                    }
                });
            }
        }

        $$('.block.'+class_name+' .btn-remove')._each(function(el)
        {
            func = function()
            {
                var data_onclick = ev(el.getAttribute('data-onclick'));
                if (typeof data_onclick == 'boolean' && data_onclick)
                {
                    parameters = {product:el.href}
                    AjaxKitMain.ajaxProcessor(_self, 'sidebar_remove_btn', parameters, remove_ajax_func);
                }
                return false;
            }

            self.setSingltonClick(el, func)
            if (!el.getAttribute('data-onclick'))
            {
                el.setAttribute('data-onclick', el.getAttribute('onclick').replace('return', ''));
            }
            el.setAttribute('onclick', 'return false;');
        });
        return is_destination_finded;
    }
    , addSubmodules: function(key, obj)
    {
        AjaxKitMain.submoduleButtons[key] = obj;
        obj.reInit();
    }
    , reinitSubmodules: function()
    {
        for (key in AjaxKitMain.submoduleButtons)
        {
            var obj = AjaxKitMain.submoduleButtons[key];
            setTimeout(obj.reInit(), 1);
        }
    }
    , setSingltonClick: function(el, func)
    {
        if (!el.hasClassName('AjaxKit-Singlton-Click'))
        {
            el.addClassName('AjaxKit-Singlton-Click')
            Event.observe(el, 'click', function(eve)
            {
                func(el);
            });
        }
    }
    , setSingltonChange: function(el, func)
    {
        if (!el.hasClassName('AjaxKit-Singlton-Change'))
        {
            el.addClassName('AjaxKit-Singlton-Change')
            Event.observe(el, 'change', function(eve)
            {
                func(el);
            });
        }
    }

    , addLoader: function(el)
    {
        if ($$('.AddToCart-loader').length == 0)
        {
            var div =document.createElement("div");
            div.addClassName("AddToCart-loader");
            var spans = el.select('span');

            if (spans.length > 0)
            {
                //spans[spans.length - 1].appendChild(div);
                spans[0].appendChild(div);
            }
            else
            {
                el.appendChild(div);
            }
        }
    }

    , removeLoader: function(el)
    {
        el.select('.AddToCart-loader').invoke('remove');
    }

}
var ev = eval;
