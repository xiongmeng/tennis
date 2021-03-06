<?php use Sports\Constant\Finance as FinanceConstant; ?>
<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12 margin-bottom-10">
            <?php echo Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
            <?php echo Form::model($queries) ?>
            <div class="form-group">
                <?php echo Form::input('text', 'billing_created_time_start', null,
                    array('class' => 'form-control datepicker', 'placeholder' => '创建开始时间', 'data-datepicker' => 'datepicker'))?>
            </div>
            -
            <div class="form-group">
                <?php echo Form::input('text', 'billing_created_time_end', null,
                    array('class' => 'form-control datepicker', 'placeholder' => '创建结束时间', 'data-datepicker' => 'datepicker'))?>
            </div>
            <div class="form-group">
                <?php echo Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
            </div>
            <?php echo Form::close() ?>
        </div>
    </div>
    <div class="row">
        <p class="col-md-12 bg-light alert-warning" style="font-size: 16px;margin-left: 15px;margin-bottom: 15px;">
            当前余额：<strong><?=cache_balance()?></strong> 元，当前积分：<strong><?= cache_points()?></strong> 分</p>
    </div>
    <div class="row">
        <div class="col-md-12">
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
                            <th>变更时间</th>
                            <th>详情</th>
                            <th>金额</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($billingStagings as $billingStaging) {
                            if ($billingStaging instanceof BillingStaging) {
                                ?>
                                <tr>
                                    <td><?php echo $billingStaging->id ?></td>
                                    <td><?php echo date('Y-m-d H:i', $billingStaging->billing_created_time) ?></td>
                                    <td>
                                        <?php if (isset(FinanceConstant::$relationTypeOptions[$billingStaging->relation_type])) { ?>
                                            <?php echo FinanceConstant::$relationTypeOptions[$billingStaging->relation_type] ?>
                                        <?php } ?>
                                        <?php if ($billingStaging->relation_type == 5) { ?>
                                            <?php if ($billingStaging->recharge_type == PAY_TYPE_ALI) { ?>
                                                支付宝充值：支付宝帐号为
                                            <?php }elseif($billingStaging->recharge_type == PAY_TYPE_WE_CHAT){ ?>
                                                微信支付：微信open_id为
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

<script type="text/javascript">
    seajs.use('datetimePicker', function () {
        $('.datepicker').datetimepicker({
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            startView: 2,
            minView: 2
        });
    });
</script>