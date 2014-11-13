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
                    <?= Form::select('stat', $status, null, array('class' => 'form-control'))?>
                </div>
                <div class="form-group">
                    <?= Form::select('type', $types, null, array('class' => 'form-control'))?>
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
                                <th>ID</th>
                                <th>创建时间</th>
                                <th>用户名</th>
                                <th>充值额度</th>
                                <th>充值方式</th>
                                <th>充值结果</th>
                                <th>通知</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($recharges as $recharge) {
                                if ($recharge instanceof Recharge) {
                                    ?>
                                    <tr>
                                        <td><?php echo $recharge->id ?></td>
                                        <td><?php if ($recharge->createtime) {
                                                echo date('Y-m-d H:i', $recharge->createtime);
                                            } else if ($recharge->created_at) {
                                                echo substr($recharge->created_at, 0, 16);
                                            }?>
                                        </td>
                                        <td><?= href_user_detail($recharge->user_id, $recharge->user_name)?></td>
                                        <td><?= $recharge->money?>元</td>
                                        <td><?= isset($types[$recharge->type]) ? $types[$recharge->type] : '未知'?></td>
                                        <td><?= isset($status[$recharge->stat]) ? $status[$recharge->stat] : '未知'?></td>
                                        <td><?= href_notify_create(NOTIFY_TYPE_RECHARGE, $recharge->id, '已充值')?></td>
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
                    <?php echo $recharges->appends($queries)->links(); ?>
                </div>
                <!--End Pegination Centered-->
            </div>
        </div>
    </div>
</div>