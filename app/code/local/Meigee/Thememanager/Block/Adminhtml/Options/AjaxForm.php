<?php

class Meigee_Thememanager_Block_Adminhtml_Options_AjaxForm extends Mage_Adminhtml_Block_Widget_Form
{
    function prepareLayout($config_arr)
    {
        $helper = Mage::helper('thememanager/themeConfig');
        $depends = array();
        $js_tabs = array();
        $fieldset = false;

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $params = Mage::app()->getRequest()->getParams();
        if (isset($params['group_namespace']) && isset($params['block_namespace']))
        {
            $group_namespace = $params['group_namespace'];
            $block_namespace = $params['block_namespace'];

            if (isset($config_arr[$group_namespace]) && isset($config_arr[$group_namespace]['blocks'][$block_namespace]))
            {
                if (isset($config_arr[$group_namespace]['blocks'][$block_namespace]['preview']))
                {
                    $preview = $config_arr[$group_namespace]['blocks'][$block_namespace]['preview'];
                    $fieldset_id = $group_namespace . '_' . $block_namespace . "_Preview";

                    $title = $preview['title'];
                    $class = $preview['class'];
                    $fieldset = $form->addFieldset($fieldset_id, array(
                        'legend'    => $this->__($title),
                        'class'     => 'fieldset-wide, collapseable block_preview',
                        'expanded'  => false,
                    ));

                    $fieldset->addType('preview_html',$class);
                    $fieldset->addField('preview_html', 'preview_html', array(
                        'preview' => $preview,
                    ));
                }

                foreach($config_arr[$group_namespace]['blocks'][$block_namespace]['params'] AS $params => $used_config)
                {
                    $fieldset_id = $params.'_fieldset';
                    $js_tabs[] = $fieldset_id;

                    $fieldset = $form->addFieldset($fieldset_id, array(
                        'legend'    => $this->__(isset($used_config['pTitle']) ? $used_config['pTitle'] : ''),
                        'class'     => 'fieldset-wide, collapseable',
                        'expanded'  => false,
                    ));
                    $fieldset->addType('data_description','Meigee_Thememanager_Block_Adminhtml_Forms_Datadescription');

                    if (!empty($used_config['pDescription']))
                    {
                        $fieldset->addField($params.'_data_description', 'data_description', array(
                            'text'     => $helper->__($used_config['pDescription']),
                        ));
                    }
                    $form_sets = array();


                    foreach ($used_config['elements'] AS $elements_namespace => $element)
                    {
                        if (!isset($element['type_adminhtml']))
                        {
                            continue;
                        }

                        $form_name = 'Meigee_Thememanager_Block_Adminhtml_Forms_'.$element['type_adminhtml'];
                        if (!isset($form_sets[$form_name]))
                        {
                            $form_sets[$form_name] = false;
                            if (class_exists($form_name))
                            {
                                $form_sets[$form_name] = 'data_'.$element['type_adminhtml'];
                                $fieldset->addType($form_sets[$form_name],$form_name);
                            }
                        }

                        if ($form_sets[$form_name])
                        {
                            $config_value = $helper->getThemeConfig($elements_namespace, true);
                            $fieldset->addField($params.$elements_namespace, $form_sets[$form_name], array(
                                'label' => null,
                                'config_label' => $element['title'],
                                'param' => $element,
                                'config_id' => $elements_namespace,
                                'config_value' => $config_value['value'],
                                'extends' => $config_value['type'],
                            ));

                            if (isset($element['depends']))
                            {
                                foreach ($element['depends'] AS $depend_name => $depend)
                                {
                                    $depends[$fieldset_id][$elements_namespace][$depend_name] = (array)$depend;
                                }
                            }
                        }
                    }
                }
            }
            if ($fieldset)
            {
                $fieldset->addType('data_depends','Meigee_Thememanager_Block_Adminhtml_Forms_SetJs');
                $fieldset->addField('data_depends', 'data_depends', array(
                    'depends_array' => $depends,
                    'update_tabs' => $js_tabs,
                ));
            }
        }
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareLayout();
    }


    function setConfigArr($config_arr)
    {
        return $this->prepareLayout($config_arr);
    }



}