
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
                <?= Form::input('text', 'buyer_name', null,
                    array('class' => 'form-control', 'placeholder' => '预订人'))?>
            </div>
            <div class="form-group">
                <?= Form::select('stat', $states, null,
                    array('class' => 'form-control', 'placeholder' => '买家名称'))?>
            </div>
            <div class="form-group">
                <?= Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
            </div>
            <?= Form::close() ?><br/>

            <div class="tab-v1">
                <ul class="nav nav-tabs">
                    <?php foreach ($tabs as $tabKey => $tab) { ?>
                        <li class="<?php if ($tabKey == $curTab) { ?>active<?php } ?>">
                            <a href="<?php echo $tab['url'] ?>"><?php echo $tab['label'] ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <!--Basic Table Option (Spacing)-->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="8%">订单号</th>
                    <th>场馆</th>
                    <th width="8%">活动时间</th>
                    <th width="8%">时段</th>
                    <th width="5%">售价</th>
                    <th width="8%">预订人</th>
                    <th width="8%">状态</th>
                    <th>操作</th>
                    <th>通知</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($reserves as $reserve) { ?>
                        <tr>
                            <td><?php echo $reserve->id; ?></td>
                            <td><?php echo $reserve->hall_name; ?></td>
                            <td><?php echo date('m-d', $reserve->event_date); ?></td>
                            <td><?php echo $reserve->start_time . '-' . $reserve->end_time; ?></td>
                            <td><?php echo $reserve->cost; ?></td>
                            <td><a href="/user/detail/<?= $reserve->user_id;?>" target="_blank"><?= $reserve->buyer_name; ?></a></td>
                            <td><?php echo $states[$reserve->stat]; ?></td>
                            <td></td>
                            <td>
                                <a href="<?='/notify/create?events=' . NOTIFY_TYPE_ORDER_FAILED . '&object=' . $reserve->id?>" target="_blank">无场地</a>|
                                <a href="<?='/notify/create?events=' . NOTIFY_TYPE_ORDER_UNPAY . '&object=' . $reserve->id?>" target="_blank">预订</a>|
                                <a href="<?='/notify/create?events=' . NOTIFY_TYPE_ORDER_PAYED . '&object=' . $reserve->id?>" target="_blank">支付</a>|
                                <a href="<?='/notify/create?events=' . NOTIFY_TYPE_ORDER_CANCEL . '&object=' . $reserve->id?>" target="_blank">取消</a>
                            </td>
                        </tr>

                    <?php } ?>


                </tbody>
            </table>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?php echo $reserves->appends($queries)->links(); ?>
            </div>

            <!--End Pegination Centered-->

        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    $(document).ready(function(){
        seajs.use('datetimePicker', function(){
            $('.datepicker').datetimepicker({
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                startView: 2,
                minView: 2
            });
        });
    });
</script>
