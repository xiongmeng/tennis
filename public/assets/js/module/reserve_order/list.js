define(function (require) {
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    var ReserveOrderModel = require('reserve_order/order');
    var Pagination = require('pagination');
    require('rest');
    require('knockout_area');

    return function (cfg) {
        var QueryModel = function (queries) {
            var self = this;
            self.id = ko.observable(queries.id);
            self.hall_name = ko.observable(queries.name);
            self.stat = ko.observable(queries.stat);
        };
        cfg.per_page = 20;
        cfg.url || (cfg.url = '/reserve/search');

        var self = new Pagination(ReserveOrderModel, QueryModel, cfg);

        return self;
    };
});