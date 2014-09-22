<div class="content" style="margin-top: 10px">

    <a><h1 style="margin-left: 5%; margin-top: 20%">亲爱的<?= $wxUserProfile->nickname?>,</h1></a>
    <a><h1 style="margin-left: 20%; margin-top: 10%">您绑定的网球通账号已经更换为<?= $user->nickname?></h1></a>

    <!--            <span class="icon icon-check">恭喜</span>-->

    <br/><br/>
    <button class="btn btn-primary btn-block"
            onclick="window.location.href='<?= url_wrapper('/mobile_buyer')?>'" style="width:60%; margin-left: 20% " data-ignore="push">确定</button>

</div>