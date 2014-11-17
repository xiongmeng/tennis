<!--=== Content ===-->

<div class="container" xmlns="http://www.w3.org/1999/html">
<div class="row">
<div class="col-md-12" id="worktable">
<div class="tab-v1">
    <ul class="nav nav-tabs nav-justified" id="tablist">
        <li><a href="#base">基本信息</a></li>
        <li><a href="#court">场地信息</a></li>
        <li><a href="#price">价格标准</a></li>
        <li><a href="#market">日期时段</a></li>
        <li><a href="#image">场馆相册</a></li>
        <li><a href="#user">用户信息</a></li>
        <li><a href="#map">地图信息</a></li>
        <li><a href="#detail">详细信息</a></li>
    </ul>
</div>

<div class="panel panel-default" id="base">
    <div class="panel-heading">场馆基本信息</div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-md-2">场馆ID：</label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="场馆ID" type="text" data-bind="value:id" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">场馆名称：</label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="场馆名称" type="text" data-bind="value:name">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">简称：</label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="场馆名称" type="text" data-bind="value:code">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">电话：</label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="电话" type="text" data-bind="value:telephone">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">联系人：</label>

                <div class="col-md-10">
                    <input class="form-control" placeholder="联系人" type="text" data-bind="value:linkman">
                </div>
            </div>

            <div class="form-group" data-bind="area: area">
                <label class="control-label col-md-2">地址：</label>

                <div class="col-md-2">
                    <select class="form-control"
                            data-bind="options: area.provinces, optionsValue: 'id', optionsText: 'name', value: area.province, optionsCaption: '省份'"></select>
                </div>
                <div class="col-md-2">
                    <select class="form-control"
                            data-bind="options: area.cities, optionsValue: 'city_id', optionsText: 'city', value: area.city, optionsCaption: '市'"></select>
                </div>
                <div class="col-md-2">
                    <select class="form-control"
                            data-bind="options: area.counties, optionsValue: 'county_id', optionsText: 'county', value: area.county, optionsCaption: '县、区'"></select>
                </div>
                <div class="col-md-4">
                    <input class="form-control" placeholder="详细地址" type="text" data-bind="value:area_text">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-md-offset-4">
                    <button class="btn-u btn-u-green btn-block" data-bind="click:update">提交保存</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default" id="court" data-bind="with:courtGroup">
    <div class="panel-heading">场地信息</div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-md-3">ID：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="ID" type="text" data-bind="value:id" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">类型：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="类型" type="text" data-bind="value:name">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">数目：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="初始密码" type="text" data-bind="value:count">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-md-offset-4">
                    <button class="btn-u btn-u-green btn-block" data-bind="click:$root.saveCourtGroup">提交保存</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default" id="price">
    <div class="panel-heading">价格标准</div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="8%">场地类型</th>
                <th width="10%">计价标准</th>
                <th width="10%">门市价</th>
                <th width="10%">会员价</th>
                <th width="10%">VIP价</th>
                <th width="10%">采购价</th>
                <th width="10%">操作</th>
            </tr>
            </thead>
            <tbody data-bind="foreach: hall_prices">
            <tr>
                <td data-bind="text:id"></td>
                <td>
                    <select class="form-control" data-bind="with:$root.courtGroup,value:court_type">
                        <option data-bind="text: name, value:id"></option>
                    </select>
                </td>
                <td class="has-success"><input type="text" class="form-control" data-bind="value:name"></td>
                <td class="has-success"><input type="text" class="form-control" data-bind="value:market"></td>
                <td class="has-success"><input type="text" class="form-control" data-bind="value:member"></td>
                <td class="has-success"><input type="text" class="form-control" data-bind="value:vip"></td>
                <td class="has-success"><input type="text" class="form-control" data-bind="value:purchase"></td>
                <td>
                    <div class="btn-toolbar">
                        <button class="btn btn-primary"
                                data-bind="click:$root.savePrice, enable:name()&&market()&&member()&&vip()&&purchase()">
                            保存
                        </button>
                        <button class="btn btn-danger" data-bind="click:$root.deletePrice, enable:id()">删除</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-default" id="market">
    <div class="panel-heading">日期时段</div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th width="5%">ID</th>
                <th width="8%">类型</th>
                <th width="10%">开始星期</th>
                <th width="10%">结束星期</th>
                <th width="10%">开始时间</th>
                <th width="10%">结束时间</th>
                <th width="10%">计价标准</th>
                <th width="10%">操作</th>
            </tr>
            </thead>
            <tbody data-bind="foreach: hall_markets">
            <tr>
                <td data-bind="text:id"></td>
                <td>
                    <select class="form-control"
                            data-bind="options:$root.types,value:type,
                            optionsText:'name',optionsValue:'id',optionsCaption: ' '"></select>
                </td>

                <td class="has-success"><select class="form-control"
                                                data-bind="options:$root.weeks,value:start_week,
                            optionsText:'name',optionsValue:'id',optionsCaption: ' '"></select></td>
                <td class="has-success"><select class="form-control"
                                                data-bind="options:$root.weeks,value:end_week,
                            optionsText:'name',optionsValue:'id',optionsCaption: ' '"></select></td>
                <td class="has-success"><select class="form-control"
                                                data-bind="options:$root.hours,value:start,optionsCaption: ' '"></select>
                </td>
                <td class="has-success"><select class="form-control"
                                                data-bind="options:$root.hours,value:end,optionsCaption: ' '"></select>
                </td>
                <td class="has-success"><select class="form-control" data-bind="options:$root.hall_prices,
                    optionsText: function(item){return item.name()},
                                   optionsValue: function(item){return item.id()},
                value:price"></td>
                <td>
                    <div class="btn-toolbar">
                        <button class="btn btn-primary"
                                data-bind="click:$root.saveMarket, enable:type()&&start_week()&&end_week()&&start()&&end()&&price()">
                            保存
                        </button>
                        <button class="btn btn-danger" data-bind="click:$root.deleteMarket, enable:id()">删除</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-default" id="image">
    <div class="panel-heading">场馆相册</div>
    <div class="panel-body">
<!--        <div class="row" data-bind="foreach: hall_images">-->
<!--            <div class="col-md-3 well">-->
<!--                <!--ko if:path()-->-->
<!--                <div class="row">-->
<!--                    <button class="btn btn-primary" data-bind="enable:id()!=$root.image(),click:$root.setEnvelope">-->
<!--                        选为首页-->
<!--                    </button>-->
<!--                    <button class="btn btn-danger" data-bind="click:$root.deleteImage">删除图片</button>-->
<!--                </div>-->
<!--                <div class="row">-->
<!--                    <a class="thumbnail" data-bind="attr:{href:path}" target="_blank" style="margin-top: 10px">-->
<!--                        <img data-bind="attr:{src:path}">-->
<!--                    </a>-->
<!--                </div>-->
<!--                <!--/ko -->-->
<!---->
<!--                <!--ko if:!path()-->-->
<!--                <div data-bind="plupload: $root.images">-->
<!--                    <a class="js_btn btn btn-block btn-primary" href="javascript:;"><span class="btn_wrap">上传</span></a>-->
<!--                </div>-->
<!--                <!--/ko -->-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>

<div class="panel panel-default" id="user" data-bind="with:user">
    <div class="panel-heading">用户信息</div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-md-3">用户ID：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="用户ID" type="text" data-bind="value:user_id" disabled>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">登录名：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="登录名" type="text" data-bind="value:nickname"
                        <?php if (count($hall->users)) { ?> disabled <?php } ?>>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">初始密码：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="初始密码" type="text" data-bind="value:init_password"
                        <?php if (count($hall->users)) { ?> disabled <?php } ?>>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">预订信息短信通知手机号：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="预订信息短信通知手机号" type="text"
                           data-bind="value:receive_sms_telephone" disabled>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-md-offset-4">
                    <button class="btn-u btn-u-green btn-block" data-bind="click:$root.generateUser">提交保存</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default" id="map" data-bind="with:map">
    <div class="panel-heading">地图信息</div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-md-3">经度：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="经度" type="text" data-bind="value:long">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">纬度：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="纬度" type="text" data-bind="value:lat">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">百度地图中的搜索关键字：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="百度地图中的搜索关键字" type="text" data-bind="value:baidu_code">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-md-offset-4">
                    <button class="btn-u btn-u-green btn-block" data-bind="click:$root.saveMap">提交保存</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default" id="detail">
    <div class="panel-heading">场馆详细信息
    </div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-md-3">场馆排序：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="场馆排序" type="text" data-bind="value:sort">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">营业时间：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="营业时间" type="text" data-bind="value:business">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">场馆温度：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="场馆温度" type="text" data-bind="value:air">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">洗浴条件：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="洗浴条件" type="text" data-bind="value:bath">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">停车位：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="停车位" type="text" data-bind="value:park">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">穿线服务：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="穿线服务" type="text" data-bind="value:thread">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">商品种类：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="商品种类" type="text" data-bind="value:good">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">备注：</span></label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="备注" type="text" data-bind="value:comment">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-3 col-md-offset-4">
                    <button class="btn-u btn-u-green btn-block" data-bind="click:update">提交保存</button>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
</div>

<script>
    seajs.use(['hall/hall'], function (HallModel) {
        var hallData = <?= json_encode($hall)?>;
        var hall = new HallModel(hallData);
        ko.applyBindings(hall, $('#worktable')[0]);
    });

    var tabs = $('#tablist li');
    tabs.click(function () {
        tabs.removeClass('active');
        $(this).addClass('active');
    });

    var curTab = location.hash || '#base';
    tabs.find('[href=' + curTab + ']').parent().addClass('active');
</script>