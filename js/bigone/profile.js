GeneralAddToCart.addToCartPopupProcessor = function (el, parent_form) {
    var self = this;

    if (undefined == parent_form)
    {
        var parent_form = $('AddToCart-popup');
    }
    var form = new VarienForm(parent_form.id);
    if (form.validator.validate())
    {
        var wrappers = parent_form.select('#product-options-wrapper, .product-info-options-wrapper, .product-info-products-groupe, #super-product-table');
        if ($('question-container'))
            wrappers.push($('question-container'));
        var attributes = {'__kit': 1};
        if (wrappers.length > 0)
        {
            wrappers._each(function (wrapper)
            {
                var values = wrapper.select('select, input, textarea');
                values._each(function (attribute)
                {
                    var field = attribute.name;
                    var attribute_type = attribute.getAttribute('type');

                    if ('checkbox' == attribute_type || 'radio' == attribute_type)
                    {
                        if (attribute.checked)
                        {
                            field = field.replace("[]", "");
                            if ('undefined' == typeof attributes[field])
                            {
                                attributes[field] = [];
                            }
                            attributes[field].push(attribute.value);
                        }
                    } else
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
        parent_form.select('.popup-related-products input[name^=related_products]:checked').each(function (checkbox)
        {
            related_products_arr.push(checkbox.value);
        });

        if ('product' == self.thisPage)
        {
            $$('.block-related input[name^=related_products]:checked').each(function (checkbox)
            {
                related_products_arr.push(checkbox.value);
            });
        }
        self.addToCartProcessor(el, {attributes: attributes, related_product: related_products_arr, qty: qty});
    }

}
GeneralAddToCart.initAddToCartButtons = function ()
{
    var self = this;
    $$(this.config.add_to_cart_btn_selector).each(function (el)
    {
        var onclickValue = false;
        if (!el.hasClassName('AjaxKit-addtocart-link'))
        {
            el.addClassName('AjaxKit-addtocart-link');
            if (('product' == self.thisPage || 'wishlist' == self.thisPage) && undefined !== el.up('#product_addtocart_form'))
            {
                var form = el.up('#product_addtocart_form');
                var onclickValue = form.getAttribute('action');
            } else
            {
                switch (el.tagName)
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
            var onclickValueUrl = reg.exec(onclickValue)
            onclickValue = onclickValueUrl ? onclickValueUrl[1] : onclickValue;
            el.setAttribute('onclick', 'return false;');
            el.setAttribute('data-onclick-value', onclickValue);

            Event.observe(el, 'click', function (eve)
            {
                form = el.up('#product_addtocart_form');
                if ('product' == self.thisPage || undefined !== form)
                {
                    self.addToCartPopupProcessor(this, form);
                } else
                {
                    self.addToCartProcessor(this)
                }
            });
        }
    });
    //custom bigone button add to cart
    var bigone_btn = $('bigone-addtocart-btn');
    if (bigone_btn) {
        var onclickValue = false;
        if (!bigone_btn.hasClassName('AjaxKit-addtocart-link'))
        {
            bigone_btn.addClassName('AjaxKit-addtocart-link');
            if (('product' == self.thisPage || 'wishlist' == self.thisPage))
            {
                var form = $('product_addtocart_form');
                var onclickValue = form.getAttribute('action');
            } else
            {
                switch (bigone_btn.tagName)
                {
                    case 'A':
                        var onclickValue = bigone_btn.getAttribute('href');
                        bigone_btn.setAttribute('href', '#');
                        break;
                    case 'INPUT':
                    case 'BUTTON':
                        var onclickValue = bigone_btn.getAttribute('onclick');
                        break;
                }
            }
        }
        if (onclickValue)
        {
            var reg = /\(\'(.*)\'\)/gi;
            var onclickValueUrl = reg.exec(onclickValue)
            onclickValue = onclickValueUrl ? onclickValueUrl[1] : onclickValue;
            bigone_btn.setAttribute('onclick', 'return false;');
            bigone_btn.setAttribute('data-onclick-value', onclickValue);

            Event.observe(bigone_btn, 'click', function (eve)
            {
                form = $('product_addtocart_form');
                if ('product' == self.thisPage || undefined !== form)
                {
                    self.addToCartPopupProcessor(this, form);
                } else
                {
                    self.addToCartProcessor(this)
                }
            });
        }
    }

    //end custom
    switch (self.thisPage)
    {
        case 'wishlist':
            $$('button[onclick^=addAllWItemsToCart], .btn-add')._each(function (el)
            {
                if (!el.hasClassName('addAllWItemsToCartBtn'))
                {
                    Event.observe(el, 'click', function (eve)
                    {
                        AjaxKitMain.addLoader(this);
                        var success_func = function (cart_data)
                        {
                            self.updateWishlist();
                            self.updateCartHtml();
                        }
                        var values = {};
                        values.qty = {};
                        $$('textarea, input')._each(function (qty_el)
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
