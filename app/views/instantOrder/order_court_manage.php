<!--=== Breadcrumbs ===-->
<div class="breadcrumbs margin-bottom-20">
    <div class="container">
        <ul class="nav nav-pills" role="tablist">
            <?php foreach ($halls as $hall) { ?>

                <li role="presentation" class="<?php if ($hall->id ==$hallID) { ?>active<?php } ?>"><a
                        href="order_court_manage?hall_id=<?php echo $hall->id ?>&court_id=">
                        <h3><?php echo $hall->name; ?></h3></a></li>

            <?php } ?>
        </ul>
    </div>
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content ===-->
<div class="container">
    <div class="row row margin-bottom-20">
        <!--Begin Sidebar Menu-->
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <?php foreach ($courts as $court) { ?>
                    <li role="presentation" class="<?php if ($court->id == $courtID) { ?>active<?php } ?>"><a
                            href="order_court_manage?hall_id=<?php echo $court->hall_id; ?>&court_id=<?php echo $court->id ?>">
                            <h3><?php echo $court->number ?>号场</h3></a></li>

                <?php } ?>
            </ul>
        </div>
        <!--End Sidebar Menu-->

        <!--Begin Content-->
        <?php function weekday($time)
        {
            if (is_numeric($time)) {
                $weekday = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
                return $weekday[date('w', $time)];
            }
        } ?>
        <div class="no-space-pricing">
            <?php foreach ($dates as $date) { ?>
                <div class="col-md-2 col-sm-6">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                <div class="pricing hover-effect">
                                    <div class="pricing-head">
                                        <h3><?php echo weekday(strtotime($date)); ?>
                                            <span> <?php echo substr($date, 0, 10); ?></span></h3>
                                        </ul>
                                    </div>
                                </div>

                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><?php foreach ($instants as $instant) { ?>
                                    <?php $fsm = new InstantOrderFsm($instant); ?>
                                    <?php if (($instant->event_date) == $date) { ?>
                                        <div class="pricing hover-effect">
                                            <div class="pricing-head">
                                                <h4>
                                                    <span><?php echo $instant->start_hour . ':00-' . $instant->end_hour . ':00'; ?></span>
                                                    <span><?php echo '价格:' . $instant->quote_price; ?></span>
                                                        <span><?php if ((!$fsm->can('offline') && !$fsm->can('online'))) { ?>
                                                                <?php echo $states[$instant->state]['label']; ?>
                                                            <?php } ?></span>

                                                    <?php if ($fsm->can('online')) { ?>
                                                        <span><a href="fsm-operate/<?php echo $instant->id; ?>/online"<i
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
                        </tr>
                        </tbody>
                    </table>
                </div>

            <?php } ?>
            <!--End Basic Table-->
        </div>
    </div>
</div>

<!--/container-->
<!--=== End Content Part ===-->




