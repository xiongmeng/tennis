
<!--=== Content ===-->
<style>
    .datepicker {
        width: 120px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <?= Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
            <?= Form::model($queries) ?>
            <div class="form-group">
                <?= Form::input('text', 'created_at_start', null,
                    array('class' => 'form-control datepicker', 'placeholder' => '发送开始时间'))?>
            </div>
            -
            <div class="form-group">
                <?= Form::input('text', 'created_at_end', null,
                    array('class' => 'form-control datepicker', 'placeholder' => '发送结束时间'))?>
            </div>
            <div class="form-group">
                <?= Form::input('text', 'object', null,
                    array('class' => 'form-control', 'placeholder' => '关联订单id/用户id等'))?>
            </div>
            <div class="form-group">
                <?= Form::input('text', 'who', null,
                    array('class' => 'form-control', 'placeholder' => '手机号/微信openid'))?>
            </div>
            <div class="form-group">
                <?= Form::select('event', $events, null,
                    array('class' => 'form-control'))?>
            </div>
            <div class="form-group">
                <?= Form::select('channel', $channels, null,
                    array('class' => 'form-control'))?>
            </div>
            <div class="form-group">
                <?= Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
            </div>
            <?= Form::close() ?><br/>
            <!--Basic Table Option (Spacing)-->
            <div class="panel panel-green margin-bottom-40">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon-li"></i></h3>
                </div>

                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th width="5%">编号</th>
                            <th width="10%">类型</th>
                            <th width="10%">发送时间</th>
                            <th width="7%">关联id</th>
                            <th width="10%">发送渠道</th>
                            <th width="10%">接收方</th>
                            <th width="30%">信息内容</th>
                            <th width="10%">通知结果</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($records as $record) { ?>
                                <tr>
                                    <td><?= $record->id; ?></td>
                                    <td><?= $events[$record->event]; ?></td>
                                    <td><?= substr($record->created_at, 2, 14); ?></td>
                                    <td><?= $record->object; ?></td>
                                    <td><?= $channels[$record->channel]; ?></td>
                                    <td><?= $record->who; ?></td>
                                    <td><?= $record->msg; ?></td>
                                    <td><?= $record->result; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?=$records->appends($queries)->links(); ?>
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
