(function($, W, undefined) {
    W.createComponent({
        xtype: "Component",
        opts: {},
        extend: undefined,
        api: ["status", "el", "show", "hide", "destroy", "getWidth", "setWidth", "getHeight", "setHeight", "addListener", "removeListener", "trigger", "render"],
        methods: {
            initialize: function() {

                var api = this.constructor.api,
                    self = this;

                self.status = {
                    initialized: false
                };

                self.events = self.events || {};

                //初始化组件容器
                self.el = $(self.el || "<div></div>");
                
                if (self.id) {
                    self.el.attr("id", self.id);
                }

                if (api instanceof Array) {
                    self.proxy = self.proxy || {
                        constructor: self.constructor
                    };

                    $.each(api, function(i, e) {
                        if (e in self.proxy) {
                            return;
                        }

                        if (typeof self[e] === "function") {
                            self.proxy[e] = function() {
                                return self[e] && self[e].apply(self, arguments);
                            };
                        }
                        else {
                            self.proxy[e] = self[e];
                        }
                    });
                }

                //调用组件子类的初始化方法
                if (self.initComponent() === false) {
                    self.destroy();
                    return;
                }

                self.proxy.el = self.el;

                self.doListeners();
                if (self.style) {
                    self.el.css(self.style);
                }

                //统一调用render方法
                if (self.renderTo) {
                    self.render(self.renderTo);
                }

                if (self.cmpCls) {
                    self.el.addClass(self.cmpCls);
                }

                if (self.cls) {
                    self.el.addClass(self.cls);
                }

                if (self.parentCmp) {
                    self.parentCmp.addItems(self);

                }

                if (self.animateTarget) {
                    self.animateTarget = $(self.animateTarget);
                    self.effect = self.effect || "animateTarget";
                }

                self.doInitStatus();
                //调用初始化事件方法
                
                self.status.initialized = true;
            },
            initComponent: function() {
                
            },
            doListeners: function() {
                var k;
                for (k in (this.config || this).listeners) {
                    if (this.config.listeners.hasOwnProperty(k)) {
                        this.addListener(k, this.listeners[k]);
                    }
                }
            },
            setHeight: function(height) {
                this.el.height(height);
                this.height = height;
            },
            getHeight: function() {
                return this.el.height() || this.height;
            },
            getWidth: function() {
                return this.el.width() || this.width;
            },
            setWidth: function(width) {
                this.width = width;
                this.el.width(width);
            },
            resize: function(size) {
                this.el.css(size);
            },
            addListener: function(type, handler, scope, ars) {
                var self = this,
                    proxy = self.proxy || self;

                W.EventManager.addListener.apply(proxy, [type, handler, scope, ars]);
                if (/^(before|after)\w+/.test(type)) {
                    var name = type.replace(/^(before|after)/, "");
                    if (typeof self[name] === "function" && !self[name].original) {
                        var tmp = self[name];
                        self[name] = function() {
                            if (self.trigger("before" + name, arguments) === false) {
                                return false;
                            }
                            var ret = self[name].original.apply(self, arguments);

                            var args = W.util.asArray(arguments);
                            

                            //简单的判断时候为延迟函数
                            if (W._isDeferred(ret)) {
                                ret.done(function() {
                                    args.unshift("resolved");
                                    self.trigger("after" + name, args);
                                }).fail(function() {
                                    args.unshift("rejected");
                                    self.trigger("after" + name, args);
                                });
                            }
                            else {
                                args.unshift(ret);
                                self.trigger("after" + name, args);
                            }
                            
                            return ret;
                        };
                        self[name].original = tmp;
                    }
                }
            },
            removeListener: function() {
                W.EventManager.removeListener.apply(this.proxy || this, arguments);
            },
            trigger: function() {
                return W.EventManager.trigger.apply(this.proxy || this, arguments);
            },
            render: function(renderTo) {
                if (W.isCmp(renderTo) && renderTo.getOutWard) {
                    this.outward = renderTo.getOutWard();
                    this.el.appendTo(renderTo.el);
                }
                else {
                    this.el.appendTo(renderTo);
                }
                this.status.rendered = true;
            },
            doInitStatus: function() {

            },
            effects: {
                //show|hide公用一个deferrred
                der: undefined,
                normal: {
                    show: function() {
                        this.el.show();
                        this.effects.der.resolveWith(this);
                        
                    },
                    hide: function() {
                        this.el.hide();
                        this.effects.der.resolveWith(this);
                    }
                },
                fade: {
                    show: function() {
                        var self = this;
                        this.el.fadeIn(250, function() {
                            self.effects.der.resolveWith(self);
                        });
                    },
                    hide: function() {
                        var self = this;
                        this.el.fadeOut(250, function() {
                            self.effects.der.resolveWith(self);
                        });
                    }
                },
                /**
                 * 
                 * @type {Object}
                 */
                animateTarget: {
                    show: function() {
                        if (this.animateTarget.length) {
                            var self = this;
                            this.animateHelper = this.animateHelper || $('<div class="' + this.cmpCls + '_animateHelper"></div>').appendTo("body");
                            this.el.css({
                                display: "block",
                                visibility: "hidden"
                            });
                            var offset = this.animateTarget.offset();
                            var winOffset = this.el.offset();

                            this.animateHelper.css({
                                display: "block",
                                width: this.animateTarget.width(),
                                height: this.animateTarget.height(),
                                left: offset.left,
                                top: offset.top,
                                opacity: "0.5"
                            }).animate({
                                height: "" + this.getHeight(),
                                width: "" + this.getWidth(),
                                left: "" + winOffset.left,
                                top: "" + winOffset.top,
                                opacity: "0.1"
                            }, "normal", function() {
                                self.animateHelper.hide();
                                self.el.css({
                                    visibility: "visible"
                                });
                                self.effects.der.resolveWith(self);
                            });
                        }
                    },
                    hide: function() {
                        if (this.animateTarget.length) {
                            var self = this;
                            this.animateHelper = this.animateHelper || $('<div class="' + this.cmpCls + '_animateHelper"></div>').appendTo("body");
                            var offset = this.animateTarget.offset();
                            var winOffset = this.el.offset();
                            this.el.hide();
                            this.animateHelper.css({
                                display: "block",
                                height: this.getHeight(),
                                width: this.getWidth(),
                                left: winOffset.left,
                                top: winOffset.top
                            }).animate({
                                width: "" + this.animateTarget.width(),
                                height: "" + this.animateTarget.height(),
                                left: "" + offset.left,
                                top: "" + offset.top
                            }, "normal", function() {
                                self.animateHelper.hide();
                                self.effects.der.resolveWith(self);
                            });

                        }
                    }
                }
            },
            show: function() {
                var self = this;
                if (!this.status.hidden) {
                    return false;
                }
                if (!this.effects.der || this.effects.der.state() !== "pending") {
                    this.effects.der = $.Deferred();
                    (this.effects[this.effect || "normal"] || this.effects.normal).show.apply(this, arguments);
                    this.effects.der.done(function() {
                        self.status.hidden = false;
                    });
                }
                this.el.focus();
                return this.effects.der.promise();
            },
            hide: function() {
                var self = this;
                if (this.status.hidden) {
                    return false;
                }
                if (!this.effects.der || this.effects.der.state() !== "pending") {
                    this.effects.der = $.Deferred();
                    (this.effects[this.effect || "normal"] || this.effects.normal).hide.apply(this, arguments);
                    this.effects.der.done(function() {
                        self.status.hidden = true;
                    });
                }
                return this.effects.der.promise();
            },
            destroy: function() {
                if (this.status) {
                    if (this.proxy) {
                        var self = this;
                        $.each(this.proxy, function(k) {
                            delete self.proxy[k];
                        });
                    }

                    if (this.container && this.container.destroy) {
                        this.container.destroy();
                    }
                    
                    if (this.parentCmp) {
                        //第二个参数true表示不再次调用组件的destroy
                        this.parentCmp.removeItem(this, true);
                    }
                    this.el.remove();
                    W.removeCmp(this.id);
                    //删除对象内的所有自身属性
                    var i;
                    for (i in this) {
                        if (this.hasOwnProperty(i)) {
                            delete this[i];
                        }
                    }
                }
            }
        }
    });
}) (jQuery, Weiboyi);