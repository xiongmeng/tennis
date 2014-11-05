(function($, W, undefined) {
    var opts = {
        template: "default",
        cmpCls: "weiboyiTips",
        style: {
        },
        tbar: [],
        width: 300,
        height: "auto",
        offset: {
            x: 5,
            y: 5
        },
        anchorOffset: {
            x: 0,
            y: 0
        },
        lazyLoad: true,
        title: undefined,
        icon: undefined,
        target: undefined,
        autoShow: false,
        trackMouse: false,
        floor: "middle",
        modal: false,
        group: undefined,
        // [click, mouseover]
        type: "click",
        // [top, bottom, left, right]
        anchor: "bottom",
        // 自动隐藏事件, 单位秒, false不隐藏
        autoHide: 1,
        defaultBtnType: "important"
    };

    var methods = {
        initComponent: function() {
            var self = this;
            self.offset.x = self.offset.x || 0;
            self.offset.y = self.offset.y || 0;
            self.target = $(self.target);

            if (!self.autoShow) {
                self.respond();
                if (typeof self.autoHide !== "number" && !self.trackMouse) {
                    self.tbar.push("close");
                }
            }
            else {
                self.currentTarget = self.target;
            }
            W.Window.prototype.initComponent.apply(self, arguments);
        },
        respond: function() {
            var self = this;
            if (this.autoHide === true) {
                this.autoHide = 0;
            }

            //对绑定事件类型做容错处理
            this.eventType = "bind";
            try {
                if (this.target.length === $(this.target.selector).length) {
                    this.eventType = "live";
                }
            } catch (e) {

            } finally {

            }

            this.target[this.eventType](this.type, function(evt) {
                self.currentTarget = $(this);
                self.show();
                self.trigger("ontargetchanged", [self, self.currentTarget]);
                clearTimeout(self.hideTimer);
            });
            
            this.el.add(this.target).bind("mouseover", function() {
                clearTimeout(self.hideTimer);
            }).bind("mouseleave", function() {
                if (typeof self.autoHide === "number") {
                    clearTimeout(self.hideTimer);
                    self.hideTimer = setTimeout(function() {
                        if (self.status) {
                            self.hide();
                        }
                    }, self.autoHide * 1000);
                }
            });

            if (this.trackMouse) {
                this.autoHide = 0;
                this.target.mousemove(function(e) {
                    if (!self.status.hidden) {
                        self.el.css({
                            left: e.pageX + self.offset.x + "px",
                            top: e.pageY + self.offset.y + "px"
                        });
                    }
                });
            }
        },
        locate: function(target) {
            if (!target || !target.length) {
                return;
            }
            else if (target === "contentchanged") {
                //当anchor在下方,内容高度有变化时重新定位
                this.locate(this.currentTarget);
                return;
            }
            var h = this.getHeight(), 
                w = this.getWidth(),
                anchor = this.anchor,
                offset = target.offset(),
                tw = $(target).width(),
                th = $(target).height(),
                ww,
                wh,
                space,
                position = {},
                scrollY = window.scrollY || document.body.scrollTop,
                scrollX = window.scrollX || document.body.scrollLeft,
                de = document.documentElement;

            if (de) {
                ww = window.innerWidth || de.clientWidth;
                wh = window.innerHeight || de.clientHeight;
                scrollY = window.scrollY || de.scrollTop;
                scrollX = window.scrollX || de.scrollLeft;
            }
            else {
                ww = document.body.offsetWidth;
                wh = document.body.offsetHeight;
                scrollY = document.body.scrollTop;
                scrollX = document.body.scrollLeft;
            }

            ww -= 25;
            wh -= 25;

            space = {
                top: offset.top - scrollY,
                left: offset.left - scrollX,
                right: ww - offset.left - tw + scrollX,
                bottom: wh - offset.top - th + scrollY
            };

            if (anchor === "auto") {
                anchor = "bottom";
            }

            if (/^(top|bottom)$/.test(anchor) && space.bottom <= h + this.offset.y) {
                if (space.top <= h + this.offset.y) {
                    // 上下位置都不够时, 放在下面
                    anchor = "left";
                }
                else {
                    anchor = "top";
                }
            }

         
            if (space.left <= w + this.offset.x && anchor === "left") {
                anchor = "right";
            }
            if (space.right <= w + this.offset.x && anchor === "right") {
                anchor = "left";
            }

            switch (anchor) {
                case "left": {
                    position.top = offset.top + (th - h) / 2;
                    position.left = offset.left - w - this.offset.x;
                } break;
                case "right": {
                    position.top = offset.top + (th - h) / 2;
                    position.left = offset.left + tw + this.offset.x;
                } break;
                case "top": {
                    position.left = offset.left - w / 2 + tw / 2 + this.offset.x;
                    position.top = offset.top - h - this.offset.y;
                } break;
                default: {
                    position.left = offset.left - w / 2 + tw / 2 + this.offset.x;
                    position.top = offset.top + th + this.offset.y;
                }
            }

            this.currentAnchor = anchor;

            this.anchorLeft = w / 2 - 7 - this.offset.x + this.anchorOffset.x;
            this.anchorTop = h / 2 - 7 - this.offset.y + this.anchorOffset.y;

            var tmp;
            if (position.left < scrollX) {
                tmp = position.left;
                position.left = scrollX + 5;
                this.anchorLeft += tmp - position.left;
            }
            else if (position.left + w > scrollX + ww) {
                tmp = position.left;
                position.left = scrollX + ww - 5 - w;
                this.anchorLeft += tmp - position.left;
            }

            if (position.top < 2) {
                position.top = 2;
            }
            this.locateAnchor(anchor, w, h);
            this.el.css(position);
        },
        getTarget: function() {
            return this.target;
        },
        getCurrentTarget: function() {
            return this.currentTarget;
        },
        /**
         * 处理指针样式位置
         **/
        locateAnchor: function(anchor, w, h) {
            var self = this;
            var info = {
                top: {
                    selector: "." + this.cmpCls + "_bc",
                    css: {
                        top: W.util.ie6 ? h : "auto",
                        bottom: W.util.ie6 ? "auto" : -8,
                        left: self.anchorLeft
                    },
                    cls: self.cmpCls + "_anchor"
                },
                bottom: {
                    selector: "." + this.cmpCls + "_tc",
                    css: {
                        top: -8,
                        bottom: "auto",
                        left: self.anchorLeft
                    },
                    cls: self.cmpCls + "_anchor"
                },
                left: {
                    selector: "." + this.cmpCls + "_mr",
                    css: {
                        right: -8,
                        left: "auto",
                        top: self.anchorTop
                    },
                    cls: self.cmpCls + "_anchor anchor_left"
                },
                right: {
                    selector: "." + this.cmpCls + "_ml",
                    css: {
                        left: -8,
                        right: "auto",
                        top: self.anchorTop
                    },
                    cls: self.cmpCls + "_anchor anchor_right"
                }
            } [anchor];
            this.anchorEl = this.anchorEl || $('<span></span>');
            this.anchorEl.removeClass().addClass(info.cls);
            this.el.find(info.selector).addClass("tipsAnchorWrap").append(this.anchorEl.css(info.css));
        },
        show: function(target) {
            var self = this;
            if (!self.status.rendered) {
                self.render("body");
            }
            if (target) {
                target = $(target);
                if (target.length) {
                    self.currentTarget = target;
                }
            }

            if (self.container) {
                self.container.load();
            }

            if (!self.trackMouse) {
                self.locate(self.currentTarget);
            }
            if (self.group) {
                $.each(W.getCmps({group: this.group}), function(i, e) {
                    e.hide();
                });
            }
            return W.Component.prototype.show.apply(self, arguments);
        },
        destroy: function() {
            //解除绑定的dom事件
            if (this.eventType === "bind") {
                this.target.unbind(this.type);
            }
            else {
                this.target.die(this.type);
            }
            clearTimeout(this.hideTimer);
            W.Window.prototype.destroy.apply(this, arguments);
        }
    };

    W.createComponent({
        xtype: "Tips",
        opts: opts,
        methods: methods,
        extend: W.Window,
        api: ["getTarget", "getCurrentTarget", 'locate']
    });
}) (jQuery, Weiboyi);