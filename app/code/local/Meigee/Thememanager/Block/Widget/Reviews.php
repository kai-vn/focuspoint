<?php

class Meigee_Thememanager_Block_Widget_Reviews extends Mage_Catalog_Block_Product_Abstract implements Mage_Widget_Block_Interface
{
	static $productPrice;
    protected function _construct()
    {
        parent::_construct();
		self::$productPrice = Mage::app()->getLayout()->createBlock('catalog/product_price');
    }

    function getReviewsCollection()
    {
		$review = Mage::getModel('review/review');
		$collection = $review->getProductCollection();
		$visible = $this->getReviewVisible();
        $visibility_filter_arr = Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds();
        $status_filter_arr = Mage::getSingleton('catalog/product_status')->getSaleableStatusIds();
		$collection
			->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
            ->addAttributeToFilter('visibility', $visibility_filter_arr)
            ->addAttributeToFilter('status', $status_filter_arr)
			->addAttributeToSelect('*')
			->getSelect()
				->limit($visible)
				 ->order('rand()');
		$review->appendSummary($collection);
		return $collection;
    }

	function getProductPrice($product)
    {
		$_product = Mage::getModel('catalog/product')->load($product->getId());
		$price = self::$productPrice->getPriceHtml($_product);
		return $price;
	}
}