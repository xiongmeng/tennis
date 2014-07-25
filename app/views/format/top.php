
<html>
<body>
<!--=== Top ===-->

<div class="top">
    <div class="container">
        <ul class="loginbar pull-right">
            <?php if($user){
                echo '<li><a href="login">'.$user->nickname.'</a></li>
                <li class="devider"></li>
                <li><a href="#">个人中心</a></li>
            <li class="devider"></li>
            <li><a href="logout">退出</a></li>';}
            else{
                echo '<li><a href="login">登录</a></li>
                <li class="devider"></li>
                <li><a href="register">注册</a></li>';

            }?>
        </ul>
    </div>
</div>
<!--/top-->
</body>
</html>