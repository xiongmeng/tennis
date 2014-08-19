<!--=== Content Part ===-->
<div class="container">
    <!--Reg Block-->
    <div class="reg-block">
        <form class="reg-page" action="" method="get">
        <div class="reg-block-header">
            <h3>会员绑定</h3>

        </div>

        <div class="input-group margin-bottom-20">
            <span class="input-group-addon"><i class="icon-user"></i></span>
            <input type="text" name="nickname" placeholder="昵称/手机号" class="form-control">
            <input type="hidden" name="app_user_id" value="<?= $queries['app_user_id']?>">
            <input type="hidden" name="app_id" value="<?= $queries['app_id']?>">
        </div>
        <div class="input-group margin-bottom-20">
            <span class="input-group-addon"><i class="icon-lock"></i></span>
            <input type="text" name="password" placeholder="密码" class="form-control">
        </div>
        <hr>
        <label class="checkbox">

            <p><a href="">忘记密码?</a></p>
        </label>

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <button type="submit" class="btn-u btn-block">确定绑定</button>
            </div>
        </div>

    </div>
    <!--End Reg Block-->
</div><!--/container-->
<!--=== End Content Part ===-->