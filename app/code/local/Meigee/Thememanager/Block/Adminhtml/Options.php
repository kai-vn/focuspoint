<?php
class Meigee_Thememanager_Block_Adminhtml_Options extends Mage_Adminhtml_Block_Widget_Form_Container
{
    static $theme_id = false;
    private static $_static_blocks = false;
    private static $_static_pages = false;

    protected function _construct()
    {
        $this->_blockGroup = 'thememanager';
        $this->_controller = 'adminhtml';
        $this->_mode = 'options';
        self::$theme_id = Mage::app()->getRequest()->getParam('theme_id');
    }

    public function getHeaderText()
    {
        $helper = Mage::helper('thememanager');
        $theme_config_data = Mage::getModel('thememanager/themes')->load(self::$theme_id);

        $this->removeButton('save');

        if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/edit'))
        {
            $this->_addButton('save', array(
                'label'     => $helper->__('<i class="fa fa-floppy-o"></i>Save'),
                'name'     => 'save',
                'onclick'   => 'save()'
            ), 2, 6);

            $this->_addButton('save_and_stay', array(
                'label'     => $helper->__('<i class="fa fa-floppy-o"></i>Save and continue edit'),
                'name'     => 'save_and_stay',
                'onclick'   => 'saveAndStay()'
            ), 1, 5);
        }

        // $this->_addButton('help_guide_button', array(
            // 'label'     => $helper->__('<i class="fa fa-life-ring"></i>Help'),
            // 'name'     => 'help_guide',
            // 'onclick'   => 'showHelpGuide()',
            // 'class'     => 'help'
        // ), 3, 8);

        $theme=$helper-> getThemeNamespace();
        $url = $this->getUrl("*/*/themeConfig", array('theme'=>$theme));
        $this->_addButton('back_to_list', array(
            'label'     => $helper->__('<i class="fa fa-chevron-left"></i>Back'),
            'name'     => 'back_to_list',
            'onclick'   => 'reloadTo(\''.$url.'\')',
            'class'     => 'back'
        ), 1, 1);

        $this->removeButton('reset');
        $this->removeButton('back');
        return $helper->__("Theme Settings") . '<span class="ThemeName">('.$theme_config_data->getName().')<span>' ;
    }

    public function getHeaderHtml()
    {
        return parent::getHeaderHtml() . $this->getHeaderCustomHtml();
    }


    private function getHeaderCustomHtml()
    {
        return '<div class="customHeader">'
               . '<div class="whatChanged">' . $this->getWhatChangedHtml() . "</div>"
               . '<div class="predefinedSelect">' . $this->getPredefinedSelectHtml(). "</div>"
               . '</div>';
    }

    private function getPredefinedSelectHtml()
    {
        if(!Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/edit'))
        {
            return '';
        }
        $helper = Mage::helper('thememanager');
        $url = $this->getUrl('*/*/savePredefinedCollection', array('theme_id'=>self::$theme_id));
        $url_admin_reset_delay = $this->getUrl('*/*/AdminResetDelay');

        $html = '<input type="hidden" id="predefinedSelectUrl" value="'.$url.'">';
        $html .= '<input type="hidden" id="AdminResetDelay" value="'.$url_admin_reset_delay.'">';
        return $html;
    }
    private function getWhatChangedHtml()
    {
        $changed_html = $this->getChangedElementsHtml();

        $html = '';
        if ($changed_html)
        {
            $helper = Mage::helper('thememanager');
            $url = $this->getUrl('*/*/resetChanges', array('theme_id'=>self::$theme_id));

            $html = '<a href="#" onclick="return showWhatChanged()">'.$helper->__("What Changed<i class='fa fa-question-circle'></i>").'</a>';
            $html .= '<div id="changes" class="hided_element">';
            $html .= '<form id="changes_form" method="post" enctype="multipart/form-data" action="'.$url.'" >' . $changed_html;


            if(Mage::getSingleton('admin/session')->isAllowed('meigee/thememanager/edit'))
            {
                $html .= '<a href="#" onclick="return resetChanges()">'.$helper->__("Reset").'</a>';
            }
            $html .= '<a href="#" onclick="return closeWhatChanged()">'.$helper->__("Close").'</a>' . '</div>';
            $html .= '</form>';
        }
        return $html;
    }

    private function getChangedElementsHtml()
    {
        $helper = Mage::helper('thememanager/themeConfig');

        $model = Mage::getModel('thememanager/themeConfigData');
        $changes = $model->getCollection()
                    ->addFieldToFilter('theme_id', self::$theme_id)
                    ->addFieldToFilter('is_system', 'N')
                    ->load();
        if ($changes->count() == 0)
        {
            return false;
        }

        $changes_html_values_arr = array();

        foreach ($changes AS $change)
        {
            $config = (array)$helper->getThemeConfigByAliase($change->getAlias()) + array('default'=>false,'result'=>false, 'type_adminhtml'=>false, 'block'=>'def', 'title'=>'');
            $value = "";

            if ($config['default'] == $change->getValue())
            {
                continue;
            }

            if (is_array($config['result']))
            {
                if (isset($config['result']['name']))
                {
                    $value = $config['result']['name'];
                }
                else
                {
                    $value = implode(', ', $config['result']);
                }
            }
            else
            {
                switch ($config['type_adminhtml'])
                {
                    case 'Input_Checkbox':
                        $value =  $config['result'] ? $helper->__('Yes') : $helper->__('No');
                    break;
//                    case 'Input_File':
//                    case 'Input_FileArray':
//                    case 'Input_Range':
//                    case 'Input':
//                    break;
                    case 'Select_Bool':
                        $config['values'][] = array('value'=>false, 'name'=>'Disabled');
                        $config['values'][] = array('value'=>"__empty__", 'name'=>'Disabled');
                    case 'Radio':
                    case 'Radio_Ico':
                    case 'Select_StaticBlock':
                        if (!self::$_static_blocks)
                        {
                            self::$_static_blocks = array();
                            $model = Mage::getModel('cms/block');
                            $static_block_collection = $model->getCollection();
                            $static_block_collection->getSelect() -> join(   array('e'=>Mage::getSingleton('core/resource')->getTableName('cms/block_store')),
                                'main_table.block_id = e.block_id '
                            )->group('main_table.block_id');

                            foreach ($static_block_collection AS $sb_el)
                            {
                                self::$_static_blocks['values'][] = array('value'=>$sb_el->getIdentifier(), 'name'=> $sb_el->getTitle());
                            }
                        }
                        $config = array_merge(self::$_static_blocks, $config);// self::$_static_blocks;
                    case 'Select':
                        foreach ($config['values'] AS $val)
                        {
                            if ($change->getValue() == $val['value'])
                            {
                                $value =  $val['name'];
                            }
                        }
                    break;
                    default:
                        $value = $config['result'] ? $config['result'] : 'Disabled';
                    break;
                }
            }
            $changes_html_values_arr[$config['block']][]= array('alias'=>$change->getAlias(), 'title'=>$config['title'], 'value'=>$helper->__($value));
        }

        $all_config_data = Mage::helper('thememanager/themeConfig')->getThemeConfigArray();
        $html = '<ul>';
        foreach($all_config_data['blocks'] AS $block)
        {
            foreach($block['params'] AS $param_name=>$param)
            {
                if (isset($changes_html_values_arr[$param_name]))
                {
                    $html .= '<li><span class="changed_blocks_title">' . strip_tags($block['bTitle']) . " / " . strip_tags($param['pTitle']) . '</span></li>';
                    foreach($changes_html_values_arr[$param_name] AS $changed_html)
                    {
                        $html .= '<li>
                                        <label>
                                            <input type="checkbox" name="aliases_to_delete[]" value="' . $changed_html['alias']. '">
                                            <span class="changed_title">' . $changed_html['title'] . ':</span><span class="changed_value">"' . $changed_html['value'] . '"</span>
                                        </label>
                                  </li>';
                    }
                }
            }

        }
        $html .= '</ul>';
        return $html;
    }
}

