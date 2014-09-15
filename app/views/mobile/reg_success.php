<div class="content" style="margin-top: 10px">

    <a><h2 style="margin-left: 5%; margin-top: 20%">亲爱的<?= $user->nickname?>,</h2></a>
    <a><h2 style="margin-left: 20%; margin-top: 10%">您已经注册成功！微信注册直接绑定为新帐号 无需再重复绑定<br/>
            点击‘充值’按钮 充值500员成为金卡会员，享受更优惠的订场价格</h2></a>

    <!--            <span class="icon icon-check">恭喜</span>-->

    <br/><br/>
<!--    <button class="btn btn-primary btn-block"-->
<!--            onclick="window.location.href='--><?//= url_wrapper('/mobile_buyer')?><!--'" style="width:60%; margin-left: 20% ">充值</button>-->
    <button class="btn btn-primary btn-block"
            onclick="window.location.href='<?= url_wrapper('/mobile_buyer')?>'" style="width:60%; margin-left: 20% ">先去逛逛</button>

</div>