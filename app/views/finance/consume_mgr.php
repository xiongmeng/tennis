
<!--=== Content ===-->
<div class="container" id="workspace">
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default" id="order">
                <div class="panel-heading">扣款信息</div>
                <div class="panel-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-md-3">用户：</label>
                            <div class="col-md-9">
                                <input class="form-control" placeholder="需要扣款的用户" type="text" data-bind="value:debtor().nickname" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">额度：</label>

                            <div class="col-md-9">
                                <input class="form-control" placeholder="扣款额度" type="text" data-bind="value:amount">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">扣款原因：</label>

                            <div class="col-md-9">
                                <textarea class="form-control" placeholder="扣款原因" data-bind="value:reason">

                                </textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 pull-right">
                                <button class="btn btn-primary" data-bind="click:consume, enable:debtor() && amount() && reason()">确认补款</button>
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
                        <tr data-bind="click: $parent.select,css:{success:user_id()==$root.debtor().user_id()}">
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
        var custom = <?= json_encode($custom)?>;

        var model = {};
        model.debtor = ko.observable(new User({}));
        model.amount = ko.observable();
        model.reason = ko.observable();

        model.consume = function(){
            if(confirm('确认要扣除用户 ' + model.debtor().nickname() + '  ' + model.amount() + ' 元吗？')){
                var defer = $.restPost('/finance/consume', {
                    debtor: model.debtor().user_id(), amount: model.amount(), reason: model.reason()
                });
                defer.done(function(){
                    window.location.href = '/user/detail/' + model.debtor().user_id();
                });
                defer.fail(function(msg){
                    alert(msg);
                });
            }
        };

        model.userList = new UserList();
        model.userList.select = function(user){
            model.debtor(user);
        };
        model.userList.userList.subscribe(function(newValue){
            model.userList.userList().length && model.debtor(model.userList.userList()[0]);
        });

        ko.applyBindings(model, $('#workspace')[0]);

        if(custom.debtor){
            model.userList.queries.user_id(custom.debtor);
            model.userList.search();
        }
    });
</script>
