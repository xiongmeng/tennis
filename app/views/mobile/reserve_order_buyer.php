<header class="bar bar-nav">

    <h1 class="title">预订订单</h1>
</header>
<div class="content" style="padding-top: 30px">
    <ul class="table-view hall-on-sale" style="padding-top: 10px">
        <?php if ($reserves->count() <= 0) { ?>
            <div class="alert alert-warning"><strong>您还没有订过场地哦！</strong></div>
        <?php } else { ?>
            <?php foreach ($reserves as $reserve) { ?>

                <li class="table-view-cell media" style="padding: 5px">

                    <img width="80px" class="media-object pull-left" src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/'.$reserve->hall_id.'.jpg'?>">

                    <div class="media-body description" style="width: 52%; float: left">
                        <p class="name"><?= $reserve->hall_name ?></p>
                        <p><span class="header"><?= date("Y-m-d", $reserve->event_date)  . "日. $reserve->start_time"."点-$reserve->end_time"."点" ?></span></p>
                        <p><span class="header">片数：</span><?= $reserve->court_num ?></p>
                        <p><span class="header">订单状态：</span><?php if( $reserve->stat==1){echo '待支付';}elseif( $reserve->stat==2){echo '已支付';}
                            elseif( $reserve->stat==3){echo '待分账';}elseif( $reserve->stat==4){echo '已结束';}elseif( $reserve->stat==5){echo '已取消';}
                            elseif( $reserve->state==0){echo '待处理';}elseif( $reserve->state==2){echo '分账结束';}
                          ?></p>
                    </div>
                    <div style="width: 18%; float: right;">
                        <p class="price"><span class="symbol">￥</span><span class="money">
                                <?= $reserve->cost ?>
                            </span></p>
                    </div>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>