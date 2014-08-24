<div class="container ">
    <div class="row margin-bottom-20">
        <div class="tab-v1 col-xs-12 col-md-12">
            <ul class="nav nav-tabs">
                <?php foreach ($halls as $hall) { ?>
                    <li role="presentation" class="<?php if ($hall->id == $hallID) { ?>active<?php } ?>"><a
                            href="\order_court_manage?hall_id=<?php echo $hall->id ?>&court_id=">
                            <h6><?php echo $hall->name; ?></h6></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="tab-v3 col-md-2">
            <ul class="nav nav-pills nav-stacked">
                <?php foreach ($dates as $date => $time) { ?>
                    <li class="<?php if ($date == $activeDate) { ?>active<?php } ?>">
                        <a href="/order_court_manage?hall_id=<?= $hallID ?>&date=<?= $date ?>">
                            <?= date('m-d', $time) ?>&nbsp;<?= $weekdayOption[date('w', $time)]; ?>
                        </a>
                    </li>

                <?php } ?>
            </ul>
        </div>
        <div class="table-responsive pin-container col-md-10" id="table_court">
            <!-- ko if: statistics.total()<=0 -->
            <div class="alert alert-info"><strong>没有可出售的场地！</strong></div>
            <!-- /ko -->
            <!-- ko if: statistics.total()>0 -->

            <div class="worktable-toolbar pinned row">
                <a class="btn btn-danger btn-lg" data-bind="click: batchOnline, css:{disabled: currentState()!='draft'}">上架</a>
                <a class="btn btn-danger btn-lg" data-bind="click: batchOffline, css:{disabled: currentState()!='on_sale'}">下架</a>
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
                    <!-- ko switch: true -->
                        <!-- ko case: state()=='draft' -->
                        <span class="instant-order online" data-bind="click: $root.select, css: {active: select}">&nbsp;</span>
                        <!-- /ko -->

                        <!-- ko case: state()=='on_sale' -->
                        <span class="instant-order offline" data-bind="click: $root.select, css: {active: select}">待售</span>
                        <!-- /ko -->

                        <!-- ko case: state()=='waste' || state()=='expired' -->
                        <span class="instant-order waste">&nbsp;</span>
                        <!-- /ko -->

                        <!-- ko case: state()=='paying' -->
                        <span class="instant-order living"><span>支付中</span>(<em style="color: red" class="countDown" data-bind="attr: {'data-time': expire_time()+60}"></em>)</span>
                        <!-- /ko -->

                        <!-- ko case: $else -->
                        <span class="instant-order living">已售</span>
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

<!--<script type="text/javascript" src="/assets/plugins/kkcountdown/js/build/kkcountdown.js"></script>-->

<script>
    seajs.use('court/manage', function(courtManage){
        courtManage.init($('#table_court')[0], <?= json_encode($worktableData)?>, {'submitUrl':'/hall/instantOrder/batchOperate'});
        $(".pinned").pin({'containerSelector' : '.pin-container', padding:{top: 5}});

        $('.countDown').kkcountdown({callback:function(){window.location.reload()}});
    });
</script>