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
                <input type="hidden" name="hall_id" id="hall_id" value="<?= isset($queries['hall_id']) ? $queries['hall_id'] : ''?>">
                <select class="combobox form-control" name="hall_id" >
                    <option></option>
                    <?php foreach($halls as $hall){?>
                        <option value="<?= $hall->id?>" <?= isset($queries['hall_id']) && $hall->id == $queries['hall_id'] ? 'selected' : '';?>><?= $hall->name?></option>
                    <?php }?>
                </select>
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
                    <th width="6%">订单号</th>
                    <th width="13%">创建时间</th>
                    <th width="23%">场馆</th>
                    <th width="8%">活动时间</th>
                    <th width="6%">时段</th>
                    <th width="5%">片数</th>
                    <th width="5%">金额</th>
                    <th width="12%">预订人</th>
                    <th width="8%">状态</th>
                    <th width="5%">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php $fsm = new ReserveOrderFsm(); ?>
                <?php foreach ($reserves as $reserve) { ?>
                    <?php $fsm->resetObject($reserve); ?>
                    <tr>
                        <td><?= href_reserve_detail($reserve->id); ?></td>
                        <td><?php if ($reserve->createtime) {
                                echo date('Y-m-d H:i', $reserve->createtime);
                            } else if ($reserve->created_at) {
                                echo substr($reserve->created_at, 0, 16);
                            }?>
                        </td>
                        <td><?= href_hall_detail($reserve->hall_id, $reserve->hall_name); ?></td>
                        <td><?= date('m-d', $reserve->event_date); ?></td>
                        <td><?= display_time_interval($reserve->start_time, $reserve->end_time); ?></td>
                        <td><?= $reserve->court_num; ?>片</td>
                        <td><?= $reserve->cost; ?></td>
                        <td><?= href_user_detail($reserve->user_id, $reserve->buyer_name) ?></td>
                        <td><?= $states[$reserve->stat]; ?></td>
                        <td>
                            <?php if ($fsm->can('modify')) { ?>
                                <a href="/reserve/modify/<?= $reserve->id ?>" target="_blank">修改</a>
                            <?php } ?>
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
    $(document).ready(function () {
        seajs.use(['datetimePicker', 'combobox'], function () {
            $('.datepicker').datetimepicker({
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                startView: 2,
                minView: 2
            });
            $('.combobox').combobox({target: $('#hall_id')});
        });
    });
</script>
