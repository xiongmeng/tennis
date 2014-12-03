

<div class="bar bar-nav tab" style="border: 0">
    <div class="segmented-control worktable" data-bind="foreach:tabs" style="padding-top: 5px; border: 0">
        <a class="date" data-bind="css:{active:$root.curTab() == id}, click:$root.selectTab, text:name"
           data-ignore="push">
        </a>
    </div>
</div>

<header class="bar bar-standard bar-header-secondary" data-bind="visible:curTab()=='search',with:queries" style="display: none; border: 0">
    <form style="display: inline" class="search" action="/mobile_home/reserve/recommend">
        <input style="width: 65%;font-size:15px;"
               type="search" class="pull-left" placeholder="请输入场馆名" data-bind="value:name">
        <input type="submit" class="btn btn-primary" data-bind="click: $root.search" value="search">
    </form>
</header>

<div class="content" style="margin-bottom: 50px;" data-bind="style:{'padding-top': curTab()!='search' ? '40px' :'80px'}">

    <ul class="table-view hall-on-sale" data-bind="visible:total()<1 && !inSearching()" style="display: none">
        <li class="notice">
            <p data-bind="visible:curTab()=='nearby'">您还没有同意上传地理位置信息哦！</p>
            <p data-bind="visible:curTab()=='history'">您还没有预订过场地哦！</p>
            <p data-bind="visible:curTab()=='search'">木有找见合适的场馆哦！</p>
        </li>
    </ul>
    <ul class="table-view hall-on-sale" data-bind="foreach: data, visible:total()>0" style="display: none">
        <li class="table-view-cell media">
            <a style="padding: 10px" data-bind="attr:{href:'/hall_reserve?hall_id=' + id()}" data-ignore="push">
                <img class="media-object pull-left head-img" data-bind="attr:{src:head_wx_url}">
                <div class="media-body description" style="width: 70%; float: left">
                    <p class="name" data-bind="text:name"></p>
                    <p><span class="header">地址：</span><span data-bind="text:area_text"></span></p>
                    <p><span class="header">电话：</span><span data-bind="text:telephone"></span></p>
                </div>
            </a>
            <a style="padding: 10px" data-bind="attr:{href:'/hall_reserve?hall_id=' + id()}" data-ignore="push">
                <table>
                    <thead>
                    <tr>
                        <th>时段</th>
                        <th>门市价</th>
                        <th>普通会员</th>
                        <th>金卡会员</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tbody data-bind="foreach: hall_prices">
                    <tr data-bind="if:id()">
                        <td data-bind="text:name"></td>
                        <td data-bind="text:market"></td>
                        <td data-bind="text:member"></td>
                        <td class="vip" data-bind="text:vip"></td>
                    </tr>
                    </tbody>
                </table>
            </a>
        </li>
    </ul>

    <p data-bind="text:inSearching() + ' '"></p>

    <button class="btn btn-primary btn-block from-button" data-ignore="push" style="display: none"
            data-bind="visible:curTab()=='search', click:next, text:!inSearching()?'点击加载更多':'正在加载， 请稍候...'"></button>
    <button class="btn btn-primary btn-block from-button" style="display: none" disabled
            data-bind="visible:curTab()!='search' && inSearching(), text:inSearching()?'正在加载， 请稍候...':'点击加载更多'">
        正在加载， 请稍候...</button>
</div>

<script type="text/javascript">
    seajs.use(['hall/list', 'knockout_switch_case'], function (HallList, switchCase) {
        var hallList = new HallList({model: 'falls', relations: 'HallPrices'});

        var recommendTab = {
            id: 'recommend',
            name: '推荐场馆',
            cfg: {url: '/hall/active/list/<?= HALL_ACTIVE_RECOMMEND?>'}
        };
        hallList.tabs = [
            recommendTab,
            {
                id: 'nearby',
                name: '附近场馆',
                cfg: {url: '/hall/nearby'}
            },
            {
                id: 'search',
                name: '搜索场馆',
                cfg: {
                    url: '/hall/search',
                    per_page: 2
                }
            },
            {
                id: 'history',
                name: '常订场馆',
                cfg: {url: '/hall/history'}
            }
        ];

        hallList.curTab = ko.observable();

        hallList.selectTab = function (tab) {
            hallList.curTab(tab.id);

            tab.cfg && (hallList.cfg = $.extend(hallList.cfg, tab.cfg));

            hallList.data.removeAll();
            if(tab.id != 'search'){
                hallList.search();
            }
        };

        hallList.queries.stat(<?= HALL_STAT_PUBlISH?>);

        hallList.nextText = ko.computed(function(){
            return !hallList.inSearching()?'点击加载更多':'正在加载， 请稍候...';
        });

        ko.applyBindings(hallList, $('#body')[0]);

        hallList.selectTab(recommendTab);
    });
</script>