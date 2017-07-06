<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.07.15
 * Time: 12:46
 */

class Meigee_AjaxKit_Block_Frontend_AjaxResult extends Mage_Core_Block_Abstract
{
    const ERROR_TYPE_MSG = 'error';
    const SUCCESS_TYPE_MSG = 'success';


    protected function removeFromSidebarByUrl($url)
    {
        $pattern = '/((\/id\/(?P<id>(.*[0-9]))\/))|((\/item\/(?P<item>(.*[0-9]))\/))|((\/product_compare\/remove\/product\/(?P<compare_product>(.*[0-9]))\/))/u';
        preg_match($pattern, $url, $matches);
        $result['deleted'] = false;

        if (isset($matches['id']) && !empty($matches['id']))
        {
            try
            {
                $product_obj = Mage::getSingleton('checkout/cart')->getQuote()->getItemById($matches['id']);
				if($product_obj){
					$product_id = Mage::getSingleton('checkout/cart')->getQuote()->getItemById($matches['id'])->getProductId();
					Mage::getSingleton('checkout/cart')->removeItem($matches['id'])->save();

					$result['deleted'] = true;
					$result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
					$result['popup_content']['text'] = $this->__('item was removed from the shopping cart.');
					$result['popup_content']['product_id_list'] = $product_id;
				}
            }
            catch (Exception $e)
            {
                $result['popup_content']['text'] =  $e->getMessage();
                $result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;
            }
        }

        if (isset($matches['item']) && !empty($matches['item']))
        {
            $id = (int) $matches['item'];
            $session    = Mage::getSingleton('customer/session');
            $customer = $session->getCustomer();

            $wishlist = Mage::getModel('wishlist/wishlist')->loadByCustomer($customer, true);
            $wishListItems = $wishlist->getItemCollection();

            $result['popup_content']['text'] = $this->__('Cannot remove the item.');
            $result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;

            foreach ($wishListItems AS $item)
            {
                if ($item->getId() == $id)
                {
                    try
                    {
                        $item->delete();
                        $wishlist->save();
                        $result['deleted'] = true;
                        $result['popup_content']['text'] = $this->__('Item was removed from the wishlist.');
                        $result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
                    }
                    catch (Mage_Core_Exception $e)
                    {
                        $result['popup_content']['text'] = $this->__('An error occurred while deleting the item from wishlist: %s', $e->getMessage());
                        $result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;
                    }
                    catch (Exception $e)
                    {
                        $result['popup_content']['text'] = $this->__('An error occurred while deleting the item from wishlist.');
                        $result['popup_content']['text_type'] = self::ERROR_TYPE_MSG;
                    }
                }
            }
            $result['popup_content']['ok_btn'] = true;
        }


        if (isset($matches['compare_product']) && !empty($matches['compare_product']))
        {
            $productId = (int)$matches['compare_product'];
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);

            if($product->getId())
            {
                /** @var $item Mage_Catalog_Model_Product_Compare_Item */
                $item = Mage::getModel('catalog/product_compare_item');
                if(Mage::getSingleton('customer/session')->isLoggedIn())
                {
                    $item->addCustomerData(Mage::getSingleton('customer/session')->getCustomer());
                }
                elseif ($this->_customerId)
                {
                    $item->addCustomerData(
                        Mage::getModel('customer/customer')->load($this->_customerId)
                    );
                }
                else
                {
                    $item->addVisitorId(Mage::getSingleton('log/visitor')->getId());
                }
                $item->loadByProduct($product);
                if($item->getId())
                {
                    $item->delete();
                    $result['popup_content']['text'] =  $this->__('The product %s has been removed from comparison list.', $product->getName());
                    $result['popup_content']['text_type'] = self::SUCCESS_TYPE_MSG;
                    Mage::dispatchEvent('catalog_product_compare_remove_product', array('product'=>$item));
                    Mage::helper('catalog/product_compare')->calculate();
                }
            }
        }
        $this->result = $result;
        return $result;
    }
}