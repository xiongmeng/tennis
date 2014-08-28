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

    <link href="/mobile/css/app.css" rel="stylesheet">

    <!-- Include the compiled Ratchet JS -->
    <script src="/mobile/js/ratchet.js"></script>

    <script type="text/javascript" src="/assets/plugins/jquery-1.10.2.min.js"></script>
</head>
<body>

<header class="bar bar-nav">
    <h1 class="title push-left">123</h1>
</header>

<!-- Wrap all non-bar HTML in the .content div (this is actually what scrolls) -->
<div class="content">
    <div class="segmented-control">
        <?php foreach ($dates as $date => $time) { ?>
            <a class="control-item <?php if ($date == $activeDate) { ?>active<?php } ?>"
               href="/mobile_court_buyer/<?= $hallID ?>?date=<?= $date ?>">
                <?= date('m-d', $time) ?>
            </a>
        <?php } ?>
    </div>

    <div class="col-md-12 table-responsive pin-container" id="table_court">
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
                                   title="<?= $startHour.'-'.($startHour+1). ' ' . $court->number . '号场'?>"
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
                                        <em style="color: red" class="countDown" data-bind="attr: {'data-time': parseInt(expire_time())+60}"></em>

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
</div>


    <!--==nav==-->
    <nav class="bar bar-tab">
        <a class="tab-item" href="<?= url_wrapper('/mobile_home/instant') ?>" data-transition="slide-in"
           data-ignore="push">
            <span class="icon icon-home"></span>
            <span class="tab-label">首页</span>
        </a>
        <a class="tab-item" href="<?= url_wrapper('/mobile_buyer') ?>" data-transition="slide-in" data-ignore="push">
            <span class="icon icon-person"></span>

            <span class="tab-label">个人中心</span>

        </a>
        <a class="tab-item" href="<?= url_wrapper('#r') ?>" data-transition="slide-in" data-ignore="push">

            <span class="icon icon-star-filled"></span>
            <span class="tab-label">提醒</span>
        </a>
    </nav>
    <!--==end nav==-->

</body>
</html>