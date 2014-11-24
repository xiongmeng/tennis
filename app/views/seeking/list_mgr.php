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
                    array('class' => 'form-control', 'placeholder' => '预订人'))?>
            </div>
            <div class="form-group">
                <?=
                Form::select('stat', $states, null,
                    array('class' => 'form-control', 'placeholder' => '买家名称'))?>
            </div>
            <div class="form-group">
                <?= Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
            </div>
            <?= Form::close() ?><br/>

            <!--Basic Table Option (Spacing)-->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th width="6%">订单号</th>
                    <th width="10%">创建时间</th>
                    <th width="12%">活动时间</th>
                    <th width="15%">场馆</th>
                    <th width="5%">片数</th>
                    <th width="20">坑位</th>
                    <th width="7%">费用</th>
                    <th width="12%">创建人</th>
                    <th width="8%">状态</th>
                    <th width="10%">过期时间</th>
                </tr>
                </thead>
                <tbody>
                <?php $fsm = new SeekingFsm(); ?>
                <?php foreach ($seekingList as $seeking) { ?>
                    <?php $fsm->resetObject($seeking); ?>
                    <tr>
                        <td><?= href_seeking_detail($seeking->id); ?></td>
                        <td><?= substr($seeking->created_at, 5, 11);?></td>
                        <td><?= substr($seeking->event_date, 5, 5) . '日' . display_time_interval($seeking->start_hour, $seeking->end_hour); ?></td>
                        <td><?= href_hall_detail($seeking->hall_id, $seeking->hall_name); ?></td>
                        <td><?= $seeking->court_num; ?>片</td>
                        <td><?= sprintf('空:%s总:%s', $seeking->on_sale, $seeking->store)?></td>
                        <td><?= $seeking->personal_cost; ?>元</td>
                        <td><?= href_user_detail($seeking->user_id, $seeking->creator_name) ?></td>
                        <td><?= $states[$seeking->state]; ?></td>
                        <td><?= date('m-d H:i', $seeking->expire_time) ?></td>
                    </tr>

                <?php } ?>


                </tbody>
            </table>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?php echo $seekingList->appends($queries)->links(); ?>
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
