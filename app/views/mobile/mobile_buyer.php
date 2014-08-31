

<div class="content user-center">
    <ul class="table-view">
        <li class="table-view-cell media">
            <a>
                <?php
                $head = $user->head;
                if(empty($head)){
                    $head = ($user->sexy==1 ? '/Images/page/head_girl.jps' : '/Images/page/head_boy.jpg');
                }
                ?>
                <img width="42px" class="media-object pull-left" src="http://wangqiuer.com<?=$head?>">

                <div class="media-body">
                    <?= $user->nickname ?>
                    <p>余额：<?= balance() ?>&nbsp;&nbsp;&nbsp;积分：<?= points() ?></p>
                </div>
            </a>
        </li>
    </ul>

    <ul class="table-view">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('reserve_order_buyer') ?>" data-ignore="push">

                <div class="media-body">
                    预约订单
                </div>
            </a>
        </li></ul>
        <ul class="table-view">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('mobile_buyer_order') ?>" data-ignore="push">

                <div class="media-body">
                    即时订单<span class="pull-right" style="font-size: 12px">&nbsp;(查看全部)</span>
                </div>
            </a>
        </li>
    </ul>
    <div class="segmented-control" style="border-top: none">

        <a class="control-item" href="<?= url_wrapper('/mobile_buyer_order?state=paying')?>" >
            <span class="icon icon-info"></span><br/>
            待付款
            <?php if ($paying != 0) { ?>
                <span class="badge badge-negative "><?= $paying ?></span>
            <?php } ?>
        </a>
        <a class="control-item" href="<?= url_wrapper('/mobile_buyer_order?state=payed')?>">
            <span class="icon icon-check"></span><br/>
            等待打球
            <?php if ($payed != 0) { ?>
                <span class="badge badge-negative"><?= $payed ?></span>
            <?php } ?>
        </a>

    </div>
<!--    <ul class="table-view">-->
<!--        <li class="table-view-cell media">-->
<!--            <a class="navigate-right" href="--><?//= url_wrapper('mobile_buyer_account_balancee') ?><!--" data-ignore="push">-->
<!---->
<!--                <div class="media-body">-->
<!--                    收支明细-->
<!--                </div>-->
<!--            </a>-->
<!--        </li></ul><ul class="table-view">-->
<!--        <li class="table-view-cell media">-->
<!--            <a class="navigate-right" href="--><?//= url_wrapper('mobile_buyer_points_balance') ?><!--" data-ignore="push">-->
<!---->
<!--                <div class="media-body">-->
<!--                    积分明细-->
<!--                </div>-->
<!--            </a>-->
<!--        </li>-->
<!--    </ul>-->
</div>