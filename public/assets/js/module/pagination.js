define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');

    return function (dataModel, queryModel, cfg) {
        var self = this;
        self.cfg = {
            model: 'falls', //falls、page,
            per_page: 10,
            url: '',
            relations:''
        };
        $.extend(self.cfg, cfg);

        self.total = ko.observable();
        self.per_page = ko.observable(cfg.per_page);
        self.current_page = ko.observable(1);
        self.last_page = ko.observable();
        self.from = ko.observable();
        self.to = ko.observable();
        self.data = ko.observableArray();
        self.queries = new queryModel({});

        function getPageRange(pages, start, end){
            for (i = start; i <= end; i++) {
                pages.push({name:i});
            }
        }

        function getDot(res){
            res.push({name:'...'});
        }

        function slidePage(){
            var res = [];
            var last_page = self.last_page();
            var current_page = self.current_page();
            if (last_page < 13) {
                getPageRange(res, 1, last_page);
            } else {
                var window = 6;
                if(current_page <= window){
                    getPageRange(res, 1, window + 2);
                    getDot(res);
                    getPageRange(res, last_page-1, last_page);
                }else if(current_page >= last_page - window){
                    getPageRange(res, 1, 2);
                    getDot(res);
                    getPageRange(res, last_page-8, last_page);
                }else{
                    getPageRange(res, 1, 2);
                    getDot(res);
                    getPageRange(res,current_page -3 ,current_page+3);
                    getDot(res);
                    getPageRange(res, last_page-1, last_page);
                }
            }
            return res;
        }
        self.pages = ko.observableArray();

        self.inSearching = ko.observable(false);

        self.fillData = function (pagination) {
            self.current_page(pagination.current_page);
            self.last_page(pagination.last_page);
            self.per_page(pagination.per_page);
            self.total(pagination.total);
            self.from(pagination.from);
            self.to(pagination.to);

            $.each(pagination.data || [], function (index, item) {
                self.data.push(new dataModel(item));
            });

            self.pages.removeAll();
            self.pages(slidePage());
        };

        var doSearch = function (page) {
            if (self.cfg.model == 'page') {
                self.data.removeAll();
            }

            var queries = mapping.toJS(self.queries);
            queries.page = page || self.current_page();
            queries.per_page = self.cfg.per_page;
            queries.relations = self.cfg.relations;

            self.inSearching(true);

            var defer = $.restGet(self.cfg.url, queries);
            defer.done(function (res, data) {
                self.fillData(data);

                self.inSearching(false);
            });
        };

        self.pre = function () {
            doSearch(self.current_page() - 1);
        };
        self.next = function () {
            doSearch(self.current_page() + 1);
        };
        self.search = function () {
            doSearch(1);
        };
        self.go = function(data){
            doSearch(data.name);
        };
        self.clear = function () {
            self.fillData({});
        };

        return self;
    };
});