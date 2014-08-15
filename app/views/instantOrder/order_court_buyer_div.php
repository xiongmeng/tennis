<style>

    .DocumentList
    {
        overflow-x:scroll;
        overflow-y:hidden;
        height:200px;
        padding: 0 15px;
        white-space: nowrap;
        position: relative;
    }

    .row {
        /*width: 500%;*/
    }

    .DocumentItem
    {
        /*border:1px solid black;*/
        padding:0;
        /*height:200px;*/
        /*width: 200px;*/
    }
</style>

<!--=== Content ===-->
<div class="container">
    <div class="row margin-bottom-20">
        <div class="tab-v1 col-md-12">
            <ul class="nav nav-tabs">
                <ul class="nav nav-tabs">
                    <?php foreach ($halls as $hall) { ?>
                        <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                                href="\order_court_buyer?hall_id=<?php echo $hall->id ?>&court_id=">
                                <h6><?php echo $hall->name; ?></h6></a></li>
                    <?php } ?>
                </ul>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="cos-xs-0 tab-v3 col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <?php foreach ($dates as $date => $time) { ?>
                    <li class="<?php if ($date == $activeDate) { ?>active<?php } ?>">
                        <a href="/order_court_buyer?hall_id=<?= $hallID ?>&date=<?= $date ?>">
                            <h4><?=date('m-d', $time)?>&nbsp;<?= $weekdayOption[date('w', $time)]; ?></h4>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </div>

        <div class="col-xs-12 col-md-10">
            <div class="row">
                <div class="col-md-1 col-xs-2 DocumentItem">
                </div>
                <div class="col-md-11">
                <div class="row">
                <?php foreach ($courts as $court) { ?>
                <div class="col-md-1 col-xs-2 DocumentItem">
                    <a class="btn btn-primary btn-lg btn-block"><?=$court->number?>号场</a>
                </div>
                <?php } ?>
                </div>
                </div>
            </div>

            <?php if (!$instants || $instants->count() <= 0) { ?>
                <div class="row">
                    <div class="alert alert-info col-md-12"><strong>没有可出售的场地！</strong></div>
                </div>
            <?php } else { ?>
                <?php $fsm = new InstantOrderFsm(); ?>
                <?php for ($startHour = $instants->first()->start_hour; $startHour < $instants->last()->start_hour; $startHour++) { ?>
                    <div class="row">
                        <div class="col-md-1 col-xs-2 DocumentItem">
                            <a class="btn btn-primary btn-block btn-lg"><?= sprintf('%2d-%2d', $startHour, $startHour+1); ?></a>
                        </div>
                        <div class="col-md-11">
                        <div class="row">
                        <?php foreach ($courts as $court) { ?>
                            <div class="col-md-1 col-xs-2 DocumentItem">
                                <?php if (isset($formattedInstants[$court->id]) && isset($formattedInstants[$court->id][$startHour])) { ?>
                                    <?php $instant = $formattedInstants[$court->id][$startHour];
                                    $fsm->resetObject($instant);?>
                                    <?php if ($fsm->can('buy')) { ?>
                                        <a class="btn btn-primary btn-block btn-lg"
                                           href="fsm_buy/<?= $instant->id; ?>">
                                            <?= intval($instant->quote_price) ?>￥
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-default btn-block btn-lg disabled">
                                            &nbsp;
                                        </a>
                                    <?php } ?>

                                <?php } else { ?>

                                <?php } ?>
                            </div>
                        <?php } ?>
                        </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>




