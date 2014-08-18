define(function(require){
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');

    var InstantOrderModel = function(data){

    };

    var ListModel = function(mappingModel){
        var self = mappingModel;
        self.hallList = ko.observableArray();

        self.loadInstantOrder = function(url, query){
            $.restGet(url, query, function(res, data){
//                self.instantOrderList.destroyAll();
//                $.each(instantOrders, function (i, data) {
//                    self.instantOrders.push(new InstantOrderModel(data));
//                });
//                self.courts.destroyAll();
//                $.each(data.courts, function(i, court){
//                    self.courts.push(court);
//                });
//
//                self.hours.destroyAll();
//                $.each(data.hours, function(i, hour){
//                    self.hours.push(hour);
//                });

//                self.instantOrders(data.instantOrders);

                var model = mapping.fromJS(data);
                mapping.fromJS(data, self);
            });
        };

        return self;
    };

    function init(dom){
        var list;
        var defer = $.restGet('http://homestead.app:8000/xm/instantOrder/view/8935/2014-08-18/');

        defer.done(function(res, data){
            if(!list){
                list = new ListModel(mapping.fromJS(data));
                ko.applyBindings(list, dom);
            }else{
                mapping.fromJS(data, list);
            }
        });

        var old= {
            key1 : 2
        };

        var new1 = {
            key1 : 1,
            key2 : '3'
        };

        var model = mapping.fromJS(old);
        mapping.fromJS(new1, model);

        var mappingModel = mapping.fromJS({});
        var model1 = new ListModel(mappingModel);
        mapping.fromJS(old, model1);
    }

    return {
        init : init
    }
});