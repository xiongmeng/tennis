<!--=== Content ===-->

<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="col-md-9">
            <?php $hall instanceof Hall && 1; ?>
            <div class="panel panel-primary hall" id="base">
                <div class="panel-heading"><?= $hall->name ?></div>
                <div class="panel-body">
                    <p><label class="col-md-2 text-right">地址：</label><span><?= $hall->area ?></span></p>

                    <p><label class="col-md-2 text-right">电话：</label><span><?= $hall->telephone ?></span></p>

                    <p><label
                            class="col-md-2 text-right">场地：</label><span><?= $hall->CourtGroup->name . $hall->CourtGroup->count . '片' ?></span>
                    </p>

                    <p><label class="col-md-2 text-right">场馆温度：</label><span><?= $hall->air ?></span></p>

                    <p><label class="col-md-2 text-right">洗浴条件：</label><span><?= $hall->bath ?></span></p>

                    <p><label class="col-md-2 text-right">停车位：</label><span><?= $hall->park ?></span></p>

                    <p><label class="col-md-2 text-right">穿线服务：</label><span><?= $hall->thread ?></span></p>

                    <p><label class="col-md-2 text-right">商品种类：</label><span><?= $hall->good ?></span></p>

                    <p><label class="col-md-2 text-right">营业时间：</label><span><?= $hall->business ?></span></p>
                </div>
            </div>

            <div class="panel panel-primary hall" id="price">
                <div class="panel-heading">场地价格</div>
                <div class="panel-body">
                    <table class="table table-bordered price-standard" style="margin-bottom: 10px !important">
                        <thead>
                        <tr>
                            <th>时段</th>
                            <th>门市价（元/小时）</th>
                            <th>普通会员（元/小时）</th>
                            <th class="vip">金卡会员（元/小时）</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($hall->HallPrices as $price) { ?>
                            <tr>
                                <td><?= $price->name ?></td>
                                <td><?= $price->market ?></td>
                                <td><?= $price->member ?></td>
                                <td class="vip"><?= $price->vip ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                    <p style="color: #018DE5">备注：<br/>
                        <?php $weeks = array('1' => '周一', '2' => '周二', '3' => '周三', '4' => '周四', '5' => '周五', '6' => '周六', '7' => '周日') ?>

                        <?php foreach ($hall->HallPrices as $price) { ?>
                            <?= $price->name ?>：<br/>
                            <?php foreach ($hall->HallMarkets as $market) { ?>
                                <?php if ($market->price == $price->id) { ?>
                                    <?php if ($market->type == 1) { ?>
                                        法定节假日&nbsp;和
                                    <?php } ?>
                                    <?= $weeks[$market->start_week] ?><?php if ($market->start_week != $market->end_week) { ?>到<?= $weeks[$market->end_week] ?>
                                    <?php } ?>
                                    <?= $market->start ?>点—<?= $market->end ?>点
                                    <br/>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </p>
                </div>
            </div>


            <div class="panel panel-primary hall" id="image">
                <div class="panel-heading">场馆相册</div>
                <div class="panel-body">
                    <?php foreach ($hall->HallImages as $image) { ?>
                        <div class="col-md-3">
                            <a class="thumbnail" target="_blank" href="<?= $image->path ?>">
                                <img src="<?= $image->path; ?>">
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="panel panel-danger hall" id="map" data-bind="with:map">
                <div class="panel-heading">怎么去这里</div>
                <div class="panel-body">
                    <div id="dituContent" style="height: 200px"></div>
                    <a href="http://map.baidu.com/?newmap=1&s=inf%26uid%3D<?=$hall->Map->baidu_code?>%26c%3D131%26all%3D0&fr=alas0&from=alamap" target="_blank">查看全图</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!--百度地图-->
<style type="text/css">
    html,body{margin:0;padding:0;}
    .iw_poi_title {color:#CC5522;font-size:14px;font-weight:bold;overflow:hidden;padding-right:13px;white-space:nowrap}
    .iw_poi_content {font:12px arial,sans-serif;overflow:visible;padding-top:4px;white-space:-moz-pre-wrap;word-wrap:break-word}
</style>
<script type="text/javascript" src="http://api.map.baidu.com/api?key=d19fabd8752897d4685396c384f50b0c&v=1.1&services=true"></script>

<script type="text/javascript">
    //创建和初始化地图函数：
    function initMap() {
        createMap();//创建地图
        setMapEvent();//设置地图事件
        addMapControl();//向地图添加控件
        addMarker();//向地图中添加marker
    }

    //创建地图函数：
    function createMap() {
        var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
        var point = new BMap.Point(courtlong, courtlat);//定义一个中心点坐标
        map.centerAndZoom(point, 16);//设定地图的中心点和坐标并将地图显示在地图容器中
        window.map = map;//将map变量存储在全局
    }

    //地图事件设置函数：
    function setMapEvent() {
        map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
        map.enableScrollWheelZoom();//启用地图滚轮放大缩小
        map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
        map.enableKeyboard();//启用键盘上下左右键移动地图
    }

    //地图控件添加函数：
    function addMapControl() {
        //向地图中添加缩放控件
        var ctrl_nav = new BMap.NavigationControl({anchor: BMAP_ANCHOR_TOP_LEFT, type: BMAP_NAVIGATION_CONTROL_LARGE});
        map.addControl(ctrl_nav);
        //向地图中添加缩略图控件
//	var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
//	map.addControl(ctrl_ove);
        //向地图中添加比例尺控件
        var ctrl_sca = new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT});
        map.addControl(ctrl_sca);
    }

    //标注点数组
    var courtlong = "<?= $hall->Map->long?>";//经度大(116...)
    var courtlat = "<?= $hall->Map->lat?>";//纬度小(40...)
    var aCotent = "<?= $hall->area?>";
    var aTitle = "<?= $hall->name?>";
    var markerArr = [
        {title: aTitle, content: aCotent, point: courtlong + "|" + courtlat, isOpen: 0, icon: {w: 21, h: 21, l: 0, t: 0, x: 6, lb: 5}}
    ];
    //创建marker
    function addMarker() {
        for (var i = 0; i < markerArr.length; i++) {
            var json = markerArr[i];
//            var p0 = json.point.split("|")[0];
//            var p1 = json.point.split("|")[1];
            var point = new BMap.Point(courtlong, courtlat);
            var iconImg = createIcon(json.icon);
            var marker = new BMap.Marker(point, {icon: iconImg});
            var iw = createInfoWindow(i);
            var label = new BMap.Label(json.title, {"offset": new BMap.Size(json.icon.lb - json.icon.x + 10, -20)});
            marker.setLabel(label);
            map.addOverlay(marker);
            label.setStyle({
                borderColor: "#808080",
                color: "#333",
                cursor: "pointer"
            });

            (function () {
                var index = i;
                var _iw = createInfoWindow(i);
                var _marker = marker;
                _marker.addEventListener("click", function () {
                    this.openInfoWindow(_iw);
                });
                _iw.addEventListener("open", function () {
                    _marker.getLabel().hide();
                })
                _iw.addEventListener("close", function () {
                    _marker.getLabel().show();
                })
                label.addEventListener("click", function () {
                    _marker.openInfoWindow(_iw);
                })
                if (!!json.isOpen) {
                    label.hide();
                    _marker.openInfoWindow(_iw);
                }
            })()
        }
    }
    //创建InfoWindow
    function createInfoWindow(i) {
//        var json = markerArr[i];
        var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + aTitle + "'>" + aTitle + "</b><div class='iw_poi_content'>" + aCotent + "</div>");
        return iw;
    }
    //创建一个Icon
    function createIcon(json) {
        //var icon = new BMap.Icon("http://openapi.baidu.com/map/images/us_mk_icon.png", new BMap.Size(json.w,json.h),{imageOffset: new BMap.Size(-json.l,-json.t),infoWindowOffset:new BMap.Size(json.lb+5,1),offset:new BMap.Size(json.x,json.h)})
        var icon = new BMap.Icon("Images/page/baidu_icon.png", new BMap.Size(json.w, json.h), {imageOffset: new BMap.Size(-json.l, -json.t), infoWindowOffset: new BMap.Size(json.lb + 5, 1), offset: new BMap.Size(json.x, json.h)})
        return icon;
    }

    initMap();//创建和初始化地图
</script>