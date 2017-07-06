<?php
class Meigee_AjaxKit_Block_Adminhtml_Interface_Header extends Mage_Adminhtml_Block_Template
{
    function __construct()
    {
        parent::__construct();
        $this->setTemplate('ajaxkit/header.phtml');
    }
}

