<header class="bar bar-nav">

    <h1 class="title">我的预订订单</h1>
</header>
<div class="bar bar-standard bar-header-secondary" style="padding: 1px;border: none; height: 30px">
    <div class="segmented-control worktable" style="height: 100%;top: 5px; border: none">
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
            <?php if($stat == 0){?>
            <div class="alert alert-warning"><strong>您没有等待处理的订单哦！</strong></div>
                <?php }elseif($stat ==1){?>
                <div class="alert alert-warning"><strong>您没有待支付的订单哦！</strong></div>
            <?php }elseif($stat ==7){?>
                <div class="alert alert-warning"><strong>您还没有在网球通预订过场地哦！</strong></div>
        <?php } }else { ?>
            <?php foreach ($reserves as $reserve) { ?>

                <li class="table-view-cell media" style="padding: 5px">

                    <img width="80px" class="media-object pull-left" src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/'.$reserve->hall_id.'.jpg'?>">

                    <div class="media-body description" style="width: 52%; float: left">
                        <p><span class="header">场馆：</span><?= $reserve->Hall->name?></p>
                        <p><span class="header"><?= date("Y-m-d", $reserve->event_date)  . "日. $reserve->start_time"."点-$reserve->end_time"."点" ?></span></p>
                        <p><span class="header">片数：</span><?= $reserve->court_num ?></p>

                    </div>
                    <div style="width: 18%; float: right;">
                        <p class="price"><span class="symbol">￥</span><span class="money">
                                <?= $reserve->cost ?>
                            </span></p><br/>
                        <p><span class="header"></span><?php if( $reserve->stat==1){echo '待支付';}elseif( $reserve->stat==2){echo '已支付';}
                            elseif( $reserve->stat==3){echo '待分账';}elseif( $reserve->stat==4){echo '已结束';}elseif( $reserve->stat==5){echo '已取消';}
                            elseif( $reserve->state==0){echo '待处理';}elseif( $reserve->state==2){echo '分账结束';}
                            ?></p>
                    </div>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>