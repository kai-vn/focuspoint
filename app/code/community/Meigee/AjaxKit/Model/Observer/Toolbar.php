<?php

class Meigee_AjaxKit_Model_Observer_Toolbar
{
    private $postParams = array();

    function __construct()
    {
        $values = Mage::app()->getRequest()->getParam('values');
        if ($values)
        {
            $this->postParams = json_decode($values, true);
        }
    }
    
    function ControllerActionPostdispatch($event)
    {
        $result = array();
        $layout = Mage::app()->getLayout();
        if($block = $layout->getBlock('product_list'))
        {
            $result['productsList']	= $block->toHtml();
        }
        elseif($block = $layout->getBlock('search_result_list'))
        {
            $result['productsList']	= $block->toHtml();
        }
        if($block = $layout->getBlock('catalog.leftnav'))
        {
            $result['left_navigation']	= $block->toHtml();
        }
        return $result;
    }
    
    function ControllerActionPostdispatchObserver(Varien_Event_Observer $event)
    {
        $post = Mage::app()->getRequest()->getParams();
        if (isset($this->postParams['infinite_scroll']) && isset($post['limit']))
        {
            $infinite_scroll = (int)$post['limit'] * (int)$this->postParams['infinite_scroll'];
            $collection = $event['collection'];
            $collection->setPageSize($infinite_scroll);
            Mage::app()->getRequest()->setParams(array('infinite_scroll'=>null, 'limit'=>'all'));
        }
    }
}
