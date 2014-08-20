define(function(require){
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('knockout_switch_case');
    require('rest');

    var option = {
        'submitUrl' : ''
    };

    var ListModel = function(mappingModel){
        var self = mappingModel;
        self.selected = ko.observableArray();
        self.currentState = ko.observable('');

        self.select = function(instantOrder, event){
            if(instantOrder.select()){
                instantOrder.select(false);
                self.selected.remove(instantOrder);
            }else{
                if(self.currentState() == '' || self.currentState() == instantOrder.state()){
                    self.currentState(instantOrder.state());
                    instantOrder.select(true);
                    self.selected.push(instantOrder);

                    $(event.currentTarget).toolbar({
                        content: '#user-toolbar-options',
                        position: 'bottom'
                    });
                }
            }

            console.log(arguments);
        };

        self.cancelSelected = function(){
            $.each(self.selected(), function(index, instantOrder){
                instantOrder.select(false);
            });
            self.selected.removeAll();
            self.currentState('');
        };

        self.submitSelected = function(){
            var selectedIds = [];
            $.each(self.selected(), function(index, instantOrder){
                selectedIds.push(instantOrder.id());
            });
            var stateOperateMaps = {draft: 'online', on_sale: 'offline'};
            var defer = $.restPost(option.submitUrl,
                {'operate' : stateOperateMaps[self.currentState()], 'instant_order_ids' : selectedIds.join(',')});

            defer.done(function(res, data){
                window.location.reload();
            });
        };

        self.buyerSubmitSelected = function(){
            var selectedIds = [];
            $.each(self.selected(), function(index, instantOrder){
                selectedIds.push(instantOrder.id());
            });

            var submitUrlMaps = {
                on_sale : '/instantOrder/batchBuy',
                paying : '/instantOrder/batchPay'
            };
            var defer = $.restPost(submitUrlMaps[self.currentState()], {'instant_order_ids' : selectedIds.join(',')});

            defer.done(function(res, data){
                if(data.status == 'no_money' || data.status == 'pay_success'){
                    window.open(data['advice_forward_url']);
                }
            });
        };

        return self;
    };

    function init(dom, worktableData, cfg){
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
        init : init
    }
});