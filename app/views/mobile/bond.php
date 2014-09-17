
    <div class="content" style="margin-top: 70px">
        <form style="display: inline" id="form1" name="form1" action="<?= url_wrapper('/mobile_bond') ?>" method="get">
            <input type="hidden" name="app_id" value="<?= $queries['app_id']?>">
            <input type="hidden" name="app_user_id" value="<?= $queries['app_user_id']?>">
            <a style="width: 15%; margin-left: 13%;float: left;">账户</a>
            <input style="width: 60%;font-size:15px;"
                   type="text" name="nickname" class="pull-left" placeholder="昵称/手机号"
                   id="nickname"
                   value="" onblur="nickcheck()">
            <p id="nick" style="width: 60%;font-size:15px;margin-left: 30%;color:red "></p>
            <a style="width: 15%; margin-left: 13%;float: left;">密码</a>
            <input style="width: 60%;font-size:15px;"
                   type="password" name="password" class="pull-left" placeholder=""
                   value="" onblur="pwdcheck()" id="password">
            <p id="pwd" style="width: 60%;font-size:15px;margin-left: 30%;color:red "></p>
            <br/><br/>
            <input type="button" id="bond" name="bond" style="width: 40%; margin-left: 30%;" class="btn btn-primary btn-block"
                   value="绑定">
        </form>
        <p style="text-align: center;padding: 10px;">还没有网球通帐号？</p>
         <p style="text-align: center;padding: 10px">   <a  href="<?= url_wrapper('/mobile_register')?>" data-ignore="push"><font size=6px>去注册</font></a></p>
    </div>
    <script>
        $('#bond').click(function(){

            if(!nick.innerText && !pwd.innerText && $('#nickname').val() && $('#password').val()){
                $('#form1').submit();}
            else{
                alert('您用户名或密码错误');
            }
 });

        function nickcheck() {
            var nickname = $('#nickname').val();
            if(!nickname){
                nick.innerText = '请输入您的账户 昵称/手机号';
            }
            else{
                nick.innerText = '';
            }
        }
        function pwdcheck() {
            var nickname = $('#nickname').val();
            var password = $('#password').val();
            if(!password){
                pwd.innerText = '请输入您的密码';
            }
            else{
                $.ajax({
                    url: "/bondValid",
                    type: "POST",
                    data: {'nickname': nickname,'password':password},
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    success: function (data) {

//
                        if (!data) {
                            pwd.innerText = '您用户名或密码错误';
                        } else {
                            pwd.innerText = "";
                        }


                    },
                    complete: function (data) {


                    }
                });//ajax
            }
        }



    </script>