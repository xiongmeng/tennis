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
                    array('class' => 'form-control', 'placeholder' => '微信昵称'))?>
            </div>
            <div class="form-group">
                <?=
                Form::input('text', 'openid', null,
                    array('class' => 'form-control', 'placeholder' => '微信openid'))?>
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
                            <th>open_id</th>
                            <th>微信昵称</th>
                            <th>用户id</th>
                            <th>性别</th>
                            <th>国家</th>
                            <th>省份</th>
                            <th>城市</th>
                            <th>头像</th>
                            <th>记录时间</th>
                            <th>最后一次记录时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($wechatUsers as $wechatUser) { ?>
                            <?php if ($wechatUser instanceof weChatUserProfile) { ?>
                                <tr>
                                    <td><?= $wechatUser->openid; ?></td>
                                    <td><?= $wechatUser->nickname; ?></td>
                                    <td><?= href_user_detail($wechatUser->user_id, $wechatUser->user_id) ?></td>
                                    <td>
                                        <?php if ($wechatUser->sexy == 1) {
                                            echo '男';
                                        } elseif ($wechatUser->sexy == 2) {
                                            echo '女';
                                        } else {
                                            echo '';
                                        } ?>
                                    </td>
                                    <td><?= $wechatUser->country ?></td>
                                    <td><?= $wechatUser->province ?></td>
                                    <td><?= $wechatUser->city ?></td>
                                    <td>
                                        <?php if ($wechatUser->headimgurl) { ?>
                                            <a target="_blank" href="<?= $wechatUser->headimgurl ?>">点击查看</a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $wechatUser->created_at?></td>
                                    <td><?= $wechatUser->updated_at?></td>
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
                <?php echo $wechatUsers->appends($queries)->links(); ?>
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
