<?PHP
class Meigee_Thememanager_Model_PageTypeConfigs_Default extends Meigee_Thememanager_Model_PageTypeConfigs_Basis
{
    function getWheteAttributes()
    {
        $this->setWhereAttribute('type', self::DefaultType, 'OR');
        $this->setWhereAttribute('type', self::StoreType, 'OR');
        $this->setStoreThemenamespace();
    }
}

