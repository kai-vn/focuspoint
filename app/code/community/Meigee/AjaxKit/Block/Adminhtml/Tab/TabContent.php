<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_TabContent extends Mage_Adminhtml_Block_Widget_Form
{
    private $html_elements;
    private $depends = array();

    function _toHtml()
    {
        $submodules = Mage::getModel('ajaxKit/configsReader')->getConfigOptions();
        $submodule_tabs = Mage::getModel('ajaxKit/configsReader')->getConfigGroups();
        $namespace = $this->getRequest()->getParam('namespace');

        if (!isset($submodules[$namespace]))
        {
            return false;
        }
        $used_submodule = $submodules[$namespace];

        if (isset($used_submodule['base']))
        {
            $this->buildForm($used_submodule['base']);
        }
        if (isset($used_submodule['extended']))
        {
            $this->buildForm($used_submodule['extended']);
        }

        $this->buildFormTabs($submodule_tabs);

        $adminhtml_config = (array)Mage::getModel('ajaxKit/configsReader')->getConfigAdminhtml();
        $adminhtml_config = (isset($adminhtml_config[$namespace]['extended']) ? (array)$adminhtml_config[$namespace]['extended'] : array()) + (array)$adminhtml_config[$namespace]['base'];
        return Mage::app()->getLayout()->getBlockSingleton('ajaxKit/adminhtml_tab_formElements_VerticalTabsBuilder')
                            ->setContent($this->html_elements)
                            ->setPreviewClass(isset($adminhtml_config['preview_class'])? $adminhtml_config['preview_class']:false)
                            ->getHtml();
    }

    private function buildFormTabs($submodule_tabs)
    {
        $namespace = $this->getRequest()->getParam('namespace');
        $configsReader = Mage::getModel('ajaxKit/configsReader');
        $element_name = 'ajaxkit-system__status___';

        $status_el = (object)array(
                                    'label'=> 'Module Status'
                                    , 'type'=> 'Input_OnOff'
                                    , 'default'=> 1
                                    , 'label_on'=> 'Enable'
                                    , 'label_off'=> 'Disable'
                                );

        $value = $configsReader->getConfigStatus($namespace);
        $value_default = $configsReader->getConfigStatus($namespace, true);

        $this->html_elements['status'] = Mage::app()->getLayout()->getBlockSingleton('ajaxKit/adminhtml_tab_formElements_Input_OnOff')
            ->setElementName($element_name.$namespace)
            ->setElement($status_el)
            ->setElementValue($value)
            ->setElementDefaultValue($value_default)
            ->setAttributes('data-status', 1)
            ->getBlockFormHtml();

        if (isset($submodule_tabs[$namespace]))
        {
            $tabs = $submodule_tabs[$namespace] + array('extended'=>array(), 'base'=>array());
            $tabs = (array)$tabs['extended'] + (array)$tabs['base'];
            $this->html_elements['main'] = Mage::app()->getLayout()->getBlockSingleton('ajaxKit/adminhtml_tab_formElements_HorizontalTabs')
                ->setDataInfo($tabs)
                ->setDataHtmlElements($this->html_elements)
                ->getBlockHorizontalTabsHtml();

            foreach($tabs AS $name=>$tab)
            {
                $this->html_elements['tabs'][$name]['preview'] = '';
                if (!empty($tab->preview_class) && class_exists('Meigee_AjaxKit_Block_Adminhtml_'.$tab->preview_class))
                {
                    $this->html_elements['tabs'][$name]['preview'] = Mage::app()->getLayout()->getBlockSingleton('ajaxKit/adminhtml_'.$tab->preview_class)->getPreview();
                }
                $this->html_elements['tabs'][$name]['descr_top'] = !empty($tab->descr_top) ? $tab->descr_top : '';
                $this->html_elements['tabs'][$name]['descr_bottom'] = !empty($tab->descr_bottom) ? $tab->descr_bottom : '';
            }
        }
    }

    private function buildForm($element_arr)
    {
        $element_arr = (array)$element_arr;
        $namespace = $this->getRequest()->getParam('namespace');
        $configsReader = Mage::getModel('ajaxKit/configsReader');

        foreach ($element_arr AS $el_name => $el)
        {
            if (!empty($el->type) && class_exists('Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_'.$el->type))
            {
                $value = $configsReader->getConfigValue($namespace, $el_name);
                $default_value = $configsReader->getConfigValue($namespace, $el_name, true);

                $element_tab = isset($el['tab']) ? (string)$el['tab'] : '_default';
                $this->html_elements['tabs'][$element_tab]['elements'][] = Mage::app()->getLayout()->getBlockSingleton('ajaxKit/adminhtml_tab_formElements_'.lcfirst((string)$el->type))
                                ->setElementName($el_name)
                                ->setElement($el)
                                ->setElementValue($value)
                                ->setElementDefaultValue($default_value)
                                ->getBlockFormHtml();

                if (!empty($el->depend))
                {
                    $depend_arr = (array)$el->depend;
                    foreach($depend_arr AS $name=>$depend)
                    {
                        $this->depends[$el_name][$name] = explode(",", str_replace(' ', '', (string)$depend));
                    }
                }
            }
        }
    }

    function getDepends()
    {
        return $this->depends;
    }
}