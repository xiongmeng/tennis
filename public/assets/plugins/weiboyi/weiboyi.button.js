(function($, W, undefined) {

    var opts = {
        type: "important",
        size: "small",
        btnCls: "",
        handler: $.noop,
        text: "",
        el: undefined,
        scope: undefined,
        renderTo: undefined,
        handlerArgs: undefined,
        status: {},
        processingText: undefined,
        timing: false
    };

    var methods = {
        initComponent: function(args) {
            if (args) {
                this.doExistBtn(args);
            }
            else {
                if (this.btnEl) {
                    this.doExistBtn(this.btnEl);
                }
                else {
                    this.createDom();
                }
                this.doHandler();
                this.el.appendTo(this.renderTo);
                if (this.disabled) {
                    this.disable();
                }
            }
        },
        doExistBtn: function(btn) {
            this.el = $(btn);
            this.text = this.config.text = this.el.text();
            if (this.el.length) {
                var btnCls = this.el.attr("class"), self = this;
                $.each(btnCls.split(" "), function(i, e) {
                    if (/^btn_[a-z]*_[a-z]*$/.test(e)) {
                        self.btnCls = e;
                        return false;
                    }
                });
                this.doBtnCls();
            }
        },
        doBtnCls: function() {
            if (this.btnCls) {
                var arr = this.btnCls.split("_");
                this.size = arr[1] || this.size;
                this.type = arr[2] || this.type;
            }
            else {
                this.btnCls = this.btnCls || "btn_" + this.size + "_" + this.type;
            }
            this.disabledCls = this.disabledCls || "btn_" + this.size + "_disabled";
        },
        active: function() {
            this.el.css({
                border: '1px blue dotted'
            });
        },
        createDom: function() {
            this.doBtnCls();

            this.el = W.util.ie6 ? $('<a href="###"></a>') : $('<a href="javascript:void(0)"></a>');
            this.el.attr({
                "class": this.btnCls
            }).addClass(this.cls);
            if (typeof this.text === "number") {
                this.text += "";
            }
            var span = $("<span class=\"btn_wrap\"></span>").text(this.text || "");
            this.el.append(span);
            
        },
        doHandler: function() {
            var self = this;
            self.el.click(function(evt) {
                if (!self.status.disabled) {
                    self.process();
                    evt.stopPropagation();
                }
                else {
                    evt.stopPropagation();
                    evt.preventDefault();
                }
            });
        },
        process: function(evt) {
            var self = this;
            if (!self.status.disabled) {
                
                var ret;
                
                if (self.handlerArgs instanceof Array) {
                    ret = self.handler.apply(self.scope || self, self.handlerArgs);
                }
                else {
                    ret = self.handler.call(self.scope || self, evt, self, self.scope);
                }

                if (W._isDeferred(ret)) {
                    self.block();
                    ret.always(function() {
                        if (!self.der || self.der.state() !== "pending") {
                            if (self.timing) {
                                self.block(self.timing, self.blockingText);
                            }
                            else {
                                self.done();
                            }
                        }
                    });
                }
                else if (self.timing) {
                    self.block(self.timing, self.blockingText);
                }
                
            }
        },
        block: function(seconds, text) {
            this.disable();
            if ($.isNumeric(seconds)) {
                return this.time(seconds, text);
            }
            else {
                if (this.processingText) {
                    this.setText(this.processingText);
                }
            }
        },
        done: function() {
            this.enable();
            this.setText(this.config.text);
            if (this.der) {
                this.der.resolveWith(this);
            }
        },
        disable: function() {
            this.el.removeClass(this.btnCls).addClass(this.disabledCls);
            this.status.disabled = true;
        },
        enable: function() {
            this.el.addClass(this.btnCls).removeClass(this.disabledCls);
            this.status.disabled = false;
        },
        /*destroy: function() {
            W.Component.prototype.destroy.apply(this, arguments);
        },
        show: function() {
            this.el.show();
        },
        hide: function() {
            this.el.hide();
        },*/
        setHanlder: function(handler) {
            this.handler = handler;
            this.doHandler();
        },
        setText: function(text) {
            this.text = text;
            this.el.find(".btn_wrap").text(this.text || "");
        },
        /**
         * 定时
         * @param  {[type]} seconds [disaebld的时间（秒）]
         * @param  {[type]} text    [disabled显示的文本]
         * @return {[type]}         [description]
         */
        time: function(seconds, text) {
            var self = this;
            if (self.der) {
                self.der.reject();
            }
            self.der = $.Deferred();
            text = text || "";

            if (self.interval) {
                clearInterval(self.interval);
            }
            self.interval = setInterval(function() {
                seconds--;
                if (seconds <= 0) {
                    clearInterval(self.interval);
                    self.der.resolveWith(self);
                }
                else {
                    self.der.notify(seconds);
                }

            }, 1000);

            self.disable();

            function progress(remind) {
                var replacement = [seconds];

                var t = text.replace(/\{(\w*)\}/, function(para, key) {
                    return replacement[key] || "";
                });
                self.setText(t);
            }
            progress();
            self.der.progress(progress).done(function() {
                self.done();
            });

            return self.der.promise();
        }
    };

    W.createComponent({
        xtype: "Button",
        opts: opts,
        methods: methods,
        extend: W.Component,
        api: ["setText", "block", "setHanlder", "enable", "disable", "done", "process", "active"]
    });

}) (jQuery, Weiboyi);