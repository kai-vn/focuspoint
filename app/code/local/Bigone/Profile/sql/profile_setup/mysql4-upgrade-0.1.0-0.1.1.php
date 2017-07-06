<?php

$installer = $this;
$setup = new Mage_Sales_Model_Mysql4_Setup('core_setup');
$installer->startSetup();
$setup->addAttribute(
        'order_item', 'bigone_profile_data', array(
        'type' => 'text', 'default' => '', 'visible' => true
        )
);
$setup->addAttribute(
        'quote_item', 'bigone_profile_data', array(
        'type' => 'text', 'default' => '', 'visible' => true
        )
);
$setup->addAttribute(
        'invoice_item', 'bigone_profile_data', array(
        'type' => 'text', 'default' => '', 'visible' => true
        )
);
$setup->addAttribute(
        'creditmemo_item', 'bigone_profile_data', array(
        'type' => 'text', 'default' => '', 'visible' => true
        )
);
$setup->addAttribute(
        'shipment_item', 'bigone_profile_data', array(
        'type' => 'text', 'default' => '', 'visible' => true
        )
);
$installer->endSetup();
