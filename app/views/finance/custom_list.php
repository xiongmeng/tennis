<?php use Sports\Constant\Finance as FinanceConstant; ?>
<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="panel">
            <div class="col-md-12 panel-heading">
                <?= Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
                <?= Form::model($queries) ?>
                <div class="form-group">
                    <?= Form::input('text', 'id', null,
                        array('class' => 'form-control', 'placeholder' => 'ID'))?>
                </div>
                <div class="form-group">
                    <?= Form::input('text', 'user_name', null,
                        array('class' => 'form-control', 'placeholder' => '用户、场馆昵称'))?>
                </div>
                <div class="form-group">
                    <?= Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
                </div>
                <?php echo Form::close() ?>
            </div>
            <div class="col-md-12 panel-body">

                <!--Basic Table Option (Spacing)-->
                <div class="panel panel-green margin-bottom-40">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15">创建时间</th>
                                <th width="13%">用户名</th>
                                <th width="7%">额度</th>
                                <th width="50%">原因</th>
                                <th width="10%">通知</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($customs as $custom) {
                                if ($custom instanceof FinanceCustom) {
                                    ?>
                                    <tr>
                                        <td><?php echo $custom->id ?></td>
                                        <td><?php if ($custom->createtime) {
                                                echo date('Y-m-d H:i', $custom->createtime);
                                            } else if ($custom->created_at) {
                                                echo substr($custom->created_at, 0, 16);
                                            }?>
                                        </td>
                                        <td><?= href_user_detail($custom->debtor, $custom->user_name)?></td>
                                        <td><?= $custom->amount?>元</td>
                                        <td><?= $custom->reason?></td>
                                        <td><?= href_notify_create(NOTIFY_TYPE_FINANCE_CUSTOM_DEBTOR, $custom->id, '已扣除')?></td>
                                    </tr>
                                <?php
                                }
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--End Basic Table-->

                <!--<!--Pegination Centered-->
                <div class="text-center">
                    <?php echo $customs->appends($queries)->links(); ?>
                </div>
                <!--End Pegination Centered-->
            </div>
        </div>
    </div>
</div>