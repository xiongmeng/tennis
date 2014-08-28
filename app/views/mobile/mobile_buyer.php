

<div class="content">
    <ul class="table-view">
        <li class="table-view-cell media">
            <a>
                <img class="media-object pull-left" src="http://placehold.it/42x42">

                <div class="media-body">
                    <?= $user->nickname ?>
                    <p>余额：<?= $account->balance ?>&nbsp;&nbsp;&nbsp;积分：<?= intval($point->balance) ?></p>

                </div>
            </a>
        </li>
    </ul>

    <ul class="table-view">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('mobile_buyer_account_balancee') ?>" data-ignore="push">

                <div class="media-body">
                    预约订单
                </div>
            </a>
        </li></ul>
        <ul class="table-view">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('mobile_buyer_order') ?>" data-ignore="push">

                <div class="media-body">
                    即时订单<p>查看全部即时订单</p>
                </div>
            </a>
        </li>
        <div class="segmented-control">

            <a class="control-item" href="<?= url_wrapper('') ?>" data-ignore="push">
                <span class="icon icon-info"></span><br/>
                待付款
                <?php if ($paying != 0) { ?>
                    <span class="badge badge-negative "><?= $paying ?></span>
                <?php } ?>
            </a>
            <a class="control-item" href="<?= url_wrapper('') ?>" data-ignore="push">
                <span class="icon icon-check"></span><br/>
                等待打球
                <?php if ($payed != 0) { ?>
                    <span class="badge badge-negative"><?= $payed ?></span>
                <?php } ?>
            </a>

        </div>

    </ul>
    <ul class="table-view">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('mobile_buyer_account_balancee') ?>" data-ignore="push">

                <div class="media-body">
                    收支明细
                </div>
            </a>
        </li></ul><ul class="table-view">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('mobile_buyer_points_balance') ?>" data-ignore="push">

                <div class="media-body">
                    积分明细
                </div>
            </a>
        </li>
    </ul>
</div>