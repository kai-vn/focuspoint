<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Select extends Meigee_Thememanager_Block_Adminhtml_Forms_Forms
{
    protected $can_be_empty = false;

    public function getFormElement()
    {
        if ($this->can_be_empty)
        {
            $this->params['values'] = array(array('values'=>'__empty__', 'name'=>'None')) + $this->params['values'];
        }

        $attributtes = array();
        $config_value_data = $this->getConfigValue();

        $result_type = '';
        $config_value = $config_value_data;
        $class = array("thememanagerFormSelect");

        if (isset($this->params['multi']) && $this->params['multi'])
        {
            $attributtes[] = 'multiple size="'.$this->params['multi'].'"';
            $result_type = '[]';

            $class[] = 'multi-select';


            $config_value = array();
            if (!empty($config_value_data))
            {
                if (is_array($config_value_data))
                {
                    $config_value = $config_value_data;
                }
                else
                {
                    $unserialized  =@unserialize($config_value_data);
                    $config_value =  false !== $unserialized  ? $unserialized  : $config_value_data;
                }
            }
        }

        $select_html = '<select name="'.$this->getConfigId().$result_type.'" class="'.implode(' ', $class).'" '.implode(' ', $attributtes).' >';
        foreach ($this->params['values'] AS $key=>$value)
        {
            $option_value =  isset($value['value']) ? $value['value'] : '';
            $selected = '';
            if (isset($this->params['use_key']) && $this->params['use_key'])
            {
                $option_value =  $key;
            }

            if (is_array($config_value))
            {
                $selected = in_array($option_value, $config_value) ? 'selected="selected"' : '';
            }
            else
            {
                $selected = $option_value == $config_value ? 'selected="selected"' : '';
            }
            $name = isset($value['name']) ? $value['name'] : '';
            $select_html .= '<option value="'.$option_value.'" '. $selected .' data-key="'.$key.'">'.$name.'</option>';
        }
        $select_html .= '</select>';
        $html = $select_html;
        return $html;
    }
}
