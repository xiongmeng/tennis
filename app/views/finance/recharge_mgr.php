
<!--=== Content ===-->
<div class="container" id="workspace">
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default" id="order">
                <div class="panel-heading">补款信息</div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-3">用户：</label>
                            <div class="col-md-9">
                                <input class="form-control" placeholder="需要补款的用户" type="text" data-bind="value:user().nickname" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">额度：</label>

                            <div class="col-md-9">
                                <input class="form-control" placeholder="补款额度" type="text" data-bind="value:money">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 pull-right">
                                <button class="btn btn-primary" data-bind="click:recharge, enable:user() && money()">确认补款</button>
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
                        <tr data-bind="click: $parent.select,css:{success:user_id()==$root.user().user_id()}">
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
        </div>
    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    seajs.use(['user/list', 'user/user', 'rest'], function (UserList, User, Rest) {
        var recharge = <?= json_encode($recharge)?>;

        var model = {};
        model.user = ko.observable(new User({}));
        model.money = ko.observable(500);
        model.recharge = function(){
            if(confirm('确认要给用户 ' + model.user().nickname() + ' 补款 ' + model.money() + ' 元吗？')){
                var defer = $.restPost('/finance/recharge', {user_id: model.user().user_id(), money: model.money()});
                defer.done(function(){
                    window.location.href = '/user/detail/' + model.user().user_id();
                });
                defer.fail(function(msg){
                    alert(msg);
                });
            }
        };

        model.userList = new UserList();
        model.userList.select = function(user){
            model.user(user);
        };
        model.userList.userList.subscribe(function(newValue){
            model.userList.userList().length && model.user(model.userList.userList()[0]);
        });

        ko.applyBindings(model, $('#workspace')[0]);

        if(recharge.user_id){
            model.userList.queries.user_id(recharge.user_id);
            model.userList.search();
        }
    });
</script>
