<?php

class Bigone_Profile_AjaxController extends Mage_Core_Controller_Front_Action {

    public function indexAction() {
        $this->getResponse()->clearHeaders()->setHeader('Content-type', 'application/json', true);
        $brandId = $this->getRequest()->getParam('brandId');
//        $data = array();
//        $glasses = Mage::getModel('profile/glasses')->getCollection()
//                ->addFieldToFilter('brand', $brandId);
//        foreach ($glasses as $item) {
//            $data['glass'][] = $item->getData();
//        }
//        Zend_Debug::dump($data);die();
//        $this->getResponse()->setBody(json_encode($data));
        $html = '';
        $html .= $this->getLayout()->createBlock('profile/questions')
                        ->setBrand($brandId)
                        ->setTemplate('profile/questions.phtml')->toHtml();
        $result['html'] = $html;
        $this->_ajaxResponse($result);
    }

    public function changePresAction() {
        $pres = $this->getRequest()->getParam('pres');
        Mage::getSingleton('customer/session')->setQuestion((int)$pres);
        $result['pres'] = Mage::getSingleton('customer/session')->getQuestion();
        $this->_ajaxResponse($result);
    }

    public function uploadAction() {
        if (isset($_FILES['file']) && $_FILES['file'] != '') {
            $uploader = new Varien_File_Uploader('file');

            $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(false);

            $uploader->setFilesDispersion(false);
            $path = Mage::getBaseDir('media') . '/profile/' . DS;
            $uploader->save($path, $_FILES['file']['name']);
        }
    }

    protected function _ajaxResponse($result = array()) {
        $this->getResponse()
                ->setHeader('Content-Type', 'application/json')
                ->setBody(Zend_Json::encode($result))
        ;
    }

}
