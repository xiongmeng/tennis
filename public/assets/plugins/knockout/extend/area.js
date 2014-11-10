define(function (require) {
    var ko = require('knockout');
    ko.extenders.area = function (target, cfg) {
        target.province = ko.observable(cfg.province);
        target.provinces = ko.observableArray(
            [
                {id: '110000', name: '北京市'},
                {id: '120000', name: '天津市'},
                {id: '130000', name: '河北省'},
                {id: '140000', name: '山西省'},
                {id: '150000', name: '内蒙古自治区'},
                {id: '210000', name: '辽宁省'},
                {id: '220000', name: '吉林省'},
                {id: '230000', name: '黑龙江省'},
                {id: '310000', name: '上海市'},
                {id: '320000', name: '江苏省'},
                {id: '330000', name: '浙江省'},
                {id: '340000', name: '安徽省'},
                {id: '350000', name: '福建省'},
                {id: '360000', name: '江西省'},
                {id: '370000', name: '山东省'},
                {id: '410000', name: '河南省'},
                {id: '420000', name: '湖北省'},
                {id: '430000', name: '湖南省'},
                {id: '440000', name: '广东省'},
                {id: '450000', name: '广西壮族自治区'},
                {id: '460000', name: '海南省'},
                {id: '500000', name: '重庆市'},
                {id: '510000', name: '四川省'},
                {id: '520000', name: '贵州省'},
                {id: '530000', name: '云南省'},
                {id: '540000', name: '西藏自治区'},
                {id: '610000', name: '陕西省'},
                {id: '620000', name: '甘肃省'},
                {id: '630000', name: '青海省'},
                {id: '640000', name: '宁夏回族自治区'},
                {id: '650000', name: '新疆维吾尔自治区'},
                {id: '710000', name: '台湾省'},
                {id: '810000', name: '香港特别行政区'},
                {id: '820000', name: '澳门特别行政区'}
            ]
        );
        target.city = ko.observable(cfg.city);
        target.cities = ko.observableArray(cfg.cities);
        target.county = ko.observable(cfg.county);
        target.counties = ko.observableArray(cfg.counties);

        //省份发生改变
        target.province.subscribe(function (newProvince) {
            if(newProvince){
                var defer = $.restGet('/area/cities/' + newProvince);
                defer.done(function (code, cities) {
                    target.cities(cities);
                });
            }
            target.city('');
            target.cities([]);
        });

        //城市发生改变
        target.city.subscribe(function (newCity) {
            if (newCity) {
                var defer = $.restGet('/area/counties/' + newCity);
                defer.done(function (code, countys) {
                    target.counties(countys);
                });
            }
            target.county('');
            target.counties([]);
        });

        //县市发生改变
//        target.county.subscribe(function (newCounty) {
//        });

        return target;
    };
});