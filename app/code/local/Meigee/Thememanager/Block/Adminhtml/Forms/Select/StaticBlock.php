<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Select_StaticBlock extends Meigee_Thememanager_Block_Adminhtml_Forms_Select
{
    public function getFormElement()
    {
        $this->can_be_empty = true;

        $collection = $this->getPagesCollection();
        $this->params['values'] = array();
        foreach ($collection AS $el)
        {
            $this->params['values'][] = array('value'=>$el->getIdentifier(), 'name'=> $el->getTitle());
        }
        return parent::getFormElement();
    }

    function getPagesCollection()
    {
//        $stores[] = 0;
//        $stores[] = Mage::helper('thememanager')->getStore();
        $model = Mage::getModel('cms/block');
        $collection = $model->getCollection();
        $collection->getSelect() -> join(   array('e'=>Mage::getSingleton('core/resource')->getTableName('cms/block_store')),
                'main_table.block_id = e.block_id '
            )->group('main_table.block_id');
        return $collection->load();
    }
}

