define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    var UserModel = require('user/user');
    require('rest');
    require('knockout_area');

    return function (userList, queries) {
        var self = this;

        function initListFromJs(userList){
            self.userList.removeAll();
            $.each(userList, function(index, item){
                self.userList.push(new UserModel(item));
            })
        }

        var QueryModel = function(queries){
            var self = this;
            self.user_id = ko.observable(queries.user_id);
            self.nickname = ko.observable(queries.nickname);
            self.telephone = ko.observable(queries.telephone);
        };

        self.userList = ko.observableArray();
        initListFromJs(userList || {});

        self.queries = new QueryModel(queries || {});

        self.search = function(){
            var queries = mapping.toJS(self.queries);
            var defer = $.restGet('/user', queries);
            defer.done(function(res, data){
                initListFromJs(data.users.data);
            });
        };

        self.clear = function(){
            initListFromJs({});
        };
        return self;
    };
});