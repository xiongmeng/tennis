<div class="content page-notice">

    <p class="description">亲爱的<?= $wxUserProfile->nickname?>，您已经成功注册了网球通账号：<?= $user->nickname?>。</p>
    <a class="btn btn-primary btn-block" href='<?= url_wrapper('/mobile_home/reserve/recommend')?>' data-ignore="push">预约订场</a>
    <a class="btn btn-primary btn-block" href='<?= url_wrapper('/mobile_home/instant')?>' data-ignore="push">即时订场</a>
</div>