<!--==nav==-->
<nav class="bar bar-tab">
    <a class="tab-item" href="<?= url_wrapper('/mobile_home/instant') ?>" data-transition="slide-in" data-ignore="push">
        <span class="icon icon-home"></span>
        <span class="tab-label">首页</span>
    </a>
    <a class="tab-item" href="<?= url_wrapper('/mobile_buyer') ?>" data-transition="slide-in" data-ignore="push">
        <span class="icon icon-person"></span>

        <span class="tab-label">个人中心</span>

    </a>
    <a class="tab-item" href="<?= url_wrapper('#r') ?>" data-transition="slide-in" data-ignore="push">

        <span class="icon icon-star-filled"></span>
<!--        --><?php //if ($isActive == true) { ?>
<!--            <span class="badge badge-negative">!</span>-->
<!--        --><?php //} ?>
        <span class="tab-label">提醒</span>
    </a>


</nav>
<!--==end nav==-->
