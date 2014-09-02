<header class="bar bar-nav">
    <form style="display: inline" class="search">
        <input style="width: 65%;font-size:15px;"
               type="search" name="hall_name" class="pull-left" placeholder="请输入场馆名" value="<?=isset($queries['hall_name']) ? $queries['hall_name'] : ''?>">
        <input type="submit" class="btn btn-primary" value="search">
    </form>
</header>

<div class="content">
    <div class="segmented-control" style="display: none">
        <a class="control-item" id="search-more-btn">
            按条件查询场馆
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

    <ul class="table-view hall-on-sale" style="margin-bottom: 50px">
        <?php if ($hallPriceAggregates->count() <= 0) { ?>
            <li class="table-view-cell media"><p style="text-align: center; width: 100%">未找到有合适场地的场馆！</p></li>
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
                    <a style="padding: 10px 5px" data-ignore="push" href="<?= url_wrapper("/mobile_court_buyer/$hallPriceAggregate->hall_id?date=$hallPriceAggregate->event_date#instant-order-$hallPriceAggregate->court_id-$hallPriceAggregate->start_hour")?>">
                        <img class="media-object pull-left head-img"
                             src="<?= 'http://wangqiuer.com/Images/weixinImage/CourtPic/' . $hall->id . '.jpg' ?>">

                        <div class="media-body description" style="width: 52%; float: left">
                            <p class="name"><?= $hall->name ?></p>
                            <p><span class="header">地址：</span><?= $hall->area_text ?></p>
                        </div>
                        <div class="price">
                            <p><span class="symbol">￥</span><span class="money">
                                <?= intval($hallPriceAggregate->quote_price) ?>
                            </span></p>
                        </div>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
    </ul>
</div>
