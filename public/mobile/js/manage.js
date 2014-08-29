define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('knockout_switch_case');
    require('rest');
    require('bootbox');
    require('kkcountdown');

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

            var defer = $.restPost(url, {'instant_order_ids': selectedIds.join(',')});

            defer.done(function (res, data) {
                if (data.status == 'no_money') {

                    mapping.fromJS({'noMoney' :data},self);

                    $('#confirmingPayModal').removeClass('active');
                    $('#noMoneyModal').addClass('active');
//                    $modalDom = $('#dialog-go-to-pay');
//                    $model = mapping.fromJS(data);
//                    ko.applyBindings($model, $modalDom[0]);
//
//                    $modalDom.modal().on('hidden.bs.modal', function () {
//                        window.location.reload();
//                    });
                } else if (data.status == 'pay_success') {
                    bootbox.alert('恭喜您，购买成功！您可以在已购买订单里面查看详情！').on('hidden.bs.modal', function () {
                        window.location.reload();
                    });
                }
            });
        }

        self.batchBuy = function () {
            doPay('/instantOrder/batchBuy');
        };
        self.batchPay = function () {
            doPay('/instantOrder/batchPay');
        };

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