<?php

class Meigee_Thememanager_Block_Adminhtml_Forms_AdvancedStyling extends Varien_Data_Form_Element_Abstract
{

    public function getElementHtml()
    {
        $helper = Mage::helper('thememanager/themeConfig');
        $first = false;
        $i = 0;


        foreach($this->getAdvancedStyling() AS $as)
        {
            $as = (array)$as;
            $style_child_arr = array();

            if (isset($as['style_child']))
            {
                $style_childs = $as['style_child'];
                if (!empty($style_childs->name))
                {
                    $style_childs = array($style_childs);
                }

                $ii = 0;
                foreach ($style_childs AS $style_child)
                {
                    $props = array('base'=>array(), 'hover'=>array());
                    foreach($style_child->props->prop AS $prop)
                    {
                        $prop_type = isset($prop['hover']) && $prop['hover'] > 0 ? 'hover' : 'base';
                        //$props[$prop_type][] = "'".$prop."'";
                        $props[$prop_type][] = array(
                            'proporty_name' => (string)$prop
                            , 'proporty_title' => (string)(isset($prop['title']) ? $prop['title'] : (string)$prop)
                        );
                    }
                    $style_child_arr['child'.$ii] = array(
                                                                'child_name' => (string)$style_child->name
                                                                , 'selector' => (string)$style_child->elements
                                                                , 'props' => $props['base']
                                                                , 'props_hover' => $props['hover']
                                                        );
                    $ii++;
                }
            }

            $props = array('base'=>array(), 'hover'=>array());
            if (isset($as['props']))
            {
                foreach($as['props']->prop AS $prop)
                {
                    $prop_type = isset($prop['hover']) && $prop['hover'] > 0 ? 'hover' : 'base';
                    $props[$prop_type][] = array(
                        'proporty_name' => (string)$prop
                    , 'proporty_title' => (string)(isset($prop['title']) ? $prop['title'] : $prop)
                    );;
                }
            }

            $blockProperties_arr["element_".$i] = array(
                                                    'name' => $as['name']
                                                    , 'selector' => $as['elements']
                                                    , 'show_selector' => isset($as['show_elements']) ? $as['show_elements'] : $as['elements']
                                                    , 'props' => $props['base']
                                                    , 'props_hover' => $props['hover']
                                                    , 'style_child' => $style_child_arr
                                                );
            $i++;
        }


        $html = '<div class="CssEditor-container hided_element" id="CssEditor_container">
            <div>
                <div class="frame-wrapper" id="iframeContainer">
                </div>
                <div class="editor-wrapper">
                    <div class="editor-wrapper-inner">
                        <select id="blockInspector">
                        <option value="-1">Select Block</option>
                        </select>
                        <div id="editBlockProperties">
                        </div>
                    </div>
                    <div class="closeCssEditor" id="closeCssEditor" onclick="CssEditor.hideContainer()"><i class="fa fa-times"></i>Close editor and return to the admin panel</div>
                </div>
            </div>
            </div>
            <style id="CssEditorStyles">

               .iframe-element-selector
               {
                    position: absolute!important;
                    left: 0!important;
                    top: 0!important;
                    font-size:14px!important;
                    color:#000!important;
                    padding: 3px!important;
                    background: #f3bf8f!important;
                    width: 20px!important;
                    z-index: 20050!important;
                    display: none;
					float: none!important;
					height: auto!important;
               }
			   .iframe-element-selector:before,
			   .iframe-element-selector:after {content: none!important;}
               .iframe-element-selector {
                    font-size:14px!important;
                    line-height:14px!important;
                    color: #232323!important;
               }
                .iframe-element:hover {
                        border:3px dotted #f3bf8f;
                        position:relative;
                    }


            </style>
            <style>
                #CssEditor_container {
                    position: fixed;
                    z-index: 9999;
                    left: 0;
                    top: 0;
                    bottom: 0;
                    right: 0;
                }
               .editor-wrapper {
                    width: 360px;
                    position: fixed;
                    right: 10px;
                    top: 10px;
                    z-index: 999;
                    padding: 0;
                    background: #fff;
                    box-shadow: 0 0 5px rgba(0,0,0,.5);
                    min-height: 50px;
               }
               .editor-wrapper-inner {padding: 20px;}
               .editor-wrapper #blockInspector { margin-bottom: 20px;}
               .editor-wrapper .m-element {
                    overflow:hidden;
                    width:100%;
                    min-height:40px;
                    line-height: 40px;
               }
               .editor-wrapper .m-element label {
                    float:left;
                    width: 35%;
               }
               .editor-wrapper .m-element input.css-property,
               .editor-wrapper .m-element select.css-property,
               .editor-wrapper .m-element .ColorPicker {
                    float:left;
                    width: 62%;
               }
               .cp-color-picker {
                    z-index: 200000;
               }
               .loading-mask {
                    z-index: 20100;
               }
               #LinkingPanel
               {
                    bottom: 10px;
                    position: absolute;
               }
               #LinkingPanel div
               {
                    width: 40px;
                    float: left;
               }
                .patterns-row {
                    float:right;
                    height: 210px;
                    overflow: auto;
                    width: 229px;
                }
                .patterns-row .meigee-radio {
                    margin-left:0;
                    margin-bottom: 5px;
                    margin-right: 5px;
                }
                .patterns-upload { clear:left;}

                .patterns-row .meigee-thumb {
                    height: 25px;
                    width: 25px;
                    overflow: hidden;
                    line-height: 25px;
                }
                .patterns-row .css-property { width:12px; height:12px; float:none;}
               .closeCssEditor {
                    width:100%;
                    height: 50px;
                    line-height: 50px;
                    text-align: center;
                    font-size: 13px; 
                    color: #333;
                    background: #F1F1F1;
                    cursor: pointer;
                }
               .closeCssEditor:hover {
                    background: #E4E4E4;
                }
               .closeCssEditor i { padding-right: 5px}
                .accordion_header i.fa { margin-right:5px; }
               h3.accordion_header { font-size:14px;}
               h4.accordion_header { font-size:12px; color: #7A7D7E;}

               h3.accordion_header:hover,
               h4.accordion_header:hover,
               h4.accordion_header.active,
               h4.accordion_header.active{ color: #BD2E44;}
               .accordion-content {
                    background: #EDEDED;
                    padding:10px 5px 10px 5px;
                    margin-bottom:10px;
               }
               .accordion-content .hover_buttons { text-align: right; padding-bottom: 5px;}
               .accordion-content .hover_buttons div { display:inline; color: #CA3E3E; padding-left: 5px; cursor: pointer;}
               .accordion-content .hover_buttons div:hover,
               .accordion-content .hover_buttons div.active { color: #3A94BB;}
                .font-wrapper {float: left; width: 200px; margin: 10px 0;}
                .font-wrapper .font-label {display: block; line-height: 1.25; color: #666;}
                .editor-wrapper .m-element .font-wrapper select.css-property {width: 193px;}
                .editor-wrapper .m-element .font-wrapper input[type=text] {width: 192px;}
            </style>
            ';

        $store_id = $this->getStoreId();
        $frameUrl = $store_id ?  Mage::app()->getStore($store_id)->getUrl('') : Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        $html .= "<script type='text/javascript'>
            jQuery.noConflict();
            jQuery('.editor-wrapper').draggable();

            CssEditor.container = jQuery('#CssEditor_container');
            CssEditor.containerDestination = jQuery('#css_previev');
            CssEditor.pageIframeId = 'preview';
            CssEditor.pageIframeContainer = jQuery('#iframeContainer');
            CssEditor.blockInspector = jQuery('#blockInspector');
            CssEditor.blockPropertiesEditor = jQuery('#editBlockProperties');
            CssEditor.readyCssContent = jQuery('#css_previev');
            CssEditor.iframeElementsSelectorsClass = '';
            CssEditor.staticStyling = JSON.parse('".Mage::helper('core')->jsonEncode($this->getStaticAdvancedStyling())."');
            CssEditor.patternImages = JSON.parse('".Mage::helper('core')->jsonEncode($this->getPatternImages())."');
            CssEditor.blockProperties = JSON.parse('".Mage::helper('core')->jsonEncode($blockProperties_arr)."');
            CssEditor.frameUrl = '".$frameUrl."';

            </script>";

        return $html;
    }


}




