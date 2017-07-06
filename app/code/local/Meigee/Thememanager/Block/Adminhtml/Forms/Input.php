<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Input extends Meigee_Thememanager_Block_Adminhtml_Forms_Forms
{
    protected $type = 'text';
    protected $element_params = array();
    protected $before_html = '';
    protected $after_html = '';

    public function getFormElement()
    {
        $this->element_params += array('type'=>$this->type, 'name'=>$this->getConfigId(), 'value'=>$this->getConfigValue());

        foreach ($this->element_params AS $name => $param)
        {
            $used_params[] = $name . '="'.$param.'"';
        }

        $html = $this->before_html . "<input ".implode(' ', $used_params).">" . $this->after_html;

        return $html;
    }
}
