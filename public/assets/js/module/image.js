define(function (require) {
    var ko = require('knockout');
    ko.extenders.image = function(target, option) {
        target.full_path = ko.computed(function(){
            var defaultSrc = option.defaultImage || '/resources/images/weixin_no_img_default.gif';
            var src = target();
            return src ? option.viewBasePath + src : defaultSrc;
        });

        target.plupload_cfg = {
            url: '/upload',
            upload_limit: 8,
            viewBasePath: '/uploadfiles/',
            filters : [
                {title : "Custom files", extensions : "gif,png,bmp,jpg,jpeg"}
            ],
            max_file_size : '5mb',
            responseParser: function(res) {
                if (res && res.success || res.code == 1000) {
                    return {
                        path: res.filename || res.data.filename,
                        id: res.file_id
                    }
                } else {
                    return {
                        error: res.error || res.msg || '文件上传失败'
                    }
                }
            }
        };

        $.extend(target.plupload_cfg, option);

        return target;
    };

    /**
     * 对应用户的model数据
     * @constructor
     */
    function ImageModel(image) {
        var self = this;
        var imagePath = '/img/uploadimg/weixin_follower_img/';
        self.id = ko.observable(image.id);
        self.name = image.name;
        self.path = ko.observable(image.path);
        self.src = ko.computed({
                read : function () {
                    return self.path() ? imagePath + self.path() : '/img/miss.jpg';
                },
                write: function(value){
                    var lastIndexOf = value.lastIndexOf(imagePath);
                    if (lastIndexOf != -1) {
                        self.path(value.substring(lastIndexOf + imagePath.length));
                    }else{
                        self.path('');
                    }
                }
            });
        self.key = function(){
            switch (image.id){
                case 101:
                    return 'screen_shot_followers';
                case 102:
                    return 'screen_portrait';
                case 103:
                    return 'screen_shot_qr_code';
                case 104:
                    return 'screen_shot_info';
                default :
                    return '';
            }
        }();
    }

    return ImageModel;
});

