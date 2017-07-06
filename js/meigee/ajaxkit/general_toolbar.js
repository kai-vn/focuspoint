GeneralToolbar =
{
    config:{}
    , submodule:'toolbar'
    , loc: window.href
    , historySinglton: false
    , infiniteScrollElement:null
    , infiniteScrollPages:null


    , init:function()
    {
        AjaxKitMain.addSubmodules('GeneralToolbar', GeneralToolbar);
    }

    , reInit:function()
    {
        var self = this;
        GeneralToolbar.initToolbar();
        if (parseInt(self.config.enable_ajax_infinite_scroll))
        {
            GeneralToolbar.initInfiniteScroll();
        }
        GeneralToolbar.onInit();
    }
    , onInit: function(){}
    , onLoadingStart: function(){}
    , onLoadingFinish: function(){}
    , onLoadingAutoScroll: function(){}
    , onLoadingStaticScroll: function(){}
    , onShowStaticScroll: function()
    {
        var button = document.createElement("button");
        button.setAttribute('type', 'button');
        button.id = 'StaticScrollBtn';
        button.addClassName('button');
        button.innerHTML = '<span><span>Show More Products</span></span>';
        this.infiniteScrollElement.appendChild(button);
    }

    , initHistory: function()
    {
        var self = this;
        if (!self.historySinglton)
        {
            window.onpopstate = function(event)
            {
                if (event.state && event.state.ajaxPage)
                {
                    self.showContent(event.state.ajaxPage, false);
                }
            };
            self.historySinglton = true;
        }
    }

    , initToolbar: function()
    {
        var self = this;
        var aHrefProcessor = function(el)
        {
            if (!el.getAttribute('data-href'))
            {
                el.setAttribute('data-href', el.getAttribute('href'));
                //el.href = '#ToolbarAnchor';
                //el.href = '#';
                el.href = 'javascript:void(0);';
            }
            var func = function (eve)
            {
                $$('.main-container')[0].up(0).scrollIntoView();
                return self.loadPage(el.getAttribute('data-href'));
            };
            AjaxKitMain.setSingltonClick(el, func);
        }

        if (parseInt(self.config.enable_ajax_toolbar))
        {
            $$('.toolbar select').each(function(el)
            {
                if (!el.hasClassName('AjaxKit-Singlton-Select'))
                {
                    el.addClassName('AjaxKit-Singlton-Select');
                    el.setAttribute('onchange', 'return GeneralToolbar.loadPage(this.value);');
                }
            });

            $$('.toolbar a').each(function(el)
            {
                aHrefProcessor(el)
            });
        }

        if (parseInt(self.config.enable_ajax_layered_navigation))
        {
            $$('.block.block-layered-nav a').each(function(el)
            {
                aHrefProcessor(el)
            });
        }
    }

    , loadPage: function(url)
    {
        var self = this;
        self.showContent(url, true);
        self.initHistory();
        return false;
    }

    , showContent: function(url, useHistory, params, use_loader)
    {
        var self = this;
        if (useHistory)
        {
            self.infiniteScrollPages = null;
        }

        var success_func = function(json)
        {
            if (json.productsList)
            {
                var div = document.createElement("div");
                div.innerHTML =json.productsList
                var category_products_new = div.select('.category-products')
                var category_products_old = $$('.category-products')
                if (category_products_new.length && category_products_old.length)
                {
                    category_products_old[0].innerHTML = '';
                    AjaxKitMain.appendHtmlJsChilds(category_products_old[0], category_products_new[0].innerHTML, true);
                    
                    var scripts = div.select('script');
                    scripts.each(function(el)
                    {
                        if((el.innerHTML.indexOf("ConfigurableMediaImages") != -1) && (el.innerHTML.match(new RegExp("ConfigurableMediaImages",'g')).length > 2))
                        {
                            eval(el.innerHTML);
                            ProductMediaManager.init();
                        };
                        return false;
                    });
                        
                    var div_nav = document.createElement("div");
                    div_nav.innerHTML =json.left_navigation
                    var left_navigation_new = div_nav.select('.block.block-layered-nav')
                    var left_navigation_old = $$('.block.block-layered-nav')

                    if (left_navigation_new.length && left_navigation_old.length)
                    {
                        left_navigation_old[0].innerHTML = '';
                        AjaxKitMain.appendHtmlJsChilds(left_navigation_old[0], left_navigation_new[0].innerHTML, true);
                    }

                    AjaxKitMain.reinitSubmodules();
                    if (useHistory)
                    {
                        history.pushState({ajaxPage:self.loc}, "", url);
                        self.loc = url;
                    }

                    try
                    {
                        ConfigurableSwatchesList.init();
                    }   
                    catch(err){}
                }
            }
            if ('undefined' == typeof use_loader || use_loader)
            {
                self.onLoadingFinish();
            }
        }
        if ('undefined' == typeof use_loader || use_loader)
        {
            self.onLoadingStart();
        }

        var post = ('undefined' == typeof params) ? {} : params;
        AjaxKitMain.ajaxProcessor(this, 'getProductListToolbar', post, success_func, true, url)
    }

    , initInfiniteScroll:function()
    {
        var self = this;
        self.getInfiniteScrollPages();

        $$('.toolbar .limiter, .toolbar .pages, .toolbar-bottom').each(function(el)
        {
            el.style.display = 'none';
        });

        if($$('.category-products').length && !$('AjaxKit-InfiniteScroll') && self.infiniteScrollPages)
        {
            self.infiniteScrollElement = document.createElement("div");
            self.infiniteScrollElement.id ='AjaxKit-InfiniteScroll';
            var category_products = $$('.category-products')[0]
            if (category_products.select('.toolbar-bottom').length)
            {
                category_products.insertBefore(self.infiniteScrollElement, category_products.select('.toolbar-bottom')[0]);
            }
            else
            {
                category_products.appendChild(self.infiniteScrollElement);
            }

            if (self.infiniteScrollPages[0].is_autoscroll)
            {
                Event.observe(window, 'scroll', function()
                {
                    var document_dimensions = document.viewport.getDimensions();
                    var InfiniteScroll_dimensions = self.infiniteScrollElement.getBoundingClientRect();
                    if ((document_dimensions.height -  InfiniteScroll_dimensions.bottom + parseInt(GeneralToolbar.config.infinite_scroll_buffer)) > 0)
                    {
                        self.loadInfiniteScrollPage();
                    }
                });
            }
            else
            {
                Event.stopObserving(window, 'scroll');
                self.onShowStaticScroll();
                if ($('StaticScrollBtn'))
                {
                    Event.observe($('StaticScrollBtn'), 'click', function()
                    {
                        self.loadInfiniteScrollPage();
                    });
                }
            }
        }
    }

    , loadInfiniteScrollPage:function()
    {
        var self = this;
        if (self.infiniteScrollPages && !self.infiniteScrollElement.hasClassName('InfiniteScroll-loading'))
        {
            self.infiniteScrollElement.addClassName('InfiniteScroll-loading')
            var url_data = self.infiniteScrollPages[0];

            if (url_data.is_autoscroll)
            {
                self.onLoadingAutoScroll();
            }
            else
            {
                self.onLoadingStaticScroll();
            }
            self.infiniteScrollPages.splice(0, 1);
            if (self.infiniteScrollPages.length < 1)
            {
                self.infiniteScrollPages = false;
            }
            self.showContent(url_data.url, false, url_data.post, false);
        }
    }

    , getInfiniteScrollPages:function()
    {
        var self = this;
        if (null === self.infiniteScrollPages)
        {
            if ($$('.toolbar .limiter select').length)
            {
                var url = $$('.toolbar .limiter select')[0].value;
                var lastPageNum = parseInt($$('.toolbar')[0].getAttribute('data-last-page-num'));

                if (lastPageNum > 1)
                {
                    self.infiniteScrollPages = [];
                    for (var i=2; i<=lastPageNum; i++)
                    {
                        var infiniteScrollUrl = {
                                    url:url,
                                    post:{infinite_scroll:i},
                                    is_autoscroll: parseInt(GeneralToolbar.config.infinite_scroll_threshold) >= i
                                };
                        self.infiniteScrollPages.push(infiniteScrollUrl);
                    }
                }
                else
                {
                    self.infiniteScrollPages = false;
                }

            }
        }
    }


}

AjaxKitMain.addSubmodule("general_toolbar", "GeneralToolbar.init()", GeneralToolbar);
