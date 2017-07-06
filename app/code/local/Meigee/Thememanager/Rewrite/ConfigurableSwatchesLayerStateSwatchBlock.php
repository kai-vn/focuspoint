<?php


class Meigee_Thememanager_Rewrite_ConfigurableSwatchesLayerStateSwatchBlock extends Mage_ConfigurableSwatches_Block_Catalog_Layer_State_Swatch
{
    function reSetFilter($filter)
    {
        $this->_initDone = false;
        $this->_init($filter);
        return $this;
    }

    function setFilter($filter)
    {
        $this->setData('filter', $filter);
        $this->reSetFilter($filter);
        return $this;
    }
}
