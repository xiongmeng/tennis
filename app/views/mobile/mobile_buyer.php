<div class="content user-center">
    <ul class="table-view">
        <li class="table-view-cell media" style="padding: 10px 5px">
            <?php
            $head = $user->head;
            if (empty($head)) {
                if (!empty($wxUserProfile->headimgurl)) {
                    $head = str_replace('/0', '/132', $wxUserProfile->headimgurl);
                } else {
                    $head = 'http://wangqiuer.com' . ($wxUserProfile->sexy == 1 ? '/Images/page/head_girl.jps' : '/Images/page/head_boy.jpg');
                }
            } else {
                $head = 'http://wangqiuer.com' . $head;
            }
            ?>
            <img width="60px" class="media-object pull-left" src="<?= $head ?>"/>

            <div class="media-body">
                <p><?php
                    echo $wxUserProfile->nickname;
                    if ($user->telephone) {
                        echo "（" . $user->nickname . "）";
                    }?>
                </p>
                <hr style="border: none; border-top: solid 1px #DDDDDD"/>
                <p>余额：<?= cache_balance() ?>
                    <a class="btn btn-primary pull-right" href='<?= url_wrapper('/recharge') ?>' style="margin-left: 5px"
                       data-ignore="push">充值</a>
                    <?php if ($user->privilege == PRIVILEGE_GOLD) { ?>
                        <a class="btn btn-negative btn-outlined disabled pull-right">金卡会员</a>
                    <?php } elseif ($user->privilege == PRIVILEGE_NORMAL) { ?>
                        <a class="btn btn-negative pull-right" href='<?= url_wrapper('/upgrade') ?>'>普通会员</a>
                    <?php } ?>
                </p>
            </div>
        </li>
    </ul>

    <?php if (!$user->telephone) { ?>
        <ul class="table-view">
            <li class="table-view-cell media">
                <a class="navigate-right" href="<?= url_wrapper('/mobile_change_user') ?>" data-ignore="push">
                    <div class="media-body">已有网球通账号，请绑定。</div>
                </a>
            </li>
        </ul>
        <ul class="table-view">
            <li class="table-view-cell media">
                <a class="navigate-right" href="<?= url_wrapper('/mobile_register') ?>" data-ignore="push">
                    <div class="media-body">没有网球通账号，请注册。</div>
                </a>
            </li>
        </ul>
    <?php } ?>

    <ul class="table-view reserve">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('reserve_order_buyer') ?>" data-ignore="push">

                <div class="media-body">
                    预约订单<span class="pull-right" style="font-size: 12px">&nbsp;(查看全部)</span>
                </div>
            </a>
        </li>
    </ul>
    <div class="segmented-control">
        <a class="control-item" onclick="window.location.href='<?= url_wrapper('/reserve_order_buyer?stat=0') ?>'"
           data-ignore="push">
            <span class="icon icon-info"></span><br/>
            待处理
            <?php if ($pending != 0) { ?>
                <span class="badge badge-negative "><?= $pending ?></span>
            <?php } ?>
        </a>
        <a class="control-item" onclick="window.location.href='<?= url_wrapper('/reserve_order_buyer?stat=1') ?>'"
           data-ignore="push">
            <span class="icon icon-check"></span><br/>
            待支付
            <?php if ($resPaying != 0) { ?>
                <span class="badge badge-negative"><?= $resPaying ?></span>
            <?php } ?>
        </a>
    </div>
    <ul class="table-view instant">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('mobile_buyer_order') ?>" data-ignore="push">

                <div class="media-body">
                    即时订单<span class="pull-right" style="font-size: 12px">&nbsp;(查看全部)</span>
                </div>
            </a>
        </li>
    </ul>
    <div class="segmented-control">
        <a class="control-item" onclick="window.location.href='<?= url_wrapper('/mobile_buyer_order?state=paying') ?>'"
           data-ignore="push">
            <span class="icon icon-info"></span><br/>
            待支付
            <?php if ($insPaying != 0) { ?>
                <span class="badge badge-negative "><?= $insPaying ?></span>
            <?php } ?>
        </a>
        <a class="control-item" onclick="window.location.href='<?= url_wrapper('/mobile_buyer_order?state=payed') ?>'"
           data-ignore="push">
            <span class="icon icon-check"></span><br/>
            已支付
            <?php if ($payed != 0) { ?>
                <span class="badge badge-positive"><?= $payed ?></span>
            <?php } ?>
        </a>
    </div>

    <?php if ($user->telephone) { ?>
        <ul class="table-view">
            <li class="table-view-cell media">
                <a class="navigate-right" href="<?= url_wrapper('/mobile_change_user') ?>" data-ignore="push">
                    <div class="media-body">更换绑定的账号</div>
                </a>
            </li>
        </ul>
        <ul class="table-view">
            <li class="table-view-cell media">
                <a class="navigate-right" href="<?= url_wrapper('/mobile_change_telephone') ?>" data-ignore="push">
                    <div class="media-body">更换绑定的手机</div>
                </a>
            </li>
        </ul>
    <?php } ?>
</div>