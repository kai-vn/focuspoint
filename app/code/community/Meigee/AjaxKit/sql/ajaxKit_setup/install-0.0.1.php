<?php

$installer = $this;
$installer->startSetup();
Mage::getModel('ajaxKit/configsReader')->getConfigOptions(true);
$installer->endSetup();
