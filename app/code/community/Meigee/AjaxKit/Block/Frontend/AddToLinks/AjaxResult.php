<?php
class Meigee_AjaxKit_Block_Frontend_AddToLinks_AjaxResult extends Meigee_AjaxKit_Block_Frontend_AjaxResult
{
    private $ajax_values = array();
    protected $result = array();

    function ajax($action, $values)
    {
        $this->ajax_values =$values;
        $popup_html_type = false;
        switch($action)
        {
            case 'add_to_compare':
                $this->addToCompare();
                $popup_html_type = 'compare';
                break;
            case 'update_compare_list':
                $this->getCompareList();
                break;

            case 'sidebar_product_compare_clear_all':
                $this->clearCompareList();
                break;
            case 'sidebar_remove_btn':
                $result = $this->removeFromSidebarByUrl($values['product']);
                $result['popup_content']['is_remove'] = true;
                break;

            case 'add_to_wishlist':
                $this->addToWishlist();
                $popup_html_type = 'wishlist';
                break;
            case 'update_wishlist_list':
                $this->getWishlistList();
                break;

        }
        if (isset($this->result['popup_content']))
        {
            $this->result['popup_html'] = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_addToLinks_Popup')
                ->setPopupContent($this->result['popup_content'])
                ->setPopupHtmlType($popup_html_type)
                ->toHtml();
            unset($this->result['popup_content']);
        }
        return $this->result;
    }

    function addToCompare()
    {
        $product_id = (int)$this->helper('ajaxKit')->getProductIdByUrl($this->ajax_values['url']);
        $this->result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;

        if ($product_id
            && (Mage::getSingleton('log/visitor')->getId() || Mage::getSingleton('customer/session')->isLoggedIn())
        ) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($product_id);
            if ($product->getId() )
            {
                Mage::getSingleton('catalog/product_compare_list')->addProduct($product);


                $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
                $this->result['popup_content']['text'] = $this->__('The product %s has been added to comparison list.', Mage::helper('core')->escapeHtml($product->getName()));

                Mage::dispatchEvent('catalog_product_compare_add_product', array('product'=>$product));
                $this->result['popup_content']['product_id_list'] = $product->getId();
            }
            Mage::helper('catalog/product_compare')->calculate();
        }
    }

    public function clearCompareList()
    {
        $items = Mage::getResourceModel('catalog/product_compare_item_collection');
        if (Mage::getSingleton('customer/session')->isLoggedIn())
        {
            $items->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId());
        }
        else
        {
            $items->setVisitorId(Mage::getSingleton('log/visitor')->getId());
        }

        /** @var $session Mage_Catalog_Model_Session */
        $session = Mage::getSingleton('catalog/session');

        try {
            $items->clear();
            $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
            $this->result['popup_content']['text'] = $this->__('The comparison list was cleared.');
                Mage::helper('catalog/product_compare')->calculate();
        }
        catch (Mage_Core_Exception $e)
        {
            $this->result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;
            $this->result['popup_content']['text'] = $e->getMessage();
        }
        catch (Exception $e)
        {
            $this->result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;
            $this->result['popup_content']['text'] = $this->__('An error occurred while clearing comparison list.');
        }
    }


    function getCompareList()
    {
        $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array(), array('default'));
        $this->result['compare_sidebar'] = $layout->getBlock('catalog.compare.sidebar') ? $layout->getBlock('catalog.compare.sidebar')->toHtml() : '';
    }

    function addToWishlist()
    {
        $pattern = '/\/wishlist\/index\/fromcart\/item\/(?P<cart_item>.*[0-9])\//';
        preg_match($pattern, $this->ajax_values['url'], $matches);
        
        if (isset($matches['cart_item']))
        {
            $itemId = $matches['cart_item'];
            $this->addProductToWishlistFromCart($itemId);
        }
        else
        {
            $product_id = 0;
            $pattern = '/\/id\/(?P<wishlist_item>.*[0-9])\//';
            preg_match($pattern, $this->ajax_values['url'], $matches);
            if (isset($matches['wishlist_item']))
            {
                $customerId = Mage::getSingleton('customer/session')->getCustomerId();
                $itemCollection = Mage::getModel('wishlist/item')->getCollection()->addCustomerIdFilter($customerId);
                foreach($itemCollection as $item) 
                {
                    if($item->getId() == $matches['wishlist_item'])
                    {
                        $product_id = $item->getProduct()->getId();
                        $item->delete();
                    }
                }
            }
            else
            {
                $product_id = (int)$this->helper('ajaxKit')->getProductIdByUrl($this->ajax_values['url']);
            }
            if ($product_id)
            {
                $this->addProductToWishlist($product_id);
            }
        }
    }


    function addProductToWishlistFromCart($itemId)
    {
        $cart = Mage::getSingleton('checkout/cart');
        $wishlist = $this->_getWishlist();

        try {
            $item = $cart->getQuote()->getItemById($itemId);
            if (!$item) {
                Mage::throwException(
                    Mage::helper('wishlist')->__("Requested cart item doesn't exist")
                );
            }

            $productId  = $item->getProductId();
            $buyRequest = $item->getBuyRequest();

            $wishlist->addNewItem($productId, $buyRequest);

            $productIds[] = $productId;
            $cart->getQuote()->removeItem($itemId);
            $cart->save();
            Mage::helper('wishlist')->calculate();
            $productName = Mage::helper('core')->escapeHtml($item->getProduct()->getName());
            $wishlistName = Mage::helper('core')->escapeHtml($wishlist->getName());
            $this->result['popup_content']['text'] = $this->__("%s has been moved to wishlist %s", $productName, $wishlistName);
            $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
            $this->result['status'] = 'RELOAD';
            $wishlist->save();
        }
        catch (Mage_Core_Exception $e)
        {
            $this->result['popup_content']['text'] = $e->getMessage();
            $this->result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;
        }
        catch (Exception $e)
        {
            $this->result['popup_content']['text'] = Mage::helper('wishlist')->__('Cannot move item to wishlist');
            $this->result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;
        }
    }





    function addProductToWishlist($product_id)
    {
        $this->result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;

        $product = Mage::getModel('catalog/product')->load($product_id);

        if (!$product->getId() || !$product->isVisibleInCatalog())
        {
            $this->result['popup_content']['text'] = $this->__('Cannot specify product.');
            return;
        }

        try
        {
            $wishlist = $this->_getWishlist();

            if (!$wishlist)
            {
                $session = Mage::getSingleton('customer/session');
                $session->setAfterAuthUrl($this->ajax_values['url']);
                $this->setRedirect();
                return;
            }

            $buyRequest = array('product'=>$product_id);
            $attributes = $this->ajax_values['attributes'];
            $buyRequest = array_merge($buyRequest, $this->helper('ajaxKit')->parseParamsByAttributes($attributes));
            $buyRequest = new Varien_Object($buyRequest);

            $result = $wishlist->addNewItem($product, $buyRequest);
            if (is_string($result)) {
                Mage::throwException($result);
            }
            $wishlist->save();

            Mage::dispatchEvent(
                'wishlist_add_product',
                array(
                    'wishlist' => $wishlist,
                    'product' => $product,
                    'item' => $result
                )
            );

            Mage::helper('wishlist')->calculate();
            $message = $this->__('%1$s has been added to your wishlist.', $product->getName());
            $this->result['popup_content']['text'] = $message;
            $this->result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
            $this->result['popup_content']['product_id_list'] = $product->getId();
        }
        catch (Mage_Core_Exception $e)
        {
            $this->result['popup_content']['text'] = $this->__('An error occurred while adding item to wishlist: %s', $e->getMessage());
            $this->setRedirect();
        }
        catch (Exception $e)
        {
            $this->result['popup_content']['text'] = $this->__('An error occurred while adding item to wishlist.');
            $this->setRedirect();
        }
    }








    private function setRedirect()
    {
        $this->result['redirect_to'] = Mage::getUrl('customer/account/login', array('_forced_secure' => Mage::app()->getStore()->isCurrentlySecure()));
        $this->result['status'] = 'REDIRECT';
    }

    protected function _getWishlist($wishlistId = null)
    {
        $wishlist = Mage::registry('wishlist');
        if ($wishlist)
        {
            return $wishlist;
        }

        try
        {
            if (!$wishlistId) {
                $wishlistId = $this->getRequest()->getParam('wishlist_id');
            }
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            /* @var Mage_Wishlist_Model_Wishlist $wishlist */
            $wishlist = Mage::getModel('wishlist/wishlist');
            if ($wishlistId) {
                $wishlist->load($wishlistId);
            } else {
                $wishlist->loadByCustomer($customerId, true);
            }

            if (!$wishlist->getId() || $wishlist->getCustomerId() != $customerId) {
                $wishlist = null;
                $this->result['popup_content']['text'] = Mage::helper('wishlist')->__("Requested wishlist doesn't exist");
            }

            Mage::register('wishlist', $wishlist);
        } catch (Mage_Core_Exception $e)
        {
            $this->result['popup_content']['text'] = $e->getMessage();
            $this->setRedirect();
            return false;
        } catch (Exception $e) {
            $this->result['popup_content']['text'] = Mage::helper('wishlist')->__('Wishlist could not be created.');
            $this->setRedirect();
            return false;
        }

        return $wishlist;
    }

    function getWishlistList()
    {
        $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array(), array('default'));
        $_links = $layout->getBlock("top.links")->getLinks();

        foreach($_links AS $_link)
        {
            if ($_link->getType() ==  'wishlist/links')
            {
                $this->result['wishlist_header'] =  $_link->toHtml();
                break;
            }
        }
        $this->result['wishlist_sidebar'] = $layout->getBlock('wishlist_sidebar')->toHtml();
    }
}



