<div class="content" style="margin-top: 10px">
    <form style="display: inline" id="form1" action="<?= url_wrapper('/submit_reserve_order') ?>" method="post">
        <a style="width: 28%; margin-left: 20px;float: left">预定人</a>
        <input style="width: 60%;font-size:15px;"
               type="text" name="nickname" class="pull-left" placeholder="" readonly="readonly" class="calc"
               id="mynickname"
               value="<?= $user->nickname ?>">
        <input type="hidden" name="user_id" value="<?= $user->user_id ?>" class="calc" id="user_id">


        <a style="width: 28%; margin-left: 20px;float: left">场馆名称</a>
        <input style="width: 60%;font-size:15px;"
               type="text" name="hallname" class="pull-left" placeholder="" readonly="readonly"
               value="<?= $hall->name ?>">
        <input type="hidden" name="hall_id" value="<?= $hall->id ?>" class="calc" id="select_order_court">

        <a style="width: 28%; margin-left: 20px;float: left">日期</a>

        <select name="event_date" style="width: 60%;" id="stimestart">
            <?php foreach ($dates as $key => $date) { ?>

                <option class="calc" value="<?= $key ?>"><?= $date ?></option>
            <?php } ?>
        </select>

        <a style="width: 28%; margin-left: 20px;float: left">时段</a>

        <select name="start_time" id="start_time" style="width: 25%;" class="calc">
            <?php foreach ($hours as $key => $hour) { ?>
                <option class="calc" value="<?= $key ?>"><?= $hour ?></option>
            <?php } ?>
        </select>

        <a style="width: 15%;font-size:15px;">&nbsp;-&nbsp;</a>

        <select name="end_time" id="end_time" style="width: 25%;" class="calc">

            <?php foreach ($hours as $key => $hour) { ?>
                <option class="calc" value="<?= $key ?>"><?= $hour ?></option>
            <?php } ?>

        </select>

        <a style="width: 28%; margin-left: 20px;float: left">片数</a>

        <select name="court_num" style="width: 60%;" id="court_num" class="calc">
            <option value="1">1片</option>
            <option value="2">2片</option>
            <option value="3">3片</option>
            <option value="4">4片</option>
        </select>

        <a style="width: 28%; margin-left: 20px;float: left">金额（元）</a>
        <input style="width: 35%;font-size:15px;"
               type="text" name="price" class="pull-left" placeholder="自动生成" readonly="readonly"
               id="order_cost">
        <input type="button" id="cost" style="width: 22%; margin-left: 10px;" class="btn btn-primary" value="计算金额">
        <br/><br/>
        <input type="button" id="ok2" style="width: 40%; margin-left: 30%;" class="btn btn-primary btn-block"
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
                            <td width="300" align="left"><?= $user->nickname ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">预订场馆：</td>
                            <td align="left"><?= $hall->name ?>
                            </td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">活动日期：</td>
                            <td align="left"><span id="pre_date"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">时间段：</td>
                            <td align="left"><span id="pre_time"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">场地片数：</td>
                            <td align="left"><span id="pre_court_num"></span>
                            </td>
                        </tr>
                        <tr>
                            <td width="230" height="50" align="right">金额：</td>
                            <td align="left"><span id="pre_order_cost"></span>元
                            </td>
                        </tr>

                        <!--<tr><td width="100" height="20" align="right" class="textgreen textweight" style="line-height: 120%">温馨提示：</td>-->
                        <!--<td align="left" class="textblack" style="line-height: 120%">请问您是否需要客服人员与您电话确认订单？</td></tr>-->

                        <!--<tr class="textlineheight textblack"><td height="20">&nbsp;</td>-->
                        <!--<td align="left"><input type="radio" name="is_need_tel" id="need" value="1" checked="checked"/>-->
                        <!--<label for="radio">需要</label>-->
                        <!--<input type="radio" name="is_need_tel" id="noneed" value="2"/>-->
                        <!--<label for="radio">不用</label></td>-->
                        <!--</tr>-->

                    </table>

                    <input type="submit" id="ok" style="width: 45%; margin-top: 20%" class="btn btn-primary btn-block"
                           value="确认预订">

                </td>
            </tr>
        </table>

    </div>
</div>
</form>
<script>
    $('#cost').click(function () {
        ajaxCalcCost();
    });
    /**
     * ajax获取服务器端自动计算出的消费金额
     */
    function ajaxCalcCost() {
        $('#order_cost').val();
        var endtime = $('#end_time').val();
        var starttime = $('#start_time').val();
        var eventdate = $('#stimestart').val();
        if (!((endtime - starttime) > 0)) {
            if (endtime == 0 && starttime == 0) {
                alert("还没有选择时段哦")
            } else {
                alert("时段 结束时间要大于开始时间哦！")
            }
        }


        //填充json数据
        var params = {};
        params[$('#user_id').attr("name")] = $('#user_id').val();
        params[$('#select_order_court').attr("name")] = $('#select_order_court').val();

        params[$('#stimestart').attr("name")] = $('#stimestart').val();
        params[$('#start_time').attr("name")] = $('#start_time').val();
        params[$('#end_time').attr("name")] = $('#end_time').val();
        params[$('#court_num').attr("name")] = $('#court_num').val();

        $.ajax
        ({
            url: "http://www.wangqiuer.com/ajax/court_queryCost",
            type: "POST",
            dataType: 'json',
            timeout: 3000,
            data: params,
            beforeSend: function (XMLHttpRequest) {
            },
            success: function (data, textStatus) {
                ajax_res = data.res;
//                if (ajax_res.code != 0) {
//                    art.dialog({
//                        title: "提示",
//                        content: ajax_res.desc,
//                        lock: true, //开启锁屏遮罩
//                        fixed: true, //开启固定定位
//                        okValue: "确定",
//                        ok: function () {
//                            return true;
//                        }
//                    });
////					alert(ajax_res.desc);
//                    return false;
//                }
                cost = data.cost_result;
                $('#order_cost').val(cost.i_cost);
                $('#order_cost_text').val(cost.s_cost);
            },
            complete: function (XMLHttpRequest, textStatus) {
            },
            error: function () {
                alert("网络错误，请稍后再试！~");
            }
        })
        return true;
    }

    $('#ok2').click(function () {
        var cost = $('#order_cost').val();

        if (!cost) {
            alert("您还没有计算金额哦！")

        }
        else {
            order_preview();
        }

    });
    function order_preview() {
        var time = $('#start_time').val() + "点-" + $('#end_time').val() + "点";


        $('#pre_date').text($('#stimestart').val());
        $('#pre_time').text(time);

        $('#pre_court_num').text($('#court_num').val());
        $('#pre_order_cost').text($('#order_cost').val());
        $('#previewModal').addClass('active');
    }


</script>