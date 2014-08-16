<style>
    .table thead > tr > th,
    .table tbody > tr > th,
    .table tfoot > tr > th,
    .table thead > tr > td,
    .table tbody > tr > td,
    .table tfoot > tr > td {
        padding: 0;
        width: 9%;
        line-height: 1.428571429;
        vertical-align: top;
        border: 1px solid #dddddd;
    }
</style>

<!--=== Content ===-->
<div class="container ">
    <div class="row margin-bottom-20">
        <div class="tab-v1 col-xs-12 col-md-12">
            <ul class="nav nav-tabs">
                <?php foreach ($halls as $hall) { ?>
                    <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                            href="\order_court_manage?hall_id=<?php echo $hall->id ?>&court_id=">
                            <h6><?php echo $hall->name; ?></h6></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="tab-v3 col-md-2">
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <?php foreach ($dates as $date => $time) { ?>
                    <li class="<?php if ($date == $activeDate) { ?>active<?php } ?>">
                        <a href="/order_court_manage?hall_id=<?= $hallID ?>&date=<?= $date ?>">
                            <h4><?= date('m-d', $time) ?>&nbsp;<?= $weekdayOption[date('w', $time)]; ?></h4>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </div>

        <div class="col-md-10 table-responsive">
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th></th>
                    <?php foreach ($courts as $court) { ?>
                        <th>
                            <a class="btn btn-primary btn-lg btn-block"><?= $court->number ?>号场</a>
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
                                <a class="btn btn-primary btn-block btn-lg"><?= sprintf('%d-%d', $startHour, $startHour + 1); ?></a>
                            </td>
                            <?php foreach ($courts as $court) { ?>
                                <td>
                                    <?php if (isset($formattedInstants[$court->id]) && isset($formattedInstants[$court->id][$startHour])) { ?>
                                        <?php $instant = $formattedInstants[$court->id][$startHour];
                                        $fsm->resetObject($instant);?>
                                        <a class="<?= $states[$instant->state]['hall_class'] ?>"
                                            <?php if ($fsm->can('online')) { ?>
                                                href="fsm-operate/<?= $instant->id; ?>/online"
                                            <?php } elseif ($fsm->can('offline')) { ?>
                                                href="fsm-operate/<?= $instant->id; ?>/offline"
                                            <?php } else { ?>
                                            <?php } ?>
                                            >
                                            <?= $states[$instant->state]['hall_label'] ?>
                                        </a>
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