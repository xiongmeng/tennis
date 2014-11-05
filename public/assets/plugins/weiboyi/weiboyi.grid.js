(function($, W, document, doT, undefined) {
    
    /*jshint eqeqeq:false */
    var opts = {
        renderTo: undefined,
        url: undefined,
        dataFilter: undefined,
        columns: [],
        listeners: {
            onrowchecked: $.noop
        },
        filters: {},
        orders: {},
        params: {},
        pageInfo: {
            start: 0,
            limit: 10
        },
        status: {},
        events: {},
        nodataMsg: "",
        animating: true,
        selectedRow: [],
        autoLoad: true,
        cmpCls: "weiboyiGrid",
        messages: {
            dataError: "请求列表数据出现错误, 请重试操作！",
            noData: "暂时没有数据哦！",
            noQueryResult: "没有查询结果哦！"
        }
    };

    var methods = {
        initComponent: function () {
            this.el.append('<div class="weiboyiGrid_loadingMask"></div>');
            this.tableDom = document.createElement("TABLE");
            this.tableDom.className = "weiboyiGrid_table";
            this.buildHead();
            this.el.append(this.tableDom);
            this.initMessage();
            this.doEditable();

            var self = this, start = this.pageInfo.start || 0, limit = this.pageInfo.limit || 20, total = 0;
            if (!this.pagebar) {
                this.pagebar = new W.Pagebar({
                    start: start,
                    limit: limit,
                    total: total,
                    renderTo: this.el,
                    listeners: {
                        onpagechanged: function(start, limit) {
                            self.pageInfo.start = start;
                            self.pageInfo.limit = limit;
                            self.reload();
                        }
                    }
                });
                this.pagebar.hide();
            }
        },
        doInitStatus: function() {
            if (this.autoLoad) {
                this.load();
            }
        },
        /**
         * 用于显示没有数据|数据加载错误等的提示信息
         * @param  {[type]} content [description]
         * @return {[type]}         [description]
         */
        showMessage: function(content) {
            this.el.addClass("weiboyiGridMsg");
            this.msgEl.html(content);
        },
        hideMessage: function() {
            this.el.removeClass("weiboyiGridMsg");
        },
        /**
         * 初始化没有数据的信息提示
         * @return {[type]} [description]
         */
        initMessage: function() {
            this.msgEl = this.msgEl || $("<div class='weiboyiGridMsg_content'></div>");
            //nodataMsgEl.html(this.nodataMsg);
            this.el.append(this.msgEl);
        },
        search: function(filters, order) {
            this.pageInfo.start = 0;
            return this.reload(filters, order);
        },
        resetFilter: function() {
            delete this.filters;
            return this.reload();
        },
        setParams: function(params) {
            this.params = params;
        },
        getParams: function() {
            return this.params;
        },
        resetParams: function() {
            this.setParams({});
            return this.reload();
        },
        resetSort: function() {
            delete this.order;
            return this.reload();
        },
        sort: function(order) {
            this.pageInfo.start = 0;
            return this.reload(null, order);
        },
        isValidData: function(data) {
            return data && data.total && data.rows && data.rows.length;
        },
        refresh: function() {
            var self = this;
            self.removeBody();

            if (self.headcheckbox) {
                self.headcheckbox.prop("checked", false);
            }

            if (!self.isValidData(self.data)) {
                self.status.empty = true;
                if (self.status.hasFilters) {
                    self.showMessage(self.messages.noQueryResult || opts.messages.noQueryResult);
                }
                else {
                    self.showMessage(self.messages.noData || opts.messages.noData);
                }  
            }
            else {
                self.buildBody();
                self.hideMessage();
                self.status.empty = false;
            }
            self.doPageInfo(self.data);
        },
        getSelectedRows: function() {
            if (this.data && this.data.rows instanceof Array) {
                var values = [];
                this.el.find("td.weiboyiGrid_table_checkable input:checked").each(function(i, e) {
                    values.push(e.value);
                });
                return $.grep(this.data.rows, function(n) {
                    return $.inArray(n.id + '', values) >= 0;
                });
            }
            return [];
        },
        reload: function(filters, order, pageInfo) {
            var self = this;

            if (filters) {
                if (typeof filters === "function") {
                    this.filters = filters();
                }
                else {
                    this.filters = filters;
                }
            }

            if (order) {
                if (typeof order === "function") {
                    this.order = order();
                }
                else {
                    this.order = order;
                }
            }

            if (this.filters instanceof Array) {
                var arr = this.filters;
                this.filters = {};
                $.each(arr, function(i, e) {
                    self.filters[e.name] = e.value;
                });
            }
            else if (typeof this.filters === "string") {
                this.filters = W.util.deserializeQueryString(this.filters);
            }

            //trim条件
            $.each(self.filters || {}, function(k, v) {
                v = $.trim(v);
                if (!v && v !== 0) {
                    delete self.filters[k];
                }
                else {
                    self.filters[k] = v;
                }
            });

            self.status.hasFilters = !$.isEmptyObject(self.filters);

            if (pageInfo) {
                this.pageInfo = pageInfo;
            }

            return this.load(this.filters, this.order);
        },
        getTotal: function() {
            return this.data ? (this.data.total || 0) : 0;
        },
        getStart: function() {
            return this.data ? (this.data.start || 0) : 0;
        },
        getLimit: function() {
            return this.data ? (this.data.limit || 0) : 0;
        },
        /**
         * 加载数据,初始化tbody/pagebar
         * @return {[type]} [description]
         */
        load: function (filters, order) {
            var self = this;
            if (this.config.data) {
                self.refresh();
            }
            else {
                this.el.addClass("weiboyiGrid_loading");
                return this.loadData(filters, order).done(function(data) {
                    self.el.removeClass("weiboyiGrid_loading");
                    self.refresh();
                }).fail(function() {
                    self.removeBody();
                    self.el.removeClass("weiboyiGrid_loading");
                    self.showMessage(this.messages.dataError || opts.messages.dataError);
                    if (self.pagebar) {
                        self.pagebar.hide();
                    }
                });
            }
        },
        removeBody: function() {
            if (this.tbodyDom && this.tbodyDom.nodeType && this.tbodyDom.parentNode) {
                this.tbodyDom.parentNode.removeChild(this.tbodyDom);
            }
        },
        /**
         * 加载数据
         * @param  回调函数
         * @return {[type]}            [description]
         */
        loadData: function(filters, order) {
            var self = this;
            var columns = [];
            $.each(this.columns || [], function(i, e) {
                if (e.dataIndex === 0 || !!e.dataIndex) {
                    columns.push(e.dataIndex);
                }
            });

            var der = $.Deferred();
            if (this.url) {

                if (this.request && this.request.state() === 'pending') {
                    this.request.abort();
                }

                var data = {};

                $.extend(data, this.params);
                $.extend(data, {
                    start: this.pageInfo.start,
                    limit: this.pageInfo.limit,
                    columns: columns.join(","),
                    f: this.filters,
                    o: this.order
                });

                this.request = $.ajax({
                    url: this.url,
                    type: "get",
                    data: data,
                    dataType: "json"
                });

                this.request.done(function(json) {
                    function defaultDataFilter(d) {
                        if (d && d.code === 1000) {
                            return d.data;
                        }
                        else {
                            return null;
                        }
                    }

                    var data = (this.dataFilter || defaultDataFilter) (json);

                    if (!!data) {
                        self.data = data;
                        der.resolveWith(self, [data]);
                    }
                    else {
                        der.rejectWith(self, []);
                    }
                });

                this.request.fail(function(ajax, state) {
                    // readyState === 0
                    if (ajax.readyState !== 0) {
                        der.rejectWith(self);
                    }
                    else {
                        //der.resolveWith(self, []);
                    }
                });
                return der.promise();
            } else {
                if (self.data) {
                    der.resolveWith(self, [self.data]);
                }
                return der.promise();
            }
        },
        renderCell: function (cell, text, row, col, self) {
            if (col.formatter) {
                var cnt = col.formatter.call(cell, text, row, col, self);
                if (!cnt && cnt !== 0) {
                    cnt = "&nbsp;";
                }
                cell.html(cnt);
            }
            else if ((col.tmpl || col.tmplId) && doT) {
                if (!col.compiles) {
                    col.tmpl = col.tmpl || document.getElementById(col.tmplId).innerHTML;
                    col.compiles = doT.template(col.tmpl);
                }

                var data = row;

                if (col.tmplPreprocessor) {
                    data = col.tmplPreprocessor(row);
                }

                cell.html(col.compiles(data));
            }
            else {
                if (!text && text !== 0) {
                    text = " ";
                }
                cell.text(text);
                // td.innerText = text;
            }
        },
        renderRow: function() {

        },
        /**
         * 组装tbody
         * @return {[type]} [description]
         */
        buildBody: function () {
            var self = this;
            this.tbodyDom = document.createElement("TBODY");
            this.tableDom.appendChild(this.tbodyDom);

            
            $.each(this.data.rows || [], function(i, row) {
                self.insertRowBefore(row, i);
            });
        },
        doEditable: function() {
            var self = this;

            function save() {

                var el = $(this),
                    td = el.parent(),
                    value = $.trim(el.val()),
                    row = self.getRow(el.parent().parent().attr("data-rowid")),
                    col = self.getCol(el.parent().attr("data-col"));

                if (!td.hasClass("editing")) {
                    return;
                }

                function resolve() {
                    row.cells[col.dataIndex || ""] = value;
                    self.renderCell(td.find(".weiboyiGrid_cellContent"), row.cells[col.dataIndex || ""], row, col, self);
                }

                function reject() {
                    self.renderCell(td.find(".weiboyiGrid_cellContent"), row.cells[col.dataIndex || ""], row, col, self);
                }

                td.removeClass("editing");
                var ret;
                if (value === row.cells[col.dataIndex || ""]) {
                    reject();
                }
                else {
                    ret = col.editHandler.call(self, el.val(), el, row, col);
                }

                if (W._isDeferred(ret)) {
                    td.addClass("weiboyiGrid_cellPending");
                    ret.fail(reject).done(resolve).always(function() {
                        td.removeClass("weiboyiGrid_cellPending");
                    });
                }
                else if (ret === false) {
                    reject();
                }
                else {
                    resolve();
                }
            }

            self.el.on("click", "tr .weiboyiGrid_editableCell", function(e) {
                if ((e.target || e.srcElement).tagName.toLowerCase() === "textarea") {
                    //点击的不是td
                    return;
                }
                var el = $(this);
                if (el.hasClass("weiboyiGrid_cellPending")) {
                    return;
                }
                var row = self.getRow(el.parent().attr("data-rowid"));
                if (row) {
                    var editor = el.find(".weiboyiGrid_cellEditor");
                    editor.val(row.cells[el.attr("data-col")]).css({width: el.outerWidth() - 10});
                    el.addClass("editing");
                    editor.focus();
                }
            });

            self.el.on("blur", ".weiboyiGrid_cellEditor", save);
            self.el.on("keypress", ".weiboyiGrid_cellEditor", function(e) {
                if (e.keyCode === 13) {
                    save.apply(this, arguments);
                }
            });
        },
        getCol: function(colId) {
            return $.grep(this.columns, function(n, i) {
                return n.id == colId;
            })[0];
        },
        getRow: function(rowId) {
            var row = $.grep(this.data.rows, function(n) {
                return n.id == rowId;
            })[0];
            if (!row || !row.el || !row.el.length) {
                var tr = this.el.find("tr[data-rowid='" + rowId + "']");
                if (tr.length) {
                    row = {
                        el: tr
                    };
                }
            }
            return row;
        },
        /**
         * 页面上删除一行
         * @param  {[type]} rowId [description]
         * @return {[type]}       [description]
         */
        deleteRow: function(rowId) {
            var row = this.getRow(rowId), ret;
            if (row && row.el) {
                if (this.animating) {
                    ret = $.Deferred();
                    row.el.animate({
                        height: 0
                    }, "fast", function() {
                        row.el.remove();
                        ret.resolve();
                    }).empty();
                }
                else {
                    row.el.remove();
                }
            }
            return ret;
        },
        disableRow: function(rowId) {
            var row = this.getRow(rowId);
            if (row) {
                row.el.find('td.weiboyiGrid_table_checkable input').prop('checked', false);
                row.el.addClass('disabledLine');
            }
        },
        enableRow: function(rowId) {
            var row = this.getRow(rowId);
            if (row) {
                row.el.removeClass('disabledLine');
            }
        },
        /**
         * 在页面上添加一行
         * @param  {[type]} index [description]
         * @return {[type]}       [description]
         */
        insertRowBefore: function(row, i) {
            var self = this;
            var tr = document.createElement("TR");
            row.id = row.id || W.getAutoID("weiboyiGrid");
            row.el = $(tr);
            self.doCheckable(row, false);
            if (row.disabled) {
                tr.className = i % 2 ? "disabledLine even" : "disabledLine odd";
            }
            else if (row.highlight) {
                tr.className = i % 2 ? "highlightLine even" : "highlightLine odd";
            }
            else {
                tr.className = i % 2 ? "even" : "odd";
            }
            row.el.attr("data-rowid", row.id);


            $.each(self.columns || [], function(i, col) {

                var td = $("<td></td>");

                if (!col.type || col.type.toLowerCase() !== "hidden") {
                    td.appendTo(tr);
                }

                //为了兼容ie6/7样式使用空格占位
                var text = row.cells[col.dataIndex || ""];
                if (!text && text !== 0) {
                    text = " ";
                }

                if (col.cls) {
                    td.addClass(col.cls);
                }

                if (col.id) {
                    td.attr("data-col", col.id);
                }

                td.attr("align", {
                    "left": "left",
                    "right": "right",
                    "center": "center"
                } [col.align] || "left");

                if (col.width) {
                    td.css({width: col.width});
                    //td.attr("width", col.width);
                }

                if (col.cellAttr) {
                    td.attr(col.cellAttr);
                }

                var editable;

                if (typeof col.editable === "function") {
                    editable = col.editable.call(td, text, row, col, self);
                }
                else {
                    editable = col.editable;
                }

                if (col.opts && col.opts instanceof Array) {
                    td.addClass("weiboyiGrid_optsCell");
                    row.opts = {};
                    $.each(col.opts, function(i, e) {
                        var disabled = false;
                        if (typeof e.enable === 'function') {
                            disabled = !e.enable.call(self, col.opts, row, col, self);
                        }

                        var btn = new W.Button({
                            renderTo: td,
                            btnCls: e.cls,
                            cls: self.cmpCls + "_btn",
                            type: e.type,
                            //scope: self,
                            size: e.size,
                            text: e.text,
                            handler: e.handler,
                            disabled: disabled || e.disabled,
                            handlerArgs: [td, row, col, self]
                        });
                        row.opts[e.text || W.getAutoID("weiboyiBtn")] = btn;
                    });
                }
                else {
                    if (editable) {
                        td.addClass("weiboyiGrid_editableCell");
                        var cell = $("<div class='weiboyiGrid_cellContent'></div>");
                        cell.appendTo(td);
                        self.renderCell(cell, text, row, col, self);
                        var editEl = $('<textarea class="weiboyiGrid_cellEditor"></textarea>').css({
                            "text-align": col.align || "left"
                        }).appendTo(td);

                        if (col.width) {
                            editEl.css({
                                width: col.width - 10
                            });
                        }
                        td.attr("title", "点击编辑");
                    }
                    else {
                        self.renderCell(td, text, row, col, self);
                    }
                }

            });

            //防止ie6/7参数无效的错误
            if (self.tbodyDom.children[i]) {
                self.tbodyDom.insertBefore(tr, self.tbodyDom.children[i]);
            }
            else {
                self.tbodyDom.appendChild(tr);
            }
            
        },
        findCell: function(row, id) {
            if (row && row.el && id) {
                return row.el.find("td[data-col=" + id + "]");
            }
        },
        getDisabledRows: function() {
            return this.getRows("disabled");
        },
        getHighlightRows: function() {
            return this.getRows("highlight");
        },
        getRows: function(type) {
            if (!this.isValidData(this.data)) {
                return [];
            }
            if (typeof type === "string") {
                var rows = [];
                $.each(this.data.rows, function(i, row) {
                    if (row[type]) {
                        rows.push(row);
                    }
                });
                return rows;
            }
            else {
                return this.data.rows;
            }
        },
        getData: function() {
            return this.data;
        },
        /**
         * 组装thead
         * @return {[type]} [description]
         */
        buildHead: function () {
            var self = this;
            this.theadDom = this.tableDom.createTHead();
            this.theadRow = this.theadDom.insertRow(0);

            this.doCheckable({el: $(this.theadRow)}, true);

            $.each(this.columns || [], function(i, col) {
                if (col.type && col.type.toLowerCase() === "hidden") {
                    return;
                }
                else if (col.type && col.type.toLowerCase() === "opts") {
                    col.text = col.text || "操作";
                }

                //表头配置容错处理
                col.text = col.text || col.id || col.dataIndex || "";
                col.id = col.id || col.dataIndex;
                //col.dataIndex = col.dataIndex || col.text;

                var th = $("<th></th>");
                th.prop("nowrap", true);
                if (col.cls) {
                    th.addClass(col.cls);
                }
                if (col.width) {
                    //th.width = col.width;
                    th.css({
                        width: col.width
                    });
                }

                var $content = $('<div class="weiboyiGrid_thcontent"></div>');
                $content.append(col.text);
                th.append($content);

                if (col.titleTips) {
                    var $tips = $('<a href="javascript:;" class="weiboyiGrid_thtips"></a>');
                    $content.append($tips);

                    if (W.Tips) {
                        var cfg = {};
                        if (typeof col.titleTips === 'string') {
                            $.extend(cfg, {
                                html: col.titleTips,
                                title: col.text,
                                autoHide: false
                            });
                            $tips.attr('title', '点击查看详情');
                        } else if (typeof col.titleTips === 'object') {
                            $.extend(cfg, col.titleTips);
                        }
                        cfg.target = $tips;
                        new W.Tips(cfg);
                    }
                }

                th.appendTo(self.theadRow);
            });
        },
        /**
         * 组装pagebar
         * @return {[type]} [description]
         */
        doPageInfo: function() {

            var self = this, start = this.data.start || 0, limit = this.data.limit || 20, total = this.data.total || 0;
            this.pagebar.reload({
                start: start,
                limit: limit,
                total: total
            });

            if (this.status.empty) {
                this.pagebar.hide();
            }
        },
        /**
         * 列单选/多选
         * @param  {[type]}  row     [description]
         * @param  {Boolean} isHead  [description]
         * @param  {[type]}  value   [description]
         * @param  {[type]}  checked [description]
         * @return {[type]}          [description]
         */
        doCheckable: function (row, isHead) {
            var checked = row.checked,
                td = document.createElement(isHead ? "TH" : "TD"),
                self = this;
            if (this.checkable === "single") {
                row.el.append(td);
                if (!isHead) {
                    var radio = $('<input type="radio" name="' + self.id + '_radio">');

                    radio.val(row.id || "");

                    if (checked) {
                        radio.prop("checked", true);
                    }

                    $(td).append(radio).addClass("weiboyiGrid_table_checkable");
                }
                else {
                    td.innerText = "选择";
                }
            }
            else if (this.checkable) {
                var checkbox = $(document.createElement("input"));
                checkbox.attr({
                    name: this.id + "_checkbox",
                    type: "checkbox"
                }).val(row.id || "");

                if (checked) {
                    checkbox.prop("checked", true);
                }
                $(td).append(checkbox).addClass("weiboyiGrid_table_checkable");
                row.el.append(td);

                if (isHead) {
                    checkbox.attr('title', '全选');
                    this.headcheckbox = checkbox.on("change", function() {
                        $(self.tbodyDom).find("tr:not(.disabledLine) td.weiboyiGrid_table_checkable input").prop("checked", checkbox.prop("checked"));
                    });
                }
                else {
                    checkbox.on("change", function() {
                        if ($(this).prop('checked')) {
                            self.trigger('onrowchecked', [row]);
                        }
                    });
                }
            }
        },
        setUrl: function(url) {
            this.url = url;
        },
        getUrl: function() {
            return this.url;
        },
        getPagebar: function() {
            return this.pagebar;
        }
    };

    W.createComponent({
        xtype: "Grid",
        opts: opts,
        methods: methods,
        extend: W.Component,
        api: [
            'disableRow',
            'enableRow',
            'setParams',
            'getParams',
            'resetParams',
            "status",
            "setUrl",
            "getUrl",
            "getPagebar",
            "load",
            "getRow",
            "insertRowBefore",
            "deleteRow",
            "search",
            "sort",
            "getRows",
            "getDisabledRows",
            "getSelectedRows",
            "getHighlightRows",
            "refresh",
            "resetFilter",
            "resetOrder",
            "getRowById",
            "getTotal",
            "getStart",
            "getLimit",
            "reload",
            "getData"
        ]
    });

}) (jQuery, Weiboyi, document, doT);