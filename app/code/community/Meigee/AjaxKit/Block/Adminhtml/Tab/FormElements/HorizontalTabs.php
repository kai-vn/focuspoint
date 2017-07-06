<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_HorizontalTabs
{
    protected $info_arr;
    protected $html_elements;

    function setDataInfo($info_arr)
    {
        $this->info_arr = $info_arr;
        return $this;
    }

    function setDataHtmlElements($html_elements)
    {
        $this->html_elements = $html_elements;
        return $this;
    }

    function getBlockHorizontalTabsHtml()
    {
        $html = '<ul class="horizontal-tabs">';
        $is_active = true;

        foreach($this->info_arr AS $name=>$tab)
        {
            if(isset($this->html_elements['tabs'][$name]))
            {
                $html .= '<li class="tab '.($is_active ? 'active' : '').'" data-tab-name="'.$name.'" >
                            <a href="#" title="'.$tab->title.'">'.$tab->title.'</a>
                        </li>';
                $is_active = false;
            }
        }
        $html .= '</ul>';
        return $html;
    }
}