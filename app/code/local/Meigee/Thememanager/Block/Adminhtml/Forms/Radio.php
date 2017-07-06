<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Radio extends Meigee_Thememanager_Block_Adminhtml_Forms_Forms
{
    protected $prefix_html = array();
    protected $add_class = '';

    function getUsedValue($key, $value)
    {
        $option_value =  $value['value'];
        if (isset($this->params['use_key']) && $this->params['use_key'])
        {
            $option_value =  $key;
        }
        return $option_value;
    }

    public function getFormElement()
    {
        $input_html = '';
        foreach ($this->params['values'] AS $key=>$value)
        {
            $option_value = $this -> getUsedValue($key, $value);
            $checked = '';
            if ($option_value == $this->getConfigValue())
            {
                $checked = 'checked="checked"';
            }
            $prefix_html = isset($this->prefix_html[$option_value]) ? $this->prefix_html[$option_value] : '';
            $input_html .= '<div class="left a-center meigee-radio '.$this->add_class .'"><label class="inline">'.$prefix_html.'<input type="radio" '. $checked .' data-value="'.$option_value.'" value="'.$option_value.'" name="'.$this->getConfigId().'">'.(isset($value['name']) && !empty($value['name']) ? $value['name'] : '').'</label></div>';
        }
        $html = '<div class="block-wrapper">'.$input_html.'</div>';
        return $html;
    }
}
