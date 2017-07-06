<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Radio_Ico extends Meigee_Thememanager_Block_Adminhtml_Forms_Radio
{
    public function getFormElement()
    {
        foreach ($this->params['values'] AS $key=>$value)
        {
            $used_value = $this -> getUsedValue($key, $value);
            if ('__empty__' == $value['value'])
            {
                $val = $this->getConfigId();
            }
            else
            {
                $img = basename($used_value);
                $val_arr = explode('.', $img);
                $val = $val_arr[0];
            }

            $active = '';
            if ($used_value == $this->getConfigValue())
            {
                $active = ' active';
            }


            $src = Mage::getDesign()->getSkinUrl("thememanager/images/".$val.'.png', array('_default'=>true));
            $this->prefix_html[$used_value] = '<div class="meigee-thumb'.$active.'"><img src="'.$src.'" /></div>';
        }
        return parent::getFormElement();
    }
}




