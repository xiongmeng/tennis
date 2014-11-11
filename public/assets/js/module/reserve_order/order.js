define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');
    require('bootbox');
    require('knockout_phpTsToDate');

    var User = require('user/user');
    var Hall = require('hall/hall');

    return function (order) {
        var self = this;
        self.user_id = ko.observable(order.user_id);
        self.user = ko.observable(new User(order.user || {}));

        self.hall_id = ko.observable(order.hall_id);
        self.hall = ko.observable(new Hall(order.hall || {}));

        self.event_date = ko.observable(order.event_date).extend({phpTsToDate: ''});

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

        function generate(preview) {
            var data = mapping.toJS(self);
            var url = '/reserve/create/' + data.user.user_id + '/' + data.hall.id
                + '/' + data.event_date + '/' + data.start_time + '/' + data.end_time + '/' + data.court_num;
            var defer = $.restPost(url, {preview: preview ? 1 : 0});
            defer.done(function (res, data) {
                self.cost(data.order.cost);

                if (!preview) {
                    window.location.href = '/reserve_order_mgr/book_pending';
                }
            });
            defer.fail(function (msg) {
                bootbox.alert(msg);
            });
        }

        self.create = function () {
            generate(false);

        };

        self.calculate = function () {
            generate(true);
        };

        return self;
    };
});