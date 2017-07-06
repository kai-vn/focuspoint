<?php

class Meigee_Thememanager_Block_Widget_BlogListChecker extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = '';

        if (!Mage::helper('core')->isModuleEnabled('AW_Blog'))
        {
            $html .= '<div style="color: red;">'.Mage::helper('core')->__('AW_Blog is not installed in your store').'</div>';
            $html .= '<script type="text/javascript">
                    var widget_options_blog_last =  $("widget_options_blog_last");
                    $("widget_options_blog_last").select("tr").each(function(tr_el)
                    {
                        tr_el.hide();
                    })
                    var blog_check = $("widget_options_blog_last").select("tr[id^=row_options_fieldset][id$=_blog_check]");
                    blog_check[0].show();
            </script>';
        }
        return $html;
    }
}