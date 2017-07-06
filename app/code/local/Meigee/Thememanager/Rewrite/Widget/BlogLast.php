<?php

class Meigee_Thememanager_Rewrite_Widget_BlogLast extends AW_Blog_Block_Last
{
    protected function _toHtml()
    {
        $meigee_theme = $this->getMeigeeTheme();
        if (!$meigee_theme || 'no_meigee_theme' == $meigee_theme || Mage::getSingleton('core/design_package')->getPackageName() != $meigee_theme)
        {
            return parent::_toHtml();
        }
        else
        {
            $template = $this->getData($meigee_theme.'_template');
            $this->setTemplate('aw_blog'.DS.$template);
            if ($this->_helper()->getEnabled())
            {
                return $this->setData('blog_widget_recent_count', $this->getBlocksCount())->renderView();
            }
        }
    }
}