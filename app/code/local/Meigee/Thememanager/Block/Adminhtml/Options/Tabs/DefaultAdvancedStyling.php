<?php
class Meigee_Thememanager_Block_Adminhtml_Options_Tabs_DefaultAdvancedStyling extends Mage_Adminhtml_Block_Widget_Form
{
    function prepareLayout()
    {
        $helper = Mage::helper('thememanager/themeConfig');
        $sorted_advanced_styling = $helper->getAdvancedStyling(true);

        $used_config = Mage::getModel('thememanager/themes')->getUsedTheme();

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save'),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $fieldset = $form->addFieldset('default_advanced_styling_fieldset', array(
            'legend'    => $this->__('Advanced Styling'),
            'class'     => 'fieldset-wide',
            'expanded'  => false,
        ));

/*
        $fieldset->addField('comment', 'hidden', array(

            'after_element_html'  => 'Custom css will be created after settings will be saved under Appearance admin section',
        ));
*/
        $predefined_css_name = $helper->__('Predefined CSS');
        $custom_css_name = $helper->__('Custom CSS');

        $select_css_arr[] = array('label'=>$helper->__('Select CSS'), 'value'=>'');
        $as_predefined_css = (array)$sorted_advanced_styling->advanced_styling_predefined_css;

//        $as_predefined_css_file_arr = array();
//        foreach($as_predefined_css['file'] AS $file_data)
//        {
//            $as_predefined_css_file_arr[] = (array)$file_data;
//        }
//
//        $select_css_arr[] = array('label'=>$predefined_css_name, 'value'=>$as_predefined_css_file_arr);
        $select_css_arr[] = array('label'=>$custom_css_name, 'value'=>Mage::getModel('thememanager/advancedStyling')->getAdvancedStylingCustomCssFilesList());

        $file_name = Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase('advanced_styling_custom_css_file');

        $fieldset->addField('select_css', 'select', array(
            'label' => $helper->__('Select CSS File'),
            'name' => 'advanced_styling_custom_css_file',
            'values' => $select_css_arr,
            'value' => $file_name,
            'onchange' => 'getCssFileContent(this);'
        ));

        $fieldset->addField('predefined_css_name', 'hidden', array(
            'value' => $predefined_css_name,
        ));

        $fieldset->addField('custom_css_name', 'hidden', array(
            'value' => $custom_css_name,
        ));


        $fieldset->addField('CssFileAjaxUrl', 'hidden', array(
            'value' => $this->getUrl('*/*/getAdvancedStylingCssFile', array('theme_id'=>$this->getRequest()->getParam('theme_id'))),
        ));

        $css_file_content = Mage::getModel('thememanager/advancedStyling')->getAdvancedStylingCssFileContent($file_name);
        $fieldset->addField('css_previev', 'textarea', array(
            'label' => $helper->__('CSS'),
            'value' => $css_file_content,
            'onchange' => 'AdvancedStylingTextareaChanged()',
        ));

        $fieldset->addField('AdvancedStylingChanged', 'hidden', array(
            'name' => 'AdvancedStylingChanged',
            'value' => 0,
        ));


        $fieldset->addField('css_editor', 'button', array(
            'value' => $helper->__('CSS Editor'),
            'class' => 'fa',
            'onclick' => 'CssEditor.showContainer(); return false;',
        ));


        $fieldset->addField('css_file_name', 'text', array(
            'label' => $helper->__('CSS File Name'),
            'onchange' => 'checkCssFileName(this); return false;',
            'value' => $file_name,
            'note' => '<span id="css_file_name_already_exist_text" class="hided_element">'.$helper->__('The file is already exist. (Wil be replased After generation).').'</span>'
                        .'<span id="predefined_css_file_name_already_exist_text" class="hided_element">'.$helper->__('The predefined CSS file can\'t be replaced. (Please rename CSS File)').'</span>',

        ));


        if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/edit'))
        {
            $fieldset->addField('css_generator', 'button', array(
                'value' => $helper->__('Generate CSS'),
                'class' => 'fa',
                'onclick' => 'generateCss(); return false;',
            ));
        }

        $style = (array)$sorted_advanced_styling->advanced_styling;

        $fieldset->addType('AdvancedStyling','Meigee_Thememanager_Block_Adminhtml_Forms_AdvancedStyling');
        $fieldset->addField('customize_css_editor', 'AdvancedStyling', array(
            'advanced_styling' => $style['style'],
            'store_id' => $used_config->getStoreId(),
            'static_advanced_styling' => $sorted_advanced_styling->static_advanced_styling,
            'pattern_images' => array_merge(
                                                Mage::getModel('thememanager/advancedStyling')->getUploadedBackgroundPatternImages(),
                                                Mage::getModel('thememanager/advancedStyling')->getBackgroundPatternImages()
                                        )
        ));

        $fieldset->addType('data_js','Meigee_Thememanager_Block_Adminhtml_Forms_SetJs');
        $fieldset->addField('data_js', 'data_js', array(
//            'hide_rows_array' => $sorted_advanced_styling['hide_rows'],
//            'is_check_custom_style' => true,
        ));

        $form->setUseContainer(false);
        $this->setForm($form);
        return parent::_prepareLayout();
    }
}











