define(function(require) {
    var ko = require('knockout');
    ko.extenders.area = function(target, cfg) {

        target.country = ko.observable();
        target.country_option = ko.observableArray([{id:101, name: '中国'},{id: 500, name: '其他'}]);
        target.province = ko.observable();
        target.province_option = ko.observableArray();
        target.city = ko.observable();
        target.city_option = ko.observableArray();
        target.district = ko.observable();
        target.district_option = ko.observableArray();

        option = {
            url : '',
            valueMaker : function(){
                var district = target.district.peek();
                var city = target.city.peek();
                var country = target.country.peek();
                if(country == 500){
                    target(country);
                    return;
                }
                target(district || city ||  '');
            }
        };

        $.extend(option, cfg);

        //国家发生改变
        target.country.subscribe(function(newCountry){
            if(newCountry){
                var defer = $.restGet(option.url, {area_id : newCountry});
                defer.done(function(code, provinces){
                    target.province_option(provinces);
                });
            }
            target.province('');
            target.province_option([]);
        });

        //省份发生改变
        target.province.subscribe(function(newProvince){
            var strProvince = '' + newProvince;
            var defer;
            if(strProvince.length == 7){
                var newCity = newProvince;
                var actualProvince = strProvince.substr(0,5);
                defer = $.restGet(option.url, {area_id : actualProvince});
                defer.done(function(code, cities){
                    target.city_option(cities);
                    target.city(newCity);
                });
            }else {
                if(strProvince){
                    defer = $.restGet(option.url, {area_id : newProvince});
                    defer.done(function(code, cities){
                        target.city_option(cities);
                    });
                }
                target.city('');
                target.city_option([]);
            }
        });

        //城市发生改变
        target.city.subscribe(function(newCity){
            if(newCity){
                var defer = $.restGet(option.url, {area_id : newCity});
                defer.done(function(code, districts){
                    target.district_option(districts);
                });
            }
            target.district('');
            target.district_option([]);

            option.valueMaker();
        });

        //县市发生改变
        target.district.subscribe(function(newDistrict){
            option.valueMaker();
        });

        return target;
    };
});