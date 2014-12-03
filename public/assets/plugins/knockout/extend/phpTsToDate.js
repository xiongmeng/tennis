define(function (require) {
    var ko = require('knockout');
    var W=require('util');
    ko.extenders.phpTsToDate = function (target, format) {
        //create a writeable computed observable to intercept writes to our observable
        target.date = ko.computed({
                read: function () {
                    return target() ? new Date(target() * 1000).format('yyyy-MM-dd HH:mm') : '';
                },
                write: function (value) {
                    date = new Date(value);
                    target(Date.parse(date)/1000);
                }
            }
        );

        return target;
    };
});