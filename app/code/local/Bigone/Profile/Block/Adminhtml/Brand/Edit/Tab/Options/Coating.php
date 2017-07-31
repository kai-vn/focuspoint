<?php

class Bigone_Profile_Block_Adminhtml_Brand_Edit_Tab_Options_Coating extends Mage_Adminhtml_Block_Template {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('profile/options/coating.phtml');
    }

    protected function _prepareLayout()
    {
        $this->setChild('delete_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Delete Column'),
                    'class' => 'delete delete-product-option'
                ))
        );

        return parent::_prepareLayout();
    }

    public function getDeleteButtonHtml()
    {
        return $this->getChildHtml('delete_button');
    }

    public function getOptionValue() 
    {
    	$data = Mage::registry('coating_data');
    	$values = array();
    	if ($data) {
    		foreach ($data as &$value) {
                $value['subprice'] = unserialize($value['subprice']);
                foreach ($value['subprice'] as $key => $sub) {
                    $value['subprice_'.$key] = $sub;
                }
                unset($value['subprice']);
    			$values[] = new Varien_Object($value);
    		}
    	}
    	return $values;
    }

    public function getFieldId()
    {
    	return 'coating_column';
    }

    public function getFieldName()
    {
        return 'options[coating]';
    }
    
    public function getAddButtonId()
    {
        return 'add_new_coating';
    }

    public function getListLens()
    {
        $list = array();
        $data_lens = Mage::registry('lens_data');
        if (!empty($data_lens)) {
            foreach ($data_lens as $lens) {
                $list[$lens['lens_id']] = $lens['title'];
            }
        }
        return $list;
    }

}
