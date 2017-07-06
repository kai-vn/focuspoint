<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Select_Bool extends Meigee_Thememanager_Block_Adminhtml_Forms_Select
{
    public function getFormElement()
    {
        $collection = $this->getPagesCollection();
        $this->params['values'] = array(array('value'=>'__empty__', 'name'=> 'Disable'))+$this->params['values'];
        if ($collection)
        {
            foreach ($collection AS $el)
            {
                $this->params['values'][] = array('value'=>$el->getIdentifier(), 'name'=> $el->getTitle());
            }
        }

        return parent::getFormElement();
    }


}

