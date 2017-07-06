<?php

class Meigee_Thememanager_Block_Adminhtml_Options_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    static $activeTab = false;
    static $_current = null;
    private $group_namespace;

    public function __construct()
    {
        $helper = Mage::helper('thememanager');
        $__current_page =  Mage::app()->getRequest()->getParam('__currentPage');

        if ($__current_page)
        {
            self::$_current = $__current_page;
        }

        parent::__construct();
        $this->setId('tabs_id');
        $this->setDestElementId('edit_form');
        $this->setTitle($helper->__('Theme Information'));
    }

    protected function prepareLayout($blocks)
    {
        $helper = Mage::helper('thememanager');
        $this->addEmptyTab();

        $theme_id = Mage::app()->getRequest()->getParam('theme_id');
        foreach ($blocks AS $block_namespace => $block)
        {
            self::$_current = is_null(self::$_current) ? $block_namespace : self::$_current;

            $this->addTab($block_namespace, array(
                'label' => $helper->__($block['bTitle']),
                'title' => $helper->__(strip_tags($block['bTitle'])),
                'class' =>   'left_ajax' . (self::$_current == $block_namespace ? ' active_ajax_tab' : '') ,
                'url'   =>   $this->getUrl('*/*/ajax',array('_current' => (self::$_current == $block_namespace),
                                                            'group_namespace'=>$this->group_namespace,
                                                            'block_namespace'=>$block_namespace,
                                                            'theme_id'=>$theme_id)),
            ));
        }

        if ('meigee_modules' == $this->group_namespace)
        {
            $this->getDefaultTabs();
        }

        $this->setActiveTab(self::$_current);
        return parent::_prepareLayout();
    }

    function getTabs($group, $group_namespace)
    {
        $this->group_namespace = $group_namespace;
        $helper = Mage::helper('thememanager');
        $this->setTitle($helper->__($group['gTitle']));
        $this->setId('tabs_id');
        return $this->prepareLayout($group['blocks']);
    }

    function getDefaultTabs()
    {
        $theme_id = (int)Mage::app()->getRequest()->getParam('theme_id');
        $helper = Mage::helper('thememanager');

        self::$_current = is_null(self::$_current) ? '_default_advanced_styling' : self::$_current;
        $this->addTab('_default_advanced_styling', array(
            'label' => $helper->__('<i class="fa fa-magic"></i>Advanced Styling'),
            'title' => $helper->__('Advanced Styling'),
            'class' =>   'left_ajax' . ((self::$_current == '_default_advanced_styling') ? ' active_ajax_tab' : '') ,
//            'class' =>   'left_ajax' . ((self::$_current == '_default_advanced_styling') ? ' active_ajax_tab ajax' : '') ,
            'url'   =>   $this->getUrl('*/*/getDefaultAdvancedStyling', array('_current'=>(self::$_current == '_default_advanced_styling'), 'theme_id'=>$theme_id)),
        ));

//        $this->setActiveTab(self::$_current);
        return parent::_prepareLayout();
    }

    function getPageTypeTabs()
    {
        $theme_id = (int)Mage::app()->getRequest()->getParam('theme_id');
        $theme_config_data = Mage::getModel('thememanager/themes')->load($theme_id);
        $type = $theme_config_data->getType();
        $all_types = Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance()->getAllTypes();
        $helper = Mage::helper('thememanager');


        if (isset($all_types[$type]) && !$all_types[$type]['is_single'])
        {
            $this->setTitle($helper->__('Theme configuration'));

            $label = $all_types[$type]['label'];

            $this->setId('tabs_id');
            $tab_action_class_arr = explode('_',$all_types[$type]['value']);
            $tab_action_class_arr = array_map('ucwords', $tab_action_class_arr);

            self::$_current = is_null(self::$_current) ? '_default' : self::$_current;

            $this->addTab('_default', array(
                'label' => '<i class="fa fa-cogs"></i>'.$helper->__('%s List', $label),
                'title' => $helper->__('%s List', $label),
                'class' =>   'left_ajax' . (self::$_current == '_default' ? ' active_ajax_tab' : '') ,
                'url'   =>   $this->getUrl('*/*/get'.implode('', $tab_action_class_arr).'Table', array('_current'=>self::$_current == '_default', 'theme_id'=>$theme_id)),
            ));
//            $this->setActiveTab(self::$_current);
            return parent::_prepareLayout();
        }
        return false;
    }


    function addEmptyTab()
    {
        $this->addTab('hided_tab', array(
            'class' =>   'hided_tab',
            'url'   =>   '#',
        ));

    }


}