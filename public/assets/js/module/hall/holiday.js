define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');
    require('knockout_phpTsToDate');
    require('knockout_options');

    var HolidayModel = function (holiday) {
        var self = this;
        self.id = ko.observable(holiday.id);
        self.date = ko.observable(holiday.date).extend({phpTsToDate: ''});
        self.type = ko.observable(holiday.type).extend({options: [
            {id: 1, name: '节假日'},
            {id: 2, name: '平时'}
        ]});
        self.created_at = ko.observable(holiday.created_at);
        self.updated_at = ko.observable(holiday.updated_at);
    };

    return function (holidays) {
        var self = this;

        self.holidays = ko.observableArray(function () {
            var res = [new HolidayModel({})];
            $.each(holidays || {}, function (index, item) {
                res.push(new HolidayModel(item));
            });
            return res;
        }());

        self.save = function (data) {
            var holiday = mapping.toJS(data);
            var defer = $.restPost('/holiday/save', holiday);
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.remove = function (data) {
            var defer = $.restPost('/holiday/remove/' + data.id());
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        return self;
    };
});