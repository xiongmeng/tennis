<!--=== Breadcrumbs ===-->
<div class="breadcrumbs margin-bottom-40">
    <div class="container">
        <h1 class="pull-left">我的订单</h1>

    </div>
</div>
<!--=== End Breadcrumbs ===-->

<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">


            <?php echo Form::model($queries, array('method' => 'GET')) ?>
            <?php echo Form::label('日期：') ?><?php echo Form::input('text', 'seller') ?>
            <?php echo Form::submit('查询') ?>
            <?php echo Form::close() ?><br/>
            <!--Basic Table Option (Spacing)-->
            <div class="panel panel-green margin-bottom-40">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon-li"></i></h3>
                </div>

                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>场馆名称</th>
                            <th>场地类型</th>
                            <th>活动时间</th>
                            <th>时段</th>
                            <th>价格</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($instants as $instant) { ?>
                            <?php $fsm = new InstantOrderFsm($instant); ?>

                            <tr>
                                <td><?php echo $instant->id; ?></td>
                                <td><?php echo $instant->hall_name; ?></td>
                                <td><?php echo $instant->court_tags; ?></td>
                                <td><?php echo substr($instant->event_date, 0, 10); ?></td>
                                <td><?php echo $instant->start_hour . '-' . $instant->end_hour; ?></td>
                                <td><?php echo $instant->quote_price; ?></td>
                                <td><?php echo $states[$instant->state]['label']; ?></td>
                                <td><?php if ($fsm->can('pay_success')) { ?>
                                        <button class="btn btn-success btn-xs"><a
                                                href="fsm-operate/<?php echo $instant->id; ?>/pay_success"><i
                                                    class="icon-ok"></i>去支付</a></button>
                                    <?php } ?>
                                    <?php if ($fsm->can('confirm')) { ?>
                                        <button class="btn btn-info btn-xs"><a
                                                href="fsm-operate/<?php echo $instant->id; ?>/confirm"><i
                                                    class="icon-check"></i>确认打球</a></button>
                                    <?php } ?>

                            </tr>

                        <?php } ?>


                        </tbody>
                    </table>
                </div>
            </div>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?php echo $instants->appends($queries)->links(); ?>

                </ul>
            </div>

            <!--End Pegination Centered-->

        </div>
    </div>
</div>


<!--=== End Content ===-->
