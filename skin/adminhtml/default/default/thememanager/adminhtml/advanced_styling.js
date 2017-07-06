var CssEditor =
{
    container: null,
    containerDestination: null,
    pageIframeId: null,
    pageIframe: null,
    pageIframeСontents: null,
    iframeElementsSelectorsClass: '',
    frameUrl: '',
    readyCssContent: '',
    pageIframeContainer: null,
    elementIToIframeAliases : {},
    elementsHovers : {},
    elementsHoversPresave : {},
    uploadedGoogleFonts : {},
    blockInspector: null,
    blockProperties: {},
    staticStyling: {},
    patternImages: [],
    blockPropertiesEditor: null,
    inputColorPickerValue: null,
    backgroundImageCounter: 0,
    elementI: 0,
    fontId: 0,
    defaultFontFamily: '__default__',

    init: function ()
    {
        this.initCss();
        this.generateEditHtml();
        this.generateElementActions();
        this.initEnd();
    },

    initStart: function ()
    {
        varienLoaderHandler.handler.onCreate({options:{loadArea:''}});
    },

    getPageIframe: function (selector)
    {
        return this.pageIframe.contents().find(selector);
    },

    initEnd: function ()
    {
        varienLoaderHandler.handler.onComplete();
    },

    initCss: function ()
    {
        this.pageIframe.css('height', jQuery(window).height()+'px');


        this.getPageIframe('head').append('<style>'+this.readyCssContent.val()+'</style>');
        this.getPageIframe('head').append('<style>'+jQuery('#CssEditorStyles').html()+'</style>');
        this.applyChanges();
    },

    /* Actions generator start */
    generateElementActions: function()
    {
        var self = this;
        self.getPageIframe('.iframe-element-selector').click(function() { self.iframeElementClicked(jQuery(this).parent()); return false; });


        self.getPageIframe('.iframe-element').hover(
            function()
            {
                jQuery(this).find('.iframe-element-selector').first().show();
            }
            , function()
            {
                jQuery(this).find('.iframe-element-selector').first().hide();
            }
        );

        jQuery('.css-property').change(function() { self.blockPropertiesEditorChanged(jQuery(this)); });
        self.blockInspector.change(function() { self.blockInspectorChanged(jQuery(this));});

        jQuery('.edit-blocks .accordion .hover_buttons  div').click(function(){ self.setTabType(jQuery(this)) });
        jQuery('.edit-blocks .accordion .accordion_header').click(function(){ self.setAccordion(jQuery(this)) });



        jQuery('input.ColorPicker').colorPicker({
            renderCallback: function($elm, toggled) {
                if (self.inputColorPickerValue != $elm.val())
                {
										$elm.attr('value', $elm.val());
										self.inputColorPickerValue = $elm.val();
                    $elm.trigger("change");
                }
            }
        });
    },

    showContainer: function()
    {
        this.initStart();
        this.pageIframeContainer.html('<iframe onload="CssEditor.init()" style="width: 100%; height: 1200px" src="'+this.frameUrl+'" id="preview"></iframe>')
        this.pageIframe = jQuery('#preview');
        this.container.removeClass('hided_element');
    },


    hideContainer: function()
    {
        var self = this;
        var element_properties = self.getChanges()
        var css_property_blocks = '';
        for (selector in element_properties)
        {
            var selector_element_properties = '';

            for (prop in element_properties[selector])
            {
                selector_element_properties += prop + ': ' + element_properties[selector][prop] +'; ';
            }
            css_property_blocks += selector + " {"+ selector_element_properties +"} \n";
        }
        var usedDate = new Date();
        var d = usedDate.getDate();
        var m = 1+ usedDate.getMonth();
        var y = usedDate.getFullYear();
        var hou = usedDate.getHours();
        var min = usedDate.getMinutes();
        var sec = usedDate.getSeconds();

        var usedDateStr = d + "." + m + "." + y + " " + hou + ":" + min + ":" + sec;
        css_property_blocks = self.containerDestination.val() + "\n" + "/* " + usedDateStr + " Css generation start */\n\n" + css_property_blocks  + "\n/* "+usedDateStr+" Css generation end */\n";
        self.containerDestination.val(css_property_blocks);
        this.container.addClass('hided_element');

        this.blockInspector.html('<option value="-1">Select Block</option>');
        this.blockPropertiesEditor.html('');
        this.pageIframeContainer.html('');
    },


    applyChanges: function()
    {
        var changes = this.getChanges()
        for (selector in changes)
        {
            for (prop in changes[selector])
            {
                this.getPageIframe(selector).css(prop, changes[selector][prop]);
            }
        }
        for (google_font in this.uploadedGoogleFonts)
        {
            this.getPageIframe('head').append(this.uploadedGoogleFonts[google_font]);
        }
    },

    getChanges: function()
    {
        var self = this;
        var is_saved = {};
        var element_properties = {};

        jQuery('.edit-blocks').each(function()
        {
            var css_property = '';
            var is_empty_property = true;
            jQuery(this).find('.css-property').each(function()
            {
                var prop_name = jQuery(this).attr('data-prop-name');
                var data_block = jQuery(this).closest('.edit-child-blocks, .edit-blocks').attr('data-block');
                var selector = self.elementIToIframeAliases[data_block];
                if (self.isHover(jQuery(this)))
                {
                    var reg = /\,/gi;
                    selector = selector.replace(reg, ':hover,') + ':hover';
                }
                var key =  selector + prop_name;

                var prop_value = self.getPropertyValue(jQuery(this));

                if (is_saved[key] || undefined == prop_value)
                {
                    return;
                }
                is_saved[key] = true;
                var base_value = jQuery(this).attr('base-value');

                if ('' != prop_value && prop_value != base_value)
                {
                    if (!element_properties[selector])
                    {
                        //element_properties[selector] = [];
                        element_properties[selector] = {};
                    }

                    var prop_name_arr = prop_name.split(',');
                    for(var j=0; j<prop_name_arr.length; j++)
                    {
                        var array_key = element_properties[selector].length;
                        switch (prop_name_arr[j])
                        {
                            case 'background-image':
                                element_properties[selector][prop_name_arr[j]] = 'url(' + prop_value + ')';
                                break;
                            case 'font-family':

                                if (self.defaultFontFamily == prop_value)
                                {
                                    prop_value = jQuery('#CssEditor_container input[data-fontid='+jQuery(this).attr('data-fontid')+']').val();
                                }
                                else
                                {
                                    prop_value = "'"+prop_value+"' /* UsedGoogleFontFamily:"+prop_value+" */";
                                }
                            default:
                                element_properties[selector][prop_name_arr[j]] = prop_value;
                                break;
                        }
                    }
                }
            });
        });

        return element_properties;
    },


    isHover: function(el)
    {
        var hover = parseInt(el.closest('.tab').attr('data-hover'));
        return hover>0;
    },

    getPropertyValue: function(el)
    {
        if('input' == el.prop("tagName").toLowerCase() && 'radio' == el.attr('type'))
        {
            return el.closest('.patterns-row').find('input.css-property[checked=checked]').val();
        }
        return el.val()
    },

    iframeElementClicked: function(el)
    {
        this.showPropertiesEditor(el.attr('data-iframe-element'));
    },

    blockInspectorChanged: function(el)
    {
        var val = el.val();
        this.showPropertiesEditor(val);
        var selected = this.getPageIframe(this.elementIToIframeAliases[val]);
        var selectedBgColor = selected.css('background-color');
    },

    blockPropertiesEditorChanged: function(el)
    {
        var self = this;
        var val = self.getPropertyValue(el);
        var prop_name = el.attr('data-prop-name');
        var block = el.closest('.edit-child-blocks, .edit-blocks').attr('data-changed', 1);
        var blocks_i = block.attr('data-block');
        var selector = self.elementIToIframeAliases[blocks_i];
        var el_iframe = self.getPageIframe(selector);

        if ('font-family' == prop_name && undefined == self.uploadedGoogleFonts[val])
        {
            var reg = /[^\w|.]/gi;
            var ff_val = val.replace(reg, '_');
            var font_link_html = "<link href='//fonts.googleapis.com/css?family="+ff_val+"' rel='stylesheet' type='text/css'>";
            self.getPageIframe('head').append(font_link_html);
            self.uploadedGoogleFonts[val] = font_link_html;
        }

        if (self.isHover(el))
        {
            el.val(val);
            el.attr('value', val);
            el_iframe.unbind('mouseenter mouseleave');
            var key = blocks_i + ":key_sep:" + prop_name;

            el_iframe.hover(
                    function()
                    {
                        self.elementsHoversPresave[key] = jQuery(this).css(prop_name);
                        self.setCss(jQuery(this), prop_name,val);
                    }
                    , function()
                    {
                        self.setCss(jQuery(this), prop_name,self.elementsHoversPresave[key]);
                    }
            );
        }
        else
        {
            self.setCss(el_iframe, prop_name,val);
        }
    },
    setCss: function(el, prop_name,val)
    {
        var prop_name_arr = prop_name.split(',');

        for(var i=0; i<prop_name_arr.length; i++)
        {
            switch (prop_name_arr[i])
            {
                case 'background-image':
                    el.css(prop_name_arr[i], 'url(' + val + ')');
                    break;
                default:
                    el.css(prop_name_arr[i],val);
                    break;
            }
        }
    },

    showPropertiesEditor: function(id)
    {
        jQuery('.edit-blocks').addClass('hided_element');
        jQuery('#edit_block_'+id).removeClass('hided_element');
        this.blockInspector.val(id);
    },
    /* Actions generator end */


    /* Html generator start */
    generateEditHtml: function()
    {
				var select_html = '<option value="-1">Select Block</option>';
        var properties_editor_html = '';
        this.elementI = 0;

        this.hoverCssReader();
        for(element in this.blockProperties)
        {
            var iframe_element = this.getPageIframe(this.blockProperties[element].selector)
            var iframe_element_show = this.getPageIframe(this.blockProperties[element].show_selector)

            if (iframe_element.length > 0)
            {
                if ('absolute' == iframe_element_show.css('position'))
                {
                    iframe_element_show.attr('style',iframe_element_show.attr('style')+';position:absolute !important');
                }
                this.getElementI(this.blockProperties[element].selector);
                this.elementIToIframeAliases[this.elementI] = this.blockProperties[element].selector;
                iframe_element_show.addClass('iframe-element');
                iframe_element_show.prepend('<div class="iframe-element-selector '+this.iframeElementsSelectorsClass+'" title="' + this.blockProperties[element].name + '"><i class="fa fa-pencil"></i></div>');
                iframe_element_show.attr('data-iframe-element', this.elementI);
                select_html +=  this.getOptionHtml(this.elementI, this.blockProperties[element].name);
                properties_editor_html += this.getPropertiesEditorBlockHtml(this.blockProperties[element], iframe_element, false);
            }
        }
        this.blockInspector.html(select_html);
        this.blockPropertiesEditor.append(properties_editor_html);
    },

    getElementI: function(selector)
    {
			var counter = 0;
			for (i in this.elementIToIframeAliases)
			{
					if (selector == this.elementIToIframeAliases[i])
					{
							this.elementI = i;
							return;
					}
					counter++;
			}
			this.elementI = counter;
			return;
    },
		
		
    getOptionHtml: function(value, name)
    {
        return  '<option value="'+value+'">'+name+'</option>';
    },

    getPropertiesEditorBlockHtml: function(block_properties_element, element, is_child)
    {
        var properties = block_properties_element.props;

        if (undefined == properties)
        {
            return '';
        }
        var hover_properties = block_properties_element.props_hover;

        var is_hover_exist = hover_properties.length > 0;
        var hover_buttons_html = '';

        if (is_hover_exist)
        {
            hover_buttons_html = '<div class="hover_buttons"><div class="active" data-tab-type="base" >Base</div><div data-tab-type="hover">Hover</div></div>';
        }

        if (is_child)
        {
            var properties_editor_html = '<div data-changed="0" class="edit-child-blocks" data-block="'+this.elementI+'"><div class="accordion"><h4 class="accordion_header"><i class="fa fa-angle-right"></i>'+block_properties_element.child_name+'</h4><div class="accordion-content hided_element">'+hover_buttons_html+'<div class="tab active properties-editor-tab-type-base hided_element" data-hover="0">';
        }
        else
        {
            var properties_editor_html = '<div id="edit_block_'+this.elementI+'" class="hided_element edit-blocks" data-block="'+this.elementI+'" data-changed="0" >';
            properties_editor_html += '<div class="accordion"><h3 class="accordion_header"><i class="fa fa-angle-right"></i>Main '+block_properties_element.name+'</h3><div class="accordion-content">'+hover_buttons_html+'<div class="tab active properties-editor-tab-type-base" data-hover="0">';
        }
        for(element_prop in properties)
        {
            prop_name = properties[element_prop].proporty_name;
            prop_title = properties[element_prop].proporty_title;
            if (typeof prop_name == 'string')
            {
                var prop_name_arr = prop_name.split(',');
                var css_style_value = this.processCssVaue(element.css(prop_name_arr[0]));
                properties_editor_html += this.getPropertyEditHtml(css_style_value, prop_name, prop_title);
            }
        }
        properties_editor_html += '</div>';

        if (is_hover_exist)
        {
            properties_editor_html += '<div class="tab hided_element properties-editor-tab-type-hover" data-hover="1">';
            for(element_prop in hover_properties)
            {
                prop_name = hover_properties[element_prop].proporty_name;
                prop_title = hover_properties[element_prop].proporty_title;


                if (typeof prop_name == 'string')
                {
                    var prop_name_arr = prop_name.split(',');

                    for (var j=0; j<prop_name_arr.length; j++)
                    {
                        var css_style_value = this.hoverCssvalue(block_properties_element.selector, prop_name_arr[j]);
                    }
                    properties_editor_html += this.getPropertyEditHtml(css_style_value, prop_name, prop_title);

                }
            }
            properties_editor_html += '</div>';
        }
        properties_editor_html += '</div>';

        if (!is_child)
        {
            for (sc in block_properties_element.style_child)
            {
                var selector_arr =  block_properties_element.selector.split(',');//  block_properties_element.selector + ' ' + block_properties_element.style_child[sc].selector;
                selector2_arr = [];

                for (var j=0; j<selector_arr.length; j++)
                {
                    if (undefined != block_properties_element.style_child[sc].selector)
                    {
                        var selector2 = block_properties_element.style_child[sc].selector.split(',');
                        for (var jj=0; jj<selector2.length; jj++)
                        {
                            selector2_arr.push(selector_arr[j] + ' ' + selector2[jj]);
                        }
                    }
                }

                var selector = selector2_arr.join(', ');
                this.getElementI(selector);

                this.elementIToIframeAliases[this.elementI] = selector;

                child_element = this.getPageIframe(selector);
                properties_editor_html += this.getPropertiesEditorBlockHtml(block_properties_element.style_child[sc], child_element, true);
            }

        }
        properties_editor_html += '</div></div>';
        return  properties_editor_html;
    },

    setTabType: function(el)
    {
        var is_active = el.hasClass('active');
        var accordion = el.closest('.hover_buttons').find('div').removeClass('active');
        var accordion = el.addClass('active');
        var accordion = el.closest('.accordion');
        var tab_type = el.attr('data-tab-type');
        accordion.find('.tab').addClass('hided_element').first().removeClass('active');
        accordion.find('.properties-editor-tab-type-'+tab_type).first().removeClass('hided_element').addClass('active');
    },

    setAccordion: function(el)
    {
        var is_hided_element = el.parent().find('.accordion-content').first().hasClass('hided_element');
        el.closest('.edit-blocks').find('.accordion-content').addClass('hided_element').find('.tab').addClass('hided_element');
        el.closest('.edit-blocks').find('.tab').addClass('hided_element');
        if (is_hided_element)
        {
            el.parent().find('.accordion-content').first().removeClass('hided_element').find('.active').removeClass('hided_element');
        }
    },

    getPropertyEditHtml: function(value, prop_name, prop_title)
    {
        var html = '';
        var self = this;
        static_styling_key = false;

        switch (prop_name)
        {
            case 'font-family':
                var ff = this.staticStyling.googlefonts.value;
                html = '<div class="font-wrapper"><span class="font-label">Google font:</span> <select type="text" data-fontid="'+self.fontId+'" onchange="CssEditor.resetFont(jQuery(this))" class="css-property" data-prop-name="'+prop_name+'" base-value="'+value+'" value="'+value+'">';

                html+= '<option value="">Select font</option>';
                html+= '<option value="'+this.defaultFontFamily+'" style="display:none"></option>';

                var is_google_font = false;

                for (i=0; i<ff.length; i++)
                {
                    var selected = '';

                    if (ff[i] == value)
                    {
                        selected ='selected="selected"';        //self.fontId
                        is_google_font = true;
                    }
                    html+= '<option value="'+ff[i]+'" '+selected+'>'+ff[i]+'</option>';
                }
                html+= '</select> <br />';
                html+= '<span class="font-label">Default font: </span><input type="text" data-fontid="'+self.fontId+'" oninput="CssEditor.resetFont(jQuery(this))"  value="'+(is_google_font? '' : value)+'"></div>';


                self.fontId++;
                break;

            case 'color':
            case 'background-color':
            case 'border-color':
                html = '<input type="text" class="css-property ColorPicker" data-prop-name="'+prop_name+'" base-value="'+value+'" value="'+value+'">';
                break;
            case 'background-image':
                html = '<div class="patterns-row">';
                var html_content = 'base-value="'+value+'" type="radio" class="css-property" data-prop-name="'+prop_name+'"';

                html += '<div class="left a-center meigee-radio">'
                html += '<div class="meigee-thumb" onclick="CssEditor.setRadioChecked(jQuery(this))" >NO PATTERN</div>'
                html += '<input '+html_content+' value="" name="background-image-' + this.backgroundImageCounter + '">'
                html += '</div>'

                for (i=0; i<this.patternImages.length; i++)
                {
                    html += '<div class="left a-center meigee-radio">'
                    html += '<div style="background:url('+this.patternImages[i]+') 0 0 repeat; background-size: 54px 54px;" class="meigee-thumb" onclick="CssEditor.setRadioChecked(jQuery(this))" ></div>'
                    html += '<input '+html_content+' value="'+this.patternImages[i]+'" name="background-image-' + this.backgroundImageCounter + '">'
                    html += '</div>'
                }
                html += '</div>';
                html += '<div class="patterns-upload">';
                html += '<input type="file" class="input-file">';
                html += '<input type="button" value="Upload Pattern Image" onclick="CssEditor.uploadPattern(this)" >';
                html += '</div>';
                this.backgroundImageCounter++;
                break;
            default:
                html = '<input type="text" class="css-property" data-prop-name="'+prop_name+'" base-value="'+value+'" value="'+value+'">';
                break;
        }
        return '<div class="m-element"><label>'+prop_title+': </label>'+  html  +'</div>';
    },
    /* Html generator end */


    resetFont:function(el)
    {
        var tagName = el.prop("tagName").toLowerCase();
        var el_font_id = el.attr('data-fontid');
        if ('input' == tagName)
        {
            jQuery('#CssEditor_container select[data-fontid='+el_font_id+']').val(this.defaultFontFamily)
        }
        else
        {
            jQuery('#CssEditor_container input[data-fontid='+el_font_id+']').val('');
        }
    },

    hoverCssReader:function()
    {
        var styleSheets = document.getElementById(this.pageIframeId).contentDocument.styleSheets;
        var reg = /\:hover$/gi;

        for(i=0; i<styleSheets.length; i++)
        {
            try
            {
                var styleSheets_i = styleSheets[i];
                for(ii=0; ii<styleSheets_i.cssRules.length; ii++)
                {
                    var styleSheets_ii = styleSheets_i.cssRules[ii];
                    var selector = styleSheets_ii.selectorText;
                    if (undefined != styleSheets_ii.style && reg.exec(selector))
                    {
                        selector = selector.replace(reg, '');

                        for(iii=0; iii<styleSheets_ii.style.length; iii++)
                        {
                            var style = styleSheets_ii.style[iii]
                            this.hoverCssvalue(selector, style, styleSheets_ii.style[style]);
                        }
                    }
                }
            }
            catch(e)
            {}
        }
    },

    processCssVaue: function(val)
    {
        var reg = /\dpx$/gi;
        if (reg.exec(val))
        {
            val_float =parseFloat(val).toFixed(2);
            val_int =parseFloat(val);
            val = (val_float==val_int ? val_int : val_float)+'px';
        }
        return val;
    },

    hoverCssvalue: function(selector, style, value)
    {
        var key = selector + ":key_sep:" + style;
        if (undefined == value)
        {
            return (undefined == this.elementsHovers[key]) ? '' : this.processCssVaue(this.elementsHovers[key]);
        }
        this.elementsHovers[key] = value;
    },

    setRadioChecked: function(el)
    {
        var main =el.closest('.patterns-row');
        main.find('.meigee-thumb').removeClass('active');
        main.find('input[type=radio]').attr('checked', false);

        var parent = el.closest('.meigee-radio');
        parent.find('.meigee-thumb').addClass('active');
        var input = parent.find('input[type=radio]').attr('checked', true);

        this.blockPropertiesEditorChanged(input);
    },

    uploadPattern: function(el)
    {
        var self = this;
        var form_value = el.parentNode.getElementsByClassName('input-file')[0].value;

        if (form_value)
        {
            var form = document.createElement("form");
            form.setAttribute('id',"uploadPattern_formId");

            var input_action = document.createElement("input");
            input_action.setAttribute('type',"hidden");
            input_action.setAttribute('name',"action");
            input_action.setAttribute('value',"UploadPattern");

            var input_form_key = document.createElement("input");
            input_form_key.setAttribute('type',"hidden");
            input_form_key.setAttribute('name',"form_key");
            input_form_key.setAttribute('value',FORM_KEY);

            var input_file =  el.parentNode.getElementsByClassName('input-file')[0];
            input_file.setAttribute('name',"pattern_file");

            form.appendChild(input_action);
            form.appendChild(input_file);
            form.appendChild(input_form_key);

            var formData = new FormData(form);
            el.parentNode.insertBefore(input_file.cloneNode(), el);

            jQuery.ajax({
                type: "POST"
                , url: jQuery('#CssFileAjaxUrl').val() + '?isAjax=true'
                , data: formData
                , processData: false
                , contentType: false
                , dataType: "json"
                , async: false

                , beforeSend: function()
                {
                    varienLoaderHandler.handler.onCreate({options:{loadArea:''}});
                }
                , complete: function()
                {
                    varienLoaderHandler.handler.onComplete();
                }
                , error: function(jqXHR, textStatus, errorMessage)
                {
                    console.log(errorMessage);
                }
                , success: function(data)
                {
                    jQuery('.patterns-row').each(function()
                    {
                        var first = jQuery(this).find('.meigee-radio').first();
                        var clone = first.clone(true);
                        clone.find('.meigee-thumb').css('background-image', 'url('+data.file_url+')');
                        clone.find('.css-property').val(data.file_url);
                        first.after(clone);

                        self.patternImages.unshift(data.file_url);
                    });
                }
            });
        }
        else
        {
            alert('Upload file Error')
        }
    }
}


function getCssFileContent(el)
{
    var file_name = el.value;
    var parameters = {'action':'getCssFileContent', 'file_name':file_name};
    func = function(transport)
    {
        var css_content = '';
        try
        {
            var json = transport.responseText.evalJSON(true);
            css_content = json.css_content;

            $('css_file_name').value = file_name;
            checkCssFileName($('css_file_name'));
            alert('Css data was loaded');
        }
        catch(e)
        {}

        $('css_previev').value = css_content;
        $('AdvancedStylingChanged').value = 0;
    }

    CssFileAjax(parameters, func);
}

function generateCss()
{
    var css_file_name = $('css_file_name').value;

    if ('' == css_file_name || '.css' == css_file_name)
    {
        alert('File name is empty');
        return false;
    }

    var is_applay = confirm('Apply generated css changes for frontend?') ? 1 : 0;

    var parameters = {'action':'generateCss', 'css_content':$('css_previev').value, 'css_file_name':css_file_name, 'is_applay':is_applay};
    func = function(transport)
    {
        try
        {
            if ($('select_css').select('option[value='+css_file_name+']').length == 0)
            {
                $('select_css').select('optgroup[label='+$('custom_css_name').value+']')[0].insert('<option value="'+css_file_name+'">'+css_file_name+'</option>');
            }

            if (is_applay)
            {
                $('select_css').value = css_file_name;
            }
            $('AdvancedStylingChanged').value = 0;

            alert('Generation was completed');
        }
        catch(e)
        {
            alert('Generation Error.');
        }
    }
    CssFileAjax(parameters, func);
}


function CssFileAjax(parameters, successFunc)
{
    new Ajax.Request($('CssFileAjaxUrl').value,
        {
            method: 'Post',
            asynchronous: false,
            parameters: parameters,
            onSuccess: function(transport)
            {
                successFunc(transport);
            }
        });
}

function checkCssFileName(el)
{
    $('css_file_name_already_exist_text').addClassName('hided_element');
    $('predefined_css_file_name_already_exist_text').addClassName('hided_element');
    $('css_generator').up('tr').removeClassName('hided_element');
    var file_name = el.value;
    var reg = /[^\w|.]/gi;
    file_name = file_name.replace(reg, '_').toLowerCase();
    var reg2 = /\.css$/gi;
    if (!reg2.exec(file_name))
    {
        file_name += '.css';
    }

    var select_css = $('select_css').select('option[value='+file_name+']');
    if (select_css.length >0)
    {
        if ($('predefined_css_name').value == select_css[0].up('optgroup').getAttribute('label'))
        {
            $('predefined_css_file_name_already_exist_text').removeClassName('hided_element');
            $('css_generator').up('tr').addClassName('hided_element');
        }
        else
        {
            $('css_file_name_already_exist_text').removeClassName('hided_element');
        }
    }
    el.value = file_name;
}

