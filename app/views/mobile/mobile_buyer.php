<div class="content user-center" style="padding-bottom: 200px">
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
            <?php if (!empty($reserveStatistics[RESERVE_STAT_INIT])) { ?>
                <span class="badge badge-negative "><?= $reserveStatistics[RESERVE_STAT_INIT] ?></span>
            <?php } ?>
        </a>
        <a class="control-item" onclick="window.location.href='<?= url_wrapper('/reserve_order_buyer?stat=1') ?>'"
           data-ignore="push">
            <span class="icon icon-check"></span><br/>
            待支付
            <?php if (!empty($reserveStatistics[RESERVE_STAT_UNPAY])) { ?>
                <span class="badge badge-negative"><?= $reserveStatistics[RESERVE_STAT_UNPAY] ?></span>
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
            <?php if (!empty($instantStatistics['paying'])) { ?>
                <span class="badge badge-negative "><?= $instantStatistics['paying'] ?></span>
            <?php } ?>
        </a>
        <a class="control-item" onclick="window.location.href='<?= url_wrapper('/mobile_buyer_order?state=payed') ?>'"
           data-ignore="push">
            <span class="icon icon-check"></span><br/>
            已支付
            <?php if (!empty($instantStatistics['payed'])) { ?>
                <span class="badge badge-positive"><?= $instantStatistics['payed'] ?></span>
            <?php } ?>
        </a>
    </div>

    <ul class="table-view instant">
        <li class="table-view-cell media">
            <a class="navigate-right" onclick="window.location.href='/seeking/order/list'" data-ignore="push">
                <div class="media-body">
                    约球单<span class="pull-right" style="font-size: 12px">&nbsp;(查看全部)</span>
                </div>
            </a>
        </li>
    </ul>
    <div class="segmented-control">
        <a class="control-item" onclick="window.location.href='/seeking/order/list?state=paying'"
           data-ignore="push">
            <span class="icon icon-info"></span><br/>
            待支付
            <?php if (!empty($seekingStatistics['paying'])) { ?>
                <span class="badge badge-negative "><?= $seekingStatistics['paying'] ?></span>
            <?php } ?>
        </a>
        <a class="control-item" onclick="window.location.href='/seeking/order/list?state=payed'"
           data-ignore="push">
            <span class="icon icon-check"></span><br/>
            已支付
            <?php if (!empty($seekingStatistics['payed'])) { ?>
                <span class="badge badge-positive"><?= $seekingStatistics['payed'] ?></span>
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