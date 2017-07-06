<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input_RadioIco extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input
{
    protected $type = 'radio';
    protected $is_vertical = false;


    function getElementHtml()
    {
        $html = '';
  //      $this->setAttributes('name', (string)$this->element->element_property->max);

        $vertical_class = $this->is_vertical ? 'vertical' : 'left';

        foreach($this->element->values->value AS $value)
        {
            $src = Mage::getDesign()->getSkinUrl("ajaxkit/images/".(string)$value['data'].'.png', array('_default'=>true));

            $val = (string)$value['data'];
            $this->attributes['checked'] = $val == $this->value ? 'checked' : '';
            $this->attributes['value'] = $val;
            $this->attributes['id'] = $this->id . '-' . $val;


            $html .= '<div class="a-center meigee-radio '.$vertical_class.'">
                            <label class="inline">
                                <div class="meigee-thumb '.($this->attributes['checked'] ? "active" : '').'">
                                    <img src="'.$src.'">
                                </div>
                                '.parent::getElementHtml() . $val.'
                            </label>
                        </div>';
        }
        $html .= '</select>';
        return $html;
    }
}
