<div class="content page-notice">
    <form style="display: inline" id="form1" name="form1" action="<?= url_wrapper('/recharge') ?>"
          method="post">
        <div class="form-controller">
            <div class="item">
                <label class="label">余额：</label>
                <label class="input-normal"><?= balance() ?>&nbsp;元</label>
            </div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">额度：</label>
                <input class="input-normal" type="text" name="money" id="money" placeholder="输入要充值的额度"
                    <?=Input::get('money')?>>
            </div>

            <div class="error" id="money"><?php if ($errors->first('money')) {
                    echo $errors->first('money');
                } ?></div>
        </div>

        <input type="submit" id="ok" name="ok" class="btn btn-primary btn-block" value="充值"/>
    </form>
</div>

<?php if (isset($recharge)) { ?>
    <div id="noMoneyModal" class="modal active" data-bind="with:$root.noMoney">
        <header class="bar bar-nav">
            <a class="icon icon-close pull-right"
               onclick="$('#noMoneyModal').removeClass('active');"></a>

            <h1 class="title">确认充值</h1>
        </header>

        <div class="content">
            <p class="content-padded">您将向网球通账户充值
                <mark> <?=$recharge->money ?></mark>
                元
            </p>
            <div class="content-padded">
                <a class="btn btn-positive btn-block" href="<?= $noMoney['adviseForwardUrl'] ?>" data-ignore="push">支付宝支付</a>
                <a class="btn btn-positive btn-block"
                   data-bind="click:$root.goToWXPay,text:$root.wxPayText,enable:$root.ttl()<=0"
                   data-ignore="push">微信支付</a>
            </div>
        </div>
    </div><!--==end nav==-->
<?php } ?>
<script>
    seajs.use('/mobile/js/manage', function (changeTelephone) {
        changeTelephone.init($('body')[0],
            <?= json_encode(array('noMoney' => $noMoney))?>);
    })
    ;
</script>