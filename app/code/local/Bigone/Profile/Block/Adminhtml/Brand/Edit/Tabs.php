<?php

class Bigone_Profile_Block_Adminhtml_Brand_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs {

    public function __construct() {
        parent::__construct();
        $this->setId('brand_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle($this->_getHelper()->__('Brand Infomation'));
    }

    protected function _beforeToHtml() {
        $helper = $this->_getHelper();
        $this->addTab('general', array(
            'label' => $helper->__('General'),
            'title' => $helper->__('General'),
            'content' => $this->getLayout()->createBlock('profile/adminhtml_brand_edit_tab_general')->toHtml(),
            'active' => true,
        ));

        $this->addTab('glasses', array(
            'label' => $helper->__('Glasses'),
            'title' => $helper->__('Glasses'),
            'content' => $this->getLayout()->createBlock('profile/adminhtml_brand_edit_tab_glasses')->toHtml(),
        ));

        $this->addTab('lens', array(
            'label' => $helper->__('Lens'),
            'title' => $helper->__('Lens'),
            'content' => $this->getLayout()->createBlock('profile/adminhtml_brand_edit_tab_lens')->toHtml(),
        ));
        
        $this->addTab('coating', array(
            'label' => $helper->__('Coating Add On'),
            'title' => $helper->__('Coating Add On'),
            'content' => $this->getLayout()->createBlock('profile/adminhtml_brand_edit_tab_coating')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }

    private function _getHelper() {
        return Mage::helper('profile');
    }

}
