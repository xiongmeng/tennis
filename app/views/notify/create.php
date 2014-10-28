<div class="container">
    <div class="row" id="worktable">
        <div class="col-md-8">
            <form class="form-horizontal" role="form">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">通知类型</label>

                    <div class="col-sm-10">
                        <select class="form-control" data-bind="options: events,
                                   optionsText: function(item){return item.name()},
                                   optionsValue: function(item){return item.id},
                                   value: event">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">关联id</label>

                    <div class="col-sm-10">
                        <input class="form-control" id="inputEmail3" data-bind="value:object">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">渠道</label>

                    <div class="col-sm-10">
                        <select class="form-control" data-bind="options: channels,
                                   optionsText: function(item){return item.name()},
                                   optionsValue: function(item){return item.id},
                                   value: channel">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">接收方</label>

                    <div class="col-sm-10">
                        <input class="form-control" data-bind="value:who()?who():'不能发送，因为用户没有手机号或者微信号'" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">接收信息</label>

                    <div class="col-sm-10">
                        <textarea class="form-control" rows="3" data-bind="text:msg"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-5 col-md-offset-2">
                        <button class="btn btn-primary btn-block" data-bind="click:send">发送</button>
                    </div>
                    <div class="col-md-5">
                        <a class="btn btn-warning btn-block" target="_blank" data-bind="attr:{href:'/notify/record?event='+event()+'&object='+object()}">查看发送历史</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    seajs.use('notify/notify', function (notify) {
        notify.init($('#worktable')[0],
            <?= json_encode(array('events' => $events, 'object' => $object, 'channels' => $channels))?>);
    });
</script>