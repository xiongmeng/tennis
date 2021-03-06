<!--=== Content ===-->
<div class="container worktable" id="worktable">
    <div class="row halls">
        <div class="tab-v2 col-xs-12 col-md-12">
            <ul class="nav nav-tabs">
                <?php foreach ($halls as $hall) { ?>
                    <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                            href="/order_court_buyer/<?php echo $hall->id ?>?court_id=">
                            <?php echo $hall->name; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="row margin-bottom-10">
        <div class="tab-v1 col-xs-12 col-md-12 dates">
            <ul class="nav nav-tabs">
                <?php foreach ($dates as $date => $time) { ?>
                    <li class="<?php if ($date == $activeDate) { ?>active<?php } ?>">
                        <a href="/order_court_buyer/<?= $hallID ?>?date=<?= $date ?>">
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
               data-bind="click: batchBuy, visible: currentState()=='on_sale'">预订</a>
            <a class="btn btn-danger btn-lg"
               data-bind="click: batchPay, visible: currentState()=='paying'">支付</a>
            <a class="btn btn-danger btn-lg"
               data-bind="click: batchCancelBuy, visible: currentState()=='paying'">取消预订</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 table-responsive pin-container" id="table_court">
            <?php if (empty($instantOrders)) { ?>
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
                                       title="<?= $startHour.'-'.($startHour+1). ' ' . $court->number . '号场'?>"
                                        <?php if ($instantOrder->state == 'on_sale') { ?>
                                       class="instant-order buy"
                                       data-bind="click: $root.select, css: {active: select}">
                                        <span style="font-size: small" class="money">￥</span><span
                                            data-bind="text: quote_price"></span>

                                        <?php } else if ($loginUserId == $instantOrder->buyer && $instantOrder->state == 'paying') { ?>

                                            class="instant-order paying" data-bind="click: $root.select,
                                            css: {active: select}">
                                            <span style="font-size: small" class="money">￥</span><span
                                                data-bind="text: quote_price"></span>
                                            <em style="color: red" class="countDown" data-bind="attr: {'data-time': parseInt(expire_time())+60}"></em>

                                        <?php } else if ($loginUserId == $instantOrder->buyer && $instantOrder->state == 'payed') { ?>
                                            class="instant-order living" style="background-color: #f0ad4e">待打球
                                        <?php } else { ?>
                                            class="instant-order">&nbsp;
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

<!-- Modal -->
<div class="modal fade" id="dialog-go-to-pay" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" aria-describedby="hello">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">余额不够啦</h4>
            </div>
            <div class="modal-body">
                <p>总共需要花费
                    <mark data-bind="text: needPay"></mark>
                    元
                </p>
                <p>您当前可用余额
                    <mark data-bind="text: balance">
                        <mark>元
                </p>
                <p>您还需要支付
                    <mark data-bind="text: needRecharge"></mark>
                    元
                </p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">关闭</a>
                <a data-bind="attr:{href: adviseForwardUrl}" class="btn btn-primary" target="_blank">去支付</a>
            </div>
        </div>
    </div>
</div>

<script>
    seajs.use('court/manage', function (courtManage) {
        courtManage.init($('#worktable')[0],
            <?= json_encode(array('instantOrders' => $formattedInstants))?>);
//        $(".pinned").pin({'containerSelector': '.pin-container', padding: {top: 5}});
//        $(".pinned").pin();

        $('.countDown').kkcountdown({callback: function () {
            window.location.reload()
        }
        })
        ;
    })
    ;
</script>