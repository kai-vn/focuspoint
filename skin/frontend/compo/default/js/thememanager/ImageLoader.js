/*  appear lib */

!function(e){function r(){n=!1;for(var r=0;r<i.length;r++){var a=e(i[r]).filter(function(){return e(this).is(":appeared")});if(a.trigger("appear",[a]),t){var o=t.not(a);o.trigger("disappear",[o])}t=a}}var t,i=[],a=!1,n=!1,o={interval:250,force_process:!1},f=e(window);e.expr[":"].appeared=function(r){var t=e(r);if(!t.is(":visible"))return!1;var i=f.scrollLeft(),a=f.scrollTop(),n=t.offset(),o=n.left,p=n.top;return p+t.height()>=a&&p-(t.data("appear-top-offset")||0)<=a+f.height()&&o+t.width()>=i&&o-(t.data("appear-left-offset")||0)<=i+f.width()?!0:!1},e.fn.extend({appear:function(t){var f=e.extend({},o,t||{}),p=this.selector||this;if(!a){var s=function(){n||(n=!0,setTimeout(r,f.interval))};e(window).scroll(s).resize(s),a=!0}return f.force_process&&setTimeout(r,f.interval),i.push(p),e(p)}}),e.extend({force_appear:function(){return a?(r(),!0):!1}})}(jQuery);




var AjaxImageLoader = {

    is_retina:null,
    NotUseRetina:'no_retina',
    UseRetina:'retina',
    UseRetinaNoCookie:'retina_no_cookie',
    LazyloadEffect:'fadeIn',
    ImageCount:0,
    ImageCountFinished:0,

    allStart:function(){},
    individualStart:function(this_image){},
    allSuccess:function(){},
    individualSuccess:function(this_image){},

    init: function()
    {
        var self = this;
		jQuery('img.no_ajax_image_loader').each(function(){
			jQuery(this).removeClass('ajax_image_loader').removeClass('lazy_image_loader');
		});
        var ajax_loader_images = jQuery('img.ajax_image_loader');
        if (ajax_loader_images.length >0)
        {
            ajax_loader_images.removeClass('lazy_image_loader');
            //ajax_loader_images.each(function()
            //{
        		//this_image.removeClass('lazy_image_loader');
            //});
        }


        var ajax_loader_images = jQuery('img.ajax_image_loader, img.lazy_image_loader');
        if (ajax_loader_images.length >0)
        {
            self.allStart();
            ajax_loader_images.each(function()
            {
                var this_image = jQuery(this);
                self.individualStart(this_image);
                self.processingRetina(this_image);
                setTimeout(function() { self.loadImage(this_image) }, 1);
                self.ImageCount++
            });
        }

        jQuery('img.lazy_image_loader').appear();
        jQuery('img.lazy_image_loader').on('appear', function(event, appeared_elements)
        {
            appeared_elements.each(function()
            {
                image = jQuery(this);
                if (!image.attr('data-showed') && !image.attr('data-img-src'))
                {
                    self.processingSuccess(image);
                }
            });
        });
    },
    processingRetina: function(image)
    {
        var self = this;
        var ajax_retina = image.attr('data-ajax_retina');

        if (self.is_retina == null && ajax_retina == 'retina_no_cookie')
        {
            var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;
            self.is_retina = pixelRatio > 1

            if (!jQuery.cookie('retina'))
            {
                var retina = self.is_retina ? self.UseRetina : self.NotUseRetina;
                jQuery.cookie('retina', retina)
            }
        }
        if (ajax_retina == 'retina')
        {
            self.is_retina = true;
        }

        var data_srcx2 = image.attr('data-img-srcX2');
        if (self.is_retina && undefined != data_srcx2)
        {
            image.attr('data-img-src', data_srcx2);
            image.removeAttr('data-img-srcX2');
        }
    },
    loadImage: function(image)
    {
        var self = this;
        var source = image.attr('data-img-src');
        if ( undefined != source)
        {
            var new_image = new Image();
            new_image.onload = function ()
            {
                image.attr('src', source);
                if (self.is_retina)
                {
                    var width = new_image.width/2;
                    image.css('width', width+'px');
                }
                self.LoadingFinished(image);
            }
            new_image.src=source;
        }
        else
        {
            self.LoadingFinished(image);
        }
    },

    LoadingFinished: function(image)
    {
        var self = this;
        image.removeAttr('data-img-src');

        if (image.hasClass('ajax_image_loader') || image.is(':appeared'))
        {
            self.processingSuccess(image);
        }

        self.ImageCountFinished++

        if (self.ImageCount == self.ImageCountFinished)
        {
            self.allSuccess();
        }
    },


    processingSuccess: function(image)
    {
        image.attr('data-showed', true);
        this.individualSuccess(image);
    }


}

