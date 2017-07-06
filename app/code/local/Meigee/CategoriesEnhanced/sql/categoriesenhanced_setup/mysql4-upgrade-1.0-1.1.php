<?php
$installer = $this;
$installer->startSetup();
// Mega Menu
$installer->addAttribute('catalog_category', 'meigee_cat_icon', array(
    'group'             => 'General Information',
    'label'             => 'Menu icon',
    'note'              => "",
    'type'              => 'text',
    'input'             => 'text',
    'visible'           => true,
    'required'          => false,
    'backend'           => '',
    'frontend'          => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'user_defined'      => true,
    'visible_on_front'  => true,
    'wysiwyg_enabled'   => true,
    'is_html_allowed_on_front'  => true,
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
	'sort_order' => 2,
));
$installer->endSetup();
