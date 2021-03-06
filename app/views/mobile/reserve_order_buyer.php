<div class="bar bar-standard bar-header-secondary tab">
    <div class="segmented-control worktable">
        <a class="date <?php if ($stat == '7') {
            echo 'active';
        } ?>" href="<?= url_wrapper('/reserve_order_buyer?') ?>">
            全部
        </a>
        <a class="date <?php if ($stat == 0) {
            echo 'active';
        } ?>" href="<?= url_wrapper('/reserve_order_buyer?stat=0') ?>">
            待处理
        </a>
        <a class="date <?php if ($stat == 1) {
            echo 'active';
        } ?>" href="<?= url_wrapper('/reserve_order_buyer?stat=1') ?>">
            待支付
        </a>
    </div>
</div>
<div class="content" style="padding-top: 30px;margin-bottom: 50px">
    <ul class="table-view hall-on-sale" style="padding-top: 35px ;margin-bottom: 2px;">
        <?php if (count($reserves) <= 0) { ?>
            <?php $stateTexts = array('7' => '您还没有在网球通预订过场地哦', '0' => '您目前没有待处理的订单哦！', '1' => '您目前没有待支付的订单哦！'); ?>
            <li class="notice"><p><?= $stateTexts[$stat] ?></p></li>
        <?php } else { ?>
            <?php foreach ($reserves as $reserve) { ?>
                <!-- ko with:$root.reserveOrder[<?= $reserve->id ?>]-->
                <li class="table-view-cell media" style="padding: 5px">

                    <img class="media-object pull-left head-img"
                         src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/' . $reserve->hall_id . '.jpg' ?>">

                    <div class="media-body description" style="width: 52%; float: left">
                        <p class="name"><?= $reserve->Hall->name ?></p>

                        <p><span
                                class="header"><?= date("m-d", $reserve->event_date) . "日 $reserve->start_time" . "点-$reserve->end_time" . "点" ?>
                                &nbsp;<?= $reserve->court_num ?>片</span></p>
                    </div>
                    <div class="price">
                        <p><span class="symbol">￥</span><span class="money">
                                <?= $reserve->cost ?>
                            </span></p>
                        <?php if ($reserve->stat == RESERVE_STAT_UNPAY) { ?>
                            <button class="btn btn-primary go" data-bind="click:$root.payReservationOrder">
                                去支付
                            </button>
                        <?php } else { ?>
                            <span class="status"><?php
                                if ($reserve->stat == RESERVE_STAT_CANCELED) {
                                    echo '已取消';
                                } elseif ($reserve->stat == RESERVE_STAT_INIT) {
                                    echo '待处理';
                                } else {
                                    echo '已支付';
                                }
                                ?></span>
                        <?php } ?>
                    </div>
                </li>
                <!--/ko-->
            <?php } ?>
        <?php } ?>
    </ul>
</div>

<div id="noMoneyModal" class="modal" data-bind="with:$root.noMoney">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" onclick="$('#noMoneyModal').removeClass('active');window.location.reload();"></a>
        <h1 class="title">余额不够啦</h1>
    </header>

    <div class="content">
        <p class="content-padded">总共需要花费<mark data-bind="text: needPay"></mark>元</p>
        <p class="content-padded">您当前可用余额<mark data-bind="text: balance"></mark>元</p>
        <p class="content-padded">您还需要支付<mark data-bind="text: needRecharge"></mark>元</p>
        <div class="content-padded">
            <a class="btn btn-positive btn-block" data-bind="click:$root.goToWXPay,text:$root.wxPayText,enable:$root.ttl()<=0" data-ignore="push">微信支付</a>
        </div>
    </div>
</div><!--==end nav==-->

<div id="paySuccessModal" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-check pull-right" onclick="$('#paySuccessModal').removeClass('active');window.location.reload();"></a>
        <h1 class="title">支付成功啦</h1>
    </header>

    <div class="content">
        <p class="content-padded">恭喜您，支付成功！</p>
        <p class="content-padded">您可以在<a data-ignore="push" href="<?= url_wrapper("/reserve_order_buyer")?>">个人中心/预约订单</a>里面查看详情！</p>
    </div>
</div><!--==end nav==-->

<script>
    seajs.use('/mobile/js/manage', function (courtManage) {
        courtManage.init($('body')[0],
            <?= json_encode(array('reserveOrder' => $reserves, 'noMoney' => $noMoney))?>);
    })
    ;
</script>