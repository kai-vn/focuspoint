<?php

class Meigee_Thememanager_Model_Themes extends Mage_Core_Model_Abstract
{
    const DefaultType = '_default';
    const StoreType = 'store';
    const DefaultNamespace = 'default';
    const DefaultName = 'Default';
    const DefaultStoreId = 0;


    public function _construct()
    {
        parent::_construct();
        $this->_init('thememanager/themes');
    }

    public function getThemesCollection()
    {
        $helper = Mage::helper('thememanager');
        $namespace = $helper->getThemeNamespace();

        if (empty($namespace))
        {
            return false;
        }
        $default_config = $this->getCollection()
                        ->addFieldToFilter('themenamespace', array( 'eq' => $namespace))
                        ->addFieldToFilter('type', array( 'eq' => self::DefaultType))
                        ->addFieldToFilter('store_id', array( 'eq' => self::DefaultStoreId))
                        ->load();



        if (!$default_config->count())
        {
            $this->setTheme(array(
                                    'themenamespace'=>$namespace
                                , 'type'=>self::DefaultType
                                , 'store_id'=>self::DefaultStoreId
                                , 'name'=>self::DefaultName
                            ));
        }
        return $this->getCollection()->addFieldToFilter('themenamespace', array( 'eq' => $namespace));
    }


    public function getUsedTheme()
    {
        $id = (int)Mage::app()->getRequest()->getParam('theme_id');
        return $this->load($id);
    }


    public function setTheme(array $data)
    {
        $data['add_date'] = Mage::getModel('core/date')->timestamp(time());
        $data['last_modified_date'] = Mage::getModel('core/date')->timestamp(time());
        $ptc = Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance();
        $types = $ptc->getAllTypes();
        if (isset($types[$data['type']]))
        {
            $data['type_order'] = $types[$data['type']]['level'];
        }
        else
        {
            $data['type_order'] = Meigee_Thememanager_Model_PageTypeConfigs_Basis::DefaultType == $data['type'] ? 1 : 100;
//            $data['type_order'] = $ptc::DefaultType == $data['type'] ? 1 : 100;
        }
        return $this->addData($data)->save()->getId();
    }

    public function removeTheme($theme_id)
    {
        $theme_id = (int)$theme_id;
        if ($theme_id)
        {
            $theme = $this->load($theme_id);
            if ($theme->getId())
            {
                $theme->delete();
            }
        }
    }
    public function removeThemeByNamespace($namespace)
    {
        $themes = $this->getCollection()
            ->addFieldToFilter('themenamespace', array( 'eq' => $namespace))
            ->delete();
    }

    public function setModifiedDate($this_id)
    {
        $data['last_modified_date'] = Mage::getModel('core/date')->timestamp(time());
        $data['theme_id'] = $this_id;
        $this->addData($data)->save();
    }
}