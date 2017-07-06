<?php

class Meigee_Thememanager_Rewrite_Catalog_Resource_LayerFilterAttribute extends Mage_Catalog_Model_Resource_Layer_Filter_Attribute
{
    private static $filtred = array();


    public function applyFilterToCollection($filter, $value)
    {
        if (!isset(self::$filtred[$value]))
        {
            self::$filtred[$value] = true;
            return parent::applyFilterToCollection($filter, $value);
        }

        return $this;
    }
}
