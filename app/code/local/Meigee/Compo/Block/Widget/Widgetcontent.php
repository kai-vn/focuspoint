<?php
/**
 * Magento
 *
 * @author    Meigeeteam http://www.meigeeteam.com <nick@meigeeteam.com>
 * @copyright Copyright (C) 2010 - 2014 Meigeeteam
 *
 */
class Meigee_Compo_Block_Widget_Widgetcontent extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Override method to output our custom image
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return String
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        // Get the default HTML for this option
        //$html = parent::_getElementHtml($element);

        $html = '<div id="compo-widget-content" class="theme-widget-content">
                 <div class="title">
                    <h2>Click on element to remove it from the widget</h2>
                 </div>
                 <div class="title-2"><h2>Click on element to add it to widget</h2></div><div class="widget-holder">
                 <div class="widget-content">';
        $value = $element->getValue();
        if ($values = $element->getValues()) {
            foreach ($values as $option) {
                $html.= $this->_optionToHtml($element, $option, $value);
            }
        }
        $html.= $element->getAfterElementHtml();
        $html.= '</div><div class="items-container">
			<div class="label_new-holder"><div class="label_new-sub"></div></div>
			<div class="label_sale-holder"><div class="label_sale-sub"></div></div>
			<div class="compo_product_category-holder"><div class="compo_product_category-sub"></div></div>
			<div class="product_name-holder"><div class="product_name-sub"></div></div>
			<div class="stock_status-holder"><div class="stock_status-sub"></div></div>
			<div class="compo_quickview-holder"><div class="compo_quickview-sub"></div></div>
			<div class="descr_data-holder"><div class="descr_data-sub"></div></div>
			<div class="rating_stars-holder"><div class="rating_stars-sub"></div></div>
			<div class="reviews">
				<div class="rating_cust_link-holder"><div class="rating_cust_link-sub"></div></div>
				<div class="rating_add_review_link-holder"><div class="rating_add_review_link-sub"></div></div>
			</div>
			<div class="add_to_cart-holder"><div class="add_to_cart-sub"></div></div>
			<div class="price"><div class="price-holder"><div class="price-sub"></div></div></div>
			<div class="timer"><div class="timer-holder"><div class="timer-sub"></div></div></div>
			<div class="wishlist-holder"><div class="wishlist-sub"></div></div>
			<div class="compare-holder"><div class="compare-sub"></div></div>
		</div></div></div>';
        $html .= '<script type="text/javascript">
                            var href = "'.Mage::getDesign()->getSkinUrl("thememanager/adminhtml/compo_widget_styles.css", array('_default'=>true)).'"
                            var fileref=document.createElement("link");
                            fileref.setAttribute("type","text/css");
                            fileref.setAttribute("rel","stylesheet");
                            fileref.setAttribute("href",href);
                            document.getElementsByTagName("head")[0].appendChild(fileref);

                var script =document.createElement("script");
                script.setAttribute("src", "'.Mage::getBaseUrl('js').'/meigee/smart_widget.js");
                script.setAttribute("type", "text/javascript");
                script.id = "SmartWidgetSingleton";
                script.onload = function()
                            {
                                SmartWidget.init();
                                SmartWidgetSingleton = true;
                            }
                document.getElementsByTagName("head")[0].appendChild(script);
                    </script>';
        return $html;
    }

    /**
     * Override method to output wrapper
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @param Array $option
     * @param String $selected
     * @return String
     */
    protected function _optionToHtml($element, $option, $selected)
    {

        //labelsAttr = ['', '', '', '', '', '', '', '', 'label_sale'];
//          data-param='price'

        $html = "
		<div class='img-box'>
			<div class='item label_new_wrapper' data-param='label_new'><div class='label_new'>New</div></div>
			<div class='item label_sale_wrapper' data-param='label_sale'><div class='label_sale'>Sale</div></div>
			<img class='product-img' src='".Mage::getDesign()->getSkinUrl("thememanager/images/widget_product_img.jpg", array('_default'=>true))."' alt='' />
			<div class='item quickview inline' data-param='compo_quickview'>
				<img class='compo_quickview' src='".Mage::getDesign()->getSkinUrl("thememanager/images/widget_product_quickview.jpg", array('_default'=>true))."' alt='' />
			</div>
		</div>
		<div class='left-col'>
			<div class='item' data-param='compo_product_category'><h3 class='compo_product_category'>Decor</h3></div>
			<div class='item' data-param='product_name'><h2 class='product_name'>Sweatshirt With Varsity Print</h2></div>
			<div class='item' data-param='stock_status'><p class='stock_status'>Product <span>In stock</span></p></div>
			<div class='item' data-param='descr_data'><div class='descr_data'>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore</div></div>
			<div class='item' data-param='rating_stars'><img class='rating_stars' src='".Mage::getDesign()->getSkinUrl("thememanager/images/widget_product_ratings.jpg", array('_default'=>true))."' alt='' /></div>
			<div class='reviews use-separator'>


				<div class='item inline' data-param='rating_cust_link'>
					<span class='rating_cust_link'>3 Review(s)</span>
				</div>

			<span id='idd_2' class='review-divide separator'> | </span>
				<div class='item inline'  data-param='rating_add_review_link'>
					<span class='rating_add_review_link'>Add Your Review</span>
				</div>
			</div>
			<div class='clear'></div>
		</div>
		<div class='item f-none' data-param='price'>
			<div class='price'>
				<div class='regular-price'>$285.00</div>
			</div>
		</div>
		<div class='item f-none'  data-param='timer'>
			<div class='timer'>
				<span class='timer-title'>Offer ends in:</span>
				<span class='date'>02d:22h:20m:49s</span>
			</div>
		</div>
		<div class='item cart-btn inline' data-param='add_to_cart'>
			<img class='add_to_cart'  src='".Mage::getDesign()->getSkinUrl("thememanager/images/widget_product_cart.jpg", array('_default'=>true))."' alt='' />
		</div>
		<div class='item inline' data-param='wishlist'>
			<img class='wishlist' src='".Mage::getDesign()->getSkinUrl("thememanager/images/widget_product_wishlist.jpg", array('_default'=>true))."' alt='' />
		</div>
		<div class='item inline' data-param='compare'>
			<img class='compare' src='".Mage::getDesign()->getSkinUrl("thememanager/images/widget_product_compare.jpg", array('_default'=>true))."' alt='' />
		</div>";
        return $html;
    }
}
