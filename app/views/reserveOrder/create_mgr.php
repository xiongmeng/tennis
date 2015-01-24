
<!--=== Content ===-->
<div class="container" id="workspace">
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default" id="order" data-bind="with: reserveOrder">
                <div class="panel-heading">订单信息</div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-3">用户：</label>
                            <div class="col-md-9">
                                <input class="form-control" placeholder="预约人昵称" type="text" data-bind="value:user().nickname" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">场馆：</label>

                            <div class="col-md-9">
                                <input class="form-control" placeholder="场馆名称" type="text" data-bind="value:hall().name" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">日期：</label>

                            <div class="col-md-9" data-bind="phpTsToDate: event_date">
                                <input class="form-control datepicker" placeholder="活动日期" type="text" data-bind="value:event_date.date">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">时段：</label>

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
                            <label class="control-label col-md-3">片数：</label>

                            <div class="col-md-9">
                                <select class="form-control" data-bind="options:court_num_option, value:court_num"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">金额：</label>

                            <div class="col-md-9">
                                <input class="form-control" placeholder="金额" type="text" data-bind="value:cost" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 pull-right">
                                <button class="btn btn-primary" data-bind="click:create,
                                    enable:user() && hall() && event_date() && start_time() && end_time() && court_num() && cost()">提交保存</button>
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

        <div class="col-md-7">
            <div class="panel panel-default" id="user" data-bind="with: userList">
                <div class="panel-heading">
                    <form class="form-inline" data-bind="with:queries">
                        <div class="form-group">
                            <label class="control-label" style="margin-right: 10px">搜索预约人</label>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="用户昵称" type="text" data-bind="value:nickname">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="手机号码" type="text" data-bind="value:telephone">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" data-bind="click:$parent.search,enable:nickname() || telephone()">搜索</button>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" data-bind="click:$parent.clear">清空列表</button>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>手机号</th>
                            <th>昵称</th>
                            <th>会员类型</th>
                            <th>账户余额</th>
                        </tr>
                        </thead>
                        <tbody data-bind="foreach: userList">
                        <tr data-bind="click: $parent.select,css:{success:user_id()==$root.reserveOrder.user().user_id()}">
                            <td data-bind="text:user_id"></td>
                            <td data-bind="text:telephone"></td>
                            <td><a data-bind="attr:{href:detail_url}, text:nickname" target="_blank"></a></td>
                            <td data-bind="text:privilege.text"></td>
                            <td data-bind="text:balance"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-default" id="hall" data-bind="with: hallList">
                <div class="panel-heading">
                    <form class="form-inline" data-bind="with:queries">
                        <div class="form-group">
                            <label class="control-label" style="margin-right: 10px">搜索预订场馆</label>
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="场馆名称" type="text" data-bind="value:name">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="场馆类型" type="text" data-bind="value:court_name">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary" data-bind="click:$parent.search,enable:name() || court_name()">搜索</button>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" data-bind="click:$parent.clear">清空列表</button>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>名称</th>
                            <th>地址</th>
                            <th>电话</th>
                            <th>场地</th>
                        </tr>
                        </thead>
                        <tbody data-bind="foreach: data">
                            <tr data-bind="click: $parent.select,css:{success:id()==$root.reserveOrder.hall().id()}">
                                <td><a data-bind="attr:{href:detail_url}, text:name" target="_blank"></a></td>
                                <td data-bind="text:area"></td>
                                <td data-bind="text:telephone"></td>
                                <td><span data-bind="text:court_name"></span>:<span data-bind="text:court_num"></span>片</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    seajs.use(['hall/list', 'user/list', 'reserve_order/order'], function (HallList, UserList, ReserveOrder) {
        var order = <?= json_encode($order)?>;

        var model = {};
        model.reserveOrder = new ReserveOrder(order);
        model.reserveOrder.callback_saved = function(createdOrder){
            window.location.href = '/reserve/detail/' + createdOrder.id;
        };

        model.userList = new UserList();
        model.userList.select = function(user){
            model.reserveOrder.user(user);
        };
        model.userList.userList.subscribe(function(newValue){
            model.userList.userList().length && model.reserveOrder.user(model.userList.userList()[0]);
        });

        model.hallList = new HallList({per_page: 20, model: 'page'});
        model.hallList.select = function(hall){
            model.reserveOrder.hall(hall);
        };
        model.hallList.data.subscribe(function(newValue){
            model.hallList.data().length && model.reserveOrder.hall(model.hallList.data()[0]);
        });

        ko.applyBindings(model, $('#workspace')[0]);

        if(order.user_id){
            model.userList.queries.user_id(order.user_id);
            model.userList.search();
        }

        if(order.hall_id){
            model.hallList.queries.id(order.hall_id);
            model.hallList.search();
            model.hallList.queries.id("");
        }
    });


    $(document).ready(function(){
        seajs.use('datetimePicker', function(){
            $('.datepicker').datetimepicker({
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                startView: 2,
                minView: 2
            });
        });
    });
</script>
