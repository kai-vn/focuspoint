<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meigeeteam.com <nick@meigeeteam.com>
 * @copyright Copyright (C) 2010 - 2014 Meigeeteam
 *
 */
class Meigee_CategoriesEnhanced_Model_Menutype
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'default-open', 'label'=>Mage::helper('Meigee_CategoriesEnhanced')->__('Always Open')),
            array('value'=>'hover', 'label'=>Mage::helper('Meigee_CategoriesEnhanced')->__('Hover'))
        );
    }

}