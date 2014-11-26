<!--=== Content ===-->
<div class="container" id="workspace" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default" id="order">
                <div class="panel-heading">创建约球信息</div>
                <div class="panel-body">
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label class="control-label col-md-3">时间：</label>

                            <div class="col-md-3" data-bind="phpTsToDate: event_date">
                                <input class="form-control datepicker" placeholder="活动日期" type="text"
                                       data-bind="value:event_date">
                            </div>

                            <div class="col-md-3 input-group">
                                <div class="input-group-addon">开始</div>
                                <select class="form-control"
                                        data-bind="options:start_hour.options, value:start_hour"></select>
                            </div>
                            <div class="col-md-3 input-group">
                                <div class="input-group-addon">结束</div>
                                <select class="form-control"
                                        data-bind="options:end_hour_option, value:end_hour"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">地点：</label>
                            <input type="hidden" id="hall_id" data-bind="value:hall_id">
                            <div class="col-md-6 input-group">
                                <div class="input-group-addon">场馆</div>
                                <select class="combobox form-control">
                                    <option></option>
                                    <?php foreach($halls as $hall){?>
                                        <option value="<?= $hall->id?>" <?= $hall->id == $seeking['hall_id'] ? 'selected' : '';?>><?= $hall->name?></option>
                                    <?php }?>
                                </select>
                            </div>

                            <div class="col-md-3 input-group">
                                <div class="input-group-addon">片数</div>
                                <select class="form-control"
                                        data-bind="options:court_num.options, value:court_num"></select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">要求：</label>

                            <div class="col-md-3 input-group">
                                <div class="input-group-addon">级别</div>
                                <select class="form-control"
                                        data-bind="options:tennis_level.options, optionsText:'name', optionsValue:'id', value:tennis_level"></select>
                            </div>
                            <div class="col-md-3 input-group">
                                <div class="input-group-addon">性别</div>
                                <select class="form-control"
                                        data-bind="options:sexy.options, optionsText:'name', optionsValue:'id', value:sexy"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">坑位：</label>

                            <div class="input-group col-md-3">
                                <div class="input-group-addon">共计</div>
                                <input class="form-control" data-bind="value:store">
                            </div>
                            <div class="input-group col-md-3">
                                <div class="input-group-addon">尚余</div>
                                <input class="form-control" data-bind="value:on_sale">
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">人均费用：</label>

                            <div class="col-md-9 input-group">
                                <input class="form-control" placeholder="人均费用" type="text"
                                       data-bind="value:personal_cost">
                                <div class="input-group-addon">元</div>
                            </div>
                        </div>

<!--                        <div class="form-group">-->
<!--                            <label class="control-label col-md-3">活动形式：</label>-->
<!---->
<!--                            <div class="col-md-9" data-bind="foreach: content.options">-->
<!--                                <label class="checkbox-inline">-->
<!--                                    <input type="checkbox" data-bind="attr:{id:id}, value:id,checked:$parent.content">-->
<!--                                    <span data-bind="text:name"></span>-->
<!--                                </label>-->
<!--                            </div>-->
<!--                        </div>-->

                        <div class="form-group">
                            <label class="control-label col-md-3 ">备注：</label>

                            <div class="col-md-9">
                                <textarea class="form-control" data-bind="value:comment" placeholder="内容举例：单打、开新球、洗澡免费"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 pull-right">
                                <button class="btn btn-primary" data-bind="click:save">提交保存</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</div>

<!--=== End Content ===-->

<script type="text/javascript">
    seajs.use(['seeking/seeking'], function (Seeking) {
        var model = new Seeking(<?= json_encode($seeking)?>);
        ko.applyBindings(model, $('#workspace')[0]);
    });


    $(document).ready(function () {
        seajs.use(['datetimePicker', 'combobox'], function () {
            $('.datepicker').datetimepicker({
                format: 'yyyy-mm-dd',
                language: 'zh-CN',
                startView: 2,
                minView: 2
            });
            $('.combobox').combobox({target: $('#hall_id')});
        });
    });
</script>
