<!--=== Content ===-->

<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-md-12" id="worktable">

            <div class="panel panel-default" id="price">
                <div class="panel-heading">法定节假日设定</div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="8%">日期</th>
                            <th width="10%">类型</th>
                            <th width="10%">操作</th>
                            <th width="10%">创建时间</th>
                            <th width="10%">修改时间</th>
                        </tr>
                        </thead>
                        <tbody data-bind="foreach: holidays">
                        <tr>
                            <td data-bind="text:id"></td>
                            <td class="has-success">
                                <input type="text" class="form-control datepicker" data-bind="value:date.date">
                            </td>

                            <td>
                                <select class="form-control"
                                        data-bind="options:type.options, optionsText:'name', optionsValue:'id', value:type"></select>
                            </td>

                            <td>
                                <div class="btn-toolbar">
                                    <button class="btn btn-primary" data-bind="click:$root.save, enable:date()&&type()">
                                        保存
                                    </button>
                                    <button class="btn btn-danger" data-bind="click:$root.remove, enable:id()">
                                        删除
                                    </button>
                                </div>
                            </td>
                            <td data-bind="text:created_at"></td>
                            <td data-bind="text:updated_at"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    seajs.use(['hall/holiday', 'datetimePicker'], function (HolidayModel, datePicker) {
        var holidays = <?= json_encode($holidays)?>;
        var hall = new HolidayModel(holidays);
        ko.applyBindings(hall, $('#worktable')[0]);

        $('.datepicker').datetimepicker({
            format: 'yyyy-mm-dd',
            language: 'zh-CN',
            startView: 2,
            minView: 2
        });
    });
</script>