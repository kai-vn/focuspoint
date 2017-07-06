<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Input extends Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Form
{
    protected $type = '';
    protected $attributes = array();

    function setAttributes($name, $value)
    {
        if (isset($this->attributes[$name]))
        {
            $this->attributes[$name] = ' ' . $value;
        }
        else
        {
            $this->attributes[$name] = $value;
        }
        return $this;
    }

    function getElementHtml()
    {
        $attributes = '';
        $this->attributes['type'] = $this->type;

     //   if(!isset($this->attributes['id']))
        {
            $this->attributes['id'] = $this->id;
        }

        $this->setAttributes('name', $this->name);

        foreach ($this->attributes AS $name => $val)
        {
            $val = trim($val);
            $attributes .= ' ' . $name . ' = "'.$val.'"';
        }
        $this->attributes = array();
        return '<input'.$attributes .' >';
    }
}