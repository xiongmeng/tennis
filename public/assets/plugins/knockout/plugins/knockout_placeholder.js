define(function(require) {
    var ko = require('knockout');

    ko.bindingHandlers.placeholder = {
        init: function(element, valueAccessor) {
            W.form.holdPlace(element, valueAccessor());
        },
        update: function(element, valueAccessor) {
            W.form.holdPlace(element, valueAccessor());
        }
    };
});