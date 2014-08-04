<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" xmlns="http://www.w3.org/1999/html"> <!--<![endif]-->
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
<div class="breadcrumbs margin-bottom-40">
    <div class="container">
        <h1 class="pull-left">即时场地</h1>
    </div><!--/container-->
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->
<div class="container">
    <div class="col-lg-3">
        <div class="input-group">
            <input type="text" class="form-control">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button">Go!</button>
      </span>
        </div><!-- /input-group -->
    </div>

</div><br/>
<!--=== Content Part ===-->
<div class="container">
        <div class="row">
    <?php function weekday($time){if(is_numeric($time)){$weekday = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');return $weekday[date('w',$time)];}}?>
    <?php foreach ( $instants as $instant){?>
    <?php   $fsm = new InstantOrderFsm($instant);?>
    <?php if ($fsm->can('buy')){?>

            <div class="col-md-3 col-sm-6">
                <div class="pricing hover-effect">
                    <div class="pricing-head" >
                        <h4><i class="icon-jpy"></i><?php echo strstr($instant->quote_price,'.',TRUE); ?></i>
                        <span><?php echo substr($instant->event_date , 0 , 10).'     '.weekday(strtotime($instant->event_date)); ?></span>
                            <span><?php echo $instant->hall_name.' '.$instant->court_tags; ?></span>
                            <span><?php echo $instant->start_hour.':00-'.$instant->end_hour.':00'; ?></span></h4>
                        <a class="btn-u btn-u-large" href="fsm-operate/<?php echo $instant->id;?>/buy"<i class="icon-shopping-cart">购买</i></a>
                    </div>

                </div>
            </div>


<?php } ?>

    <?php } ?></div>
    <!--/container-->
<!--=== End Content Part ===-->

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