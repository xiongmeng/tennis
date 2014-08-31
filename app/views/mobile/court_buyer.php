<!-- Block button in standard bar fixed below top bar -->
<div class="bar bar-standard bar-header-secondary" style="padding: 1px;border: none">
    <div class="segmented-control worktable" style="height: 100%;top: 0;margin-bottom: 2px; border: none">
        <?php foreach ($dates as $date => $time) { ?>
            <a class="date <?php if ($date == $activeDate) { ?>active<?php } ?>"
               href="/mobile_court_buyer/<?= $hallID ?>?date=<?= $date ?>" data-ignore="push">
                <p><?=$weekdayOption[date('w', $time)]?></p><p style="margin-top: 6px"><?= date('m-d', $time) ?></p>
            </a>
        <?php } ?>
    </div>
</div>

<div class="content" style="border: none; margin-top: 2px; margin-bottom: 50px">
    <?php if (empty($instantOrders)) { ?>
        <div class="alert alert-info"><strong>没有可出售的场地！</strong></div>
    <?php } else { ?>
        <div class="table-court">
            <div class="col-hour">
                <a class="hour disabled">&nbsp;</a>
                <?php for ($startHour = $instantOrders->first()->start_hour; $startHour < $instantOrders->last()->start_hour; $startHour++) { ?>
                    <a class="hour disabled"
                       name="hour-<?= $startHour ?>"><?= $startHour, '-' . ($startHour + 1) ?></a>
                <?php } ?>
                <a class="hour disabled">&nbsp;</a>
            </div>
            <?php foreach ($courts as $court) { ?>
                <div class="col-instant-order">
                    <a name="court-<?= $court->id ?>" class="court disabled"><?= $court->number ?>号场</a>
                    <?php for ($startHour = $instantOrders->first()->start_hour; $startHour < $instantOrders->last()->start_hour; $startHour++) { ?>
                        <?php if (isset($formattedInstants[$court->id]) && isset($formattedInstants[$court->id][$startHour])) { ?>
                            <?php $instantOrder = $formattedInstants[$court->id][$startHour]; ?>
                            <!-- ko with:$root.instantOrders[<?= $court->id ?>][<?= $startHour ?>]-->
                            <a name="<?= 'instant-order-' . $court->id . '-' . $startHour ?>"
                               title="<?= $startHour . '-' . ($startHour + 1) . ' ' . $court->number . '号场' ?>"
                                <?php if ($instantOrder->state == 'on_sale') { ?>
                               class="instant-order buy"
                               data-bind="click: $root.select, css: {active: select}">
                                ￥<?= intval($instantOrder->quote_price)?>
                                <?php } else if ($loginUserId == $instantOrder->buyer && $instantOrder->state == 'paying') { ?>
                                    class="instant-order paying" data-bind="click: $root.select,
                                    css: {active: select}">
                                    ￥<?= intval($instantOrder->quote_price)?>
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
                    <a name="court-<?= $court->id ?>" class="court disabled"><?= $court->number ?>号场</a>
                </div>
            <?php } ?>
            <div class="col-hour">
                <a class="hour disabled">&nbsp;</a>
                <?php for ($startHour = $instantOrders->first()->start_hour; $startHour < $instantOrders->last()->start_hour; $startHour++) { ?>
                    <a class="hour disabled"
                       name="hour-<?= $startHour ?>"><?= $startHour, '-' . ($startHour + 1) ?></a>
                <?php } ?>
                <a class="hour disabled">&nbsp;</a>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Block button in standard bar fixed above the footer -->
<div class="bar bar-standard bar-footer-secondary toolbar" data-bind="visible:selected().length>0" style="display: none">
    <div class="segmented-control">
        <a class="control-item" onclick="$('#confirmingBuyModal').addClass('active')" data-bind="visible: currentState()=='on_sale'">预&nbsp;订</a>
        <a class="control-item" onclick="$('#confirmingPayModal').addClass('active')" data-bind="visible: currentState()=='paying'">继续支付</a>
        <a class="control-item"
           data-bind="click: batchCancelBuy, visible: currentState()=='paying'">取消预订</a>
    </div>
</div>

<div id="confirmingBuyModal" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" onclick="$('#confirmingBuyModal').removeClass('active')"></a>
        <h1 class="title">确认购买吗</h1>
    </header>

    <div class="content">
        <p class="content-padded">共选取<mark data-bind="text:selected().length"></mark>个时段，共计<mark data-bind="text:selectedMoney"></mark>元</p>
        <div class="content-padded">
            <button class="btn btn-positive btn-block" data-bind="click: batchBuy">确定</button>
        </div>
    </div>
</div><!--==end nav==-->

<div id="confirmingPayModal" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" onclick="$('#confirmingPayModal').removeClass('active')"></a>
        <h1 class="title">确认支付吗</h1>
    </header>

    <div class="content">
        <p class="content-padded">共选取<mark data-bind="text:selected().length"></mark>个时段，共计<mark data-bind="text:selectedMoney"></mark>元</p>
        <div class="content-padded">
            <button class="btn btn-positive btn-block" data-bind="click: batchPay">确定</button>
        </div>
    </div>
</div><!--==end nav==-->


<div id="noMoneyModal" class="modal" data-bind="with:$root.noMoney">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" onclick="$('#noMoneyModal').removeClass('active');window.location.reload();"></a>
        <h1 class="title">余额不够啦</h1>
    </header>

    <div class="content">
        <p class="content-padded">总共需要花费<mark data-bind="text: needPay"></mark>元</p>
        <p class="content-padded">您当前可用余额<mark data-bind="text: balance"><mark>元</p>
        <p class="content-padded">您还需要支付<mark data-bind="text: needRecharge"></mark>元</p>
        <div class="content-padded">
            <a class="btn btn-positive btn-block" data-bind="attr{href: adviseForwardUrl}" data-ignore="push">去支付</a>
        </div>
    </div>
</div><!--==end nav==-->

<div id="paySuccessModal" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" onclick="$('#paySuccessModal').removeClass('active');window.location.reload();"></a>
        <h1 class="title">支付成功啦</h1>
    </header>

    <div class="content">
        <p class="content-padded">恭喜您，购买成功！您可以在<b>个人中心/已购买订单</b>里面查看详情！</p>
    </div>
</div><!--==end nav==-->

<script>
    seajs.use('/mobile/js/manage', function (courtManage) {
        courtManage.init($('body')[0],
            <?= json_encode(array('instantOrders' => $formattedInstants, 'noMoney' => $noMoney))?>);
    })
    ;
</script>