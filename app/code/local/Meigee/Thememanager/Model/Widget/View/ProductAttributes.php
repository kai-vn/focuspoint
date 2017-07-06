<?php class Meigee_Thememanager_Model_Widget_View_ProductAttributes
{
    public function toOptionArray()
    {
        $attributes = Mage::getSingleton('eav/config')
            ->getEntityType(Mage_Catalog_Model_Product::ENTITY)->getAttributeCollection();

        $attributes->addStoreLabel(Mage::app()->getStore()->getId());

        $return_attributes = array();

        foreach ($attributes as $attr)
        {
            $return_attributes[] = array('value'=>$attr->getName(), 'label'=>$attr->getName());
        }
        return $return_attributes;
    }

}


