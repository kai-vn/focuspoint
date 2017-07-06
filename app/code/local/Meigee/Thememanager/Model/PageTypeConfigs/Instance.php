<?PHP
class Meigee_Thememanager_Model_PageTypeConfigs_Instance
{
    private static $instance = null;
    private $baseUrl = null;
    static $theme_id = null;
    static $theme_data = null;

    function __construct()
    {
        if (is_null(self::$instance))
        {
            self::$instance = $this;
        }
        $this->baseUrl = Mage::getConfig()->substDistroServerVars('{{base_url}}');
    }

    function getInstance()
    {
        return self::$instance;
    }

    private function saveFile($namespace, $file_types, $file_name)
    {
        if (empty($_FILES[$namespace]['size']))
        {
            return false;
        }

        $file_path = Mage::getBaseUrl('media') . $file_name;
        try
        {
            $uploader = new Varien_File_Uploader($namespace);
            $uploader->setAllowedExtensions(is_array($file_types) ? $file_types : explode(',', $file_types));
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $uploader->save(Mage::getBaseDir('media'), $file_name);
        }
        catch(Exception $e)
        {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')->setFormData($file_name);
            return false;
        }
        $file_path = str_replace($this->baseUrl, '', $file_path);
        return $file_path;
    }

    function unlincMediaFile($http_path)
    {
        $path = Mage::getBaseDir('media').DS.str_replace(Mage::getBaseUrl('media'), '', $http_path);
        if (file_exists($path) || !is_dir($path))
        {
            unlink($path);
        }
    }

    public function setConfigsByAliases($config, $theme_id)
    {
        $helper = Mage::helper('thememanager/themeConfig');
        foreach ($config AS $name => $value)
        {
            $name_arr = explode('::', $name);
            if (count($name_arr)>1 && 'delete'==$name_arr[0])
            {
                $new_name_arr = $name_arr;
                unset($new_name_arr[0]);
                $new_name = implode('::', $new_name_arr);

                if (is_array($value))
                {
                    $old_config_arr = $helper->getThemeConfigResultByAliase($new_name);
                    foreach ($value AS $val)
                    {
                        $arr_key = array_search($val ,$old_config_arr);
                        if (false !== $arr_key)
                        {
                            $this->unlincMediaFile($val);
                            unset($old_config_arr[$arr_key]);
                        }
                    }
                    $config[$new_name] = $old_config_arr;
                }
                else
                {
                    $this->unlincMediaFile($value);
                    $config[$new_name] = null;
                }
                unset($config[$name]);
            }
        }

        $used_config_aliases = $helper->getThemeConfigByAliases();
        if (!empty($_FILES))
        {
            foreach ($_FILES AS $namespace => $file)
            {
                if (!empty($file['name']))
                {
                    $file_types = isset($used_config_aliases[$namespace]) && isset($used_config_aliases[$namespace]['file_types']) ? $used_config_aliases[$namespace]['file_types'] : 'gif,jpg,tiff,png';

                    if (is_array($file['name']))
                    {
                        foreach ($file['name'] AS $key => $name)
                        {
                            $ns = $namespace . "-" . $key;
                            $_FILES[$ns] = array(
                                'name' =>$_FILES[$namespace]['name'][$key]
                                , 'type' =>$_FILES[$namespace]['type'][$key]
                                , 'tmp_name' =>$_FILES[$namespace]['tmp_name'][$key]
                                , 'error' => $_FILES[$namespace]['error'][$key]
                                , 'size' => $_FILES[$namespace]['size'][$key]
                            );
                            $path = $this->saveFile($ns, $file_types, $file['name'][$key]);
                            if ($path)
                            {
                                $config[$namespace][] = $path;
                            }
                        }
                    }
                    else
                    {
                        $config[$namespace] = $this->saveFile($namespace, $file_types, $file['name']);
                    }
                }
            }
        }

//        __CheckProductList
//        __CheckedProduct
        if (isset($config['__CheckProductList']) && !empty($config['__CheckProductList']))
        {
            $product_collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('meigee_product_theme_id')
                ->addFieldToFilter('entity_id', array('in' => $config['__CheckProductList']))
                ->load();
            $checked_products = array_flip((array)$config['__CheckedProduct']);
            foreach ($product_collection AS $product)
            {
                if (isset($checked_products[$product->getId()]))
                {
                    if ($theme_id != $product->getMeigeeProductThemeId())
                    {
                        $product->setMeigeeProductThemeId($theme_id)->save();
                    }
                }
                else
                {
                    if ($theme_id == $product->getMeigeeProductThemeId())
                    {
                        $product->setMeigeeProductThemeId(null)->save();
                    }
                }
            }
            unset($config['__CheckProductList'], $config['__CheckedProduct']);
        }

//        __CheckedCategories
//        __CheckCategoryList
        if (isset($config['__CheckCategoryList']) && !empty($config['__CheckCategoryList']))
        {
            $checked_category_list = explode(',', $config['__CheckCategoryList']);

            $category_collection = Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToSelect('meigee_category_theme_id')
                ->addFieldToFilter('entity_id', array('in' => $checked_category_list))
                ->load();
            $checked_category = explode(',', $config['__CheckedCategories']);
            $checked_category = array_flip(array_unique($checked_category));

            foreach ($category_collection AS $category)
            {
                if (isset($checked_category[$category->getId()]))
                {
                    if ($theme_id != $category->getMeigeeCategoryThemeId())
                    {
                        $category->setMeigeeCategoryThemeId($theme_id)->save();
                    }
                }
                else
                {
                    if ($theme_id == $category->getMeigeeCategoryThemeId())
                    {
                        $category->setMeigeeCategoryThemeId(null)->save();
                    }
                }
            }
            unset($config['__CheckCategoryList'], $config['__CheckedCategories']);
        }

//        __CheckedCmsPages
//        __CheckCmsPagesList
        if (isset($config['__CheckCmsPagesList']) && !empty($config['__CheckCmsPagesList']))
        {
            $cms_page_collection = Mage::getModel('cms/page')
                ->getCollection()
                ->addFieldToFilter('identifier', array('in' => $config['__CheckCmsPagesList']))
                ->addStoreFilter($helper->getStore())
                ->load();

            $checked_cms_pages = array_flip((array)$config['__CheckedCmsPages']);
            foreach ($cms_page_collection AS $cms_page)
            {
                $is_theme_id_eq = ($theme_id == $helper->getCmsPageConfigByEntity($cms_page));
                if (isset($checked_cms_pages[$cms_page->getIdentifier()]))
                {
                    if (!$is_theme_id_eq)
                    {
                        $helper->setCmsPageConfigByEntity($cms_page, $theme_id);
                    }
                }
                else
                {
                    if ($is_theme_id_eq)
                    {
                        $helper->setCmsPageConfigByEntity($cms_page, 0);
                    }
                }
            }

            unset($config['__CheckCmsPagesList'], $config['__CheckedCmsPages']);
        }

        foreach ($config AS $name => $value)
        {
            if (isset($used_config_aliases[$name]) || '_subValue_' == substr($name, 0, 10) )
            {
                Mage::getModel('thememanager/themeConfigData')->saveThemeConfig($name, $value, $theme_id);
            }
        }
        Mage::getSingleton('adminhtml/session')->addSuccess($helper->__('Options have been saved.'));
        session_write_close();
    }
}
