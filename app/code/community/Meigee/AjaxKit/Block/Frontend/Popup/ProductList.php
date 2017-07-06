<?php
class Meigee_AjaxKit_Block_Frontend_Popup_ProductList extends Mage_Core_Block_Template
{
    protected $popup_text = false;
    protected $popup_text_class = false;

    function getProductCollection()
    {
        $product_ids = $this->getProductIds();

        if (!is_array($product_ids))
        {
            $product_ids = (array)$product_ids;
        }
        if (!empty($product_ids))
        {
            return Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array('in' => $product_ids))
                ->addAttributeToSelect(array('name', 'price', 'small_image', 'short_description'), 'inner')
                ->addAttributeToSelect('news_from_date')
                ->addAttributeToSelect('news_to_date')
                ->addAttributeToSelect('special_price')
                ->addAttributeToSelect('status')
                ->addAttributeToSelect('*');
        }
        return false;
    }
}
