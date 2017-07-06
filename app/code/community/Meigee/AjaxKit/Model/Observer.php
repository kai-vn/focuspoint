<?php
class Meigee_AjaxKit_Model_Observer
{
    function Observer(Varien_Event_Observer $event, $method)
    {
        $post = Mage::app()->getRequest()->getParams();
        if (isset($post['isAjax']) && isset($post['useObserver']) && isset($post['submodule']))
        {
            $submodule_arr = explode('_', $post['submodule']);
            $submodule_arr = array_map('ucfirst', $submodule_arr);
            $model = Mage::getSingleton('ajaxKit/Observer_'.implode('', $submodule_arr));

            if (method_exists($model, $method))
            {
                return $model->$method($event);
            }
        }
        return false;
    }

    function ControllerActionPostdispatch(Varien_Event_Observer $event)
    {
        $result = $this->Observer($event, 'ControllerActionPostdispatch');
        if ($result)
        {
            echo Mage::helper('core')->jsonEncode($result);
            die();
        }
    }

    function ControllerActionPostdispatchObserver(Varien_Event_Observer $event)
    {
        $this->Observer($event, 'ControllerActionPostdispatchObserver');
    }










}
