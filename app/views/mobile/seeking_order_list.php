<div class="bar bar-standard bar-header-secondary tab">
    <div class="segmented-control worktable">
        <a class="date <?php if (empty($state)) {
            echo 'active';
        } ?>" href="<?= url_wrapper('/seeking/order/list') ?>">
            全部
        </a>
        <a class="date <?php if ($state == 'paying') {
            echo 'active';
        } ?>" href="<?= url_wrapper('/seeking/order/list?state=paying') ?>">
            待支付
        </a>
        <a class="date <?php if ($state == 'payed') {
            echo 'active';
        } ?>" href="<?= url_wrapper('/seeking/order/list?state=payed') ?>">
            已支付
        </a>
    </div>
</div>

<div class="content" style="margin-bottom: 50px; padding-top: 66px">
    <ul class="table-view hall-on-sale">
        <?php $levels = option_tennis_level(); ?>
        <?php if (count($seekingOrderList) <= 0) { ?>
            <?php $stateTexts = array('7' => '您还没有在网球通参加过约球哦', 'paying' => '您目前没有待支付的约球单哦！', 'payed' => '您目前没有待打球的约球单哦！'); ?>
            <li class="notice"><p><?= $stateTexts[$state] ?></p></li>
        <?php } else { ?>
            <?php foreach ($seekingOrderList as $seekingOrder) { ?>
                <li class="table-view-cell media" style="padding: 5px">
                    <img class="media-object pull-left head-img"
                         src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/' . $seekingOrder->hall_id . '.jpg' ?>">

                    <div class="media-body description" style="width: 52%; float: left">
                        <p class="name"><?= $seekingOrder->hall_name ?></p>

                        <p><span
                                class="header"><?= substr($seekingOrder->event_date, 5, 5) . "日 $seekingOrder->start_hour" . "点-$seekingOrder->end_hour" . "点" ?>
                                &nbsp;<?= $seekingOrder->court_num ?>片</span></p>
                    </div>
                    <div class="price">
                        <p style="text-align: right"><span class="symbol">￥</span><span class="money">
                                <?= intval($seekingOrder->cost) ?>
                            </span></p>
                    </div>
                </li>
                <!--/ko-->
            <?php } ?>
        <?php } ?>
    </ul>
</div>
