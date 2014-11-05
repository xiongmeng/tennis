(function ($, W, undefined) {
    var opts = {
        start: undefined,
        limit: undefined,
        total: undefined,
        page: undefined,
        totalPages: undefined,
        listeners: {
            onpagechanged: function () {
            }
        }
    };

    var methods = {
        initComponent: function () {
            this.el = $("<div class='weiboyiPagebar'></div>");
            this.buildBar();
        },
        destroyButtons: function() {
            if (this.buttons && this.buttons.length) {
                $.each(this.buttons, function(i, e) {
                    if (e && e.status) {
                        e.destroy();
                    }
                });
            }
        },
        destroy: function () {
            this.destroyButtons();
            W.Component.prototype.destroy.apply(this, arguments);
        },
        empty: function() {
            this.destroyButtons();
            this.el.empty();
        },
        getLimit: function() {
            return this.limit;
        },
        getTotal: function() {
            return this.total;
        },
        getStart: function() {
            return this.start;
        },
        page: function(page) {
            if (!this.status.disable) {
                if (page >= this.totalPages && this.totalPages >= 1) {
                    page = this.totalPages - 1;
                }
                else if (page < 0) {
                    page = 0;
                }
                this.start = page * this.limit;
                if (this.trigger("onpagechanged", [this.start, this.limit, page]) !== false) {
                    this.reload();
                }
            }
        },
        reload: function(info) {
            this.empty();
            if (info) {
                this.start = info.start === 0 ? 0 : (info.start || this.start);
                this.limit = info.limit === 0 ? 0 : (info.limit || this.limit);
                this.total = info.total === 0 ? 0 : (info.total || this.total);
            }
            this.buildBar();
        },
        /**
         * 组装pagebar
         * @return {[type]} [description]
         */
        buildBar: function() {
            //this.destroyPageInfo();
            this.buttons = [];
            var self = this, start = this.start * 1 || 0, limit = this.limit * 1 || 20, total = this.total * 1 || 0, totalPages, currentPage, pages = {}, sp = {};

            this.totalPages = totalPages = Math.ceil(total / limit);
            currentPage = Math.ceil((start + 1) / limit);

            pages[totalPages - 1] = totalPages - 1;
            pages[0] = 0;

            pages[currentPage - 1] = currentPage - 1;
            pages[currentPage] = currentPage;
            pages[currentPage + 1] = currentPage + 1;
            pages[currentPage - 2] = currentPage - 2;
            pages[currentPage - 3] = currentPage - 3;
            pages.length = totalPages;

            this.pageInfo = {
                start: start,
                limit: limit,
                total: total,
                currentPage: currentPage,
                totalPages: totalPages
            };
            var pageTags = [];

            //使用类数组处理, 简化逻辑判断
            $.each(W.util.asArray(pages), function(i, e) {
                if (e !== undefined) {
                    pageTags.push({
                        text: e + 1,
                        current: e + 1 === currentPage
                    });
                }
                else {
                    if (!sp[i > currentPage ? "before" : "after"]) {
                        sp[i > currentPage ? "before" : "after"] = true;
                        pageTags.push({
                            separate: true
                        });
                    }
                }
            });

            var prev = new W.Button({
                text: "上一页",
                type: "simple",
                cls: "btn_prev",
                disabledCls: "btn_simple_disabled",
                disabled: currentPage <= 1,
                renderTo: self.el,
                handler: function() {
                    self.page(currentPage - 2);
                }
            });

            $.each(pageTags, function(i, e) {
                var tag;
                if (e.separate) {
                    self.el.append("...");
                }
                else {
                    
                    if (!e.current) {
                        var cfg = {
                            renderTo: self.el,
                            text: e.text,
                            type: "simple",
                            handler: function() {
                                self.page(e.text - 1);
                            }
                        };
                        self.buttons.push(new W.Button(cfg));
                    }
                    else {
                        self.el.append("<span class='weiboyiPageBar_current'>" + e.text + "</span>");
                    }
                    
                }
                
            });
            
            var next = new W.Button({
                text: "下一页",
                type: "simple",
                cls: "btn_next",
                disabledCls: "btn_simple_disabled",
                disabled: currentPage >= totalPages,
                renderTo: self.el,
                handler: function() {
                    self.page(currentPage);
                }
            });

            this.el.append("&nbsp;&nbsp;共" + totalPages + "页&nbsp;&nbsp;到第<input type='text'/>页&nbsp;");
            
            var gotoBtn =  new W.Button({
                text: "前往",
                type: "normal",
                cls: "btn_goto button",
                disabled: currentPage > totalPages,
                renderTo: self.el,
                handler: function() {
                    var input = self.el.find("input");
                    var page = (input.val() * 1);

                    if (page > totalPages) {
                        page =  totalPages;
                    }
                    else if (page < 1 || isNaN(page)) {
                        page = 1;
                    }

                    self.page(Math.round(page) - 1);

                    // self.pageInfo.start = (Math.round(page) - 1) * self.pageInfo.limit;
                    // self.reload();
                }
            });
            this.buttons.push(prev);
            this.buttons.push(next);
            
            if (this.limit >= this.total) {
                this.hide();
            }
            else {
                this.show();
            }
        },
        disable: function() {
            this.status.disable = true;
        },
        enable: function() {
            this.status.disable = false;
        }
    };

    W.createComponent({
        xtype: "Pagebar",
        opts: opts,
        methods: methods,
        extend: W.Component,
        api: ["reload", "page", "getStart", "getLimit", "getTotal", "disable", "enable"]
    });

}) (jQuery, Weiboyi);