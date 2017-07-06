<?php
class Meigee_Thememanager_Block_Adminhtml_Options_Tabs_CmsPageTable extends Mage_Adminhtml_Block_Cms_Page_Grid
{
    private $used_store_id = false;
    private $uri = '*/*/getCmsPageTable';

    public function __construct()
    {
        parent::__construct();
    }


    public function setUsedStoreId($used_store_id)
    {
        $this->used_store_id = $used_store_id;
        return $this;
    }
    function setUri($uri)
    {
        $this->uri = $uri;
        return $this;
    }


    public function getGridUrl()
    {
        return $this->getUrl($this->uri, array('_current'=>true));
//        return $this->getUrl('*/*/getCmsPageTable', array('_current'=>true));
    }

    public function getRowUrl($row)
    {
        //return "#";
    }

    protected function _prepareCollection()
    {
        parent::_prepareCollection();

        if ($this->used_store_id === false)
        {
            $theme_id = (int)Mage::app()->getRequest()->getParam('theme_id');
            $theme_config_data = Mage::getModel('thememanager/themes')->load($theme_id);
            $store_id = $theme_config_data->getStoreId();
        }
        else
        {
            $store_id = (int)$this->used_store_id;
        }

        $collection = $this->getCollection()->addStoreFilter($store_id);
        $this->setCollection($collection);
    }


    protected function _prepareColumns()
    {
        $helper = Mage::helper('thememanager');

        $this->addColumn('entity_id_grid_checkbox', array(
            'header' => $helper->__(''),
            'width' => 20,
            'renderer'  => 'Meigee_Thememanager_Block_Adminhtml_Options_Tabs_CmsPageTable_EntityIdGrid',
            'filter' => false,
        ));

        parent::_prepareColumns();
        $used_product_columns = array('entity_id_grid_checkbox', 'entity_id', 'title', 'identifier', 'is_active');
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





