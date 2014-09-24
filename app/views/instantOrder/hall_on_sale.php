<link rel="stylesheet" href="/assets/css/pages/page_search.css">

<div class="container hall-on-sale">
    <div class="row margin-bottom-20 bg-light">
        <div class="col-md-12">
            <?= Form::open(array('method' => 'GET', 'class' => 'navbar-form')) ?>
            <?= Form::model($queries) ?>
            <div class="form-group">
                <label class="sr-only" for="hall_name">场馆</label>
                <?=
                Form::input('text', 'hall_name', null,
                    array('class' => 'form-control', 'placeholder' => '场馆名称', 'id' => 'hall_name'))?>
            </div>
            <div class="form-group">
                <?= Form::select('event_date', $dates, null, array('class' => 'form-control')) ?>
            </div>
            <div class="form-group">
                <?= Form::select('start_hour', $hours, null, array('class' => 'form-control')) ?>
            </div>
            <div class="form-group">
                <?= Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
            </div>
            <?php echo Form::close() ?>
        </div>
    </div>
    <?php if ($hallPriceAggregates->count() <= 0) { ?>
        <div class="alert alert-warning"><strong>未找到有合适场地的场馆！</strong></div>
    <?php } else { ?>
        <?php foreach ($hallPriceAggregates as $hallPriceAggregate) { ?>
            <?php
            $hall = $halls[$hallPriceAggregate->hall_id];
            $hallImage = hall_head($hall);
            ?>
            <div class="row col-md-11 hall">

                <div class="col-md-2 col-xs-4 head-img">
                    <a href="/" class="thumbnail">
                        <img
                            src="<?= ($hallImage instanceof HallImage) ? 'http://wangqiuer.com' . $hallImage->path : 'http://wangqiuer.com/uploadfiles/court/201205/8920_e4b32c3b0eb0f699f8fc4217ddae403e.jpg' ?>">
                    </a>
                </div>
                <div class="col-md-6 col-xs-8 description">
                    <p class="name"><?= $hall->name ?></p>

                    <p><span class="title">地址：</span><?= $hall->city ?></p>

                    <p><span class="title">电话：</span><?= $hall->telephone ?></p>
                </div>
                <div class="row col-md-4">
                    <div class="col-md-12 col-xs-5 price">
                            <span class="symbol">￥</span>
                            <span class="money">
                                <?= intval($hallPriceAggregate->quote_price) ?>
                            </span>
                    </div>
                    <div class="col-md-12 col-xs-1">&nbsp;</div>
                    <div class="col-md-12 col-xs-7 go">
                        <a class="btn btn-primary btn-lg" target="_blank"

                           href="<?= url_wrapper("/order_court_buyer/$hallPriceAggregate->hall_id?date=$hallPriceAggregate->event_date#instant-order-$hallPriceAggregate->court_id-$hallPriceAggregate->start_hour") ?>">
                            <span>快去看看</span>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    <div class="row col-md-8 bg-light">
        <?= $hallPriceAggregates->appends($queries)->links(); ?>
    </div>
</div>