<div class="bar bar-nav tab" style="border: 0">
    <div class="segmented-control worktable" data-bind="foreach:tabs" style="padding-top: 5px; border: 0">
        <a class="date" data-bind="css:{active:$root.curTab() == id}, click:$root.selectTab, text:name"
           data-ignore="push">
        </a>
    </div>
</div>

<div class="content" style="margin-bottom: 50px;">

    <ul class="table-view hall-on-sale">
        <!--ko foreach: cacheData -->

        <li class="table-view-cell media">
            <a data-bind="click:$root.next">
                <img class="media-object pull-left head-img" data-bind="attr:{src:head_wx_url}">

                <div class="media-body description" style="width: 70%; float: left">
                    <p class="name" data-bind="text:name"></p>

                    <p><span class="header">地址：</span><span data-bind="text:area_text"></span></p>

                    <p><span class="header">电话：</span><span data-bind="text:telephone"></span></p>
                </div>
            </a>
        </li>

        <!--/ko-->
    </ul>
</div>


<script type="text/javascript">
    seajs.use(['hall/list', 'knockout_switch_case'], function (HallList, switchCase) {
        var hallList = new HallList({model: 'falls', relations: 'HallPrices'});

        var recommendTab = {
            id: 'recommend',
            name: '推荐场馆',
            cfg: {url: '/hall/active/list/<?= HALL_ACTIVE_RECOMMEND?>'}
        };

        var searchTab = {
            id: 'search',
            name: '搜索场馆',
            cfg: {
                url: '/hall/search',
                per_page: 4
            }
        };

        hallList.tabs = [
            recommendTab,
            {
                id: 'nearby',
                name: '附近场馆',
                cfg: {url: '/hall/nearby'}
            },
            searchTab,
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
//            if (tab.id != 'search') {
                hallList.search();
//            }
        };

        hallList.queries.stat(<?= HALL_STAT_PUBlISH?>);


        ko.applyBindings(hallList, $('#body')[0]);

        hallList.selectTab(searchTab);

    });
</script>