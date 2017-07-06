<?php
class Meigee_Thememanager_Controller_Adminhtml_WidgetController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function ajaxAction()
    {
        $action = $this->getRequest()->getParam('action');
        if ($action)
        {
            switch ($action)
            {
                case 'get_products':
                {
                    $category = $this->getRequest()->getParam('category');
                    if ($category)
                    {
                        $contentBlock = $this->getLayout()->createBlock('thememanager/widget_products');
                        $contentBlock->setFeaturedCategory($category);
                        $contentBlock->setUseAjax(true);
                        $this->getResponse()->setBody($contentBlock->getFeaturedCategoryProductHtml());
                    }
                }
            }
        }
    }
}
