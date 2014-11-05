(function($, W, undefined) {

    var opts = {
        cmpCls: "weiboyiWindow",
        style: {
        },
        lazyLoad: true,
        layout: {},
        frameHeight: 50,
        frameWidth: 20,
        tbar: ["close"],
        width: 500,
        height: 300,
        autoShow: false,
        title: undefined,
        icon: undefined,
        minWidth: 150,
        minHeight: 100,
        modal: true,
        floor: "middle",
        defaultBtnType: "strong"
    };

    var methods = {
        locate: function(position) {
            var css = {
                "margin-top": - (this.getHeight() / 2) + "px",
                "margin-left": - (this.getWidth() / 2) + "px",
                position: "fixed"
            };

            if (W.util.ie6) {
                css.position = "absolute";
            }

            this.el.addClass("absoluteCenter").css(css);
        },
        isClosable: function() {
            return !!($.grep(this.tbar || [], function(btn) {
                if (btn === 'close' || btn.id === 'close') {
                    return true;
                }
            }).length);
        },
        doContainer: function() {
            var self = this;
            if (!self.layout || typeof self.layout === "string") {
                var l = self.layout;
                self.layout = {
                    type: l
                };
            }
            self.proxy = self.proxy || {};

            var cfg = {
                proxy: self.proxy,
                items: self.items,
                height: self.contentHeight || "inherit",
                content: self.content,
                src: self.src,
                html: self.html,
                outward: self,
                el: self.bodyEl,
                text: self.text,
                loader: self.loader,
                lazyLoad: self.lazyLoad,
                listeners: self.untreatedListeners
            };

            var c = self.container = new W.register[self.layout.type ? self.layout.type + "ctnr" : "container"]($.extend(cfg, self.layout));
            
            if (self.constructor.api instanceof Array) {
                $.each(c, function(k, v) {
                    if (!(k in self.proxy)) {
                        self.proxy[k] = v;
                    }
                });
            }
            

            this.addListener("afterresize", function() {
                this.container.resize({height: this.contentHeight});
            });
        },
        initComponent: function(mustLoad) {

            var self = this;

            self.buildDom();

            if (typeof self.height === "number") {
                self.contentHeight = self.height - self.frameHeight;
                self.bodyEl.height(self.contentHeight);
            }

            if (W.util.ie6 && self.width === "auto") {
                self.width = 600;
            }

            self.el.width(self.width);
            self.el.attr({
                'tabindex': -1
            });
            self.initHead();
            self.initButtons();
            self.doDraggable();
            self.doResizable();

            var css = {
                "z-index": W.zIndex.getMax(self.floor)
            };

            if (self.modal !== false) {
                if (!self.floor) {
                    self.floor = "low";
                    css["z-index"] = W.zIndex.getMax(self.floor, 2);
                }
                self.initMask(css["z-index"] - 1);
            }

            self.el.css(css);

            if (W.util.ie6) {
                self.ie6Mask = $('<iframe border="0" frameborder="0" cellspacing="0" class="ie6mask" src="about:blank"></iframe>');
                self.el.append(self.ie6Mask);
                setTimeout(function() {
                    if (self.status) {
                        self.ie6Mask.css({
                            height: self.el.innerHeight(),
                            width: self.el.innerWidth()
                        });
                    }
                }, 100);
            }

            //这里要加入到afterinitialize事件中
            self.doContainer();
            self.addListener("contentchanged", function() {
                if (self.height === "auto" && !self.status.hidden) {
                    setTimeout(function() {
                        if (self.status) {
                            self.locate("contentchanged");
                        }
                        if (W.util.ie6) {
                            if (self.status) {
                                self.ie6Mask.css({
                                    height: self.el.innerHeight(),
                                    width: self.el.innerWidth()
                                });
                            }
                        }
                    }, 0);
                }
            });
            self.addListener("afterresize", function() {
                self.locate("center");
            });
            self.status.loaded = true;
        },
        doInitStatus: function() {
            this.status.hidden = true;
            if (this.autoShow) {
                this.show();
            }
        },
        initMask: function(zIndex) {
            var self = this,
                css = {
                    "z-index": zIndex,
                    width: $(window).width(),
                    height: $(window).height()
                };

            self.mask = $('<div class="' + self.cmpCls + '_mask"></div>');

            if (W.util.ie6) {

                css.top = $(document).scrollTop();
                css.left = $(document).scrollLeft();

                this.mask.append('<iframe border="0" frameborder="0" cellspacing="0" class="" src="about:blank"></iframe>');
                $(window).scroll(function() {
                    $("." + self.cmpCls + "_mask").css({
                        top: $(document).scrollTop(),
                        left: $(document).scrollLeft()
                    });
                });
            }

            self.mask.css(css);

            if (!W.maskResizeEvent) {
                W.maskResizeEvent = true;
                var timer;
                $(window).resize(function() {
                    clearTimeout(timer);
                    timer = setTimeout(function() {
                        $("." + self.cmpCls + "_mask").css({
                            width: $(window).width(),
                            height: $(window).height()
                        });
                    }, 100);
                    
                });
            }

            if ($.isNumeric(this.modal)) {
                this.mask.css({
                    opacity: this.modal,
                    filter: "alpha(opacity=" + this.modal * 100 + ")"
                });
            }
            
            if (this.status.rendered) {
                this.mask.appendTo("body");
            }
        },
        doDraggable: function() {
            /*if (this.draggable === true) {
                this.el.draggable({
                    handle: ".weiboyiWindow_tl"
                });
            }
            else if (W.util.isObject(this.draggable)) {
                this.el.draggable($.extend(this.draggable, {
                    handler: ".weiboyiWindow_tl"
                }));
            }*/
        },
        doResizable: function() {
            /*if (this.height === "auto") {
                return;
            }
            var self = this;
            var opts = {
                alsoResize: this.bodyEl,
                minWidth: this.minWidth,
                minHeight: this.minHeight,
                autoHide: true,
                stop: function(e, ui) {
                    self.contentHeight = ui.size.height;
                    self.trigger("afterresize");
                }
            };

            if (this.resizable === true) {
                this.el.resizable(opts);
            }
            else if (W.util.isObject(this.resizable)) {
                this.el.resizable($.extend(this.resizable, opts));
            }*/

        },
        initHead: function() {
            if (this.title || (this.tbar && this.tbar.length) || this.icon) {
                if (this.icon) {
                    var icon = $('<span class="' + this.cmpCls + '_head_icon"></span>');
                    icon.addClass(this.icon);
                    this.headEl.append(icon);
                }
                this.titleEl = $('<span class="' + this.cmpCls + '_head_title"></span>');
                this.titleEl.append($('<span></span>').html(this.title));
                var tbar = $('<span class="' + this.cmpCls + '_head_tools"></span>');
                W.Tools.renderTools.call(this, this.tbar, tbar);
                this.headEl.show().append(this.titleEl).append(tbar);
                this.status.headerinited = true;
            }
            else {
                this.headEl.hide();
            }
        },
        initButtons: function() {
            var self = this;

            self.bbar = self.buttons || self.bbar;
            self.buttons = [];

            if (self.bbar instanceof Array && self.bbar.length) {
                self.footEl = $('<div class="' + self.cmpCls + '_foot"></div>');
                self.footEl.appendTo(self.el.find("." + self.cmpCls + "_bc"));
                $.each(self.bbar, function(i, e) {

                    var button = new W.Button({
                        renderTo: self.footEl,
                        btnCls: e.cls,
                        id: e.id,
                        cls: self.cmpCls + "_btn",
                        type: e.type || self.defaultBtnType,
                        scope: self.proxy || self,
                        size: e.size,
                        text: e.text,
                        handler: e.handler,
                        disabled: e.disabled
                    });
                    self.buttons.push(button);

                    if (e.defaultBtn) {
                        $(document).on('keypress', ':not(textarea,input)', function(e) {
                            if ((e.keyCode === 13 || e.keyCode === 32) && (self.el.is(e.target) || self.el.has(e.target).length)) {

                                button.active();

                                setTimeout(function() {
                                    button.process();
                                }, 500);
                                e.preventDefault();
                            }
                        });
                    }
                    
                });
            }
        },
        buildDom: function() {
            var tl = $("<div></div>").addClass(this.cmpCls + "_tl");
            var tr = $("<div></div>").addClass(this.cmpCls + "_tr");
            var tc = $("<div></div>").addClass(this.cmpCls + "_tc");
            tl.append(tr.append(tc));

            var ml = $("<div></div>").addClass(this.cmpCls + "_ml");
            var mr = $("<div></div>").addClass(this.cmpCls + "_mr");
            var mc = $("<div></div>").addClass(this.cmpCls + "_mc");
            ml.append(mr.append(mc));


            var bl = $("<div></div>").addClass(this.cmpCls + "_bl");
            var br = $("<div></div>").addClass(this.cmpCls + "_br");
            var bc = $("<div></div>").addClass(this.cmpCls + "_bc");
            bl.append(br.append(bc));

            this.headEl = $('<div class="' + this.cmpCls + '_head"></div>');
            this.headEl.appendTo(tc);
            this.bodyEl = $('<div class="' + this.cmpCls + '_body"></div>').appendTo(mc);

            this.el.append(tl).append(ml).append(bl);
        },
        getHeight: function() {
            return this.el.height() || this.height * 1 || 0 + this.frameHeight;
        },
        getWidth: function() {
            return this.el.width() || this.width * 1 || 0 + this.frameWidth;
        },
        setContentHeight: function(height) {
            this.contentHeight = height;
            this.bodyEl.height(height);
        },
        show: function() {
            var self = this;

            if (!self.status.rendered) {
                self.render("body");
                if (self.mask) {
                    self.mask.appendTo("body");
                }
            }

            if (!self.status.hidden) {
                return false;
            }
            if (self.modal !== false) {
                if (W.util.ie6) {
                    self.mask.height($(window).height());
                }
                self.mask.show();
            }

            //增加显示效果的时候先进行定位
            self.el.css({
                display: "block",
                visibility: "hidden"
            });
            self.locate("center");
            self.el.css({
                display: "none",
                visibility: "visible"
            });

            var der = W.Component.prototype.show.apply(this, arguments);
            if (W._isDeferred(der)) {
                //处理lazyload
                der.done(function() {
                    if (self.container) {
                        self.container.load();
                    }
                });
            }

            return der;
        },
        close: function() {
            if (this.closeAction === "destroy") {
                this.destroy();
            }
            else {
                this.hide();
            }
        },
        hide: function() {
            var der = W.Component.prototype.hide.apply(this, arguments);
            if (this.modal !== false) {
                this.mask.hide();
            }
            return der;
        },
        destroy: function() {
            if (this.modal !== false) {
                this.mask.remove();
            }
            $.each(this.buttons || [], function(i, e) {
                if (e && e.destroy) {
                    e.destroy();
                }
            });
            W.Component.prototype.destroy.apply(this, arguments);
        },
        getTitle: function() {
            return this.title;
        },
        setTitle: function(title) {
            if (typeof title !== 'undefined') {
                this.title = title || "";
            }

            if (!this.status.headerinited) {
                this.initHead();
            } else {
                this.titleEl.html(this.title);
            }
        }
    };

    W.createComponent({
        xtype: "Window",
        opts: opts,
        methods: methods,
        extend: W.Component,
        api: ["content", "status", "setTitle", "getTitle", "destroy", "show", "hide", "trigger", "addListener", "removeListener", "close", "setContentHeight", "setHeight", "setWidth", "getWidth", "getHeight"]
    });

    W.baseMessageBox = function(msg, type, windowCfg) {
        var msgCls = {
            success: "weiboyiMessage_success",
            error: "weiboyiMessage_error",
            info: "weiboyiMessage_info",
            loading: "weiboyiMessage_loading",
            question: "weiboyiMessage_question"
        } [type || "info"];

        var ctnr = $('<div class="weiboyiMessage_ctnr"></div>');
        var content = $('<span class="weiboyiMessage_content ' + msgCls + '"></span>').html(msg);
        
        // text方法给span加入了displayinline的属性
        content.css({
            "display": "inline-block"
        });

        return new W.Window($.extend({
            content: ctnr.append(content),
            autoShow: true,
            width: 400,
            height: "auto",
            floor: "high",
            closeAction: "destroy",
            modal: 0
        }, windowCfg));
    };

    W.confirm = function(msg, type, callback) {
        if (typeof type === "function") {
            callback = type;
            type = "question";
        }

        var win = W.getCmp("weiboyiConfirmWin");
        if (win) {
            win.destroy();
        }

        W.baseMessageBox(msg, type, {
            title: "提示",
            id: "weiboyiConfirmWin",
            tbar: [{
                id: "close",
                handler: function() {
                    if (typeof callback === "function") {
                        callback.call(this, false);
                    }
                    this.close();
                }
            }],
            bbar: [{
                text: "确定",
                defaultBtn: true,
                handler: function() {
                    if (typeof callback === "function") {
                        callback.call(this, true);
                    }
                    this.close();
                }
            }, {
                text: "取消",
                cls: "btn_small_normal",
                handler: function() {
                    if (typeof callback === "function") {
                        callback.call(this, false);
                    }
                    this.close();
                }
            }]
        });
    };

    W.alert = function(msg, type, callback) {
        if (typeof type === "function") {
            callback = type;
            type = "info";
        }

        var win = W.getCmp("weiboyiAlertWin");
        if (win) {
            win.destroy();
        }

        W.baseMessageBox(msg, type, {
            title: "提示",
            id: "weiboyiAlertWin",
            tbar: [{
                id: "close",
                handler: function() {
                    if (typeof callback === "function") {
                        callback.call(this, false);
                    }
                    this.close();
                }
            }],
            bbar: [{
                text: "确定",
                handler: function() {
                    if (typeof callback === "function") {
                        callback.call(this, true);
                    }
                    this.close();
                }
            }]
        });
    };

    W.message = function(msg, type, duration) {
        if (typeof type === "number") {
            duration = type;
            type = "info";
        }

        var callback = W.util.asArray(arguments).pop();

        var win = W.getCmp("weiboyiMessageWin");
        if (win) {
            win.destroy();
        }
        win = W.baseMessageBox(msg, type, {
            id: "weiboyiMessageWin",
            tbar: []
        });

        if (typeof duration !== "number") {
            duration = 2;
        }
        if (duration !== 0) {
            setTimeout(function() {
                if (win.status) {
                    win.close();
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            }, 1000 * duration);
        }

        return {
            close: function() {
                if (win.close) {
                    win.close();
                    if (typeof callback === 'function') {
                        callback();
                    }
                }
            }
        };
    };

}) (jQuery, Weiboyi);