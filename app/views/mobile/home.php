
<div class="bar bar-standard bar-header-secondary">
    <div class="segmented-control">
        <?php foreach($orders as $orderkey =>$order){?>
        <a class="control-item <?php if($orderkey==$curOrder){echo 'active';}?>" href="<?= url_wrapper($order['url']) ?>" >
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
        <?php foreach($types as $typekey =>$type){?>
        <a class="control-item <?php if($typekey==$curType){echo 'active';}?>" href="<?= url_wrapper($type['url'])?>" >
            <?= $type['label']?>
        </a>
        <?php }?>
    </div>
    <ul class="table-view">
        <?php foreach($halls as $key =>$hall){?>
        <li class="table-view-cell media">
            <a class="navigate-right" href="/hall_reserve?hall_id=<?=$hall['id']?>">
<!--                <img class="media-object pull-left" src="http://placehold.it/42x42">-->
                <div class="media-body">
                    <?= $hall['name']?>
                    <p><span>地址：</span><?= $hall['area_text'] ?></p>
                    <p><span">电话：</span><?= $hall['telephone'] ?></p>

                </div>
            </a>
            <div class="segmented-control">
                <a class="control-item" >
                    时段
                </a>
                <a class="control-item" >
                    门市价
                </a>
                <a class="control-item" >
                    普通会员
                </a>
                <a class="control-item" >
                    金卡会员
                </a>
            </div>
            <?php foreach($hall['price'] as $price){?>
            <div class="segmented-control">

                <a class="control-item" >
                    <?=$price['name']?>
                </a>
                    <a class="control-item" >
                        <?=$price['market']?>
                    </a>
                    <a class="control-item" >
                        <?=$price['member']?>
                    </a>
                    <a class="control-item" >
                        <?=$price['vip']?>
                    </a>

            </div>
            <?php }?>
        </li>
        <?php }?>
    </ul>
    <ul class="table-view">
        <li class="table-view-cell media">


                <div class="media-body">

                </div>
            </a>
        </li>
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
<?php }?>
</div>
