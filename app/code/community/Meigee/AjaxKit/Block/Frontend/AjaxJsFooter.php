<?php

class Meigee_AjaxKit_Block_Frontend_AjaxJsFooter extends Mage_Core_Block_Abstract
{
    static $isAdded = false;

    protected function _toHtml()
    {
        if (!self::$isAdded && !$this->getRequest()->getParam('isAjax'))
        {
            self::$isAdded = true;
            $used_js_css = array();
            Mage::getModel('ajaxKit/updateLayout')->getLayoutJsCss($used_js_css);
            return
                "<script type='text/javascript'>//<![CDATA[
                     AjaxKitConfig['main']['js_css'] = JSON.parse('" . Mage::helper('core')->jsonEncode($used_js_css) . "');
                // ]]></script>";
        }
    }
}