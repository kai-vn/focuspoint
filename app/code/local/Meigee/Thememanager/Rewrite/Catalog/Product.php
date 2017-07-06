<?php
class Meigee_Thememanager_Rewrite_Catalog_Product extends Mage_Catalog_Model_Product
{
    private static $theme_config_helper;
    private static $product_additional_content = false;
    // private static $is_catalog_mode = null;

    function __construct()
    {
        self::$theme_config_helper = Mage::helper('thememanager/themeConfig');
        parent::__construct();
    }

    function getProductPagination()
    {
        $product_pagination = self::$theme_config_helper->getThemeConfigResultByAliase('prev_next_buttons');
        $products = array();
        if($product_pagination)
        {
            $_product_id = (int)$this->getId();
            $current_category = $this->getCategory();

            if (!$current_category)
            {
                $current_cids  =  $this->getCategoryIds();
                if (!isset($current_cids[0]))
                {
                    return false;
                }
                $current_category = Mage::getModel('catalog/category')->load($current_cids[0]);
            }

            $visibility_filter_arr = Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds();
            $status_filter_arr = Mage::getSingleton('catalog/product_status')->getSaleableStatusIds();
            $children = $current_category->getProductCollection()
                ->addAttributeToSelect(array('name', 'price', 'small_image', 'short_description'), 'inner')
                ->addAttributeToSelect('news_from_date')
                ->addAttributeToSelect('news_to_date')
                ->addAttributeToSelect('special_price')
                ->addAttributeToSelect('status')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('visibility', $visibility_filter_arr)
                ->addAttributeToFilter('status', $status_filter_arr);
            if (!Mage::getStoreConfig(Mage_Catalog_Helper_Product::XML_PATH_PRODUCT_URL_USE_CATEGORY, $this->getStoreId()))
            {
                $children ->addUrlRewrite();
            }

            $_count = is_array($children) ? count($children) : $children->count();
            if ($_count)
            {
                $i = 0;
                $current_i = 0;
                foreach ($children as $product)
                {
                    $pid = (int)$product->getId();
                    $plist[$i] = $product;
                    $current_i = ($pid == $_product_id) ? $i : $current_i;
                    $i++;
                }

                if ($current_i && isset($plist[$current_i-1]))
                {
                    $products['prev'] = $plist[$current_i-1];
                }
                if ($current_i != count($plist)-1)
                {
                    $products['next'] = $plist[$current_i+1];
                }

                return $products;
            }
        }
        return $products;
    }



    function getProductMediaGalleryImages()
    {
        $gallery = $this->getMediaGalleryImages();
        if (!$gallery)
        {
            $gallery = Mage::getModel('catalog/product') -> load($this->getId())->getMediaGalleryImages();            //    $this->addImageHtmlAttributte($this, 'heh', $this->getImage());
        }
        return $gallery;
    }

    public function productHoverImage($view, $width, $isMobile = false, $mobileWidth = 385)
    {
        $this->productHoverImage = false;
        $html = "";
        $hover_image = self::$theme_config_helper->getThemeConfigResultByAliase('product_hover_image');
        if ($hover_image)
        {
            $gallery = $this->getProductMediaGalleryImages();
            if ($gallery->count() > 0)
            {
                foreach ($gallery as $image )
                {
                    if ($image->getLabel() == 'hover')
                    {
                        //$image_src = Mage::helper('thememanager/images')->setProductImage($image, $width, 'small_image')->getImageSrc();
//                        $image_src = Mage::helper('thememanager/images')->setProductImage($image, $width, 'small_image')->getImageHtmlAttributte();
                        $image_src = Mage::helper('thememanager/images')->setProductImage($image, $width, 'small_image', $this, $isMobile, $mobileWidth)->getImageHtmlAttributte();
                        if($isMobile == 'with-mobile'){
							$image_src .= ' width="'.$width.'" ';
						}
						$html = $view->getLayout()->createBlock('page/html')->setImage($image_src)->setTemplate($hover_image)->toHtml();
                        break;
                    }
                }
            }
        }
        return $html;
    }

    function getProductMediaGalleryResized($width, $imgType = "image")
    {
        $gallery = $this->getProductMediaGalleryImages();
        $gallery_return = array();
        if ($gallery->count() > 0)
        {
            $last_image = false;
            foreach ($gallery as $image)
            {
                $last_image = Mage::helper('thememanager/images')->setProductImage($image, $width, $imgType);
                //$last_image = Mage::helper('thememanager/images')->setProductImage($this, $width, $imgType);
                $last_image->setIsLastGalleryImage(false);
                $gallery_return[] = $last_image;
            }
            $last_image->setIsLastGalleryImage(true);
        }
        return $gallery_return;
    }

    function getProductMediaResized($width, $imgType = "image", $isMobile = false, $mobileWidth = 385)
    {
		if (!$this->getResizedImage())
        {
			return Mage::helper('thememanager/images')->setProductImage($this, $width, $imgType, false, $isMobile, $mobileWidth);
        }
        return false;
    }




    function resizeImage($imgType, $image, $width, $resize_info = false)
    {
        return Mage::helper('thememanager/images')->resizeImage($imgType, $image, $width, $resize_info);
    }


    public function isProductNew($label_new = null)
    {
        if (is_null($label_new))
        {
            $label_new = self::$theme_config_helper->getThemeConfigResultByAliase('product_label_new');
        }

        if ($label_new)
        {
            $from = new Zend_Date($this->getNewsFromDate());
            $to = new Zend_Date($this->getNewsToDate());
            $now = new Zend_Date(Mage::getModel('core/date')->timestamp(time()));
            return ($from->isEarlier($now) && $to->isLater($now));
        }
        return false;
    }

    public function isProductSale($label_sale = null, $label_sale_percentage = null)
    {
        if (is_null($label_sale))
        {
            $label_sale = self::$theme_config_helper->getThemeConfigResultByAliase('product_label_sale');
        }

        $this->productLabelSalePercentage = false;
        if ($label_sale)
        {
            $helper_tax = MAGE::helper('tax');

            $_finalPrice = $helper_tax->getPrice($this, $this->getFinalPrice());
            $_regularPrice = $helper_tax->getPrice($this, $this->getPrice());

            if ($_regularPrice != $_finalPrice)
            {
                if (is_null($label_sale_percentage))
                {
                    $label_sale_percentage = self::$theme_config_helper->getThemeConfigResultByAliase('product_label_sale_percentage');
                }

                if ($label_sale_percentage)
                {
                    $getpercentage = number_format($_finalPrice / $_regularPrice * 100, 2);
                    $this->productLabelSalePercentage = number_format((100 - $getpercentage), 0);
                }
                return true;
            }
        }
        return false;
    }

    public function getProductOnlyXleft($label_only_xleft = null)
    {
        if (is_null($label_only_xleft))
        {
            $label_only_xleft = self::$theme_config_helper->getThemeConfigResultByAliase('product_label_only_xleft');
        }

        if ($label_only_xleft)
        {
            $stockThreshold = Mage::getStoreConfig('cataloginventory/options/stock_threshold_qty');
            $productQty = round(Mage::getModel('cataloginventory/stock_item')->loadByProduct($this)->getQty());
            if($productQty != 0 and $productQty < $stockThreshold)
            {
                return $productQty+1;
            }
        }
        return false;
    }



    public function getProductAdditionalContentTabs()
    {
        if (false !== self::$product_additional_content)
        {
            return;
        }


        self::$product_additional_content = array('under_product_description'=>array(), 'product_tabs'=>array(), 'related'=>array());

        $_under_product_description = self::$theme_config_helper->getThemeConfigResultByAliase('additional_content_under_product_description_static_blocks');
        $_product_tabs = self::$theme_config_helper->getThemeConfigResultByAliase('additional_content_product_tabs_static_blocks');
        $_relateds = self::$theme_config_helper->getThemeConfigResultByAliase('additional_content_related_static_blocks');

        $product_tabs_aliases = array();
        if (!empty($_under_product_description))
        {
            $product_tabs_aliases = $_under_product_description;
        }

        if (!empty($_product_tabs))
        {
            $product_tabs_aliases = array_merge($_product_tabs, $product_tabs_aliases);
        }

        if (!empty($_relateds))
        {
            $product_tabs_aliases = array_merge($_relateds, $product_tabs_aliases);
        }

        if (!empty($product_tabs_aliases))
        {
            $product_tabs_aliases = array_unique($product_tabs_aliases);
            $block = Mage::getModel('cms/block')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->getCollection()
                ->addFieldToFilter('identifier', array( 'in' => $product_tabs_aliases))
                ->load();

            $blocks_arr = array();
            foreach($block AS $block)
            {
                $blocks_arr[$block->identifier] = $block;
            }

            if (!empty($_under_product_description))
            {
                foreach ($_under_product_description AS $under_description)
                {
                    if (isset($blocks_arr[$under_description]))
                    {
                        self::$product_additional_content['under_product_description'][] = $blocks_arr[$under_description];
                    }
                }
            }

            if (!empty($_product_tabs))
            {
                foreach ($_product_tabs AS $product_tab)
                {
                    if (isset($blocks_arr[$product_tab]))
                    {
                        self::$product_additional_content['product_tabs'][] = $blocks_arr[$product_tab];
                    }
                }
            }
            if (!empty($_relateds))
            {
                foreach ($_relateds AS $related)
                {
                    if (isset($blocks_arr[$related]))
                    {
                        self::$product_additional_content['related'][] = $blocks_arr[$related];
                    }
                }
            }
        }
    }


    function getUnderProductDescriptionTab()
    {
        $this->getProductAdditionalContentTabs();
        return self::$product_additional_content['under_product_description'];
    }


    function getProductTabs()
    {
        $this->getProductAdditionalContentTabs();
        return self::$product_additional_content['product_tabs'];
    }


    function getRelatedTabs()
    {
        $this->getProductAdditionalContentTabs();
        return self::$product_additional_content['related'];
    }

}