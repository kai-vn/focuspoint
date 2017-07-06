<?php 
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meigeeteam.com <nick@meigeeteam.com>
 * @copyright Copyright (C) 2010 - 2014 Meigeeteam
 *
 */
class Meigee_CategoriesEnhanced_Model_Menuskin
{
    public function toOptionArray()
    {
        return array(
            array('value'=>'skin-1', 'label'=>Mage::helper('Meigee_CategoriesEnhanced')->__('Skin 1')),
            array('value'=>'skin-2', 'label'=>Mage::helper('Meigee_CategoriesEnhanced')->__('Skin 2'))
        );
    }

}