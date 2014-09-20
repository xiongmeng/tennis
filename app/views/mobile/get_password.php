<div class="content" style="margin-top: 70px">
    <form style="display: inline" id="from" name="form1" action="<?= url_wrapper('/get_password') ?>" method="post">
        <input type="hidden" name="app_id" value="<?= $queries['app_id']?>">
        <input type="hidden" name="app_user_id" value="<?= $queries['app_user_id']?>">
        <a style="width: 60%; margin-left: 10%;float: left;">输入绑定的手机号：</a>
        <input style="width: 70%;font-size:15px;margin-left: 15%"
               type="text" name="telephone" class="pull-left" placeholder=""
               id="telephone"
               value="" onblur="checkpwd()" onfocus="pwd()">
        <p id="teleok" style="width: 70%;font-size:15px;margin-left: 45%;color:green "></p>
        <p id="tele" style="width: 70%;font-size:15px;margin-left: 18%;color:red "></p>

        <input type="button" id="get" name="bond" style="width: 50%; margin-left: 25%;" class="btn btn-primary btn-block"
               value="获取密码">

        <a style="width: 80%; margin-left: 10%;float: left;margin-bottom: 18px"
           href="<?= url_wrapper('/get_password')?>" data-ignore="push">
            <p>如果您长时间未收到短息，可直接拨打（4000 66 5189），由客服人员帮忙找回密码</p></a>
    </form>
    <script>
        $('#get').click(function(){
            checkpwd();
            var telephone = $('#telephone').val();
            if(telephone  && !tele.innerText && teleok.innerText == 'ok' ){
                $('#from').submit();
            }

        });


        function checkpwd(){
            var telephone = $('#telephone').val();
            if(!telephone){
                tele.innerText = '请输入手机号码';

            }
            else{
                if(telephone.length != 11){
                    alert('请输入有效的手机号码');
                }
                else{
                    $.ajax({
                        url: "/telephoneValid",
                        type: "POST",
                        data: {'telephone': telephone},
                        dataType: 'json',
                        beforeSend: function () {

                        },
                        success: function (data) {

//
                            if (data) {
                                tele.innerText = '您输入的手机号还未被注册';
                                teleok.innerText = '';

                            } else {

                                tele.innerText = '';
                            }


                        },
                        error:function(){
                            alert('网络错误,请稍候重试');
                        },
                        complete: function (data) {


                        }
                    });//ajax

                }
            }
        }
    </script>