define(function(require){
    var ko = require('knockout');
    var mapping = require('knockout_mapping');
    require('knockout_switch_case');
    require('rest');

    var option = {
        'submitUrl' : ''
    };

    function object2Array(obj) {
        return obj ? $.map(obj, function(v, k) {return {id: k, name: v}}) : {};
    }

    var ListModel = function(mappingModel){
        var self = mappingModel;
        self.event = ko.observable();

        self.event.subscribe(function(newSelected){

        });

        self.channel = ko.observable();
        self.who = ko.observable();
        self.msg = ko.observable();

        self.refresh = ko.computed(function(){
            getRecord();
        });

        function getRecord(){
            self.who('');self.msg('');
            if(self.event() && self.channel() && self.object()){
                var defer = $.restGet('/notify/getRecord',
                    {'event' : self.event(), 'channel' : self.channel(), 'object' : self.object()});

                defer.done(function(res, data){
                    self.who(data.who);
                    self.msg(data.msg);
                });
                defer.fail(function(){
                    console.log(arguments);
                });
            }
        }

        self.send = function(){
            if(self.event() && self.channel() && self.object() && self.who() && self.msg()){
                if(confirm('确认要发送此通知吗？')){
                    var defer = $.restPost('/notify/send',
                        {'event' : self.event(), 'channel' : self.channel(),
                            'object' : self.object(), 'who' : self.who(), 'msg' : self.msg()});

                    defer.done(function(res, data){
                        window.location.href = '/notify/record';
                    });
                    defer.fail(function(){
                        console.log(arguments);
                    });
                }
            }
        };

        return self;
    };

    function init(dom, worktableData, cfg){
        $.extend(option, cfg);
        worktableData.events = object2Array(worktableData.events);
        worktableData.channels = object2Array(worktableData.channels);

        var list = new ListModel(mapping.fromJS(worktableData));
        ko.applyBindings(list, dom);
    }

    return {
        init : init
    }
});