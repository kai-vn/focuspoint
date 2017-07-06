<?php
class Meigee_AjaxKit_Block_Adminhtml_Interface_Tabs extends Mage_Adminhtml_Block_Template
{
    private $store_switcher;
    function __construct()
    {
        parent::__construct();
        $this->setTemplate('ajaxkit/tabs.phtml');

        $this->setChild('store_switcher',
            Mage::app()->getLayout()->createBlock('adminhtml/store_switcher')
                ->setSwitchUrl($this->getUrl('*/*/*', array('_current'=>true, '_query'=>false, 'store'=>null)))
                ->setTemplate('store/switcher/enhanced.phtml')
        );
    }

    function getConfigTabs()
    {
        $submodules = Mage::getModel('ajaxKit/configsReader')->getConfigAdminhtml();
        $tabs = array();
        foreach ($submodules AS $namespace => $submodule)
        {
            $used = isset($submodule['extended']) ? $submodule['extended'] : $submodule['base'];
            $used->namespace = $namespace;
            $group_name = (string)$used->group_name;
            $tabs[$group_name][] = $used;
        }
        return $tabs;
    }
    function getStoreSwitcher()
    {
        return $this->getChildHtml('store_switcher');
    }
}
