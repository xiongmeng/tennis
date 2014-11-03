<!--=== Content ===-->

<div class="container" xmlns="http://www.w3.org/1999/html">
<div class="row">
<div class="col-md-12" id="worktable">
<ul class="nav nav-tabs nav-justified" role="tablist">
    <li role="presentation"><a href="#base">基本信息</a></li>
    <li role="presentation"><a href="#user">用户信息</a></li>
    <li role="presentation"><a href="#map">地图信息</a></li>
    <li role="presentation"><a href="#detail">详细信息</a></li>
</ul>

<div class="panel panel-default" id="base">
    <div class="panel-heading">场馆基本信息</div>
    <div class="panel-body">
        <form class="form-horizontal">
            <div class="form-group">
                <label class="control-label col-md-3">场馆ID：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="场馆ID" type="text" data-bind="value:id" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">场馆名称：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="场馆名称" type="text" data-bind="value:name">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">简称：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="场馆名称" type="text" data-bind="value:code">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">电话：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="电话" type="text" data-bind="value:telephone">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">联系人：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="联系人" type="text" data-bind="value:linkman">
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">省份：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="省份" type="text" data-bind="value:province" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">市：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="市" type="text" data-bind="value:city" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-3">区县：</label>

                <div class="col-md-8">
                    <input class="form-control" placeholder="区县" type="text" data-bind="value:county" disabled>
                </div>
            </div>


            <div class="form-group">
                <label class="control-label col-md-3">详细地址：</label>

                <div class="col-md-8">
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
    seajs.use('hall/hall', function (hall) {
        hall.init($('#worktable')[0], <?= json_encode($hall)?>);
    });
</script>