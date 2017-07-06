<?php
class Meigee_Thememanager_Block_Frontend_SocialNetwork_FacebookLikeBox extends Meigee_Thememanager_Block_Frontend_BlockAbstract
{
    public function _getHtml($params = array())
    {
        return <<<HTML
                <div class="block block-fblikebox">
                    <div class="block-title">
                        <strong><span>{$this->helper->__('Facebook Like Box')}</span></strong>
                    </div>
                    <div class="block-content">
                        <div id="fb-root"></div>
                        <script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.3";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
						<div class="fb-page" {$this->getOptions()}><div class="fb-xfbml-parse-ignore"><blockquote cite="{$this->getUrl()}"><a href="{$this->getUrl()}">&nbsp;</a></blockquote></div></div>
                    </div>
                </div>
HTML;
    }

    private function getOptions()
    {
        $fbcontent = array();
        $fbcontent[] = 'data-width="300"';
        $fbcontent[] = 'data-height="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_height') . '"';
        $fbcontent[] = 'data-href="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_facebook_page_url') . '"';
        // $fbcontent[] = 'data-colorscheme="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_color_scheme') . '"';
        // $fbcontent[] = 'data-show-faces="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_show_friends_faces') . '"';
        // $fbcontent[] = 'data-header="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_show_header') . '"';
        // $fbcontent[] = 'data-stream="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_show_posts') . '"';
        // $fbcontent[] = 'data-show-border="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_show_border') . '"';
		$fbcontent[] = 'data-show-facepile="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_show_friends_faces') . '"';
		$fbcontent[] = 'data-small-header="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_smallheader') . '"';
		$fbcontent[] = 'data-adapt-container-width="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_adaptive_width') . '"';
		$fbcontent[] = 'data-hide-cover="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_hide_cover') . '"';
		$fbcontent[] = 'data-show-posts="' . $this->helper->getThemeConfigResultByAliase('facebook_like_box_show_posts') . '"';
        return implode(' ',$fbcontent);
    }
	
	public function getUrl($route = '', $params = array())
    {
		$url = $this->helper->getThemeConfigResultByAliase('facebook_like_box_facebook_page_url');
		return $url;
	}

}
