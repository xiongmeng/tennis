<div class="container" id="workspace">

    <div class="tab-v1">
        <ul class="nav nav-tabs" id="hallTabs" data-bind="foreach: tabs">

            <li data-bind="css:{active:$root.curTab() == id}">
                <a href="javascript:void(0)" data-bind="click:$root.selectTab,text:name"></a>
            </li>
        </ul>
    </div>
    <div class="panel panel-default" style="border: 0">
        <div class="panel-body">
            <div class="row margin-bottom-20 bg-light" data-bind="visible:curTab()=='search'" style="display: none">
                <div class="col-md-12">
                    <form method="get" class="form-inline" data-bind="with: queries">
                        <div class="form-group">
                            <label class="sr-only" for="hall_name">场馆</label>
                            <input type="text" class="form-control" placeholder="场馆名称" data-bind="value:name">
                        </div>
                        <div class="form-group">
                            <a type="button" class="btn-u btn-u-green" data-bind="click:$root.search">查询</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="alert alert-warning col-md-10" data-bind="visible:total()<1" style="display: none">
                    <strong>未找到有合适场地的场馆！</strong>
                </div>

                <div class="alert alert-warning col-md-10" data-bind="visible:inSearching" style="display: none">
                    <strong>正在加载数据，请稍候！</strong>
                </div>

                <div class="col-md-9" data-bind="visible:total()>0" style="display: none">
                    <!--ko foreach: data-->
                    <div class="row hall margin-bottom-20 bg-light">
                        <div class="col-md-3 head-img">
                            <a href="/" class="thumbnail">
                                <img data-bind="attr:{src:head_url}">
                            </a>
                        </div>
                        <div class="col-md-6 col-xs-8 description">
                            <p class="name" data-bind="text: name">></p>

                            <p><span class="title">地址：</span><span data-bind="text: area"></span></p>

                            <p><span class="title">电话：</span><span data-bind="text: telephone"></span></p>

                            <p data-bind="with:courtGroup"><span class="title">场地：</span><span
                                    data-bind="text: name()+count()+'片'"></span></p>
                        </div>

                        <div class="col-md-3">
                            <a data-bind="attr:{href:'/hall/frontend/detail/'+id()}" class="btn btn-primary btn-lg margin-bottom-10 btn-block" target="_blank" >
                                查看详细
                            </a>
                        </div>

                        <table class="table table-bordered margin-bottom-10 price-standard">
                            <thead>
                            <tr>
                                <th>时段</th>
                                <th>门市价（元/小时）</th>
                                <th>普通会员（元/小时）</th>
                                <th class="vip">金卡会员（元/小时）</th>
                            </tr>
                            </thead>
                            <tbody data-bind="foreach: hall_prices">
                            <tr data-bind="if:id()">
                                <td data-bind="text:name"></td>
                                <td data-bind="text:market"></td>
                                <td data-bind="text:member"></td>
                                <td class="vip" data-bind="text:vip"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--/ko-->
                </div>
            </div>

            <div class="row ">
                <div class="col-md-10 bg-light" data-bind="visible: last_page()>1" style="display: none">
                    <ul class="pagination">
                        <!--ko switch: current_page()<=1-->
                        <li class="disabled" data-bind="case.visible: true"><span>«</span></li>
                        <li data-bind="case.visible: false"><a href="javascript:void(0)" data-bind="click: pre">«</a>
                        </li>
                        <!--/ko-->

                        <!--ko foreach: pages-->
                        <!--ko switch: name==$root.current_page() || name=="..."-->
                        <li data-bind="case.visible: true, css:{active:name==$root.current_page(), disabled:name=='...'}">
                            <span data-bind="text:name"></span>
                        </li>
                        <li data-bind="case.visible: false">
                            <a href="javascript:void(0)" data-bind="click:$root.go, text:name"></a>
                        </li>
                        <!--/ko-->
                        <!--/ko-->

                        <!--ko switch: current_page()==last_page()-->
                        <li class="disabled" data-bind="case.visible: true"><span>»/span></li>
                        <li data-bind="case.visible: false"><a href="javascript:void(0)" data-bind="click: pre">»</a>
                        </li>
                        <!--/ko-->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    seajs.use(['hall/list', 'knockout_switch_case'], function (HallList, switchCase) {
        var hallList = new HallList({model: 'page', relations: 'HallPrices,HallImages,Envelope,CourtGroup'});

        var recommendTab = {
            id: 'recommend',
            name: '推荐场馆',
            cfg: {url: '/hall/active/list/<?= HALL_ACTIVE_RECOMMEND?>'}
        };
        hallList.tabs = [
            recommendTab,
            {
                id: 'latest',
                name: '最新场馆',
                cfg: {url: '/hall/active/list/<?= HALL_ACTIVE_LATEST?>'}
            },
            {
                id: 'search',
                name: '搜索场馆',
                cfg: {
                    url: '/hall/search',
                    per_page: 10
                }
            }
        ];

        hallList.curTab = ko.observable();

        hallList.selectTab = function (tab) {
            hallList.curTab(tab.id);

            tab.cfg && (hallList.cfg = $.extend(hallList.cfg, tab.cfg));
            hallList.search();
        };

        hallList.queries.stat(<?= HALL_STAT_PUBlISH?>);

        ko.applyBindings(hallList, $('#workspace')[0]);

        hallList.selectTab(recommendTab);
    });
</script>
