<?php

class Bigone_Profile_Block_Adminhtml_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract {

    public function render(Varien_Object $row) {
        $value = $row->getData($this->getColumn()->getIndex());
        return '<p style="text-align:center;padding-top:10px;"><img src="' . $value . '"  style="width:100px;height:50px;text-align:center;"/></p>';
    }

}
