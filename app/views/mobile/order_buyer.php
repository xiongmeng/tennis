<!-- Block button in standard bar fixed below top bar -->
<div class="bar bar-standard bar-header-secondary" style="padding: 1px;border: none; height: 30px">
    <div class="segmented-control worktable" style="height: 100%;top: 5px; border: none">
        <a class="date <?php if ($label == 'all') {
            echo 'active';
        } ?>" href="<?= url_wrapper('/mobile_buyer_order?') ?>">
            全部
        </a>
        <a class="date <?php if ($label == 'paying') {
            echo 'active';
        } ?>" href="<?= url_wrapper('/mobile_buyer_order?state=paying') ?>">
            待支付
        </a>
        <a class="date <?php if ($label == 'payed') {
            echo 'active';
        } ?>" href="<?= url_wrapper('/mobile_buyer_order?state=payed') ?>">
            已支付
        </a>
    </div>
</div>

<!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
<div class="content" style="padding-top: 60px">
    <ul class="table-view hall-on-sale" style="padding-top: 10px">
        <?php if ($instants->count() <= 0) { ?>
            <?php $stateTexts = array('all' =>'预订过', 'paying' => '待支付', 'payed' => '已支付');?>
            <li class="table-view-cell media" style="padding: 5px;text-align: center"><p>您目前没有<?= $stateTexts[$label]?>的场地哦！</p></li>
        <?php } else { ?>
            <?php foreach ($instants as $instant) { ?>

                <li class="table-view-cell media" style="padding: 5px">

                    <img width="80px" class="media-object pull-left"
                         src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/' . $instant->hall_id . '.jpg' ?>">

                    <div class="media-body description" style="width: 52%; float: left">
                        <p class="name"><?= $instant->hall_name ?></p>

                        <p><span
                                class="header"><?= substr($instant->event_date, 0, 10) . "日 $instant->start_hour" . "点-$instant->end_hour" . "点" ?></span>
                        </p>

                        <p><span class="header">类型：</span><?= $instant->court_tags ?></p>
                    </div>
                    <div style="width: 18%; float: right;">
                        <p class="price"><span class="symbol">￥</span><span class="money">
                                <?= intval($instant->quote_price) ?>
                            </span></p>
                        <?php if ($instant->state == 'paying') { ?>
                            <br>
                            <button class="btn btn-primary"
                                    onclick="window.location.href='<?= url_wrapper("/mobile_court_buyer/$instant->hall_id?date=$instant->event_date#instant-order-$instant->court_id-$instant->start_hour") ?>'">
                                去支付
                            </button>
                        <?php } ?>
                    </div>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>
