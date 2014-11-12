define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');
    require('bootbox');
    require('knockout_phpTsToDate');
    require('knockout_options');

    var User = require('user/user');
    var Hall = require('hall/hall');

    return function (order) {
        var self = this;
        self.id = ko.observable(order.id);
        self.user_id = ko.observable(order.user_id);
        self.user = ko.observable(new User(order.user || {}));

        self.hall_id = ko.observable(order.hall_id);
        self.hall = ko.observable(new Hall(order.hall || {}));

        self.event_date = ko.observable(order.event_date).extend({phpTsToDate: '', options: order.dates || {}});

        self.start_time = ko.observable(order.start_time);
        self.start_time_option = ko.computed(function () {
            var start = self.hall().business.start();
            var end = self.hall().business.end();
            var res = [];
            for (start; start < end; start++) {
                res.push(start);
            }
            return res;
        });

        self.end_time = ko.observable(order.end_time);
        self.end_time_option = ko.computed(function () {
            var start = self.start_time() + 1;
            var end = self.hall().business.end();
            var res = [];
            for (start; start <= end; start++) {
                res.push(start);
            }
            return res;
        });

        self.court_num = ko.observable(order.court_num);
        self.court_num_option = [1, 2, 3, 4, 5, 6];
        self.cost = ko.observable(order.cost);
        self.cost_text = ko.observable(order.cost_text);
        self.stat = ko.observable(order.stat);

        self.callback_saved = function(){

        };

        function generateOrderData(){
            var data = mapping.toJS(self);
            return {
                id: data.id,
                user_id : data.user.user_id,
                hall_id : data.hall.id,
                event_date: data.event_date,
                start_time: data.start_time,
                end_time: data.end_time,
                court_num: data.court_num
            }
        }

        self.create = function () {
            var defer = $.restPost('/reserve/save', generateOrderData());
            defer.done(function(res, data){
                self.callback_saved(data.order);
            });
            defer.fail(function(msg){
                alert(msg);
            });
            return defer;
        };

        self.calculate = function(){
            var defer = $.restPost('/reserve/calculate', generateOrderData());
            defer.done(function (res, data) {
                self.cost(data.order.cost);
            });
            defer.fail(function(msg){
                alert(msg);
            });
            return defer;
        };

        return self;
    };
});