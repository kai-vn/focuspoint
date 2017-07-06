<?php
class Meigee_Thememanager_Rewrite_Catalog_Template_CatalogFilter extends Mage_Catalog_Model_Template_Filter
{

    function retinaurlDirective($construction)
    {
        $params = $this->_getIncludeParameters($construction[2]);
        return Mage::helper('thememanager/images')->dataToHtml($params);
    }




}