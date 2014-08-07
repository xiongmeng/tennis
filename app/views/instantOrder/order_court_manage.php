<!--=== Content ===-->
<div class="container">
    <div class="row margin-bottom-20">
        <div class="panel">
            <div class="tab-v1 panel-heading col-md-12">
                <ul class="nav nav-tabs">
                    <?php foreach ($halls as $hall) { ?>
                        <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                                href="\order_court_manage?hall_id=<?php echo $hall->id ?>&court_id=">
                                <h6><?php echo $hall->name; ?></h6></a></li>

                    <?php } ?>
                </ul>
            </div>

            <div class="panel-body tab-v3 col-md-12">
                <div class="row col-sm-2">
                    <ul class="nav nav-pills nav-stacked" role="tablist">
                        <?php foreach ($courts as $court) { ?>
                            <li role="presentation" class="<?php if ($court->id == $courtID) { ?>active<?php } ?>"><a
                                    href="order_court_manage?hall_id=<?php echo $court->hall_id; ?>&court_id=<?php echo $court->id ?>"
                                <h4><?php echo $court->number ?>号场</h4></a></li>

                        <?php } ?>
                    </ul>
                </div>

                <div class="col-sm-10">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <?php foreach ($dates as $date) { ?>
                                <th>
                                    <div class="pricing hover-effect">
                                        <div class="pricing-head">
                                            <h3><?php echo $weekdayOption[date('w', strtotime($date))]; ?>
                                                <span> <?php echo substr($date, 0, 10); ?></span></h3>
                                        </div>
                                    </div>
                                </th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $fsm = new InstantOrderFsm(); ?>
                        <?php if ($instants->count() <= 0) { ?>
                            <tr>
                                <td colspan="<?php $dates->count() ?>>">没有可出售的场地！</td>
                                >
                            </tr>
                        <?php } else { ?>
                            <?php for ($startHour = $instants->first()->start_hour; $startHour < $instants->last()->start_hour; $startHour++) { ?>
                                <tr>
                                    <?php foreach ($dates as $date) { ?>
                                        <td>
                                            <?php foreach ($instants as $instant) { ?>
                                                <?php $fsm->resetObject($instant); ?>
                                                <?php if (($instant->event_date) == $date && ($instant->start_hour == $startHour)) { ?>
                                                    <div class="pricing hover-effect">
                                                        <div class="pricing-head">
                                                            <h4>
                                                                <span><?php echo $instant->start_hour . ':00-' . $instant->end_hour . ':00'; ?></span>
                                                                <span><?php echo '价格:' . $instant->quote_price; ?></span>
                                                        <span><?php if ((!$fsm->can('offline') && !$fsm->can('online'))) { ?>
                                                                <?php echo $states[$instant->state]['label']; ?>
                                                            <?php } ?></span>

                                                                <?php if ($fsm->can('online')) { ?>
                                                                    <span><a
                                                                            href="fsm-operate/<?php echo $instant->id; ?>/online"<i
                                                                            class="icon-check">上架</i></a></span>
                                                                <?php } ?>
                                                                <?php if (($fsm->can('offline'))) { ?>

                                                                    <span><a
                                                                            href="fsm-operate/<?php echo $instant->id; ?>/offline"<i
                                                                            class="icon-trash">下架</i></a></span>

                                                                <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




