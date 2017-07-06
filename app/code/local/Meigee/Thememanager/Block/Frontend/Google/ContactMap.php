<?php
class Meigee_Thememanager_Block_Frontend_Google_ContactMap extends Meigee_Thememanager_Block_Frontend_BlockAbstract
{
    function _getHtml($params = array())
    {
        $attributes_arr = array();
//        $params['attributes']['src'] = $this->getUrl('thememanager/index/googleContactMap');
        $params['attributes']['src'] = $this->getUrl('thememanager/index/googleContactMap',array('_secure'=>Mage::app()->getStore()->isFrontUrlSecure()));
		$params['attributes']['class'] = 'contact-map';
        foreach ($params['attributes'] AS $att_name =>$attribute)
        {
           $attributes_arr[$att_name] = $att_name . ' = "' . $attribute . '"';
        }

        $attributes = implode(' ', $attributes_arr);

        return <<<HTML
        <iframe {$attributes}></iframe>
HTML;
    }


}


