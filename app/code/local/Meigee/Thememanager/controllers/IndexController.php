<?php
class Meigee_Thememanager_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function googleContactMapAction()
    {
        $contentBlock = $this->getLayout()->createBlock('thememanager/frontend_google_contactMapPage')->_getHtml();
        $this->getResponse()->setBody($contentBlock);
    }



    public function contactAction()
    {
        $contentBlock = $this->getLayout()->createBlock('core/template')->setTemplate('contacts/form_footer.phtml')->toHtml();
        $this->getResponse()->setBody($contentBlock);
    }


}
