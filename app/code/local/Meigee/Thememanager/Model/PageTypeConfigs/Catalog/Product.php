<?PHP

class Meigee_Thememanager_Model_PageTypeConfigs_Catalog_Product extends Meigee_Thememanager_Model_PageTypeConfigs_Basis
{
    function getWheteAttributes()
    {
        $product = Mage::registry('current_product');
        if ($product)
        {
            $theme_id = $product->getMeigeeProductThemeId();
            if ($theme_id)
            {
                $this->setWhereAttribute('theme_id', $theme_id, 'OR');
                self::$theme_id = $theme_id;
            }
        }

        $this->setWhereAttribute('type', self::AllProductType, 'OR');
        $this->setWhereAttribute('type', self::DefaultType, 'OR');
        $this->setWhereAttribute('type', self::StoreType, 'OR');
        $this->setStoreThemenamespace();
    }

}

