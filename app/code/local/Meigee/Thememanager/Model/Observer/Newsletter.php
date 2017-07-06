<?php
class Meigee_Thememanager_Model_Observer_Newsletter
{
    public function saveBefore(Varien_Event_Observer $observer)
    {
        $subscriber = $observer->getEvent()->getSubscriber();
        $data = (array)Mage::app()->getRequest()->getParams();
        if(isset($data['email']))
        {
            if (isset($data['firstname']))
            {
                $subscriber->setThememanagerSubscriberFirstname($data['firstname']);
            }

            if (isset($data['lastname']))
            {
                $subscriber->setThememanagerSubscriberLastname($data['lastname']);
            }
        }
        return $this;
    }
}



