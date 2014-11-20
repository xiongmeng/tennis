<!--=== Content ===-->
<div class="container" id="workspace">
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default" id="order">
                <div class="panel-heading">订单信息</div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-3">用户：</label>

                            <div class="col-md-9">
                                <input class="form-control" placeholder="预约人昵称" type="text"
                                       data-bind="value:user().nickname" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">场馆：</label>

                            <div class="col-md-9">
                                <input class="form-control" placeholder="场馆名称" type="text" data-bind="value:hall().name"
                                       disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">日期：</label>

                            <div class="col-md-9" data-bind="phpTsToDate: event_date">
                                <input class="form-control datepicker" placeholder="活动日期" type="text"
                                       data-bind="value:event_date.date" disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">时段：</label>

                            <div class="col-md-4">
                                <select class="form-control" data-bind="options:start_time_option, value:start_time"
                                        disabled></select>
                            </div>
                            <div class="col-md-1">
                                <p class="form-control-static">-</p>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" data-bind="options:end_time_option, value:end_time"
                                        disabled></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">片数：</label>

                            <div class="col-md-9">
                                <select class="form-control" data-bind="options:court_num_option, value:court_num"
                                        disabled></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">金额：</label>

                            <div class="col-md-9">
                                <input class="form-control" placeholder="金额" type="text" data-bind="value:cost"
                                       disabled>
                            </div>
                        </div>

                        <div class="form-group">
                            <?php $fsm = new ReserveOrderFsm($order); ?>
                            <?php if ($fsm->can('book_fail')) { ?>
                                <div class="col-md-3 pull-right">
                                    <a class="btn btn-primary" href="/reserve/operate/<?= $order->id ?>/book_fail">预订失败</a>
                                </div>
                            <?php } ?>

                            <?php if ($fsm->can('book_success')) { ?>
                                <div class="col-md-3 pull-right">
                                    <a class="btn btn-primary" href="/reserve/operate/<?= $order->id ?>/book_success"
                                       >预订成功</a>
                                </div>
                            <?php } ?>

                            <?php if ($fsm->can('pay_success')) { ?>
                                <div class="col-md-3 pull-right">
                                    <a class="btn btn-primary" href="/reserve/operate/<?= $order->id ?>/pay_success"
                                       >待支付</a>
                                </div>
                            <?php } ?>

                            <?php if ($fsm->can('cancel')) { ?>
                                <div class="col-md-3 pull-right">
                                    <a class="btn btn-primary" href="/reserve/operate/<?= $order->id ?>/cancel"
                                       >取消订单</a>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">通知用户：</label>

                            <div class="col-md-9 pull-right">
                                <?= href_notify_create(NOTIFY_TYPE_ORDER_FAILED, $order->id, '无场地') ?> |
                                <?= href_notify_create(NOTIFY_TYPE_ORDER_UNPAY, $order->id, '预订') ?> |
                                <?= href_notify_create(NOTIFY_TYPE_ORDER_PAYED, $order->id, '支付') ?> |
                                <?= href_notify_create(NOTIFY_TYPE_ORDER_CANCEL, $order->id, '取消') ?>                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="panel panel-default" id="user" data-bind="with: user">
                <div class="panel-heading">预订人信息</div>
                <div class="panel-body">
                    <p>ID： <span data-bind="text:user_id"></span></p>

                    <p>手机号： <span data-bind="text:telephone"></span></p>

                    <p>昵称： <a data-bind="attr:{href:detail_url}, text:nickname" target="_blank"></a></p>

                    <p>会员类型： <span data-bind="text:privilege.text"></span></p>

                    <p>账户余额： <span data-bind="text:balance"></span></p>
                </div>
            </div>

            <div class="panel panel-default" id="hall" data-bind="with: hall">
                <div class="panel-heading">预订场馆信息</div>
                <div class="panel-body">
                    <p>名称： <a data-bind="attr:{href:detail_url}, text:name" target="_blank"></a></span></p>

                    <p>地址： <span><?= area_hall($order['hall']) ?></span></p>

                    <p>电话： <span data-bind="text:telephone"></span></p>

                    <p>联系人： <span data-bind="text:linkman"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    seajs.use(['hall/list', 'user/list', 'reserve_order/order'], function (HallList, UserList, ReserveOrder) {
        var reserveOrder = new ReserveOrder(<?= json_encode($order)?>);
        ko.applyBindings(reserveOrder, $('#workspace')[0]);
    });

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
