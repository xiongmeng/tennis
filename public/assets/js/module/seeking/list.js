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
            perPage: 10
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
            self.currentPage(data.currentPage);
            self.lastPage(data.lastPage);
            self.perPage(data.perPage);
            self.total(data.total);
        }

        var QueryModel = function (queries) {
            var self = this;
            self.hall_name = ko.observable(queries.hall_name);
            self.tennis_level = ko.observable(queries.tennis_level).extend({options: op.tennis_level});
            self.event_date = ko.observable(queries.event_date).extend({options: op.event_date});
        };

        self.seekingList = ko.observableArray();
        initListFromJs(seekingList || {});

        self.queries = new QueryModel(queries || {});

        self.currentPage = ko.observable(1);
        self.lastPage = ko.observable();
        self.perPage = ko.observable(self.cfg.perPage);
        self.total = ko.observable();

        self.inSearching = ko.observable(false);

        var doSearch = function (page) {
            var queries = mapping.toJS(self.queries);
            queries.page = page || self.currentPage();
            queries.perPage = self.perPage();

            self.inSearching(true);

            var defer = $.restGet('/seeking/search', queries);
            defer.done(function (res, data) {
                initPageFromJs(data);
                initListFromJs(data.data);
                self.inSearching(false);
            });
        };

        self.loadNextPage = function () {
            doSearch(self.currentPage() + 1);
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