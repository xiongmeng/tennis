<!--=== Content ===-->
<div class="container ">
    <div class="row margin-bottom-20">
        <div class="tab-v1 col-xs-12 col-md-12">
            <ul class="nav nav-tabs">
                <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                        href="\order_court_manage?hall_id=<?php echo $hall->id ?>&court_id=">
                        <h6><?php echo $hall->name; ?></h6></a></li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="tab-v3 col-md-2">
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <?php foreach ($courts as $court) { ?>
                    <li role="presentation" class="<?php if ($court->id == $courtID) { ?>active<?php } ?>"><a
                            href="order_court_manage?hall_id=<?php echo $court->hall_id; ?>&court_id=<?php echo $court->id ?>"
                        <h4><?php echo $court->number ?>号场</h4></a></li>

                <?php } ?>
            </ul>
        </div>

        <div class="col-md-10 table-responsive">
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th></th>
                    <?php foreach ($dates as $date) { ?>
                        <th>
                            <a class="btn btn-primary btn-lg btn-block"><?php echo $weekdayOption[date('w', strtotime($date))]; ?>
                                (<?php echo date('m-d', strtotime($date)) ?>)</a>
                        </th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php if (!$instants || $instants->count() <= 0) { ?>
                    <tr>
                        <td></td>
                        <td colspan="<?= count($dates) ?>" style="text-align: center">
                            <div class="alert alert-info"><strong>没有可出售的场地！</strong></div>
                        </td>
                    </tr>
                <?php } else { ?>
                    <?php $fsm = new InstantOrderFsm(); ?>
                    <?php for ($startHour = $instants->first()->start_hour; $startHour < $instants->last()->start_hour; $startHour++) { ?>
                        <tr>
                            <td>
                                <a class="btn btn-primary btn-block btn-lg"><?php echo $startHour . ':00 - ' . ($startHour + 1) . ':00'; ?></a>
                            </td>
                            <?php foreach ($dates as $date) { ?>
                                <td>
                                    <?php if (isset($formattedInstants[$date]) && isset($formattedInstants[$date][$startHour])) { ?>
                                        <?php $instant = $formattedInstants[$date][$startHour];
                                        $fsm->resetObject($instant);?>
                                        <?php if ($fsm->can('buy')) { ?>
                                            <a class="btn btn-primary btn-block btn-lg" href="fsm-operate/<?= $instant->id; ?>/buy">
                                                购买
                                            </a>
                                        <?php } else { ?>
                                            <a class="btn btn-default btn-block btn-lg disabled">
                                                &nbsp;
                                            </a>
                                        <?php } ?>

                                    <?php } else { ?>

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




