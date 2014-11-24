define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');
    require('bootbox');
    require('knockout_options');

//    var User = require('user/user');
    var Hall = require('hall/hall');

    return function (seeking) {
        var self = this;
        self.id = ko.observable(seeking.id);
        self.detail_url = ko.computed(function(){
            return '/seeking/detail/' + self.id();
        });
        self.state = ko.observable(seeking.state);
        self.event_date = ko.observable(seeking.event_date);
        self.start_hour = ko.observable(seeking.start_hour).extend(
            {options: [6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23]});

        self.end_hour = ko.observable(seeking.end_hour);
        self.end_hour_option = ko.computed(function () {
            var start = self.start_hour() + 1;
            var res = [];
            for (start; start <= 24; start++) {
                res.push(start);
            }
            return res;
        });

        self.hall_id = ko.observable(seeking.hall_id);
        self.hall_name = ko.observable(seeking.hall_name);
//        self.hall = ko.observable(new Hall(seeking.hall || {}));

        self.court_num = ko.observable(seeking.court_num).extend({options: [1, 2, 3, 4, 5, 6]});
        self.tennis_level = ko.observable(seeking.tennis_level).extend({options: [
            {id: 1, name: '1.0'},
            {id: 2, name: '2.0'},
            {id: 3, name: '3.0'},
            {id: 4, name: '3.5'},
            {id: 5, name: '4.0'},
            {id: 6, name: '4.5'},
            {id: 7, name: '5.0'},
            {id: 8, name: '更高'}
        ]});

        self.content = ko.observableArray(function(){
            return seeking.content ? seeking.content.split(' ') : '';
        }()).extend({options: [
            {id: 1, name: '单打'},
            {id: 2, name: '双打'},
            {id: 3, name: '混双'},
            {id: 4, name: '练习'},
            {id: 5, name: '比赛'}
        ]});

        self.sexy = ko.observable(seeking.sexy).extend({options: [
            {id:-1, name: '不限'},
            {id: 1, name: '女'},
            {id: 2, name: '男'}
        ]});

        self.personal_cost = ko.observable(seeking.personal_cost);

        self.on_sale = ko.observable(seeking.on_sale);
        self.store = ko.observable(seeking.store);

        self.creator = ko.observable(seeking.creator);

        self.comment = ko.observable(seeking.comment);

        function generateOrderData(){
            return {
                id: self.id(),
                event_date: self.event_date(),
                start_hour: self.start_hour(),
                end_hour: self.end_hour(),

                hall_id : self.hall_id(),
                court_num: self.court_num(),

                tennis_level: self.tennis_level(),
                sexy: self.sexy(),

                on_sale: self.on_sale(),
                store: self.store(),

                personal_cost: self.personal_cost(),
                content: self.content().join(' '),

                comment: self.comment()
            }
        }

        self.save = function(){
            var defer = $.restPost('/seeking/save', generateOrderData());
            defer.done(function(res, data){
                console.log(arguments);
                window.location.href = '/seeking/list';
            });
            defer.fail(function(msg){
                alert(msg);
            });
        };
        return self;
    };
});