<?PHP

class Meigee_Thememanager_Helper_Data extends Mage_Core_Helper_Abstract
{
    static $theme_name;
    static $theme_namespace = false;
    static $theme_store = false;
    static $currentPage;
    static $baseUrlS = false;


    function getBaseUrlS()
    {
        if (!self::$baseUrlS)
        {
            self::$baseUrlS = Mage::getConfig()->substDistroServerVars('{{base_url}}');
            if (Mage::app()->getStore()->isCurrentlySecure() && 'https' != substr(self::$baseUrlS, 0, 5))
            {
                self::$baseUrlS = 'https:'.substr(self::$baseUrlS, strpos(self::$baseUrlS, ':'));
            }
        }
        return self::$baseUrlS;
    }


    function getThemeNamespace()
    {
        return Mage::getModel('thememanager/pageTypeConfigs_instance')->getInstance()->getStoreThemenamespace();
    }

    function getInstalledThems()
    {
        $installed = Mage::getStoreConfig('meigee/thememanager/installed',0);
        return $installed;
    }

    function getUsedThems()
    {
        $meige_theme = Mage::getStoreConfig('design/package/meigee_package_name',0);
        $used_thems[] = $meige_theme ? $meige_theme : Mage::getStoreConfig('design/package/name',0);
        foreach (Mage::app()->getWebsites() as $website)
        {
            foreach ($website->getGroups() as $group)
            {
                $stores = $group->getStores();
                foreach ($stores as $store)
                {
                    $meige_theme = Mage::getStoreConfig('design/package/meigee_package_name',$store->getId());
                    $used_thems[$store->getId()] = $meige_theme ? $meige_theme : Mage::getStoreConfig('design/package/name',$store->getId());
                }
            }
        }
        return $used_thems;
    }

    function getStore()
    {
        if (false === self::$theme_store)
        {
            $theme_id = (int)Mage::app()->getRequest()->getParam('theme_id');
            $theme_config_data = Mage::getModel('thememanager/themes')->load($theme_id);

            self::$theme_store = $theme_config_data->getStoreId();
            if (!self::$theme_store)
            {
                self::$theme_store = 0;
            }
        }
        return self::$theme_store;
    }

    function setFrontStore()
    {
        self::$theme_store = Mage::app()->getStore()->getId();
    }


    function getStoreTitleNameById($storeId)
    {
        $core_store = Mage::getModel( "core/store" )->load($storeId);
        return $core_store->getName() ? ($core_store->getGroup()->getName() . " / " . $core_store->getName()) : false;
    }



    function getStoresForm()
    {
        $websites = Mage::app()->getWebsites();
        $websites_count = count($websites);
        $return = array();
        foreach ($websites as $website)
        {
            foreach ($website->getGroups() as $group)
            {
                $stores = $group->getStores();
                $stores_arr = array();
                foreach ($stores as $store)
                {
                    $return[] = array(
                                        'label'=>$this->getStoreTitleNameById($store->getId())
                                        , 'value'=>$store->getId()
                                        , 'store' => array(
                                                'code' => $store->getCode()
                                                , 'name' => $store->getName()
                                                , 'website' => array(
                                                        'code' => $website->getCode()
                                                        , 'name' => $website->getName()
                                                )
                                            )
                                    );
                    //$return[] = array('label'=>$store->getName(), 'value'=>$store->getId());
                }
            }
        }
        return $return;
    }

    function getCmsPageConfigKeyByEntity($entity)
    {
        $identifier =  preg_replace('/[^a-zA-Z0-9]/','_', $entity->getIdentifier());
        return 'meigee_cms_page/current_theme_id/'.$identifier."_".$entity->getId();
    }


    function getCmsPageConfigByEntity($entity)
    {
        return Mage::getStoreConfig($this->getCmsPageConfigKeyByEntity($entity), $this->getStore());
    }

    function setCmsPageConfigByEntity($entity, $value)
    {
        $code = (0==$this->getStore() ? 'default' : 'stores'); 
        Mage::getConfig()->saveConfig($this->getCmsPageConfigKeyByEntity($entity), $value, $code, $this->getStore());
    }



    function getCurrentTab()
    {
        self::$currentPage = '_default';
        $cp =  Mage::app()->getRequest()->getParam('__currentPage');

        if ($cp && !empty($cp))
        {
            self::$currentPage = Mage::app()->getRequest()->getParam('__currentPage');
        }
        return self::$currentPage;
    }



















}

