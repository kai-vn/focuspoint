GeneralLogin =
{
    config:{}
    , submodule:'login'
    , login_html:false
    , login_selected_form:''
    , login_wishlist_el:false
    , init:function()
    {
        this.initLogin();
    }

    , initLogin: function()
    {
        var self = this;
        $$('a[href*=/customer/account/login/]').each(function(el)
        {
            el.setAttribute('onclick', 'return false;');
            var success_func = function()
            {
                self.loginClickAction(el);
            }
            AjaxKitMain.setSingltonClick(el, success_func);
        });

        $$('a[href*=/customer/account/logout/]').each(function(el)
        {
            el.setAttribute('onclick', 'return false;')

            var success_func = function()
            {
                self.logoutClickAction(el);
            }
            AjaxKitMain.setSingltonClick(el, success_func);
        });
    }

    , logoutClickAction: function(el)
    {
        var self = this;
        AjaxKitMain.addLoader(el);
        var success_func = function(json)
        {
            self.processingJson(json);
            AjaxKitMain.removeLoader(el);
        }
        AjaxKitMain.ajaxProcessor(GeneralLogin, 'logout', {pathname:window.location.pathname}, success_func)
    }

    , loginClickAction: function(el, is_wishlist_el)
    {
        var self = this;
        if (!self.login_html)
        {
            AjaxKitMain.addLoader(el);
            var success_func = function(json)
            {
                self.login_html = json;

                if (is_wishlist_el)
                {
                    self.login_wishlist_el = el;
                }
                self.showLoginPopup(is_wishlist_el);
                AjaxKitMain.removeLoader(el);
            }
            AjaxKitMain.ajaxProcessor(GeneralLogin, 'get_login_popup', {pathname:window.location.pathname}, success_func)
        }
        else
        {
            self.showLoginPopup();
        }
    }
    , showLoginPopup: function()
    {
        var self = this;
        AjaxKitMain.loadJsCss(self.login_html, 'LoginPopupLoader', self, 'initLoginPopupJsLoaded');
    }


    , initLoginPopupJsLoaded: function()
    {
        var self = GeneralLogin;
        var form_forgot_password_html_div =document.createElement("div");
        form_forgot_password_html_div.id ='form_forgot_password_html';
        form_forgot_password_html_div.addClassName('login-popup-tab');
        form_forgot_password_html_div.style.display ='none';
        form_forgot_password_html_div.innerHTML = self.login_html.form_forgot_password_html;

        var form_login_html_div =document.createElement("div");
        form_login_html_div.id ='form_login_html';
        form_login_html_div.addClassName('login-popup-tab');     //.class ='login-popup-tab';
        form_login_html_div.innerHTML = self.login_html.form_login_html;

        var form_register_html_div =document.createElement("div");
        form_register_html_div.id ='form_register_html';
        form_register_html_div.addClassName('login-popup-tab'); ///.class ='login-popup-tab';
        form_register_html_div.innerHTML = self.login_html.form_register_html;
        form_register_html_div.style.display ='none';

        var login_buttons_div =document.createElement("div");
        login_buttons_div.id ='login_buttons';
        login_buttons_div.innerHTML = self.login_html.login_buttons;
        GeneralLogin.login_selected_form = 'form_login_html';
        var div =document.createElement("div");
        div.id ='AjaxKitMainLoginForms';
        div.appendChild(form_forgot_password_html_div);
        div.appendChild(form_login_html_div);
        div.appendChild(form_register_html_div);
        div.appendChild(login_buttons_div);

        var main_div =document.createElement("div");
        main_div.innerHTML = self.login_html.popup_html;
        main_div.select('#ajaxkit-popup-content')[0].appendChild(div);
        AjaxKitMain.addHtmlPopup(main_div.innerHTML);

        $$('.show_form').each(function(el)
        {
            Event.observe(el, 'click', function (eve)
            {
                self.showPopupTab(el);
            });
        });

        $$('.ajaxkit-login-submit-form').each(function(submit_form_btn)
        {
            Event.observe(submit_form_btn, 'click', function (eve)
            {
                self.submitForm(this);
            });
        });
    }


    , showPopupTab: function(el)
    {
        var self = this;
        var id = el.getAttribute('data-form-name');

        if ($$('#'+id).length)
        {
            $$('.login-popup-tab').each(function(el_tab)
            {
                el_tab.style.display ='none';
            });
            $(id).style.display ='block';
        }
        GeneralLogin.login_selected_form = id;

        $$('.show_form').each(function(el_tab)
        {
            el_tab.style.display ='block';
        });
        el.style.display ='none';
    }

    , submitForm: function(btn)
    {
        var self = this;
        var form_id = GeneralLogin.login_selected_form;
        var params = {};
        AjaxKitMain.addLoader(btn);
        params.form_id = form_id;
        params.form_values = {};

        if ($$('#AjaxKitMainLoginForms #'+form_id).length)
        {
            var is_valid = true
            $$('#AjaxKitMainLoginForms #'+form_id)[0].select('form').each(function(form_el)
            {
                var form_el_id = form_el.id;
                var form = new VarienForm(form_el_id);
                if (form.validator.validate())
                {
                    form_el.select('input, select').each(function(el)
                    {
                        if(undefined != el.name)
                        {
                            if('checkbox' == el.type)
                            {
                                var value = el.checked ? 1 : 0;
                            }
                            else
                            {
                                var value = el.value;
                            }
                            params.form_values[el.name] = value;
                        }
                    });
                }
                else
                {
                    is_valid = false;
                }
            });

            if(is_valid)
            {
                var success_func = function(json)
                {
                    self.processingJson(json);
                    AjaxKitMain.removeLoader(btn);
                }
                AjaxKitMain.ajaxProcessor(self, 'processing_user_form', params, success_func);
            }
            else
            {
                AjaxKitMain.removeLoader(btn);
            }
        }
    }
    , redirectAction: function(url)
    {
        if(url)
        {
            window.location.href = url;
        }
        AjaxKitMain.closePopup();
    }
    , processingJson: function(json)
    {
        var self = this;
        if (json.message_error)
        {
            var html = '';
            if($$('#AjaxKitMainLoginForms .messages').length)
            {
                for (i=0; i<json.message_error.length; i++)
                {
                    html += '<div class="error-msg">'+json.message_error[i]+'</div>';
                }
            }
            $$('#AjaxKitMainLoginForms .messages').each(function(mel)
            {
                mel.innerHTML = html;
            });
        }
        else
        {
            if (json.popup_html)
            {
                var popup_bottom = json.popup_bottom ? json.popup_bottom : '';
                AjaxKitMain.addHtmlPopup(json.popup_html + popup_bottom);
            }
            if (json.welcome)
            {
                $$('.welcome-msg').each(function(welcome_msg_el)
                {
                    welcome_msg_el.innerHTML = json.welcome;
                });
            }
            if (json.login_header)
            {
                var header_top_links = $$(self.config.header_selector + ' a[href*=/customer/account/login/], ' + self.config.header_selector + ' a[href*=/customer/account/logout/]');
                if (header_top_links.length)
                {
                    header_top_links.each(function(link_el)
                    {
                        var a =document.createElement("a");
                        a.title = json.login_header.title;
                        a.innerHTML = json.login_header.label;
                        a.href = json.login_header.url;
                        var parent = link_el.up(0);
                        link_el.remove();
                        parent.appendChild(a);
                    });
                }
                self.initLogin();
            }

            if (self.login_wishlist_el)
            {
                GeneralAddToLinks.addToWishlistProcessor(self.login_wishlist_el, true);
                self.login_wishlist_el = false;
            }
            else
            {
                if (json.redirect_to)
                {
                    self.redirectAction(json.redirect_to);
                }
                else
                {
                    if (json.reload)
                    {
                        location.reload();
                    }
                }
            }
        }
    }
}

AjaxKitMain.addSubmodule("general_login", "GeneralLogin.init()", GeneralLogin);
