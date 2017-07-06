GeneralAddToLinks =
{
    config:{}
    , submodule:'add_to_links'
    , init:function()
    {
        var self = this;
        AjaxKitMain.addSubmodules('GeneralAddToLinks', GeneralAddToLinks);
        //self.updateCompareList();
        //self.updateWishlistList();
    }

    , reInit:function()
    {
        if (parseInt(GeneralAddToLinks.config.enabled_add_to_compare))
        {
            GeneralAddToLinks.initAddToCompareButtons();
        }
        if (parseInt(GeneralAddToLinks.config.enabled_add_to_wishlist))
        {
            GeneralAddToLinks.initAddToWishlistButtons();
        }
    }

    , initAddToWishlistButtons: function()
    {
        var self = this;
        $$('.link-wishlist').each(function(el)
        {
            var func = function(el)
            {
                GeneralAddToLinks.addToWishlistProcessor(el);
                return false;
            }
            AjaxKitMain.setSingltonClick(el, func);
            el.setAttribute('onclick', 'return false;');
        });
    }
    , initAddToCompareButtons: function()
    {
        var self = this;
        $$('.link-compare').each(function(el)
        {
            var func = function(el)
            {
                GeneralAddToLinks.addToCompareProcessor(el);
                return false;
            }
            AjaxKitMain.setSingltonClick(el, func);
            el.setAttribute('onclick', 'return false;');
        });
    }
    , addToCompareProcessor: function(el)
    {
        var self = this;
        AjaxKitMain.addLoader(el);
        var success_func = function(json)
        {
            if ('REDIRECT' == json.status)
            {
                window.location.href = json.redirect_to;
            }
            if (json.popup_html)
            {
                AjaxKitMain.addHtmlPopup(json.popup_html);
            }
            self.updateCompareList();
            AjaxKitMain.removeLoader(el);
        }
        var parameters = {url:el.href}
        AjaxKitMain.ajaxProcessor(this, 'add_to_compare', parameters, success_func)
    }

    , updateCompareList: function()
    {
        var self = this;
        var success_func = function(json)
        {
            var sFunc = function(json)
            {
                self.updateCompareList();
                if (json.popup_html)
                {
                    AjaxKitMain.addHtmlPopup(json.popup_html);
                }
            }
            AjaxKitMain.resetSidebarBlocks('block-compare', json.compare_sidebar, sFunc, self);

            self.updateCompareListClearAll();
        }

        var parameters = {}
        AjaxKitMain.ajaxProcessor(this, 'update_compare_list', parameters, success_func)
    }

    , updateCompareListClearAll: function()
    {
        var self = this;

        $$('a[href*=/catalog/product_compare/clear/]').each(function(el)
        {
            Event.observe(el, 'click', function (eve)
            {
                var data_onclick = ev(el.getAttribute('data-onclick'));
                if (typeof data_onclick == 'boolean' && data_onclick)
                {
                    var success_func = function(json)
                    {
                        self.updateCompareList();
                        AjaxKitMain.removeLoader(el);
                    }
                    AjaxKitMain.ajaxProcessor(self, 'sidebar_product_compare_clear_all', {}, success_func);
                }
                return false;
            });
            el.setAttribute('data-onclick', el.getAttribute('onclick').replace('return', ''));
            el.setAttribute('onclick', 'return false;');
        });
    }


    , addToWishlistProcessor: function(el, is_reload)
    {
        var self = this;
        AjaxKitMain.addLoader(el);

        var success_func = function(json)
        {
            var is_show_popup = true;
            if ('REDIRECT' == json.status)
            {
                if (!is_reload)
                {
                    if ("undefined" == typeof GeneralLogin)
                    {
                        window.location.href = json.redirect_to;
                    }
                    else
                    {
                        GeneralLogin.loginClickAction(el, true);
                        is_show_popup = false;
                    }
                }
            }
            if ('RELOAD' == json.status)
            {
                window.location.reload();
            }
            if (json.popup_html && is_show_popup)
            {
                AjaxKitMain.addHtmlPopup(json.popup_html);
            }
            AjaxKitMain.removeLoader(el);
            self.updateWishlistList();
        }

        var attributes = {};
        if($$('form#product_addtocart_form, #AddToCart-popup #ajaxkit-popup-content').length >0)
        {
            $$('form#product_addtocart_form, #AddToCart-popup #ajaxkit-popup-content')[0].select('select, input, textarea')._each(function(attribute)
            {
                var field = attribute.name;
                attributes[field] = attribute.value;
            });
        }
        var parameters = {url:el.href, attributes:attributes};
        AjaxKitMain.ajaxProcessor(this, 'add_to_wishlist', parameters, success_func);
    }

    , updateWishlistList: function()
    {
        var self = this;
        var success_func = function(json)
        {
            var sFunc = function(sfjson)
            {
                self.updateWishlistList();
                if (sfjson.popup_html)
                {
                    AjaxKitMain.addHtmlPopup(sfjson.popup_html);
                }
            }
            AjaxKitMain.resetSidebarBlocks('block-wishlist', json.wishlist_sidebar, sFunc, self);
            if (json.wishlist_header)
            {
                var header_top_link = $$(self.config.header_selector + ' a[href*=/wishlist/]');
                if (header_top_link.length)
                {
                    header_top_link[0].up('li').replace(json.wishlist_header);
                }
            }
        }
        var parameters = {}
        AjaxKitMain.ajaxProcessor(this, 'update_wishlist_list', parameters, success_func)
    }
}


AjaxKitMain.addSubmodule("general_add_to_links", "GeneralAddToLinks.init()", GeneralAddToLinks);



