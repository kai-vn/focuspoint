GeneralAddToCart =
{
    config:{}
    , thisPage:{}
    , submodule:'add_to_cart'
    , jsLoadedQuantity:0
    , jsLoadedQuantityQuickView:0
    , isJHtmlLoaded:false
    , productOptions:{}
    , loadedQuickViewContent:{}
    , quickViewContentContainer:{}
    , flyDiv:null

    , init:function()
    {
        var self = this;
        if (self.thisPage)
        {
            AjaxKitMain.addSubmodules('GeneralAddToCart', GeneralAddToCart);
            self.updateCartHtml();
        }
    }

    , reInit:function()
    {
        GeneralAddToCart.initAddToCartButtons();
        GeneralAddToCart.initQuickViewButtons();
    }

    , initAddToCartButtons: function()
    {
        var self = this;
        $$(this.config.add_to_cart_btn_selector).each(function(el)
        {
            var onclickValue = false;
            if (!el.hasClassName('AjaxKit-addtocart-link'))
            {
                el.addClassName('AjaxKit-addtocart-link');
                if (('product' == self.thisPage || 'wishlist' == self.thisPage) && undefined !==  el.up('#product_addtocart_form'))
                {
                    var form = el.up('#product_addtocart_form');
                    var onclickValue = form.getAttribute('action');
                }
                else
                {
                    switch(el.tagName)
                    {
                        case 'A':
                            var onclickValue = el.getAttribute('href');
                            el.setAttribute('href', '#');
                            break;
                        case 'INPUT':
                        case 'BUTTON':
                            var onclickValue = el.getAttribute('onclick');
                            break;
                    }
                }
            }
            if (onclickValue)
            {
                var reg = /\(\'(.*)\'\)/gi;
                var onclickValueUrl =reg.exec(onclickValue)
                onclickValue = onclickValueUrl ? onclickValueUrl[1] : onclickValue;
                el.setAttribute('onclick', 'return false;');
                el.setAttribute('data-onclick-value', onclickValue);

                Event.observe(el, 'click', function(eve)
                {
                    form = el.up('#product_addtocart_form');
                    if ('product' == self.thisPage || undefined !==  form)
                    {
                        self.addToCartPopupProcessor(this, form);
                    }
                    else
                    {
                        self.addToCartProcessor(this)
                    }
                });
            }
        });
        switch (self.thisPage)
        {
            case 'wishlist':
                $$('button[onclick^=addAllWItemsToCart], .btn-add')._each(function(el)
                {
                    if (!el.hasClassName('addAllWItemsToCartBtn'))
                    {
                        Event.observe(el, 'click', function (eve)
                        {
                            AjaxKitMain.addLoader(this);
                            var success_func = function(cart_data)
                            {
                                self.updateWishlist();
                                self.updateCartHtml();
                            }
                            var values = {};
                            values.qty = {};
                            $$('textarea, input')._each(function(qty_el)
                            {
                                if (qty_el.name)
                                {
                                    values[qty_el.name] = qty_el.value;
                                }
                            });
                            AjaxKitMain.ajaxProcessor(self, 'add_wishlist_to_сart', values, success_func);
                        });
                        el.setAttribute('onclick', 'return false;');
                        el.addClassName('addAllWItemsToCartBtn');
                    }
                });
                break;
        }
    }
    , addToCartProcessor: function(el, parameters)
    {
        var self = this;
        AjaxKitMain.addLoader(el);
        if (undefined==parameters)
        {
            var parameters ={};
        }
        parameters.product = el.getAttribute('data-onclick-value');
        parameters.pageType = self.thisPage;
        var success_func = function(json)
        {
			if ('SUCCESS' == json.status)
            {
                self.hideQuickView();
                if (json.show_options )
                {
                    self.productOptions.product = el;
                    self.productOptions.popup_html = json.popup_html;
                    AjaxKitMain.loadJsCss(json, 'addToCart', self, 'jsLoaded');		
                }
                if (json.added)
                {
                    AjaxKitMain.closePopup();
                    if (json.popup_html)
                    {
                        AjaxKitMain.addHtmlPopup(json.popup_html);
                    }

                    if (json.update_wishlist)
                    {
                        self.updateWishlist();
                    }
                    else
                    {
                        self.productFlyToCart(el);
                    }

                    var func = function(){   self.highlightCart();   }
                    self.updateCartHtml(func);
                }
            }
            else
            {
                AjaxKitMain.addHtmlPopup(json.popup_html);
            }
            AjaxKitMain.removeLoader(el);
        }
        AjaxKitMain.ajaxProcessor(this, 'add_to_cart', parameters, success_func)
    }
    , addToCartPopupProcessor: function(el, parent_form)
    {
        var self = this;

        if (undefined == parent_form)
        {
            var parent_form = $('AddToCart-popup');
        }
        var form = new VarienForm(parent_form.id);
        if (form.validator.validate())
        {
            var wrappers = parent_form.select('#product-options-wrapper, .product-info-options-wrapper, .product-info-products-groupe, #super-product-table');
            var attributes = {'__kit':1};
            if (wrappers.length > 0)
            {
                wrappers._each(function(wrapper)
                {
                    var values = wrapper.select('select, input, textarea');
                    values._each(function(attribute)
                    {
                        var field = attribute.name;
                        var attribute_type = attribute.getAttribute('type');

                        if ('checkbox' == attribute_type || 'radio' == attribute_type )
                        {
                            if (attribute.checked)
                            {
                                field = field.replace("[]", "");
                                if ('undefined' == typeof attributes[field] )
                                {
                                    attributes[field] = [];
                                }
                                attributes[field].push(attribute.value);
                            }
                        }
                        else
                        {
                            attributes[field] = attribute.value;
                        }
                    });
                });
            }
            var qty = 0;
            if (parent_form.select('#qty').length > 0)
            {
                var qty = parent_form.select('#qty')[0].value;
            }
            var related_products_arr = [];
            parent_form.select('.popup-related-products input[name^=related_products]:checked').each(function(checkbox)
            {
                related_products_arr.push(checkbox.value);
            });

            if ('product' == self.thisPage)
            {
                $$('.block-related input[name^=related_products]:checked').each(function(checkbox)
                {
                    related_products_arr.push(checkbox.value);
                });
            }
            self.addToCartProcessor(el, {attributes:attributes, related_product:related_products_arr, qty:qty});
        }
    }

    , jsLoaded: function()
    {
        var self = GeneralAddToCart;
        self.jsLoadedQuantity--;

        if (self.productOptions.popup_html)
        {
            var popup_elements = [];
            var divBottom = AjaxKitMain.addHtmlPopup(self.productOptions.popup_html, false);
            divBottom.select(self.config.add_to_cart_btn_selector).each(function(el)
            {
                el.addClassName('AjaxKit-addtocart-link');
                el.setAttribute('onclick', 'return false;');
                el.setAttribute('data-onclick-value', self.productOptions.product.getAttribute('data-onclick-value'));

                Event.observe(el, 'click', function(eve)
                {
                    AjaxKitMain.addLoader(this);
                    self.addToCartPopupProcessor(self.productOptions.product);
                });
            });
            AjaxKitMain.reinitSubmodules();
        }
    }
    , updateCartHtml: function(after_update_func)
    {
        var self = this;

        var reg = /checkout\/cart\//gi;
        var is_checkout_cart =reg.exec(window.location.pathname.substr(1));

        if (this.isInit && 'checkout' == self.thisPage && is_checkout_cart)
        {
            location.reload();
            return false;
        }
        var success_func = function(cart_data)
        {
            var tmp_div =document.createElement("div");
            tmp_div.innerHTML = cart_data.top_link_cart_html;
            var header_top_link_cart = $$('.top-link-cart');

            if (null != self.flyDiv)
            {
                self.flyDiv = header_top_link_cart[0].select('.fly-div')[0].clone(true);
            }
            
            for (j=0; j<header_top_link_cart.length; j++)
            {
                header_top_link_cart[j].innerHTML = tmp_div.select('.top-link-cart')[0].innerHTML;
                AjaxKitMain.runJs(header_top_link_cart[j]);
            }
            
            if (null != self.flyDiv)
            {
                header_top_link_cart[0].appendChild(self.flyDiv);
            }
            var sFunc = function(json)
            {
                self.updateCartHtml();
                AjaxKitMain.addHtmlPopup(json.popup_html);
            }
            AjaxKitMain.resetSidebarBlocks('block-cart', cart_data.cart_sidebar, sFunc, self);
            AjaxKitMain.reinitSubmodules()
            truncateOptions();

            if(after_update_func != undefined)
            {
                after_update_func();
            }
        }
        AjaxKitMain.ajaxProcessor(this, 'get_сart_html', {}, success_func);
        return true;
    }

    , highlightCart: function()
    {
        var self = this;
        if ($$(self.config.highlight_cart_selector))
        {
            $$(self.config.highlight_cart_selector).invoke('addClassName', 'highlight-cart');
            var removeHighlightFunc = function()
            {
                $$(self.config.highlight_cart_selector).invoke('removeClassName', 'highlight-cart');
            }
            setTimeout(removeHighlightFunc, 300);
        }
    }

    , productFlyToCart: function(el)
    {
        var self = this;
        var cart = $$(this.config.header_selector + ' .top-link-cart');
        if(!cart.length || !parseInt(this.config.product_image_animation))
        {
            return false;
        }
        cart = cart[0];
        var parent_el = el;
        do
        {
            var parent_el = parent_el.up(1);
            img = parent_el.select('img');
        }
        while(!img)

        if(!img.length)
        {
            return false;
        }

        img = img[0];
        var bodyTop = $$('body')[0].getBoundingClientRect().top;
        var bodyLeft = $$('body')[0].getBoundingClientRect().left;
        var pTop = img.getBoundingClientRect().top - bodyTop;
        var pLeft = img.getBoundingClientRect().left - bodyLeft;
        var cartTop = cart.getBoundingClientRect().top - bodyTop;
        var cartLeft = cart.getBoundingClientRect().left - bodyLeft;
        var x = pLeft - cartLeft;
        var y =  pTop - cartTop;
        var div =document.createElement("div");
        div.style.position = 'absolute';
        div.style.top = y+'px';
        div.style.left = x+'px';
        div.addClassName('fly-div');
        var img_clone = img.clone(true);
        div.appendChild(img_clone);
        var width = img.width;
        var height = img.height;

        div.style.width = width+'px';
        div.style.height = height+'px';
        cart.appendChild(div);
        self.flyDiv = div;

        var flyFunc = function(i)
        {
            if (i>0)
            {
                i--;
                var new_x = i*x/100;
                var new_y = i*y/100;
                self.flyDiv.style.top = new_y+'px';
                self.flyDiv.style.left = new_x+'px';
                self.flyDiv.style.width = 290+'px';
                self.flyDiv.style.height = 430+'px';
                var new_w = i*width/100;
                var new_h = i*height/100;
                self.flyDiv.style.width = new_w+'px';
                self.flyDiv.style.height = new_h+'px';
                img_clone.style.width = new_w+'px';
                img_clone.style.height = new_h+'px';
                setTimeout(flyFunc, 5, i);
            }
            else
            {
                self.flyDiv.remove();
                self.flyDiv = null;
            }
        }
        flyFunc(100);
    }
    , updateWishlist: function()
    {
        var self = this;
        var success_func = function(wishlist_data)
        {
            AjaxKitMain.appendHtmlJsChilds($$('.col-main')[0], wishlist_data.wishlist_html, true);
            self.initAddToCartButtons();
        }
        AjaxKitMain.ajaxProcessor(self, 'get_wishlist_html', {}, success_func);
    }

    , initQuickViewButtons: function()
    {
        var self = this;

        $$('.btn-ajaxkit-quick-view').each(function(el)
        {
            if (!el.hasClassName('AjaxKit-quick-view-link'))
            {
                el.addClassName('AjaxKit-quick-view-link');

                Event.observe(el, 'click', function(eve)
                {
                    if (!el.getAttribute('data-id'))
                    {
                        return;
                    }
                    AjaxKitMain.addLoader(el)
                    var quick_view_container = this.up('.quick-view-container');
                    var quick_view_data_container = quick_view_container.select('.quick-view-data-container');
                    if (quick_view_data_container.length > 0)
                    {
                        quick_view_data_container = quick_view_data_container[0];
                        var prodict_id = el.getAttribute('data-id');

                        var success_func = function(content)
                        {
                            self.quickViewContentContainer.checkout = content.checkout;
                            self.quickViewContentContainer.html = content.popup_html;
                            self.quickViewContentContainer.el = quick_view_data_container;
                            self.quickViewContentContainer.view_container = quick_view_container;
                            self.quickViewContentContainer.prodict_id = prodict_id;
                            AjaxKitMain.loadJsCss(content, 'QuickViewLoader', self, 'initQuickViewJsLoaded');
                            AjaxKitMain.removeLoader(el)
                        }
                        AjaxKitMain.ajaxProcessor(self, 'get_quick_view_html', {id:prodict_id}, success_func);
                    }
                    AjaxKitMain.reinitSubmodules();
                });
            }
        });
    }

   , showQuickView: function(quick_view_data_container)
    {
        quick_view_data_container.style.display = 'block';
    }
    , hideQuickView: function()
    {
        $$('.quick-view-data-container').each(function(el)
        {
            el.innerHTML='';
            el.style.display = 'none';
        });
    }

    , initQuickViewJsLoaded: function(self)
    {
        if  (self.quickViewContentContainer.el)
        {
            self.showQuickView(self.quickViewContentContainer.el);
            var popup = AjaxKitMain.addHtmlPopup(self.quickViewContentContainer.html, false, self.quickViewContentContainer.el);
            popup.select('.close-popup, .close-popup-overlay').each(function(el)
            {
                Event.observe(el, 'click', function(eve)
                {
                    self.hideQuickView();
                });
            });

            popup.select(self.config.add_to_cart_btn_selector).each(function(el)
            {
                el.setAttribute('onclick', 'data/product/'+self.quickViewContentContainer.prodict_id)
            });

            AjaxKitMain.reinitSubmodules();
        }
    }
}

AjaxKitMain.addSubmodule("general_add_to_cart", "GeneralAddToCart.init()", GeneralAddToCart);

