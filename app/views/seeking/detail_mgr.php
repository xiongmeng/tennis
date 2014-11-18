<!--=== Content ===-->
<div class="container" id="workspace" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default" id="order">
                <div class="panel-heading">约球信息</div>
                <div class="panel-body">
                    <form class="form-horizontal">

                        <div class="form-group">
                            <label class="control-label col-md-3">时间：</label>

                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= sprintf('%s日%s点-%s点', substr($seeking->event_date, 0, 10), $seeking->start_hour, $seeking->end_hour)?>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">地点：</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= sprintf('%s馆 %s片场地', $seeking->Hall->name, $seeking->court_num)?>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">要求：</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    级别：<span data-bind="text: tennis_level.text"></span>
                                    性别：<span data-bind="text: sexy.text"></span>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">坑位：</label>

                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= sprintf('共%s坑，剩余%s坑，已占%s坑', $seeking->store, $seeking->on_sale, $seeking->sold)?>
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">人均费用：</label>
                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= $seeking->personal_cost?>元
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">活动形式：</label>

                            <div class="col-md-9" data-bind="foreach: content.options">
                                <label class="checkbox-inline">
                                    <input type="checkbox" data-bind="attr:{id:id}, value:id,checked:$parent.content">
                                    <span data-bind="text:name"></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3 ">备注：</label>

                            <div class="col-md-9">
                                <p class="form-control-static">
                                    <?= $seeking->comment?>元
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 pull-right">
                                <a class="btn btn-primary" href="/seeking/join/<?= $seeking->id?>">我要报名</a>
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
