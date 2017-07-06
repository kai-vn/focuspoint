<?php
class Meigee_Thememanager_Block_Frontend_CustomFonts extends Meigee_Thememanager_Block_Frontend_BlockAbstract
{
    private $is_use_loader = false;

    function __construct()
    {
        parent::__construct();
        if (Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase('google_fonts_loader'))
        {
            $this->is_use_loader = true;
        }
    }

    function _toHtml($params = array())
    {
        $html = '';
        $fonts_str = Mage::helper('thememanager/themeConfig')->getThemeConfigByAliase('advanced_styling_custom_css_google_fonts');
        $fonts_str = Mage::helper('thememanager/themeConfig')->getThemeConfigResultByAliase('advanced_styling_custom_css_google_fonts');
        if ($fonts_str)
        {
            $fonts_arr = explode(';', $fonts_str);
            foreach($fonts_arr AS $font)
            {
                $html .= $this->_toFontsHtml($font);
            }
        }
        return $html;
    }


    function _toFontsHtml($font, $is_full_font_family = false)
    {
        $font_url =  "//fonts.googleapis.com/css?family=".$font . ($is_full_font_family ? '' : ":100,200,300,400,500,600,700,800");
        if ($this->is_use_loader)
        {
            return '
                <script type="text/javascript">//<![CDATA[
                    appendFont("'.$font_url.'");
                //]]></script>
                ';
        }
        else
        {
            return '<link href="'.$font_url.'" rel="stylesheet" type="text/css">';
        }
        return '';
    }


}
