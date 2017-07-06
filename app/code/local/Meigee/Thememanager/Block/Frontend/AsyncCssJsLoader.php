<?php

class Meigee_Thememanager_Block_Frontend_AsyncCssJsLoader extends Mage_Core_Block_Template
{
    private $css_info;
    private $js_info = array();
    private $important_js_info = array();

    function __construct()
    {
        $this->setTemplate('meigee/async_js_css_loader.phtml');
        parent::__construct();
    }

    function addJs($js_info)
    {
        $js_file = (string)$js_info['name'];

        if (empty($js_file) || isset($this->js_info[$js_file]))
        {
            return false;
        }

        $js_info['data_name'] = 'skin_js' ==$js_info['type'] ?  Mage::getDesign()->getSkinUrl($js_info['name'], array()) :Mage::getBaseUrl('js') . $js_info['name'] ;
        $js_info['params_data'] =  $this->processingParams($js_info);

        if (isset($js_info['params_data']['important-js']))
        {
            $this->important_js_info[$js_file] = $js_info;
        }
        else
        {
            sort($js_info['params_data']);
            $this->js_info[$js_file] = $js_info;
        }

        return $js_info;
    }

    function addCss($css_info)
    {
        $param_data_arr = $this->processingParams($css_info);
        sort($param_data_arr);
        $css_info['data_name'] = Mage::getDesign()->getSkinUrl($css_info['name'], array());

        $this->css_info[] = array_merge(array_map('htmlspecialchars', $css_info), array('params_data' =>$param_data_arr)) ;
    }

    function processingParams($data_info)
    {
        $param_data_arr = array();
        if (!empty($data_info['params']))
        {
            $params = explode(' ', $data_info['params']);
            foreach($params AS $param)
            {
                $param_data = explode('=', $param);
                $param_data_arr[$param_data[0]] = array($param_data[0], (isset($param_data[1]) ? trim($param_data[1], '"\'') : true));
            }
        }
        return $param_data_arr;
    }

    function getCss()
    {
        return $this->css_info;
    }

    function getJs()
    {
        return $this->js_info;
    }
    function getImportantJs()
    {
        return $this->important_js_info;
    }

    function getJsCount()
    {
        return count($this->js_info);
    }


}