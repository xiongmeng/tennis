<div class="container worktable" id="worktable">
    <div class="row halls">
        <div class="tab-v2 col-xs-12 col-md-12">
            <ul class="nav nav-tabs" style="border-bottom: solid 1px #dddddd">
                <?php foreach ($halls as $hall) { ?>
                    <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                            href="/order_court_manage?hall_id=<?php echo $hall->id ?>&court_id=">
                            <h6><?php echo $hall->name; ?></h6></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="row margin-bottom-10">
        <div class="tab-v1 col-xs-12 col-md-12 dates">
            <ul class="nav nav-tabs">
                <?php foreach ($dates as $date => $time) { ?>
                    <li class="<?php if ($date == $activeDate) { ?>active<?php } ?>">
                        <a href="/order_court_manage?hall_id=<?= $hallID ?>&date=<?= $date ?>">
                            <?= date('m-d', $time) ?>&nbsp;<?= $weekdayOption[date('w', $time)]; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="toolbar pinned col-md-12" data-bind="visible: selected().length>0" style="display: none">
            <a class="btn btn-danger btn-lg"
               data-bind="click: batchOnline, visible: currentState()=='draft'">上架</a>
            <a class="btn btn-danger btn-lg"
               data-bind="click: batchOffline, visible: currentState()=='on_sale'">下架</a>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive pin-container col-md-12" id="table_court">
            <?php if ($instantOrders->count()<=0) { ?>
                <div class="alert alert-info"><strong>没有可出售的场地！</strong></div>
            <?php } else { ?>
                <div class="table-court">
                    <div class="col-hour">
                        <a class="hour disabled">&nbsp;</a>
                        <?php for ($startHour = $instantOrders->first()->start_hour; $startHour <= $instantOrders->last()->start_hour; $startHour++) { ?>
                            <a class="hour disabled"
                               name="hour-<?= $startHour ?>"><?= $startHour, '-' . ($startHour + 1) ?></a>
                        <?php } ?>
                    </div>
                    <?php foreach ($courts as $court) { ?>
                        <div class="col-instant-order">
                            <a name="court-<?= $court->id ?>" class="court disabled"><?= $court->number ?>号场</a>
                            <?php for ($startHour = $instantOrders->first()->start_hour; $startHour <= $instantOrders->last()->start_hour; $startHour++) { ?>
                                <?php if (isset($formattedInstants[$court->id]) && isset($formattedInstants[$court->id][$startHour])) { ?>
                                    <?php $instantOrder = $formattedInstants[$court->id][$startHour]; ?>
                                    <!-- ko with:$root.instantOrders[<?= $court->id ?>][<?= $startHour ?>]-->
                                    <a name="<?= 'instant-order-' . $court->id . '-' . $startHour ?>"
                                       title="<?= $startHour . '-' . ($startHour + 1) . ' ' . $court->number . '号场' ?>"
                                        <?php if ($instantOrder->state == 'draft') { ?>
                                       class="instant-order online"
                                       data-bind="click: $root.select, css: {active: select}">&nbsp;
                                        <?php } else if ($instantOrder->state == 'on_sale') { ?>
                                            class="instant-order offline"
                                            data-bind="click: $root.select, css: {active: select}">待售
                                        <?php } else if ($instantOrder->state == 'waste' || $instantOrder->state == 'expired') { ?>
                                            class="instant-order waste">&nbsp;
                                        <?php } else if ($instantOrder->state == 'paying') { ?>
                                            class="instant-order living"><span>支付中</span>
                                            <em style="color: red" class="countDown" data-bind="attr: {'data-time': parseInt(expire_time())+60}"></em>
                                        <?php } else { ?>
                                            class="instant-order living">已售
                                        <?php } ?>
                                    </a>
                                    <!--/ko-->
                                <?php } else { ?>
                                    <span class="instant-order">&nbsp;</span>
                                <?php } ?>
                            <?php } ?>
                        </div>>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    seajs.use('court/manage', function (courtManage) {
        courtManage.init($('#worktable')[0],
            <?= json_encode(array('instantOrders' => $formattedInstants))?>);
//        $(".pinned").pin({'containerSelector': '.pin-container', padding: {top: 5}});

        $('.countDown').kkcountdown({callback: function () {
            window.location.reload()
        }
        })
        ;
    })
    ;
</script>