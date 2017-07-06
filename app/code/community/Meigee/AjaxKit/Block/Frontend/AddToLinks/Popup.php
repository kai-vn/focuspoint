<?php
class Meigee_AjaxKit_Block_Frontend_AddToLinks_Popup extends Meigee_AjaxKit_Block_Frontend_Popup
{
    protected $submoduleLayoutName = 'AddToLinks';
    protected  function getStaticBlockContent()
    {
        $configsReader = Mage::getModel('ajaxKit/configsReader');
        $static_block = false;

        switch ($this->getPopupHtmlType())
        {
            case 'wishlist':
                $static_block = $configsReader->getConfig('general_add_to_links', 'add_to_wishlist_success_message_static_block');
                break;
            case 'compare':
                $static_block = $configsReader->getConfig('general_add_to_links', 'add_to_compare_success_message_static_block');
                break;
        }
        return $static_block;
    }

    protected function getAppBlock($type)
    {
        $app_block = false;
        switch (strtolower($type))
        {
            case 'removed_products':
            case 'added_products':
                $app_block = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_popup_productList')
                    ->setProductIds($this->getProductIdList());
                break;
            case 'to_cart_button':
            case 'to_checkout_button':
            case 'continue_shopping_button':
                $app_block = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_popup_button');
                break;
            case 'related_products':
            case 'upsell_products':
            case 'cross_sell_products':
                $app_block = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_popup_additionalProducts')
                    ->setProductIds($this->getProductIdList());
                break;
            case 'info_text':
                $app_block = $this->getChild('info_text');
                break;
            case 'total_cart':
                $app_block = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_popup_cartTotal');
                break;
        }
        return $app_block;
    }
}