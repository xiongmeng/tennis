<!DOCTYPE html>
<!--[if IE 8]>
<html lang="zh-CN" class="ie8"> <![endif]-->
<!--[if IE 9]>
<html lang="zh-CN" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="zh-CN"> <!--<![endif]-->
<head>
    <title>网球通——即时订场</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSS Global Compulsory-->
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/headers/header1.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link rel="shortcut icon" href="favicon.ico">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="/assets/plugins/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/plugins/flexslider/flexslider.css">
    <link rel="stylesheet" href="/assets/plugins/parallax-slider/css/parallax-slider.css">
    <!-- CSS Style Page -->
    <link rel="stylesheet" href="/assets/css/pages/page_log_reg_v1.css">
    <link rel="stylesheet" href="/assets/css/pages/page_pricing.css">

    <!-- CSS Theme -->
    <link rel="stylesheet" href="/assets/css/themes/default.css" id="style_color">
    <link rel="stylesheet" href="/assets/css/themes/headers/default.css" id="style_color-header-1">

    <!-- datePicker css-->
    <link rel="stylesheet" href="/assets/plugins/smalot-bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css">

    <script type="text/javascript" src="/assets/plugins/seajs/sea.js"></script>
    <script type="text/javascript" src="/assets/js/seajs-config.js"></script>
    <script type="text/javascript" src="/assets/plugins/jquery-1.10.2.min.js"></script>

    <script type="text/javascript">
        seajs.config({
            paths: {
                plugin: '/assets/plugins',
                module: '/assets/js/module'
//                'module_common': '<{$UI_resources_site}>/js/module_common',
//                lib: '<{$UI_resources_site}>/js/lib'
            },
            base: '/assets/js/module/',
            charset: 'utf-8',
            alias: {
//                ueditor: '/js/lib/ueditor/1.2.6/ueditor_cmd.js'
            },
            map: [
                [/(.*\/assets\/js\/module\/.*\.(?:css|js|tpl))(?:.*)$/i, '$1?' + '201408221157']
            ]
        });
    </script>
</head>

<body>

<!--=== Header ===-->
<?php echo $header; ?><!--/header-->
<!--=== End Header ===-->

<?php echo $content; ?>



<?php echo $copyright; ?>

<!-- JS Global Compulsory -->
<script type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--<script type="text/javascript" src="/assets/plugins/stickUp/stickUp.js"></script>-->

<script type="text/javascript" src="/assets/plugins/toolbar/jquery.toolbar.js"></script>
<script type="text/javascript" src="/assets/plugins/pin/jquery.pin.js"></script>
</body>
</html>	
