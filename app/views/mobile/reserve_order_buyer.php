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
        <?php if (count($orders) <= 0) { ?>
            <?php $stateTexts = array('7' =>'全部', '0' => '待处理', '1' => '待支付');?>
            <li class="table-view-cell media notice"><p>您目前没有<?= $stateTexts[$stat]?>的场地哦！</p></li>
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
                        <span class="status"><?php if( $reserve->stat==1){echo '待支付';}elseif( $reserve->stat==2){echo '已支付';}
                            elseif( $reserve->stat==3){echo '待分账';}elseif( $reserve->stat==4){echo '已结束';}elseif( $reserve->stat==5){echo '已取消';}
                            elseif( $reserve->state==0){echo '待处理';}elseif( $reserve->state==2){echo '分账结束';}
                            ?></span>
                    </div>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>