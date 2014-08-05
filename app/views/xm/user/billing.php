
<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php echo Form::model($queries, array('method' => 'GET')) ?>
            <?php echo Form::label('日期：') ?><?php echo Form::input('text', 'seller') ?>
            <?php echo Form::submit('查询') ?>
            <?php echo Form::close() ?><br/>

            <div class="tab-v1">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="">账户</a></li>
                    <li><a href="">积分</a></li>
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
                            if ($billingStaging instanceof BillingStaging) { ?>
                                <tr>
                                    <td><?php echo $billingStaging->id ?></td>
                                    <td><?php echo date('Y-m-d H:i', $billingStaging->billing_created_time) ?></td>
                                    <td>
                                        <?php echo \Sports\Constant\Finance::$relationTypeOptions[$billingStaging->relation_type] ?>
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
                                            <?php echo date('Y-m-d', $billingStaging->booking_event_date) ?>日
                                            <?php echo $billingStaging->booking_start_time ?>点到<?php echo $billingStaging->booking_end_time ?>点打球活动
                                        <?php } ?>
                                    </td>
                                    <td><?php echo intval($billingStaging->account_change) ?>元</td>
                                </tr>
                            <?php }
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