<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->  
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->  
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
    <title>Unify | Welcome...</title>

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
    <!-- CSS Style Page -->    
    <link rel="stylesheet" href="/assets/css/pages/page_log_reg_v1.css">
    <!-- CSS Theme -->    
    <link rel="stylesheet" href="/assets/css/themes/default.css" id="style_color">
    <link rel="stylesheet" href="/assets/css/themes/headers/default.css" id="style_color-header-1">
</head> 

<body>
<!--=== Top ===-->


<!--/top-->
<!--=== End Top ===-->


<!--=== Header ===-->
<?php echo $header;  ?>
<!--/header-->
<!--=== End Header ===-->

<!--=== Breadcrumbs ===-->
<!--<div class="breadcrumbs margin-bottom-40">
    <div class="container">
        <h1 class="pull-left">Login</h1>
        <ul class="pull-right breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li><a href="">Pages</a></li>
            <li class="active">Login</li>
        </ul>
    </div><!--/container
</div>--><!--/breadcrumbs-->
<!--=== End Breadcrumbs ===-->

<!--=== Content Part ===-->
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
            <form class="reg-page" action="logining" method="post">
                <div class="reg-header">
                    <h2>网球通账号登录</h2>
                </div>

                <div class="input-group margin-bottom-20">
                    <span class="input-group-addon"><i class="icon-user"></i></span>
                    <input type="text" name="nickname" placeholder="昵称/手机号" class="form-control">
                </div>
                <div class="input-group margin-bottom-20">
                    <span class="input-group-addon"><i class="icon-lock"></i></span>
                    <input type="text" name="password" placeholder="密码" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="checkbox"><input type="checkbox">记住登录</label>
                    </div>
                    <div class="col-md-6">
                        <input class="btn-u pull-right" type="submit" value="登录">
                    </div>
                </div>

                <hr>

                <h4><a href="http://www.wangqiuer.com/user_passwordReset.html">忘记密码 ?</h4>
                <p>不用担心，点击<a class="color-green" href="http://www.wangqiuer.com/user_passwordReset.html">这里</a>返回网球通订场网站去重置密码</p>
            </form>
        </div>
    </div><!--/row-->
</div><!--/container-->
<!--=== End Content Part ===-->

<!--=== Footer ===-->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 md-margin-bottom-40">
                <!-- About -->
                <div class="headline"><h2>关于我们</h2></div>
                <p class="margin-bottom-25 md-margin-bottom-40"></p>
                <!-- Monthly Newsletter -->
                <div class="headline"><h2></h2></div>
                <p></p>

            </div><!--/col-md-4-->


            <div class="col-md-6">
                <!-- Monthly Newsletter -->
                <div class="headline"><h2>联系我们</h2></div>

            </div><!--/col-md-4-->
        </div><!--/row-->
    </div><!--/container-->
</div><!--/footer-->
<!--=== End Footer ===-->


<!--=== Copyright ===-->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <p class="copyright-space">
                    2013 &copy; Unify. ALL Rights Reserved.
                    <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>
                </p>
            </div>
            <div class="col-md-6">
                <a href="index.html">
                    <img id="logo-footer" src="/assets/img/logo.png" class="pull-right" alt="" />
                </a>
            </div>
        </div><!--/row-->
    </div><!--/container-->
</div><!--/copyright-->
<!--=== End Copyright ===-->
<!-- JS Global Compulsory -->           
<script type="text/javascript" src="/assets/plugins/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/assets/plugins/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/plugins/hover-dropdown.min.js"></script>
<script type="text/javascript" src="/assets/plugins/back-to-top.js"></script>
<!-- JS Page Level -->           
<script type="text/javascript" src="/assets/js/app.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        App.init();
    });
</script>
<!--[if lt IE 9]>
    <script src="/assets/plugins/respond.js"></script>
<![endif]-->


</body>
</html> 
