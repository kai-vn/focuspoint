<?php

class Meigee_Thememanager_Block_Widget_Forms_Category extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $html = parent::_getElementHtml($element);
        $adminVersion = Mage::getConfig()->getModuleConfig('Mage_Admin')->version;
        $ajax_url = (version_compare($adminVersion, '1.6.1.1', '>='))
                    ? $this->getUrl('*/thememanager_widget/ajax')
                    : $this->getUrl('thememanager/adminhtml_widget/ajax') ;

        $html .=  "
<script type='text/javascript'>//<![CDATA[

    var  ajax_url = '".$ajax_url."';
    var  used_category = false;
    var  id = '".$element->getHtmlId()."';

    var main_options_fieldset_id = $(id).up('div.fieldset').getAttribute('id');



    //clone insert_button
    var clone = Element.clone($('insert_button'), true);
    clone.setAttribute('id', 'insert_button_clone');
    $('widget_options_thememanager_widget_products').appendChild(clone);


    //some validate manipulations
    $(main_options_fieldset_id+'_widget_id').up('tr').hide();
    $(main_options_fieldset_id+'_products_amount').addClassName('validate-number');
    $(id+'_hidden').up('tr').remove();
    $(id).setAttribute('hidden',true);
    $(id).setAttribute('id',id+'_hidden');
    $(id+'_hidden').removeClassName('required-entry');
    $(id+'_hidden').removeClassName('validation-failed');
    $('chooser'+id).value = '1';

    getProductsAjax();

    function getProductsAjax()
    {
        if ($('chooser'+id))
        {
            el = $('chooser'+id).up('td').select('input[type=hidden]')[0];

            if (el.value != used_category)
            {
                used_category = el.value;
                $(id+'_products').select('option').each(function(el)
                {
                    el.remove();
                })
                new Ajax.Request(ajax_url,
                {
                    method: 'Post',
                    asynchronous: false,
                    parameters: {'action':'get_products', 'category':el.value},
                    onSuccess: function(transport)
                    {
                        $(id+'_products').insert(transport.responseText);
                    }
                });
            }

            var chooser = $('chooser'+id);
            var input = chooser.up('td').select('input')[0];
            if ('featuredcategory' == $(main_options_fieldset_id+'_select_type').value)
            {
                chooser.up('td').show();
                input.addClassName('required-entry');
                input.addClassName('validation-failed');
            }
            else
            {
                chooser.up('td').hide();
                input.removeClassName('required-entry');
                input.removeClassName('validation-failed');
            }
            setTimeout(getProductsAjax, 700);
        }
    }
//]]</script>";
        return $html;
    }
}

