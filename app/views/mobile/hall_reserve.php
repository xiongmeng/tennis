<div class="content" style="margin-top: 10px" id="workspace">
        <div class="form-controller">
            <div class="item">
                <a class="label">预订人</a>
                <input class="input-normal" type="text" readonly data-bind="value:user().nickname">
            </div>
        </div>

        <div class="form-controller">
            <div class="item">
                <a class="label">场馆</a>
                <input class="input-normal" type="text" readonly data-bind="value:hall().name">
            </div>
        </div>

        <div class="form-controller">
            <div class="item">
                <a class="label">日期</a>

                <select class="input-normal" data-bind="options:event_date.options, optionsText:'name', optionsValue:'id', value:event_date"></select>
            </div>
        </div>

        <div class="form-controller">
            <div class="item">
                <a class="label">时段</a>
                <select style="width: 31%;" data-bind="options:start_time_option, value:start_time"></select>
                <a style="width: 15%;font-size:15px;">&nbsp;-&nbsp;</a>
                <select style="width: 31%;" data-bind="options:end_time_option, value:end_time"></select>
            </div>
        </div>

        <div class="form-controller">
            <a class="label">片数</a>
            <select class="input-normal" data-bind="options:court_num_option, value:court_num"></select>
        </div>
        <div class="form-controller">
            <a class="label">金额(元)</a>
            <input style="width: 36%;font-size:15px;"
                   type="text" class="pull-left" placeholder="自动生成" readonly data-bind="value:cost">
            <input type="button" data-bind="click:calculate,
                                    enable:user() && hall() && event_date() && start_time() && end_time() && court_num()"
                   style="width: 31%; margin-left: 10px;font-size: 15px;height: 35px" class="btn btn-primary" value="计算金额">
        </div>
        <input type="button" style="width:90%; margin: 5px auto ;" class="btn btn-primary btn-block"
                data-bind="click:preview,
                    enable:user() && hall() && event_date() && start_time() && end_time() && court_num() && cost()"
               value="预订">

</div>


<div id="previewModal" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" onclick="$('#previewModal').removeClass('active')"></a>

        <h1 class="title">确认预订吗</h1>
    </header>

    <div class="content">
        <table id="order_order">
            <tr>
                <td class="bigclass_bg900" align="center">
                    <table>
                        <tr>
                            <td width="230" height="50" align="right">预订人：</td>
                            <td width="300" align="left" data-bind="text: user().nickname"></td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">预订场馆：</td>
                            <td align="left" data-bind="text: hall().name"></td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">活动日期：</td>
                            <td align="left" data-bind="text: event_date.text"></td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">时间段：</td>
                            <td align="left" data-bind="text: start_time() + '时-' + end_time() + '时'"></td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">场地片数：</td>
                            <td align="left"><span data-bind="text: court_num"></span>片</td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">金额：</td>
                            <td align="left"><span data-bind="text: cost"></span>元</td>
                        </tr>
                    </table>

                    <input type="submit" style="width: 45%; margin-top: 20%" class="btn btn-primary btn-block"
                           data-bind="click:create,
                                    enable:user() && hall() && event_date() && start_time() && end_time() && court_num() && cost()"
                           value="确认预订">

                </td>
            </tr>
        </table>

    </div>
</div>

<script type="text/javascript">
    seajs.use(['reserve_order/order'], function (ReserveOrder) {
        var reserveOrder = new ReserveOrder(<?= json_encode($order)?>);
        reserveOrder.preview = function(){
            var defer = reserveOrder.generate(true);
            defer.done(function(){
                $('#previewModal').addClass('active');
            });
            defer.fail(function(msg){
                alert(msg);
            });
        };

        reserveOrder.create = function(){
            var defer = reserveOrder.generate(false);
            defer.done(function(){
                window.location.href = '/reserve_order_buyer?stat=0';
            });
            defer.fail(function(msg){
                alert(msg);
            });
        };

        reserveOrder.calculate = function(){
            var defer = reserveOrder.generate(true);
            defer.fail(function(msg){
                alert(msg);
            });
        };

        ko.applyBindings(reserveOrder, $('#workspace')[0]);
        ko.applyBindings(reserveOrder, $('#previewModal')[0]);
    });
</script>