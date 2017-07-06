<?PHP

class Meigee_Thememanager_Model_PageTypeConfigs_Thememanager_Adminhtml_Thememanager extends Meigee_Thememanager_Model_PageTypeConfigs_Basis
{

    function getStoreThemenamespace()
    {
        if (is_null($this->themenamespace))
        {
            if (Mage::app()->getRequest()->getParam('theme_id'))
            {
                self::$theme_id = (int)Mage::app()->getRequest()->getParam('theme_id');
                $this->themenamespace = Mage::getModel('thememanager/themes')->load(self::$theme_id)->getThemenamespace();
            }
            elseif(Mage::app()->getRequest()->getParam('theme'))
            {
                $this->themenamespace = Mage::app()->getRequest()->getParam('theme');
            }
        }
        return $this->themenamespace;
    }


    function getWheteAttributes()
    {
        self::$theme_id = (int)Mage::app()->getRequest()->getParam('theme_id');
        $theme = Mage::getModel('thememanager/themes')->load(self::$theme_id);

        if ($theme->getId())
        {
            $this->setWhereAttribute('theme_id', self::$theme_id, 'OR');
            $store = $theme->getStoreId();

            switch ($theme->getType())
            {
                case self::DefaultType:
                    break;
                case self::StoreType:
                case self::AllProductType:
                case self::AllCategoryType:
                case self::AllCmsPagesType:
                    $this->setWhereAttribute('type', self::DefaultType, 'OR', $store);
                    $this->setWhereAttribute('type', self::StoreType, 'OR', $store);
                    break;

                case self::ProductType:
                    $this->setWhereAttribute('type', self::DefaultType, 'OR', $store);
                    $this->setWhereAttribute('type', self::StoreType, 'OR', $store);
                    $this->setWhereAttribute('type', self::AllProductType, 'OR', $store);
                    break;
                case self::CategoryType:
                    $this->setWhereAttribute('type', self::DefaultType, 'OR', $store);
                    $this->setWhereAttribute('type', self::StoreType, 'OR', $store);
                    $this->setWhereAttribute('type', self::AllCategoryType, 'OR', $store);
                    break;
                case self::CmsPagesType:
                    $this->setWhereAttribute('type', self::DefaultType, 'OR', $store);
                    $this->setWhereAttribute('type', self::StoreType, 'OR', $store);
                    $this->setWhereAttribute('type', self::AllCmsPagesType, 'OR', $store);
                    break;
                default :
                    break;
            }
        }




    }



}

