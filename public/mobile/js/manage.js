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
            return self.ttl() > 0 ? ('支付中，请稍候' + self.ttl() + '秒') : '微信支付';
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

        self.payReservationOrder = function(reservationOrder){
            var defer = $.restPost('/reserveOrder/pay' ,{'reserve_order_ids' : reservationOrder.id} );

            defer.done(function (res, data) {
                if (data.status == 'no_money') {
                    mapping.fromJS({'noMoney' :data},self);
                    $('#noMoneyModal').addClass('active');
                } else if (data.status == 'pay_success') {
                    $('#paySuccessModal').addClass('active');
                }
            });
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
    }

    return {
        init: init
    }
});