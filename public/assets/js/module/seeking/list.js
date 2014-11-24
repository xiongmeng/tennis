define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    var SeekingModel = require('seeking/seeking');
    require('rest');

    return function (seekingList, queries) {
        var self = this;

        function initListFromJs(seekingList){
            self.seekingList.removeAll();
            $.each(seekingList, function(index, item){
                self.seekingList.push(new SeekingModel(item));
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

        self.seekingList = ko.observableArray();
        initListFromJs(seekingList || {});

        self.queries = new QueryModel(queries || {});

        self.search = function(){
            var queries = mapping.toJS(self.queries);
            var defer = $.restGet('/seeking/search', queries);
            defer.done(function(res, data){
                initListFromJs(data.data);
            });
        };

        self.clear = function(){
            initListFromJs({});
        };
        return self;
    };
});