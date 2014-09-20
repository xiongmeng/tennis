<div class="content user-center">
<ul class="table-view reserve">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('/mobile_bond') ?>" data-ignore="push">

                <div class="media-body">切换账号
                </div>
            </a>
        </li>
    </ul>
    <ul class="table-view reserve">
        <li class="table-view-cell media">
            <a class="navigate-right" href="<?= url_wrapper('/mobile_change_telephone') ?>" data-ignore="push">

                <div class="media-body"><?php if($telephone){echo '更换绑定手机号';}else{echo '绑定手机号';}?>
                </div>
            </a>
        </li>
    </ul>
</div>