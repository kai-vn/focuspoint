<?php

class Bigone_Profile_Block_Adminhtml_Brand extends Mage_Adminhtml_Block_Widget_Grid_Container {
  public function __construct(){
    $this->_controller = 'adminhtml_brand';
    $this->_blockGroup = 'profile';
    $this->_headerText = Mage::helper('profile')->__('Manage Brand');
    parent::__construct();
  }
}