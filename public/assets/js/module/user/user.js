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

        var RoleModel = function (role) {
            var self = this;
            self.id = ko.observable(role.id);
            self.user_id = ko.observable(role.user_id);
            self.role_id = ko.observable(role.role_id).extend({options: [
                {id: 1, name: '球友'},
                {id: 2, name: '管理员'},
                {id: 3, name: '场馆'},
                {id: 999, name: '开发'}
            ]});
            self.created_at = ko.observable(role.created_at);
        };

        var privileges = [
            {id: 1, name: '普通会员'},
            {id: 2, name: 'vip会员'}
        ];
        self.user_id = ko.observable(user.user_id);
        self.detail_url = ko.computed(function () {
            return '/user/detail/' + self.user_id();
        });
        self.nickname = ko.observable(user.nickname);
        self.telephone = ko.observable(user.telephone);
        self.balance = ko.observable(user.balance);
        self.privilege = ko.observable(user.privilege).extend({options: privileges});
        self.roles = ko.observableArray(function () {
            var roles = [];
            $.each(user.roles || {}, function (index, item) {
                roles.push(new RoleModel(item));
            });
            roles.push(new RoleModel({user: user.id}));
            return roles;
        }());

        self.saveRole = function (data) {
            var defer = $.restPost('/role/save/' + self.user_id() + '/' + data.role_id(), {id: data.id()});
            defer.done(function (res, data) {
                window.location.reload();
            });
        };
        self.deleteRole = function (data) {
            var defer = $.restPost('/role/delete/' +  data.id());
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        return self;
    };
});