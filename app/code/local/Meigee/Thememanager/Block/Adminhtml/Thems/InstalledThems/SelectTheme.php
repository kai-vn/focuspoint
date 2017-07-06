<?php
class Meigee_Thememanager_Block_Adminhtml_Thems_InstalledThems_SelectTheme extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $themes_html = '
        <input type="hidden" value="'.$this->getDeactivateUrl().'" id="DeactivationUrl" >
        <input type="hidden" value="'.$this->getActivateUrl().'" id="ActivationUrl" >';

        foreach ($this->getThems() AS $namesapce => $name)
        {
            if(in_array($namesapce, $this->getUsedThems()))
            {
                $themes_html .= '<div class="meigee-radio">
                                    <h3 class="theme-name"> ' . $name .' </h3>
                                <a href="' . $this->getUrl()."?theme=".$namesapce .'">
                                    <img src="'.Mage::getBaseUrl('media').'/' . $namesapce . '/preview.jpg" />
                                </a>
                                <button class="theme-configure" onClick="reloadTo(\''.$this->getUrl().'?theme='.$namesapce.'\'); return false;">' . Mage::helper('thememanager')->__('Configure') .'</button>';
                
                if (Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/edit'))
                {
		    $themes_html .= '<button class="theme-deactivate" onClick="deactivateTheme(\''.$namesapce.'\'); return false;">' . Mage::helper('thememanager')->__('Deactivate') .'</button>';
                }
                $themes_html .= '</div>';
            }
            else
            {
                $themes_html .= '<div class="meigee-radio">
                                    <h3 class="theme-name"> ' . $name .' </h3>
                                    <a href="#" onClick="activateTheme(\''.$namesapce.'\'); return false;" class="theme-deactivate">
                                        <img src="'.Mage::getBaseUrl('media').'/' . $namesapce . '/preview.jpg" />
                                    </a>
                                    <button class="theme-configure" onClick="activateTheme(\''.$namesapce.'\'); return false;">' . Mage::helper('thememanager')->__('Activate') .'</button>
                                </div>';
            }
        }
        $html = '<tr>';
        $html .= '<td>' . $this->getText() . '</td>';
        $html .= '<td>' . $themes_html . '</td>';
        $html .= '</tr>';
        return $html;
    }
}





