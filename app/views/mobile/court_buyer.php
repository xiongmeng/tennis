<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>GoTennis</title>

    <!-- Sets initial viewport load and disables zooming  -->
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <!-- Makes your prototype chrome-less once bookmarked to your phone's home screen -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Include the compiled Ratchet CSS -->
    <link href="/mobile/css/ratchet.css" rel="stylesheet">

    <link href="/mobile/css/app.css?1" rel="stylesheet">
<!--    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.css">-->

    <!-- Include the compiled Ratchet JS -->
    <script src="/mobile/js/ratchet.js"></script>

    <script type="text/javascript" src="/assets/plugins/seajs/sea.js"></script>
    <script type="text/javascript" src="/assets/js/seajs-config.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-1.10.2.min.js"></script>
<!--    <script type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>-->

    <script type="text/javascript">
        seajs.config({
            paths: {
                plugin: '/assets/plugins',
                module: '/assets/js/module'
            },
            base: '/assets/js/module/',
            charset: 'utf-8',
            map: [
                [/(.*\/assets\/js\/module\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + '201408221157']
            ]
        });
    </script>
</head>
<body>

<header class="bar bar-nav">
    <h1 class="title push-left">123</h1>
</header>

<!-- Block button in standard bar fixed below top bar -->
<div class="bar bar-standard bar-header-secondary">
    <div class="segmented-control worktable">
        <?php foreach ($dates as $date => $time) { ?>
            <a class="date <?php if ($date == $activeDate) { ?>active<?php } ?>"
               href="/mobile_court_buyer/<?= $hallID ?>?date=<?= $date ?>" data-ignore="push">
                <?= date('m-d', $time) ?>
            </a>
        <?php } ?>
    </div>
</div>

<div class="content">
    <?php if (empty($instantOrders)) { ?>
        <div class="alert alert-info"><strong>没有可出售的场地！</strong></div>
    <?php } else { ?>
        <div class="table-court">
            <div class="col-hour">
                <a class="hour disabled">&nbsp;</a>
                <?php for ($startHour = $instantOrders->first()->start_hour; $startHour < $instantOrders->last()->start_hour; $startHour++) { ?>
                    <a class="hour disabled"
                       name="hour-<?= $startHour ?>"><?= $startHour, '-' . ($startHour + 1) ?></a>
                <?php } ?>
            </div>
            <?php foreach ($courts as $court) { ?>
                <div class="col-instant-order">
                    <a name="court-<?= $court->id ?>" class="court disabled"><?= $court->number ?>号场</a>
                    <?php for ($startHour = $instantOrders->first()->start_hour; $startHour < $instantOrders->last()->start_hour; $startHour++) { ?>
                        <?php if (isset($formattedInstants[$court->id]) && isset($formattedInstants[$court->id][$startHour])) { ?>
                            <?php $instantOrder = $formattedInstants[$court->id][$startHour]; ?>
                            <!-- ko with:$root.instantOrders[<?= $court->id ?>][<?= $startHour ?>]-->
                            <a name="<?= 'instant-order-' . $court->id . '-' . $startHour ?>"
                               title="<?= $startHour . '-' . ($startHour + 1) . ' ' . $court->number . '号场' ?>"
                                <?php if ($instantOrder->state == 'on_sale') { ?>
                               class="instant-order buy"
                               data-bind="click: $root.select, css: {active: select}">
                                <span style="font-size: small" class="money">￥</span><span
                                    data-bind="text: quote_price"></span>

                                <?php } else if ($loginUserId == $instantOrder->buyer && $instantOrder->state == 'paying') { ?>

                                    class="instant-order paying" data-bind="click: $root.select,
                                    css: {active: select}">
                                    <span style="font-size: small" class="money">￥</span><span
                                        data-bind="text: quote_price"></span>
                                    <em style="color: red" class="countDown"
                                        data-bind="attr: {'data-time': parseInt(expire_time())+60}"></em>

                                <?php } else if ($loginUserId == $instantOrder->buyer && $instantOrder->state == 'payed') { ?>
                                    class="instant-order living" style="background-color: #f0ad4e">待打球
                                <?php } else { ?>
                                    class="instant-order">&nbsp;
                                <?php } ?>
                            </a>
                            <!--/ko-->
                        <?php } else { ?>
                            <span class="instant-order">&nbsp;</span>
                        <?php } ?>
                    <?php } ?>
                </div>>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<!-- Block button in standard bar fixed above the footer -->
<div class="bar bar-standard bar-footer-secondary" data-bind="visible: selected().length>0">
    <div class="segmented-control">
        <a class="control-item" href="#confirmingBuyModal"
           data-bind="visible: currentState()=='on_sale'">buy</a>
        <a class="control-item" href="#confirmingPayModal"
           data-bind="visible: currentState()=='paying'">pay</a>
        <a class="control-item "
           data-bind="click: batchCancelBuy, visible: currentState()=='paying'">cancel-buy</a>
    </div>
</div>

<!--==nav==-->
<nav class="bar bar-tab bar-footer">
    <a class="tab-item" href="<?= url_wrapper('/mobile_home/instant') ?>" data-transition="slide-in"
       data-ignore="push">
        home </a>
    <a class="tab-item" href="<?= url_wrapper('/mobile_buyer') ?>" data-transition="slide-in" data-ignore="push">

        center
    </a>
    <a class="tab-item" href="http://homestead.app:8000/order_court_buyer/8935" data-transition="slide-in"
       data-ignore="push">

        notice </a>
</nav>


<div id="confirmingBuyModal" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" href="#confirmingPayModal"></a>
        <h1 class="title">确认购买吗</h1>
    </header>

    <div class="content">
        <p class="content-padded">共选取<mark data-bind="text:selected().length"></mark>个时段，共计<mark data-bind="text:selectedMoney"></mark>元</p>
        <div class="content-padded">
            <button class="btn btn-positive btn-block" data-bind="click: batchBuy">去购买</button>
        </div>
    </div>
</div><!--==end nav==-->

<div id="confirmingPayModal" class="modal">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" href="#confirmingPayModal"></a>
        <h1 class="title">确认支付吗</h1>
    </header>

    <div class="content">
        <p class="content-padded">共选取<mark data-bind="text:selected().length"></mark>个时段，共计<mark data-bind="text:selectedMoney"></mark>元</p>
        <div class="content-padded">
            <button class="btn btn-positive btn-block" data-bind="click: batchPay">去支付</button>
        </div>
    </div>
</div><!--==end nav==-->


<div id="noMoneyModal" class="modal" data-bind="with:$root.noMoney">
    <header class="bar bar-nav">
        <a class="icon icon-close pull-right" href="#noMoneyModal"></a>
        <h1 class="title">余额不够啦</h1>
    </header>

    <div class="content">
        <p class="content-padded">总共需要花费
            <mark data-bind="text: needPay"></mark>
            元
        </p>
        <p class="content-padded">您当前可用余额
            <mark data-bind="text: balance">
                <mark>元
        </p>
        <p class="content-padded">您还需要支付
            <mark data-bind="text: needRecharge"></mark>
            元
        </p>
        <div class="content-padded">
            <a class="btn btn-positive btn-block" data-bind="attr{href: adviseForwardUrl}" data-ignore="push">去支付</a>
        </div>
    </div>
</div><!--==end nav==-->

<script>
    seajs.use('/mobile/js/manage', function (courtManage) {
        courtManage.init($('body')[0],
            <?= json_encode(array('instantOrders' => $formattedInstants, 'noMoney' => $noMoney))?>);
    })
    ;
</script>
</body>
</html>