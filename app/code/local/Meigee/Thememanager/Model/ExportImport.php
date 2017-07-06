<?php

class Meigee_Thememanager_Model_ExportImport
{
    const DS_STRING = '[DS]';
    private $xml_file_arr = array();
    private $xml_doc = array();
    private $used_config = false;
    private $files_dir = false;
    private $is_parse_items_ids = true;
    private $is_parse_values = true;
    private $error_msg = array();
//    private $error_msg = array();




    function is_writable($is_use_session = true)
    {
        $dir = Mage::getBaseDir('var') . DS ;
        $msg = false;
        if (is_writable($dir))
        {
            $dir .= 'ThememanagerExport' . DS;
            if (file_exists($dir))
            {
                if (!is_dir($dir))
                {
                    $msg = Mage::helper('thememanager')->__('"%s" is not a dir', $dir);
                }
            }
            else
            {
                mkdir($dir, 0777);
            }
            if (!is_writable($dir))
            {
                $msg = Mage::helper('thememanager')->__('"%s" is not writable', $dir);
            }
        }
        else
        {
            $msg = Mage::helper('thememanager')->__('"%s" is not writable', $dir);
        }
        if ($msg)
        {
            $this->error_msg[] = $msg;
            if ($is_use_session)
            {
                Mage::getSingleton('core/session')->addError($msg);
            }
            return false;
        }
        else
        {
            $this->files_dir = $dir . date('U'). DS;
            mkdir($this->files_dir, 0777);
            return $this->files_dir;
        }
    }

    function getErrorMsg()
    {
        return $this->error_msg;
    }




    function getConfigFile(Meigee_Thememanager_Model_Themes $config)
    {
        $this->xml_file_arr = array();
        $this->used_config = $config;
        $this->getThemeInfo();
        $this->getProductsInfo();
        $this->getCategoriesInfo();
        $this->getCmsPagesInfo();
        $this->getThemeData();
        $this->saveXml();
        $file_name = $this->files_dir . $this->used_config->getId() . '_ThememanagerConfig.xml';

        $simplexml = simplexml_import_dom($this->xml_doc);
        $simplexml->saveXML($file_name);
        return $file_name;
    }

    function getThemeInfo()
    {
        $store = Mage::getModel( "core/store" )->load($this->used_config->getStoreId());
        $website = Mage::getModel('core/website')->load($store->getWebsiteId());

        $this->xml_file_arr['theme'] = array(
            'name' => $this->used_config->getName()
            , 'themenamespace' => $this->used_config->getThemenamespace()
            , 'type' => $this->used_config->getType()
            , 'type_order' => $this->used_config->getTypeOrder()
            , 'store' => array(
                    'code' => $store->getCode()
                    , 'name' => $store->getName()
                    , 'website' => array(
                        'code' => $website->getCode()
                        , 'name' => $website->getName()
                    )
                )
        );
    }



    function getProductsCollection($theme_id)
    {
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('meigee_product_theme_id');
        $collection->addFieldToFilter(array(
            array('attribute'=>'meigee_product_theme_id','eq'=>$theme_id),
        ));
        return $collection;
    }
    function getProductsInfo()
    {
        if($this->used_config->getType() == 'product')
        {
            $collection = $this->getProductsCollection($this->used_config->getId());
            foreach($collection AS $product)
            {
                $this->xml_file_arr['products_ids'][] = $product->getId();
            }
        }
    }


    function getCategorCollection($theme_id)
    {
        $collection = Mage::getModel('catalog/category')->getCollection();
        $collection->addAttributeToSelect('meigee_category_theme_id');
        $collection->addFieldToFilter(array(
            array('attribute'=>'meigee_category_theme_id','eq'=>$theme_id),
        ));
        return $collection;
    }

    function getCategoriesInfo()
    {
        if($this->used_config->getType() == 'category')
        {
            $collection = $this->getCategorCollection($this->used_config->getId());
            foreach($collection AS $product)
            {
                $this->xml_file_arr['categories_ids'][] = $product->getId();
            }
        }
    }



    function getCmsPagesCollection($theme_id, $store_id)
    {
        $cms_pages =  Mage::getStoreConfig('meigee_cms_page/current_theme_id', $store_id);
        $pages = array();
        foreach($cms_pages AS $page_id => $page_theme_id)
        {
            if ($theme_id == $page_theme_id)
            {
                $arr = explode('_', $page_id);
                $page_id = array_pop($arr);
                $pages[] = Mage::getModel('cms/page')->load($page_id);
            }
        }
        return $pages;
    }
    function getCmsPagesInfo()
    {
        if($this->used_config->getType() == 'cms_page')
        {
            $collection = $this->getCmsPagesCollection($this->used_config->getId(), $this->used_config->getStoreId());
            foreach($collection AS $page)
            {
                $this->xml_file_arr['pages_ids'][] = $page->getIdentifier();
            }
        }
    }

    function getThemeData()
    {
        $config_data_collection = Mage::getModel('thememanager/themeConfigData')->getCollection()->addFieldToFilter('theme_id', array( 'eq' => (int)$this->used_config->getId()));
        $custom_css_files_dir_path = implode('',Mage::getModel('thememanager/advancedStyling')->getAdvancedStylingCustomCssFilesDirPath());
        foreach($config_data_collection AS $config_data)
        {
            $this->xml_file_arr['theme_config'][$config_data->getAlias()] = $config_data->getValue();
            if ('advanced_styling_custom_css_file' == $config_data->getAlias())
            {
                $file_path_from =  $custom_css_files_dir_path.DS . $config_data->getValue();
                $file_path_to =  $this->files_dir. $config_data->getValue();
                if (file_exists($file_path_from) && is_file($file_path_from))
                {
                    copy($file_path_from, $file_path_to);
                }
            }
        }
    }


    function saveXml()
    {
        $this->xml_doc = new DomDocument('1.0', 'UTF-8');
        $this->xml_doc->preserveWhiteSpace = false;
        $this->xml_doc->formatOutput = true;
        $root = $this->xml_doc->createElement('root');
        $root = $this->xml_doc->appendChild($root);
        $this->buildArrayToXml($this->xml_file_arr, $root);
    }


    function buildArrayToXml($arr, $xml_obj)
    {
        foreach ($arr as $field => $fieldValue )
        {
            $fieldName = is_int($field) ? 'value' : $field;
            $child = $this->xml_doc->createElement($fieldName);
            $child = $xml_obj->appendChild($child);
            if ( is_array($fieldValue) )
            {
                $this->buildArrayToXml($fieldValue, $child);
            }
            else
            {
                $value = $this->xml_doc->createTextNode($fieldValue);
                $child->appendChild($value);
            }
        }
    }


    function createZip()
    {
        $files = scandir($this->files_dir);
        $valid_files = array();
        if(count($files))
        {
            $zip_file = $this->files_dir . 'ThememanagerConfig.zip';
            $zip = new ZipArchive();
            $zip_open = $zip->open($zip_file,(file_exists($zip_file) ? ZIPARCHIVE::OVERWRITE :ZIPARCHIVE::CREATE));
            if($zip_open !== true)
            {
                return false;
            }
            foreach($files as $file)
            {
                if (is_file($this->files_dir.$file))
                {
                    $zip->addFile($this->files_dir.$file,$file);
                }
            }
            $zip->close();

            return file_exists($zip_file) ? $zip_file : false;
        }
        else
        {
            return false;
        }
    }

    function uploadConfigFile()
    {
        $uploaded_files = array();
        if (!empty($_FILES['ImportFiles']))
        {
            foreach ($_FILES['ImportFiles']['name'] AS $key => $name)
            {
                try
                {
                    $ns = "ImportFiles-" . $key;
                    $_FILES[$ns] = array(
                            'name' =>$_FILES['ImportFiles']['name'][$key]
                        , 'type' =>$_FILES['ImportFiles']['type'][$key]
                        , 'tmp_name' =>$_FILES['ImportFiles']['tmp_name'][$key]
                        , 'error' => $_FILES['ImportFiles']['error'][$key]
                        , 'size' => $_FILES['ImportFiles']['size'][$key]
                    );

                    $uploader = new Varien_File_Uploader($ns);
                    $uploader->setAllowedExtensions(array('xml', 'zip'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);
                    $uploader->save($this->files_dir, $name);
                }
                catch(Exception $e)
                {
                    $uploaded_files[] = array('name'=>$name, 'error'=>$e->getMessage());
                }
            }
        }


        foreach (scandir($this->files_dir) AS $file_name)
        {
            if ('.' != $file_name && '..' != $file_name)
            {
                $uploaded_files[] = array('name'=>$file_name, 'file_path'=>$this->files_dir.$file_name);
            }
        }

        $files_contents = array();

        foreach ($uploaded_files AS $u_file)
        {
            if (isset($u_file['error']))
            {
                $files_contents[] = $u_file;
                continue;
            }

            switch (substr($u_file['name'], -3))
            {
                case 'xml':
                    $files_contents[] = $this->getXlmContent($u_file['file_path'], $u_file['name']);
                    break;
                case 'zip':
                    $files_contents = array_merge($files_contents, $this->uzipFile($u_file['file_path'], $u_file['name']));
                    break;
            }
        }
        return $files_contents;
    }

    function setIsParseItemsIds($is_parse = true)
    {
        $this->is_parse_items_ids = $is_parse;
    }


    function setIsParseConfigValues($is_parse = true)
    {
        $this->is_parse_values = $is_parse;
    }


    function getXlmContent($file_path, $file_name, $file_zip = false)
    {
        $file_info = array();

        $theme = Mage::app()->getRequest()->getParam('theme');
        $themes = Mage::helper('thememanager')->getInstalledThems();

        try
        {
            $theme_name = $themes[$theme];
            $xml=simplexml_load_file($file_path);
            if ($xml)
            {
                $file_info= json_decode(json_encode($xml), true);
                if ($file_info['theme']['themenamespace'] != Mage::app()->getRequest()->getParam('theme'))
                {
                    $file_info['error'][] = Mage::helper('thememanager')->__('Theme Scheme is not for the "%s"', $theme_name);
                }
                else
                {
                    if ($this->is_parse_items_ids)
                    {
                        if (isset($file_info['products_ids']))      { $file_info['products_ids'] = (array)$file_info['products_ids']['value']; }
                        if (isset($file_info['categories_ids']))    { $file_info['categories_ids'] = (array)$file_info['categories_ids']['value']; }
                        if (isset($file_info['pages_ids']))         { $file_info['pages_ids'] = (array)$file_info['pages_ids']['value']; }
                    }
                    else
                    {
                        if (isset($file_info['products_ids']))      { unset($file_info['products_ids']); }
                        if (isset($file_info['categories_ids']))    { unset($file_info['categories_ids']); }
                        if (isset($file_info['pages_ids']))         { unset($file_info['pages_ids']); }
                    }

                    if (!$this->is_parse_values && isset($file_info['theme_config']))
                    {
                        unset($file_info['theme_config']);
                    }
                }
                $file_info['file_name'] = $file_name;
                $file_info['name'] = $file_name;
                $file_info['file_zip'] = $file_zip;
                $file_info['file_path'] = $this->encodeFilePath($file_path);
            }
        }
        catch(Exception $e)
        {
            $file_info['error'] = array('file_name'=>$file_name, 'error'=>$e->getMessage());
        }
        return $file_info;
    }

    function uzipFile($zip_file_path, $zip_file_name)
    {
        $unpacked_files = array();
        $zip = new ZipArchive;
        $res = $zip->open($zip_file_path);

        if ($res === TRUE)
        {
            $unpacked_dir = $this->files_dir . DS . 'unpacked_dir_' . md5($zip_file_path) . DS;
            mkdir($unpacked_dir);
            $zip->extractTo($unpacked_dir);
            $zip->close();

            foreach (scandir($unpacked_dir) AS $file_name)
            {
                if ('.' != $file_name && '..' != $file_name)
                {
                    $content = $this->getXlmContent($unpacked_dir.$file_name, $file_name, $zip_file_name);
                    if ($content)
                    {
                        $unpacked_files[] = $content;
                    }
                }
            }
        }
        else
        {
            $unpacked_files[] = array('name'=>$zip_file_name, 'error'=>Mage::helper('thememanager')->__('Can\'t unpacked file "%s"', $zip_file_name));
        }
        return $unpacked_files;
    }

    function clear()
    {
        $is_del = true;
        foreach(scandir($this->files_dir) AS $file)
        {
            if ('.' == $file || '..' == $file)
            {
                continue;
            }

            $file = $this->files_dir . $file;
            if (!is_file($file))
            {
                $is_del = false;
            }
            unlink($file);
        }
        if ($is_del)
        {
            rmdir($this->files_dir);
        }
    }

    function encodeFilePath($file)
    {
        return str_replace(DS, self::DS_STRING, $file);
    }
    function decodeFilePath($file)
    {
        return str_replace(self::DS_STRING, DS, $file);
    }


    function setSelectedEntityIdGrid($file)
    {
        $file = $this->decodeFilePath($file);
        $file_content = $this->getXlmContent($file, '');

        $items = (isset($file_content['products_ids'])?$file_content['products_ids']:(isset($file_content['categories_ids'])?$file_content['categories_ids']:(isset($file_content['pages_ids'])?$file_content['pages_ids']:array())));
        $items = array_flip($items);
        Mage::register('SelectedEntityIdGrid', $items);
    }


    function importData($data, $themenamespace)
    {
        switch ($data['action'])
        {
            case 'do_nothing':
                return true;
                break;
            case 'new':
                $theme_id = Mage::getModel('thememanager/themes')->setTheme(array(
                    'name'=>$data['name']
                , 'store_id'=>$data['store']
                , 'type'=>$data['type']
                ,'themenamespace'=>$themenamespace
                ));
                break;
            default:
                $theme_id = (int)$data['action'];
                if (!$theme_id)
                {
                    return false;
                }
                $theme = Mage::getModel('thememanager/themes')->load($theme_id);
                $data['type'] = $theme->getType();
                $data['store'] = $theme->getStore();
                $data['name'] = $theme->getName();
                break;
        }

        $themeConfigDataModel = Mage::getModel('thememanager/themeConfigData');
        $themeConfigDataModel->deleteDataByThemeId($theme_id);

        $file = $this->decodeFilePath($data['file-path']);
        $file_content = $this->getXlmContent($file, '');
        $custom_css_files_dir_path = implode('',Mage::getModel('thememanager/advancedStyling')->getAdvancedStylingCustomCssFilesDirPath());

        if (isset($file_content['theme_config']))
        {
            foreach($file_content['theme_config'] AS $alias => $value)
            {
                $themeConfigDataModel->addThemeConfig($alias, $value, $theme_id);
                if ('advanced_styling_custom_css_file' == $alias)
                {
                    $file_path_to =  $custom_css_files_dir_path.DS . $value;
                    $path_parts = pathinfo($file);
                    $file_path_from =  $path_parts['dirname'].DS. $value;
                    if (file_exists($file_path_from))
                    {
                        copy($file_path_from, $file_path_to);
                    }
                }
            }
        }

        switch ($data['type'])
        {
            case 'product':
                $collection = $this->getProductsCollection($theme_id);
                foreach ($collection AS $product)
                {
                    $product->setMeigeeProductThemeId(null)->save();
                }

                $items = isset($data['items']) ? array_keys($data['items']) : $file_content['products_ids'];

                $product_collection = Mage::getModel('catalog/product')
                    ->getCollection()
                    ->addAttributeToSelect('meigee_product_theme_id')
                    ->addFieldToFilter('entity_id', array('in' => $items))
                    ->load();
                foreach ($product_collection AS $product)
                {
                    $product->setMeigeeProductThemeId($theme_id)->save();
                }

                break;
            case 'category':
                $collection = $this->getCategorCollection($theme_id);
                foreach ($collection AS $cat)
                {
                    $cat->setMeigeeCategoryThemeId(null)->save();
                }

                $items = isset($data['items']) ? explode(',', $data['items']) : $file_content['categories_ids'];

                $category_collection = Mage::getModel('catalog/category')
                    ->getCollection()
                    ->addAttributeToSelect('meigee_category_theme_id')
                    ->addFieldToFilter('entity_id', array('in' => $items))
                    ->load();
                foreach ($category_collection AS $cat)
                {
                    $cat->setMeigeeCategoryThemeId($theme_id)->save();
                }

                break;
            case 'cms_page':
                $helper = Mage::helper('thememanager/themeConfig');
                $collection = $this->getCmsPagesCollection($theme_id, $data['store']);
                foreach ($collection AS $cms_page)
                {
                    $helper->setCmsPageConfigByEntity($cms_page, 0);
                }
                $items = isset($data['items']) ? array_keys($data['items']) : $file_content['pages_ids'];

                $cms_page_collection = Mage::getModel('cms/page')
                    ->getCollection()
                    ->addFieldToFilter('identifier', array('in' => $items))
                    ->addStoreFilter($data['store'])
                    ->load();

                foreach ($cms_page_collection AS $cms_page)
                {
                    $helper->setCmsPageConfigByEntity($cms_page, $theme_id);
                }

                break;
        }

        switch ($data['action'])
        {
            case 'do_nothing':
                break;
            case 'new':
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('thememanager')->__('File %s was successfully imported', basename($file)));
                break;
            default:
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('thememanager')->__('Theme %s was successfully changed', $data['name']));
                break;
        }
    }
}








