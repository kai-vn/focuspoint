<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Guide extends Varien_Data_Form_Element_Abstract
{
    private $guide_node_name;
    private $guide_node;
    private $guide_node_id;

    private $js = array();
    private $guide_counter = 0;

    private $pages = array();
    private $content_inner_start = array();
    private $content_inner_end = array();
    private $nav_button = array();
    private $css = array();

    public function getElementHtml()
    {
        $helper = Mage::helper('thememanager/themeConfig');
        $group = $this->getGroup();
        $block = $this->getBlock();

        if ('_module_guide' == $group)
        {
            $guides = $helper->getThemeGuideArray('Thememanager');
        }
        else
        {
            $guides = $helper->getThemeGuideArray();
        }

        if (!isset($guides[$group]) || !isset($guides[$group][$block]))
        {
            return '';
        }
        $guide = $guides[$group][$block];

        foreach ($guide AS $guide_node_name => $guide_node)
        {
            $this->guide_node_name = $guide_node_name;
            $this->guide_node = $guide_node;
            $this->guide_node_id = '-'.$group.'-'.$block.'-'.$guide_node_name;

            $this->addPage();
            $this->addContentInner();
            $this->addNavButton();
            $this->addContentInnerTargetCss();
            $this->addPageCss();

            $this->guide_counter++;
        }
        return $this->buildHtml('-'.$group.'-'.$block);
    }



    private function addPage()
    {
        $this->pages[] = '<div class="page" id="page'.$this->guide_node_id.'">
                            <div class="page-info" id="info'.$this->guide_node_id.'">
                                <h2>'.$this->guide_node['title'].'</h2>
                                <div class="page-text">'.$this->guide_node['description'].'</div>
                            </div>
                        </div>';
    }


    private function addContentInner()
    {
        $this->content_inner_start[] = '<div id="content-inner'.$this->guide_node_id.'">';
        $this->content_inner_end[] = '</div>';
    }

    private function addNavButton()
    {
        $this->nav_button[] = '<li class="button" id="button2"><a href="#content-inner'.$this->guide_node_id.'"></a></li>';
    }

    private function addContentInnerTargetCss()
    {
        $id_target = '#content-inner'.$this->guide_node_id.':target #content-inner';
        $this->css[] = '
                        '.$id_target.' {
                        -webkit-transition: all 400ms ease;
                        -moz-transition: all 400ms ease;
                        -o-transition: all 400ms ease;
                        transition: all 400ms ease;
                    }';
        $this->js[] = 'setSizeMarginLeft("'.$id_target.'", '.$this->guide_counter.');';
    }

    private function addPageCss()
    {
        $this->css[] = '
        #page'.$this->guide_node_id.'
        {
			background: -moz-radial-gradient('.$this->guide_node['position_x'].'% '.$this->guide_node['position_y'].'%, circle closest-side, rgba(0,0,0,0) '.$this->guide_node['bg_size'].'px, rgba(0,0,0,'.($this->guide_node['bg_opacity']/100).') 31px);
			background: -webkit-radial-gradient('.$this->guide_node['position_x'].'% '.$this->guide_node['position_y'].'%, circle closest-side, rgba(0,0,0,0) '.$this->guide_node['bg_size'].'px, rgba(0,0,0,'.($this->guide_node['bg_opacity']/100).') 31px);
			background: -o-radial-gradient('.$this->guide_node['position_x'].'% '.$this->guide_node['position_y'].'%, circle closest-side, rgba(0,0,0,0) '.$this->guide_node['bg_size'].'px, rgba(0,0,0,'.($this->guide_node['bg_opacity']/100).') 31px);
			background: -ms-radial-gradient('.$this->guide_node['position_x'].'% '.$this->guide_node['position_y'].'%, circle closest-side, rgba(0,0,0,0) '.$this->guide_node['bg_size'].'px, rgba(0,0,0,'.($this->guide_node['bg_opacity']/100).') 31px);
			background: radial-gradient('.$this->guide_node['position_x'].'% '.$this->guide_node['position_y'].'%, circle closest-side, rgba(0,0,0,0) '.$this->guide_node['bg_size'].'px, rgba(0,0,0,'.($this->guide_node['bg_opacity']/100).') 31px);
		}';
    }

    private function buildHtml($id)
    {
        $id = 'content-slider'.$id;
        $this->js[] = 'hideJsRows();';
        $this->js[] = 'setWidthHeightSizes("#'.$id.' #content");';
        $this->js[] = 'setWidthHeightSizes("#'.$id.' .page");';
        $this->js[] = 'setFullWidthHeightSizes("#'.$id.' #content-inner", '.$this->guide_counter.');';
        $this->js[] = 'setWidthSize("#'.$id.' #content-slider");';
        return '
    <style type="text/css">
		#'.$id.' #content-slider {
			font-family: arial;
		}
		#'.$id.' #content {
			overflow: hidden;
			position: fixed;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
			z-index: 9999;
		}
		#'.$id.' .page {
			float: left;
		}
		'.implode('', $this->css).'
		</style>
		<div class="__guide_block hided_element" id="'.$id.'">
			<div id="content">
                '.implode('', $this->content_inner_start).'
										<div id="content-inner">
                                            '.implode('', $this->pages).'
										</div>
                '.implode('', $this->content_inner_end).'
			</div>
			<ul id="slider-nav">
			'.implode('', $this->nav_button).'


			<li class="slider-close"><a onclick="return hideHelpGuide();"><div style="z-index:1000;" >&times;</div></a></li>

			</ul>

		</div>
		<input type="hidden" class="hided_js_row111">
		<script type="text/javascript">
            '.implode("\n", $this->js).'
        </script>
		';
    }
}

