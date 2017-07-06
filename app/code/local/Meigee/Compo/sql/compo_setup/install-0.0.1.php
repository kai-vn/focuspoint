<?php
$installer = $this;
$installer->startSetup();

Mage::getConfig()->saveConfig('meigee/thememanager/installed/compo', 'Compo', 'default', 0);

// $RevSlider = new Meigee_Thememanager_Rewrite_RevSlider();
// $file = __DIR__ . DS . "compo_slider.zip";

// $RevSlider->installFile($file);


$adminVersion = Mage::getConfig()->getModuleConfig('Mage_Admin')->version;
if(version_compare($adminVersion, '1.6.1.2', '>=') && class_exists('Mage_Admin_Model_Block'))
{
    $blockNames = array(
        'page/switch',
        'newsletter/subscribe'
    );
    foreach ($blockNames as $blockName)
    {
        $whitelistBlock = Mage::getModel('admin/block')->load($blockName, 'block_name');
        $whitelistBlock->setData('block_name', $blockName);
        $whitelistBlock->setData('is_allowed', 1);
        $whitelistBlock->save();
    }
}

$installer->endSetup();
