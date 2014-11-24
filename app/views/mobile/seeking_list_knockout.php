<header class="bar bar-nav">
    <form style="display: inline" class="search" action="/mobile_home/reserve/recommend">
        <input style="width: 65%;font-size:15px;"
               type="search" name="hall_name" class="pull-left" placeholder="请输入场馆名"
               value="<?= isset($queries['hall_name']) ? $queries['hall_name'] : '' ?>">
        <input type="submit" class="btn btn-primary" value="search">
    </form>
</header>

<div class="content" style="margin-bottom: 50px; padding-top: 66px">
    <ul class="table-view hall-on-sale" style="display: none" data-bind="visible: seekingList().length>=0">
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

    <a class="btn btn-primary btn-block from-button" data-ignore="push" data-bind="click:search">点击加载更多</a>

</div>

<script>
    seajs.use('seeking/list', function (SeekingListModel) {
        var seekingList = new SeekingListModel({});
        ko.applyBindings(seekingList, $('#content')[0]);

        seekingList.search();
    });
</script>