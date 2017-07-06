<?php


class Meigee_Thememanager_Helper_Images extends Varien_Object
{
    const NotUseRetina = 'no_retina';
    const UseRetina = 'retina';
    const UseRetinaNoCookie = 'retina_no_cookie';

    protected $ajax_images = null;
    protected $retina_images = null;
    private static $theme_config_helper;

    private $image;
    public $is_product = true;
    private $image_path = false;
    private $image_path_x2 = false;
    private $_product = false;


    function __construct()
    {
        self::$theme_config_helper = Mage::helper('thememanager/themeConfig');
    }

    function setProductImage($image, $width, $imgType, $product = false, $isMobile = false, $mobileWidth = 0)
    {
        $this->is_product = true;
        $clone = clone $this;
        $clone->_product = $product;
        $clone->setImage($image);
        $clone->processingImageData($width, $imgType, $isMobile, $mobileWidth);

        return $clone;
    }

    function setPresetImages($image, $imageX2)
    {
        $this->is_product = false;
        $clone = clone $this;
        $clone->setImagePath($image);
        $clone->setImagePathX2($imageX2);
        $clone->processingImageDataNoResize();
        return $clone;
    }

    function addImageHtmlAttributte($attribute_name, $attribute_value)
    {
        //$ready_attributes = $image->getImageHtmlAttributes();
        $ready_attributes = $this->getImageHtmlAttributes();
        $ready_attributes[$attribute_name][] = $attribute_value;
        //$image->setImageHtmlAttributes($ready_attributes);
        $this->setImageHtmlAttributes($ready_attributes);


        return $this;
    }

    function getImageHtmlAttributte()
    {
        $merged_attributes = array();
        //$image_html_attributes = $image->getImageHtmlAttributes();
        $image_html_attributes = $this->getImageHtmlAttributes();

        if ($image_html_attributes)
        {
            foreach ($image_html_attributes AS $name => $values)
            {
                if (!empty($name))
                {
                    $values = array_map('trim', $values, array('\'"'));
                    $merged_attributes[] = $name . '="'.implode(' ', $values).'"';
                }
            }
        }
        return implode(' ', $merged_attributes);
    }

    function getImageSrc()
    {
        $image_html_attributes = $this->getImageHtmlAttributes();
        $image_src = array();
        if ($image_html_attributes && (isset($image_html_attributes['src']) || isset($image_html_attributes['data-img-src'])))
        {
            $image_src = isset($image_html_attributes['src']) ? $image_html_attributes['src'] : $image_html_attributes['data-img-src'];
        }
        return implode(' ', $image_src);
    }


    function getResizeInfo($width)
    {
        $aspect_ratio = self::$theme_config_helper->getThemeConfigResultByAliase('aspect_ratio');
        switch ($aspect_ratio)
        {
            case -1:
                $url_keepAspectRatio = TRUE;
                $url_keepFrame = FALSE;
                $height = NULL;
                $height_x2 = NULL;
                break;
            case -2:
                $url_keepAspectRatio = TRUE;
                $url_keepFrame = TRUE;
                $height = NULL;
                $height_x2 = NULL;
                break;
            case -3:
                $custom_aspect_ratio_height = (float)self::$theme_config_helper->getThemeConfigResultByAliase('custom_aspect_ratio_height');
                if (!empty($custom_aspect_ratio_height))
                {
                    $aspect_ratio = $custom_aspect_ratio_height;
                }
            default :
                $url_keepAspectRatio = FALSE;
                $url_keepFrame = FALSE;
                $height = $width*$aspect_ratio;
                $height_x2 = $height*2;
                break;
        }
        return array(
                        'keepAspectRatio'=>$url_keepAspectRatio
                        ,'keepFrame'=>$url_keepFrame
                        ,'height'=>$height
                        ,'height_x2'=>$height_x2
                    );
    }



    function ajaxImages()
    {
        $ajax_images = self::$theme_config_helper->getThemeConfigResultByAliase('ajax_product_images');
        if($ajax_images && !$this->is_product)
        {
            $ajax_images = 'ajax_image_loader';
        }
        return $ajax_images;
    }

    function retinaImages()
    {
        if(is_null($this->retina_images))
        {
            $this->retina_images = self::NotUseRetina;
            $retina_images = (bool)self::$theme_config_helper->getThemeConfigResultByAliase('retina_product_images');

            if ($retina_images)
            {
                $cookie = Mage::getModel('core/cookie')->get('retina');
                $this->retina_images =  (bool)$cookie ? $cookie : self::UseRetinaNoCookie;
            }
        }
        return $this->retina_images;
    }


    function getBaseImageUrl($image, $imgType)
    {
        return (string)Mage::helper('catalog/image')->init($image, $imgType, $image->getFile());
    }

    function resizeImage($imgType, $image, $width, $resize_info = false)
    {
        if ($image instanceof Mage_Catalog_Model_Product )
        {
    	    $img = Mage::helper('catalog/image')->init($image, $imgType, $image->getFile());
        }
        else
        {
            if($this->_product)
            {
                $img = Mage::helper('catalog/image')->init($this->_product, $imgType, $image->getFile());
            }
            else
            {
                $_product = Mage::registry('product');
                if ($image && (string)$image->getFile())
                {
                    $img = Mage::helper('catalog/image')->init($_product, $imgType, (string)$image->getFile());
                }
                else
                {
                    $img = Mage::helper('catalog/image')->init($_product, $imgType);
                }
            }
        }
        if ($resize_info)
        {
            $img->keepAspectRatio($resize_info['keepAspectRatio']);
            $img->keepFrame($resize_info['keepFrame']);
            return (string)$img->constrainOnly(TRUE)->resize($width, $resize_info['height']);
        }
        return (string)$img->resize($width);
    }


    function processingImageData($width, $imgType, $isMobile = false, $mobileWidth = 0)
    {
        $resize_info = $this->getResizeInfo($width);
        $ajax_images = $this->ajaxImages();

        if ($ajax_images)
        {
			switch ($this->retinaImages())
            {
                case self::UseRetinaNoCookie:
                    $resized_image_url = $this->resizeImage($imgType, $this->getImage(), $width, $resize_info);
                    $resize_info['height'] = $resize_info['height_x2'];
                    $resized_image_url_x2 = $this->resizeImage($imgType, $this->getImage(), $width*2, $resize_info);

                    $this->addImageHtmlAttributte('data-img-src', $resized_image_url);
                    $this->addImageHtmlAttributte('data-img-srcX2', $resized_image_url_x2);
					
					if($isMobile == 'with-mobile'){
						$this->addImageHtmlAttributte('data-src-desktop', $resized_image_url_x2);
						$resized_mobile_image_url_x2 = $this->resizeImage($imgType, $this->getImage(), $mobileWidth*2, $resize_info);
						$this->addImageHtmlAttributte('data-src-mobile', $resized_mobile_image_url_x2);
					}
					break;
                case self::UseRetina:
                    $resize_info['height'] = $resize_info['height_x2'];
                    $resized_image_url = $this->resizeImage($imgType, $this->getImage(), $width*2, $resize_info);
                    $this->addImageHtmlAttributte('data-img-src', $resized_image_url);
					if($isMobile == 'with-mobile'){
						$this->addImageHtmlAttributte('data-src-desktop', $resized_image_url);
						$resized_mobile_image_url = $this->resizeImage($imgType, $this->getImage(), $mobileWidth*2, $resize_info);
						$this->addImageHtmlAttributte('data-src-mobile', $resized_mobile_image_url);
					}
					break;
                case self::NotUseRetina:
                default:
					$resized_image_url = $this->resizeImage($imgType, $this->getImage(), $width, $resize_info);
                    $this->addImageHtmlAttributte('data-img-src', $resized_image_url);
					if($isMobile == 'with-mobile'){
						$this->addImageHtmlAttributte('data-src-desktop', $resized_image_url);
						$resized_mobile_image_url = $this->resizeImage($imgType, $this->getImage(), $mobileWidth, $resize_info);
						$this->addImageHtmlAttributte('data-src-mobile', $resized_mobile_image_url);
					}
                    break;
            }

            $this->addImageHtmlAttributte('data-ajax_retina', $this->retinaImages());
            $this->addImageHtmlAttributte('class', $ajax_images);
            $this->addImageHtmlAttributte('src', $resized_image_url);
        }
        else
        {
            $resized_image_url = $this->resizeImage($imgType, $this->getImage(), $width, $resize_info);
            $this->addImageHtmlAttributte('src', $resized_image_url);
			if($isMobile == 'with-mobile'){
				$this->addImageHtmlAttributte('data-src-desktop', $resized_image_url);
				$resized_mobile_image_url = $this->resizeImage($imgType, $this->getImage(), $mobileWidth, $resize_info);
				$this->addImageHtmlAttributte('data-src-mobile', $resized_mobile_image_url);
			}
        }

    }

    function processingImageDataNoResize()
    {
        $ajax_images = $this->ajaxImages();
        if ($ajax_images)
        {
            switch ($this->retinaImages())
            {
                case self::UseRetinaNoCookie:
                    $image_url = $this->getImagePath();
                    $image_url_x2 = $this->getImagePathX2();

                    $this->addImageHtmlAttributte('data-img-src', $image_url);
                    $this->addImageHtmlAttributte('data-img-srcX2', $image_url_x2);
                    break;
                case self::UseRetina:
                    $image_url = $this->getImagePathX2();
                    $this->addImageHtmlAttributte('data-img-src', $image_url);

                    break;
                case self::NotUseRetina:
                default:
                    $image_url = $this->getImagePath();
                    $this->addImageHtmlAttributte('data-img-src', $image_url);
                    break;
            }

            $this->addImageHtmlAttributte('data-ajax_retina', $this->retinaImages());
            $this->addImageHtmlAttributte('class', $ajax_images);
            $this->addImageHtmlAttributte('src', $image_url);
        }
        else
        {
            $image_url = $this->getImagePath();
            $this->addImageHtmlAttributte('src', $image_url);
        }

    }


    function resizeImageFile($imgType, $width)
    {
        $resize_info = $this->getResizeInfo($width);

        return $this->resizeImage($imgType, $this->getImage(), $width, $resize_info);
    }

    function getFileBaseImageUrl($imgType)
    {
        $image = $this->getImage();
        
        if ($image instanceof Mage_Catalog_Model_Product )
        {
	        return (string)Mage::helper('catalog/image')->init($this->getImage(), $imgType, $this->getImage()->getFile());
        }
        else
        {
            $_product = Mage::registry('product');
            return (string)Mage::helper('catalog/image')->init($_product, $imgType, $this->getImage()->getFile());
        }
    }

    function dataToHtml($params)
    {
        $media =Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA, Mage::app()->getStore()->isCurrentlySecure());

        if (isset($params['data-img-srcX2']))
        {
            $data_src = $media.$params['data-img-src'];
            $data_src_x2 = (isset($params['data-img-srcX2']) ? $media. $params['data-img-srcX2'] :$data_src) ;
        }

        $image = $this->setPresetImages($data_src, $data_src_x2);
        foreach ($params AS $name => $data)
        {
            if ('data-img-src' != $name && 'data-img-srcX2' != $name)
            {
                $image-> addImageHtmlAttributte($name, $data);
            }
        }
        return $image-> getImageHtmlAttributte();
    }
}
