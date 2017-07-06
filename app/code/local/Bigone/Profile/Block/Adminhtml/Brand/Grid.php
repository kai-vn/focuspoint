<?php

class Bigone_Profile_Block_Adminhtml_Brand_Grid extends Mage_Adminhtml_Block_Widget_Grid {

    public function __construct() {
        parent::__construct();

        $this->setId('profileBrandId');
        $this->setDefaultSort('brand_id');
        $this->setDefaultDir(Varien_Data_Collection::SORT_ORDER_ASC);
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _getHelper() {
        return Mage::helper('profile');
    }

    protected function _prepareCollection() {
        $collection = Mage::getModel('profile/brand')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function getStoreId() {
        return Mage::registry('store_id');
    }

    protected function _prepareColumns() {
        $helper = $this->_getHelper();

        $this->addColumn('brand_id', array(
            'header' => $helper->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'brand_id',
        ));

        $this->addColumn('title', array(
            'header' => $helper->__('Title'),
            'index' => 'title',
            'align' => 'left',
        ));

        $this->addColumn('logo', array(
            'header' => $helper->__('Logo'),
            'index' => 'logo',
            'renderer' =>'Bigone_Profile_Block_Adminhtml_Renderer_Image'
        ));

        $this->addColumn('sort_order', array(
            'header' => $helper->__('Sort order'),
            'index' => 'sort_order',
            'align' => 'center',
            'width' => '150px',
        ));

        $this->addColumn('status', array(
            'header' => $helper->__('Status'),
            'index' => 'status',
            'type' => 'options',
            'options' => Mage::getSingleton('profile/status')->getOptionArray(),
            'align' => 'center',
            'width' => '100px',
        ));

        $this->addColumn('action', array(
            'header' => $helper->__('Action'),
            'width' => '100',
            'type' => 'action',
            'getter' => 'getId',
            'actions' => array(
                array(
                    'caption' => $helper->__('Delete'),
                    'url' => array('base' => '*/*/delete'),
                    'field' => 'id'
                )),
            'filter' => false,
            'sortable' => false,
            'index' => 'stores',
            'is_system' => true,
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction() {
        $helper = $this->_getHelper();
        $this->setMassactionIdField('brand_id');
        $this->getMassactionBlock()->setFormFieldName('brandForm');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $helper->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => $helper->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('profile/status')->getOptionArray();

        array_unshift($statuses, array('label' => '', 'value' => ''));
        $this->getMassactionBlock()->addItem('status', array(
            'label' => $helper->__('Change status'),
            'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
            'additional' => array(
                'visibility' => array(
                    'name' => 'status',
                    'type' => 'select',
                    'class' => 'required-entry',
                    'label' => $helper->__('Status'),
                    'values' => $statuses
                ))
        ));
        return $this;
    }

    public function getRowUrl($row) {
        return $this->getUrl('*/*/edit', array('id' => $row->getBrandId(), 'store' => $this->getStoreId()));
    }

    public function getGridUrl() {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

}
