<div class="content page-notice">
    <p class="description">充值<mark> <?=$recharge->money ?></mark>元即可升级为金卡会员</p>
    <a class="btn btn-positive btn-block"
       data-bind="click:$root.goToWXPay,text:$root.wxPayText,enable:$root.ttl()<=0"
       data-ignore="push">微信支付</a>
</div>

<script>
    seajs.use('/mobile/js/manage', function (changeTelephone) {
        changeTelephone.init($('body')[0],
            <?= json_encode(array('noMoney' => $noMoney))?>);
    })
    ;
</script>