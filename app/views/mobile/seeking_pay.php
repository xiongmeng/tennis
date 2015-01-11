<?php if ($result['status'] == 'pay_success') { ?>
    <div class="content">
        <p class="content-padded">恭喜您，支付成功！</p>

        <p class="content-padded">您可以在<a data-ignore="push"
                                         href="<?= url_wrapper("/seeking/order/list?state=payed") ?>">个人中心/约球记录</a>里面查看详情！
        </p>
    </div>
<?php } else { ?>
    <div class="content" data-bind="with:noMoney">
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
            <a class="btn btn-positive btn-block"
               data-bind="click:$root.goToWXPay,text:$root.wxPayText,enable:$root.ttl()<=0"
               data-ignore="push">微信支付</a>
        </div>

        <p style="padding: 10px 20px;  color: indianred; font-weight: bolder; font-size: 18px">
            系统已为您冻结了一个坑，有效时间截止到<?= date('m-d H:i', $order->expire_time)?>，请尽快完成支付！
        </p>
    </div>
<?php } ?>

<script>
    seajs.use('/mobile/js/manage', function (courtManage) {
        courtManage.init($('body')[0], <?= json_encode(array('noMoney' => $result))?>);
    });
</script>
