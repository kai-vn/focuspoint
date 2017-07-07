<?php

class Meigee_AjaxKit_Block_Frontend_AddToCart_AjaxResult extends Meigee_AjaxKit_Block_Frontend_AjaxResult {

    private $ajax_values = array();
    private $wishlist_item = null;

    function ajax($action, $values) {
        $this->ajax_values = $values;
        $result = array();
        switch ($action) {
            case 'add_to_cart':
                $result = $this->addToCartByUrl($values['product']);
                $result['popup_content']['is_remove'] = false;
                break;
            case 'sidebar_remove_btn':
                $result = $this->removeFromSidebarByUrl($values['product']);
                $result['popup_content']['is_remove'] = true;
                break;
            case 'get_сart_html':
                $result = $this->getCart();
                break;
            case 'add_wishlist_to_сart':
                $result = $this->addWishlistProducts();
                break;
            case 'get_wishlist_html':
                $result = $this->getWishlistHtml();
                break;
            case 'get_quick_view_html':
                $result = $this->getQuickViewHtml();
                break;
        }
        if (isset($result['popup_content'])) {
            $result['popup_html'] = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_addToCart_Popup')
                    ->setPopupContent($result['popup_content'])
                    ->toHtml();
            unset($result['popup_content']);
        }
        return $result;
    }

    function clearProductRegistry() {
        if (Mage::registry('product')) {
            Mage::unregister('product');
        }

        if (Mage::registry('current_product')) {
            Mage::unregister('current_product');
        }
    }

    private function addToCartByUrl($url) {
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
//                    Zend_debug::dump($params);die('ádsa');
//                    if (!empty($params['profile'])) {
//                        //add custom option
//                        $additionalOptions = array();
//                        if ($additionalOption = $product->getCustomOption('additional_options')) {
//                            $additionalOptions = (array) unserialize($additionalOption->getValue());
//                        }
//                        foreach ($params['profile'] as $key => $value) {
//                            $additionalOptions[] = array(
//                                'label' => $key,
//                                'value' => $value,
//                            );
//                        }
//                        // add the additional options array with the option code additional_options
//                        $product->addCustomOption('additional_options', serialize($additionalOptions));
//                    }
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

    function getCart() {
        $return = array();
        $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array(), array('ajaxkit_popup_AddToCart', 'default'));
        $return['top_link_cart_html'] = $layout->getBlock("top_cart")->toHtml();
        $return['cart_sidebar'] = $layout->getBlock("cart_sidebar")->setData('item_count', null)->toHtml();
        $return['wishlist_sidebar'] = $layout->getBlock("wishlist_sidebar")->toHtml();
        return $return;
    }

    function addWishlistProducts($singleItemId = false) {
        $session = Mage::getSingleton('customer/session');
        $customer = $session->getCustomer();

        if ($customer->getId()) {
            $wishlist = Mage::getModel('wishlist/wishlist')->loadByCustomer($customer, true);
            $wishListItem_collection = $wishlist->getItemCollection();
            $cart = Mage::getSingleton('checkout/cart');
            $qty_arr = array();
            $description_arr = array();

            if (!empty($this->ajax_values)) {
                $attr_arr = $this->helper('ajaxKit')->parseParamsByAttributes($this->ajax_values);
                $qty_arr = isset($attr_arr['qty']) ? $attr_arr['qty'] : array();
                $description_arr = isset($attr_arr['description']) ? $attr_arr['description'] : array();
            }

            $isOwner = $wishlist->isOwner(Mage::getSingleton('customer/session')->getCustomerId());
            $messages = array();
            $addedItems = array();
            $notSalable = array();
            $hasOptions = array();
            $is_one_item_added = false;

            $defaultCommentString = Mage::helper('wishlist')->defaultCommentString();
            foreach ($wishListItem_collection as $wishlist_item) {
                $itemId = $wishlist_item->getId();
                if ($singleItemId !== false && $singleItemId != $itemId) {
                    continue;
                }

                try {
                    $item = Mage::getModel('wishlist/item')->load($itemId);
                    if ($singleItemId !== false) {
                        $this->wishlist_item = $item;
                    }

                    if (isset($qty_arr[$itemId])) {
                        $qty = (int) $qty_arr[$itemId] ? (int) $qty_arr[$itemId] : 1;
                        $item->setQty($qty);
                    }

                    if (isset($description_arr[$itemId])) {
                        $description = $defaultCommentString == $description_arr[$itemId] ? '' : $description_arr[$itemId];
                        $item->setDescription($description);
                    }

                    $options = Mage::getModel('wishlist/item_option')->getCollection()
                            ->addItemFilter(array($itemId));
                    $item->setOptions($options->getOptionsByItem($itemId));

                    $buyRequest = Mage::helper('catalog/product')->addParamsToBuyRequest(
                            $this->getRequest()->getParams(), array('current_config' => $item->getBuyRequest())
                    );

                    $item->mergeBuyRequest($buyRequest);
                    if ($item->addToCart($cart, true)) {
                        $addedItems[] = $item->getProduct();
                        $is_one_item_added = true;
                    }
                    Mage::helper('wishlist')->calculate();
                } catch (Mage_Core_Exception $e) {
                    if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_NOT_SALABLE) {
                        $notSalable[] = $item;
                    } else if ($e->getCode() == Mage_Wishlist_Model_Item::EXCEPTION_CODE_HAS_REQUIRED_OPTIONS) {
                        $hasOptions[] = $item;
                    } else {
                        $messages[] = $this->__('%s for "%s".', trim($e->getMessage(), '.'), $item->getProduct()->getName());
                    }

                    $cartItem = $cart->getQuote()->getItemByProduct($item->getProduct());
                    if ($cartItem) {
                        $cart->getQuote()->deleteItem($cartItem);
                    }

                    if ($singleItemId !== false) {
                        return $is_one_item_added;
                    }
                } catch (Exception $e) {
                    Mage::logException($e);
                    $session->addException($e, $this->__('Cannot add item to shopping cart'));
                    if ($singleItemId !== false) {
                        return $is_one_item_added;
                    }
                }
            }

            if ($notSalable) {
                $products = array();
                foreach ($notSalable as $item) {
                    $products[] = '"' . $item->getProduct()->getName() . '"';
                }
                $messages[] = Mage::helper('wishlist')->__('Unable to add the following product(s) to shopping cart: %s.', join(', ', $products));
            }

            if ($hasOptions) {
                $products = array();
                foreach ($hasOptions as $item) {
                    $products[] = '"' . $item->getProduct()->getName() . '"';
                }
                $messages[] = Mage::helper('wishlist')->__('Product(s) %s have required options. Each of them can be added to cart separately only.', join(', ', $products));
            }

            if ($messages) {
                $isMessageSole = (count($messages) == 1);
                if ($isMessageSole && count($hasOptions) == 1) {
                    $item = $hasOptions[0];
                    if ($isOwner) {
                        $item->delete();
                    }
                } else {
                    foreach ($messages as $message) {
                        $session->addError($message);
                    }
                }
            }

            if ($addedItems) {
                try {
                    $wishlist->save();
                } catch (Exception $e) {
                    $session->addError($this->__('Cannot update wishlist'));
                }

                $products = array();
                foreach ($addedItems as $product) {
                    $products[] = '"' . $product->getName() . '"';
                }

                $session->addSuccess(
                        Mage::helper('wishlist')->__('%d product(s) have been added to shopping cart: %s.', count($addedItems), join(', ', $products))
                );
                $cart->save()->getQuote()->collectTotals();
            }

            Mage::helper('wishlist')->calculate();
            if ($singleItemId !== false) {
                return $is_one_item_added;
            }
        }
        return array('completed' => true);
    }

    function getWishlistHtml() {
        $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array(), array('wishlist_index_index', 'default'));
        $sessionMessages = Mage::getSingleton('customer/session')->getMessages(true);
        $layout->getBlock('messages')->setMessages($sessionMessages);
        $customer_wishlist_block = $layout->getBlock("customer.wishlist");
        if (!$customer_wishlist_block) {
            $customer_wishlist_block = $layout->createBlock("wishlist/customer_wishlist");
        }
        $return['wishlist_html'] = $customer_wishlist_block->toHtml();
        return $return;
    }

    function getQuickViewHtml() {
        $result = array();
        if ($this->ajax_values['id'] && (int) $this->ajax_values['id']) {
            $product = Mage::getModel('catalog/product')->load((int) $this->ajax_values['id']);
            $this->clearProductRegistry();
            Mage::register('product', $product);
            Mage::register('current_product', $product);
            $layout = Mage::getModel('ajaxKit/updateLayout')
                    ->getConfigs(array('head' => 'page/html_head'), array('ajaxkit_quick_product_view', 'PRODUCT_TYPE_' . strtolower($product->getTypeId())));

            $result['popup_content']['content_html'] = $layout->getBlock("product.info")->toHtml();
            Mage::getModel('ajaxKit/updateLayout')->getLayoutJsCss($result);
            $result['checkout'] = 'ajaxKit/checkout/cart/add/product/' . $this->ajax_values['id'] . '/quickview';
        }
        return $result;
    }

}
