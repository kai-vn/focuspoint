<?php

$installer = $this;

$installer->startSetup();

$installer->run("
ALTER TABLE  {$this->getTable('sales/quote_address')} ADD  `charges_amount` DECIMAL( 12, 2 ) NOT NULL;
ALTER TABLE  {$this->getTable('sales/quote_address')} ADD  `base_charges_amount` DECIMAL( 12, 2 ) NOT NULL;
ALTER TABLE  {$this->getTable('sales/order')} ADD  `charges_amount` DECIMAL( 12, 2 ) NOT NULL;
ALTER TABLE  {$this->getTable('sales/order')} ADD  `base_charges_amount` DECIMAL( 12, 2 ) NOT NULL;
ALTER TABLE  {$this->getTable('sales/invoice')} ADD  `charges_amount` DECIMAL( 12, 2 ) NOT NULL;
ALTER TABLE  {$this->getTable('sales/invoice')} ADD  `base_charges_amount` DECIMAL( 12, 2 ) NOT NULL;
ALTER TABLE  {$this->getTable('sales/creditmemo')} ADD  `charges_amount` DECIMAL( 12, 2 ) NOT NULL;
ALTER TABLE  {$this->getTable('sales/creditmemo')} ADD  `base_charges_amount` DECIMAL( 12, 2 ) NOT NULL;
");

$installer->endSetup();
