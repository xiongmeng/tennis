define(function(require){
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('knockout_switch_case');
    require('rest');
    require('bootbox');

    var option = {
        'submitUrl' : ''
    };

    var MapModel = function(map){
        var self = this;
        self.id = map.id;
        self.hall_id = map.hall_id;
        self.long = ko.observable(map.long);
        self.lat = ko.observable(map.lat);
        self.baidu_code = ko.observable(map.baidu_code);
    };

    var UserModel = function(user){
        var self = this;
        self.user_id = user.user_id;
        self.nickname = ko.observable(user.nickname);
        self.receive_sms_telephone = ko.observable(user.receive_sms_telephone);
        self.init_password = ko.observable(user.init_password);
    };

    var HallModel = function(mappingModel, hallData){
        var self = mappingModel;
        self.map = ko.observable(new MapModel(hallData.map || {}));
        self.user = ko.observable(new UserModel(hallData.users.length > 0 ? hallData.users[0] : {}));

        self.generateUser = function(){
            var $user = mapping.toJS(self.user);
            bootbox.confirm('确认要为此场馆添加用户吗？', function(result){
                if(!result){
                    return ;
                }
                var defer = $.restPost('/hall/generateUser/' + self.id(), $user);
                defer.done(function(res, data){
                    window.location.reload();
                });
            });
        };

        self.saveMap = function(){
            var $map = mapping.toJS(self.map);
            var defer = $.restPost('/hall/saveMap/' + self.id(), $map);
            defer.done(function(res, data){
                window.location.reload();
            });
        };

        self.update = function(){
            var $hall = mapping.toJS(self);
            var defer = $.restPost('/hall/update/' + self.id(), $hall);
            defer.done(function(res, data){
                window.location.reload();
            });
        };

        return self;
    };

    function init(dom, hallData, cfg){
        $.extend(option, cfg);
        var hall = new HallModel(mapping.fromJS(hallData), hallData);
        ko.applyBindings(hall, dom);
    }

    return {
        init : init
    }
});