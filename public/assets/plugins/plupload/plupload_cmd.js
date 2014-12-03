define(function(require) {
    var plupload = require('./plupload.full.js');
    require('./uploadimages.css');

    var imagesTpl = '<li class="upload_item js_item">' +
        '<div class="upload_item_wrap">' +
        '<a class="upload_item_link js_link js_progressctnr js_thumb" href="javascript:;" target="_blank">' +
        '</a>' +
        '</div>' +
        '<a href="javascript:void(0)" class="js_removeitem">删除</a>' +
        '</li>';

    var attachmentsTpl = '<li class="uploadattach_item js_item">' +
        '<div class="uploadattach_item_wrap">' +
        '<a class="uploadattach_item_link js_link" href="javascript:;" target="_blank">' +
        '<span class="js_progressctnr"></span>' +
        '<span class="js_filename"></span>' +
        '</a>' +
        '</div>' +
        '<a href="javascript:void(0)" class="js_removeitem uploadattach_remove">删除</a>' +
        '</li>';

    function getAutoID(prefix) {
        this.autoID = this.autoID || 0;
        this.autoID++;
        return (prefix || "weiboyiCmp") + this.autoID;
    }

    function processAutoID($el) {
        if ($el.attr('id')) {
            return $el.attr('id')
        } else {
            var id = getAutoID('plupload');
            $el.attr('id', id);
            return id;
        }
    }

    function processToBeUploadItem(tpl, id, name) {
        var $item = $(tpl);
        var $progressCtnr = $item.find('.js_progressctnr');
        var $name = $item.find('.js_filename');
        $progressCtnr.append('<span class="upload_progress" id="progress' + id + '"></span>');
        $name.attr('title', name).text(name);
        return $item;
    }

    $.fn.plupload = function(settings) {
        var opts = {
            template: {
                images: imagesTpl,
                attachments: attachmentsTpl
            },
            type: 'images', // [images|attachments]
            runtimes : 'html5,flash,silverlight,browserplus',
            browse_button : 'updateMultipleLinkBtn',
            flash_swf_url: '/js/lib/plupload/plupload.flash.swf',
            upload_limit: 10,
            container: 'updateMultipleLink',
            max_file_size : '3mb',
            url : null,
            file_data_name: 'qqfile',
            multipart_params: {
                web_csrf_token: $('#web_csrf_token').val()
            },
            filters : [
                {title : "Image files", extensions : "jpg,gif,png,jpeg"}
            ],
            viewBasePath: '',
            onitemschange: $.noop,
            onitemappend: $.noop,
            responseParser: function(res) {
                if (res && res.success || res.code == 1000) {
                    return {
                        path: '/' + (res.filename || res.data.filename),
                        id: res.file_id
                    }
                } else {
                    return {
                        error: res.error || res.msg || '文件上传失败'
                    }
                }
            }
        };

        if (settings && settings.multipart_params) {
            settings.multipart_params.web_csrf_token = $('#web_csrf_token').val();
        }

        settings = $.extend(opts, settings);

        var self = this;

        var items = [], disabled = false;
        var $btn = self.find('.js_btn');
        var $list = self.find('.js_list');
        var $ctnr = $btn.parent();

        settings.browse_button = processAutoID($btn);
        settings.container = processAutoID($ctnr);

        $list.on('click', '.js_removeitem', function() {
            removeItem(this);
            return false;
        });

        var pluploader = new plupload.Uploader(settings), uploader;


        pluploader.init();

        pluploader.bind('Init', function(up) {
            if (disabled) {
                up.disableBrowse(true)
            }
        });


        var timer, errors = [];
        pluploader.bind('Error', function(up, error) {
            var msg = {
                '-600': '文件{0}上传失败，大小超过限制',
                '-601': '文件{0}上传失败，文件类型错误',
                '-200': '文件{0}上传失败，Http错误'
            } [error.code] || '文件{0}上传失败，' + error.message;

            errors.push(W.util.formatStr(msg, error.file.name));

            clearTimeout(timer);
            timer = setTimeout(function() {
                if (errors.length > 4) {
                    alert(errors.length + '个文件上传失败！', 'error');
                } else {
                    alert(errors.join('<br/>'), 'error');
                }
                errors = [];
            }, 100);

            var $progress = $list.find('#progress' + error.file.id);
            var $item = $progress.closest('.js_item');

            $item.remove();
            up.removeFile(up.getFile(error.file.id));
        });

        pluploader.bind('FilesAdded', function(up, files) {

            var uploaded = $list.find('.js_item').length;
            files = $.grep(files, function(file) {
                return file.status === 1;
            });

            while (files.length > settings.upload_limit - uploaded) {
                var file = files.pop();
                up.removeFile(file);
            }

            var tpl = settings.type === 'images' ? settings.template.images : settings.template.attachments;

            $.each(files, function (i, file) {
                $list.append(processToBeUploadItem(tpl, file.id, file.name));
            });

            pluploader.start();
        });

        pluploader.bind('UploadProgress', function(up, file) {
            var $progress = $list.find('#progress' + file.id);
            $progress.css({
                width: file.percent + '%'
            });
        });

        pluploader.bind('FileUploaded', function(up, file, res) {
            var $progress = $list.find('#progress' + file.id);
            var $item = $progress.closest('.js_item');

            $progress.remove();
            if (res.response) {
                var result = {};
                try {
                    result = $.parseJSON(res.response);
                } catch(e) {
                    result = {
                        error: '上传失败，返回数据格式错误！'
                    };
                }
                var r = settings.responseParser(result);

                if(r === true){
                    return;
                }
                if (r.path || r.id) {
                    var src = r.path;
                    var $thumb = $item.find('.js_thumb');
                    if ($thumb.length) {
                        var $img = $('<img class="js-uploadimg" src="' + settings.viewBasePath + r.path + '">');
                        $thumb.append($img);
                    }

                    processItem($item, r.path, r.id, result);
                    settings.onitemschange.apply(self, [uploader, $list, result]);
                    return;
                } else{
                    alert(r.error || '文件上传失败', 'error');
                }
            }

            $item.remove();
            up.removeFile(up.getFile(file.id));
            settings.onitemschange.apply(self, [uploader, $list]);
        });

        function addItem(filepath, id, name) {
            if (!filepath) {
                return;
            }


            if (items.length >= settings.upload_limit) {
                return;
            }

            if ($.inArray(filepath, items) >= 0) {
                return;
            }
            var tpl = settings.type === 'images' ? settings.template.images : settings.template.attachments;
            var $item = $(tpl);

            var $thumb = $item.find('.js_thumb');
            if ($thumb.length) {
                var $img = $('<img class="js-uploadimg" src="' + settings.viewBasePath + filepath + '">');
                $thumb.append($img);
            }
            name = name || '附件';
            $item.find('.js_filename').attr('title', name).text(name);
            $list.append($item);
            processItem($item, filepath, id);
            settings.onitemschange.apply(self, [uploader, $list]);
        }

        function disable() {
            disabled = true;
            pluploader.disableBrowse(true);
            $btn.addClass('btn_small_disabled');
        }

        function enable() {
            if (items.length < settings.upload_limit) {
                disabled = false;
                pluploader.disableBrowse(false);
                $btn.removeClass('btn_small_disabled');
            }
        }

        function processItem($item, filepath, id, res) {
            $item.find('.js_link').attr({
                'href': settings.viewBasePath + filepath,
                'data-path': filepath
            });

            items.push({
                path: filepath,
                id: id
            });

            settings.onitemappend.apply(self, [uploader, $list, $item, filepath, res]);

            if (items.length >= settings.upload_limit) {
                disable()
            }
        }

        function removeItem(mix) {
            var path, $item;
            if (typeof mix === 'string') {
                path = mix;
                $item = $list.find('[data-path="' + path + '"]').closest('.js_item');
            } else {
                var $item = $(mix).closest('.js_item');
                path = $item.find('[data-path]').attr('data-path');
            }

            var $progress = $item.find('.upload_progress');
            if ($progress.length) {
                var id = $progress.attr('id').replace(/^progress/, '');
                if (id) {
                    pluploader.removeFile(pluploader.getFile(id));
                }
            }

            $item.remove();

            items = $.grep(items, function(item) {
                return item.path != path;
            });

            settings.onitemschange.apply(self, [uploader, $list]);

            if (items.length < settings.upload_limit) {
                enable();
            }
        }

        function removeAllItems() {
            $.each(items || [], function(index, item) {
                removeItem(item.path);
            });
        }

        /**
         * 获取上传中和待上传的文件队列
         * @returns {*}
         */
        function getUploadingQueue() {
            return $.grep(pluploader.files, function(file) {
                if (file.status === plupload.QUEUED || file.status === plupload.UPLOADING) {
                    return true;
                }
            });
        }

        function setParams(params)  {
            $.extend(pluploader.settings.multipart_params, params);
            pluploader.refresh();
        }

        function setUploadLimit(limit) {
            settings.upload_limit = limit;
            pluploader.refresh();

            // 去掉多余的模块
            for (var index = limit; index < items.length; index++) {
                removeItem(items[index].id);
            }
        }

        uploader = {
            serialize: function() {
                return $list.find('a[data-path]').map(function(index, item) {
                    return {
                        name: 'link[' + index + ']',
                        value: $(item).attr('data-path').replace(/^\//, '')
                    };
                }).toArray();
            },
            setUploadLimit: setUploadLimit,
            getItems: function() {
                return items;
            },
            addItem: addItem,
            removeItem: removeItem,
            removeAllItems: removeAllItems,
            enable: enable,
            disable: disable,
            getUploadingQueue: getUploadingQueue,
            setParams: setParams
        };

        return uploader;
    };

});