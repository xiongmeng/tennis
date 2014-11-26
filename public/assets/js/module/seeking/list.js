define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    var SeekingModel = require('seeking/seeking');
    var op = require('option');
    require('rest');
    require('knockout_options');

    return function (seekingList, queries, cfg) {
        var self = this;
        self.cfg = {
            model: 'falls', //falls、page,
            per_page: 10
        };
        $.extend(self.cfg, cfg);

        function initListFromJs(seekingList) {
            if (self.cfg.model == 'page') {
                self.seekingList.removeAll()
            }
            $.each(seekingList, function (index, item) {
                self.seekingList.push(new SeekingModel(item));
            })
        }

        function initPageFromJs(data) {
            self.current_page(data.current_page);
            self.last_page(data.last_page);
            self.per_page(data.per_page);
            self.total(data.total);
        }

        var QueryModel = function (queries) {
            var self = this;
            self.state = ko.observable(queries.state);
            self.hall_name = ko.observable(queries.hall_name);
            self.tennis_level = ko.observable(queries.tennis_level).extend({options: op.tennis_level});
            self.event_date = ko.observable(queries.event_date).extend({options: op.event_date});
        };

        self.seekingList = ko.observableArray();
        initListFromJs(seekingList || {});

        self.queries = new QueryModel(queries || {});

        self.current_page = ko.observable(1);
        self.last_page = ko.observable();
        self.per_page = ko.observable(self.cfg.per_page);
        self.total = ko.observable();

        self.inSearching = ko.observable(false);

        var doSearch = function (page) {
            var queries = mapping.toJS(self.queries);
            queries.page = page || self.current_page();
            queries.per_page = self.per_page();

            self.inSearching(true);

            var defer = $.restGet('/seeking/search', queries);
            defer.done(function (res, data) {
                initPageFromJs(data);
                initListFromJs(data.data);
                self.inSearching(false);
            });
        };

        self.loadNextPage = function () {
            doSearch(self.current_page() + 1);
        };
        self.search = function(){
            self.seekingList.removeAll();
            doSearch(1);
        };

        self.clear = function () {
            initListFromJs({});
        };
        return self;
    };
});