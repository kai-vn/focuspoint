<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList_Grid extends Mage_Adminhtml_Block_Widget_Grid
{



    protected function _prepareCollection()
    {

        $this->setPagerVisibility(false);
        $this->setFilterVisibility(false);

        $data_collection = Mage::getModel('thememanager/themes')->getThemesCollection();
        $data_collection_arr = array();

        foreach ($data_collection AS $data_row)
        {
            $row_key = $data_row->getStoreId() . '-' . $data_row->getTypeOrder(). '-' . $data_row->getType(). '-' . $data_row->getId();
            $data_collection_arr[$row_key] = $data_row;
        }

        ksort($data_collection_arr);
        $collection = new Varien_Data_Collection();
        foreach ($data_collection_arr AS $row)
        {
            $collection->addItem($row);
        }
        $this->setCollection($collection);
        $this->setFilterVisibility(false);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $helper = Mage::helper('thememanager');
        $this->addColumn('name', array(
            'header' => $helper->__('Name'),
            'index' => 'name',
            'type' => 'text',
            'filter' => false,
            'sortable' => false,
            'width' => '60%',
        ));

        $this->addColumn('store', array(
            'header' => $helper->__('Store'),
            'index' => 'store',
            'renderer'  => 'Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Store',
            'type' => 'text',
            'filter' => false,
            'sortable' => false,
            'width' => '40%',
        ));

        $this->addColumn('type', array(
            'header' => $helper->__('Type'),
            'index' => 'type',
            'type' => 'text',
            'filter' => false,
            'sortable' => false,
            'width' => '70px',
        ));

        $this->addColumn('add_date', array(
            'header' => $helper->__('Add Date'),
            'index' => 'add_date',
            'renderer'  => 'Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_DateAdd',
            'type' => 'text',
            'filter' => false,
            'sortable' => false,
            'width' => '125px',
        ));

        $this->addColumn('last_modified_date', array(
            'header' => $helper->__('Last modification date'),
            'index' => 'last_modified_date',
            'renderer'  => 'Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_DateLastModified',
            'type' => 'text',
            'filter' => false,
            'sortable' => false,
            'width' => '125px',
        ));

        if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/add_or_clone'))
        {
            $this->addColumn('Clone',
                array(
                    'header' => Mage::helper('catalog')->__('Clone'),
                    'index' => 'clone',
                    'renderer' => 'Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Clone',
                    'width' => '70px',
                    'filter' => false,
                    'sortable' => false,
                ));
        }

        if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/remove'))
        {
            $this->addColumn('Remove',
                array(
                    'header'=> Mage::helper('catalog')->__('Remove'),
                    'index' => 'remove',
                    'renderer'  => 'Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Remove',
                    'width' => '70px',
                    'filter' => false,
                    'sortable' => false,
                ));

            $this->addColumn('Export',
                array(
                    'header'=> Mage::helper('catalog')->__('Export'),
                    'index' => 'export',
                    'renderer'  => 'Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Export',
                    'width' => '70px',
                    'filter' => false,
                    'sortable' => false,
                ));
        }

        $this->addColumn('Edit',
            array(
                'header'=> (Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/edit')) ? Mage::helper('catalog')->__('Edit') : Mage::helper('catalog')->__('View'),
                'index' => 'edit',
                'renderer'  => 'Meigee_Thememanager_Block_Adminhtml_ConfigList_Renderer_Edit',
                'width' => '70px',
                'filter' => false,
                'sortable' => false,
            ));


        return parent::_prepareColumns();
    }

    public function getRowUrl($item)
    {
        return 'javascript:void(0);';
    }

}