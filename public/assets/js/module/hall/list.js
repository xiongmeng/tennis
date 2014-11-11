define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    var HallModel = require('hall/hall');
    require('rest');
    require('knockout_area');

    return function (hallList, queries) {
        var self = this;

        function initListFromJs(hallList){
            self.hallList.removeAll();
            $.each(hallList, function(index, item){
                self.hallList.push(new HallModel(item));
            })
        }

        var QueryModel = function(queries){
            var self = this;
            self.id = ko.observable(queries.id);
            self.name = ko.observable(queries.name);
            self.area = ko.observable().extend({area: queries});
            self.court_name = ko.observable(queries.court_name);
            self.area_text = ko.observable(queries.area_text);
        };

        self.hallList = ko.observableArray();
        initListFromJs(hallList || {});

        self.queries = new QueryModel(queries || {});

        self.search = function(){
            var queries = mapping.toJS(self.queries);
            var defer = $.restGet('/hall/list/all', queries);
            defer.done(function(res, data){
                initListFromJs(data.halls.data);
            });
        };

        self.clear = function(){
            initListFromJs({});
        };
        return self;
    };
});