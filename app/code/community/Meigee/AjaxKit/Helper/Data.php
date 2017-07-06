<?PHP

class Meigee_AjaxKit_Helper_Data extends Mage_Core_Helper_Abstract
{
    function clearUrl($url)
    {
        $url = trim(str_replace(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, true), '', $url), '/');
        $url = trim(str_replace(Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, false), '', $url), '/');
        return $url;
    }

    function getProductIdByUrl($url)
    {
        $product_id = null;
        $url = $this->clearUrl($url);

        if (false !== strpos($url, '/product/'))
        {
            $url_p = $url.'/';
            $pattern = '/\/product\/(.*[0-9])\//';
            preg_match($pattern, $url_p, $matches);
            if (isset($matches[1]))
            {
                $product_id =$matches[1];
            }
        }
        else
        {
            $url_arr = explode('?', $url);
            $oRewrite = Mage::getModel('core/url_rewrite')->setStoreId(Mage::app()->getStore()->getId())->loadByRequestPath($url_arr[0]);
            $product_id = (int)$oRewrite->getProductId();
        }
        return $product_id;
    }

    function parseParamsByAttributes($attributes)
    {
        $params = array();
        foreach ($attributes AS $attribute=>$value)
        {
            $pattern = '/\[(.*)\]/';
            preg_match($pattern, $attribute, $matches);
            if (isset( $matches[1]))
            {
                $name = $matches[1];
                $type = str_replace($matches[0], '', $attribute);

                if (strpos($name, '][') !== false)
                {
                    $name_arr = explode('][', $name);
                    $params[$type][$name_arr[0]][$name_arr[1]] = $value;
                }
                else
                {
                    $params[$type][$name] = $value;
                }
            }
            else
            {
                $params[$attribute] = $value;
            }
        }
        return $params;
    }
}

