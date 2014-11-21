<div class="content" style="margin-bottom: 50px; padding-top: 66px" xmlns="http://www.w3.org/1999/html">
    <ul class="table-view" style="color: #777">
        <li class="table-view-cell">
            <label>共需支付：</label>
            <label><?= $seeking->personal_cost ?>元</label>
        </li>
        <li class="table-view-cell">
            <label>当前余额：</label>
            <label><?= $balance ?>元</label>
        </li>
        <li class="table-view-cell">
            <label>需要充值：</label>
            <label><?= $seeking->personal_cost <= $balance ? 0 : ($seeking->personal_cost - $balance) ?>元</label>
        </li>
    </ul>

    <form method="post" action="/seeking/join/<?= $seeking->id ?>">
        <?php if ($needRecharge >= 0) { ?>
            <input type="submit" class="form-button btn btn-primary btn-block" name="ali" value="报名并充值支付（支付宝）">
            <input type="submit" class="form-button btn btn-primary btn-block" name="wx" value="报名并充值支付（微信）">
        <?php } else { ?>
            <input type="submit" class="form-button btn btn-primary btn-block" name="pay" value="报名并支付">
        <?php } ?>
    </form>
</div>


<div id="noMoneyModal" class="modal" data-bind="with:$root.noMoney">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right"
           onclick="$('#noMoneyModal').removeClass('active');window.location.reload();"></a>

        <h1 class="title">余额不够啦</h1>
    </header>

    <div class="content">
        <p class="content-padded">总共需要花费
            <mark data-bind="text: needPay"></mark>
            元
        </p>
        <p class="content-padded">您当前可用余额
            <mark data-bind="text: balance"></mark>
            元
        </p>
        <p class="content-padded">您还需要支付
            <mark data-bind="text: needRecharge"></mark>
            元
        </p>
        <div class="content-padded">
            <a class="btn btn-positive btn-block" data-bind="attr:{href: adviseForwardUrl}" data-ignore="push">支付宝支付</a>
            <a class="btn btn-positive btn-block"
               data-bind="click:$root.goToWXPay,text:$root.wxPayText,enable:$root.ttl()<=0" data-ignore="push">微信支付</a>
        </div>
    </div>
</div><!--==end nav==-->

<div id="paySuccessModal" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-check pull-right"
           onclick="$('#paySuccessModal').removeClass('active');window.location.reload();"></a>

        <h1 class="title">支付成功啦</h1>
    </header>

    <div class="content">
        <p class="content-padded">恭喜您，支付成功！</p>

        <p class="content-padded">您可以在<a data-ignore="push"
                                         href="<?= url_wrapper("/reserve_order_buyer") ?>">个人中心/约球记录</a>里面查看详情！</p>
    </div>
</div><!--==end nav==-->