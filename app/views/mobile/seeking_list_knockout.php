<header class="bar bar-nav">
    <form style="display: inline" data-bind="with:queries">
        <div class="form-controller pull-left">
            <input type="text" style="width: 35%; margin: 6px 0" placeholder="场馆关键字" data-bind="value:hall_name">
            <select style="width: 23%" data-bind="options:event_date.options,
        optionsText:'name', optionsValue:'id', value:event_date, optionsCaption:'日期'"></select>
            <select style="width: 20%" data-bind="options:tennis_level.options,
        optionsText:'name', optionsValue:'id', value:tennis_level, optionsCaption:'级别'"></select>
            <input data-bind="click:$root.search" type="submit"
                   style="width: 15%;top: 9px;margin-left: 4px" class="btn btn-primary pull-right" value="查询">
        </div>
    </form>
</header>

<div class="content" style="margin-bottom: 50px; padding-top: 16px">
    <ul class="table-view hall-on-sale" data-bind="if: seekingList().length<=0">
        <li class="table-view-cell notice"><p data-bind="text:inSearching()?'正在加载， 请稍候...':'抱歉，没有您想要的约球信息！'">正在加载， 请稍候...</p></li>
    </ul>

    <ul class="table-view hall-on-sale" style="display: none" data-bind="visible: seekingList().length>0">
        <!-- ko foreach: seekingList-->
        <li class="table-view-cell">
            <a style="padding: 10px" data-bind="attr:{href: detail_url}" data-ignore="push">
                <img class="media-object pull-left head-img"
                     data-bind="attr:{src:'http://wangqiuer.com/Images/weixinImage/CourtPic/' + hall_id() + '.jpg'}">

                <div class="media-body description" style="width:52%;float: left">
                    <p><span class="name" data-bind="text:hall_name"></span> &nbsp;<span
                            data-bind="text:court_num"></span>片</p>

                    <p>
                        <span class="header">时间：</span>
                        <span data-bind="text:event_date().substr(5,5)"></span>日&nbsp;
                        <span data-bind="text:start_hour"></span>-
                        <span data-bind="text:end_hour"></span>时

                    <p>
                        <span class="header">坑位：</span>
                        <span style="font-size: 20px;color: red;font-weight: bold" data-bind="text:on_sale"></span>坑&nbsp;/&nbsp;<span
                            data-bind="text:store"></span>坑
                    </p>
                </div>
                <div class="price">
                    <p style="text-align: right"><span style="color: darkolivegreen;font-size: 20px;font-weight: bold"
                                                       data-bind="text:tennis_level.text()"></span></p>

                    <p style="text-align: right"><span class="symbol">￥</span><span class="money"
                                                                                    data-bind="text:personal_cost"></span>
                    </p>
                </div>
            </a>
        </li>
        <!--/ko-->
    </ul>

    <button class="btn btn-primary btn-block from-button" data-ignore="push"
            data-bind="enable: currentPage()<total(), click:loadNextPage">
        <span data-bind="text:inSearching()?'正在加载， 请稍候...':'点击加载更多'">正在加载， 请稍候...</span></button>

</div>

<script>
    seajs.use('seeking/list', function (SeekingListModel) {
        var seekingList = new SeekingListModel({}, {}, {perPage: 1});
        ko.applyBindings(seekingList, $('#body')[0]);
//        ko.applyBindings(seekingList, $('header')[0]);
        seekingList.search();
    });
</script>