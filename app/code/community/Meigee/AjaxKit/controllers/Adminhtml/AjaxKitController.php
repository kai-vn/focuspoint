<?php
class Meigee_AjaxKit_Adminhtml_AjaxKitController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('meigee');
        $this->getLayout()->getBlock('head')->addItem('skin_css', 'ajaxkit/adminhtml/custom.css');
        $this->getLayout()->getBlock('head')->addItem('skin_js', 'ajaxkit/adminhtml/scripts.js');

        $block = $this->getLayout()->createBlock('ajaxKit/adminhtml_interface_header');
        $this->_addContent($block);

        $block = $this->getLayout()->createBlock('ajaxKit/adminhtml_interface_tabs');
        $this->_addContent($block);

        $block = $this->getLayout()->createBlock('ajaxKit/adminhtml_interface_content');
        $this->_addContent($block);

        $this->renderLayout();
    }


    public function getAjaxTabAction()
    {
        $result = array();
        $action = $this->getRequest()->getParam('action');
        $store = (int)$this->getRequest()->getParam('store');
        Mage::app()->getStore($store)->resetConfig();
        Mage::app()->setCurrentStore(Mage::app()->getStore($store));
        $namespace = $this->getRequest()->getParam('namespace');

        if ($action)
        {
            switch ($action)
            {
                case 'getTabHtml':
                    $submodule_info = Mage::getModel('ajaxKit/configsReader')->getConfigAdminhtmlInfo($namespace);

                    $guideBlock = $this->getLayout()->createBlock('ajaxKit/adminhtml_tab_tabContent');
                    $guideBlock->setUseAjax(true);
                    $result['html'] = $guideBlock->_toHtml();
                    $result['submodule_info'] = $submodule_info;
                    $result['depends'] = $guideBlock->getDepends();
                    $result['store_id'] = $store;
                    break;

                case 'changeStatus':
                    $submodule_info = Mage::getModel('ajaxKit/configsReader')->getConfigAdminhtmlInfo($namespace, true);
                    $guideBlock = $this->getLayout()->createBlock('ajaxKit/adminhtml_tab_tabContent');
                    $guideBlock->setUseAjax(true);
                    $result['submodule_info'] = $submodule_info;
                    $result['store_id'] = $store;
                    break;

                case 'resetDefaults':
                    Mage::getModel('ajaxKit/configsReader')->getConfigOptions(true);
                    break;

                case 'saveTabs':
                    $tabs_json = $this->getRequest()->getParam('tabs');

                    if ($tabs_json)
                    {
                        $config_model  = Mage::getModel('ajaxKit/configsReader');
                        $tabs_arr =  Mage::helper('core')->jsonDecode($tabs_json);
                        foreach ($tabs_arr AS $namespace => $tab)
                        {
                            $config_model->saveConfig($namespace, $tab);
                        }
                    }

                    break;
            }
        }


        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}
