(function($, W, undefined) {

    /**
     * 按比例缩放图片
     *     调用：    $("#tobeScale").wImageScale({width: xxx, height: xxx});
     *      DOM:     <div id="tobeScale"><img src="xxxx"></div>
     * @param  {width: xxx, height: xxxx}
     * @return
     */
    $.fn.imageScale = function(settings) {

        settings.width = parseInt(settings.width, 10);
        settings.height = parseInt(settings.height, 10);

        if (!$.isNumeric(settings.width) || !$.isNumeric(settings.height)) {
            return this;
        }

        return this.each(function(i, img) {
            if (img && img.tagName.toLowerCase() !== "img") {
                return;
            }
            
            img = $(img);
    
            var container;

            if (typeof settings.container === "string") {
                container = img.closest(settings.container);
            }
            else {
                container = img.parent();
            }


            if (img.length !== 1) {
                return;
            }

            container.css({
                width: settings.width,
                height: settings.height,
                overflow: "hidden",
                display: "block"
            });

            function scale(img) {
                $(img).removeAttr("height").removeAttr("width");
                $(img).css({
                    width: 'auto',
                    height: 'auto'
                });
                var width = img.clientWidth || img.width,
                    height = img.clientHeight || img.height;

                if (width / height > settings.width / settings.height) {
                    img.style.height = settings.height + 'px';
                }
                else {
                    img.style.width = settings.width + 'px';
                }
            }

            var loadTimer;
            img.load(function() {
                var self = this;
                clearTimeout(loadTimer);
                loadTimer = setTimeout(function() {
                    scale(self);
                }, 100);
            });

            scale(img.get(0));
        });
    };

}) (jQuery, Weiboyi);