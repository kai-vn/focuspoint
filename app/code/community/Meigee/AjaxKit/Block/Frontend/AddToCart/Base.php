<?php
class Meigee_AjaxKit_Block_Frontend_AddToCart_Base extends Meigee_AjaxKit_Block_Frontend_Submodules
{
    protected function _toHtml()
    {
        $is_switched = false;
        $request_module = strtolower(Mage::app()->getRequest()->getModuleName());
        $type = $request_module;

        switch ($request_module)
        {
            case "cms":
                $is_switched = true;
                $type = $request_module;
                break;
            case "catalog":

                $request_controller = strtolower(Mage::app()->getRequest()->getControllerName());
                if (in_array($request_controller, array('category', 'product')))
                {
                    $is_switched = true;
                    $type = $request_controller;
                }
                break;
        }

        if ($is_switched && !in_array($type, $this->getConfig('enable_ajax_for_add_to_cart')))
        {
            $type = false;
        }

        return "<script type='text/javascript'>//<![CDATA[
                     GeneralAddToCart.thisPage = " . ($type ? '"'.$type.'"' : 'false') . "
                // ]]></script>";
    }
}
