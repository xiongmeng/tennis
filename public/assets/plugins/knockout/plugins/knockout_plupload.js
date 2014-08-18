define(function(require) {
    var ko = require('knockout');
    require('plupload');

    ko.bindingHandlers.plupload = {
        init: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
            // This will be called when the binding is first applied to an element
            // Set up any initial state, event handlers, etc. here
            var target = valueAccessor(),
                $element = $(element);

            if (!$element.find('*').length) {
                $element.append('<div><a class="js_btn btn_small_important button" href="javascript:;"><span class="btn_wrap">上传</span></a></div><ul class="js_list"></ul>');
            }

            var config = {
                responseParser : function(res) {
                    if (res && res.success || res.code == 1000) {
                        return {
                            path: res.url,
                            id: res.file_id
                        }
                    } else {
                        return {
                            error: res.error || res.msg || '文件上传失败'
                        }
                    }
                },
                onitemschange: function() {
                    var pathArr = $.map(uploader.getItems(), function(item) {
                        if (item) {
                            return item.path;
                        }
                    });
                    console.log(pathArr);
                    target(pathArr.join(','));
                },
                onitemappend: function(up, $list, $item, filepath) {
                    $item.find('.js_link').fancybox();
                    $item.find('img').imageScale({
                        height: 70,
                        width: 70
                    });
                }
            };

            $.extend(config, target.plupload_cfg);

            var uploader = $element.plupload(config);

            $element.data('uploader', uploader);

            //uploader.removeAllItems();
            $.each((target() || '').split(','), function(index, path) {
                uploader.addItem(path);
            });
        },
        update: function(element, valueAccessor, allBindings, viewModel, bindingContext) {
            // This will be called once when the binding is first applied to an element,
            // and again whenever the associated observable changes value.
            // Update the DOM element based on the supplied values here.
            var target = valueAccessor(),
                $element = $(element),
                uploader = $element.data('uploader');

            var paths = target();
            uploader.removeAllItems();
            $.each((paths || '').split(','), function(index, path) {
                uploader.addItem(path);
            });
        }
    };
});