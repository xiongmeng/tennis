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
                <?=
                Form::input('text', 'app_user_id', null,
                    array('class' => 'form-control', 'placeholder' => '微信openid'))?>
            </div>
            <div class="form-group">
                <?= Form::select('app_id', $appTypes, null, array('class' => 'form-control')) ?>
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
                            <th>openid</th>
                            <th>注册时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($appUsers as $appUser) { ?>
                            <?php if ($appUser instanceof RelationUserApp) { ?>
                                <tr>
                                    <td><?= $appUser->id; ?></td>
                                    <td><?= href_user_detail($appUser->user_id, $appUser->nickname)?></td>
                                    <td><?= $appUser->telephone; ?></td>
                                    <td><?= $appTypes[$appUser->app_id] ?></td>
                                    <td><?= $appUser->app_user_id ?></td>
                                    <td><?= $appUser->created_at ?></td>
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
                <?php echo $appUsers->appends($queries)->links(); ?>
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
