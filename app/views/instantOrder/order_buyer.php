<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?= Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
            <?= Form::model($queries) ?>
            <div class="form-group">
                <?= Form::input('text', 'id', null,
                    array('class' => 'form-control', 'placeholder' => '订单号'))?>
            </div>
            <div class="form-group">
                <?= Form::input('text', 'event_date_start', null,
                    array('class' => 'form-control datepicker', 'placeholder' => '活动开始时间'))?>
            </div>
            -
            <div class="form-group">
                <?= Form::input('text', 'event_date_end', null,
                    array('class' => 'form-control datepicker', 'placeholder' => '活动结束时间'))?>
            </div>
            <div class="form-group">
                <?= Form::input('text', 'hall_name', null,
                    array('class' => 'form-control', 'placeholder' => '场馆名称'))?>
            </div>
            <div class="form-group">
                <?= Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
            </div>
            <?= Form::close() ?><br/>
            <!--Basic Table Option (Spacing)-->
            <div class="margin-bottom-40 table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>场馆名称</th>
                            <th>场地类型</th>
                            <th>活动时间</th>
                            <th>时段</th>
                            <th>价格</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($instants as $instant) { ?>
                            <?php $fsm = new InstantOrderFsm($instant); ?>

                            <tr class="<?php if($instant->state == 'payed'){?>danger<?php }?>">
                                <td><?php echo $instant->id; ?></td>
                                <td><?php echo $instant->hall_name; ?></td>
                                <td><?php echo $instant->court_tags; ?></td>
                                <td><?php echo substr($instant->event_date, 0, 10); ?></td>
                                <td><?php echo $instant->start_hour . '-' . $instant->end_hour; ?></td>
                                <td><?php echo $instant->quote_price; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
            </div>

            <div class="text-center">
                <?php echo $instants->appends($queries)->links(); ?>
            </div>

        </div>
    </div>
</div>

<script type="text/javascript">
    seajs.use('datetimePicker', function(){
        $('.datepicker').datetimepicker({
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            startView: 2,
            minView: 2
        });
    });
</script>