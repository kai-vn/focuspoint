

<?php class Meigee_Thememanager_Model_Widget_View
{
    public function getVisible()
    {
        return array(
                        array('value'=>'1', 'label'=>Mage::helper('thememanager')->__('1')),
                        array('value'=>'2', 'label'=>Mage::helper('thememanager')->__('2')),
                        array('value'=>'3', 'label'=>Mage::helper('thememanager')->__('3')),
                        array('value'=>'4', 'label'=>Mage::helper('thememanager')->__('4')),
                        array('value'=>'5', 'label'=>Mage::helper('thememanager')->__('5')),
                        array('value'=>'6', 'label'=>Mage::helper('thememanager')->__('6')),
                        array('value'=>'7', 'label'=>Mage::helper('thememanager')->__('7')),
                        array('value'=>'8', 'label'=>Mage::helper('thememanager')->__('8'))
                    );
    } 

	public function getVisibleHorizontal()
    {
        return array(
                        array('value'=>'1', 'label'=>Mage::helper('thememanager')->__('1')),
                        array('value'=>'2', 'label'=>Mage::helper('thememanager')->__('2')),
                        array('value'=>'3', 'label'=>Mage::helper('thememanager')->__('3')),
                        array('value'=>'4', 'label'=>Mage::helper('thememanager')->__('4')),
                        array('value'=>'5', 'label'=>Mage::helper('thememanager')->__('5')),
                        array('value'=>'6', 'label'=>Mage::helper('thememanager')->__('6'))
                    );
    }

	public function getVisibleVertical()
    {
        return array(
                        array('value'=>'1', 'label'=>Mage::helper('thememanager')->__('1')),
                        array('value'=>'2', 'label'=>Mage::helper('thememanager')->__('2')),
                        array('value'=>'3', 'label'=>Mage::helper('thememanager')->__('3')),
                        array('value'=>'4', 'label'=>Mage::helper('thememanager')->__('4')),
                        array('value'=>'5', 'label'=>Mage::helper('thememanager')->__('5'))
                    );
    }

    public function getProductsperrow()
    {
        return array(
						array('value'=>'1', 'label'=>Mage::helper('thememanager')->__('1')),
                        array('value'=>'2', 'label'=>Mage::helper('thememanager')->__('2')),
                        array('value'=>'3', 'label'=>Mage::helper('thememanager')->__('3')),
                        array('value'=>'4', 'label'=>Mage::helper('thememanager')->__('4')),
                        array('value'=>'5', 'label'=>Mage::helper('thememanager')->__('5')),
                        array('value'=>'6', 'label'=>Mage::helper('thememanager')->__('6')),
                        array('value'=>'7', 'label'=>Mage::helper('thememanager')->__('7')),
                        array('value'=>'8', 'label'=>Mage::helper('thememanager')->__('8'))
                    );
    }
    public function getTemplates()
    {
        return array(
                        'grid' => array('value'=>'grid', 'label'=>'Grid', 'phtml'=> 'widget/grid.phtml'),
                        'masonry_grid' => array('value'=>'masonry_grid', 'label'=>'Masonry Grid', 'phtml'=> 'widget/masonry_grid.phtml'),
                        'list' => array('value'=>'list', 'label'=>'List', 'phtml'=> 'widget/list.phtml'),
                        'footer_list' => array('value'=>'footer_list', 'label'=>'Footer List', 'phtml'=> 'widget/footer_list.phtml'),
                        'slider' => array('value'=>'slider', 'label'=>'Slider', 'phtml'=> 'widget/slider.phtml')
                    );
    }
    public function getBoolean()
    {
        return array(
                        array('value'=>'true', 'label'=>Mage::helper('thememanager')->__('Enable')),
                        array('value'=>'false', 'label'=>Mage::helper('thememanager')->__('Disable'))
                    );
    }
    public function getBrandsview()
    {
        return array(
            array('value'=>'list', 'label'=>'Simple List'),
            array('value'=>'slider', 'label'=>'Slider')
        );
    }
    public function getButtonspos()
    {
        return array(
            array('value'=>'0', 'label'=>Mage::helper('thememanager')->__('Top')),
            array('value'=>'1', 'label'=>Mage::helper('thememanager')->__('Bottom'))
        );;
    }

    public function getColumnsratio()
    {
        return array(
            array('value'=>'1', 'label'=>Mage::helper('thememanager')->__('20/80')),
            array('value'=>'2', 'label'=>Mage::helper('thememanager')->__('25/75')),
            array('value'=>'3', 'label'=>Mage::helper('thememanager')->__('30/70')),
            array('value'=>'4', 'label'=>Mage::helper('thememanager')->__('35/65')),
            array('value'=>'5', 'label'=>Mage::helper('thememanager')->__('40/60')),
            array('value'=>'6', 'label'=>Mage::helper('thememanager')->__('45/55')),
            array('value'=>'7', 'label'=>Mage::helper('thememanager')->__('50/50'))
        );
    }

    public function getEasing()
    {
        return array(
            array('value'=>'easeInQuad', 'label'=>'easeInQuad'),
            array('value'=>'easeOutQuad', 'label'=>'easeOutQuad'),
            array('value'=>'easeInOutQuad', 'label'=>'easeInOutQuad'),
            array('value'=>'easeInCubic', 'label'=>'easeInCubic'),
            array('value'=>'easeOutCubic', 'label'=>'easeOutCubic'),
            array('value'=>'easeInOutCubic', 'label'=>'easeInOutCubic'),
            array('value'=>'easeInQuart', 'label'=>'easeInQuart'),
            array('value'=>'easeOutQuart', 'label'=>'easeOutQuart'),
            array('value'=>'easeInOutQuart', 'label'=>'easeInOutQuart'),
            array('value'=>'easeInQuint', 'label'=>'easeInQuint'),
            array('value'=>'easeOutQuint', 'label'=>'easeOutQuint'),
            array('value'=>'easeInOutQuint', 'label'=>'easeInOutQuint'),
            array('value'=>'easeInSine', 'label'=>'easeInSine'),
            array('value'=>'easeOutSine', 'label'=>'easeOutSine'),
            array('value'=>'easeInOutSine', 'label'=>'easeInOutSine'),
            array('value'=>'easeInExpo', 'label'=>'easeInExpo'),
            array('value'=>'easeOutExpo', 'label'=>'easeOutExpo'),
            array('value'=>'easeInOutExpo', 'label'=>'easeInOutExpo'),
            array('value'=>'easeInCirc', 'label'=>'easeInCirc'),
            array('value'=>'easeOutCirc', 'label'=>'easeOutCirc'),
            array('value'=>'easeInOutCirc', 'label'=>'easeInOutCirc'),
            array('value'=>'easeInElastic', 'label'=>'easeInElastic'),
            array('value'=>'easeOutElastic', 'label'=>'easeOutElastic'),
            array('value'=>'easeInOutElastic', 'label'=>'easeInOutElastic'),
            array('value'=>'easeInBack', 'label'=>'easeInBack'),
            array('value'=>'easeOutBack', 'label'=>'easeOutBack'),
            array('value'=>'easeInOutBack', 'label'=>'easeInOutBack'),
            array('value'=>'easeInBounce', 'label'=>'easeInBounce'),
            array('value'=>'easeOutBounce', 'label'=>'easeOutBounce'),
            array('value'=>'easeInOutBounce', 'label'=>'easeInOutBounce')
        );
    }

    public function getFbschemes()
    {
        return array(
            array('value'=>'light', 'label'=>Mage::helper('thememanager')->__('Light')),
            array('value'=>'dark', 'label'=>Mage::helper('thememanager')->__('Dark')),
        );;
    }

    public function getGrid2description()
    {
        return array(
            array('value'=>'0', 'label'=>Mage::helper('thememanager')->__('Hide product description of all products')),
            array('value'=>'1', 'label'=>Mage::helper('thememanager')->__('Show product description of all products')),
            array('value'=>'2', 'label'=>Mage::helper('thememanager')->__('Show product description of symmetric products')),
            array('value'=>'3', 'label'=>Mage::helper('thememanager')->__('Show products description of odd-numbered products'))
        );
    }

    public function getImagesformat()
    {
        return array(
            array('value'=>'.png', 'label'=>Mage::helper('thememanager')->__('.png')),
            array('value'=>'.jpg', 'label'=>Mage::helper('thememanager')->__('.jpg')),
            array('value'=>'.gif', 'label'=>Mage::helper('thememanager')->__('.gif'))
        );
    }

    public function getModes()
    {
        return array(
            array('value'=>'horizontal', 'label'=>Mage::helper('thememanager')->__('Horizontal')),
            array('value'=>'vertical', 'label'=>Mage::helper('thememanager')->__('Vertical')),
            array('value'=>'fade', 'label'=>Mage::helper('thememanager')->__('Fade'))
        );
    }

    public function getNumber()
    {
        $randId = rand (0, 9999);
        return array(
            array('value'=>$randId, 'label'=>$randId)
        );
    }

    public function getTickerDirection()
    {
        return array(
            array('value'=>'next', 'label'=>Mage::helper('thememanager')->__('Next')),
            array('value'=>'prev', 'label'=>Mage::helper('thememanager')->__('Prev'))
        );
    }
}