define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    var HallModel = require('hall/hall');
    var Pagination = require('pagination');
    require('rest');
    require('knockout_area');

    return function (cfg) {
        var QueryModel = function (queries) {
            var self = this;
            self.id = ko.observable(queries.id);
            self.name = ko.observable(queries.name);
            self.area = ko.observable().extend({area: queries});
            self.court_name = ko.observable(queries.court_name);
            self.area_text = ko.observable(queries.area_text);
            self.stat = ko.observable(queries.stat);
        };
        cfg.url || (cfg.url = '/hall/search');

        var self = new Pagination(HallModel, QueryModel, cfg);

        return self;
    };
});