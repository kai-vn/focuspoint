<?php
class Meigee_Thememanager_Rewrite_Product_View extends Mage_Catalog_Block_Product_View
{
    private static $theme_config_helper;
    private $extra = false;


    function __construct()
    {
        self::$theme_config_helper = Mage::helper('thememanager/themeConfig');
        parent::__construct();
    }


    public function isProductSidebar($position = false, $extra = false)
    {
        if ($position)
        {
            $sidebar_position = self::$theme_config_helper->getThemeConfigResultByAliase('product_sidebar_position');
            $sidebar = $sidebar_position == $position;
        }
        else
        {
            return (bool)self::$theme_config_helper->getThemeConfigResultByAliase('product_sidebar_position');
        }

        if ($sidebar)
        {
            $shop_col = self::$theme_config_helper->getThemeConfigResultByAliase('product_shop_col_xs_size');
            if (($extra && 12 != $shop_col['size']['value']) || (!$extra && 12 == $shop_col['size']['value']))
            {
                $sidebar = false;
            }

            if ($extra && $sidebar)
            {
                $this->extra = true;
            }
        }
        return $sidebar;
    }

    public function getProductSidebar($position, $extra = false)
    {
        $return = '';
        if ($this->isProductSidebar($position, $extra))
        {
            $return = $this->getChildHtml('product_sidebar');
        }
        return $return;
    }

    public function getProductSidebarColXsSize()
    {
        $sidebar_size = (bool)self::$theme_config_helper->getThemeConfigResultByAliase('product_sidebar_position') ? 3 : 0;
        return $sidebar_size;
    }

    public function getProductColXsSize()
    {
        $shop_col = self::$theme_config_helper->getThemeConfigResultByAliase('product_shop_col_xs_size');

        $sidebar_size = $this->getProductSidebarColXsSize();
        return 12-(12 != $shop_col['size']['value'] ? $sidebar_size : 0);
    }

    public function getProductShopColXsSize()
    {
        $shop_col = self::$theme_config_helper->getThemeConfigResultByAliase('product_shop_col_xs_size');
        return ((12 == $shop_col['size']['value'] && 0 != $this->getProductSidebarColXsSize()) ? 9 : $shop_col['size']['value']) . ' ' . $shop_col['class']['value'];
    }
    public function getProductImgBoxColXsSize()
    {
        $shop_col = self::$theme_config_helper->getThemeConfigResultByAliase('product_shop_col_xs_size');
        return (12-$shop_col['size']['value']) . ' ' . $shop_col['class']['value'];
    }

    public function getProductMediaImgSize()
    {
        $shop_col = self::$theme_config_helper->getThemeConfigResultByAliase('product_shop_col_xs_size');
        return $shop_col['pm_image_size']['value'];
    }




}









