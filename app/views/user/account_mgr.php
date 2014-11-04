<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <?= Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
            <?= Form::model($queries) ?>
            <div class="form-group">
                <?=
                Form::input('text', 'id', null,
                    array('class' => 'form-control', 'placeholder' => '账户ID'))?>
            </div>
            <div class="form-group">
                <?=
                Form::input('text', 'nickname', null,
                    array('class' => 'form-control', 'placeholder' => '昵称'))?>
            </div>
            <div class="form-group">
                <?=
                Form::input('text', 'telephone', null,
                    array('class' => 'form-control', 'placeholder' => '手机号'))?>
            </div>
            <div class="form-group">
                <?=
                Form::input('text', 'balance_lower_bound', null,
                    array('class' => 'form-control', 'placeholder' => '余额下限'))?>
            </div>
            -
            <div class="form-group">
                <?=
                Form::input('text', 'balance_upper_bound', null,
                    array('class' => 'form-control', 'placeholder' => '余额上限'))?>
            </div>
            <div class="form-group">
                <?= Form::select('purpose', $purposes, null, array('class' => 'form-control')) ?>
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
                            <th>ID</th>
                            <th>昵称</th>
                            <th>联系电话</th>
                            <th>类型</th>
                            <th>余额</th>
                            <th>通知</th>
                            <th>创建时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($accounts as $account) { ?>
                            <?php if ($account instanceof Account) { ?>
                                <tr>
                                    <td><?= $account->id; ?></td>
                                    <td><?= href_user_detail($account->user_id, $account->nickname)?></td>
                                    <td><?= $account->telephone; ?></td>
                                    <td><?= $purposes[$account->purpose] ?></td>
                                    <td><?= intval($account->balance) ?></td>
                                    <td>
                                        <a href="<?= '/notify/create?events=' . NOTIFY_TYPE_NOMONEY . '&object=' . $account->user_id ?>"
                                           target="_blank">余额不足</a>
                                    </td>
                                    <td><?= date('Y-m-d H:i', $account->created_time) ?></td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?php echo $accounts->appends($queries)->links(); ?>
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
