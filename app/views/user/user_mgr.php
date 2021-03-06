<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <?= Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
            <?= Form::model($queries) ?>
            <div class="form-group">
                <?=
                Form::input('text', 'user_id', null,
                    array('class' => 'form-control', 'placeholder' => '用户ID'))?>
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
                <?= Form::select('privilege', $privileges, null, array('class' => 'form-control')) ?>
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

                <div class="panel-body table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th width="8%">ID</th>
                            <th width="10%">注册时间</th>
                            <th width="10%">登录信息</th>
                            <th width="8%">类型</th>
                            <th width="20%">昵称</th>
                            <th width="10%">联系电话</th>
                            <th width="5%">余额</th>
                            <th width="10%">通知</th>
                            <th width="12%">传送门</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user) { ?>
                            <?php if ($user instanceof User) { ?>
                                <tr>
                                    <td><?= $user->user_id; ?></td>
                                    <td><?php if ($user->createtime) {
                                            echo date('Y-m-d', $user->createtime);
                                        } else if ($user->created_at) {
                                            echo substr($user->created_at, 0, 10);
                                        }?></td>
                                    <td><?= $user->logonnum?>|<?= date('m-d H:i',$user->logontime)?></td>
                                    <td><?= isset($privileges[$user->privilege]) ? $privileges[$user->privilege] : '普通会员'; ?></td>
                                    <td><?= href_user_detail($user->user_id, $user->nickname);?></td>
                                    <td><?= $user->telephone; ?></td>
                                    <td><?= intval($user->balance);?>元</td>
                                    <td>
                                        <a href="<?= '/notify/create?events=' . NOTIFY_TYPE_NOMONEY . '&object=' . $user->user_id ?>"
                                           target="_blank">没钱</a>|
                                        <a href="<?= '/notify/create?events=' . NOTIFY_TYPE_INIT_WJ . '&object=' . $user->user_id ?>"
                                           target="_blank">初始</a>
                                    </td>
                                    <td>
                                        <a href="/reserve_order_mgr/all?buyer_name=<?= $user->nickname ?>"
                                           target="_blank">预约</a>|
                                        <a href="/billing_mgr/account_balance?user_name=<?= $user->nickname ?>"
                                           target="_blank">流水</a>|
                                        <a href="/instant_order_mgr/all?buyer_name=<?= $user->nickname ?>"
                                           target="_blank">即时</a>
                                    </td>
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
                <?php echo $users->appends($queries)->links(); ?>
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
