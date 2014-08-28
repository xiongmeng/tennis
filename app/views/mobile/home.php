
<div class="bar bar-standard bar-header-secondary">
    <div class="segmented-control">
        <?php foreach($orders as $orderkey =>$order){?>
        <a class="control-item <?php if($orderkey==$curOrder){echo 'active';}?>" href="<?= url_wrapper($order['url']) ?>"   data-ignore="push">
            <?= $order['label']?>
        </a>
        <?php } ?>

    </div>

</div>

<!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
<div class="content">

    <form>
        <input type="search" placeholder="搜索场馆">
    </form>
<?php if($curOrder == 'reserve'){?>
    <div class="segmented-control">
        <a class="control-item active" href=""  data-ignore="push">
            推荐场馆
        </a>
        <a class="control-item" href="mobile_nearby_hall"  data-ignore="push">
            附近场馆
        </a>
        <a class="control-item" href="<?= url_wrapper('mobile_ordered_hall') ?>"  data-ignore="push">
            常订场馆
        </a>
    </div>
<?php } elseif($curOrder == 'instant'){?>
    <div class="segmented-control">
        <a class="control-item" href="#item1mobile" id="search-more-btn">

            按条件查询场馆
            <!--            <span id="search-more-icon" style="display: none" class="icon icon-up-nav pull-right"></span>-->
        </a>

    </div>
    <div id="search-list" style="display: none">

        <button class="btn btn-positive">日期</button>
        <button class="btn btn-link">周一</button>
        <button class="btn btn-link">周二</button>
        <button class="btn btn-link">周三</button>
        <br/><br/>
        <button class="btn btn-positive">类型</button>
        <button class="btn btn-link">室内</button>
        <button class="btn btn-link">室外</button>
        <button class="btn btn-link">红土</button>
        <button class="btn btn-link">硬地</button>

    </div>
    <?php }?>
    <ul class="table-view">
        <?php if ($hallPriceAggregates->count() <= 0) { ?>
            <div class="alert alert-warning"><strong>未找到有合适场地的场馆！</strong></div>
        <?php } else { ?>
        <?php foreach ($hallPriceAggregates as $hallPriceAggregate) { ?>
        <?php
        $hall = $halls[$hallPriceAggregate->hall_id];
        $hallImage = null;
        if ($hall instanceof Hall) {
            if ($hall->Envelope) {
                $hallImage = $hall;
            } else if ($hall->HallImages->count() > 0) {
                $hallImage = $hall->HallImages->first();
            }
        }
        ?>
        <li class="table-view-cell media">
            <a class="navigate-right">
                <img class="media-object pull-left" src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/'.$hall->id.'.jpg'?>">

                <div class="media-body">
                    <?= $hall->name ?>
                    <p><span>地址：</span><?= $hall->city ?></p>
                    <p><span">电话：</span><?= $hall->telephone ?></p>
                    <p>￥<span>
                                <?= intval($hallPriceAggregate->quote_price) ?>
                            </span></p>

                </div>
            </a>
        </li>
<?php }?>
        <?php }?>
    </ul>
</div>
