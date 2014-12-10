
<!--=== Content ===-->
<div class="container" id="workspace">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary" id="order">
                <div class="panel-heading">创建订单</div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-2">用户：</label>
                            <div class="col-md-9">
                                <input class="form-control" placeholder="预约人昵称" type="text" data-bind="value:user().nickname" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">场馆：</label>
                            <input type="hidden" id="hall_id" data-bind="value:hall_id">

                            <div class="col-md-9">
                                <select class="combobox form-control">
                                    <option></option>
                                    <?php foreach($halls as $hall){?>
                                        <option value="<?= $hall->id?>" <?= $hall->id == $order['hall_id'] ? 'selected' : '';?>><?= $hall->name?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">日期：</label>

                            <div class="col-md-9" data-bind="phpTsToDate: event_date">
                                <input class="form-control datepicker" placeholder="活动日期" type="text" data-bind="value:event_date.date">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">时段：</label>

                            <div class="col-md-4">
                                <select class="form-control" data-bind="options:start_time_option, value:start_time"></select>
                            </div>
                            <div class="col-md-1">
                                <p class="form-control-static">-</p>
                            </div>
                            <div class="col-md-4">
                                <select class="form-control" data-bind="options:end_time_option, value:end_time"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">片数：</label>

                            <div class="col-md-9">
                                <select class="form-control" data-bind="options:court_num_option, value:court_num"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">金额：</label>

                            <div class="col-md-9">
                                <input class="form-control" placeholder="金额" type="text" data-bind="value:cost" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 pull-right">
                                <button class="btn btn-primary" data-bind="click:confirm,
                                    enable:user() && hall() && event_date() && start_time() && end_time() && court_num() && cost()">预订场地</button>
                            </div>

                            <div class="col-md-3 pull-right">
                                <button class="btn btn-warning" data-bind="click:calculate,
                                    enable:user() && hall() && event_date() && start_time() && end_time() && court_num()">计算金额</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-danger" id="order">
                <div class="panel-heading">温馨提示</div>
                <div class="panel-body" style="text-indent: 30px">
                    <p>预订成功后，您会收到一条确认短信。收到短信后，请您在30分钟内登录网球通完成支付。如果您不方便上网进行支付，可以拨打4000 66 5189，授权网球通客服人员代您完成支付；</p>
                    <p>如果您的账户余额不足，您会收到通知短信，请您尽快充值，确保您可以及时完成支付；</p>
                    <p>如果取消预订，请提前24小时致电4000 66 5189；</p>
                    <p>您可以先与场馆确认场地并为您预留，再通过网球通完成预订，可以提高预订成功率。</p>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="dialog-confirm" tabindex="-1"
     role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" aria-describedby="hello">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">确认预订场地</h4>
            </div>
            <div class="modal-body">
                <p>场馆：<span data-bind="text: hall().name"></span></p>
                <p>日期：<span data-bind="text: event_date.date().substr(0, 10)"></span></p>
                <p>时段：<span data-bind="text: start_time() + '-' + end_time()"></span>时</p>
                <p>场地：<span data-bind="text: court_num"></span>片</p>
                <p>费用：<span data-bind="text: cost"></span>元</p>
            </div>
            <div class="modal-footer">
                <a class="btn btn-default" data-dismiss="modal">关闭</a>
                <a data-bind="click: create" class="btn btn-primary" target="_blank">确认预订</a>
            </div>
        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    seajs.use(['hall/hall', 'reserve_order/order'], function (Hall, ReserveOrder) {
        var order = <?= json_encode($order)?>;

        var reserve = new ReserveOrder(order);
        reserve.callback_saved = function(createdOrder){
            window.location.href = '/reserve/frontend/list';
        };

        reserve.hall_id.subscribe(function(hallId){
            refreshHall(hallId);
        });

        reserve.confirm = function(){
            $('#dialog-confirm').modal();
//            var data = reserve.generateOrderData();
//            if(confirm('确认预订' + reserve.hall().name() + " " + reserve.event_date.date().substr(0, 10) + " " + reserve.start_time() + '到'
//                + reserve.end_time() + '的' + reserve.court_num() + '片场地， 共计' + reserve.cost() + '元' )){
//                reserve.create();
//            }
        };

        ko.applyBindings(reserve, $('#workspace')[0]);
        ko.applyBindings(reserve, $('#dialog-confirm')[0]);


        if(order['hall_id']){
            refreshHall(order['hall_id']);
        }

        function refreshHall(hallId){
            var defer = $.restGet('/hall/detail/' + hallId);
            defer.done(function(res, data){
                reserve.hall(new Hall(data));
            });
        }
    });


    $(document).ready(function () {
        seajs.use(['datetimePicker', 'combobox', 'util'], function () {
            $('.datepicker').datetimepicker({
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                startView: 2,
                minView: 2,
                startDate: new Date().format('yyyy-MM-dd')
            });
            $('.combobox').combobox({target: $('#hall_id')});
        });
    });
</script>
