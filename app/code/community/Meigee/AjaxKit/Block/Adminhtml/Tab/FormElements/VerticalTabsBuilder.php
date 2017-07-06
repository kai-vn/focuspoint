<?php

class Meigee_AjaxKit_Block_Adminhtml_Tab_FormElements_VerticalTabsBuilder
{
    protected $content_arr;
    protected $preview;

    function setContent($content)
    {
        $this->content_arr = $content;
        return $this;
    }
    function setPreviewClass($class_name)
    {
        $this->preview = $class_name;
        return $this;
    }
    function getPreview()
    {
        $preview_html = '';
        if ($this->preview && class_exists('Meigee_AjaxKit_Block_Adminhtml_'.$this->preview))
        {
            $preview_html = Mage::app()->getLayout()->getBlockSingleton('ajaxKit/adminhtml_'.$this->preview)->getPreview();
        }
        return $preview_html;
    }
    function getHtml()
    {
        if (!isset($this->content_arr['main']))
        {
            $this->content_arr['main']='';
        }

        $preview_html = $this->getPreview();
        $html = '<div class="meigee-ajaxkit-preview top">
					'.$preview_html.'
                </div>

                <div class="meigee-ajaxkit-submodule-status">
                     '.$this->content_arr['status'].'
                </div>

                <div class="horizontal-tabs-wrapper">
                    '.(count($this->content_arr['tabs']) > 1 ? $this->content_arr['main'] : '').'
                <div class="horizontal-tabs-content">
                ';
        $is_active = true;
        foreach ($this->content_arr['tabs'] AS $name =>$element_tab )
        {
            $element_tab = $element_tab + array('preview'=>'', 'elements'=>array());
            $html .= '<div class="tab-content '.($is_active ? 'active' : '').'" data-tab-name="'.$name.'">
                            <div class="meigee-ajaxkit-preview">
                                '.$element_tab['preview'].'
                            </div>
                            <div class="meigee-ajaxkit-descr-top">
                                '.(isset($element_tab['descr_top']) ? $element_tab['descr_top'] : '').'
                            </div>
                            '.implode('', $element_tab['elements']).'
                            <div class="meigee-ajaxkit-descr-bottom">
                                '.(isset($element_tab['descr_bottom']) ? $element_tab['descr_bottom'] : '').'
                            </div>
                    </div>';
            $is_active = false;
        }
        $html .= '</div>';
        return $html;               //
    }

}