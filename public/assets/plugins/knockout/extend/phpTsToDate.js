define(function (require) {
    var ko = require('knockout');
    var W=require('weiboyi');
    ko.extenders.phpTsToDate = function (target, format) {
        //create a writeable computed observable to intercept writes to our observable
        target.date = ko.computed({
                read: function () {
                    return target() ? W.util.formatDate(new Date(target() * 1000), format || 'Y-m-d H:i') : '';
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