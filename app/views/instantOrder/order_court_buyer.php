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
                        <a href="/order_court_buyer/<?= $hallID ?>?date=<?= $date ?>">
                            <h4><?=date('m-d', $time)?>&nbsp;<?= $weekdayOption[date('w', $time)]; ?></h4>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </div>

        <div class="col-md-10 table-responsive pin-container" id="table_court">
            <!-- ko if: statistics.total()<=0 -->
            <div class="alert alert-info"><strong>没有可出售的场地！</strong></div>
            <!-- /ko -->
            <!-- ko if: statistics.total()>0 -->
            <div class="worktable-toolbar pinned row">
                <a class="btn btn-danger btn-lg" data-bind="click: batchBuy, css:{disabled: currentState()!='on_sale'}">预订</a>
                <a class="btn btn-danger btn-lg" data-bind="click: batchPay, css:{disabled: currentState()!='paying'}">支付</a>
                <a class="btn btn-danger btn-lg" data-bind="click: batchCancelBuy, css:{disabled: currentState()!='paying'}">取消预订</a>
            </div>
            <table class="table-court">
                <thead>
                <tr>
                    <th></th>
                    <!-- ko foreach:courts-->
                    <th>
                        <span class="court disabled" data-bind="text: number()+'号场'"></span>
                    </th>
                    <!-- /ko-->
                </tr>
                </thead>
                <tbody>

                <!-- ko foreach: instantOrdersByHours -->
                <tr>
                    <td>
                        <span class="hour disabled" data-bind="text: start() + '-' + end()"></span>
                    </td>
                    <!-- ko foreach: instantOrders -->
                    <td>
                        <!-- ko switch: true -->
                            <!-- ko case: state() == 'on_sale' -->
                            <span class="instant-order buy" data-bind="click: $root.select, css: {active: select}, text: quote_price() + '￥'"></span>
                            <!-- /ko -->

                            <!-- ko case: state() == 'paying' -->
                                <!-- ko if: $root.loginUserId()==buyer() -->
                                <span class="instant-order paying" data-bind="click: $root.select,
                                    css: {active: select}">
                                    <span style="font-size: small">￥</span><span data-bind="text: quote_price"></span>
                                    <em style="color: red" class="countDown" data-bind="attr: {'data-time': expire_time()+60}"></em>
                                </span>
                                <!-- /ko -->
                            <!-- /ko -->

                            <!-- ko case: state() == 'payed' -->
                                <!-- ko if: $root.loginUserId()==buyer() -->
                                <span class="instant-order" style="background-color: #f0ad4e" data-bind="">待打球</span>
                                <!-- /ko -->
                            <!-- /ko -->

                            <!-- ko case: $default -->
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

<!-- Modal -->
<div class="modal fade" id="dialog-go-to-pay" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" aria-describedby="hello">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">余额不够啦</h4>
            </div>
            <div class="modal-body">
                <p>总共需要花费<mark data-bind="text: needPay"></mark>元</p>
                <p>您当前可用余额<mark data-bind="text: balance"><mark>元</p>
                <p>您还需要支付<mark data-bind="text: needRecharge"></mark>元</p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">关闭</a>
                <a data-bind="attr:{href: adviseForwardUrl}" class="btn btn-primary" target="_blank">去支付</a>
            </div>
        </div>
    </div>
</div>

<script>
    seajs.use('court/manage', function(courtManage){
        courtManage.init($('#table_court')[0], <?= json_encode($worktableData)?>);
        $(".pinned").pin({'containerSelector' : '.pin-container', padding:{top: 5}});

        $('.countDown').kkcountdown({callback:function(){window.location.reload()}});
    });
</script>