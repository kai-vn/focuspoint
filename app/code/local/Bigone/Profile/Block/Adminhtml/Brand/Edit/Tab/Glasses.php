<?php

class Bigone_Profile_Block_Adminhtml_Brand_Edit_Tab_Glasses extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/glasses.phtml');
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        $this->setChild('add_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Add New Column'),
                            'class' => 'add',
                            'id' => 'add_new_glasses'
                        ))
        );
        $this->setChild('glasses_column', $this->getLayout()->createBlock('profile/adminhtml_brand_edit_tab_options_glasses')
        );
        return $this;
    }

    public function getTitle() {
        return 'Column For Glasses';
    }

}
