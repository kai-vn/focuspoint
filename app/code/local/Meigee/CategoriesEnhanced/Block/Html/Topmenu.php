<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Page
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Top menu block
 *
 * @category    Mage
 * @package     Mage_Page
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Meigee_CategoriesEnhanced_Block_Html_Topmenu extends Mage_Page_Block_Html_Topmenu
{
    private $menuTreeHtml = array('vertical'=>array(), 'horizontal'=>array());


    protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
    {
		if(empty($this->menuTreeHtml['vertical']) && empty($this->menuTreeHtml['horizontal'])) {
			$this->_renderHtml($menuTree, $childrenWrapClass);
		}
		return implode('', $this->menuTreeHtml['vertical']) . implode('', $this->menuTreeHtml['horizontal']);
    }

    protected function _addMenuTreeHtml($html, $type)
    {
        $type = 'vertical' == $type ? $type : 'horizontal';
        $this->menuTreeHtml[$type][] = $html;
    }

    private function _renderHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
    {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

        $counter = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';
		if(Mage::getStoreConfig('meigee_categoriesenhanced/options/vertical_menu_status')) {
			$verticalParentId = Mage::getStoreConfig('meigee_categoriesenhanced/options/vertical_menu_id');
			$verticalType = Mage::getStoreConfig('meigee_categoriesenhanced/options/vertical_menu_type');
			$verticalSkin = Mage::getStoreConfig('meigee_categoriesenhanced/options/vertical_menu_skin');
		} else {
			$verticalParentId = '';
			$verticalType = '';
			$verticalSkin = '';
		}
        foreach ($children as $child) {
            $catId = explode("category-node-", $child->getId());
            $categoryComplete = Mage::getModel('catalog/category')->load($catId[1]);

            $outermostClassCode = '';
			if(Mage::getStoreConfig('meigee_categoriesenhanced/options/vertical_menu_status')) {
				$isVerticalMenu = $catId[1] == $verticalParentId;
			} else {
				$isVerticalMenu = '';
			}
            if ($isVerticalMenu) {
                $outermostClass = 'vertical-parent '. $verticalSkin . ' ' . $menuTree->getOutermostClass();
            } else {
                $outermostClass = $menuTree->getOutermostClass();
            }

            $iconCode = Mage::getResourceModel('catalog/category')->getAttributeRawValue($catId[1], "meigee_cat_icon", Mage::app()->getStore()->getId());
            if($iconCode and $childLevel <= 1){
                $icon = '<i class="'.$iconCode.' custom-icon"></i>';
            }else{
                $icon = '';
            }

            $catLabel = '';
            if ($categoryComplete->getMeigeeCatCustomlabel()) {
                $catLabel = '<em class="category-label ' . $categoryComplete->getMeigeeCatCustomlabel() . '">' . $categoryComplete->getMeigeeCatLabeltext() . '</em>';
            }

            if(Mage::getStoreConfig('meigee_categoriesenhanced/options/status') and ($categoryComplete->getMeigeeCatMenutype() != 1) ){

                // $childrenWrapClass = $isVerticalMenu ? 'vertical-menu-wrapper '.$verticalType : 'menu-wrapper';
                $childrenWrapClass = 'menu-wrapper';

                if ($categoryComplete->getMeigeeCatCustomlink()) {
                    if ($categoryComplete->getMeigeeCatCustomlink() == '/') {
                        $itemUrl = Mage::getBaseUrl();
                    }
                    elseif ($categoryComplete->getMeigeeCatCustomlink() == '#') {
                        $itemUrl = '#';
                    }
                    else $itemUrl = $categoryComplete->getMeigeeCatCustomlink();
                }
                else $itemUrl = $child->getUrl();

                // Get ratio value
                $ratio = explode("/", $categoryComplete->getMeigeeCatRatio());

                $child->setLevel($childLevel);
                $child->setIsFirst($counter == 1);
                $child->setIsLast($counter == $childrenCount);
                $child->setPositionClass($itemPositionClassPrefix . $counter);

                if ($childLevel == 0 && $outermostClass) {
                    // $outermostClassCode = ' class="' . $outermostClass . '" ';
                    $outermostClassCode = ' class="' . $outermostClass . '" ';
                    $child->setClass($outermostClass);
                }

					if ($childLevel == 1) {
						if ($child->hasChildren()) {
							if (!empty($childrenWrapClass)) {
								if($categoryComplete->getMeigeeCatMaxQuantity() and is_numeric($categoryComplete->getMeigeeCatMaxQuantity())){
									$columnsCount = $categoryComplete->getMeigeeCatMaxQuantity();
								}else{
									$columnsCount = Mage::getStoreConfig('meigee_categoriesenhanced/options/column_count');
								}
								$columnsCount = ' data-columns="'.$columnsCount.'"';

								$categoryBg = '';
								if($categoryComplete->getMeigeeCatBg()){
									$categoryBgOption = $categoryComplete->getMeigeeCatBgOption();
									switch($categoryBgOption){
										case "1":
											$categoryBgOption = "background-position: left bottom;";
											break;
										case "2":
											$categoryBgOption = "background-position: right bottom;";
											break;
										case "3":
											$categoryBgOption = "background-position: center bottom;";
											break;
										case "4":
											$categoryBgOption = "background-size: 100% 100%;";
											break;
										default:
											$categoryBgOption = "background-position: left bottom;";
									}
									$categoryBg = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/category/'.$categoryComplete->getMeigeeCatBg();
								}
							}
							$html .= '<li class="level1" data-menu-bg="'.$categoryBg.'"  data-menu-bgpos="'.$categoryBgOption.'" '.$columnsCount.'>';
						} else {
							$html .= '<li class="level1">';
						}
					}
					else {
						$html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
					}
                $subTitle = '';
                if ($childLevel == 1) {
                    $subTitle = ' class="subtitle"';
                }
                if ($categoryComplete->getMeigeeCatBlockTop() && $childLevel > 0) {
                    $html .= '<div class="top-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockTop()) . '</div><div class="clear"></div>';
                }

                if (!$categoryComplete->getMeigeeCatSubcontent()) {
					if(Mage::helper('core/url')->getCurrentUrl() == $itemUrl){
						empty($outermostClassCode) ? $outermostClassCode = 'class="active"' : '';
					}
                    $html .= '<a href="' . $itemUrl . '" ' . $outermostClassCode  .($categoryComplete->getMeigeeCatLinktarget()?' target="_blank"':''). '>'.$icon.'<span' . $subTitle . '>'
                        . $this->escapeHtml($child->getName()) . '</span>' . $catLabel . '</a>';
                }
                elseif ($categoryComplete->getMeigeeCatSubcontent() && $childLevel == 0) {
						 $html .= '<a href="' . $itemUrl . '" ' . $outermostClassCode .($categoryComplete->getMeigeeCatLinktarget()?' target="_blank"':''). '>'.$icon.'<span' . $subTitle . '>'
                        . $this->escapeHtml($child->getName()) . '</span>' . $catLabel . '</a>';

                }

                if ($child->hasChildren()) {
					$childLevelCounter = '';
					$isVerticalMenu ? $childLevelCounter == 1 : $childLevelCounter == 0;
                    if (!empty($childrenWrapClass) && $childLevel == $childLevelCounter) {
                        if($categoryComplete->getMeigeeCatMaxQuantity() and is_numeric($categoryComplete->getMeigeeCatMaxQuantity())){
                            $columnsCount = $categoryComplete->getMeigeeCatMaxQuantity();
                        }else{
                            $columnsCount = Mage::getStoreConfig('meigee_categoriesenhanced/options/column_count');
                        }
                        $columnsCount = ' data-columns="'.$columnsCount.'"';

                        $categoryBg = '';
                        if($categoryComplete->getMeigeeCatBg()){
                            $categoryBgOption = $categoryComplete->getMeigeeCatBgOption();
                            switch($categoryBgOption){
                                case "1":
                                    $categoryBgOption = "background-position: left 0;";
                                    break;
                                case "2":
                                    $categoryBgOption = "background-position: right 0;";
                                    break;
                                case "3":
                                    $categoryBgOption = "background-position: center 0;";
                                    break;
                                case "4":
                                    $categoryBgOption = "background-size: 100% 100%;";
                                    break;
                                default:
                                    $categoryBgOption = "background-position: left 0;";
                            }

                            $retinaBg = $categoryComplete->getMeigeeCatBgRetina();
                            if($retinaBg){
                                $retinaBg = ' dataX2="background-image:url('.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/category/'.$categoryComplete->getMeigeeCatBgRetina().'); '.$categoryBgOption.'"';
                            }
                            $categoryBg = ' style="background-image:url('.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/category/'.$categoryComplete->getMeigeeCatBg().'); '.$categoryBgOption.'"'.$retinaBg;
                        }
						if($isVerticalMenu){
							$html .= '<div class="vertical-menu-wrapper ' .$verticalType. '">';
						} else {
							$html .= '<div class="' . $childrenWrapClass . '"'.$columnsCount.$categoryBg.'>';
						}
                    }
                    if ($categoryComplete->getMeigeeCatSubcontent()) {
                        $html .= '<div class="sub-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatSubcontent()) . '</div>';
                    }
                    else {
                        if ($categoryComplete->getMeigeeCatBlockTop() && $childLevel == 0) {
                            $html .= '<div class="top-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockTop()) . '</div><div class="clear"></div>';
                        }
                        if ($categoryComplete->getMeigeeCatBlockRight() && $childLevel == 0 && !$isVerticalMenu ) {
                            $html .= '<div class="row"><div class="col-md-'. $ratio[0] .'">';
                        }
						$html .= '<ul class="level' . $childLevel . '">';
						$html .= $this->_renderHtml($child, $childrenWrapClass);
						$html .= '</ul>';
                        if (!empty($childrenWrapClass) && $childLevel == 0 && !$isVerticalMenu) {
                            if ($categoryComplete->getMeigeeCatBlockRight()) {
                                $html .= '</div>';
                                $html .= '<div class="col-md-'. $ratio[1] .' right-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockRight()) . '</div></div>';
                            }
                            $html .= '<div class="clear"></div>';
                        }
                        if ($categoryComplete->getMeigeeCatBlockBottom() && $childLevel == 0 && !$isVerticalMenu) {
                            $html .= '<div class="bottom-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockBottom()) . '</div><div class="clear"></div>';
                        }
                    }
					// if($isVerticalMenu){
						// if (!empty($childrenWrapClass) && $childLevel == 0) {
							// $html .= '<div class="transparent"></div></div></div>';
						// }
					// } else {
                    $module = Mage::app()->getRequest()->getModuleName();
                    $controller = Mage::app()->getRequest()->getControllerName();
                    $action = Mage::app()->getRequest()->getActionName();
                    if (!empty($childrenWrapClass) && $childLevel == 0) {
                        $html .= '<div class="transparent"></div>';
                        if (!empty($childrenWrapClass) && $childLevel == 0 && $isVerticalMenu) {
                            if($module == 'cms' && $controller == 'index' && $action == 'index') {
                              $html .= '<div class="vertical-menu-button"><span class="mobile open"></span><span class="open"><i class="meigee-fa-plus-square"></i>'.$this->__("More categories").'</span><span class="close"><i class="meigee-fa-minus-square"></i>'.$this->__("Close").'</span></div>';
                            }
                        }
                        $html .= '</div>';
						}
                    // }
                }
                if ($categoryComplete->getMeigeeCatBlockBottom() && $childLevel > 0 && !$isVerticalMenu) {
                    $html .= '<div class="bottom-content">' . $this->helper('cms')->getBlockTemplateProcessor()->filter($categoryComplete->getMeigeeCatBlockBottom()) . '</div><div class="clear"></div>';
                }

                $html .= '</li>';

                $counter++;

            }
            else{
                if($categoryComplete->getMeigeeCatCustomlink()) {
                    if ($categoryComplete->getMeigeeCatCustomlink() == '/') {
                        $itemUrl = Mage::getBaseUrl();
                    }
                    if ($categoryComplete->getMeigeeCatCustomlink() == '#') {
                        $itemUrl = '#';
                    }
                    else $itemUrl = $categoryComplete->getMeigeeCatCustomlink();
                }
                else $itemUrl = $child->getUrl();

                $child->setLevel($childLevel);
                $child->setIsFirst($counter == 1);
                $child->setIsLast($counter == $childrenCount);
                $child->setPositionClass($itemPositionClassPrefix . $counter);

                $outermostClassCode = '';
                $outermostClass = $menuTree->getOutermostClass();

                if ($childLevel == 0 && $outermostClass) {
                    $outermostClassCode = ' class="' . $outermostClass . '" ';
                    $child->setClass($outermostClass);
                }


                if(!Mage::getStoreConfig('meigee_categoriesenhanced/options/status')){
                    $catLabel = '';
                }
                $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
                $html .= '<a href="' . $itemUrl . '" ' . $outermostClassCode . '>'.$icon.'<span>'
                    . $this->escapeHtml($child->getName()) . '</span>'.$catLabel.'</a>';

                if ($child->hasChildren()) {
                    if((empty($childrenWrapClass) and Mage::getStoreConfig('meigee_categoriesenhanced/options/status')) || (!$isVerticalMenu and Mage::getStoreConfig('meigee_categoriesenhanced/options/status'))){
                        $childrenWrapClass = 'menu-wrapper';
                    }
                    if (!empty($childrenWrapClass)) {
                        $isDefaultMenu = '';
                        if($categoryComplete->getMeigeeCatMenutype() == 1){
                            $isDefaultMenu = ' default-menu';
                        }
                        $html .= '<div class="' . $childrenWrapClass . $isDefaultMenu . '">';
                    }
                    $html .= '<ul class="level' . $childLevel . '">';
                    $html .= $this->_renderHtml($child, $childrenWrapClass);
                    $html .= '</ul>';

                    if (!empty($childrenWrapClass)) {
                        $html .= '<div class="transparent"></div></div>';
                    }
                }
                $html .= '</li>';

                $counter++;
            }

            if (0 === $childLevel)
            {
                $menu_type = $isVerticalMenu ? 'vertical' : 'horizontal';
                $this->_addMenuTreeHtml($html, $menu_type);
                $html = '';
            }
        }

        if (0 != $childLevel)
        {
            return $html;
        }
    }

    protected function _getMenuItemClasses(Varien_Data_Tree_Node $item)
    {
        $classes = array();

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        }

        if (Mage::helper('core/url')->getCurrentUrl() == str_replace(Mage::helper('catalog/product')->getProductUrlSuffix(), "", $item->getUrl()) ) {
            $classes[] = 'custom-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        return $classes;
    }
}
