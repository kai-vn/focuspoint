<?php

$installer = $this;
$installer->startSetup();


$table = $installer->getConnection()->newTable($installer->getTable('thememanager/table_themes'))
    ->addColumn('theme_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true,
    ), 'Theme Id')

    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
        'nullable' => false,
    ), 'Name')

    ->addColumn('themenamespace', Varien_Db_Ddl_Table::TYPE_TEXT, 100, array(
        'nullable' => false,
        'index' => 'themenamespace'
     ), 'Themenamespace')

    ->addColumn('type', Varien_Db_Ddl_Table::TYPE_TEXT, 20, array(
        'nullable' => false,
        'index' => 'type'
     ), 'Type')

    ->addColumn('type_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'default' => 0
    ), 'Type Order')

    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
        'index' => 'store_id'
    ), 'store_id')

    ->addColumn('add_date', Varien_Db_Ddl_Table::TYPE_INTEGER, 15, array(
        'unsigned' => true,
    ), 'add date')

    ->addColumn('last_modified_date', Varien_Db_Ddl_Table::TYPE_INTEGER, 15, array(
        'unsigned' => true,
    ), 'last modified date')
    ->addIndex($installer->getIdxName('thememanager/table_themes', array('themenamespace')),
        array('themenamespace'))
    ->addIndex($installer->getIdxName('thememanager/table_themes', array('type')),
        array('type'))
    ->addIndex($installer->getIdxName('thememanager/table_themes', array('type_order')),
        array('type_order'))
    ->addIndex($installer->getIdxName('thememanager/table_themes', array('store_id')),
        array('store_id'));

$installer->getConnection()->createTable($table);


$table = $installer->getConnection()->newTable($installer->getTable('thememanager/table_theme_config_data'))
    ->addColumn('theme_config_data_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true,
    ), 'theme config data id')

    ->addColumn('theme_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
        'index' => 'theme_id'
    ), 'theme id')

    ->addColumn('alias', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => false,
    ), 'alias')

    ->addColumn('value', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => false,
    ), 'value')

    ->addColumn('is_system', Varien_Db_Ddl_Table::TYPE_TEXT, 1, array(
        'nullable' => false,
        'default' => 'N'
    ), 'is_system')

    ->addIndex($installer->getIdxName('thememanager/table_themes', array('theme_id')),
        array('theme_id'))
    ->addIndex($installer->getIdxName('thememanager/table_themes', array('alias')),
        array('alias'))
    ->addForeignKey($installer->getFkName('thememanager/table_theme_config_data', 'theme_id', 'thememanager/table_themes', 'theme_id'),
        'theme_id', $installer->getTable('thememanager/table_themes'), 'theme_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE);





$installer->addAttribute('catalog_product', 'meigee_product_theme_id', array(
    'type'          => 'int',
//    'input_renderer'    => 'thememanager/catalog_product_helper_form_example',
    "backend"  => false,
    "frontend" => false,
    'default' => 0,
    'default_value' => 0,
    'sort_order' => 5,
    "label"    => "Meigee Product Theme Id",
    'is_visible'       => false,
    'required'      => false,
    'user_defined' => 0,
    'searchable' => false,
    'filterable' => false,
    'comparable'    => false,
    'visible_on_front' => false,
    'visible_in_advanced_search'  => false,
    'is_html_allowed_on_front' => false,
    'global'     => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    "note"       => "Do not change value! Is used by Meigee ThemeManager."
));


$installer->addAttribute("catalog_category", "meigee_category_theme_id",  array(
    "type"     => "int",
    "backend"  => false,
    "frontend" => false,
    "label"    => "Meigee Category Theme Id",
    "class"    => "",
    "source"   => "",
    "global"   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    "is_visible" => false,
    "required" => false,
    "user_defined"  => 0,
    "default" => "",
    'default_value' => 0,
    "searchable" => false,
    "filterable" => false,
    "comparable" => false,
    "visible_on_front"  => false,
    'visible_in_advanced_search'  => false,
    "note"       => "Do not change value! Is used by Meigee ThemeManager."

));



/*
*/




$installer->getConnection()->createTable($table);
$installer->endSetup();


