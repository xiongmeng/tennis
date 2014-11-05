(function($, W, undefined) {

    /*jshint eqeqeq:false*/

    var opts = {
        activeTab: 0,
        // [top, bottom, left, right, none]
        tab: "top",
        tabCls: "",
        contentHeight: "auto",
        height: "inherit",
        cmpCls: "weiboyiTabCtnr",
        layout: {},
        lazyTab: true
    };

    var methods = {
        renderItems: function() {
            var self = this;
            self.tabBodyEl = $('<div class="' + self.cmpCls + '_tabbody"></div>');
            if (self.tab === "top") {
                self.tabHeadEl = $('<div class="' + self.cmpCls + '_tabhead"></div>');
                self.tabHeadList = $('<ul></ul>');
                if (self.tabCls) {
                    self.tabHeadEl.addClass(self.tabCls);
                }
                self.el.append(self.tabHeadEl.append(self.tabHeadList)).append(self.tabBodyEl);
            }
            else if (self.tab === "bottom") {
                //TODO
            }
            else if (self.tab === "left") {
                //TODO
            }
            else if (self.tab === "right") {
                //TODO
            }

            if (self.config.items) {
                self.items = [];
                if (self.config.activeTab >= self.config.items.length) {
                    self.config.activeTab = 0;
                }
                $.each(self.config.items, function(i, e) {
                    var item = self.renderItem(e, self.tabBodyEl);
                    self.items.push(item);
                    if (self.config.activeTab == i) {
                        self.setActiveTab(i);
                    }
                    else {
                        item.hide();
                    }
                });
            }
            
        },
        renderItem: function(item) {
            var self = this;
            var tabText = $('<span></span>').text(item.tabTitle || item.title);
            var tab = $('<a href="javascript:void(0)"></a>').append(tabText);

            if (item.icon) {
                var icon = $('<span class="' + this.cmpCls + '_icon"></span>');
                icon.addClass(item.icon);
                tabText.prepend(icon);
            }

            this.tabHeadList.append($("<li></li>").append(tab));
            item.lazyLoad = this.lazyTab;
            var cmp = W.Container.prototype.renderItem.apply(this, arguments);
            if (cmp) {
                tab.click(function(e) {
                    self.setActiveTab(cmp);
                    e.preventDefault();
                    e.stopPropagation();
                });
                cmp.tabTitleEl = tab;
                return cmp;
            }
        },
        setActiveTab: function(mix) {
            var item, index;
            if (typeof mix === "number") {
                if (mix >= 0 && mix < this.items.length) {
                    item = this.items[mix];
                }
            }
            else if (typeof mix === "string") {
                item = W.getCmp(mix);
            }
            else {
                item = mix;
            }
            index = $.inArray(item, this.items);
            if (item &&  index > -1) {
                if (this.activeTabItem !== item) {
                    if (this.activeTabItem) {
                        this.activeTabItem.hide();
                        this.activeTabItem.tabTitleEl.removeClass("activeTab");
                    }
                    item.tabTitleEl.addClass("activeTab");
                    this.activeTabItem = item;
                    if (!item.status.loaded) {
                        item.load();
                        this.trigger("ontabinited", [item, index]);
                    }
                    item.show();
                }
            }
        }
    };

    W.createComponent({
        xtype: "TabCtnr",
        opts: opts,
        methods: methods,
        extend: W.Container,
        api: ["setActiveTab", "hide", "show", "status", "render", "doLoader", "trigger", "removeListener", "addListener", "load", "destroy", "addItems", "removeItem", "renderItem", "renderItems", "setText", "setContent", "setHtml", "reload"]
    });
}) (jQuery, Weiboyi);