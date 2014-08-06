<?php use Sports\Constant\Finance as FinanceConstant; ?>
<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="panel">
            <div class="col-md-12 panel-heading">
                <?php echo Form::open(array('method' => 'GET', 'class' => 'form-inline')) ?>
                <?php echo Form::model($queries) ?>
                <div class="form-group">
                    <?php echo Form::input('text', 'billing_created_time_start', '',
                        array('class' => 'form-control', 'placeholder' => '创建开始时间', 'data-datepicker' => 'datepicker'))?>
                </div>
                -
                <div class="form-group">
                    <?php echo Form::input('text', 'billing_created_time_end', '',
                        array('class' => 'form-control', 'placeholder' => '创建结束时间', 'data-datepicker' => 'datepicker'))?>
                </div>
                <div class="form-group">
                    <?php echo Form::submit('查询', array('class' => 'btn-u btn-u-green')) ?>
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
                                            售出场地：<?php echo date('Y-m-d', $billingStaging->booking_event_date) ?>日 <?php echo $billingStaging->hall_name ?>
                                            <?php if (!empty($billingStaging->instant_order_court_number)) { ?><?php echo $billingStaging->instant_order_court_number ?>号场地<?php } ?>
                                            <?php echo $billingStaging->booking_start_time ?>点到<?php echo $billingStaging->booking_end_time ?>点
                                        </td>
                                        <td>
                                            <?php echo intval($billingStaging->account_change) ?>元
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
    //    $('.datepicker').datepicker();
</script>