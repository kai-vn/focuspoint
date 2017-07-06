<?php

class Meigee_Thememanager_Block_Adminhtml_Thems_ActivateTheme_StaticConflicts extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $helper = Mage::helper('thememanager');
        $theme = Mage::app()->getRequest()->getParam('theme');

        $form = new Varien_Data_Form(array(
            'method' => 'post',
            'name' => 'form_data',
            'action' => $this->getUrl('*/*/installSkin'),
            'onsubmit' => 'return installCheckedConflictsSkin();',
            'enctype' => 'multipart/form-data'
        ));

        $action_LeaveAsIs = array('value'=>'LeaveAsIs',  'label'=>$helper->__('Leave as is')) ;
        $action_Overwrite = array('value'=>'Overwrite',  'label'=>$helper->__('Overwrite'));
        $action_Create = array('value'=>'Create',  'label'=>$helper->__('Create static block for specific store'));
        $action_PS = array('value'=>'',  'label'=>$helper->__('Please select'));

        $types = array(
            'block-store' => array('key'=>'block', 'descr'=>$helper->__('Such blocks were already created for selected store'), 'actions'=>array($action_PS, $action_LeaveAsIs, $action_Overwrite))
            , 'block-all' => array('key'=>'block', 'descr'=>$helper->__('Such static blocks were already created and assigned for all store views'), 'actions'=>array($action_PS, $action_LeaveAsIs, $action_Overwrite, $action_Create))
            , 'page' => array('key'=>'page', 'descr'=>$helper->__('Such pages were already created for selected store'), 'actions'=>array($action_PS, $action_LeaveAsIs, $action_Overwrite))
        );


        $form->addField('activationNote', 'note', array(
          'text'     => $helper->__('Activation Options'),
          'class'   => 'activation-title'
        ));
        $form->addField('submit1', 'submit', array(
            'required'  => true,
            'value'  => $this->__('Activate Theme'),
            'class'  => 'install-demo-btn',
            'tabindex' => 1
        ));

        foreach ($this->getStaticConflicts() AS $store_id => $conflicts)
        {
            $store = Mage::getModel('core/store')->load($store_id);
            $website = Mage::app()->getWebsite($store->getWebsiteId());
            $website_store_name = $website->getName() .' :: '. $store->getName();

            $fieldset = $form->addFieldset('store_fieldset_'.$store_id, array('legend'=>$helper->__('Conflicts ( %s )', $website_store_name)));

            foreach($types AS $type => $type_data)
            {
                if (!isset($conflicts[$type]))
                {
                    continue;
                }
                $fieldset->addField('label_block_store-'.$store_id.'-'. $type, 'label', array(
                    'value'     => $type_data['descr']
                ));

                foreach ($conflicts[$type] AS $identifier => $title)
                {
                    $name = $store_id . '::' . $type_data['key'] . "::" . $identifier;
                    $fieldset->addField($name, 'select', array(
                        'label'     => $title . " (".$identifier.")",
                        'class'     => 'required-entry',
                        'required'  => true,
                        'name'      => $name,
                        'value'  => '0',
                        'values' => $type_data['actions'],
                    ));
                }
            }
        }

        $fieldset->addField('theme', 'hidden', array(
            'name'      => 'theme',
            'value'  =>  $theme
        ));

        $fieldset->addField('skin', 'hidden', array(
            'name'      => 'skin',
            'value'  =>  Mage::app()->getRequest()->getParam('skin')
        ));

        $fieldset->addField('stores', 'hidden', array(
            'name'      => 'stores',
            'value'  =>  implode('|', (array)Mage::app()->getRequest()->getParam('stores'))
        ));

        $fieldset->addField('conflicts_fixed', 'hidden', array(
            'name'      => 'conflicts_fixed',
            'value'  =>  1
        ));

        foreach ($this->getInstalledStoreActions() AS $action_name => $action_value)
        {
            $fieldset->addField($action_name, 'hidden', array(
                'name'      => $action_name,
                'value'  =>  $action_value
            ));
        }
        $form->addField('submit2', 'submit', array(
            'required'  => true,
            'value'  => $this->__('Activate Theme'),
            'class'  => 'install-demo-btn bottom',
            'tabindex' => 1
        ));

        $form->setUseContainer(true);
        $form->setId('install_form');
        $this->setForm($form);

        return parent::_prepareForm();
    }


}