var Weiboyi, W;

Weiboyi = W = (function($, window, document, undefined) {

    if (typeof console === "undefined") {
        window.console = {
            log: $.noop,
            warn: $.noop,
            error: $.noop
        };
    }

    /**
     * 创建组件的核心方法
     **/
    function createComponent(cfg) {
        
        if (typeof cfg.xtype === "string") {
            var self = this;
            self[cfg.xtype] = function(c) {
                this.superClass = cfg.extend;
                c = c || {};

                $.extend(true, this, cfg.opts);

                this.id = this.id || W.getAutoID(this.autoIdPrefix);
                
                if (typeof c === "string" || typeof c === "number") {
                    this.config = {};
                    $.extend(this.config, cfg.opts);
                    this.initialize(c);
                }
                else {
                    $.extend(this, c);
                    this.config = {};
                    $.extend(this.config, self[cfg.xtype].opts, c);
                    this.initialize();
                }

                //注册组件
                W.addCmp(this.id, this);
                return this.proxy || this;
            };

            if (cfg.extend) {
                W.extend(self[cfg.xtype], cfg.extend);
                
                if (cfg.api) {
                    cfg.api = $.unique(cfg.api.concat(cfg.extend.api));
                }
                else {
                    cfg.api = cfg.extend.api;
                }
            }

            var xtype = cfg.xtype.toLowerCase();
            self.register[xtype] = self[cfg.xtype];
            $.extend(self.register[xtype].prototype, cfg.methods);
            self[cfg.xtype].prototype.xtype = xtype;
            self[cfg.xtype].api = cfg.api;
            self[cfg.xtype].opts = cfg.opts;
        }
    }

    /**
　　 * @author pjn
 　　* @version 1.1
     * @description 新增业务组件Grid
     *              新增tb主题
　　 */
    var W = {
        version: "1.1",
        // 所有组件实例的集合
        components: {},
        // 组件实例分组
        register: {},
        layouts: {
            register: {}
        },
        zIndex: {
            no: undefined,
            low: 0,
            middle: 1000,
            high: 10000,
            getMax: function(lvl, step) {
                lvl = lvl || "low";
                if (this.hasOwnProperty(lvl)) {
                    return this[lvl || "low"] += (step || 1);
                }
            }
        },
        isCmp: function(cmp) {
            return cmp && cmp.status;
        },
        _isDeferred: function(der) {
            return der && typeof der.done === "function" && typeof der.fail === "function";
        },
        getCmp: function(id) {
            var cmp = this.components[id];
            return cmp && cmp.proxy || cmp;
        },
        getCmps: function(filters) {
            filters = filters || {};
            var cmps = [];
            $.each(this.components, function(k, v) {
                var flag = true;
                $.each(filters, function(fk, fv) {
                    if (v[fk] !== fv) {
                        return flag = false;
                    }
                });

                if (flag) {
                    cmps.push(v.proxy || v);
                }
            });
            return cmps;
        },
        addCmp: function(id, cmp) {
            if (typeof id === "string" || typeof id === "number"){
                this.components[id] = cmp;
            }
        },
        removeCmp: function(id) {
            delete this.components[id];
        },
        getAutoID: function(prefix) {
            this.autoID = this.autoID || 0;
            this.autoID++;
            return (prefix || "weiboyiCmp") + this.autoID;
        },
        /**
         * 仅用于prototype方式的继承
         **/
        extend: function(subCls, superCls) {
            $.each(superCls.prototype, function(k, v) {
                subCls.prototype[k] = subCls.prototype[k] || v;
            });
        },
        setDefaultOpts: function(xtype, opts) {
            $.extend(W.register[xtype].opts, opts);
        },
        createComponent: createComponent,
        /**
         * 做测试用到的函数集合
         **/
        test: {
            effCompare: function() {
                var args = W.util.asArray(arguments), len = args.length, times = 100, i;
                if (len >= 2) {
                    if (typeof args[len - 1] === "number") {
                        times = args[len - 1];
                    }
                    $.each(args, function(j, e) {
                        if (typeof e === "function") {
                            var s = new Date().getTime();
                            for (i = 0; i < times; i++) {
                                e();
                            }
                            console.log(j + "--" + (new Date().getTime() - s));
                        }
                    });
                }
            }
        },
        module: function(ns, callback) {
            if (!ns || typeof ns !== "string") {
                console.log("error namespace : " + ns);
            }
            if (typeof window[ns] === "undefined") {
                if (typeof callback === "function") {
                    window[ns] = callback() || {};
                }
                else {
                    window[ns] = {};
                }
            }
            else {
                console.log("namespace " + ns + " already exists");
            }
        }
    };

    W.Tools = {
        types: {
            close: {
                title: "关闭",
                cls: "weiboyiTool weiboyiTool_close",
                defaultHandler: function(event, tool, panel, params) {
                    if (panel.close) {
                        panel.close();
                    }
                    else {
                        panel.hide();
                    }
                }
            },
            gear: {
                title: "设置",
                cls: "weiboyiTool weiboyiTool_gear",
                defaultHandler: function(event, tool, panel, params) {

                }
            },
            help: {
                title: "帮助",
                cls: "weiboyiTool weiboyiTool_help",
                defaultHandler: function(event, tool, panel, params) {
                }
            },
            more: {
                title: "更多",
                cls: "weiboyiTool weiboyiTool_more",
                defaultHandler: function(event, tool, panel, params) {

                }
            }
        },
        renderTools: function(tools, el) {
            var self = this;
            if (tools instanceof Array && tools.length) {
                $.each(tools, function(i, e) {
                    var tool, handler, name;
                    if (typeof e === "string") {
                        if (!W.Tools.types.hasOwnProperty(e)) {
                            return;
                        }
                        name = e;
                        tool = W.Tools.types[e];
                        handler = tool.defaultHandler;
                    }
                    else if (W.util.isObject(e)) {
                        name = e.id;
                        tool = W.Tools.types[e.id] || e;
                        handler = e.handler || tool.defaultHandler || $.noop;
                    }
                    else {
                        return;
                    }
                    var toolEl = $('<a href="javascript:void(0)" class="' + tool.cls + '"></a>').text(tool.text || "");
                    toolEl.click(function(e) {
                        handler.call(self, e, toolEl, self);
                        //e.preventDefault();
                        e.stopPropagation();
                    });
                    toolEl.appendTo(el);
                });
            }
        }
    };

    //事件管理
    W.EventManager = {
        events: {},
        addListener: function(type, handler, scope, params) {
            this.events = this.events || {};
            this.events[type] = this.events[type] || [];
            this.events[type].push({
                handler: handler,
                scope: scope,
                params: params
            });
        },
        removeListener: function(type, handler, scope) {
            if (this.events) {
                this.events[type] = $.grep(this.events[type], function(e) {
                    var s = scope || e.scope;
                    var h = handler || e.handler;
                    return e.scope !== s || e.handler !== h;
                });
            }
        },
        trigger: function(type, params) {
            if (this.events) {
                var fns = this.events[type], i, fn;
                if(!fns) {
                    return;
                }
                for(i = 0; fn = fns[i]; i++) {
                    if (fn.handler.apply(fn.scope || this, params || fn.params || []) === false) {
                        return false;
                    }
                }
            }
        }
    };

    /**
     * ie6/7中使用userData做本地存储
     */
    var UserData = {
        userData: null,
        name: location.hostname,
        expiresDays: 360,
        init: function(){
            if (!UserData.userData) {
                try {
                    UserData.userData = document.createElement('INPUT');
                    UserData.userData.type = "hidden";
                    UserData.userData.style.display = "none";
                    UserData.userData.style.behavior = "url('#default#userData')" ;
                    UserData.userData.addBehavior ("#default#userData");
                    document.body.appendChild(UserData.userData);
                    var expires = new Date();
                    expires.setDate(expires.getDate() + UserData.expiresDays);
                    UserData.userData.expires = expires.toUTCString();
                } catch(e) {
                    return false;
                }
            }
            return true;
        },
        clear: function() {
            
        },
        setItem: function(key, value) {

            if(UserData.init()){
                UserData.userData.load(UserData.name);
                UserData.userData.setAttribute(key, value);
                UserData.userData.save(UserData.name);
            }
        },
        getItem: function(key) {
            if(UserData.init()){
                UserData.userData.load(UserData.name);
                return UserData.userData.getAttribute(key);
            }
        },
        removeItem: function(key) {
            if(UserData.init()){
                UserData.userData.load(UserData.name);
                UserData.userData.removeAttribute(key);
                UserData.userData.save(UserData.name);
            }

        }
    };
    W.UserData = UserData;
    W.localStorage = window.localStorage || W.UserData;

    W.restGet = function(url, data, success, error, type) {
        var der = $.Deferred();
        $.ajax({
            url: url,
            type: type || "get",
            data: data,
            dataType: "json"
        }).done(function(data) {
            if (data && data.code === 1000) {
                if (typeof success === "function") {
                    success.call(this, data);
                }
                der.resolve();
            }
            else {
                der.reject(data);
            }
        }).fail(function() {
            der.reject();
        });
        return der.promise();
    };

    W.restPost = function(url, data, success, error) {
        return W.restGet(url, data, success, error, "post");
    };

    var supportTester = document.createElement("INPUT");
    W.support = {
        placeholder: "placeholder" in supportTester,
        css3Animation: "webkitAnimation" in supportTester.style || "oAnimation" in supportTester.style || "mozAnimation" in supportTester.style || "animation" in supportTester.style
    };

    return W;
}) (jQuery, window, document);