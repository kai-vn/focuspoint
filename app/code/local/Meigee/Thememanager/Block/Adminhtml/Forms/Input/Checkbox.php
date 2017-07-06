<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Input_Checkbox extends Meigee_Thememanager_Block_Adminhtml_Forms_Input
{
    public function getFormElement()
    {
        $this->type = 'hidden';
        $value = $this->getConfigValue();
        $this->element_params += array('class'=>'_input_checkbox_value thememanagerFormSelect', 'data-value'=>$this->params['data_values'], 'value'=>$value);
        $checked = '__empty__' != $value ? 'checked="checked"' : '' ;
        $id = 'check-'.$this->getConfigId();
        $html = '<div class="input_checkbox">';
        $html .= parent::getFormElement();
        $html .= '<div class="slide pull-right">
                    <input type="checkbox"  id="'.$id.'" '.$checked.' onchange="setInputCheckboxChanged(this);" class="thememanagerFormSelect" value="'.$value.'" />
                    <label for="'.$id.'" class="slider">
                    <label for="'.$id.'" class="display on">Display</label>
                    <span class="switch"></span>
                    <label for="'.$id.'" class="display off">Hide</label>
                  </label>
                </div>
                </div>
                ';
        return $html;
    }
}
