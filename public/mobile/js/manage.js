define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('knockout_switch_case');
    require('rest');

    var option = {
        'submitUrl': ''
    };

    var ListModel = function (mappingModel) {
        var self = mappingModel;
        self.selected = ko.observableArray();
        self.currentState = ko.observable('');

        self.selectedMoney = ko.computed(function () {
            var selectedMoney = 0;
            $.each(self.selected(), function (index, instantOrder) {
                selectedMoney += parseInt(instantOrder.quote_price());
            });
            return selectedMoney;
        });

        self.selected.subscribe(function (newSelected) {
            var length = newSelected.length;
            if (length <= 0) {
                self.currentState('');
            } else {
                var lastInstantOrder = newSelected[length - 1];
                self.currentState(lastInstantOrder.state());
            }
        });

        self.select = function (instantOrder, event) {
            if (instantOrder.select()) {
                instantOrder.select(false);
                self.selected.remove(instantOrder);
            } else {
                if (self.currentState() == '' || self.currentState() == instantOrder.state()) {
                    instantOrder.select(true);
                    self.selected.push(instantOrder);
                }
            }
        };

        function doBatch(operate) {
            var selectedIds = [];
            $.each(self.selected(), function (index, instantOrder) {
                selectedIds.push(instantOrder.id());
            });
            var defer = $.restPost('/instantOrder/batchOperate',
                {'operate': operate, 'instant_order_ids': selectedIds.join(',')});

            defer.done(function (res, data) {
                window.location.reload();
            });
            defer.fail(function () {

            });
        }

        self.batchOnline = function () {
            doBatch('online');
        };
        self.batchOffline = function () {
            doBatch('offline');
        };
        self.batchCancelBuy = function () {
            doBatch('cancel_buy');
        };

        function doPay(url) {
            var selectedIds = [];
            var selectedMoney = 0;

            $.each(self.selected(), function (index, instantOrder) {
                selectedIds.push(instantOrder.id());
                selectedMoney += parseInt(instantOrder.quote_price());
            });

            var params = {'instant_order_ids': selectedIds.join(',')};
            var appUserId = getQueryString('app_user_id');
            var appId = getQueryString('app_id');
            appUserId && (params.app_user_id = appUserId);
            appId && (params.app_id = appId);

            var defer = $.restPost(url, params);

            defer.done(function (res, data) {
                if (data.status == 'no_money') {
                    mapping.fromJS({'noMoney' :data},self);
                    $('#noMoneyModal').addClass('active');
                } else if (data.status == 'pay_success') {
                    $('#paySuccessModal').addClass('active');
                }
            });
        }

        self.batchBuy = function () {
            $('#confirmingBuyModal').removeClass('active');
            doPay('/instantOrder/batchBuy');
        };
        self.batchPay = function () {
            $('#confirmingPayModal').removeClass('active');
            doPay('/instantOrder/batchPay');
        };

        self.ttl = ko.observable(0);
        self.wxPayText = ko.computed(function(){
            return self.ttl() > 0 ? ('接口比较慢，请耐心等待' + self.ttl() + '秒') : '微信支付';
        });

        self.ttl.subscribe(function(newValue) {
            if(newValue > 0){
                window.setTimeout(function () {
                    self.ttl(self.ttl()-1);
                }, 1000)
            }
        });

        self.goToWXPay = function(){
            if(self.ttl() <= 0){
                window.location.href = self.noMoney.weChatPayUrl();
                self.ttl(5);
            }
        };

        function getQueryString(name) {
            var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
            var r = window.location.search.substr(1).match(reg);
            if (r != null) return unescape(r[2]); return null;
        }

        return self;
    };

    function init(dom, worktableData, cfg) {
        $.extend(option, cfg);

        var list = new ListModel(mapping.fromJS(worktableData));
        ko.applyBindings(list, dom);

//        var defer = $.restGet('/xm/instantOrder/view/8935/2014-08-18/');
//
//        defer.done(function(res, data){
//            if(!list){
//                list = new ListModel(mapping.fromJS(data));
//            }else{
//                mapping.fromJS(data, list);
//            }
//        });
    }

    return {
        init: init
    }
});