<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <?= Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
            <?= Form::model($queries) ?>
            <div class="form-group">
                <?=
                Form::input('text', 'id', null,
                    array('class' => 'form-control', 'placeholder' => '订单号'))?>
            </div>
            <div class="form-group">
                <?=
                Form::input('text', 'event_date_start', null,
                    array('class' => 'form-control datepicker', 'placeholder' => '活动开始时间'))?>
            </div>
            -
            <div class="form-group">
                <?=
                Form::input('text', 'event_date_end', null,
                    array('class' => 'form-control datepicker', 'placeholder' => '活动结束时间'))?>
            </div>
            <div class="form-group">
                <?=
                Form::input('text', 'hall_name', null,
                    array('class' => 'form-control', 'placeholder' => '场馆名称'))?>
            </div>
            <div class="form-group">
                <?=
                Form::input('text', 'buyer_name', null,
                    array('class' => 'form-control', 'placeholder' => '买家名称'))?>
            </div>
            <div class="form-group">
                <?=
                Form::select('state', $states, null,
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
                    <th>订单号</th>
                    <th>场馆</th>
                    <th>场地类型</th>
                    <th>活动时间</th>
                    <th>时段</th>
                    <th>售价</th>
                    <th>成本价</th>
                    <th>过期时间</th>
                    <th>卖家</th>
                    <th>买家</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($instants as $instant) { ?>
                    <?php $fsm = new InstantOrderFsm($instant); ?>
                    <tr>
                        <td><?php echo $instant->id; ?></td>
                        <td><?= href_hall_detail($instant->hall_id, $instant->hall_name); ?></td>
                        <td><?php echo $instant->court_tags; ?></td>
                        <td><?php echo substr($instant->event_date, 0, 10); ?></td>
                        <td><?php echo $instant->start_hour . '-' . $instant->end_hour; ?></td>
                        <td><?php echo $instant->quote_price; ?></td>
                        <td><?php echo $instant->cost_price; ?></td>
                        <td><?= !empty($instant->expire_time) ? date('m-d H:i', $instant->expire_time) : '' ?>
                        <td><?php echo $instant->seller; ?></td>
                        <td><?= href_user_detail($instant->buyer, $instant->buyer_name); ?></td>
                        <td><?php echo $states[$instant->state]; ?></td>

                        <td><?php if ($fsm->can('cancel')) { ?>
                                <a class="btn btn-danger btn-xs"
                                   href="/fsm-operate/<?php echo $instant->id; ?>/cancel"><i
                                        class="icon-trash"></i> 取消</a>
                            <?php } ?>
                            <?php if ($fsm->can('online')) { ?>
                                <a class="btn btn-warning btn-xs"
                                   href="/fsm-operate/<?php echo $instant->id; ?>/online"><i
                                        class="icon-ok"></i>上架</a>
                            <?php } ?>
                            <?php if ($fsm->can('terminate')) { ?>
                                <a class="btn btn-warning btn-xs"
                                   href="/fsm-operate/<?php echo $instant->id; ?>/terminate"><i
                                        class="icon-pencil"></i>终止</a>
                                <!--<button class="btn btn-success btn-xs"><i class="icon-ok"></i> Submit</button>-->
                                <!--<button class="btn btn-info btn-xs"><i class="icon-share"></i> Share</button></td>-->
                            <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?php echo $instants->appends($queries)->links(); ?>
            </div>

            <!--End Pegination Centered-->

        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    $(document).ready(function () {
        seajs.use('datetimePicker', function () {
            $('.datepicker').datetimepicker({
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                startView: 2,
                minView: 2
            });
        });
    });
</script>
