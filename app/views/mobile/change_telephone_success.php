<div class="content page-notice">

    <p class="description">亲爱的<?= $user->nickname?>，您绑定的手机号已经更换为：<?= $user->telephone?></p>

    <button class="btn btn-primary btn-block"
            onclick="window.location.href='<?= url_wrapper('/mobile_buyer')?>'" data-ignore="push">确定</button>

</div>