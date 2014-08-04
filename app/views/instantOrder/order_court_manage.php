<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <title>网球通</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS Global Compulsory-->
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/headers/header1.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link rel="shortcut icon" href="favicon.ico">
    <!-- CSS Page Style -->
    <link rel="stylesheet" href="/assets/css/pages/page_pricing.css">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="/assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/plugins/flexslider/flexslider.css">
    <link rel="stylesheet" href="/assets/plugins/parallax-slider/css/parallax-slider.css">
    <!-- CSS Theme -->
    <link rel="stylesheet" href="/assets/css/themes/default.css" id="style_color">
    <link rel="stylesheet" href="/assets/css/themes/headers/default.css" id="style_color-header-1">
</head>

<body>

<!--=== Top ===-->
<?php echo $top;?><!--/top-->
<!--=== End Top ===-->

<!--=== Header ===-->
<?php echo $header;  ?><!--/header-->
<!--=== End Header ===-->

<!--=== Breadcrumbs ===-->
<div class="breadcrumbs margin-bottom-20">
    <div class="container">
        <ul class="nav nav-tabs" role="tablist">
            <?php foreach($halls as $hall){?>

                <li role="presentation" class="active"><a href="order_court_manage?hall_id=<?php echo $hall->id?>&court_id="><h3><?php echo $hall->name;?></h3></a></li>

            <?php }?>
        </ul>
    </div>
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content ===-->
<div class="container">
    <div class="row row margin-bottom-20">
        <!--Begin Sidebar Menu-->
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" role="tablist">
                <?php foreach($courts as $court){?>
                    <li role="presentation" class="active"><a href="order_court_manage?hall_id=<?php echo $court->hall_id; ?>&court_id=<?php echo $court->id?>"><h3><?php echo $court->number?>号场</h3></a></li>

                <?php }?>
            </ul>
        </div>
        <!--End Sidebar Menu-->

        <!--Begin Content-->
        <?php function weekday($time){if(is_numeric($time)){$weekday = array('周日','周一','周二','周三','周四','周五','周六');return $weekday[date('w',$time)];}}?>
        <div class="no-space-pricing">
        <?php foreach($dates as $date){?>
            <div class="col-md-2 col-sm-6">
                <table class="table" >
                    <thead>
                    <tr>
                        <th>
                        <div class="pricing hover-effect">
                            <div class="pricing-head" >
                                            <h3><?php echo weekday(strtotime($date));?>
                                                   <span> <?php echo substr($date , 0 , 10);?></span></h3>
                                        </ul>
                                    </div>
                                </div>

                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php foreach($instants as $instant){?>
                                <?php   $fsm = new InstantOrderFsm($instant);?>
                                <?php if(($instant->event_date)==$date){?>
                            <div class="pricing hover-effect">
                                <div class="pricing-head" >
                                                    <h4><span><?php echo $instant->start_hour.':00-'.$instant->end_hour.':00'; ?></span>
                                                        <span><?php echo '价格:'.$instant->quote_price;?></span>
                                                        <span><?php if((!$fsm->can('offline')&&!$fsm->can('online'))){?>
                                                               <?php echo $states[$instant->state]['label'];?>
                                                               <?php }?></span>

                                    <?php if($fsm->can('online')){ ?>
                                            <span><a href="fsm-operate/<?php echo $instant->id;?>/online"<i class="icon-check">上架</i></a></span>
                                                        <?php }?>
                                                         <?php if(($fsm->can('offline'))){?>

                                                            <span><a href="fsm-operate/<?php echo $instant->id;?>/offline"<i class="icon-trash">下架</i></a></span>

                                                        <?php }?>
                                            </div>
                                        </div>

                                <?php }?>
                            <?php }?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

        <?php }?>
        <!--End Basic Table-->
    </div>
        </div>
</div>

<!--/container-->
<!--=== End Content Part ===-->




<!--=== End Content ===-->
<!-- JS Global Compulsory -->
<script type="text/javascript" src="/assets/plugins/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/plugins/hover-dropdown.min.js"></script>
<script type="text/javascript" src="/assets/plugins/back-to-top.js"></script>
<!-- JS Implementing Plugins -->
<script type="text/javascript" src="/assets/plugins/flexslider/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="/assets/plugins/parallax-slider/js/modernizr.js"></script>
<script type="text/javascript" src="/assets/plugins/parallax-slider/js/jquery.cslider.js"></script>
<!-- JS Page Level -->
<script type="text/javascript" src="/assets/js/app.js"></script>
<script type="text/javascript" src="/assets/js/pages/index.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        App.init();
        App.initSliders();
        Index.initParallaxSlider();
    });
</script>
<!--[if lt IE 9]>
<script src="/assets/plugins/respond.js"></script>
<![endif]-->


</body>
</html>