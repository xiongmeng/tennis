define(function(require){
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');

    var ListModel = function(mappingModel){
        var self = mappingModel;
        self.selected = ko.observableArray();
        self.currentState = ko.observable('');

        self.select = function(instantOrder){
            if(instantOrder.select()){
                instantOrder.select(false);
                self.selected.remove(instantOrder);
            }else{
                if(self.currentState() == '' || self.currentState() == instantOrder.state()){
                    self.currentState(instantOrder.state());
                    instantOrder.select(true);
                    self.selected.push(instantOrder);
                }
            }
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
            var defer = $.restPost('/xm/hall/instantOrder/batchOperate/' + stateOperateMaps[self.currentState()],
                {'instant_order_ids' : selectedIds.join(',')});

            defer.done(function(res, data){
                window.location.reload();
            });
        };

        return self;
    };

    function init(dom){
        var list;
        var defer = $.restGet('/xm/instantOrder/view/8935/2014-08-18/');

        defer.done(function(res, data){
            if(!list){
                list = new ListModel(mapping.fromJS(data));
                ko.applyBindings(list, dom);
            }else{
                mapping.fromJS(data, list);
            }
        });
    }

    return {
        init : init
    }
});