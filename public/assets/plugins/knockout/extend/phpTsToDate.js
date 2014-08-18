define(function(require) {
    var ko = require('knockout');
    ko.extenders.phpTsToDate = function(target, format) {
        //create a writeable computed observable to intercept writes to our observable
        target.date = ko.computed(function(){
            return W.util.formatDate(new Date(target() * 1000), format || 'Y-m-d H:i');
        });

        return target;
    };
});