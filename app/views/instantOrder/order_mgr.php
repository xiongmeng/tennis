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
        <h1 class="pull-left">订单</h1>

    </div>
</div><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content ===-->
<div class="container">
    <div class="row">
        <div class="col-md-12">


            <?php echo Form::model($queries, array('method' => 'GET'))?>
            <?php echo Form::label('订单号：')?><?php echo Form::input('text', 'id')?>
            <?php echo Form::label('场馆ID：')?><?php echo Form::input('text', 'hall_id')?>
            <?php echo Form::label('买家：')?><?php echo Form::input('text', 'buyer')?>
            <?php echo Form::label('卖家：')?><?php echo Form::input('text', 'seller')?>
            <?php echo Form::submit('查询')?>
            <?php echo Form::close() ?><br/>
            <!--Basic Table Option (Spacing)-->
            <div class="panel panel-green margin-bottom-40">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon-li"></i></h3>
                </div>

                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>场馆ID</th>
                            <th>场地类型</th>
                            <th>活动时间</th>
                            <th>时段</th>
                            <th>售价</th>
                            <th>卖家</th>
                            <th>买家</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $instants as $instant){?>
                            <?php   $fsm = new InstantOrderFsm($instant);?>
                        <?php if (($instant->state)!='draft' && ($instant->state)!='expired'){?>
                            <tr>
                                <td><?php echo $instant->id; ?></td>
                                <td><?php echo $instant->hall_id; ?></td>
                                <td><?php echo $instant->court_tags; ?></td>
                                <td><?php echo substr($instant->event_date , 0 , 10); ?></td>
                                <td><?php echo $instant->start_hour.'-'.$instant->end_hour; ?></td>
                                <td><?php echo $instant->quote_price; ?></td>
                                <td><?php echo $instant->seller; ?></td>
                                <td><?php echo $instant->buyer; ?></td>
                                <td><?php echo $states[$instant->state]['label']; ?></td>

                                <td><?php if($fsm->can('cancel')){ ?>
                                        <button class="btn btn-danger btn-xs"><a href="fsm-operate/<?php echo $instant->id;?>/cancel"><i class="icon-trash"></i> 取消</a></button>
                                    <?php }?>
                                    <?php if($fsm->can('online')){ ?>
                                        <button class="btn btn-warning btn-xs"><a href="fsm-operate/<?php echo $instant->id;?>/online"><i class="icon-ok"></i>上架</a></button>
                                    <?php }?>
                                    <?php if($fsm->can('terminate')){ ?>
                                        <button class="btn btn-warning btn-xs"><a href="fsm-operate/<?php echo $instant->id;?>/terminate"><i class="icon-pencil"></i>终止</a></button>
                                        <!--<button class="btn btn-success btn-xs"><i class="icon-ok"></i> Submit</button>-->
                                        <!--<button class="btn btn-info btn-xs"><i class="icon-share"></i> Share</button></td>-->
                                    <?php }?>
                            </tr>

                        <?php } ?>

                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--End Basic Table-->

            <!--Pegination Centered-->

            <div class="text-center">
                <?php echo $instants->appends($queries)->links(); ?>
            </div>

            <!--End Pegination Centered-->

        </div>
    </div>
</div>


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