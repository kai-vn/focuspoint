
var themConfigDepends = {};
var importSelectedDataItema = false;
//var window.importSelectedItems = {};
var importSelectedItems = {};
var readyThemes = null;

document.observe("dom:loaded", function()
{
    var elements = $$('.left_ajax');
    for (i=0; i<elements.length; i++)
    {
        elements[i].addClassName('ajax');
    
	        Event.observe(elements[i], 'click', function(eve)
            {
               var active_subtab = $('tabs_id_'+$$('.left_ajax.active')[0].getAttribute('name')+'_content').select('.entry-edit-head.active') ;
				if (active_subtab.length)
				{
					horizontalTabsClick(active_subtab[0]) ;
					horizontalTabs()
				}
            });
	}

    if ($$('.hided_tab').length)
    {
        var elements = $$('.hided_tab');
        for(var i=0; i<elements.length; i++)
        {
            elements[i].up('li').addClassName('hided_element');
        }
    }

    if ($$('.form-timer').length)
    {
        var elements = $$('.form-timer');
        for(var i=0; i<elements.length; i++)
        {
            formTimer(elements[i]);
        }
    }

    if ($('ActivationStoreMultiselect'))
    {
        clearAlreadyInstalledThemeActions();
        Event.observe($('ActivationStoreMultiselect'), 'change', function(element)
        {
            clearAlreadyInstalledThemeActions();
        });


        $$('#install-skin input[type=radio]').each(function(el)
        {
            Event.observe(el, 'change', function(element)
            {
                clearAlreadyInstalledThemeActions();
            });
        })

        $$('.installed_store_actions').each(function(el)
        {
            Event.observe(el, 'change', function(element)
            {
                el.up('tr').select('.installed_store_action_note').each(function(note)
                {
                    note.addClassName('hided_element');
                });

                var checked = el.up('tr').select('.checked__'+el.value);
                if (checked.length)
                {
                    checked[0].removeClassName('hided_element')
                }
            });

            clearAlreadyInstalledThemeActions();
        });
    }

    changeTrClass();
    setThememanagerAdminResetDelay();
});



function formTimer(el)
{
    var sec = parseInt(el.innerHTML) - 1;
    if (sec >= 0)
    {
        el.innerHTML = sec;
        setTimeout(function() { formTimer(el) }, 1000)
    }
    else
    {
        parentReload();
        return false;
    }
}

function parentReload()
{
    window.parent.location.href = $('parentUrl').value;
    return false;
}




function activateTheme(theme, url)
{
    if ('undefined' == typeof url)
    {
        var url = $('ActivationUrl').value;
    }

    var dialogWindow = Dialog.info(null
        , {
            closable:true,
            resizable:false,
            draggable:true,
            className:'magento',
            windowClassName:'popup-window',
            title:'Activate Theme',
            top:50,
            width:800,
            height:600,
            zIndex:1000,
            recenterAuto:false,
            hideEffect:Element.hide,
            showEffect:Element.show,
            id:'browser_window',
            url:url + '?isAjax=true&theme='+theme
        });
}



function deactivateTheme(theme)
{
    var url = $('DeactivationUrl').value;
    var dialogWindow = Dialog.info(null
        , {
            closable:true,
            resizable:false,
            draggable:true,
            className:'magento',
            windowClassName:'popup-window',
            title:'Deactivate Theme',
            top:50,
            width:800,
            height:600,
            zIndex:1000,
            recenterAuto:false,
            hideEffect:Element.hide,
            showEffect:Element.show,
            id:'browser_window',
            url:url + '?isAjax=true&theme='+theme
        });
}




function InstallSkin()
{
    if ($('install-skin'))
    {
        var skin = $('install-skin').select('input[type=radio]:checked');
        var valid_skin = skin.length > 0;
        $('advice-required-entry-skin').style.display = valid_skin ? 'none' : 'block';
    }
    var form = new varienForm('install_form');
    return (form.validator.validate() && valid_skin);
}

function installCheckedConflictsSkin()
{
    var form = new varienForm('install_form');
    return form.validator.validate();
}


function clearAlreadyInstalledThemeActions()
{
    $$('.installed_store_actions').each(function(el)
    {
        el.up('tr').addClassName('hided_element');
        el.removeClassName('required-entry');
    });
    $('delete_all_settings').value = 0;
    $('delete_all_settings').up(0).select('.installed_store_action_note')[0].addClassName('hided_element');


    var install_skin_selected = $('install-skin').select('input:checked');

    if (install_skin_selected.length)
    {
        var _stores = [];
        var StoreMultiselect = $('ActivationStoreMultiselect').select('option:selected').each(function(el_option)
        {
            var el = $$('.installed_store_action-'+el_option.value);
            if (el.length)
            {
                _stores.push(el[0]);
            }
        });


        if (install_skin_selected.length && 1 == install_skin_selected[0].getAttribute('use_extensions'))
        {
            if (_stores.length)
            {
                var delete_all_settings = $('delete_all_settings');
                delete_all_settings.value = 1;
                delete_all_settings.up(0).select('.installed_store_action_note')[0].removeClassName('hided_element');
                delete_all_settings.addClassName('required-entry');
            }
        }
        else
        {
            for (var i= 0, c = _stores.length; i<c; i++)
            {
                el = _stores[i];
                el.up('tr').removeClassName('hided_element');
                el.addClassName('required-entry');
            }
        }
    }

}


function checkDeactivateSkinForm()
{
    var form = new varienForm('deactivate_form');
    return form.validator.validate() && confirm($('areYouSure').value);
}


function setThememanagerAdminResetDelay()
{
    if (!$('AdminResetDelay'))
    {
        return false;
    }

    new Ajax.Request($('AdminResetDelay').value,
        {
            method: 'Post',
            asynchronous: true,
            parameters: {},
            loaderArea:false,
            onSuccess: function(transport)
            {
                setTimeout(setThememanagerAdminResetDelay, 5*60000);
            }
        });
}





function  setNewDependsJson(json)
{
    var depends = JSON.parse(json)
    for (tab in depends)
    {
        var tab_depends = depends[tab]
        for (fieldset_id in  tab_depends)
        {
            themConfigDepends[fieldset_id] = tab_depends[fieldset_id];
        }
        var elements = $$("#"+ tab + " .thememanagerFormSelect");
        for (i=0; i<elements.length; i++)
        {
            var element = elements[i];
            Event.observe(element, 'change', function(element)
            {
                showDepends();
            });
        }
    }
    showDepends();
}

function setUpdateTabs(json)
{
    var tabs = JSON.parse(json)
    tabs.each(function(tab_name)
    {
        var elements = $$("#"+ tab_name + " .thememanagerFormSelect");
        for (i=0; i<elements.length; i++)
        {
            var element = elements[i];
            Event.observe(element, 'change', function(element)
            {
                showPreview();
            });
        }


        $$("#"+ tab_name + " input[type=radio]").each(function(element)
        {
            Event.observe(element, 'change', function(el)
            {
                checkTypeRadio(element);
            });
            Event.observe(element.up('label').select('img')[0], 'click', function(el)
            {
                checkTypeRadio(element);
            });
        });


    });
    showPreview();
	horizontalTabs();
}

function changeTrClass()
{
    $$('table [data-parent-tr-class]').each(function(el)
    {
        var class_name = el.getAttribute('data-parent-tr-class');
        el.up('tr').addClassName(class_name);
    });
}


function checkTypeRadio(el)
{
    el.up('tr').select('.meigee-thumb').each(function (element)
    {
        element.removeClassName('active');
    });
    el.setAttribute('checked', true);// .checked = true;

    el.up('label').select('.meigee-thumb')[0].addClassName('active');
    showPreview();
	horizontalTabs();
    var activeTab = el.up('.entry-edit').select('.entry-edit-head.active')[0];
    calcTabsStylese(activeTab);
}

function horizontalTabs()
{
    setTimeout('horizontalTabsTimeout()', 200);
}

function horizontalTabsTimeout(){
	
	if($$('.tabs-preview-wrapper').length){
		var preview = $$('.tabs-preview-wrapper');
		preview.each(function(prev){
			var prevWrapper = prev.up('.fieldset').up(1);
			if(prevWrapper.getStyle('display') != 'none'){
				var titles = prevWrapper.select('.entry-edit-head');
				var contents = prevWrapper.select('.fieldset');
				visible = titles[0];
				if(undefined == visible){
					return;
				}
				visible.addClassName('visible');
				visible.up(0).addClassName('tabs-content-wrapper');
				first = titles[1];
				if(undefined == first){
					return;
				}
				if(!first.hasClassName('first-tab')){
					blockHeight = first.next().getHeight() + visible.next().getHeight() + (first.getHeight() * 2);
					firstTop = first.getHeight() + first.positionedOffset()[1];
					first.up('.entry-edit').setStyle({
						'height': blockHeight+'px'
					});
					first.addClassName('first-tab active').next().setStyle({
						'display': 'block',
						'top' :  firstTop+10+'px'
					});
				}
				titles.each(function(el){
					el.observe('click', respondToClick);
					function respondToClick(event)
					{
						element = event.element();
						if(element.hasClassName('icon-head')){
							var activeTab = element.up('.entry-edit-head');
						} else {
							var activeTab = element;
						}
						horizontalTabsClick(activeTab);
					}
				});
			 }
		});
	}
}


function horizontalTabsClick(activeTab)
{
    activeTab.up(0).select('.fieldset').invoke('hide');
    activeTab.up(0).select('.entry-edit-head').invoke('removeClassName', 'active');
    activeTab.addClassName('active');
    calcTabsStylese(activeTab);
}


function calcTabsStylese(activeTab)
{
    func = function()
    {
        var block_preview_height =0;
        var block_preview =  activeTab.up('.entry-edit').select('.block_preview');

        if (block_preview.length > 0)
        {
            block_preview_height =  block_preview[0].getHeight();
        }

        var active_head = activeTab.up('.entry-edit').select('.entry-edit-head.active');
        var active_content_height = 0;
        var active_head_height = 0;

        if (active_head.length > 0)
        {
            active_head_height =  2*active_head[0].getHeight();

            var active_content = active_head[0].next();
            var active_content_height = active_content.getHeight();

            active_head[0].setStyle({
                'display': 'block'
            });
            activeTab.up('.tabs-content-wrapper').setStyle({
                'height': (active_content_height + active_head_height + block_preview_height) + 'px'
            });
            active_content.setStyle({
                'top': (20 + active_head_height + block_preview_height)+'px',
                'display': 'block'
            });
        }
    }
    setTimeout(func, 100);
}

function updateTrAttributes()
{
    if ($$('.tr_attributes').length)
    {
        var elements = $$('.tr_attributes');
        for(var i=0; i<elements.length; i++)
        {
            elements[i].up('tr').setAttribute("id", elements[i].getAttribute('data-id'));
            elements[i].up('tr').setAttribute("data-configId", elements[i].getAttribute('data-data_configId'));
        }
    }
}

function showDepends()
{
    setTimeout('showDependsTimeout()', 100);
}

function showDependsTimeout()
{
    for (alias in themConfigDepends)
    {
        if ($('alias-id-'+alias))
        {
            $('alias-id-'+alias).addClassName('hided_element');
            var configDepend = themConfigDepends[alias];
            var aliasConfigDependLength = 0;
            var aliasConfigDependcount = 0;


            for (ii in configDepend)
            {
                el_and = configDepend[ii];
                el_and_length = 0;
                element = $$('.thememanagerFormSelect[name='+ii+']')[0];

                if ( undefined != element )
                {
                    if ('select' == element.tagName.toLowerCase())
                    {
                        el_and_key = element.options[element.selectedIndex].getAttribute('data-key');
                    }
                    else
                    {
                        el_and_key = element.value;
                    }

                    for(el_or in el_and)
                    {
                        if (el_and[el_or] == el_and_key)
                        {
                            el_and_length++;
                        }
                    }

                    if (el_and_length>0)
                    {
                        aliasConfigDependLength++
                    }
                    aliasConfigDependcount++;
                }
            };
            if (aliasConfigDependLength == aliasConfigDependcount)
            {
                $('alias-id-'+alias).removeClassName('hided_element');
            }
        }
    }
}



function showPreview()
{
    setTimeout('showPreviewTimeout()', 100);
}
function showPreviewTimeout()
{
    $$('.option-value-preview').each(function(el)
    {
        el.up(0).addClassName('hided_element');
    });

    $$('.option-preview').each(function(el)
    {
        var data_option = el.getAttribute('data-option');
        var selected_el = $$('.thememanagerFormSelect[name='+data_option+'], input[type=radio][name='+data_option+']:checked');

        if (selected_el.length > 0)
        {
            selected_value = selected_el[0].getAttribute('data-value') && (selected_el[0].checked || selected_el[0].selected) ? selected_el[0].getAttribute('data-value') : selected_el[0].value;
            var selected_els = el.select('.option-value-preview[data-value='+selected_value+']');
            if ('' != selected_value && selected_els.length > 0)
            {
                selected_els.each(function(selected_el)
                {
                    selected_el.up(0).removeClassName('hided_element');
                });
            }
        }

    })
}

function showAddButtonPopup(url)
{
    winCompare = new Window({className:'magento',title:'Add Config',url:url,width:600,height:185,minimizable:false,maximizable:false,showEffectOptions:{duration:0.4},hideEffectOptions:{duration:0.4}});
    winCompare.setZIndex(100);
    winCompare.showCenter(true);
}


function reloadTo(url)
{
    setTimeout(function(){document.location.href = url},10);
    return true;
}


function exportConfig(id_url)
{
    reloadTo(id_url)
}

function importConfig(url)
{
    var dialogWindow = Dialog.info(null
        , {
        closable:true,
        resizable:false,
        draggable:true,
        className:'magento',
        windowClassName:'popup-window',
        title:'Import Config',
        top:50,
        width:800,
        height:600,
        zIndex:1000,
        recenterAuto:false,
        hideEffect:Element.hide,
        showEffect:Element.show,
        id:'browser_window',
        url:url,
        onClose:function (param, el) {
        //    location.reload();
        }
    });
}


function uploadImportFiles()
{
    var form = new varienForm('UploadImportFilesForm');
    if (form.validator.validate())
    {
        var UploadImportFilesForm = $('UploadImportFilesForm');
        var formData = new FormData(UploadImportFilesForm);
        jQuery.ajax({
            type: "POST"
            , url: $('UploadImportFilesAjaxUrl').value + '?isAjax=true'
            , data: formData
            , processData: false
            , contentType: false
            , dataType: "json"
            , async: false

            , success: function(json)
            {
                if (json)
                {
                    showUploadedImportFilesProps(json);
                }
            }
        });
    }
}


function addErrorMsg(txt)
{
    var html = '<ul class="messages"><li class="error-msg"><ul><li>' + txt + '</li></ul></li></ul>';
    $('messages').insert(html);
}

function showUploadedImportFilesProps(data)
{
    if (data.errors)
    {
        data.errors.each(function(txt)
        {
            addErrorMsg(txt)
        });
    }

    if (data.files && data.all_types && data.stores)
    {
        readyThemes = data.themes;
        var action_select = $('ActionExample').select('select')[0];
        var replace_text = action_select.getAttribute('data-replace-txt');
        action_select.removeAttribute('data-replace-txt');

        data.themes.each(function(theme)
        {
            var theme_option = document.createElement('option');
            theme_option.innerHTML = replace_text + ' ' + theme.name;
            theme_option.value = theme.theme_id;
            theme_option.setAttribute('data-name', theme.name);
            theme_option.setAttribute('data-store', theme.store);
            theme_option.setAttribute('data-type', theme.type);
            action_select.appendChild(theme_option);
        });

        var type_select = document.createElement('select');
        type_select.addClassName('import-value');
        type_select.name = 'type';
        for (type_namecpace in data.all_types)
        {
            var type_option = document.createElement('option');
            type_option.innerHTML = data.all_types[type_namecpace].label;
            type_option.value = type_namecpace;
            type_select.appendChild(type_option)
        }

        var store_select = document.createElement('select');
        store_select.name = 'store';
        store_select.addClassName('import-value');
        var store_option = document.createElement('option');
        store_option.innerHTML = 'Default';
        store_option.value = 0;
        store_option.setAttribute('data-value', 'admin{::}admin');
        store_select.appendChild(store_option);

        data.stores.each(function(store)
        {
            var store_option = document.createElement('option');
            store_option.innerHTML = store.label;
            store_option.value = store.value;
            store_option.setAttribute('data-value', store.store.website.code +'{::}'+ store.store.code);
            store_select.appendChild(store_option)
        });

        var entry_edit_example = $('entry-edit-example');
        data.files.each(function(file_prop, i)
        {
            if (file_prop.error)
            {
                addErrorMsg(file_prop.name + ' ' + file_prop.error);
            }
            else
            {
                if (file_prop.theme)
                {
                    $('uploadImportFilesForm').style.display = 'none';

                    var entry_edit_example_clone = entry_edit_example.clone(true);
                    entry_edit_example_clone.id = 'entry-edit-' + i;
                    entry_edit_example_clone.style.display = 'block';
                    entry_edit_example_clone.select('.import-file-H4').each(function(el)
                    {
                        el.innerHTML = file_prop.file_name + (file_prop.file_zip ? ' ('+file_prop.file_zip+')' : '');
                    });

                    entry_edit_example_clone.select('.import-name-value').each(function(el)
                    {
                        el.value = file_prop.theme.name;
                    });
                    entry_edit_example_clone.select('.import-name-previous').each(function(el)
                    {
                        el.innerHTML = file_prop.theme.name;
                    });

                    entry_edit_example_clone.select('.import-store-value').each(function(el)
                    {
                        store_select_clone = store_select.clone(true);
                        var selected_el = store_select_clone.select('option[data-value='+file_prop.theme.store.website.code +'{::}'+ file_prop.theme.store.code+']')
                        if (selected_el.length)
                        {
                            selected_el[0].selected = true;
                        }
                        el.appendChild(store_select_clone);
                    });

                    entry_edit_example_clone.select('.import-store-previous').each(function(el)
                    {
                        el.innerHTML = 'Website: '
                        + file_prop.theme.store.website.name
                        + ' ('+file_prop.theme.store.website.code+') '
                        + 'Store: '
                        + file_prop.theme.store.name
                        + ' ('+file_prop.theme.store.code+') '  ;
                    });

                    entry_edit_example_clone.select('.import-type-value').each(function(el)
                    {
                        type_select_clone = type_select.clone(true);
                        type_select_clone.value = file_prop.theme.type;
                        el.appendChild(type_select_clone);
                    });
                    entry_edit_example_clone.select('.import-type-previous').each(function(el)
                    {
                        el.innerHTML = file_prop.theme.type;
                    });

                    entry_edit_example_clone.select('.import-file-path').each(function(el)
                    {
                        el.value = file_prop.file_path;
                    });

                    entry_edit_example_clone.select('.import-action').each(function(el)
                    {
                        action_select_clone = action_select.clone(true);
                        var data_store = file_prop.theme.store.website.code +'{::}'+ file_prop.theme.store.code
                        action_select_clone.select('option[data-store='+data_store+'][data-type='+file_prop.theme.type+']').each(function(option_el)
                        {
                            if (file_prop.theme.name == option_el.getAttribute('data-name'))
                            {
                                option_el.selected = true;
                            }
                        });
                        el.appendChild(action_select_clone);
                    });
                    $('entry-edit-files').appendChild(entry_edit_example_clone);
                }
            }
        });

        $('entry-edit-files').select('select, input').each(function(el)
        {
            Event.observe(el, 'change', function()
            {
                validateConfigs();
            });
        });
        validateConfigs();
    }
}

function showProductSelector(btn, url)
{
    var file = btn.up('.import_items').select('.import-file-path')[0].value;
    var file_type = file + '-' + btn.up('.import_form').select('.import-type-value select')[0].value;

    importSelectedDataItema = false;
    if ('undefined' != typeof window.parent.importSelectedItems[file_type])
    {
        importSelectedDataItema = window.parent.importSelectedItems[file_type];
    }

    var onCloseFunc = function()
    {
        window.parent.importSelectedItems[file_type] = importSelectedDataItema;
        importSelectedDataItema = false;
    }
    showImportPopup(
        'Select products'
        , url + 'file_path/' + btn.up('.import_items').select('.import-file-path')[0].value
        , onCloseFunc
    );
}

function showCmsPageSelector(btn, url)
{
    var file = btn.up('.import_items').select('.import-file-path')[0].value;
    var file_type = file + '-' + btn.up('.import_form').select('.import-type-value select')[0].value;

    importSelectedDataItema = false;
    if ('undefined' != typeof window.parent.importSelectedItems[file_type])
    {
        importSelectedDataItema = window.parent.importSelectedItems[file_type];
    }

    var onCloseFunc = function()
    {
        window.parent.importSelectedItems[file_type] = importSelectedDataItema;
        importSelectedDataItema = false;
    }

    var import_form = btn.up('.import_form');

    var file_path = import_form.select('.import-file-path')[0].value;
    var store_id = import_form.select('.import-store-value select')[0].value;
    showImportPopup(
        'Select products'
        , url + 'file_path/' + file_path + '/store_id/' + store_id + '/'
        , onCloseFunc
    );
}

function showCategorySelector(btn, url)
{
    var file = btn.up('.import_items').select('.import-file-path')[0].value;
    var file_type = file + '-' + btn.up('.import_form').select('.import-type-value select')[0].value;

    importSelectedDataItema = false;
    if ('undefined' != typeof window.parent.importSelectedItems[file_type])
    {
        importSelectedDataItema = window.parent.importSelectedItems[file_type];
    }

    var onCloseFunc = function()
    {
        window.parent.importSelectedItems[file_type] = importSelectedDataItema;
        importSelectedDataItema = false;
    }
    var import_form = btn.up('.import_form');
    var file_path = import_form.select('.import-file-path')[0].value;
    showImportPopup(
        'Select products'
        , url + (importSelectedDataItema ? 'cat_list/'+importSelectedDataItema : 'file_path/' + file_path)+'/'
        , onCloseFunc
    );
}

function showImportPopup(title, url, onCloseFunc)
{
    var dialogWindow = Dialog.info(null
        , {
            closable:true,
            resizable:false,
            draggable:true,
            className:'magento',
            windowClassName:'popup-window',
            title:title,
            top:50,
            width:1000,
            height:600,
            zIndex:1001,
            recenterAuto:true,
            hideEffect:Element.hide,
            showEffect:Element.show,
            id:'importConfigPopup',
            url:url,
            onClose:function (param, el)
            {
                onCloseFunc();
            }
        });
}


function  validateConfigs()
{
    var is_valid = true;
    validateReadyThemes = {};
    readyThemes.each(function(theme)
    {
        key = theme.type + '#' + theme.store
        validateReadyThemes[key] = theme.name
    });

    $$('#entry-edit-files .import-entry-edit-file').each(function(entry)
    {
        disableEnableImportEntry(entry, false);
        entry.select('.validation-advice').each(function(validation_entry)
        {
            if (validation_entry.hasClassName('clone-entry'))
            {
                validation_entry.remove();
            }
            else
            {
                validation_entry.style.display = 'none';
            }
        });

        var type = entry.select('.import-type-value select')[0].value;

        entry.select('.import_items input').each(function(el)
        {
            el.style.display = 'none'
        });
        entry.select('.import-selector-'+type).each(function(el)
        {
            el.style.display = 'block'
        });

        entry.select('.import-type-value option, .import-store-value option[value=0]').each(function(ivo_el)
        {
            ivo_el.style.display = 'block';
            ivo_el.disabled = false;
        });


        var import_action_value = entry.select('.import-action select')[0].value;
        switch (import_action_value)
        {
            case 'do_nothing':
                disableEnableImportEntry(entry, true);
                break;
            case 'new':
                if ('_default' == type)
                {
                    entry.select('.duplicated-theme-default-entry').each(function(selected_entry)
                    {
                        selected_entry.style.display = 'block';
                    });
                    is_valid = false;
                }
                if (['store', 'all_product', 'all_category', 'all_cms_page', '_default'].indexOf(type) >= 0)
                {
                    var store = entry.select('.import-store-value select option:selected')[0].getAttribute('data-value');
                    key = type + '#' + store
                    if ('undefined' != typeof  validateReadyThemes[key])
                    {
                        var already_theme_entry =  entry.select('.validation-advice.already-created-theme-entry')[0];
                        already_theme_entry_clone = already_theme_entry.clone(true);
                        already_theme_entry_clone.innerHTML = already_theme_entry_clone.innerHTML.replace('{theme_name}', validateReadyThemes[key]);
                        already_theme_entry_clone.addClassName('clone-entry');
                        already_theme_entry_clone.style.display = 'block';
                        already_theme_entry.up(0).insertBefore(already_theme_entry_clone, already_theme_entry);
                        is_valid = false;
                    }
                    else
                    {
                        var type_elts_length = 0;
                        var store_elts_length = 0;
                        $$('#entry-edit-files .import-entry-edit-file .import-type-value select').each(function(select_el)
                        {
                            if (select_el.value == type)
                            {
                                type_elts_length++;
                            }
                        });
                        $$('#entry-edit-files .import-entry-edit-file .import-store-value select option:selected').each(function(select_el)
                        {
                            if (select_el.getAttribute('data-value') == store)
                            {
                                store_elts_length++;
                            }
                        });
                        if (store_elts_length > 1 && type_elts_length > 1)
                        {
                            entry.select('.already-selected-entry').each(function(selected_entry)
                            {
                                selected_entry.style.display = 'block';
                            });
                            is_valid = false;
                        }
                    }
                    entry.select('.import-type-value option[value=store], .import-type-value option[value=_default], .import-store-value option[value=0]').each(function(ivo_el)
                    {
                        ivo_el.style.display = 'none';
                        ivo_el.disabled = true;
                        ivo_el.selected = false;
                    });

                }
                break;
            default:
                disableEnableImportEntry(entry, true);
                var valiate_import_entrys = $$('#entry-edit-files .import-entry-edit-file .action-value[value='+import_action_value+']');
                if (valiate_import_entrys.length > 1)
                {
                    entry.select('.validation-advice.duplicate-entry')[0].style.display = 'block';
                    is_valid = false;
                }
                break;
        }
    });
    disableImportBtn(is_valid);
    return is_valid;
}



function disableEnableImportEntry(entry, is_disabled, exceptions)
{
    if (undefined == exceptions)
    {
        exceptions = [];
    }
    entry.select('.import-value').each(function(iv_el)
    {
        if (exceptions.indexOf(iv_el.name) < 0)
        {
            iv_el.disabled = is_disabled;
        }
    });
}

function disableImportBtn(is_enable)
{
    $$('.import-btn').each(function(btn)
    {
        if (is_enable)
        {
            btn.removeClassName('back');
            btn.disabled = false;
        }
        else
        {
            btn.addClassName('back');
            btn.disabled = 'disabled';
        }
        btn.style.display = 'block';
    });
}






function  importConfigs()
{
    if (validateConfigs())
    {
        disableImportBtn(false);
        var import_file_entry_data = [];
        $$('#entry-edit-files .import-entry-edit-file').each(function(import_file_entry)
        {
            var entry_data = {};
            import_file_entry.select('input, select').each(function(entry)
            {
                if(entry.name)
                {
                    entry_data[entry.name] = entry.value;
                }
            });
            var file_type = entry_data['file-path'] + '-' + entry_data.type;

            if (window.parent.importSelectedItems[file_type])
            {
                entry_data.items = window.parent.importSelectedItems[file_type];
            }
            import_file_entry_data.push(entry_data);
        });


        new Ajax.Request($('ImportDataAjaxUrl').value,
            {
                method: 'Post',
                asynchronous: true,
                parameters: {importData:JSON.stringify(import_file_entry_data)},
                loaderArea:false,
                onSuccess: function(transport)
                {
                    window.parent.location.reload();
                }
            });
    }
}

function removeConfig(url)
{
    if (url)
    {
        if (confirm("Are you sure?"))
        {
            reloadTo(url);
        }
    }
    else
    {
        alert('ThemeConfig can not be to Removed');
    }
}

function cloneConfig(url)
{
    showAddButtonPopup(url);
}


function showWhatChanged()
{
    $('changes').removeClassName('hided_element');
    return false;
}

function closeWhatChanged()
{
    $('changes').addClassName('hided_element');
    return false;
}

function selectPredefinedCollection(el)
{
    var url = $('predefinedSelectUrl').value;

    if (el.value != '' && confirm("Settings will be lost. Are you sure?"))
    {
        reloadTo(url + '?collection='+el.value);
    }
}

function save()
{
    if (!checkAdvancedStylingChanges())
    {
        return;
    }


    if (validateCustomStyle())
    {
        editForm.submit();
    }
    else
    {
        alert('You have some errors');
    }
}
function saveAndStay()
{
    if (!checkAdvancedStylingChanges())
    {
        return;
    }

    if (validateCustomStyle())
    {
        if ($$('.left_ajax.active').length > 0)
        {
            $('__currentPage').value = $$('.left_ajax.active')[0].name;
        }
        editForm.submit();
    }
    else
    {
        alert('You have some errors');
    }
}


function resetChanges()
{
    var input =document.createElement("input");
    input.setAttribute("value", FORM_KEY);
    input.setAttribute("type", 'hidden');
    input.setAttribute("name", 'form_key');
    $('changes_form').appendChild(input);
    $('changes_form').submit();
    return false;
}


function showHelpGuide()
{
    if ($$('.left_ajax.active').length > 0)
    {
        var el_path = '#tabs_id_'+$$('.left_ajax.active')[0].name+'_content .__guide_block';
    }
    else
    {
        var el_path = '.__guide_block';
    }
    $$(el_path).each(function(el)
    {
        el.removeClassName('hided_element');
    });
    return false;
}

function hideHelpGuide()
{
    $$('.__guide_block').each(function(el)
    {
        el.addClassName('hided_element');
    });
    return false;
}

function setInputCheckboxChanged(el)
{
    var el_value = el.up('.input_checkbox').select('._input_checkbox_value')[0];
    var value = el_value.value;
    var value = ('__empty__' == value) ? el_value.getAttribute('data-value') : '__empty__';
    el_value.value = value;
    el.value = value;
}

function cloneMultifileElement(el)
{
    var parent = el.up('.multifile-element-content');
    var destination = parent.select('.multifile-element-inputs')[0];
    var element_example = parent.select('.multifile-element-example')[0];
    var copy = Element.clone(element_example, true);
    copy = copy.removeClassName('hided_element');
    copy = copy.removeClassName('multifile-element-example');
    copy.select('.disabled_element')[0].disabled = false;
    destination.appendChild(copy);
}

function  setHiddenRows(json)
{
    var row_ids = JSON.parse(json)
    for (id in row_ids)
    {
        var el = $('alias-id-'+row_ids[id]);
        if (el)
        {
            el.addClassName('hided_element');
        }
    }
}

function setShowedRows()
{
    var elements = $$('.showed_row');
    for(var i=0; i<elements.length; i++)
    {
        if (elements[i] && elements[i].up('tr').getAttribute('data-configid'))
        {
            showAdvancedStylingElements(elements[i].up('tr').getAttribute('data-configid'));
        }
    }
}

function showAdvancedStylingElements(el_value)
{
    if ('' != el_value)
    {
        var el = $('alias-id-'+el_value);
        if (el)
        {
            el.removeClassName('hided_element');
            elements = el.select('input, select');
            for(var i=0; i<elements.length; i++)
            {
                if (elements[i] && elements[i].getAttribute("data-name"))
                {
                    elements[i].name = elements[i].getAttribute("data-name");
                }
            }
        }
    }
}



function editStyle()
{
    showAdvancedStylingElements($('element_to_customize').value);
}

function closeStyle(el)
{
    var row = el.up('tr');
    row.addClassName('hided_element');

    elements = row.select('input, select');
    for(var i=0; i<elements.length; i++)
    {
        if (elements[i] && elements[i].getAttribute("data-name"))
        {
            elements[i].removeAttribute("name");
        }
    }
}


function checkAdvancedStylingChanges()
{
    if ($('AdvancedStylingChanged') && 1 == $('AdvancedStylingChanged').value)
    {
        return confirm("Please click on the button Generate otherwise your css changes will be lost. Continue saving?");
    }
    return true;
}

function AdvancedStylingTextareaChanged()
{
    $('AdvancedStylingChanged').value = 1;
}

function addNewStyle()
{
    var default_element = $('alias-id-_default_new_style_');
    var copy = Element.clone(default_element, true);
    var id = 'new_style_'+Math.round(new Date().getTime() / 1000) +'-'+ Math.floor(Math.random(1,1000)*1000);
    copy.setAttribute("id", 'alias-id-'+id);
    copy.setAttribute("data-configid", id);

    if (default_element.up('tbody'))
    {
        default_element.up('tbody').appendChild(copy);
    }
    else
    {
        default_element.up('table').appendChild(copy);
    }

    copy_elements = $('alias-id-'+id).select('input, select');

    for(var i=0; i<copy_elements.length; i++)
    {
        if (copy_elements[i] && copy_elements[i].getAttribute("data-name"))
        {
            var data_name = copy_elements[i].getAttribute("data-name");
            data_name = data_name.replace("_default_new_style_", id);

            copy_elements[i].setAttribute("data-name", data_name);
        }
    }
    showAdvancedStylingElements(id);
    $('alias-id-'+id).select('.custom_style, .CustomStyle')[0].addEventListener("change", validateCustomStyle);

    var option =document.createElement("option");
    option.innerHTML = "New Style Element";
    option.setAttribute("value",id);
    option.selected = true;

    $('element_to_customize').appendChild(option);
}


function checkCustomStyle()
{
    var elements = $$('.custom_style');
    for(var i=0; i<elements.length; i++)
    {
        var tr = elements[i].up('tr');
        var label = tr.select('td.label label')[0];

        var label_value = label.innerHTML;
        label.innerHTML = '';
        var input =document.createElement("input");
        input.value = label_value;
        input.type = 'text';
        input.addClassName('CustomStyle');
        input.addEventListener("change", validateCustomStyle);

        if (tr.select('.IsDisabled').length)
        {
            input.disabled = true;
        }

        var data_name = 'AdvancedStyling::'+ (tr.getAttribute("data-configid")) +'::name';
        input.setAttribute("data-name", data_name);

        label.appendChild(input);

        if ('_default_new_style_' != tr.getAttribute("data-configid"))
        {
            showAdvancedStylingElements(tr.getAttribute("data-configid"));
        }
    }


    var elements = $$('.advanced-styling-block-help-list');

    for(var i=0; i<elements.length; i++)
    {
        var div =document.createElement("div");
        div.addClassName('fa');
        div.addClassName('fa-question-circle');
        div.setAttribute('onclick', 'showStylingBlockHelpList(this)');  //onclick = showStylingBlockHelpList();
        var td_label = elements[i].up('tr').select('td.label')[0].appendChild(div);
    }

}


function showStylingBlockHelpList(el)
{
    el.up('tr').select('.advanced-styling-block-help-list')[0].removeClassName('hided_element');
    return false;
}

function closeStylingBlockHelpList(el)
{
    el.up('tr').select('.advanced-styling-block-help-list')[0].addClassName('hided_element');
    return false;
}



function validateCustomStyle()
{
    $$('.CustomStyleError').each(function(el)
    {
        el.remove();
    });

    var elements = $$('.CustomStyle');
    var is_valid = true;
    elements_values = {};
    var i = 0;

    elements.each(function(el)
    {
        if (el.getAttribute("name"))
        {
            var value = el.value;
            if (value && value != '')
            {
                if (!elements_values[value])
                {
                    elements_values[value] = {};
                    elements_values[value]['elements'] = {};
                    elements_values[value]['count'] = 0;
                }
                elements_values[value]['elements'][i] = el;
                elements_values[value]['count']++;
            }
            else
            {
                addCustomStyleError(el, 'Empty Entity Name')
                is_valid = false;
            }
        }
        i++;
    });

    for (value in elements_values)
    {
        if (elements_values[value]['count'] > 1)
        {
            for (i in elements_values[value]['elements'])
            {
                addCustomStyleError(elements_values[value]['elements'][i], 'Duplicate Entity Name')
            }
            is_valid = false;
        }
    }
    return is_valid;
}


function addCustomStyleError(el, message)
{
    var div =document.createElement("div");
    div.addClassName('CustomStyleError');
    div.addClassName('validation-advice');
    div.innerHTML = message;
    el.up('label').appendChild(div);
}

function setCustomProperties(el)
{
    var is_disabled = !el.checked;
    var elements = el.up('tr').select('input, select');
    for(var i=0; i<elements.length; i++)
    {
        if (elements[i] != el)
        {
            elements[i].disabled = is_disabled;
        }
    }
}


function setSizeMarginLeft(id, counter)
{
    var style = document.styleSheets[0];
    var styleSel = id;
    var styleDec = "margin-left: "+(-1*screen.width*counter)+"px;";

    if(style.insertRule)
    {
        style.insertRule(id+'{'+styleDec+'}', style.cssRules.length);
    }
    else
    {
        style.addRule(id, styleDec, -1);
    }
}

function setWidthHeightSizes(id)
{
    $$(id).each(function(el)
    {
        el.setStyle({
            width: screen.width+'px',
            height: screen.height+'px'
        });
    });
}

function setFullWidthHeightSizes(id, total_elemrnts)
{
    $$(id).each(function(el)
    {
        el.setStyle({
            width: screen.width*total_elemrnts+'px',
            height: screen.height+'px'
        });
    });
}

function setWidthSize(id)
{
    $$(id).each(function(el)
    {
        el.setStyle({
            width: screen.width+'px'
        });
    });
}

function hideJsRows()
{
    $$('.hided_js_row').each(function(el)
    {
        el.up('tr').addClassName('hided_element');
    });
}

document.observe("dom:loaded", function()
{
    $$('.removed-store').each(function(el)
    {
        var tr = el.up('tr');
        tr.addClassName('removed-store-row');
        tr.select('.edit-btn').each(function(edit_btn)
        {
            edit_btn.addClassName('back');
            edit_btn.setAttribute('onclick', 'alert("ThemeConfig can not be to Edited")');
        });
    });

});




function checkHeadEditFormNode(el)
{

    var text_form = el.up(0).select('.head-edit-form-node-text');
    if (text_form.length)
    {
        text_form[0].style.display = ('none' == text_form[0].style.display) ? 'block' : 'none';
    }

}



