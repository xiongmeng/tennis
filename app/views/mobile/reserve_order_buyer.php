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
            <?php $stateTexts = array('7' =>'您还没有在网球通预订过场地哦', '0' => '您目前没有待处理的订单哦！', '1' => '您目前没有待支付的订单哦！');?>
            <li class="table-view-cell media notice"><p><?= $stateTexts[$stat]?></p></li>
        <?php }else { ?>
            <?php foreach ($reserves as $reserve) { ?>

                <li class="table-view-cell media" style="padding: 5px">

                    <img class="media-object pull-left head-img" src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/'.$reserve->hall_id.'.jpg'?>">

                    <div class="media-body description" style="width: 52%; float: left">
                        <p class="name"><?= $reserve->Hall->name?></p>
                        <p><span class="header"><?= date("m-d", $reserve->event_date)  . "日 $reserve->start_time"."点-$reserve->end_time"."点" ?>&nbsp;<?= $reserve->court_num ?>片</span></p>
                    </div>
                    <div class="price">
                        <p><span class="symbol">￥</span><span class="money">
                                <?= $reserve->cost ?>
                            </span></p>
                        <span class="status"><?php if( $reserve->stat==1){echo '待支付';}
                            elseif( $reserve->stat==5){echo '已取消';}
                            elseif( $reserve->stat==0){echo '待处理';}else{echo '已支付';}
                            ?></span>
                    </div>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>