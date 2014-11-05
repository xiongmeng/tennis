define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');
    require('bootbox');
    require('knockout_plupload');

    var MarketModel = function (market) {
        var self = this;
        self.id = ko.observable(market.id);
        self.hall_id = ko.observable(market.hall_id);
        self.type = ko.observable(market.type);
        self.start_week = ko.observable(market.start_week);
        self.end_week = ko.observable(market.end_week);
        self.start = ko.observable(market.start);
        self.end = ko.observable(market.end);
        self.price = ko.observable(market.price);
    };

    var PriceModel = function (price) {
        var self = this;
        self.id = ko.observable(price.id);
        self.hall_id = ko.observable(price.hall_id);
        self.court_type = ko.observable(price.court_type);
        self.name = ko.observable(price.name);
        self.market = ko.observable(price.market);
        self.member = ko.observable(price.member);
        self.vip = ko.observable(price.vip);
        self.purchase = ko.observable(price.purchase);
    };

    var CourtGroupModel = function (courtGroup) {
        var self = this;
        self.id = courtGroup.id;
        self.hall_id = courtGroup.hall_id;
        self.name = courtGroup.name;
        self.count = courtGroup.count;
    };

    var MapModel = function (map) {
        var self = this;
        self.id = map.id;
        self.hall_id = map.hall_id;
        self.long = ko.observable(map.long);
        self.lat = ko.observable(map.lat);
        self.baidu_code = ko.observable(map.baidu_code);
    };

    var UserModel = function (user) {
        var self = this;
        self.user_id = user.user_id;
        self.nickname = ko.observable(user.nickname);
        self.receive_sms_telephone = ko.observable(user.receive_sms_telephone);
        self.init_password = ko.observable(user.init_password);
    };

    var HallModel = function (mappingModel, hallData) {
        var self = mappingModel;
        self.map = ko.observable(new MapModel(hallData.map || {}));
        self.user = ko.observable(new UserModel(hallData.users.length > 0 ? hallData.users[0] : {}));
        self.courtGroup = ko.observable(new CourtGroupModel(hallData.court_group || {}));
        self.hall_prices.push(new PriceModel({hall_id: hallData.id}));
        self.hall_markets.push(new MarketModel({hall_id: hallData.id}));
        self.types = [
            {id: 1, name: '节假日'},
            {id: 2, name: '平时'}
        ];
        self.weeks = [
            {id: 1, name: '周一'},
            {id: 2, name: '周二'},
            {id: 3, name: '周三'},
            {id: 4, name: '周四'},
            {id: 5, name: '周五'},
            {id: 6, name: '周六'},
            {id: 7, name: '周日'}
        ];
        self.hours = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24];
        self.images = ko.observable(function(){
            return $.map(hallData.hall_images, function(item){
                return item.path;
            }).join(',');
        }());
        self.images.plupload_cfg = {
            url: '/upload',
            upload_limit: 8,
            filters : [
                {title : "Custom files", extensions : "gif,png,bmp,jpg,jpeg"}
            ],
            max_file_size : '5mb'
        };

        self.generateUser = function () {
            var $user = mapping.toJS(self.user);
            bootbox.confirm('确认要为此场馆添加用户吗？', function (result) {
                if (!result) {
                    return;
                }
                var defer = $.restPost('/hall/generateUser/' + self.id(), $user);
                defer.done(function (res, data) {
                    window.location.reload();
                });
            });
        };

        self.saveMap = function () {
            var $map = mapping.toJS(self.map);
            var defer = $.restPost('/hall/saveMap/' + self.id(), $map);
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.saveCourtGroup = function () {
            var group = mapping.toJS(self.courtGroup);
            var defer = $.restPost('/hall/saveCourtGroup/' + self.id(), group);
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.savePrice = function (data) {
            var price = mapping.toJS(data);
            var defer = $.restPost('/hall/savePrice/' + self.id(), price);
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.deletePrice = function (data) {
            var defer = $.restPost('/hall/deletePrice/' + data.id());
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.saveMarket = function (data) {
            var market = mapping.toJS(data);
            var defer = $.restPost('/hall/saveMarket/' + self.id(), market);
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.deleteMarket = function (data) {
            var defer = $.restPost('/hall/deleteMarket/' + data.id());
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.saveImages = function(data){
            var defer = $.restPost('/hall/saveImages/' + data.id());
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.update = function () {
            var hall = mapping.toJS(self);
            var defer = $.restPost('/hall/update/' + self.id(), hall);
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        return self;
    };

    function init(dom, hallData) {
        var hall = new HallModel(mapping.fromJS(hallData), hallData);
        ko.applyBindings(hall, dom);
    }

    return {
        init: init
    }
});