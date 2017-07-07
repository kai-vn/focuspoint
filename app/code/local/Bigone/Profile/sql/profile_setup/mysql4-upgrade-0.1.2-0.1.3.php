<?php

$installer = $this;
$installer->startSetup();
$installer->run("
    
ALTER TABLE {$this->getTable('profile_lens')} ADD `tooltip` varchar(255) NOT NULL default '' after `description`;
ALTER TABLE {$this->getTable('profile_coating')} ADD `tooltip` varchar(255) NOT NULL default '' after `description`;
ALTER TABLE {$this->getTable('profile_coating')} ADD `subtitle` varchar(255) NOT NULL default '' after `title`;

");

$installer->endSetup();
