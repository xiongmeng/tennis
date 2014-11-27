define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('rest');
    require('bootbox');
    require('knockout_plupload');
    require('knockout_area');

    var ImageModel = function (image) {
        var self = this;
        self.id = ko.observable(image.id);
        self.hall_id = ko.observable(image.hall_id);
        self.path = ko.observable(image.path);
    };

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

    return function (hallData) {
        var self = this;

        self.id = ko.observable(hallData.id);
        self.detail_url = ko.computed(function(){
            return '/hall/detail/' + self.id();
        });
        self.name = ko.observable(hallData.name);
        self.code = ko.observable(hallData.code);
        self.telephone = ko.observable(hallData.telephone);
        self.linkman = ko.observable(hallData.linkman);
        self.area_text = ko.observable(hallData.area_text);
        self.sort = ko.observable(hallData.sort);
        self.business = ko.observable(hallData.business);

        function calcBusinessStartAndEnd(business){
            self.business.start('');self.business.end('');
            if(business){
                var a = business.split('-');
                self.business.start(parseInt(a[0]));
                self.business.end(parseInt(a[1]));
            }
        }
        self.business.start = ko.observable();
        self.business.end = ko.observable();
        self.business.subscribe(function(newValue){calcBusinessStartAndEnd(newValue);});
        calcBusinessStartAndEnd(hallData.business);

        self.air = ko.observable(hallData.air);
        self.bath = ko.observable(hallData.bath);
        self.park = ko.observable(hallData.park);
        self.thread = ko.observable(hallData.thread);
        self.good = ko.observable(hallData.good);
        self.comment = ko.observable(hallData.comment);
        self.court_name = ko.observable(hallData.court_name);
        self.court_num = ko.observable(hallData.court_num);

        self.map = ko.observable(new MapModel(hallData.map || {}));
        self.user = ko.observable(new UserModel((hallData.users && hallData.users.length > 0) ? hallData.users[0] : {}));
        self.courtGroup = ko.observable(new CourtGroupModel(hallData.court_group || {}));
        self.hall_prices = ko.observableArray(function(){
            var prices = [];
            $.each(hallData.hall_prices || {}, function(index, item){
                prices.push(new PriceModel(item));
            });
            prices.push(new PriceModel({hall_id: hallData.id}));
            return prices;
        }());

        self.hall_markets = ko.observableArray(function(){
            var prices = [];
            $.each(hallData.hall_markets || {}, function(index, item){
                prices.push(new MarketModel(item));
            });
            prices.push(new MarketModel({hall_id: hallData.id}));
            return prices;
        }());

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

        self.image = ko.observable(hallData.image);
        self.images = ko.observable();
        self.images.plupload_cfg = {
            url: '/hall/saveImage/' + hallData.id,
            upload_limit: 8,
            filters : [
                {title : "Custom files", extensions : "gif,png,bmp,jpg,jpeg"}
            ],
            max_file_size : '5mb',
            responseParser : function(res) {
                if(res && res.code == 1000){
                    window.location.reload();
//                    self.hall_images.push(new ImageModel(res.data));
                }
                return true;
            }
        };
        self.hall_images = ko.observableArray(function(){
            var prices = [];
            $.each(hallData.hall_images || {}, function(index, item){
                prices.push(new ImageModel(item));
            });
            prices.push(new ImageModel({hall_id: hallData.id}));
            return prices;
        }());

        self.area = ko.observable(hallData.area).extend({area:hallData});

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

        self.deleteImage = function(data){
            var defer = $.restPost('/hall/deleteImage/' + data.id());
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.setEnvelope = function(data){
            var defer = $.restPost('/hall/setEnvelope/' + data.hall_id() + '/' + data.id());
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        self.update = function () {
            var data = {};
            data.name = self.name();
            data.code = self.code();
            data.telephone = self.telephone();
            data.linkman = self.linkman();
            data.province = self.area.province();
            data.city = self.area.city();
            data.county = self.area.county();

            data.sort = self.sort();
            data.business = self.business();
            data.air = self.air();
            data.bath = self.bath();
            data.park = self.park();
            data.thread = self.thread();
            data.good = self.good();
            data.comment = self.comment();

            var defer = $.restPost('/hall/update/' + self.id(), data);
            defer.done(function (res, data) {
                window.location.reload();
            });
        };

        return self;
    };
});