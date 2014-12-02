var lib_last_modify = '201412022201',
    module_common_last_modify = '201412022201';

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
        knockout_plupload: 'plugin/knockout/plugins/knockout_plupload.js',
        knockout_area: 'plugin/knockout/extend/area.js',
        knockout_phpTsToDate: 'plugin/knockout/extend/phpTsToDate.js',
        knockout_options: 'plugin/knockout/extend/options.js',
        plupload: 'plugin/plupload/plupload_cmd.js',
        doT:    'plugin/doT/doT_cmd.js',
        fancybox: 'plugin/fancybox/fancybox_cmd.js',
        weiboyi: 'plugin/weiboyi/weiboyi_all_cmd',
        datetimePicker: 'plugin/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker.cmd.js',
        combobox: 'plugin/combobox/js/bootstrap-combobox-cmd.js',
        kkcountdown:'plugin/kkcountdown/js/build/kkcountdown.js',
        bootbox:'plugin/bootbox/bootbox.min.js'
    },
    charset: 'utf-8',
    map: [
        [/(.*\/assets\/plugins\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + lib_last_modify]
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
