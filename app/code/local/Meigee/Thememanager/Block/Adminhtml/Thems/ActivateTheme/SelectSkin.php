<?php
class Meigee_Thememanager_Block_Adminhtml_Thems_ActivateTheme_Selectskin extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $skins_hidden = count($this->getSkins()) == 1;


        $html = '<div id="install-skin" '.($skins_hidden ? 'style="display: none;"' : '').'>';
        foreach ($this->getSkins() AS $skin => $skinData)
        {
            $use_extensions = (int)isset($skinData['install']) && isset($skinData['install']['store_extension_configs']);
            $html .= '<div class="meigee-radio">
                            <label>
                                    <h3 class="skin-name"> ' . $skinData['name'] .' </h3>
                                    <img src="'.Mage::getBaseUrl('media') . $this->getNamesapce() . '/'.$skin.'.jpg" />
                                    <input type="radio" name="skin" use_extensions="'.$use_extensions.'" value="'.$skin.'" '.($skins_hidden ? ' checked="checked"' : '').' />
                            </label>
                       </div>';
        }
        $html .= '</div>';
        $html .= '<div id="advice-required-entry-skin" class="validation-advice" style="display: none;">' . Mage::helper('thememanager')->__('Please select at least one theme') .'</div>';
        return $html;
    }
}


