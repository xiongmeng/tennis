<!--=== Content ===-->
<div class="container " xmlns="http://www.w3.org/1999/html">
    <div class="row margin-bottom-20">
        <div class="tab-v1 col-xs-12 col-md-12">
            <ul class="nav nav-tabs">
                <?php foreach ($halls as $hall) { ?>
                    <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                            href="\order_court_buyer?hall_id=<?php echo $hall->id ?>&court_id=">
                            <h6><?php echo $hall->name; ?></h6></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="tab-v3 col-md-2">
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <?php foreach ($dates as $date => $time) { ?>
                    <li class="<?php if ($date == $activeDate) { ?>active<?php } ?>">
                        <a href="/order_court_buyer?hall_id=<?= $hallID ?>&date=<?= $date ?>">
                            <h4><?=date('m-d', $time)?>&nbsp;<?= $weekdayOption[date('w', $time)]; ?></h4>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </div>

        <div class="col-md-10 table-responsive" id="table_court">
            <!-- ko if: statistics.total()<=0 -->
            <div class="alert alert-info"><strong>没有可出售的场地！</strong></div>
            <!-- /ko -->
            <!-- ko if: statistics.total()>0 -->
            <div class="btn-group" id="stickUp">
                <a class="btn btn-primary btn-lg" data-bind="click: submitSelected">提交</a>
                <a class="btn btn-danger btn-lg" data-bind="click: cancelSelected">取消选取</a>
            </div>

            <table class="table-court">
                <thead>
                <tr>
                    <th></th>
                    <!-- ko foreach:courts-->
                    <th>
                        <span class="court" data-bind="text: number()+'号场'"></span>
                    </th>
                    <!-- /ko-->
                </tr>
                </thead>
                <tbody>

                <!-- ko foreach: instantOrdersByHours -->
                <tr>
                    <td>
                        <span class="hour" data-bind="text: start() + '-' + end()"></span>
                    </td>
                    <!-- ko foreach: instantOrders -->
                    <td>
                        <!-- ko switch: state() -->
                            <!-- ko case: 'on_sale' -->
                            <span class="instant-order on_sale" data-bind="click: $root.select, css: {active: select}, text: quote_price() + '￥'"></span>
                            <!-- /ko -->

                            <!-- ko case: '$default' -->
                            <span class="instant-order disabled"></span>
                            <!-- /ko -->
                        <!-- /ko -->
                    </td>
                    <!-- /ko -->
                </tr>
                <!-- /ko -->
                </tbody>
            </table>
            <!-- /ko -->
        </div>
    </div>
</div>

<script>
    seajs.use('court/manage', function(courtManage){
        courtManage.init($('#table_court')[0], <?= json_encode($worktableData)?>, {'submitUrl':'/instantOrder/batchBuy'});
        $("#stickUp").pin();
    });
</script>