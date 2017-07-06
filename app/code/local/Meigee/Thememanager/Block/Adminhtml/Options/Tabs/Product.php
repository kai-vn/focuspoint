<?php
class Meigee_Thememanager_Block_Adminhtml_Options_Tabs_Product extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    private $uri = '*/*/getProductTable';

    public function __construct()
    {
        parent::__construct();
    }

    function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }


    protected function _getStore()
    {
        $theme_id = (int)Mage::app()->getRequest()->getParam('theme_id');
        $theme_config_data = Mage::getModel('thememanager/themes')->load($theme_id);
        return Mage::app()->getStore($theme_config_data->getStoreId());
    }
    protected function _prepareMassaction()
    {
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl($this->uri, array('_current'=>true));
//        return $this->getUrl('*/*/getProductTable', array('_current'=>true));
    }

    public function getRowUrl($row)
    {}

    protected function _prepareCollection()
    {
        parent::_prepareCollection();
    }


    protected function _prepareColumns()
    {
        $helper = Mage::helper('thememanager');

        $this->addColumn('entity_id_grid_checkbox', array(
            'header' => $helper->__(''),
            'width' => 20,
            'renderer'  => 'Meigee_Thememanager_Block_Adminhtml_Options_Tabs_Product_EntityIdGrid',
            'filter' => false,
        ));

        parent::_prepareColumns();
        $used_product_columns = array('entity_id_grid_checkbox', 'entity_id', 'name', 'custom_name', 'type');
        $columns = $this->getColumns();
        foreach ($columns AS $column_id =>$columns)
        {
            if (!in_array($column_id, $used_product_columns))
            {
                $this->removeColumn($column_id);
            }
        }
    }



}





