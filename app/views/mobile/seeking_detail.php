<div class="content" style="margin-bottom: 50px; padding-top: 20px">

    <?php $levels = option_tennis_level(); ?>
    <?php $sexy = option_sexy(); ?>
    <?php $states = option_seeking_state(); ?>
    <?php $seeking instanceof Seeking && 1; ?>

    <ul class="table-view" style="color: #777">
        <li class="table-view-cell">
            <label>状态：</label>
            <label><?= $states[$seeking->state] ?></label>
        </li>
        <li class="table-view-cell">
            <label>时间：</label>
            <label><?=
                sprintf('%s日 %s时', substr($seeking->event_date, 0, 10),
                    display_time_interval($seeking->start_hour, $seeking->end_hour))?></label>
        </li>
        <li class="table-view-cell" style="padding-right: 0">
            <label>场馆：</label>
            <label><?= $seeking->Hall->name ?></label>
        </li>
        <li class="table-view-cell">
            <label>场地：</label>
            <label><?= $seeking->court_num ?>片</label>
        </li>
        <li class="table-view-cell">
            <label>坑位：</label>
            <label><?= sprintf('共%s坑，剩余%s坑', $seeking->store, $seeking->on_sale) ?></label>
        </li>
        <li class="table-view-cell">
            <label>级别：</label>
            <label><?= $levels[$seeking->tennis_level] ?>
        </li>
        <?php if ($seeking->sexy != -1) { ?>
            <li class="table-view-cell">
                <label>性别：</label>
                <label><?= $sexy[$seeking->sexy] ?>
            </li>
        <?php } ?>
        <li class="table-view-cell">
            <label>费用：</label>
            <label>人均<?= $seeking->personal_cost ?>元</label>
        </li>
        <?php if (!empty($seeking->comment)) { ?>
            <li class="table-view-cell">
                <label>备注：</label>
                <label><?= $seeking->comment ?></label>
            </li>
        <?php } ?>

        <?php if (count($orders) > 0) { ?>
            <li class="table-view-cell" style="padding-right: 0">
                <label>已经报名的球友：</label>
                <label>
                    <?php foreach ($orders as $order) { ?>
                        <span style="color: royalblue; padding-right: 10px"><?= $order->Joiner->nickname ?></span>
                    <?php } ?>
                </label>
            </li>
        <?php } else { ?>
            <li class="table-view-cell" style="padding-right: 0">
                <label>目前还没有球友报名哦</label>
            </li>
        <?php } ?>
    </ul>
    <?php $fsm = new SeekingFsm($seeking); ?>
    <?php if ($fsm->can('join')) { ?>
        <a style="width:90%; margin: 5px auto ;" class="btn btn-primary btn-block"
           href="/seeking/join/<?= $seeking->id ?>" data-ignore="push">我要报名</a>
    <?php } ?>

    <a style="width:90%; margin: 5px auto ;" class="btn btn-primary btn-block"
       href="/seeking/list" data-ignore="push">查看更多约球信息</a>


    <p style="padding: 10px 20px; text-indent: 40px; color: indianred; font-weight: bolder; font-size: 20px">
        约球有你，点击右上角“发送给朋友”，“分享到朋友圈”，大家一起来约球！
    </p>

</div>

<script type="text/javascript">
    <?php $title = seeking_brief($seeking);?>
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        // 发送给好友
        WeixinJSBridge.on('menu:share:appmessage', function (argv) {
            WeixinJSBridge.invoke('sendAppMessage', {
                "appid": "123",
                "img_url": "<?= hall_head_wechat($seeking->hall_id)?>",
                "img_width": "200",
                "img_height": "200",
                "link": "<?= URL::current()?>",
                "desc": "<?= $title?>",
                "title": "<?= $title?>"
            }, function (res) {
//                alert(res.err_msg);
            })
        });

        // 分享到朋友圈
        WeixinJSBridge.on('menu:share:timeline', function (argv) {
            WeixinJSBridge.invoke('shareTimeline', {
                "img_url": "<?= hall_head_wechat($seeking->hall_id)?>",
                "img_width": "200",
                "img_height": "200",
                "link": "<?= URL::current()?>",
                "desc": "<?= $title?>",
                "title": "<?= $title?>"
            }, function (res) {
//                alert(res.err_msg);
            });
        });
    }, false)
</script>

