
    <div class="content" style="margin-top: 70px">
        <form style="display: inline" id="form1" name="form1" action="<?= url_wrapper('/mobile_bond') ?>" method="get">
            <input type="hidden" name="app_id" value="<?= $queries['app_id']?>">
            <input type="hidden" name="app_user_id" value="<?= $queries['app_user_id']?>">
            <a style="width: 15%; margin-left: 13%;float: left;">账户</a>
            <input style="width: 60%;font-size:15px;"
                   type="text" name="nickname" class="pull-left" placeholder="昵称/手机号"
                   id="nickname"
                   value="">

            <a style="width: 15%; margin-left: 13%;float: left;">密码</a>
            <input style="width: 60%;font-size:15px;"
                   type="password" name="password" class="pull-left" placeholder=""
                   value="">
            <br/><br/>
            <input type="button" id="bond" name="bond" style="width: 40%; margin-left: 30%;" class="btn btn-primary btn-block"
                   value="绑定">
        </form>
        <p style="margin-left: 30%;">还没有网球通帐号？
            <a  href="<?= url_wrapper('/mobile_register')?>" data-ignore="push">去注册</a></p>
    </div>
    <script>
        $('#bond').click(function(){
            var nickname = $('#nickname').val();
            var password = $('#password').val();
            if(!password && !nickname){
                if(!nickname){
                    alert('用户名不能为空');

                }else{
                    alert('您还没有输入密码');}
            }
            else{
                $('#form1').submit();
            }




            });




    </script>