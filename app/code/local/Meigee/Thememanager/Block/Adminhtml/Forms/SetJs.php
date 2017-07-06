<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_SetJs extends Varien_Data_Form_Element_Abstract
{
    public function getElementHtml()
    {
        $html[] = '<script type="text/javascript">
                      updateTrAttributes();';

        $tabs = $this->getUpdateTabs() ? $this->getUpdateTabs() : array();
        $html[] = ' setUpdateTabs(\'' . json_encode($tabs) . '\');';

        if ($this->getDependsArray())
        {
            $html[] = ' setNewDependsJson(\'' . json_encode($this->getDependsArray()) . '\');';
        }

        if ($this->getHideRowsArray())
        {
            $html[] = ' setHiddenRows(\'' . json_encode($this->getHideRowsArray()) . '\');';
            $html[] = ' setShowedRows();';
        }

        if ($this->getIsCheckCustomStyle())
        {
            $html[] = ' checkCustomStyle();';
        }

        $html[] = "hideJsRows()";


        $html[] = '</script>';
        $html[] = ' <input type="hidden" class="hided_js_row">';
        return implode("\n ", $html);
    }
}







