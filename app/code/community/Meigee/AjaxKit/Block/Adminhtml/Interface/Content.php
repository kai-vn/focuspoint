<?php
class Meigee_AjaxKit_Block_Adminhtml_Interface_Content extends Mage_Adminhtml_Block_Template
{
    function __construct()
    {
        parent::__construct();
        $this->setTemplate('ajaxkit/content.phtml');
    }
}
