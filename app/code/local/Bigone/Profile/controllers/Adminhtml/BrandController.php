<?php

class Bigone_Profile_Adminhtml_BrandController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('profile/brand')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Manage Brands'), Mage::helper('adminhtml')->__('Manage Brands'));
        return $this;
    }

    public function indexAction() {
        $this->_initAction()
                ->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $id = (int) $this->getRequest()->getParam('id');
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        Mage::register('store_id', $storeId);

        //get option data
        if ($id) {
            //glass data
            $glasses = Mage::getModel('profile/glasses')->getCollection()
                    ->addFieldToFilter('brand', $id);
            if (count($glasses)) {
                $data_glass = array();
                foreach ($glasses as $item) {
                    $data_glass[] = $item->getData();
                }
                Mage::register('glasses_data', $data_glass);
            }
            //lens data
            $lens = Mage::getModel('profile/lens')->getCollection()
                    ->addFieldToFilter('brand', $id);
            if (count($lens)) {
                $data_lens = array();
                foreach ($lens as $item) {
                    $data_lens[] = $item->getData();
                }
                Mage::register('lens_data', $data_lens);
            }
            //coating data
            $coating = Mage::getModel('profile/coating')->getCollection()
                    ->addFieldToFilter('brand', $id);
            if (count($coating)) {
                $data_coating = array();
                foreach ($coating as $item) {
                    $data_coating[] = $item->getData();
                }
                Mage::register('coating_data', $data_coating);
            }
        }

        $model = Mage::getModel('profile/brand')->load($id);

        if ($model->getId() || $id == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
                if ($id) {
                    $model->setId($id);
                }
            }

            Mage::register('brand_data', $model);
            $this->_title($this->__('Edit Brand'));
            $this->loadLayout()
                    ->_setActiveMenu('profile/brand')
                    ->_addBreadcrumb(Mage::helper('adminhtml')->__('Edit Brand'),Mage::helper('adminhtml')->__('Edit Brand'))
                    ->_addContent($this->getLayout()->createBlock('profile/adminhtml_brand_edit'))
//                    ->_addLeft($this->getLayout()->createBlock('adminhtml/store_switcher'))
                    ->_addLeft($this->getLayout()->createBlock('profile/adminhtml_brand_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Option set do not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost()) {
//            Zend_Debug::dump($data);die('asd');
            $model = Mage::getModel('profile/brand');
            if (isset($data['logo']['delete'])) {
                $data['logo'] = '';
            }
            if (isset($data['logo']['value'])) {
                $data['logo'] = $data['logo']['value'];
            }

            if (isset($_FILES['logo']['name']) && $_FILES['logo']['name'] != '') {
                try {
                    $uploader = new Varien_File_Uploader('logo');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media') . DS . 'profile_brand' . DS;
                    $uploader->save($path, $_FILES['logo']['name']);
                } catch (Exception $e) {
                    
                }
                $data['logo'] = $_FILES['logo']['name'];
            }

            $model->setData($data);
            if ($id = $this->getRequest()->getParam('id'))
                $model->setId($id);
            try {
                $model->save();
                $brandId = $model->getId();
                //save glasses
                if ($brandId) {
                    if (isset($data['options']['glasses'])) {
                        $glasses = $data['options']['glasses'];
                        ksort($glasses);
                        $glassIds = Mage::helper('profile')->getGlassIds();
                        foreach ($glasses as $key => $value) {
                            $modelGlass = Mage::getModel('profile/glasses');
                            $modelGlass->setData($value)->setBrand($brandId);
                            if (in_array($key, $glassIds)) {
                                $modelGlass->setId($key);
                            }
                            $modelGlass->save();
                        }
                    }
                }
                //save lens
                if ($brandId) {
                    if (isset($data['options']['lens'])) {
                        $lens = $data['options']['lens'];
                        ksort($lens);
                        $lenIds = Mage::helper('profile')->getLenIds();
                        foreach ($lens as $key => $value) {
                            $modelLen = Mage::getModel('profile/lens');
                            $modelLen->setData($value)->setBrand($brandId);
                            if (in_array($key, $lenIds)) {
                                $modelLen->setId($key);
                            }
                            $modelLen->save();
                        }
                    }
                }
                //save coating
                if ($brandId) {
                    if (isset($data['options']['coating'])) {
                        $coatings = $data['options']['coating'];
                        // foreach ($coatings as $key => &$coating) {
                            
                        // }
                        
                        ksort($coatings);
                        $coatingIds = Mage::helper('profile')->getCoatingIds();
                        foreach ($coatings as $key => &$value) {
                            $value['subprice'] = serialize($value['subprice']);

                            $modelCoating = Mage::getModel('profile/coating');
                            $modelCoating->setData($value)->setBrand($brandId); 
                            // Zend_debug::dump($modelCoating->getData());die('sss');
                            if (in_array($key, $coatingIds)) {
                                $modelCoating->setId($key);
                            }
                            $modelCoating->save();
                        }
                    }
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('profile')->__('Item was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('profile')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('profile/brand');
                $model->setId($this->getRequest()->getParam('id'))->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Data was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $formIds = $this->getRequest()->getParam('brandForm');
        if (!is_array($formIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($formIds as $formId) {
                    $formData = Mage::getModel('profile/brand')->load($formId);
                    $formData->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Total of %d record(s) were successfully deleted', count($formIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $formIds = $this->getRequest()->getParam('brandForm');
        if (!is_array($formIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($formIds as $formId) {
                    $formData = Mage::getSingleton('profile/brand')->load($formId)->setStatus($this->getRequest()->getParam('status'))->setIsMassupdate(TRUE)->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d record(s) were successfully updated', count($formIds)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function gridAction() {
        $this->loadLayout();
        $body = $this->getLayout()->createBlock('profile/adminhtml_brand_grid')->toHtml();
        $this->getResponse()->setBody($body);
    }

    public function editPresAction() {
        Zend_debug::dump('asdasd');die();
    }

}
