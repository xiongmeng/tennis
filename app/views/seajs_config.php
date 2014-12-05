<script type="text/javascript" src="/assets/plugins/seajs/sea.js"></script>

<script type="text/javascript">
    var jsVersion = '201412031500';
    seajs.config({
        paths: {
            plugin: '/assets/plugins',
            module: '/assets/js/module'
        },
        base: '/assets/js/module/',
        charset: 'utf-8',
        alias: {
            rest: 'plugin/rest/rest_cmd.js',
            knockout: 'plugin/knockout/knockout.cmd.js',
            knockout_mapping: 'plugin/knockout/plugins/mapping_debug.js',
            knockout_switch_case: 'plugin/knockout/plugins/knockout-switch-case.min.js',
            knockout_plupload: 'plugin/knockout/plugins/knockout_plupload.js',
            knockout_area: 'plugin/knockout/extend/area.js',
            knockout_phpTsToDate: 'plugin/knockout/extend/phpTsToDate.js',
            knockout_options: 'plugin/knockout/extend/options.js',
            plupload: 'plugin/plupload/plupload_cmd.js',
            doT: 'plugin/doT/doT_cmd.js',
            fancybox: 'plugin/fancybox/fancybox_cmd.js',
            weiboyi: 'plugin/weiboyi/weiboyi_all_cmd',
            datetimePicker: 'plugin/smalot-bootstrap-datetimepicker/js/bootstrap-datetimepicker.cmd.js',
            combobox: 'plugin/combobox/js/bootstrap-combobox-cmd.js',
            kkcountdown: 'plugin/kkcountdown/js/build/kkcountdown.js',
            bootbox: 'plugin/bootbox/bootbox.min.js'
        },
        map: [
            [/(.*\/assets\/js\/module\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + jsVersion],
            [/(.*\/mobile\/js\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + jsVersion],
            [/(.*\/assets\/plugins\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + jsVersion]
        ]
    });

    /**
     * 已经加载的模块直接执行，这里是同步阻塞的执行
     * @param id
     * @returns {*}
     */
    seajs.execImmediateInCache = function (id) {
        var mod = seajs.cache[seajs.resolve(id)];
        if (mod) {
            return mod.exec();
        }
        return null;
    };
</script>

<?php if (!debug()) { ?>
    <?php
    function getModuleHashInfo(){
        $hashInfo = json_decode(file_get_contents(public_path() . '/assets/hash_info.json'), true);

        if ($hashInfo == null) {
            return array(
                'assets/module.min.js' => '',
                'assets/plugin.min.js' => ''
            );
        }

        $res = array();
        foreach($hashInfo as $file => $ts){
            $res[] = '/' . $file . '?' . $ts;
        }
        return $res;
    }

    ?>
    <script>
            seajs.config({
            preload: <?= json_encode(getModuleHashInfo())?>
        });
    </script>
<?php } ?>