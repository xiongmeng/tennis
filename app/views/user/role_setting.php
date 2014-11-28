<!--=== Content ===-->
<div class="container" id="workspace">
    <div class="row">
        <div class="col-md-5">
            <div class="panel panel-default" id="order">
                <div class="panel-heading">角色信息</div>
                <div class="panel-body" data-bind="with:user">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>角色</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody data-bind="foreach: roles">
                        <tr>
                            <td data-bind="text:id"></td>
                            <td>
                                <select class="form-control"
                                        data-bind="options:role_id.options,value:role_id,
                            optionsText:'name',optionsValue:'id',optionsCaption: '请选择角色'"></select>
                            </td>
                            <td>
                                <div class="btn-toolbar">
                                    <button class="btn btn-primary"
                                            data-bind="click:$parent.saveRole, enable:role_id()">
                                        保存
                                    </button>
                                    <button class="btn btn-danger" data-bind="click:$parent.deleteRole, enable:id()">删除</button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
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
                        <div class="form-group" style="width: 15%">
                            <input class="form-control" placeholder="用户ID" type="text" data-bind="value:user_id">
                        </div>
                        <div class="form-group" style="width: 20%">
                            <input class="form-control" placeholder="用户昵称" type="text" data-bind="value:nickname">
                        </div>
                        <div class="form-group" style="width: 20%">
                            <input class="form-control" placeholder="手机号码" type="text" data-bind="value:telephone">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary"
                                    data-bind="click:$parent.search,enable:nickname() || telephone()">搜索
                            </button>
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
        var user_id =<?=json_encode(Input::get('user_id'));?>;
        var model = {};
        model.user = ko.observable(new User({}));

        model.userList = new UserList({}, {}, {relations: 'roles'});
        model.userList.select = function (user) {
            model.user(user);
        };
        model.userList.userList.subscribe(function (newValue) {
            model.userList.userList().length && model.user(model.userList.userList()[0]);
        });

        ko.applyBindings(model, $('#workspace')[0]);

        if (user_id) {
            model.userList.queries.user_id(user_id);
            model.userList.search();
        }
    });
</script>
