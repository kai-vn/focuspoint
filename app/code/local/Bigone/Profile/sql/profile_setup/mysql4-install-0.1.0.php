<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('profile_brand')};
CREATE TABLE {$this->getTable('profile_brand')} (
  `brand_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `logo` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  `sort_order` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('profile_glasses')};
CREATE TABLE {$this->getTable('profile_glasses')} (
  `glasses_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `subtitle` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `tooltip` varchar(255) NOT NULL default '',
  `price` decimal(12,4) NOT NULL default '0.0000',
  `brand` int(11) unsigned NOT NULL,
  `status` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`glasses_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('profile_lens')};
CREATE TABLE {$this->getTable('profile_lens')} (
  `lens_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `subtitle` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `price` decimal(12,4) NOT NULL default '0.0000',
  `brand` int(11) unsigned NOT NULL,
  `status` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`lens_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('profile_coating')};
CREATE TABLE {$this->getTable('profile_coating')} (
  `coating_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `price` decimal(12,4) NOT NULL default '0.0000',
  `brand` int(11) unsigned NOT NULL,
  `status` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`coating_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS {$this->getTable('profile_product_brand')};
CREATE TABLE {$this->getTable('profile_product_brand')} (
  `assign_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned NOT NULL,
  `brands` varchar(255) NOT NULL default '',
  PRIMARY KEY (`assign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 