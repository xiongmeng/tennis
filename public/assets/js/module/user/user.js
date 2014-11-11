define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');
    require('bootbox');
    require('knockout_plupload');
    require('knockout_area');

    return function (user) {
        var self = this;
        self.user_id = ko.observable(user.user_id);
        self.nickname = ko.observable(user.nickname);
        self.telephone = ko.observable(user.telephone);
        self.balance = ko.observable(user.balance);
        return self;
    };
});