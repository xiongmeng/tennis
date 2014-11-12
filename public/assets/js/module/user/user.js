define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');
    require('bootbox');
    require('knockout_plupload');
    require('knockout_area');
    require('knockout_options');

    return function (user) {
        var self = this;
        var privileges = [
            {id: 1, name: '普通会员'},
            {id: 2, name: 'vip会员'}
        ];
        self.user_id = ko.observable(user.user_id);
        self.detail_url = ko.computed(function(){
            return '/user/detail/' + self.user_id();
        });
        self.nickname = ko.observable(user.nickname);
        self.telephone = ko.observable(user.telephone);
        self.balance = ko.observable(user.balance);
        self.privilege = ko.observable(user.privilege).extend({options:privileges});
        return self;
    };
});