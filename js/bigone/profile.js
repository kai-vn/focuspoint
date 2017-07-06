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
        if ($('question-container')) wrappers.push($('question-container'));
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