productTimer = {
    init: function(c, b) {
        daysHolder = jQuery(".timer-" + b + " .days span");
        hoursHolder = jQuery(".timer-" + b + " .hours span");
        minutesHolder = jQuery(".timer-" + b + " .minutes span");
        secondsHolder = jQuery(".timer-" + b + " .seconds span");
        var a = true;
        productTimer.timer(c, daysHolder, hoursHolder, minutesHolder, secondsHolder, a);
        setTimeout(function() {
            jQuery(".timer-box").css("display", "inline-block")
        }, 1100)
    },
    timer: function(f, e, b, a, d, c) {
        setTimeout(function() {
            days = Math.floor(f / 86400);
            hours = Math.floor((f / 3600) % 24);
            minutes = Math.floor((f / 60) % 60);
            seconds = f % 60;
            d.html(seconds);
            if (d.text().length == 1) {
                d.html("0" + seconds)
            } else {
                if (d.text()[0] != 0) {
                    d.html(seconds)
                }
            }
            if (c == true) {
                e.html(days);
                b.html(hours);
                a.html(minutes);
                if (a.text().length == 1) {
                    a.html("0" + minutes)
                }
                if (b.text().length == 1) {
                    b.html("0" + hours)
                }
                if (e.text().length == 1) {
                    e.html("0" + days)
                }
                c = false
            }
            if (seconds >= 59) {
                if (a.text().length == 1 || a.text()[0] == 0 && a.text() != 0) {
                    a.html("0" + minutes)
                } else {
                    a.html(minutes)
                }
                if (b.text().length == 1 || b.text()[0] == 0 && b.text() != 0) {
                    b.html("0" + hours)
                } else {
                    b.html(hours)
                }
                if (e.text().length == 1 || e.text()[0] == 0 && e.text() != 0) {
                    e.html("0" + days)
                } else {
                    e.html(days)
                }
            }
            f--;
            productTimer.timer(f, e, b, a, d, c)
        }, 1000)
    }
};
jQuery.noConflict();
if (Prototype.BrowserFeatures.ElementExtensions) {
    var disablePrototypeJS = function(c, b) {
            var a = function(d) {
                d.target[c] = undefined;
                setTimeout(function() {
                    delete d.target[c]
                }, 0)
            };
            b.each(function(d) {
                jQuery(window).on(c + ".bs." + d, a)
            })
        },
        pluginsToDisable = ["collapse", "dropdown", "modal", "tooltip", "popover", "tab"];
    disablePrototypeJS("show", pluginsToDisable);
    disablePrototypeJS("hide", pluginsToDisable)
}
jQuery(document).ready(function(a) {
    a(".bs-example-tooltips").children().each(function() {
        a(this).tooltip()
    });
    a(".bs-example-popovers").children().each(function() {
        a(this).popover()
    })
});

function topCartListener(a) {
    var b = a.touches[0];
    if (jQuery(b.target).parents(".topCartContent").length == 0 && jQuery(b.target).parents(".cart-button").length == 0 && !jQuery(b.target).hasClass("cart-button")) {
        jQuery(".top-cart .block-title").removeClass("active");
        jQuery(".topCartContent").slideUp(500).removeClass("active");
        document.removeEventListener("touchstart", topCartListener, false)
    }
}

function topCart(a) {
    jQuery("header.header").each(function() {
        var c = jQuery(this);

        function b() {
            c.find(".top-cart .block-title").off().on("click", function(d) {
                d.stopPropagation();
                jQuery(this).toggleClass("active");
                if (jQuery(this).parents(".top-cart").hasClass("slide") && jQuery(document.body).width() < 978) {
                    jQuery(".close-btn").on("click", function() {
                        jQuery(this).parents(".top-cart").find(".block-title").removeClass("active")
                    })
                } else {
                    jQuery(this).next(".topCartContent").slideToggle(500).toggleClass("active")
                }
                document.addEventListener("touchstart", topCartListener, false);
                jQuery(document).on("click.cartEvent", function(f) {
                    if (jQuery(f.target).parents(".topCartContent").length == 0) {
                        c.find(".top-cart .block-title").removeClass("active");
                        c.find(".topCartContent").slideUp(500).removeClass("active");
                        jQuery(document).off("click.cartEvent")
                    }
                })
            });
            c.find(".top-cart").off().on("mouseenter", function(d) {
                d.stopPropagation();
                jQuery(this).find(".block-title").addClass("hover")
            });
            c.find(".top-cart").on("mouseleave", function(d) {
                d.stopPropagation();
                jQuery(this).find(".block-title").removeClass("hover")
            })
        }
        if (a) {
            if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i)) || (navigator.userAgent.match(/Android/i))) {
                b()
            } else {
                c.find(".top-cart").off().on("mouseenter mouseleave", function(d) {
                    d.stopPropagation();
                    jQuery(this).find(".block-title").toggleClass("active");
                    c.find(".topCartContent").stop().slideToggle(500).toggleClass("active")
                })
            }
        } else {
            b()
        }
    })
}

function labelsHeight() {
    jQuery(".label-type-1 .label-new, .label-type-1 .label-sale").each(function() {
        labelNewWidth = jQuery(this).outerWidth();
        if (jQuery(this).parents(".label-type-1").length) {
            if (jQuery(this).hasClass("percentage")) {
                lineHeight = labelNewWidth - labelNewWidth * 0.22
            } else {
                lineHeight = labelNewWidth
            }
        } else {
            lineHeight = labelNewWidth
        }
        jQuery(this).css({
            height: labelNewWidth,
            "line-height": lineHeight + "px"
        })
    })
}

function productImageSize() {
    if (jQuery(".product-image-zoom").length) {
        productImage = jQuery(".product-image-zoom #image");
        productImage.parent().animate({
            height: productImage.height()
        }, 100).parent().removeClass("loading");
        productImage.animate({
            opacity: 1
        }, 100)
    }
}
function productOptions() {
    if (jQuery(".options-block").length) {
        jQuery(document).on('mouseenter', '.hover-buttons', function(event){
            event.stopPropagation();
            event.preventDefault();
            jQuery(this).closest('.hover-buttons').addClass('open');
        });
        jQuery(document).on('mouseleave', '.hover-buttons', function(event){
            event.stopPropagation();
            event.preventDefault();
            jQuery(this).closest('.hover-buttons').removeClass('open');
        });
        jQuery(document).on('touchstart', function(event){
            if(jQuery(event.target).parents('.hover-buttons').length == 0){
                if (jQuery(this).closest('.hover-buttons').hasClass('open')) {
                    jQuery(this).closest('.hover-buttons').removeClass('open');
                } else {
                    jQuery(this).closest('.hover-buttons').addClass('open');
                }
            }
        });
    }
}

function WideMenuTop() {
    if (jQuery(document.body).width() > 767) {
        setTimeout(function() {
            if (!jQuery("#header").hasClass("header-15")) {
                jQuery(".nav-wide li .menu-wrapper").each(function() {
                    WideMenuItemHeight = jQuery(this).parent().height();
                    WideMenuItemPos = jQuery(this).parent().position().top;
                    jQuery(this).css("top", (WideMenuItemHeight + WideMenuItemPos))
                })
            } else {
                jQuery(".nav-wide li .menu-wrapper, ul.topmenu:not(.nav-wide) li.level-top > ul").each(function() {
                    WideMenuItemPos = jQuery(this).parent().position().top;
                    jQuery(this).css("top", WideMenuItemPos)
                })
            }
        }, 100)
    } else {
        jQuery(".nav-wide li .menu-wrapper").css("top", "auto")
    }
}
var imageList;

function imageChanger() {
    if (jQuery(document.body).width() < 768) {
        imgScrAttr = "data-src-mobile"
    } else {
        imgScrAttr = "data-src-desktop"
    }
    imageList.each(function() {
        jQuery(this).attr("src", jQuery(this).attr(imgScrAttr))
    })
}

function imageController(b) {
    if (b) {
        imageList = jQuery("img[data-src-mobile]")
    }
    if (imageList.length) {
        imageChanger();

        function c() {
            imageChanger()
        }
        if (jQuery(document.body).width() < 768) {
            var a = "mobile"
        } else {
            var a = "base"
        }
        jQuery(window).off("resize.imgcontrol").on("resize.imgcontrol", function() {
            if (jQuery(document.body).width() < 768) {
                if (a != "mobile") {
                    a = "mobile";
                    c()
                }
            } else {
                if (a != "base") {
                    a = "base";
                    c()
                }
            }
        })
    }
}
function mobileVerticalMenuButton() {
    mobileVerticalMenuButton = jQuery('header#header .vertical-menu-button');
    menuWrapper = jQuery('#header .navbar:not(.in-mobile) .vertical-menu-wrapper');
    contentPadding = parseFloat(jQuery('.content-wrapper').css('padding-top'));
    menuPadding = parseFloat(jQuery(menuWrapper).css('padding-top')) + parseFloat(jQuery(menuWrapper).css('padding-bottom')) + parseFloat(jQuery(menuWrapper).css('border-top-width')) + parseFloat(jQuery(menuWrapper).css('border-bottom-width'));
    menuCHeight = menuWrapper.children().innerHeight();
    menuHeight = menuPadding + menuCHeight;
    sliderHeight = jQuery('#home-image-slider').height();
    if(jQuery('body').hasClass('cms-index-index') && jQuery('#home-image-slider').length){
        jQuery('#sticky-header .topmenu .vertical-menu-button').remove();
        if (menuHeight < sliderHeight) {
            mobileVerticalMenuButton.remove();
        } else {
            mobileVerticalMenuButton.show();
            jQuery('header#header .vertical-menu-wrapper').addClass('with-more-button');
        }
    } else {
        mobileVerticalMenuButton.hide();
        jQuery('#header .topmenu + .vertical-menu-button').remove();
    }
}
function WideVerticalMenu(){
    if(jQuery('#header .vertical-menu-wrapper.default-open').length){
        if (jQuery(document.body).width() < 1007){
            jQuery('div.vertical-menu-button').hide();
        } else {
            jQuery('div.vertical-menu-button').show();
        }
        jQuery('#header .vertical-menu-wrapper.default-open').delay(170).animate({'opacity' : '1'}, 500);

        menuWrapper = jQuery('#header .navbar:not(.in-mobile) .vertical-menu-wrapper');
        leftSidebar = jQuery('.col-left.sidebar, .home-sidebar');

        slider = jQuery('#home-image-slider');
        mobileVerticalMenuButton = jQuery('header#header .navbar:not(.in-mobile) .vertical-menu-button');
        menuCHeight = menuWrapper.children().innerHeight();
        contentIndent = parseFloat(jQuery('.content-wrapper').css('padding-top'));

        jQuery('.vertical-menu-wrapper li.level1').each(function(){
            thisElem = jQuery(this);
            if(thisElem.children('ul.level1').length){
                thisElem.addClass('parent');
                thisElem.children('ul.level1').attr('style', '');
                if(thisElem.data('columns') == 1){
                    thisElem.addClass('default-dropdown');
                } else {
                    if(thisElem.data('menuBgpos')){
                        menuBgpos = thisElem.data('menuBgpos');
                        thisElem.children('ul.level1').attr('style', menuBgpos);
                    }
                    if(thisElem.data('menuBg')){
                        menuBg = thisElem.data('menuBg');
                        menuBg = 'url('+menuBg+')';
                        thisElem.children('ul.level1').css('background-image', menuBg);
                    }
                    if(thisElem.data('columns')){
                        columns = thisElem.data('columns');
                        columnWidth = 100/columns;
                        thisElem.find('ul.level1 li.level2').css('width', columnWidth + '%');
                    }
                }
            }
        });

        if (jQuery(document.body).width() > 1007 && jQuery(document.body).width() <= 1332){
            if(jQuery('.parent-menu-item-button').length == 0){
                jQuery('li.vertical-parent a.vertical-parent').after('<span class="parent-menu-item-button"><i class="meigee-plus"></i><i class="meigee-minus"></i></span>');
            }
            jQuery('.parent-menu-item-button').off().on('click', function(){
                jQuery(this).toggleClass('active').next('.vertical-menu-wrapper').toggleClass('shown-sub');
            });
        } else {
            jQuery('.parent-menu-item-button').remove();
            mobileVerticalMenuButton.hide();
        }
        if(leftSidebar.length){
            if(jQuery(document.body).width() > 1332){
                jQuery('.breadcrumbs-wrapper .breadcrumbs-inner').css('padding-left', jQuery('.main-container').width() - jQuery('.col-main').width());
                if(jQuery(slider).length && jQuery('header#header .navbar:not(.in-mobile) .vertical-menu-button').length){
                    mobileVerticalMenuButton = jQuery('header#header .navbar:not(.in-mobile) .vertical-menu-button');
                    mobileVerticalMenuButton.show();
                    menuPadding = parseFloat(jQuery(menuWrapper).css('padding-top')) + parseFloat(jQuery(menuWrapper).css('padding-bottom')) + parseFloat(jQuery(menuWrapper).css('border-top-width')) + parseFloat(jQuery(menuWrapper).css('border-bottom-width'));
                    contentPadding = parseFloat(jQuery('.content-wrapper').css('padding-top'));
                    menuWheight = slider.height() - contentPadding;
                    menuAnimateIndent = menuCHeight - menuWheight;
                    contentIndext = slider.offset().top - menuWrapper.offset().top;

                    setTimeout(function(){
                        menuWrapper.show();
                        menuWrapper.removeClass('open').attr('style', '').find('ul.level0').css({
                            'opacity' : 1,
                            'height' : slider.height() + contentIndext - menuPadding - mobileVerticalMenuButton.outerHeight(true)
                        })
                        sidebarPosition = menuWrapper.outerHeight() - 20;
                        if(leftSidebar.hasClass('col-left')){
                            leftSidebar.css('padding-top', sidebarPosition + 20);
                        } else {
                            leftSidebar.css('padding-top', sidebarPosition);
                        }
                        mobileVerticalMenuButton.off().on('click', function(){
                            if(menuWrapper.hasClass('open')){
                                menuWrapper.removeClass('open').find('ul.level0').attr('style', '').animate({
                                    'height' : slider.height() + contentIndext - menuPadding - mobileVerticalMenuButton.outerHeight(true)
                                }, 500);
                            } else {
                                menuWrapper.addClass('open').find('ul.level0').animate({
                                    'height' : menuCHeight + contentIndext - menuPadding
                                }, 500);
                            }
                        });

                    },200);

                } else {
                    sidebarPosition = menuWrapper.outerHeight() - 20;
                    if(leftSidebar.hasClass('col-left')){
                        leftSidebar.css('padding-top', sidebarPosition + 20);
                    } else {
                        leftSidebar.css('padding-top', sidebarPosition);
                    }
                    menuWrapper.show();
                }
            } else {
                mobileVerticalMenuButton.hide();
                menuWrapper.attr('style', '');
                leftSidebar.attr('style', '');
                jQuery('.breadcrumbs-wrapper .breadcrumbs-inner').attr('style', '');
            }
        } else {
            menuWrapper.attr('style', '');
        }
    }
}


function headerSearchFocus() {
	jQuery(".search-mini-form").each(function() {
		jQuery(this).delegate( ".form-control", "focus blur", function() {
			setTimeout(function() {
				var elem = jQuery("header .input-group input.form-control");
				elem.closest('.search-mini-form').toggleClass( "focused", elem.is( ":focus" ) );
			}, 0 );
		});
	});
}


jQuery(window).load(function() {
    if (jQuery(".cart-collaterals.panel-group").length) {
        jQuery(".cart-collaterals.panel-group .panel .panel-collapse.collapse").css("height", 0)
    }
    if (jQuery("body").hasClass("totop-button")) {
        (function(n) {
            n.fn.UItoTop = function(q) {
                var s = {
                    min: 200,
                    inDelay: 600,
                    outDelay: 400,
                    containerID: "toTop",
                    containerHoverID: "toTopHover",
                    scrollSpeed: 1200,
                    easingType: "linear"
                };
                var r = n.extend(s, q);
                var p = "#" + r.containerID;
                var o = "#" + r.containerHoverID;
                n("body").append('<a href="#" id="' + r.containerID + '"></a>');
                n(p).hide().click(function() {
                    n("html, body").animate({
                        scrollTop: 0
                    }, r.scrollSpeed, r.easingType);
                    n("#" + r.containerHoverID, this).stop().animate({
                        opacity: 0
                    }, r.inDelay, r.easingType);
                    return false
                }).prepend('<span id="' + r.containerHoverID + '"><i class="meigee-arrow-up"></i></span>');
                n(window).scroll(function() {
                    var t = n(window).scrollTop();
                    if (typeof document.body.style.maxHeight === "undefined") {
                        n(p).css({
                            position: "absolute",
                            top: n(window).scrollTop() + n(window).height() - 50
                        })
                    }
                    if (t > r.min) {
                        n(p).fadeIn(r.inDelay)
                    } else {
                        n(p).fadeOut(r.Outdelay)
                    }
                })
            }
        })(jQuery);
        jQuery().UItoTop()
    }

    function e() {
        if (jQuery("#header .customer-name").length) {
            var o = jQuery("#header .customer-name-wrapper");
            jQuery("#header .links").hide();
            jQuery("header#header .customer-name").removeClass("open");
            jQuery("header#header .customer-name + .links").slideUp(500);
            jQuery("header#header .links li").each(function() {
                jQuery(this).find("a").append('<span class="hover-divider" />')
            });

            function n(p) {
                var q = p.touches[0];
                if (jQuery(q.target).parents("header#header .customer-name + .links").length == 0 && !jQuery(q.target).hasClass("customer-name") && !jQuery(q.target).parents(".customer-name").length) {
                    jQuery("header#header .customer-name").removeClass("open");
                    jQuery("header#header .customer-name + .links").slideUp(500);
                    document.removeEventListener("touchstart", n, false)
                }
            }
            o.parent().off().on("mouseenter", function(p) {
                p.stopPropagation();
                jQuery(this).children().addClass("hover")
            });
            o.parent().on("mouseleave", function(p) {
                p.stopPropagation();
                jQuery(this).children().removeClass("hover")
            });
            o.off().on("click", function(p) {
                p.stopPropagation();
                jQuery(this).toggleClass("open");
                var q = o.position().top + o.outerHeight(true);
                jQuery("#header .links").slideToggle().css("top", q);
                document.addEventListener("touchstart", n, false);
                jQuery(document).on("click.headerCustomerEvent", function(r) {
                    if (jQuery(r.target).parents("header#header ul.links").length == 0) {
                        jQuery("header#header .customer-name").removeClass("open");
                        jQuery("header#header .customer-name + .links").slideUp(500);
                        jQuery(document).off("click.headerCustomerEvent")
                    }
                })
            })
        }
    }
    if (jQuery(".twitter-timeline").length) {
        jQuery(".twitter-timeline").contents().find("head").append("<style>body{color: #aaa} body .p-author .profile .p-name{color: #fff}</style>")
    }
    if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
        jQuery("body").addClass("mobile-device");
        var j = true
    } else {
        if (!navigator.userAgent.match(/Android/i)) {
            var j = false
        }
    }
    var c = false;
    var k = false;
    var b = jQuery("#nav").attr("class");
    var g = false;
    jQuery(".header h2.logo a.logo, div.topmenu a, a.cartHeader, .search-mini-form .search-button").on("click", function(n) {
        if (g) {
            n.preventDefault()
        }
    });

    var isMenuAnimation = false;
    jQuery('.header h2.logo a.logo, div.topmenu a, a.cartHeader, .search-mini-form .search-button').on('click', function(event){
        if(isMenuAnimation){
            event.preventDefault();
        }
    });

    function d(s) {
        switch(s)
         {
         case 'animate':
           if(!jQuery('div.mobile-menu-inner').hasClass('mobile')){
                jQuery("div.mobile-menu-inner").addClass('mobile');
                jQuery('div.topmenu > ul').slideUp('fast');
                menuButton = jQuery('.menu-button');
                if(menuButton.length){
                    menuButton.removeClass('active').children('.mobile-menu-button').removeClass('close');
                    jQuery('body').animate({'margin-left': '0', 'margin-right': '0'}, 500);
                    var isActiveMenu = false;
                    var isTouch = false;
                    if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i)) || (navigator.userAgent.match(/Android/i))){
                        var isTouch = true;
                    }
                    function callEvent(event){
                        event.stopPropagation();
                        if(isActiveMenu == false && !isMenuAnimation){
                            isMenuAnimation = true;
                            menuButton.addClass('active').children('.mobile-menu-button').addClass('close');
                            jQuery(this).addClass('active');
                            menuWidth = jQuery('.header .mobile-menu-inner').css('width');
                            jQuery('body').animate({
                                'margin-left': menuWidth,
                                'margin-right': '-' + menuWidth
                            }, 250);
                            jQuery('div.mobile-menu-inner').addClass('in');
                            jQuery('div.topmenu > ul').slideDown('medium', function(){
                                setTimeout(function(){
                                    isMenuAnimation = false;
                                }, 1000);
                            });
                            isActiveMenu = true;
                            if(isTouch){
                                document.addEventListener('touchstart', mobMenuListener, false);
                            }else{
                                jQuery(document).on('click.mobMenuEvent', function(e){
                                    if(jQuery(e.target).parents('div.mobile-menu-inner').length == 0){
                                        closeMenu();
                                        document.removeEventListener('touchstart', mobMenuListener, false);
                                        jQuery(document).off('click.mobMenuEvent');
                                    }
                                });
                            }
                        }else if(!isMenuAnimation){
                            closeMenu();
                        }
                    }

                    if(!isTouch){
                        menuButton.on('click.menu', function(event){
                            callEvent(event);
                        });
                    }else{
                        document.getElementsByClassName('menu-button')[0].addEventListener('touchstart', callEvent, false);
                    }

                    function closeMenu(eventSet){
                        menuButton.removeClass('active').children('.mobile-menu-button').removeClass('close');
                        jQuery('body').animate({'margin-left': '0', 'margin-right': '0'}, 500);
                        isMenuAnimation = true;
                        jQuery('div.mobile-menu-inner').removeClass('in');
                        jQuery('div.topmenu > ul').slideUp('medium', function(){
                            isMenuAnimation = false;
                            isActiveMenu = false;
                        });
                        document.removeEventListener('touchstart', mobMenuListener, false);
                    }
                    function mobMenuListener(e){
                        var touch = e.touches[0];
                        if(jQuery(touch.target).parents('div.mobile-menu-inner').length == 0 && jQuery(touch.target).parents('.menu-button').length == 0 && !jQuery(touch.target).hasClass('menu-button')){
                            closeMenu();
                        }
                    }
                }
               jQuery('div.topmenu > ul a').each(function(){
                    if(jQuery(this).next('ul').length || jQuery(this).next('div.menu-wrapper').length){
                        jQuery(this).before('<span class="menu-item-button"><i class="meigee-fa-plus"></i><i class="meigee-fa-minus"></i></span>')
                        jQuery(this).next('ul').slideUp('fast');
                        jQuery(this).prev('.menu-item-button').on('click', function(){
                            jQuery(this).toggleClass('active');
                            jQuery(this).nextAll('ul, div.menu-wrapper').slideToggle('medium');
                        });
                    }
               });
           }
           break;
         default:
                jQuery('div.topmenu').removeClass('mobile');
                jQuery('div.topmenu ul').attr('style', '');
                jQuery('.menu-button').off();
                jQuery('.menu-item-button').remove();
                jQuery('.lines-button').on('click', function(){
                    jQuery(this).toggleClass('close');
                        jQuery('.menu-block').toggleClass('open');
                        if(!jQuery('.menu-block').hasClass('open')){
                            setTimeout(function(){
                                jQuery('.menu-block').attr('style', '').find('row').css('width', '1200px');
                            }, 500);
                        } else {
                            setTimeout(function(){
                                jQuery('.menu-block').css('overflow', 'visible').find('row').css('width', 'auto');
                            }, 500);
                        }
                    });
                 }
    }

    function m() {
        if (jQuery(".background-wrapper").length) {
            jQuery(".background-wrapper").each(function() {
                var n = jQuery(this);
                if (jQuery(document.body).width() < 768) {
                    n.attr("style", "");
                    if (n.parent().hasClass("text-banner") || n.find(".text-banner").length) {
                        bgHeight = n.parent().outerHeight();
                        n.parent().css("height", bgHeight - 2)
                    }
                    if (jQuery("body").hasClass("boxed-layout")) {
                        bodyWidth = n.parents(".container").outerWidth();
                        bgLeft = (bodyWidth - n.parents(".container").width()) / 2
                    } else {
                        bgLeft = n.parent().offset().left;
                        bodyWidth = jQuery(document.body).width()
                    }
                    if (n.data("bgColor")) {
                        bgColor = n.data("bgColor");
                        n.css("background-color", bgColor)
                    }
                    setTimeout(function() {
                        n.css({
                            position: "absolute",
                            left: -bgLeft,
                            width: bodyWidth
                        }).parent().css("position", "relative")
                    }, 300)
                } else {
                    n.attr("style", "");
                    if (jQuery("body").hasClass("boxed-layout")) {
                        bodyWidth = n.parents(".container").outerWidth();
                        bgLeft = (bodyWidth - n.parents(".container").width()) / 2
                    } else {
                        bgLeft = n.parent().offset().left;
                        bodyWidth = jQuery(document.body).width()
                    }
                    n.css({
                        position: "absolute",
                        left: -bgLeft,
                        width: bodyWidth
                    }).parent().css("position", "relative");
                    if (n.data("bgColor")) {
                        bgColor = n.data("bgColor");
                        n.css("background-color", bgColor)
                    }
                    if (n.parent().hasClass("text-banner") || n.find(".text-banner").length) {
                        bgHeight = n.children().innerHeight();
                        n.parent().css("height", bgHeight - 2)
                    }
                }
                if (n.parent().hasClass("parallax-banners-wrapper")) {
                    jQuery(".parallax-banners-wrapper").each(function() {
                        block = jQuery(this).find(".text-banner");
                        var r = jQuery(this);
                        var p = 0;
                        var q = block.size();
                        var o = 0;
                        block.each(function() {
                            imgUrl = jQuery(this).css("background-image").replace(/url\(|\)|\"/ig, "");
                            if (imgUrl.indexOf("none") == -1) {
                                img = new Image;
                                img.src = imgUrl;
                                img.setAttribute("name", jQuery(this).attr("id"));
                                img.onload = function() {
                                    imgName = "#" + jQuery(this).attr("name");
                                    if (r.data("fullscreen")) {
                                        windowHeight = document.compatMode == "CSS1Compat" && !window.opera ? document.documentElement.clientHeight : document.body.clientHeight;
                                        jQuery(imgName).css({
                                            height: windowHeight + "px",
                                            "background-size": "100% 100%"
                                        });
                                        p += windowHeight
                                    } else {
                                        jQuery(imgName).css("height", this.height + "px");
                                        jQuery(imgName).css("height", (this.height - 200) + "px");
                                        p += this.height - 200
                                    }
                                    r.css("height", p);
                                    o++;
                                    if (!jQuery("body").hasClass("mobile-device")) {
                                        if (o == q) {
                                            if (jQuery(document.body).width() > 1278) {
                                                jQuery("#parallax-banner-1").parallax("60%", 0.8, false);
                                                jQuery("#parallax-banner-2").parallax("60%", 0.8, false);
                                                jQuery("#parallax-banner-3").parallax("60%", 0.8, false);
                                                jQuery("#parallax-banner-4").parallax("60%", 0.8, false);
                                                jQuery("#parallax-banner-5").parallax("60%", 0.8, false);
                                                jQuery("#parallax-banner-6").parallax("60%", 0.8, false);
                                                jQuery("#parallax-banner-7").parallax("60%", 0.7, false);
                                                jQuery("#parallax-banner-8").parallax("60%", 0.7, false);
                                                jQuery("#parallax-banner-9").parallax("60%", 0.7, false);
                                                jQuery("#parallax-banner-10").parallax("60%", 0.7, false)
                                            } else {
                                                if (jQuery(document.body).width() > 977) {
                                                    jQuery("#parallax-banner-1").parallax("60%", 0.8, false);
                                                    jQuery("#parallax-banner-2").parallax("60%", 0.8, false);
                                                    jQuery("#parallax-banner-3").parallax("60%", 0.9, false);
                                                    jQuery("#parallax-banner-4").parallax("60%", 0.85, false);
                                                    jQuery("#parallax-banner-5").parallax("60%", 0.8, false);
                                                    jQuery("#parallax-banner-6").parallax("60%", 0.8, false);
                                                    jQuery("#parallax-banner-7").parallax("60%", 0.8, false);
                                                    jQuery("#parallax-banner-8").parallax("60%", 0.9, false);
                                                    jQuery("#parallax-banner-9").parallax("60%", 0.85, false);
                                                    jQuery("#parallax-banner-10").parallax("60%", 0.8, false)
                                                } else {
                                                    if (jQuery(document.body).width() > 767) {
                                                        jQuery("#parallax-banner-1").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-2").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-3").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-4").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-5").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-6").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-7").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-8").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-9").parallax("60%", 0.8, false);
                                                        jQuery("#parallax-banner-10").parallax("60%", 0.8, false)
                                                    } else {
                                                        jQuery("#parallax-banner-1").parallax("30%", 0.5, true);
                                                        jQuery("#parallax-banner-2").parallax("60%", 0.1, false);
                                                        jQuery("#parallax-banner-3").parallax("60%", 0.1, false);
                                                        jQuery("#parallax-banner-4").parallax("60%", 0.1, false);
                                                        jQuery("#parallax-banner-5").parallax("60%", 0.1, false);
                                                        jQuery("#parallax-banner-6").parallax("60%", 0.1, false);
                                                        jQuery("#parallax-banner-7").parallax("60%", 0.1, false);
                                                        jQuery("#parallax-banner-8").parallax("60%", 0.1, false);
                                                        jQuery("#parallax-banner-9").parallax("60%", 0.1, false);
                                                        jQuery("#parallax-banner-10").parallax("60%", 0.1, false)
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            bannerText = jQuery(this).find(".banner-content");
                            if (bannerText.data("top")) {
                                bannerText.css("top", bannerText.data("top"))
                            }
                            if (bannerText.data("left")) {
                                if (!bannerText.data("right")) {
                                    bannerText.css({
                                        left: bannerText.data("left"),
                                        right: "auto"
                                    })
                                } else {
                                    bannerText.css("left", bannerText.data("left"))
                                }
                            }
                            if (bannerText.data("right")) {
                                if (!bannerText.data("left")) {
                                    bannerText.css({
                                        right: bannerText.data("right"),
                                        left: "auto"
                                    })
                                } else {
                                    bannerText.css("right", bannerText.data("right"))
                                }
                            }
                        })
                    });
                    jQuery(window).scroll(function() {
                        jQuery(".parallax-banners-wrapper").each(function() {
                            block = jQuery(this).find(".text-banner");
                            block.each(function() {
                                var p = jQuery(this).offset().top;
                                var o = jQuery(window).scrollTop();
                                if (p < o + 600) {
                                    jQuery(this).addClass("slideup")
                                } else {
                                    jQuery(this).removeClass("slideup")
                                }
                            })
                        })
                    });
                    setTimeout(function() {
                        jQuery("#parallax-loading").fadeOut(200)
                    }, 1000)
                }
                n.animate({
                    opacity: 1
                }, 200)
            })
        }
    }
    if (jQuery(".product-tabs-widget").length) {
        function f() {
            jQuery("ul.product-tabs").off().on("click", "li:not(.current)", function() {
                jQuery(this).addClass("current").siblings().removeClass("current").parents("div.product-tabs-widget").find("div.product-tabs-box").eq(jQuery(this).index()).fadeIn(800).addClass("visible").siblings("div.product-tabs-box").hide().removeClass("visible");
                labelsHeight()
            });
            jQuery(".product-tabs-widget").each(function() {
                listHeight = jQuery(this).find(".product-tabs").outerHeight(true);
                if (jQuery(this).hasClass("top-buttons")) {
                    if (jQuery(this).find(".widget-title").length) {
                        if (jQuery(document.body).width() < 767) {
                            titleHeight = jQuery(this).find(".widget-title").innerHeight();
                            blockTopIndent = parseFloat(jQuery(this).css("padding-top"));
                            jQuery(this).find(".product-tabs").css("top", titleHeight + blockTopIndent + listHeight / 2 + 5)
                        } else {
                            jQuery(this).find(".product-tabs").attr("style", "")
                        }
                    }
                    jQuery(this).css("padding-top", listHeight)
                } else {
                    jQuery(this).css("padding-bottom", listHeight)
                }
            })
        }
        f();
        jQuery(window).resize(function() {
            f()
        })
    }

    function l() {
        if (window.innerWidth < 480) {
            d("animate")
        }
        if (window.innerWidth > 479 && window.innerWidth < 768) {
            d("animate")
        }
        if (window.innerWidth > 767 && window.innerWidth <= 1007) {
            d("animate")
        }
        if (window.innerWidth > 1007 && window.innerWidth <= 1374) {
            d("reset")
        }
        if (window.innerWidth > 1374) {
            d("reset")
        }
    }

    function h() {
        slider = jQuery("#home-image-slider");
        navigation = slider.data("navigation");
        pagination = slider.data("pagination");
        items = slider.data("items");
        itemsMobile = slider.data("itemsMobile");
        stagePadding = slider.data("stagePadding");
        slideSpeed = slider.data("speed");
        navigation ? navigation = true : navigation = false;
        pagination ? pagination = true : pagination = false;
        items ? items = items : items = 1;
        itemsMobile ? itemsMobile = itemsMobile : itemsMobile = 1;
        stagePadding ? stagePadding = stagePadding : stagePadding = 0;
        slider.owlCarousel({
            items: items,
            responsive: {
                0: {
                    items: itemsMobile
                },
                767: {
                    items: (items > 1 ? items = 2 : items = 1),
                    margin: 0,
                    stagePadding: 0,
                    loop: true,
                    center: true,
                },
                1331: {
                    items: slider.data("items"),
                    margin: 20,
                    stagePadding: stagePadding,
                    loop: true,
                    center: true,
                },
            },
            nav: navigation,
            navSpeed: slideSpeed,
            dots: pagination,
            dotsSpeed: 400,
            navText: ['<i class="meigee-arrow-left"></i>', '<i class="meigee-arrow-right"></i>']
        })
    }
    e();
    productImageSize();
    labelsHeight();
    l();
    m();
    WideMenuTop();
    h();
    mobileVerticalMenuButton();
    WideVerticalMenu();
    h();
    headerSearchFocus();
    productOptions();
    jQuery(window).resize(function() {
        e();
        productImageSize();
        labelsHeight();
        l();
        m();
        WideMenuTop();
        WideVerticalMenu();
    });



    /* Scroll To */

    function scrollToElem(elem, speed){
        jQuery("html, body").animate({ scrollTop: jQuery(elem).offset().top }, speed);
    }
    if (jQuery('#checkpoint-0').length && jQuery('#checkpoint-1').length && jQuery('.checkpoint-button').length ) {
        if(document.URL.indexOf('#customer-reviews') == -1 && !jQuery('body').hasClass('ajax-index-options')){
            scrollToElem('#checkpoint-0', 500);
        }
        var scrollto_top = 0;
        jQuery('.checkpoint-button').click(function(){
            if(scrollto_top == 0){
                scrollToElem('#checkpoint-1', 500);
                scrollto_top = 1;
            }else{
                scrollToElem('#checkpoint-0', 500);
                scrollto_top = 0;
            }
        });
    }

    if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i)) || (navigator.userAgent.match(/Android/i))) {
        var j = true
    } else {
        var j = false
    }
    if (jQuery(".nav-wide").length) {
        jQuery(".nav-wide li.level-top").mouseenter(function() {
            jQuery(this).addClass("over");
            if (j == true) {
                document.addEventListener("touchstart", a, false)
            }
        });
        jQuery(".nav-wide li.level-top").mouseleave(function() {
            jQuery(this).removeClass("over")
        });

        function a(n) {
            var o = n.touches[0];
            if (jQuery(o.target).parents("div.menu-wrapper").length == 0) {
                jQuery(".nav-wide li.level-top").removeClass("over");
                document.removeEventListener("touchstart", a, false)
            }
        }
        columnsWidth = function(o, n) {
            if (n.size() > 1) {
                n.each(function() {
                    jQuery(this).css("width", (100 / n.size()) + "%")
                })
            } else {
                n.css("width", (100 / o) + "%")
            }
        };
        jQuery(".nav-wide .menu-wrapper").each(function() {
            columnsCount = jQuery(this).data("columns");
            items = jQuery(this).find("ul.level0 > li");
            groupsCount = items.size() / columnsCount;
            ratio = 1;
            for (i = 0; i < groupsCount; i++) {
                currentGroupe = items.slice((i * columnsCount), (columnsCount * ratio));
                columnsWidth(columnsCount, currentGroupe);
                ratio++
            }
        });
        elements = jQuery(".nav-wide .menu-wrapper.default-menu ul.level0 li");
        if (elements.length) {
            elements.on("mouseenter mouseleave", function() {
                if (!jQuery(".nav-container").hasClass("mobile")) {
                    jQuery(this).children("ul").toggle()
                }
            });
            jQuery(window).resize(function() {
                if (!jQuery(".nav-container").hasClass("mobile")) {
                    elements.find("ul").hide()
                }
            });
            elements.each(function() {
                if (jQuery(this).children("ul").length) {
                    jQuery(this).addClass("parent")
                }
            });
            items = [];
            jQuery(".nav-wide li.level0").each(function() {
                if (jQuery(this).children(".default-menu").length) {
                    items.push(jQuery(this))
                }
            });
            jQuery(items).each(function() {
                jQuery(this).on("mouseenter mouseleave", function() {
                    if (jQuery(this).hasClass("over")) {
                        if (!jQuery("body").hasClass("rtl")) {
                            jQuery(this).children(".default-menu").css({
                                top: jQuery(this).position().top,
                                left: jQuery(this).position().left
                            })
                        } else {
                            jQuery(this).children(".default-menu").css({
                                top: jQuery(this).position().top,
                                left: jQuery(this).position().left - (jQuery(this).children(".default-menu").width() - jQuery(this).width())
                            })
                        }
                    } else {
                        jQuery(this).children(".default-menu").css("left", "-10000px")
                    }
                })
            })
        }
    }
});
jQuery(document).ready(function() {
    imageList = jQuery("img[data-src-mobile]");
    imageController();
    jQuery(".language-currency-block").on("click", function() {
        jQuery(".language-currency-block").toggleClass("open");
        jQuery(".language-currency-dropdown").slideToggle()
    });
    var b = false;
    if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
        b = true;

        function d(e) {
            items = jQuery("header.header, .backstretch");
            if (e == false) {
                topIndent = jQuery(window).scrollTop();
                items.css({
                    position: "absolute",
                    top: topIndent
                })
            } else {
                items.css({
                    position: "fixed",
                    top: "0"
                })
            }
        }
        jQuery(".sticky-search header#sticky-header .form-search input").on("focusin focusout", function() {
            jQuery(this).toggleClass("focus");
            if (jQuery("header.header").hasClass("floating")) {
                if (jQuery(this).hasClass("focus")) {
                    setTimeout(function() {
                        d(false)
                    }, 500)
                } else {
                    d(true)
                }
            }
        })
    }

    function c() {
        jQuery(".header .search-button").each(function() {
            jQuery(this).off().on("click", function() {
                if (jQuery(this).parents(".form-search").hasClass("type-1") || jQuery(this).parents(".form-search").hasClass("type-3")) {
                    jQuery(this).parents(".form-search").addClass("active").find(".indent").append('<span class="btn-close" />').css({
                        left: 0,
                        "z-index": "999991"
                    }).animate({
                        opacity: 1
                    }, 150)
                }
                if (jQuery(this).parents(".form-search").hasClass("active") && jQuery(this).parents(".form-search").find(".btn-close").length) {
                    jQuery(this).parents(".form-search").find(".btn-close").on("click", function() {
                        jQuery(this).parents(".indent").css("left", "-100%").animate({
                            opacity: 0,
                            "z-index": "-1"
                        }, 150);
                        jQuery(this).parents(".form-search").removeClass("active").find(".btn-close").remove()
                    })
                }
            })
        })
    }
    c();
    if (jQuery("#sticky-header").length) {
        var a = jQuery("#header").height();
        sticky = jQuery("#sticky-header");
        jQuery(window).on("scroll", function() {
            if (jQuery(document.body).width() > 977) {
                if (!b) {
                    heightParam = a
                } else {
                    heightParam = a * 2
                }
                if (jQuery(this).scrollTop() >= heightParam) {
                    sticky.slideDown(100);
                    WideMenuTop()
                }
                if (jQuery(this).scrollTop() < a) {
                    sticky.hide();
                    WideMenuTop()
                }
            }
        });
        jQuery("#sticky-header .search-button").on("click", function() {
            jQuery(this).next(".indent").slideToggle(400)
        })
    }
    jQuery(document).on("click", '*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', function(e) {
        e.preventDefault();
        jQuery(this).ekkoLightbox()
    });
    jQuery(document).delegate('*[data-gallery="navigateTo"]', "click", function(e) {
        e.preventDefault();
        return jQuery(this).ekkoLightbox({
            onShown: function() {
                var f = this.modal_content.find(".modal-footer a");
                if (f.length > 0) {
                    f.click(function(g) {
                        g.preventDefault();
                        this.navigateTo(2)
                    }.bind(this))
                }
            }
        })
    });
    if (jQuery(".toolbar-bottom .pager .pages").length == 0 || jQuery(".products-list li.item.type-2").length) {
        jQuery(".toolbar-bottom").addClass("no-border")
    }
    if (jQuery(".footer-links-button").length) {
        jQuery(".footer-links-button").on("click", function() {
            jQuery(this).toggleClass("active").parent().find("ul").slideToggle(300)
        })
    }
    if (jQuery(".custom-block .mobile-button").length) {
        jQuery(".custom-block .mobile-button").click(function() {
            if (jQuery(".custom-block .indent").hasClass("active")) {
                jQuery(this).prev(".indent").removeClass("active").animate({
                    opacity: 0,
                    "z-index": "-1",
                    height: "0"
                })
            } else {
                jQuery(this).prev(".indent").addClass("active").animate({
                    opacity: 1,
                    "z-index": "999",
                    height: "100%"
                })
            }
        })
    }
    $$(".nav").each(function(e) {
        new mainNav(e, {
            show_delay: "100",
            hide_delay: "100"
        })
    });
    if (jQuery("body").hasClass("header-with-image") && jQuery(".catalog-product-view .product-buttons").length) {
        jQuery(".product-buttons").appendTo(jQuery(".breadcrumbs-inner"))
    }
    if (jQuery(".toolbar .pager .pages").length == 0) {
        jQuery(".toolbar").addClass("no-pagination")
    }
    if (typeof(AjaxImageLoader) !== "undefined") {
        AjaxImageLoader.individualStart = function(e) {
            e.closest(".product-image").addClass("loading")
        };
        AjaxImageLoader.individualSuccess = function(e) {
            e.closest(".product-image").removeClass("loading")
        };
        AjaxImageLoader.init()
    }
    if ("undefined" != typeof AjaxKitMain) {
        AjaxKitMain._reinitSubmodules = AjaxKitMain.reinitSubmodules;
        AjaxKitMain.reinitSubmodules = function() {
            AjaxKitMain._reinitSubmodules();
            if ("undefined" != typeof layeredNavigation) {
                layeredNavigation()
            }
        }
    }
    if ("undefined" != typeof GeneralToolbar) {
        GeneralToolbar.onLoadingStart = function() {
            jQuery("#toolbar-loading").show()
        };
        GeneralToolbar.onLoadingFinish = function() {
            jQuery("#toolbar-loading").hide();
            if ("undefined" != typeof layeredNavigation) {
                layeredNavigation()
            }
        };
        GeneralToolbar.onInit = function() {
            jQuery(".selectpicker").selectpicker("refresh");
            imageController(true);
            if ("undefined" != typeof ConfigurableSwatchesList) {
                ConfigurableSwatchesList.init()
            }
        };
        GeneralToolbar.onLoadingAutoScroll = function() {
            jQuery("#AjaxKit-InfiniteScroll").html(jQuery(".infinite-scroll-elements .infinite-scroll-loader"))
        };
        GeneralToolbar.onLoadingStaticScroll = function() {
            jQuery("#AjaxKit-InfiniteScroll").html(jQuery(".infinite-scroll-elements .infinite-scroll-loader"))
        };
        GeneralToolbar.onShowStaticScroll = function() {
            jQuery("#AjaxKit-InfiniteScroll").html(jQuery(".infinite-scroll-elements .infinite-scroll-button"))
        }
    } else {
        if ("undefined" != typeof ConfigurableSwatchesList) {
            jQuery(document).on("configurable-media-images-init", function() {
                ConfigurableSwatchesList.init()
            })
        }
    }
    jQuery(".navbar-toggle").off().on("click", function(f) {
        f.preventDefault();
        target = jQuery(this).data("target");
        jQuery(target).slideToggle("medium")
    });
    jQuery('*[data-toggle="tooltip"]').on("mouseenter", function() {
        jQuery(this).tooltip("show")
    });
    if (/iPhone|iPad|iPod|Mac/i.test(navigator.userAgent)) {
        jQuery("body").addClass("apple-device")
    }
});

function appendFont(a) {
    var b = document.createElement("link");
    b.href = a;
    b.type = "text/css";
    b.rel = "stylesheet";
    document.getElementsByTagName("head")[0].appendChild(b)
};
