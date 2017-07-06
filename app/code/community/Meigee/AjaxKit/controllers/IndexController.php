<?php
class Meigee_AjaxKit_IndexController extends Mage_Core_Controller_Front_Action
{
    function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        parent::__construct($request, $response, $invokeArgs);
        $post = $this->getRequest()->getParams();
        if (isset($post['action']) && isset($post['submodule']))
        {
            $json = Mage::helper('core')->jsonDecode($post['values']);
            if (isset($json['parent']))
            {
                $url = Mage::helper('ajaxKit')->clearUrl($json['parent']['url']);
                $url_arr = explode('?', $url);
                $url = $url_arr[0];

                if ('product' == $json['parent']['controller'] || 'category' == $json['parent']['controller'])
                {
                    $oRewrite = Mage::getModel('core/url_rewrite')->setStoreId(Mage::app()->getStore()->getId())->loadByRequestPath($url);

                    if ('product' == $json['parent']['controller'])
                    {
                        Mage::register('current_product', Mage::getModel('catalog/product')->load((int)$oRewrite->getProductId()));
                    }
                    else
                    {
                        Mage::register('current_category', Mage::getModel('catalog/category')->load((int)$oRewrite->getCategoryId()));
                    }
                }
                elseif('cms' == $json['parent']['module'])
                {
                    if ('index' == $json['parent']['controller'])
                    {
                        $pageId = Mage::getStoreConfig(Mage_Cms_Helper_Page::XML_PATH_HOME_PAGE);
                        Mage::getSingleton('cms/page')->load($pageId);
                    }
                    else
                    {
                        Mage::getSingleton('cms/page')->load($url, 'identifier');
                    }
                }
                Mage::register('meigee_ajax', $json['parent']);
            }
        }
    }

    public function indexAction()
    {
        $post = $this->getRequest()->getParams();

        if (isset($post['action']) && isset($post['submodule']))
        {
            $json = Mage::helper('core')->jsonDecode($post['values']);

            $submodule_arr = explode('_', $post['submodule']);
            $submodule_arr = array_map('ucfirst', $submodule_arr);
            $block = $this->getLayout()->createBlock('ajaxKit/frontend_'.implode('', $submodule_arr).'_AjaxResult');
            $block->setUseAjax(true);
            $result = $block->ajax($post['action'], $json);
            $this->getResponse()->setBody( Mage::helper('core')->jsonEncode($result));
        }

    }

}



