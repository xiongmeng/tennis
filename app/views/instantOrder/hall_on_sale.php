<link rel="stylesheet" href="/assets/css/pages/page_search.css">

<div class="container">
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
    <div class="row">
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
                <div class="col-md-6 col-xs-12 well">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="/" class="thumbnail">
                                <!--                            <img style="width: 178px;height: 147px"-->
                                <img style="height: 120px"
                                     src="<?= ($hallImage instanceof HallImage) ? 'http://wangqiuer.com' . $hallImage->path : 'http://wangqiuer.com/uploadfiles/court/201205/8920_e4b32c3b0eb0f699f8fc4217ddae403e.jpg' ?>">
                            </a>
                        </div>
                        <div class="col-md-5">
                            <h4><?= $hall->name ?></h4>

                            <p>地址：<?= $hall->city ?></p>

                            <p>电话：<?= $hall->telephone ?></p>
                        </div>
                        <div class="col-md-3">
                            <h1><?= intval($hallPriceAggregate->quote_price) ?>￥</h1>
                            <a class="btn btn-primary btn-block" target="_blank"

                               href="<?= url_wrapper("/order_court_buyer?hall_id=".$hallPriceAggregate->hall_id) ?>"
                                <span><?= $hallPriceAggregate->count ?>个时段</span>
                                <i class="icon-long-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>
    </div>

    <div>
        <?= $hallPriceAggregates->appends($queries)->links(); ?>
    </div>
</div>