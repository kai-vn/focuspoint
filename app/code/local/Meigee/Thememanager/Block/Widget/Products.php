<?php

class Meigee_Thememanager_Block_Widget_Products extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
    protected $products;
    private $pager_collection;
    private $products_collection;
    private static $is_isotope_js_enabled = false;

    function _construct()
    {
        parent::_construct();

        $templates = Mage::getModel('thememanager/widget_view')->getTemplates();
        $template = $this->getTemplate();

        if (isset($templates[$template])) {
            $this->setTemplate($templates[$template]['phtml']);
            $this->products_collection = $this->getProductsCollection();
        }
    }


    function _toHtml()
    {
        $themeConfig_helper = Mage::helper('thememanager/themeConfig');
        $theme_namespace = $themeConfig_helper->getThemeNamespace();
        $installed_thems = $themeConfig_helper->getInstalledThems();

        if($theme_namespace == $this->getMeigeeTheme() || count($installed_thems) == 1)
        {
            return parent::_toHtml();
        }
        return '';
    }



    function getCollection()
    {
        return $this->products_collection;
    }

    function getProductsCollection()
    {
        $collection = false;
        switch ($this->getSelectType()) {
            case 'featuredcategory':
                $collection = $this->getFeaturedCategoryCollection();
                break;
            case 'newproducts':
                $collection = $this->getNewProductsCollection();
                break;
            case 'bestsellers':
                $collection = $this->getBestSellersCollection();
                break;
            case 'saleproducts':
                $collection = $this->getSaleProductsCollection();
                break;
        }

        if ($collection)
        {
            $visibility_filter_arr = Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds();
            $status_filter_arr = Mage::getSingleton('catalog/product_status')->getSaleableStatusIds();


            $collection
                ->addAttributeToSelect(array('name', 'price', 'small_image', 'short_description'), 'inner')
                ->addAttributeToSelect('news_from_date')
                ->addAttributeToSelect('news_to_date')
                ->addAttributeToSelect('special_price')
                ->addAttributeToSelect('status')
                ->addAttributeToSelect('*')
                ->addUrlRewrite()
                //->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds())
                ->addAttributeToFilter('visibility', $visibility_filter_arr)
                ->addAttributeToFilter('status', $status_filter_arr)
                ->addStoreFilter();

            if ($this->getProductAttributeFilter()) {
                $collection->addAttributeToFilter($this->getProductAttributeCode(), $this->getProductAttributeValue());
            }
            if ($this->getFeaturedCategoryProducts()) {
                $product_ids = explode(',', $this->getFeaturedCategoryProducts());
                $collection->addAttributeToFilter('entity_id', array('in' => $product_ids));
            }

            if ($this->getProductPager()) {
                $page = $this->getRequest()->getParam('p') ? $this->getRequest()->getParam('p') : 1;
                $this->pager_collection = clone $collection;
                $collection->setCurPage($page);
            }
            $pageSize = $this->getRequest()->getParam('limit') ? $this->getRequest()->getParam('limit') : $this->getProductsAmount();

            $collection->setPageSize($pageSize);

            if (0 == Mage::getStoreConfig('cataloginventory/options/show_out_of_stock'))
            {
                Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
            }
        }

        return $collection;
    }


    function getFeaturedCategoryCollection()
    {
        $category_id = preg_replace('/[^0-9]/', '', $this->getFeaturedCategory());
        $category = Mage::getModel('catalog/category')->load($category_id);
        return $category->getProductCollection()->addAttributeToSort('position');
    }

    function getFeaturedCategoryProductHtml()
    {
        $product_collection = $this->getFeaturedCategoryCollection();
        $product_collection->addAttributeToSelect(array('name'), 'inner');
        $product_html_arr = array();
        foreach ($product_collection AS $product) {
            $product_html_arr[] = '<option value="' . $product->getId() . '">' . $product->getName() . '</option>';
        }
        return implode('', $product_html_arr);
    }


    function getBestSellersCollection()
    {
        $cls = Mage::getResourceModel('sales/report_bestsellers_collection')
            ->setModel('catalog/product')
            ->setPageSize($this->getProductsAmount())
            ->addStoreFilter();

        $product_ids = array();
        foreach ($cls AS $cl) {
            $product_ids[] = $cl->getProductId();
        }
        return Mage::getModel('catalog/product')->getCollection()->addAttributeToFilter('entity_id', array('in' => $product_ids));
    }


    function getNewProductsCollection()
    {
        $todayStartOfDayDate = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $collection = Mage::getResourceModel('catalog/product_collection');
        //  $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());


        return $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToFilter('news_from_date', array('or' => array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('news_to_date', array('or' => array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'news_from_date', 'is' => new Zend_Db_Expr('not null')),
                    array('attribute' => 'news_to_date', 'is' => new Zend_Db_Expr('not null'))
                )
            )
            ->addAttributeToSort('news_from_date', 'desc');

    }

    function getSaleProductsCollection()
    {
        $todayStartOfDayDate = Mage::app()->getLocale()->date()
            ->setTime('00:00:00')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate = Mage::app()->getLocale()->date()
            ->setTime('23:59:59')
            ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addAttributeToFilter('special_from_date', array('or' => array(
                0 => array('date' => true, 'to' => $todayEndOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter('special_to_date', array('or' => array(
                0 => array('date' => true, 'from' => $todayStartOfDayDate),
                1 => array('is' => new Zend_Db_Expr('null')))
            ), 'left')
            ->addAttributeToFilter(
                array(
                    array('attribute' => 'special_from_date', 'is' => new Zend_Db_Expr('not null')),
                    array('attribute' => 'special_to_date', 'is' => new Zend_Db_Expr('not null'))
                )
            )
            ->addAttributeToSort('special_from_date', 'desc');
        return $collection;
    }

    function getPager()
    {
        $pager_content = '';
        if ($this->getProductPager() && $this->pager_collection) {
            $pager_content = Mage::app()->getLayout()->createBlock('page/html_pager', 'wiget_pager_')->setCollection($this->pager_collection)->toHtml();
        }
        return $pager_content;
    }

    function includeIsotopeJs()
    {
        if (!self::$is_isotope_js_enabled)
        {
            $mconfig = Mage::helper('thememanager/themeConfig')->getThemeConfigByAliase('rtl');
            if ('disable' == strtolower($mconfig['result']['name']) || !$mconfig)
            {
                $js = 'js/jquery.isotope.min.js';
            }
            else
            {
                $js = 'js/jquery.isotope.min_rtl.js';
            }
            self::$is_isotope_js_enabled = true;
            return $this->getSkinUrl($js);
        }
        else
        {
            return false;
        }
    }






}




