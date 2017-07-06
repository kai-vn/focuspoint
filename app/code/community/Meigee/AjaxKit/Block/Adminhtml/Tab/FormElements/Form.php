<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_Form
{
    protected $name;
    protected $element;
    protected $value;
    protected $default_value;

    function setElementName($name)
    {
        $this->name = $name;
        $this->id = 'AjaxKit-'.$name;
        return $this;
    }

    function setElement($element)
    {
        $this->element = $element;
        return $this;
    }

    function setElementDefaultValue($default_value)
    {
        $this->default_value = $default_value;
        return $this;
    }

    function setElementValue($value)
    {
        $this->value = $value;
        return $this;
    }

    function getBlockFormHtml()
    {
        $class = 'option AjaxKitOption AjaxKit-' . $this->element->type . '-element-type';

        $html = '';

        if ($this->element instanceof SimpleXMLElement && isset($this->element['delimiter']))
        {
            $html .= '<div><hr /></div>';
        }

        $html .= '<div class="option-wrapper"><div class="label">' . $this->element->label . '</div><div class="'.$class.'" id="'.$this->id.'-element" >';
        $html .= $this->getElementHtml();

        if (!empty($this->element->note))
        {
            $html .= '<div class="note">'.$this->element->note.'</div>';
        }

        if (is_array($this->value))
        {
            $val1 = (array)$this->default_value;
            $val2 = (array)$this->value;
            sort($val1);
            sort($val2);
            $checked = http_build_query($val1) == http_build_query($val2);
        }
        else
        {
            $checked = $this->default_value == $this->value;
        }
        $html .= '</div>';




        if (0 != Mage::app()->getStore()->getId())
        {
            $html .= '<div class="default-store">
                        <label><input type="checkbox" class="use-default-store-checkbox" name="default::'.$this->name.'" value="'.(int)$checked.'" ' . ($checked ? 'checked="checked"':'').' />'.Mage::helper('ajaxKit')->__('Use default').'</label>
                    </div>';
        }

        $html .= '</div>';

        return $html;
    }

}