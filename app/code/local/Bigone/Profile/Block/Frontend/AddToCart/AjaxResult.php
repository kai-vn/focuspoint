<?php

class Bigone_Profile_Block_Frontend_AddToCart_AjaxResult extends Meigee_AjaxKit_Block_Frontend_AddToCart_AjaxResult {

    private function addToCartByUrl($url) {
        die('asdads');
        $attributes = isset($this->ajax_values['attributes']) ? $this->ajax_values['attributes'] : false;
        $related_product = isset($this->ajax_values['related_product']) ? $this->ajax_values['related_product'] : false;
        $qty = isset($this->ajax_values['qty']) ? $this->ajax_values['qty'] : 1;
        $product = false;
        $product_id = null;

        $result = array();
        $result['update_wishlist'] = false;
        $result['added'] = false;

        $pattern = '/(addWItemToCart\((.*[0-9])\))|(\/index\/cart\/item\/(.*[0-9])\/)/';
        preg_match($pattern, $url, $matches);

        if (!empty($matches)) {
            $item_id = end($matches);
            $wishlist_status = $this->addWishlistProducts($item_id);
            $product_id = $this->wishlist_item->getProduct()->getId();

            if ($wishlist_status) {
                $result['status'] = 'SUCCESS';
                $result['added'] = '1';
                $result['popup_content']['product_id_list'] = $product_id;
            }
            $result['update_wishlist'] = 'wishlist' == strtolower($this->ajax_values['pageType']);
        } else {
            $product_id = $this->helper('ajaxKit')->getProductIdByUrl($url);
        }

        if (!empty($product_id)) {
            $product = Mage::getModel('catalog/product')->load($product_id);
        }

        $this->clearProductRegistry();
        Mage::register('product', $product);
        Mage::register('current_product', $product);

        if (!$product) {
            if (!$result['added']) {
                $result['status'] = 'ERROR';
            }
        } else {
            $is_add_to_cart = true;
            $is_product_controller = isset($this->ajax_values['pageType']) && 'product' == strtolower($this->ajax_values['pageType']);

            $has_options = !$attributes && ($product->getTypeInstance(true)->hasOptions($product) || (!$is_product_controller && 'grouped' == $product->getTypeId()));
            if (isset($attributes['__kit'])) {
                unset($attributes['__kit']);
            }

            $relatedProductsIds = $product->getRelatedProductIds();
            $layout = Mage::getModel('ajaxKit/updateLayout')
                    ->getConfigs(array('head' => 'page/html_head'), array('ajaxkit_popup_AddToCart', 'catalog_product_view', 'PRODUCT_TYPE_' . strtolower($product->getTypeId())));
            if ($has_options) {
                Mage::getModel('ajaxKit/updateLayout')->getLayoutJsCss($result);
                $result['popup_content']['json_config'] = '';
                $block_options_wrapper = $layout->getBlock("product.info.options.wrapper");

                if ($has_options && method_exists($block_options_wrapper, 'getJsonConfig')) {
                    $result['popup_content']['json_config'] .= '<script type="text/javascript">
                                        var optionsPrice = new Product.OptionsPrice(' . $block_options_wrapper->getJsonConfig() . ');
                                     </script>';
                    switch ($product->getTypeId()) {
                        case "bundle":
                            $result['popup_content']['bundle_prices'] = $layout->getBlock("bundle.prices")->toHtml();
                            $result['popup_content']['json_config'] .= '<script type="text/javascript">
                                        var bundle = new Product.Bundle(' . $layout->getBlock("product.info.bundle")->getJsonConfig() . ');
                                     </script>';
                            break;

                        default:

                            break;
                    }
                    $result['popup_content']['product_info_options_wrapper_html'] = $block_options_wrapper->toHtml();
                }
                $result['popup_content']['product_info_grouped_html'] = '';
                switch ($product->getTypeId()) {
                    case "grouped":
                        $result['popup_content']['product_info_grouped_html'] = $layout->getBlock("product.info.grouped")->_toHtml();
                        break;
                }
                $result['popup_content']['product_info_options_wrapper_bottom_html'] = $layout->getBlock("product.info.options.wrapper.bottom")->toHtml();
                $result['status'] = 'SUCCESS';
                $result['show_options'] = '1';
                $is_add_to_cart = false;
            }

            if ($is_add_to_cart) {
                try {
                    $params = array(
                        'product' => $product->getId(),
                        'qty' => $qty,
                        'related_product' => ''
                    );

                    if ($attributes) {
                        $params = array_merge($params, $this->helper('ajaxKit')->parseParamsByAttributes($attributes));
                    }

                    $filter = new Zend_Filter_LocalizedToNormalized(
                            array('locale' => Mage::app()->getLocale()->getLocaleCode())
                    );
                    $params['qty'] = $filter->filter($params['qty']);

                    //add custom option
                    $additionalOptions = array();
                    if ($additionalOption = $product->getCustomOption('additional_options')) {
                        $additionalOptions = (array) unserialize($additionalOption->getValue());
                    }
                    foreach ($params['profile'] as $key => $value) {
                        $additionalOptions[] = array(
                            'label' => $key,
                            'value' => $value,
                        );
                    }
                    // add the additional options array with the option code additional_options
                    $product->addCustomOption('additional_options', serialize($additionalOptions));

                    $cart = Mage::getSingleton('checkout/cart');
                    $cart->addProduct($product, $params);

                    // get child ID
                    $child = false;
                    if (method_exists($product->getTypeInstance(true), 'getProductByAttributes')) {
                        $child = $product->getTypeInstance(true)->getProductByAttributes($params['super_attribute'], $product);
                    }

                    $added_products = array();
                    $added_products[] = $child ? $child->getId() : $product->getId();

                    if (!empty($related_product)) {
                        $cart->addProductsByIds($related_product);
                    }

                    $cart->save();
                    $result['status'] = 'SUCCESS';
                    $result['added'] = '1';

                    if (!is_null($this->wishlist_item)) {
                        $this->wishlist_item->delete();
                    }

                    if (isset($params['super_group'])) {
                        foreach ($params['super_group'] AS $gr_id => $count) {
                            if ($count > 0) {
                                $added_products[] = $gr_id;
                            }
                        }
                    }

                    if (!empty($related_product)) {
                        $added_products = array_merge($added_products, $related_product);
                    }

                    $result['popup_content']['product_id_list'] = $added_products;
                    $result['popup_content']['text'] = $this->__('Product has been added to the shopping cart.');
                    $result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
                } catch (Exception $e) {
                    $result['status'] = 'ERROR';
                    $result['popup_content']['text'] = $e->getMessage();
                    $result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;
                    $result['popup_content']['ok_btn'] = true;
                    Mage::logException($e);
                }
            } else {
                $result['popup_content']['product'] = $product;
            }
        }
        return $result;
    }

}
