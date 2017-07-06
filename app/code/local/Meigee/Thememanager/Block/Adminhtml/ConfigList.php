
<?php

class Meigee_Thememanager_Block_Adminhtml_ConfigList extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    function __construct()
    {
        parent::__construct();
        $helper = Mage::helper('thememanager');
        $namespace = $helper-> getThemeNamespace();
        $installed_thems = $helper->getInstalledThems();
        $installed_theme =  $installed_thems[$namespace];

        $this->_blockGroup = 'thememanager';
        $this->_controller = 'adminhtml_configList';
        $this->_headerText = $helper->__('%s - Thememanager', $installed_theme);

        $this->_addButtonLabel = '<i class="fa fa-plus"></i>'.$helper->__('Add Config for Pages');
        $this->removeButton('add');



        if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/add_or_clone'))
        {
            $this->addButton('installDemo', array(
                'label'     => '<i class="fa fa-magic"></i>' . $helper->__('Apply Theme for Stores'),
                'onclick'   => 'activateTheme(\''.$namespace.'\',\''.$this->getUrl('*/*/activateTheme').'\')',
                'class'     => 'install-demo',
            ));


            $this->addButton('add', array(
                'label'     => $this->getAddButtonLabel(),
                'onclick'   => 'showAddButtonPopup(\''.$this->getUrl('*/*/getNewConfig', array('theme'=>$namespace)).'\')',
                'class'     => '',
            ));

            $this->addButton('export_all', array(
                'label'     =>  '<i class="fa fa-upload"></i>' . $helper->__('Export ALL'),
                'onclick'   => 'exportConfig(\''. $this->getUrl('*/*/exportConfig', array('theme'=>$namespace)) .'\')',
                'class'     => '',
            ));


            $this->addButton('import_all', array(
                'label'     =>  '<i class="fa fa-download"></i>' . $helper->__('Import Configs'),
                'onclick'   => 'importConfig(\''. $this->getUrl('*/*/importConfig', array('theme'=>$namespace)) .'\')',
                'class'     => '',
            ));


        }

        $this->_addButton('help_guide_button', array(
            'label'     => $helper->__('<i class="fa fa-life-ring"></i>Help'),
            'name'     => 'help_guide',
            'onclick'   => 'showHelpGuide()',
            'class'     => 'help',
        ));
    }
}