<?php

class Bigone_Profile_AjaxController extends Mage_Core_Controller_Front_Action {

    protected function _ajaxResponse($result = array()) {
        $this->getResponse()
                ->setHeader('Content-Type', 'application/json')
                ->setBody(Zend_Json::encode($result))
        ;
    }

    public function indexAction() {
        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
        $brandId = $this->getRequest()->getParam('brandId');
        $html = '';
        $html .= $this->getLayout()->createBlock('profile/questions')
                        ->setBrand($brandId)
                        ->setTemplate('profile/questions.phtml')->toHtml();
        $result['html'] = $html;
        $this->_ajaxResponse($result);
    }

    public function changePresAction() {
        $pres = $this->getRequest()->getParam('pres');
        Mage::getSingleton('customer/session')->setQuestion((int) $pres);
        $result['pres'] = Mage::getSingleton('customer/session')->getQuestion();
        $this->_ajaxResponse($result);
    }

    public function uploadAction() {
        $result = array();
        if (isset($_FILES['file']) && $_FILES['file'] != '') {
            $uploader = new Varien_File_Uploader('file');

            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png','txt','docx','xlsx'));
            $uploader->setAllowRenameFiles(false);

            $uploader->setFilesDispersion(false);
            $path = Mage::getBaseDir('media') . '/profile/' . DS;
            $uploader->save($path, $_FILES['file']['name']);
            $result['fileName'] = $_FILES['file']['name'];
        }
        $this->_ajaxResponse($result);
    }

    public function changeOrderAction() {
        $orderId = $this->getRequest()->getParam('order');
        $data = Mage::helper('profile')->getSavedPrescription($orderId);
        $this->_ajaxResponse($data);
    }

    public function editOrderAction() {
        $result = array(); 
        if (isset($_FILES['file']) && $_FILES['file'] != '') {
        //upload image
            $uploader = new Varien_File_Uploader('file');

            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png','txt','docx','xlsx'));
            $uploader->setAllowRenameFiles(false);

            $uploader->setFilesDispersion(false);
            $path = Mage::getBaseDir('media') . '/profile/' . DS;
            $fileName = $_FILES['file']['name'];
            $uploader->save($path, $fileName);
            $result['status'] = 1;
        //change item data
            $itemId = $this->getRequest()->getParam('itemId');
            $item = Mage::getModel('sales/order_item')->load($itemId);
            if ($item->getId()) {
                // change data order item
                $profileData = unserialize($item->getBigoneProfileData());
                if (!empty($profileData['upload_file'])) {
                    $profileData['upload_file'] = $fileName;
                }
                $item->setBigoneProfileData(serialize($profileData))->save();
                //change data quote item
                $quoteItem = Mage::getModel('sales/quote_item')->load($item->getQuoteItemId());
                if ($quoteItem->getId()) {
                    $profileQuoteData = unserialize($quoteItem->getBigoneProfileData());
                    if (!empty($profileQuoteData['upload_file'])) {
                        $profileQuoteData['upload_file'] = $fileName;
                    }
                    $item->setBigoneProfileData(serialize($profileQuoteData))->save();
                }
            }
        } else $result['status'] = 0;
        $this->_ajaxResponse($result);
    } 

}
