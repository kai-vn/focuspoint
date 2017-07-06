<?PHP

class Meigee_Thememanager_Model_PageTypeConfigs_Catalog_Category extends Meigee_Thememanager_Model_PageTypeConfigs_Basis
{
    function getWheteAttributes()
    {
        $category = Mage::registry('current_category');
        if ($category)
        {
            $theme_id = $category->getMeigeeCategoryThemeId();
            if ($theme_id)
            {
                $this->setWhereAttribute('theme_id', $theme_id, 'OR');
                self::$theme_id = $theme_id;
            }
        }
        $this->setWhereAttribute('type', self::AllCategoryType, 'OR');
        $this->setWhereAttribute('type', self::DefaultType, 'OR');
        $this->setWhereAttribute('type', self::StoreType, 'OR');
        $this->setStoreThemenamespace();
    }
}

