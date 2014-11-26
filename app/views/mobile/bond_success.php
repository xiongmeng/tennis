<div class="content page-notice">
    <p class="description">亲爱的<?= $wxUserProfile->nickname?>，您已经成功的绑定网球通账号：<?= $user->nickname?></p>

    <button class="btn btn-primary btn-block"
            onclick="window.location.href='<?= $callbackUrl?>'" data-ignore="push">确定</button>

</div>