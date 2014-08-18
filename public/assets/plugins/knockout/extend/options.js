define(function(require) {
    var ko = require('knockout');
    ko.extenders.options = function(target, options) {
        target.options = options || [
            {id: 1, name:'是'},
            {id: 2, name:'否'}
        ];
        target.text = ko.computed(function(){
            var name = '数据不足';
            var value = target();
            $.each(options, function(i, e){
                if(e.id == value){
                    name = e.name;
                }
            });
            return name;
        });

        return target;
    };
});