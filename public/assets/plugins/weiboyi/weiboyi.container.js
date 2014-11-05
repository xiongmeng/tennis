(function($, W, undefined) {
    var opts = {
        items: [],
        src: undefined,
        content: undefined,
        contentHeight: "auto",
        height: "inherit",
        layout: {
            type: undefined
        },
        cmpCls: "weiboyiCtnr",
        lazyLoad: false
    };

    var methods = {
        initComponent: function() {
            var self = this;

            if (typeof self.layout === "string") {
                var l = self.layout;
                self.layout = {
                    type: l
                };
            }
            if (!self.lazyLoad) {
                self.load();
            }
            if (typeof self.height === "number") {
                self.el.height(self.height);
            }
        },
        doLoader: function() {
            var self = this;
            if (W._isDeferred(self.loader)) {
                self.el.addClass(self.cmpCls + "_loading");
                self.loader.always(function() {
                    self.el.removeClass(self.cmpCls + "_loading");
                });
                return self.loader;
            } else {
                self.el.addClass(self.cmpCls + "_loading");
                var complete = self.loader.complete;
                self.loader.complete = function() {
                    self.el.removeClass(self.cmpCls + "_loading");
                    if (typeof complete === "function") {
                        complete.apply(self, arguments);
                    }
                };

                var success = self.loader.success;
                self.loader.success = function() {
                    if (typeof success === "function") {
                        success.apply(self.proxy || self, arguments);
                    }
                };

                return $.ajax(self.loader);
            }
        },
        doLayout: function() {

        },
        setLoader: function(loader) {
            this.loader = loader;
            this.el.empty();
            return this.doLoader();
        },
        getContent: function() {
            return this.content;
        },
        setContent: function(content) {
            content = $(content);
            if (content.length && this.el.children().get(0) !== content.get(0)) {
                
                this.content = content;
                if (this.outward) {
                    this.outward.content = this.content;
                }
                this.el.empty().append(this.content.show());
                this.trigger("contentchanged");
            }
        },
        getHtml: function() {
            return this.html;
        },
        setHtml: function(html) {
            var self = this;
            self.el.html(this.html = html || this.html);

            //当内容中有图片时,图片load后会影响内容尺寸
            self.el.find("img").on("load", function() {
                self.trigger("contentchanged");
            });
            self.trigger("contentchanged");
        },
        getText: function() {
            return this.text;
        },
        setText: function(text) {
            this.el.text(this.text = text || this.text).addClass(this.cmpCls + "Text");
            this.trigger("contentchanged");
        },
        empty: function(fire) {
            if (this.items && this.cfgItems) {
                var items = this.items.slice(0);
                //这里不直接用this.items循环
                //因为this.items在this.parentCmp.removeItem(this, true)
                //后长度发生变化,$.each找到的项就不正确了
                $.each(items, function(i, e) {
                    if (e && e.destroy) {
                        e.destroy();
                    }
                });
            }
            this.el.empty();
            this.status.loaded = false;
            if (fire !== false) {
                this.trigger("contentchanged");
            }
        },
        reload: function() {
            this.empty(false);
            this.load();
        },
        load: function() {
            if (this.status.loaded) {
                return false;
            }
            if (this.config.items instanceof Array && this.config.items.length) {
                //Weiboyi组件子元素
                this.renderItems();
            }
            else if ($(this.content).length) {
                //content
                this.setContent(this.content);
            }
            else if (this.config.html) {
                this.setHtml();
            }
            else if (this.config.text) {
                this.setText();
            }
            else if (this.config.src) {
                //iframe
                this.iframe = $('<iframe class="weiboyiIframeContent" border="0" frameborder="0" src="about:blank" allowTransparency="true"></iframe>').appendTo(this.el);
                this.iframe.attr("src", this.src);
            }
            if (this.config.loader) {
                this.doLoader();
            }
            this.status.loaded = true;
        },
        renderItems: function() {
            var self = this;
            this.items = [];

            if (this.outward) {
                this.outward.items = this.items;
            }
            $.each(this.config.items, function(i, e) {
                self.addItem(e);
            });
            this.doLayout();
        },
        renderItem: function(item, renderTo) {
            if (typeof item.layout === "string") {
                item.layout = {
                    type: item.layout
                };
            }
            var constructor = W.register[item.xtype || (item.layout ? item.layout.type ? item.layout.type + "ctnr" : "container" : "container")];
            item.renderTo = renderTo || this.el;
            if (constructor) {
                //item.height = item.height || this.contentHeight;
                return new constructor(item);
            }
        },
        addItem: function(e) {
            var self = this;
            var item = W.isCmp(e) ? e : self.renderItem(e);
            if (item) {
                item.render(self);
                self.items.push(item);
                self.trigger("contentchanged");
            }
        },
        addItems: function(items) {

        },
        removeItem: function(item) {

        },
        getItems: function() {
            return this.items;
        },
        getOutWard: function() {
            return this.outward;
        },
        destroy: function() {
            if (this.items && this.items.length) {
                var items = this.items.slice(0);
                //这里不直接用this.items循环
                //因为this.items在this.parentCmp.removeItem(this, true)
                //后长度发生变化,$.each找到的项就不正确了
                $.each(items, function(i, e) {
                    if (e && e.destroy) {
                        e.destroy();
                    }
                });
            }
            else if (this.iframe) {
                this.iframe.contentWindow.location = "about:blank";
            }
            W.Component.prototype.destroy.apply(this, arguments);
        }
    };

    W.createComponent({
        xtype: "Container",
        opts: opts,
        methods: methods,
        extend: W.Component,
        api: ["getOutWard" ,"hide", "show", "status", "render", "doLoader", "trigger", "removeListener", "addListener", "load", "destroy", "addItems", "removeItem", "renderItem", "renderItems", "setText", "setContent", "setHtml", "reload", "getContent", "getHtml", "getText", "setLoader", "addItem", "getItems"]
    });
    
}) (jQuery, Weiboyi);