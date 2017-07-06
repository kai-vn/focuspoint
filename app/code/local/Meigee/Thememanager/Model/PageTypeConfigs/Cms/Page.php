<?PHP

class Meigee_Thememanager_Model_PageTypeConfigs_Cms_Page extends Meigee_Thememanager_Model_PageTypeConfigs_Basis
{
    function getWheteAttributes()
    {
        $page = Mage::getSingleton('cms/page');
        $helper = Mage::helper('thememanager/themeConfig');


        $helper->setFrontStore();

        $theme_id = $helper->getCmsPageConfigByEntity($page);


        if ($theme_id)
        {
            $this->setWhereAttribute('theme_id', $theme_id, 'OR');
            self::$theme_id = $theme_id;
        }
        $this->setWhereAttribute('type', self::AllCmsPagesType, 'OR');
        $this->setWhereAttribute('type', self::DefaultType, 'OR');
        $this->setWhereAttribute('type', self::StoreType, 'OR');
        $this->setStoreThemenamespace();
    }



}

