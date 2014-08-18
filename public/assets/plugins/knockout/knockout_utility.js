define(function(require) {
    var ko = require('knockout');
    require('./plugins/knockout_placeholder.js');

    require('./extend/numeric.js');
    require('./extend/options.js');
    require('./extend/phpTsToDate.js');
});