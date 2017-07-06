<?php
class Meigee_Thememanager_Block_Frontend_Google_ContactMapPage extends Meigee_Thememanager_Block_Frontend_BlockAbstract
{
    function __construct()
    {
        $helper = Mage::helper('thememanager/themeConfig');
        $this->setGoogleLatitude($helper->getThemeConfigResultByAliase('google_latitude'));
        $this->setGoogleLongitude($helper->getThemeConfigResultByAliase('google_longitude'));
        $this->setGoogleMapZoom($helper->getThemeConfigResultByAliase('google_map_zoom'));
        $this->setGoogleMapType($helper->getThemeConfigResultByAliase('google_map_type'));
        $this->setGoogleMarker($helper->getThemeConfigResultByAliase('google_marker'));
        $this->setGoogleMarkerTitle($helper->getThemeConfigResultByAliase('google_marker_title'));
        //$this->setGoogleStaticBlockForDetails($helper->getThemeConfigResultByAliase('google_static_block_for_details'));
		$this->setGoogleStaticBlockForDetails(Mage::app()->getLayout()->getMConfigResultByAlias('google_static_block_for_details'));
        $this->setGoogleDetailsBlockStatus($helper->getThemeConfigResultByAliase('google_details_block_status'));
        $this->setGoogleDetailsBlockWidth($helper->getThemeConfigResultByAliase('google_details_block_width'));
        $this->setGoogleDetailsBlockHeight($helper->getThemeConfigResultByAliase('google_details_block_height'));
        $this->setGoogleDetailsBlockPosition($helper->getThemeConfigResultByAliase('google_details_block_position'));

        $this->setTemplate('meigee/blockmap.phtml');
    }
    function _getHtml($params = array())
    {
        return $this->toHtml();
    }
}


