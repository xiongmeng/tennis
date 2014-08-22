var lib_last_modify = '2014070815',
    module_common_last_modify = '2014070815';

seajs.config({
    debug: true,
    alias: {
//        'knockout_mapping': 'lib/knockout/plugins/mapping_debug.js',
//        fancybox: 'lib/fancybox/fancybox_cmd.js',
//        'seajs-debug': 'lib/seajs/sea-debug.js'
        rest: 'plugin/rest/rest_cmd.js',
        knockout: 'plugin/knockout/knockout.cmd.js',
        knockout_mapping: 'plugin/knockout/plugins/mapping_debug.js',
        knockout_switch_case: 'plugin/knockout/plugins/knockout-switch-case.min.js',
        datetimePicker: 'plugin/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker.cmd.js',
//        kkcountdown:'plugin/kkcountdown/js/build/kkcountdown.js',
        bootbox:'plugin/bootbox/bootbox.min.js'
    },
    charset: 'utf-8',
    map: [
        [/(.*\/js\/module_common\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + module_common_last_modify],
        [/(.*\/js\/lib\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + lib_last_modify]
    ]
});

/**
 * 已经加载的模块直接执行，这里是同步阻塞的执行
 * @param id
 * @returns {*}
 */
seajs.execImmediateInCache = function(id) {
    var mod = seajs.cache[seajs.resolve(id)];
    if (mod) {
        return mod.exec();
    }
    return null;
};
