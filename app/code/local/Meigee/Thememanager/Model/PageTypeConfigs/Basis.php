<?PHP

class Meigee_Thememanager_Model_PageTypeConfigs_Basis extends Meigee_Thememanager_Model_PageTypeConfigs_Instance
{
    const DefaultType = '_default';
    const StoreType = 'store';
    const DefaultName = 'Default';
    const DefaultStoreId = 0;

    const AllProductType = 'all_product';
    const AllCategoryType = 'all_category';
    const AllCmsPagesType = 'all_cms_page';
    const ProductType = 'product';
    const CategoryType = 'category';
    const CmsPagesType = 'cms_page';

    private $whete_attributes = array();
    private $theme_config_info = null;
    protected $themenamespace = null;

    function getAllTypes($is_use_default = false)
    {
        $types = array(
                            'store' => array('level'=>10, 'label'=>'Store', 'value'=>self::StoreType, 'is_single'=>true, 'visible'=>false)
                        , 'all_product' => array('level'=>20, 'label'=>'All Products', 'value'=>self::AllProductType, 'is_single'=>true, 'visible'=>true)
                        , 'product' => array('level'=>30, 'label'=>'Product', 'value'=>self::ProductType, 'is_single'=>false, 'visible'=>true)
                        , 'all_category' => array('level'=>20, 'label'=>'All Categories', 'value'=>self::AllCategoryType, 'is_single'=>true, 'visible'=>true)
                        , 'category' => array('level'=>30, 'label'=>'Category', 'value'=>self::CategoryType, 'is_single'=>false, 'visible'=>true)
                        , 'all_cms_page' => array('level'=>20, 'label'=>'All CMS Pages', 'value'=>self::AllCmsPagesType, 'is_single'=>true, 'visible'=>true)
                        , 'cms_page' => array('level'=>30, 'label'=>'CMS Page', 'value'=>self::CmsPagesType, 'is_single'=>false, 'visible'=>true)
                    );

        if ($is_use_default)
        {
            $types['_default'] = array('level'=>1, 'label'=>'Default', 'value'=>self::DefaultType, 'is_single'=>true);
        }


        return $types;
    }


    protected function setWhereAttribute($name, $value, $action, $store = false)
    {
        if ('OR' == $action && 'type' == $name)
        {
            $store = ($value == self::DefaultType) ? self::DefaultStoreId : (!$store ? Mage::app()->getStore()->getStoreId() : $store);
            $this->whete_attributes[$action][] = "(`mt`.`" . $name . "`='" . $value . "' AND `mt`.`store_id`='" . $store . "')";
        }
        else
        {
            $this->whete_attributes[$action][] = "(`mt`.`" . $name . "`='" . $value . "')";
        }
    }

    function getStoreThemenamespace()
    {
        if (is_null($this->themenamespace))
        {
            $meige_theme = Mage::getStoreConfig('design/package/meigee_package_name');
            $this->themenamespace = $meige_theme ? $meige_theme : Mage::getStoreConfig('design/package/name');
        }
        return $this->themenamespace;
    }


    protected function setStoreThemenamespace()
    {
        $this->setWhereAttribute('themenamespace', $this->getStoreThemenamespace(), 'AND');
    }



    function processingDefaultThemeInfo()
    {
        $default = Mage::helper('thememanager/themeConfig')->getThemeConfigByAliases();
        $default_resp = array();
        if ($default)
        {
            foreach ($default AS $alias => $data)
            {
                $data['default'] = empty($data['default']) ? '' : $data['default'];
                $default_resp[$alias] = array('value' => $data['default'], 'type' => self::DefaultType);
            }
        }
        return $default_resp;
    }

    function getConfigDataResult()
    {
        $this->getWheteAttributes();
        $sql_where_arr = array();

        foreach ($this->whete_attributes AS $action => $whete_attribute)
        {
            $sql_where_arr[] = "(" . implode(' '.$action . ' ', $whete_attribute) . ")";
        }
        $sql_where_str = implode(" AND ", $sql_where_arr);

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $table_themes = $resource->getTableName('thememanager/table_themes');
        $table_theme_config_data = $resource->getTableName('thememanager/table_theme_config_data');

        $sql = "SELECT `mt`.`type`, `e`.`alias`, `e`.`value`, `e`.`theme_id`
                      FROM ".$table_themes." AS `mt`
                        INNER JOIN ".$table_theme_config_data." AS `e` ON `e`.`theme_id` = `mt`.`theme_id`
                    WHERE " . $sql_where_str ."
                    ORDER BY `mt`.`type_order`";
        return $readConnection->fetchAll($sql);
    }

    function getThemesInfo()
    {
        if (is_null($this->theme_config_info))
        {
            $this->theme_config_info = array();

            $theme_id_is_null = is_null(self::$theme_id);

/**///            $this->whete_attributes['AND'][] = "(`e`.`is_system`='N')";
            $values = $this->getConfigDataResult();
            $values_sorting = array();
            foreach ($values AS $el)
            {
                $values_sorting[$el['alias']] = array('value' => $el['value'], 'type' => $el['type']);
                if ($theme_id_is_null)
                {
                    self::$theme_id = $el['theme_id'];
                }
            }

            $this->theme_config_info['configs'] = array_merge(
                $this->processingDefaultThemeInfo()
                , empty($values_sorting) ? array() : $values_sorting
            );
        }


        return $this->theme_config_info;
    }

    function getSystemThemesInfo($system_name)
    {
/**///        $this->whete_attributes['AND'][] = "(`e`.`is_system`='Y')";
        $this->whete_attributes['AND'][] = "(`e`.`alias`='".$system_name."')";
        return $this->getConfigDataResult();
    }

    function getAdvancedStyling()
    {
        $__AdvancedStyling_arr = $this->getSystemThemesInfo('__AdvancedStyling');

/**/
return isset($__AdvancedStyling_arr[0]) ? $__AdvancedStyling_arr[0]['value'] : false;
/**/

        $result = array();
        foreach ($__AdvancedStyling_arr AS $theme_style)
        {
            $unserialized_value = unserialize($theme_style['value']);
            foreach ($unserialized_value['AdvancedStyling'] AS $name => $value)
            {
                $key = (isset($value['name'])) ? $value['name'] : $name;
                $value['type'] = $theme_style['type'];
                $result[$key] = $value;
            }
        }
        return $result;
    }








}

