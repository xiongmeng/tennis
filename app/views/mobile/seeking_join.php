<div class="content" style="margin-bottom: 50px; padding-top: 26px">
    <ul class="table-view" style="color: #777">
        <li class="table-view-cell" style="padding-right: 12px">
            <label style="width: 25%;float: left">报名人员：</label>
            <?php $wxUserProfile = cache_weChat_profile($user->user_id); ?>
            <label
                style="width: 75%; float: left"><?= sprintf("%s（%s）", $user->nickname, !empty($wxUserProfile) ? $wxUserProfile->nickname : '') ?></label>
        </li>
        <li class="table-view-cell" style="padding-right: 12px">
            <label style="width: 25%;float: left">约球信息：</label>
            <label style="width: 75%; float: left"><?= seeking_brief($seeking, '') ?></label>
        </li>
        <li class="table-view-cell">
            <label>当前余额：<?= $balance ?>元</label>
        </li>
        <li class="table-view-cell">
            <label>需要花费：<?= $seeking->personal_cost ?>元</label>
        </li>
    </ul>


    <form method="post" action="/seeking/join/<?= $seeking->id ?>">
        <?php $fsm = new SeekingFsm($seeking); ?>
        <?php if ($fsm->can('join')) { ?>
            <?php if (isset($ordersGroupByState['paying'])) { ?>
                <a href="/seeking/order/list?state=paying" data-ignore="push"
                   class="form-button btn btn-primary btn-block">存在支付中的约球单，去查看并支付</a>
            <?php } else if (isset($ordersGroupByState['payed'])) { ?>
                <a href="/seeking/order/list?state=payed" data-ignore="push"
                   class="form-button btn btn-primary btn-block">存在支付过的约球单，去查看</a>
                <input type="submit" class="form-button btn btn-primary btn-block" name="pay" value="继续报名并支付">
            <?php } else { ?>
                <input type="submit" class="form-button btn btn-primary btn-block" name="pay" value="报名并支付">
            <?php } ?>
        <?php } else { ?>
            <input type="button" disabled class="form-button btn btn-primary btn-block" value="此约球信息已过期或已关门">
        <?php } ?>
    </form>
</div>