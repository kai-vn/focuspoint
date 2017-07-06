<?php
class Meigee_AjaxKit_Block_Frontend_Submodules extends Mage_Core_Block_Abstract
{
    protected $submodule_config = array();

    function setConfigs($submodule_config)
    {
        $this->submodule_config = $submodule_config;
        return $this;
    }

    function getConfig($key)
    {
        if (isset($this->submodule_config[$key]))
        {
            return $this->submodule_config[$key];
        }
        return false;
    }
}

