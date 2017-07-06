<?php
class Meigee_Thememanager_Block_Adminhtml_Forms_Input_File extends Meigee_Thememanager_Block_Adminhtml_Forms_Input
{
    public function getFormElement()
    {
        $this->type = 'file';
        $this->element_params = isset($this->params['element_property'])? (array)$this->params['element_property'] : array();
        $value = $this->getConfigValue();
        if (!empty($value))
        {
            $baseUrl = Mage::getConfig()->substDistroServerVars('{{base_url}}');
			// cdn fix //
            $mediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
			$mediaUrlLength = strlen($mediaUrl);
			$strim_val = mb_strimwidth($value, 0, $mediaUrlLength);
			$mediaUrl == $strim_val ? $baseUrl = '' : $baseUrl = $baseUrl;
			// !cdn fix //
			$skin_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
			$is_cdn = strpos($skin_url, 'cdn-');
			if($is_cdn == true) {
				$baseUrl = $skin_url;
				$mediaUrl == $strim_val ? $baseUrl = '' : $baseUrl = $baseUrl;
				// var_dump($skin_url);
				// var_dump($value);
				$value = substr($value, 5);
				$this->before_html = '<div class="meigee-thumb"><img src="'.$baseUrl.$value.'" /></div>';
			} else {
				$this->before_html = '<div class="meigee-thumb"><img src="'.$baseUrl.$value.'" /></div>';
			}
            $this->after_html = '<p><label class="inline"><input type="checkbox" name="delete::'.$this->getConfigId().'" value="'.$value.'" />Delete<label></p>';
        }
        return parent::getFormElement();
    }
}





