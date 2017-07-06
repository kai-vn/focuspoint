<?php

class Meigee_Thememanager_Model_AdvancedStyling
{
    const AdvancedStylingCss = 'advanced_styling';
    const PatternImages = 'patterns';

    function isSkinCss($file_name)
    {
        $advanced_styling_predefined_files = (array)Mage::helper('thememanager/themeConfig')->getAdvancedStyling(true);
        $is_skin = false;
        $predefined_css = isset($advanced_styling_predefined_files['advanced_styling_predefined_css']) ? (array)$advanced_styling_predefined_files['advanced_styling_predefined_css']: array();
        if ($predefined_css && isset($predefined_css['file']))
        {
            $advanced_styling_predefined_files = $predefined_css['file'];
            if ($advanced_styling_predefined_files)
            {
                foreach($advanced_styling_predefined_files AS $file_data)
                {
                    $file_data = (array)$file_data;
                    if ($file_name == $file_data['value'])
                    {
                        return true;
                        break;
                    }
                }
            }
        }
        return $is_skin;
    }

    function getAdvancedStylingSkinCssFileContent($file_name, $theme_namespace = false)
    {
        if (!$theme_namespace)
        {
            $theme_namespace = Mage::helper('thememanager/themeConfig')->getThemeNamespace();
        }
        $css_ending = DS . 'frontend'.DS.$theme_namespace.DS.'default'.DS.'css'.DS.self::AdvancedStylingCss .DS . $file_name;
        $path = Mage::getConfig()->getOptions()->getSkinDir() . $css_ending;
        if (file_exists($path))
        {
            return file_get_contents($path);
        }
        return false;
    }
    function getAdvancedStylingCustomCssFileContent($file_name)
    {
        $file_path_arr = $this->getAdvancedStylingCustomCssFilesDirPath();
        $file_path = implode('', $file_path_arr).DS . $file_name;
        if (file_exists($file_path))
        {
            return file_get_contents($file_path);
        }
        return false;
    }

    function getAdvancedStylingCssFileContent($file_name)
    {
        $content = $this->getAdvancedStylingCustomCssFileContent($file_name);
        if (!$content)
        {
            $content = $this->getAdvancedStylingSkinCssFileContent($file_name);
        }
        return $content;
    }

    function getAdvancedStylingCustomCssFilesDirPath()
    {
        $theme_namespace = Mage::helper('thememanager/themeConfig')->getThemeNamespace();
        $css_ending = DS .self::AdvancedStylingCss .DS.$theme_namespace;
        $path = Mage::getConfig()->getOptions()->getMediaDir();

        return array(
                    'start' => $path,
                    'ending' =>$css_ending
        );
    }

    function getAdvancedStylingCustomCssFilesList()
    {
        $file_path_arr = $this->getAdvancedStylingCustomCssFilesDirPath();
        $file_path = implode(DS, $file_path_arr);

        $file_arr = array();

        if (file_exists($file_path))
        {
            foreach(scandir($file_path) AS $file)
            {
                if (is_file($file_path.DS.$file))
                {
                    $file_arr[] = array('label'=>$file, 'value'=>$file);
                }
            }
        }
        return $file_arr;
    }

    function saveAdvancedStylingCssFile($file_name, $file_content)
    {
        $file_path_arr = $this->getAdvancedStylingCustomCssFilesDirPath();
        $css_ending = $file_path_arr['ending'];
        $path = $file_path_arr['start'];
        $is_dir_created = $this->checkAndCreateDir($path, $css_ending);

        if ($is_dir_created)
        {
            return file_put_contents($path.$css_ending.DS. $file_name, $file_content);
        }
        return false;
    }

    function checkAndCreateDir($root_path, $second_path)
    {
        if (!file_exists($root_path) || !is_dir($root_path))
        {
            return false;
        }
        $second_path_arr = explode(DS, $second_path);

        $is_created = true;
        for($i=0, $c=count($second_path_arr); $i<$c; $i++)
        {
            if ($is_created && !empty($second_path_arr[$i]))
            {
                $root_path .= DS .$second_path_arr[$i];
                if (!file_exists($root_path) || !is_dir($root_path))
                {
                    $is_created = @mkdir($root_path);
                }
            }
        }
        return $is_created;
    }

    function getLocalFilePath()
    {
        $media_dir = Mage::getBaseDir('media') .DS . self::AdvancedStylingCss . DS;
        return $this->checkFilePath($media_dir);
    }

    function checkFilePath($dir)
    {
        if (!file_exists($dir))
        {
            mkdir($dir);
        }
        return $dir;
    }

    function getHttpCssFilePath()
    {
        $result = array();
        $file_name = Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase('advanced_styling_base_css_file');
        if ($file_name)
        {
            $result[] = Mage::getDesign()->getSkinUrl("css/".self::AdvancedStylingCss ."/". $file_name);
        }

        $file_name = Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase('advanced_styling_custom_css_file');
        if ($file_name)
        {
            $theme_namespace = Mage::helper('thememanager/themeConfig')->getThemeNamespace();
            $result[] = Mage::getBaseUrl('media') . self::AdvancedStylingCss ."/".$theme_namespace."/". $file_name;
        }
        return $result;
    }

    function getBackgroundPatternImages()
    {
        $theme_namespace = Mage::helper('thememanager/themeConfig')->getThemeNamespace();
        $path = Mage::getConfig()->getOptions()->getSkinDir() .  DS . 'frontend'.DS.$theme_namespace.DS.'default'.DS.'images'.DS.self::PatternImages;
        $http_path = Mage::getDesign()->getSkinUrl("images/".self::PatternImages ."/", array('_area'=>'frontend', '_package'=>$theme_namespace));
        $file_arr = array();
        if (file_exists($path))
        {
            foreach(scandir($path) AS $file)
            {
                if (is_file($path.DS.$file))
                {
                    $file_arr[] = $http_path.$file;
                }
            }
        }
        return $file_arr;
    }

    function getUploadedBackgroundPatternImages()
    {
        $path = $this->getLocalPatternFilePath();
        $http_path = $this->getHttpPatternFilePath();
        $file_arr = array();

        if (file_exists($path))
        {
            foreach(scandir($path) AS $file)
            {
                if (is_file($path.DS.$file))
                {
                    $file_arr[] = $http_path.$file;
                }
            }
        }
        return $file_arr;
    }

    function getLocalPatternFilePath()
    {
        $media_dir = Mage::getBaseDir('media') .DS . self::PatternImages . DS;
        return $this->checkFilePath($media_dir);
    }

    function getHttpPatternFilePath()
    {
        return Mage::getBaseUrl('media') .self::PatternImages . '/';
    }

    function uploadPattern()
    {
        if (isset($_FILES['pattern_file']) && !empty($_FILES['pattern_file']['name']))
        {
            $file_name = $_FILES['pattern_file']['name'];
            $file_path = $this->getLocalPatternFilePath();
            $http_file_path = $this->getHttpPatternFilePath();

            try
            {
                $uploader = new Varien_File_Uploader('pattern_file');
                $uploader->setAllowedExtensions(implode(',', 'gif,jpg,tiff,png'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save($file_path, $file_name);
            }
            catch(Exception $e)
            {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($file_name);
                return false;
            }
            return $http_file_path . $file_name;
        }
        return false;
    }
}