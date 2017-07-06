<?php
class Meigee_AjaxKit_Model_UpdateLayout
{
    public function getConfigs($blocks, $need_to_load_arr = array())
    {
        $layout = Mage::app()->getLayout();
        $update = $layout->getUpdate();

        if (!is_array($need_to_load_arr)) {
            $need_to_load_arr = (array)$need_to_load_arr;
        }

        if (!empty($need_to_load_arr))
        {
            $update->load($need_to_load_arr);
        }

        foreach ($blocks AS $block_name => $block_type) {
            $layout->createBlock($block_type, $block_name);
        }
        $layout->generateXml();
        $layout->generateBlocks();
        return $layout;
    }

    function getLayoutJsCss(&$result)
    {
        $layout = Mage::app()->getLayout();
        $js_css_html = $layout->getBlock("head")->getCssJsHtml();
        $js_css_html_arr = explode("\n", trim($js_css_html));

        $result['head_html'] = '';
        $result['head_js_css'] = array();

        $baseJsUrl = Mage::getBaseUrl('js');
        $designPackage = Mage::getDesign();

        foreach ($layout->getBlock("head")->getItems() AS $item)
        {
            if (empty($item['if']))
            {
                switch($item['type'])
                {
                    case 'js_css':
                        $result['head_js_css'][] = array('name'=>'link', 'attributes'=>array(
                            'href' => $baseJsUrl.$item['name']
                        ));
                        break;
                    case 'js':
                        $result['head_js_css'][] = array('name'=>'script', 'attributes'=>array(
                            'src' => $baseJsUrl.$item['name']
                        ));
                        break;
                    case 'skin_css':
                        $result['head_js_css'][] = array('name'=>'link', 'attributes'=>array(
                            'href' => $designPackage->getSkinUrl($item['name'], array())
                        ));
                        break;
                    case 'skin_js':
                        $result['head_js_css'][] = array('name'=>'script', 'attributes'=>array(
                            'src' => $designPackage->getSkinUrl($item['name'], array())
                        ));
                        break;
                    default:
                        continue;
                        break;
                }
            }
        }
        return $result;
    }
}
