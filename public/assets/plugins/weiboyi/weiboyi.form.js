(function($, W, undefined) {

    /*jshint eqeqeq:false*/

    W.form = {
        /**
         * 输入框的placeholder (暂不完美)
         * @param  {[type]} el          [description]
         * @param  {[type]} placeholder [description]
         * @return {[type]}             [description]
         */
        holdPlace: function(el, placeholder) {

            function simulate(input, text) {
                var holderCtnr = $("<span class='weiboyiPlaceholder'></span>"),
                    holder = $("<span class='weiboyiPlaceholder_content'></span>"),
                    height = input.height(),
                    width = input.width();

                if (input.val()) {
                    holder.css({
                        display: "none"
                    });
                }
                input.wrap(holderCtnr);
                input.after(holder);//.appendTo(holderCtnr);
                
                holder.text(text);

                var offset = input.position();

                var css = {
                    width: width || "auto",
                    height: height || "auto",
                    left: offset.left,
                    top: offset.top,
                    margin: input.css('margin'),
                    padding: input.css('padding')
                };

                if (input.get(0).tagName.toLowerCase() !== "textarea") {
                    css.lineHeight = height + "px";
                }
                else {
                    css.lineHeight = "28px";
                }

                holder.css(css).mousedown(function() {
                    holder.hide();
                    setTimeout(function() {
                        input.focus();
                    }, 0);
                });

                input.blur(function() {
                    if (this.value === "") {
                        holder.show();
                    }
                }).focus(function() {
                    holder.hide();
                });

                input.attr('data-placeholder', text);
            }


            var support = W.support.placeholder;
            el  = $(el);
            if (!el.length) {
                return;
            }

            if (support) {
                el.attr("placeholder", placeholder);
            } else {
                el.each(function(i, e) {
                    e = $(e);
                    if (e.attr("data-placeholder")) {
                        var holder = e.next(".weiboyiPlaceholder_content");
                        if (holder.length) {
                            holder.text(placeholder);
                        }
                        else {
                            simulate(e, placeholder);
                        }
                    }
                    else {
                        simulate(e, placeholder);
                    }
                });
            }
        },
        /**
         * 美化表单元素, 目前仅支持select
         * @param  elements 表单元素
         */
        prettify: function(elements) {
            elements = $(elements);

            function PrettifySelect(select) {

                if (select.hasClass("prettifyElement")) {
                    return {init: $.noop};
                }

                var self = this;

                self.build = function () {
                    var options,
                    selected,
                    _options = '',
                    output;
                    options = select.addClass("prettifyElement").find('option');
                    selected = options.filter(':selected');
                    options.each(function () {
                        _options += '<li><a href="javascript:void(0)">' + $(this).text() + '</a></li>';
                    });
                    output =
                        '<ul class="prettifySelect">' +
                        '<li class="prettifySelect_wrap">' +
                        '<a href="javascript:void(0)" class="prettifySelect_title">' + selected.text() + '</a>' +
                        '<ul>' + _options + '</ul>' +
                        '</li>' +
                        '</ul>';
                    return output;
                };

                self.el = $(self.build()); // Wrap in jquery object
                self.title = self.el.find('.prettifySelect_title');
                self.menu = self.el.find('ul');
                self.items = self.menu.find('a');
                // Events
                self.events = {
                    open : function (e) {
                        self.el.addClass('open');
                        self.menu.show();
                        e.preventDefault();
                        e.stopPropagation();
                    },
                    close : function (e) {
                        self.el.removeClass('open');
                        self.menu.scrollTop(0);
                        self.menu.hide();
                        e.preventDefault();
                        e.stopPropagation();
                    },
                    change : function (e) {
                        var idx = $(this).parent().index();
                        self.title.text($(this).text());
                        select.find('option').eq(idx).prop('selected', true);
                        select.trigger('change');
                        self.events.close(e);
                        e.preventDefault();
                        e.stopPropagation();
                    }
                };

                // Initializate
                self.init = function () {
                    // Calculate width & height and insert prettifySelect
                    var idealselect = self.el.insertAfter(select),
                        items = idealselect.find('ul a'),
                        menu = idealselect.find('ul'),
                        wrap = idealselect.find(".prettifySelect_wrap"),
                        menuWidth = menu.width() + 11;

                    function setWidth() {
                        var css;
                        if (menuWidth < 20) {
                            css = {
                                "min-width": 80
                            };

                            if (W.util.ie6) {
                                css.width = 80;
                            }
                        }
                        else {
                            css = {
                                width: menuWidth
                            };
                        }
                        menu.css(css);
                        //idealselect.css(css);
                        wrap.css(css);
                    }

                    if (items.length > 10) {
                        setWidth();
                        menu.height(items.outerHeight() * 10);
                    } else {
                        setWidth();
                        menu.css('overflow-y', 'hidden');
                    }

                    self.menu.hide();

                    // Bind events
                    self.el.find('a').click(function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                    });
                    self.el.on('mouseleave', self.events.close);
                    self.title.on('click', self.events.open);
                    self.menu.on('mouseleave', self.events.close);
                    self.items.on('click', self.events.change);

                };
            }

            elements.each(function () {
                if (this.tagName === "SELECT") {
                    var prettifySelect = new PrettifySelect($(this));
                    prettifySelect.init();
                }
            });
        },
        init: function(form, data) {
            form = $(form);
            if (form.length) {
                form.find("input, select, textarea").each(function(i, el) {
                    var value = data[this.name || this.id];

                    if (typeof value !== "number" && !value) {
                        return;
                    }

                    switch ((this.type || "text").toLowerCase()) {
                        case "text":
                        case "password":
                        case "hidden":
                        case "button":
                        case "reset":
                        case "textarea":
                        case "select-one":
                        case "submit": {
                            if(typeof value === "string") {
                                $(this).val(value.toUpperCase() === "NULL" ? "" : value);
                            }
                            else {
                                $(this).val(value + "");
                            }
                            
                        } break;
                        case "checkbox":
                        case "radio": {
                            $(this).prop("checked", false);
                            if(value.constructor == Array) { //checkbox multiple value is Array
                                for(var elem in value) {
                                    if(value[elem] == $(this).val()) {
                                        $(this).prop("checked", true);
                                    }
                                }
                            } else { //radio or checkbox is a string single value
                                if(value == $(this).val()) {
                                    $(this).prop("checked", true);
                                }
                            }
                            break;
                        }
                        defaults: {

                        }
                    }
                });
            }
        }
    };
    var opts = {
        status: {},
        //附加到表单的用户数据 [Object|function]
        userDate: undefined,
        events: {}
    };

    W.createComponent({
        xtype: "Form",
        opts: opts,
        methods: {
            initialize: function() {
                var self = this;
                this.form = $(this.form);
                //如果没有引用jquery.validator插件则退出
                if (this.initValidator() !== false) {
                    this.validator = this.form.validate(this.validate);
                    this.status.validate = true;
                }

                this.init();

                this.type = this.type || this.form.attr("type");
                this.action = this.action || this.form.attr("action");
                if (this.submitBtn) {
                    this.submitBtn = new W.Button({
                        btnEl: this.submitBtn,
                        handler: function() {
                            return self.ajaxSubmit();
                        }
                    });
                }
                this.doListeners();
                this.addListener("beforeajaxSubmit", function() {
                    if (this.status.validate) {
                        return this.valid();
                    }
                });
                
            },
            init: function() {
                if (this.initData) {
                    W.form.init(this.form, this.initData);
                }
            },
            reset: function() {
                $.each(this.form || [], function() {
                    if (typeof this.reset == 'function' || (typeof this.reset == 'object' && !this.reset.nodeType)) {
                        this.reset();
                    }
                });

                if (this.status.validate && this.validator) {
                    this.validator.resetForm();
                    this.form.find(".validateIconError, .validateIconSuccess").removeClass().addClass("validateIcon");
                }

            },
            valid: function(elements) {
                if (elements) {
                    var self = this, result = true;
                    $.each(elements.split(","), function(i, e) {
                        var el = document.getElementById($.trim(e));
                        if (el) {
                            result = result && self.validator.element(el);
                        }
                    });
                    return result;
                }
                else {
                    return this.form.valid();
                }
            },
            showErrors: function(errors) {
                this.validator.showErrors(errors);
            },
            initValidator: function() {
                if (!$.validator) {
                    return false;
                }
                else if ($.validator.customSetting) {
                    return true;
                }

                var self = this;

                $.validator.addMethod("customRegExp", function(value, element, param) {
                    if (param instanceof RegExp) {
                        return param.test(value);
                    }
                }, "validate error");

                $.validator.addMethod("phone", function(value, element, param) {
                    if (value) {
                        return W.util.isPhoneNo(value);
                    }
                }, "请输入正确的手机号");

                $.validator.addMethod("mincharlength", function(value, element, param) {
                    if (value) {
                        value = value.replace(/[\u4E00-\u9FA5]|[^\x00-\xff]/ig, "cc");
                        return value.length >= param * 1;
                    }
                    return false;
                }, "请至少输入{0}个字符");

                $.validator.addMethod("maxcharlength", function(value, element, param) {
                    if (value) {
                        value = value.replace(/[\u4E00-\u9FA5]|[^\x00-\xff]/ig, "cc");
                        return value.length <= param * 1;
                    }
                    return true;
                }, "最多只能输入{0}个字符");

                $.validator.addMethod("QQ", function(value, element, param) {
                    if (value) {
                        return (/^[1-9]{1}[0-9]{4,19}$/).test(value) || W.util.isEmail(value);
                    }
                }, "请正确填写QQ");

                //常用的名称规则
                //匹配中文|字母|数字|-|_
                $.validator.addMethod("exspecialchar_name", function(value, element, param) {
                    if (value) {
                        return (/^[a-zA-Z0-9\-_\u4e00-\u9fa5]*$/).test(value);
                    }
                    return true;
                }, "不能包含特殊字符");

                $.validator.addMethod("tel", function(value, element, param) {
                    if (value) {
                        return (/^\d{2,4}[\-\s]\d{7,8}([\-\s]\d{3,6}?)?$/).test(value);
                    }
                    return true;
                }, "格式为xxx-xxxxxxxx或xxx-xxxxxxxx-xxxx");

                var onfocusin = $.validator.defaults.onfocusin;

                var labels = {
                    infoElements: {},
                    iconElements: {},
                    getInfoLabel: function(id) {
                        if (id) {
                            if (this.infoElements[id]) {
                                return this.infoElements[id];
                            }
                            var info = $("label[for=" + id + "].validateInfo");
                            if (!info.length) {
                                info = $('<label for=' + id + ' class="validateInfo"></label>');
                                $("#" + id).after(info);
                            }
                            return this.infoElements[id] = info;
                        }
                    },
                    getIconLabel: function(id) {
                        if (id) {
                            if (this.iconElements[id]) {
                                return this.iconElements[id];
                            }
                            var icon = $("label[for=" + id + "].validateIcon");
                            if (!icon.length) {
                                icon = $('<label for=' + id + ' class="validateIcon"></label>');
                                this.getInfoLabel(id).before(icon);
                            }
                            return this.iconElements[id] = icon;
                        }
                    },
                    success: function(id) {
                        var label = this.getIconLabel(id);
                        if (label && label.length) {
                            label.removeClass("validateIconError").addClass("validateIconSuccess");
                        }
                    },
                    hideInfoLabel: function(id) {
                        var label = this.getInfoLabel(id);
                        if (label && label.length) {
                            label.hide();
                        }
                    },
                    showInfoLabel: function(id) {
                        var label = this.getInfoLabel(id);
                        if (label && label.length) {
                            label.css({
                                display: "inline-block"
                            });
                        }
                        var icon = this.getIconLabel(id);
                        if (icon && icon.length) {
                            icon.removeClass();
                        }

                        $("label.defaultError[for=" + id + "]").hide();
                    },
                    error: function(id) {
                        var label = this.getIconLabel(id);
                        if (label && label.length) {
                            label.addClass("validateIconError").removeClass("validateIconSuccess");
                        }
                    }
                };

                $.validator.setDefaults({
                    onkeyup: false,
                    errorClass: "validateError",
                    errorPlacement: function(error, element) {
                        var label = $("label[for=" + element.attr("id") + "].validateInfo");
                        label.after(error);
                    },
                    focusCleanup: true,
                    success: function(label, element) {
                        label.hide();
                        labels.success(element.id);
                    },
                    showErrors: function(msgs, nodeList) {
                        var i;
                        for ( i = 0; this.errorList[i]; i++ ) {
                            var error = this.errorList[i];
                            labels.error(error.element.id);

                        }
                        this.defaultShowErrors();
                    },
                    onfocusout: function(element, event) {
                        if (!this.checkable(element)) {
                            var el = $(element);
                            el.val($.trim(el.val()));
                            this.element(element);
                            if (!this.settings.rules[element.id]) {
                                labels.success(element.id);
                            }
                        }
                        labels.hideInfoLabel(element.id);
                        $(element).removeClass("focus");
                    },
                    onfocusin: function(element, event) {
                        $(element).addClass("focus");
                        labels.showInfoLabel(element.id);
                        onfocusin.apply(this, arguments);
                    }
                });
                $.validator.customSetting = true;
            },
            formsubmit: function() {
                if (this.form.length) {
                    if (this.valid()) {
                        this.form[0].submit();
                    }
                }
            },
            doListeners: function() {
                return W.Component.prototype.doListeners.apply(this, arguments);
            },
            addListener: W.Component.prototype.addListener,
            removeListener: W.Component.prototype.removeListener,
            trigger: W.Component.prototype.trigger,
            ajaxSubmit: function() {
                this.loading = W.message("正在提交数据...", "loading", 1000);
                var data = this.form.serializeArray() || [], self = this;

                var userData;
                if (typeof this.userData === "function") {
                    userData = this.userData.call(this);
                }
                else {
                    userData = this.userData;
                }

                $.each(userData || {}, function(k, v) {
                    data.push({
                        name: k,
                        value: v
                    });
                });

                return $.ajax({
                    type: this.type || "get",
                    url: this.action,
                    dataType: "json",
                    data: data
                }).done(function(data) {
                    if (data.code === 1000) {
                        if (typeof self.submitSuccess === "function") {
                            self.submitSuccess.apply(self, arguments);
                        }
                    }
                    else if (typeof self.submitError === "function") {
                        self.submitError.apply(self, arguments);
                    }
                }).fail(function() {
                    if (typeof self.submitError === "function") {
                        self.submitError.apply(self, arguments);
                    }
                }).always(function() {
                    if (self.loading) {
                        self.loading.close();
                    }
                });
            }

        }
    });

    

}) (jQuery, Weiboyi);