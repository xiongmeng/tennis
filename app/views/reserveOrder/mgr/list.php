<div class="container" id="workspace">

    <div class="row tab-v1 margin-bottom-10">
        <ul class="nav nav-tabs" data-bind="foreach: tabs">
            <li data-bind="css:{active:$root.curTab() == id}">
                <a href="javascript:void(0)" data-bind="click:$root.selectTab,text:name"></a>
            </li>
        </ul>
    </div>

    <div class="row margin-bottom-10 bg-light" data-bind="visible:curTab()=='search'" style="display: none">
        <div class="col-md-12">
            <form method="get" class="form-inline" data-bind="with: queries">
                <div class="form-group">
                    <label class="sr-only" for="hall_name">场馆</label>
                    <input type="text" class="form-control" placeholder="场馆名称" data-bind="value:hall_name">
                </div>
                <div class="form-group">
                    <a type="button" class="btn-u btn-u-green" data-bind="click:$root.search">查询</a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="alert alert-warning col-md-10" data-bind="visible:total()<1" style="display: none">
            <strong>未找到合适的预约订单！</strong>
        </div>

        <div class="alert alert-warning col-md-10" data-bind="visible:inSearching" style="display: none">
            <strong>正在加载数据，请稍候！</strong>
        </div>

        <div class="col-md-10 bg-light" data-bind="visible:total()>0" style="display: none">
            <table class="table table-stripe table-hover">
                <thead>
                <tr>
                    <th>订单号</th>
                    <th>场馆</th>
                    <th>日期</th>
                    <th>时间段</th>
                    <th>片数</th>
                    <th>金额</th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody>
                <!--ko foreach: data-->
                <tr>
                    <td data-bind="text:id"></td>
                    <td><a data-bind="text:hall().name, attr:{href: '/hall/frontend/detail/' + hall_id()}"
                           target="_blank"></a></td>
                    <td data-bind="text:event_date.date().substr(0, 10)"></td>
                    <td data-bind="text:start_time() + '-' + end_time()"></td>
                    <td data-bind="text:court_num"></td>
                    <td data-bind="text:cost"></td>
                    <td data-bind="switch: stat">
                        <a data-bind="case: <?= RESERVE_STAT_UNPAY?>" href="" target="_blank">去支付</a>
                        <span data-bind="case: <?= RESERVE_STAT_INIT?>">待处理</span>
                        <span data-bind="case: <?= RESERVE_STAT_CANCELED?>">已取消</span>
                        <span data-bind="case: $default">已支付</span>
                    </td>
                </tr>
                <!--/ko-->
                </tbody>
            </table>
        </div>
    </div>

    <?= View::make('pagination_ko'); ?>
</div>

<script type="text/javascript">
    seajs.use(['reserve_order/list', 'knockout_switch_case'], function (ReserveList, switchCase) {
        var hallList = new ReserveList({model: 'page', relations: 'Hall'});

        var disposedTab = {
            id: 'recommend',
            name: '待处理订单',
            stat: <?= RESERVE_STAT_INIT?>
        };
        hallList.tabs = [
            disposedTab,
            {
                id: 'latest',
                name: '待支付订单',
                stat: <?= RESERVE_STAT_UNPAY?>
            },
            {
                id: 'search',
                name: '全部订单',
                stat: null
            }
        ];

        hallList.curTab = ko.observable();

        hallList.selectTab = function (tab) {
            hallList.curTab(tab.id);

            hallList.queries.stat(tab.stat);
            hallList.search();
        };

        ko.applyBindings(hallList, $('#workspace')[0]);

        hallList.selectTab(disposedTab);
    });
</script>
