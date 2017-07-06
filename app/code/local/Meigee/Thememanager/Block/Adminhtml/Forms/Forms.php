<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Forms extends Varien_Data_Form_Element_Abstract
{
    protected $params;
    function _construct()
    {
        parent::_construct();
        $this->params = $this->getParam();
    }
    public function getElementHtml()
    {
        $hided = false;
        if (isset($this->params['depends']))
        {
            $helper = Mage::helper('thememanager/themeConfig');
            $config = $helper->getThemeConfigByAliases();

            $hided = true;
            foreach ($this->params['depends'] AS $parent => $keys)
            {
                $keys = (array)$keys;
                if(isset($config[$parent]))
                {
                    foreach ($keys AS $key)
                    {
                        $config_data = $helper->getThemeConfigByAliase($parent);
                        if (isset($config[$parent]['values'][$key]) && !($config[$parent]['values'][$key]['value'] == $config_data['result']))
                        {
                            $hided = false;
                            break 2;
                        }
                    }
                }
            }
        }
        $row_id = 'alias-id-'.$this->getConfigId();
        $adminhtml_class = isset($this->params['adminhtml_class']) ? $this->params['adminhtml_class'] :  '';

        $html = '<div class="config_form '.$adminhtml_class.'">';
        $html .= '<div class="label">'.$this->getLabelHtml().'</div>';

        if ($this->getConfigLabel())
        {
            $html .= '<div class="form_label">'.$this->getConfigLabel().'</div>';
        }


        if ($this->getIsShowOptionOrder())
        {
            $html .= '<div class="form_order"><input type="number" name="_subValue_'. $this->getConfigId() .'_order" value="'. $this->getOptionOrder() .'" /></div>';
        }


        $html .='<input type="hidden" class="tr_attributes" data-id="' . $row_id . '"  data-data_configId="' . $this->getConfigId() . '" />';
        $html .= '<div class="form_element">'.$this->getFormElement();
		$html .= '<div class="form_note">'.((isset($this->params['note']) && !empty($this->params['note'])) ? '<p class="note"><span>'.$this->params['note'].'</span></p>' : '').'</div>';
		$html .= '</div>';

        $html .= '<div class="form_description">'.(isset($this->params['description'])?$this->params['description']:'') . '</div>';
        $html .= '<div class="extends-wrapper"><span class="extends">['.$this->getExtends() . ']</span></div>';
        $html .= '</div>';
//        $html .= '</tr>';
        return $html;
    }
}


