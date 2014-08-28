

<!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
<div class="content">
    <div class="segmented-control">
        <a class="control-item active" href="mobile_instant_order"  data-ignore="push">
            全部
        </a>
        <a class="control-item" href="mobile_nearby_hall"  data-ignore="push">
            待付款
        </a>
        <a class="control-item" href="<?= url_wrapper('mobile_ordered_hall') ?>"  data-ignore="push">
            待打球
        </a>
    </div>

    <ul class="table-view">
        <?php if ($instants->count() <= 0) { ?>
            <div class="alert alert-warning"><strong>您还没有订过场地哦！</strong></div>
        <?php } else { ?>
            <?php foreach ($instants as $instant) { ?>

                <li class="table-view-cell media">

                        <img class="media-object pull-left" src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/'.$instant->hall_id.'.jpg'?>">

                        <div class="media-body">
                            <?= $instant->hall_name ?>
                            <p>类型：<span>
                                <?= $instant->court_tags ?>
                            </span></p>
                            <p>￥<span>
                                <?= intval($instant->quote_price) ?>
                            </span></p>

                      </div>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>
