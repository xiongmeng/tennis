define(function (require) {
    var ko = require('knockout');
    require('rest');

    var ChangeTelephoneModel = function () {
        self.telephone = ko.observable('');
        self.telephoneError = ko.observable('');
        self.validcodeError = ko.observable('');
        self.ttl = ko.observable(0);
        self.validCodeText = ko.computed(function(){
            return self.ttl() > 0 ? (self.ttl() + '秒后重新获取') : '获取短信验证码';
        });
        self.ttl.subscribe(function(newValue) {
            if(newValue > 0){
                window.setTimeout(function () {
                    self.ttl(self.ttl()-1);
                }, 1000)
            }
        });
        self.inGettingValidCode = false;

        self.bindErrors = function(errors){
            if(errors.telephone && errors.telephone.length >= 1){
                self.telephoneError(errors.telephone[0]);
            }

            if(errors.validcode && errors.validcode.length >= 1){
                self.validcodeError(errors.validcode[0]);
            }
        };

        self.bindValidCode = function(validCode){
            if(validCode && validCode.ttl){
                self.ttl(validCode.ttl);
            }
        };

        self.getValidCode = function(){
            var defer = $.restPost('/telValidCodeMake', {'telephone': self.telephone(), 'not_exists': 1, 'ttl': 5});
            defer.done(function (res, data) {
                if(data.status == 1){
                    self.bindErrors(data.errors);
                }else if(data.status == 2){
                    self.bindValidCode(data.validCode);
                }
            });
            defer.fail(function () {
                console.log(arguments);
            });
        };

        return self;
    };

    function init(dom, data) {
        var model = new ChangeTelephoneModel();
        model.telephone(data.queries.telephone);
        model.bindErrors(data.errors);
        model.bindValidCode(data.validCode);

        ko.applyBindings(model, dom);
    }

    return {
        init: init
    }
});