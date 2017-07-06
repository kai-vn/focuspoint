<?php
class Meigee_AjaxKit_Block_Frontend_Popup_Default extends Mage_Core_Block_Template
{
    function __construct()
    {
        parent::__construct();
    }
    function getChildDataHtml($name, $attributes = array())
    {
        $child_block = parent::getChild($name);
        foreach($attributes AS $field=>$value)
        {
            $child_block->setData($field, $value);
        }
        return $child_block->toHtml();
    }
}
