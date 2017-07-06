<?php

class Meigee_Thememanager_Block_Adminhtml_Options_Tabs_Category extends  Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Categories
{
    protected $_categoryIds;
    protected $_selectedNodes = array();
    protected $loadTreeUri = '*/*/categoriesJson';
    protected $loadTreeUriArr = array();
    private $_selected = false;




    public function __construct() {
        parent::__construct();
        $this->setTemplate('thememanager/categories.phtml');
    }

    function setUri($uri, $loadTreeUriArr)
    {
        $this->loadTreeUri = $uri;
        $this->loadTreeUriArr += $loadTreeUriArr;

        return $this;
    }

    public function getLoadTreeUrl($expanded = null)
    {
//        return $this->getUrl($this->loadTreeUri, array('_current' => true));
        return $this->getUrl($this->loadTreeUri, $this->loadTreeUriArr + array('_current' => true));
    }

    protected function getCategoryIds()
    {
        if (false === $this->_selected)
        {
            $SelectedEntityIdGrid = Mage::registry('SelectedEntityIdGrid');
            if ($SelectedEntityIdGrid)
            {
                $this->_selected = array_flip($SelectedEntityIdGrid);
            }
            else
            {
                $theme_id = $this->getRequest()->getParam('theme_id');
                $categories = Mage::getModel('catalog/category')->getCollection();
                $categories->addAttributeToSelect('meigee_category_theme_id');
                $categories->addAttributeToFilter('meigee_category_theme_id', $theme_id);
                $categories_collection = $categories->load();

                $this->_selected  = array();
                foreach ($categories_collection AS $cat)
                {
                    $this->_selected[] =  $cat->getId();
                }
            }
        }

//print_r($this->_selected);

        return $this->_selected;
    }

    public function isReadonly() {
        return false;
    }

    public function getIdsString() {
        return implode(',', $this->getCategoryIds());
    }
}




























