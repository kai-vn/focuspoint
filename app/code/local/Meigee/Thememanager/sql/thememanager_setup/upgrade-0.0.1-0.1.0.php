<?php

$installer = $this;
$installer->startSetup();
$tableName = $installer->getTable('newsletter_subscriber');

$installer->getConnection()->addColumn($tableName, 'thememanager_subscriber_firstname', array(
    'nullable' => true,
    'length' => 255,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'added from extension Thememanager'
));
$installer->getConnection()->addColumn($tableName, 'thememanager_subscriber_lastname', array(
    'nullable' => true,
    'length' => 255,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'added from extension Thememanager'
));
$installer->endSetup();
