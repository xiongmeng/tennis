<div class="top">
    <div class="container">
        <ul class="loginbar pull-right">
            <?php if($user){?>
            <li>欢迎:</li>
            <li><a href="/login"><?php echo $user->nickname?></a></li>
            <li class="devider"></li>
            <li><a href="/logout">退出登录</a></li>
            <?php }?>
<!--            --><?php //if(!$user){?>
<!--                <li><a href="/login">登录</a></li>-->
<!--                <!--<li class="devider"></li>-->
<!--<!--                <li><a href="register"></a></li>';-->
<!--            --><?php //}?>
        </ul>
    </div>
</div>