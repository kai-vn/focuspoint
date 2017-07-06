<?php

class Bigone_Profile_Block_Question5 extends Mage_Core_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/question5.phtml');
    }

    public function _prepareLayout() {
        return parent::_prepareLayout();
    }

    public function getOptions($start=25, $step=0.5, $end=40) {
        $option = array();
        while ($start <= $end) {
            $option[] = $start;
            $start += $step;
        }
        return $option;
    }

}
