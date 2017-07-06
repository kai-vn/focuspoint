<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Select_StaticBlock extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Select
{
    function getElementHtml()
    {
        $collection = $this->getPagesCollection();
        $this->params['values'] = array();
        $this->opt = array();

        if ($this->element instanceof SimpleXMLElement && !(int)$this->element->type['hide_empty'])
        {
            $this->opt[] = array('value'=>'', 'option'=>Mage::helper('ajaxKit')->__('Select static block'));
        }
        foreach ($collection AS $el)
        {
            $this->opt[] = array('value'=>$el->getIdentifier(), 'option'=> $el->getTitle());
        }
        return parent::buildHtml();
    }
    function getPagesCollection()
    {
        $stores[] = 0;
        $stores[] = (int)Mage::app()->getRequest()->getParam('store');
        $model = Mage::getModel('cms/block');
        $collection = $model->getCollection();
        $collection->getSelect() -> join(   array('e'=>Mage::getSingleton('core/resource')->getTableName('cms/block_store')),
            'main_table.block_id = e.block_id and e.store_id IN ('.implode(',', $stores).')'
        )->group('main_table.block_id');
        return $collection->load();
    }

}