<?php
class Meigee_AjaxKit_Block_Frontend_Popup_AdditionalProducts extends Mage_Core_Block_Template
{

    function __construct()
    {
        $this->setTemplate('ajaxkit/popup/additional_products.phtml');
        parent::__construct();
    }


    private function _getProductCollection($product_ids, $is_full = true)
    {
        $product_collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array('in' => $product_ids));
        if ($is_full)
        {
            $product_collection->addAttributeToSelect(array('name', 'price', 'small_image', 'short_description'), 'left')
                ->addAttributeToSelect('news_from_date')
                ->addAttributeToSelect('news_to_date')
                ->addAttributeToSelect('special_price')
                ->addAttributeToSelect('status')
                ->addAttributeToSelect('*');

            if ($this->getLimit() && (int)$this->getLimit()>0)
            {
                $product_collection->setCurPage(1)->setPageSize((int)$this->getLimit());
            }
        }
        return $product_collection;
    }

    function getProductCollection()
    {
        $product_ids = $this->getProductIds();

        if (!is_array($product_ids))
        {
            $product_ids = (array)$product_ids;
        }
        $product_collection = $this->_getProductCollection($product_ids, false);

        $new_product_ids = array();

        switch (strtolower($this->getType()))
        {
            case 'related_products':
                foreach ($product_collection AS $product)
                {
                    $new_product_ids = array_merge($new_product_ids, $product->getRelatedProductIds());
                }
                break;
            case 'upsell_products':
                foreach ($product_collection AS $product)
                {
                    $new_product_ids = array_merge($new_product_ids, $product->getUpSellProductIds());
                }
                break;
            case 'cross_sell_products':

                foreach ($product_collection AS $product)
                {
                    $new_product_ids = array_merge($new_product_ids, $product->getCrossSellProductIds());
                }
                break;
        }

        if (!empty($new_product_ids))
        {
            return $this->_getProductCollection($new_product_ids, true);
        }
        return false;
    }
    function isShow($param_name)
    {
        $param = $this->getData('show_'.$param_name);
        return $param && '0' != $param;
    }
}
