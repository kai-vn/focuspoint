<?php
class Meigee_AjaxKit_Block_Frontend_Popup extends Mage_Core_Block_Template
{
    public $content = array();

    function __construct()
    {
        $this->setTemplate('ajaxkit/popup.phtml');
        parent::__construct();
    }

    function setPopupContent($popup_content_arr)
    {
        foreach ($popup_content_arr AS $name=>$data)
        {
            $this->setData($name, $data);
        }
        $info_text_block = Mage::app()->getLayout()->createBlock('ajaxKit/frontend_popup_infoText')
            ->setPopupTextType($this->getTextType())
            ->setPopupText($this->getText());
        $this->setChild('info_text', $info_text_block);
        return $this;
    }

    function isHaveProductList()
    {
        return $this->getProductIdList() && count($this->getProductIdList());
    }

    function getPopupContent()
    {
        $static_block =  $this->getStaticBlockContent();
        $html_content = array();

        if (!empty($static_block))
        {
            $html = $this->getLayout()->createBlock('cms/block')->setBlockId($static_block)->toHtml();
            $cms_block_html = str_replace("{{","\r\n{{", $html);
            $pattern = '/{{ajaxKit(.*)}}/';
            preg_match_all($pattern, $cms_block_html, $html_matches);
            if (isset($html_matches[1]))
            {
                foreach ($html_matches[1] AS $tag_i=>$tag_text)
                {
                    $tag_md5 = md5($html_matches[0][$tag_i]);

                    if (isset($html_content[$tag_md5]))
                    {
                        continue;
                    }
                    $pattern = "/(\S+)=[\"']?((?:.(?![\"']?\s+(?:\S+)=|[>\"']))+.)[\"']?/";
                    preg_match_all($pattern, $tag_text, $attributes_matches);
                    $html_attributes = array();
                    if (isset($attributes_matches[1]) && isset($attributes_matches[2]))
                    {
                        foreach ($attributes_matches[1] AS $i => $field)
                        {
                            $html_attributes[$field] = isset($attributes_matches[2][$i]) ? trim($attributes_matches[2][$i], '"\'') : '';
                        }
                        $html_attributes['__replace_content'] = $html_matches[0][$tag_i];
                    }
                    $html_content[$tag_md5] = $html_attributes;
                }
            }

            foreach ($html_content AS $block)
            {
                if (!isset($block['type']))
                {
                    continue;
                }

                $app_block = $this->getAppBlock($block['type']);
                if ($app_block)
                {
                    foreach ($block AS $attribute_name => $attribute)
                    {
                        $app_block->setData($attribute_name, $attribute);
                    }

                    $app_block_html = $app_block->toHtml();
                    if ($block['__replace_content'])
                    {
                        $html = str_replace($block['__replace_content'], $app_block_html, $html);
                    }
                    else
                    {
                        $html .= $app_block_html;
                    }
                }
            }
        }
        else
        {
            $layout = Mage::getModel('ajaxKit/updateLayout')->getConfigs(array(), array('ajaxkit_popup_'.$this->submoduleLayoutName));
            $layout_block = $layout->getBlock("ajaxkit_popup_content_default");
            foreach($this->getData() AS $field=>$value)
            {
                $layout_block->setData($field, $value);
            }
            $html = $layout_block->toHtml();
        }
       return $html;
    }
}