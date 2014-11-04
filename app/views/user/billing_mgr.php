<?php use Sports\Constant\Finance as FinanceConstant; ?>
<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="panel">
            <div class="col-md-12 panel-heading">
                <?= Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
                <?= Form::model($queries) ?>
                <div class="form-group">
                    <?= Form::input('text', 'billing_created_time_start', null,
                        array('class' => 'form-control datepicker', 'placeholder' => '创建开始时间'))?>
                </div>
                -
                <div class="form-group">
                    <?= Form::input('text', 'billing_created_time_end', null,
                        array('class' => 'form-control datepicker', 'placeholder' => '创建结束时间'))?>
                </div>
                <div class="form-group">
                    <?= Form::input('text', 'user_name', null,
                        array('class' => 'form-control', 'placeholder' => '用户、场馆昵称'))?>
                </div>
                <div class="form-group">
                    <?= Form::input('text', 'hall_name', null,
                        array('class' => 'form-control', 'placeholder' => '场馆名称'))?>
                </div>
                <div class="form-group">
                    <?= Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
                </div>
                <?php echo Form::close() ?>
            </div>
            <div class="col-md-12 panel-body">
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
                <div class="panel panel-green margin-bottom-40">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>变更id</th>
                                <th>用户名</th>
                                <th>变更时间</th>
                                <th width="50%">详情</th>
                                <th>变更额度</th>
                                <th>变更后额度</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($billingStagings as $billingStaging) {
                                if ($billingStaging instanceof BillingStaging) {
                                    ?>
                                    <tr>
                                        <td><?php echo $billingStaging->id ?></td>
                                        <td><?= href_user_detail($billingStaging->user_id, $billingStaging->user_name)?></td>
                                        <td><?php echo date('Y-m-d H:i', $billingStaging->billing_created_time) ?></td>
                                        <td>
                                            <?php if (isset(FinanceConstant::$relationTypeOptions[$billingStaging->relation_type])) { ?>
                                                <?php echo FinanceConstant::$relationTypeOptions[$billingStaging->relation_type] ?>
                                            <?php } ?>
                                            <?php if ($billingStaging->relation_type == 5) { ?>
                                                <?php if ($billingStaging->recharge_type == 1) { ?>
                                                    支付宝充值：支付宝帐号为
                                                <?php } ?>
                                                <?php echo $billingStaging->recharge_token ?>
                                            <?php } else if ($billingStaging->recharge_type == 9) { ?>
                                                俱乐部培训活动
                                            <?php } else if ($billingStaging->relation_type == 10 or $billingStaging->relation_type == 11) { ?>
                                                <?php echo $billingStaging->finance_custom_reason ?>
                                            <?php } else { ?>
                                                <?php echo date('Y-m-d', $billingStaging->booking_event_date) ?>日 <?php echo $billingStaging->hall_name ?>
                                                <?php if (!empty($billingStaging->instant_order_court_number)) { ?><?php echo $billingStaging->instant_order_court_number ?>号场地<?php } ?>
                                                <?php echo $billingStaging->booking_start_time ?>点到<?php echo $billingStaging->booking_end_time ?>点打球活动
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php echo intval($billingStaging->account_change) ?>
                                            <?php echo $queries['purpose'] == FinanceConstant::PURPOSE_ACCOUNT ? "元" : "分" ?>
                                        </td>
                                        <td>
                                            <?= intval($billingStaging->account_after)?>
                                            <?php echo $queries['purpose'] == FinanceConstant::PURPOSE_ACCOUNT ? "元" : "分" ?>
                                        </td>
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
                    <?php echo $billingStagings->appends($queries)->links(); ?>
                </div>
                <!--End Pegination Centered-->
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    seajs.use('datetimePicker', function(){
        $('.datepicker').datetimepicker({
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            startView: 2,
            minView: 2
        });
    });
</script>