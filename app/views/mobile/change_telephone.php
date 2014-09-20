<div class="content" style="margin-top: 20px">
    <form style="display: inline" id="form1" name="form1" action="<?= url_wrapper('/mobile_change_telephone') ?>" method="post">
        <input type="hidden" name="app_id" value="<?= $queries['app_id'] ?>">
        <input type="hidden" name="app_user_id" value="<?= $queries['app_user_id'] ?>">

<!--        显示已绑定手机号-->
        <?php if ($telephone) { ?>
            <a style="width: 30%; margin-left: 10%;float: left;">已绑定手机</a>
            <p style="margin-left: 33%"><?= $telephone ?></p>
        <?php } ?>

<!--        绑定新手机号-->
        <a style="width: 30%; margin-left: 10%;float: left;">绑定新手机</a>
        <input style="width: 40%;font-size:15px;margin-left:1%"
               type="text" name="newtelephone" class="pull-left" placeholder=""
               id="newtelephone"
               value="" onblur="checktelephone()">
        <p id="tele" style="width: 70%;font-size:15px;margin-left: 18%;color:red "></p>
        <input type="button" id="tel_valid_code" name="get" style="width: 40%; margin-left: 42%;margin-bottom: 10px"
               class="btn btn-positive input-normal"
               value="获取短信验证码"><br/>

<!--        输入验证码-->
        <a style="width: 30%; margin-left: 10%;float: left;">验证码</a>
        <input style="width: 40%;font-size:15px;margin-left:1%"
               type="text" name="validcode" class="pull-left" placeholder=""
               id="validcode"
               value="" onblur="codecheck()">
        <p id="code" style="width: 70%;font-size:15px;margin-left: 18%;color:red "></p>
        <input type="button" id="ok" name="ok" style="width: 50%; margin-left: 25%;" class="btn btn-primary btn-block"
               value="确定">

<!--        提示语句-->
        <a style="width: 80%; margin-left: 10%;float: left;margin-bottom: 18px"
           href="<?= url_wrapper('/get_password') ?>" data-ignore="push">
            <p>如果您长时间未收到短息，可直接拨打（4000 66 5189），由客服人员帮忙绑定新的手机号</p></a>
    </form>


    <script>
        $('#ok').click(function () {
            submit();
        });

        function submit() {
            checktelephone();
            codecheck();
            var telephone = $('#newtelephone').val();
            var validcode = $('#validcode').val();
            if (!code.innerText && !tele.innerText && telephone && validcode) {
                $('#form1').submit();
            }
            else {
                alert('您没有正确填写！请检查红色标记部分。');
            }
        }

        function checktelephone() {
            var telephone = $('#newtelephone').val();
            if (!telephone) {
                tele.innerText = '请输入手机号码';

            }
            else {
                if (telephone.length != 11) {
                    alert('请输入有效的手机号码');
                }
                else {
                    $.ajax({
                        url: "/telephoneValid",
                        type: "POST",
                        data: {'telephone': telephone},
                        dataType: 'json',
                        beforeSend: function () {

                        },
                        success: function (data) {

//
                            if (!data) {
                                tele.innerText = '您的手机号已经被别的账号绑定';


                            } else {

                                tele.innerText = '';
                            }


                        },
                        error: function () {
                            alert('网络错误,请稍候重试');
                        },
                        complete: function (data) {


                        }
                    });//ajax

                }
            }
        }

        //验证码
        var stat = 1;
        var oButton = null;

        var CALC_TIMES = 60;
        var oTimer = null;
        var iTime = CALC_TIMES;
        function startTimer() {
            stopTimer();
            $(oButton).val(iTime + '秒后再次获取');
            oTimer = window.setTimeout(function () {
                timerEvent()
            }, 1000);
        }

        function stopTimer() {
            window.clearTimeout(oTimer);
            iTime = CALC_TIMES;
            oTimer = null;
        }

        function timerEvent() {
            iTime--;
            if (iTime <= 0) {
                oButton.disabled = false;
                $(oButton).val('重新获取验证码');
                stat = 1;
                stopTimer();
            }
            else {
                $(oButton).val(iTime + '秒后再次获取');
                window.setTimeout(function () {
                    timerEvent()
                }, 1000);
            }
        }


        $(function () {

            $('#tel_valid_code').click(function () {
                checktelephone();
                if (!tele.innerText && $('#newtelephone').val()) {
                    oButton = this;
                    $.ajax({
                        url: "/telValidCodeMake",
                        type: "POST",
                        data: {'telephone': $('#newtelephone').val()},
                        dataType: 'json',
                        beforeSend: function () {
                            oButton.disabled = true;
                        },
                        success: function (data) {
                            ajax_res = data;
                            if (!ajax_res) {
                                return;
                            }
                            stat = 2;
                            startTimer();
                        },
                        complete: function (data) {
                            if (stat != 2)
                                oButton.disabled = false;
                        }
                    });//ajax
                }
                else {
                    alert('请输入正确的手机号码');
                }
            });//click
        });//function

        function codecheck() {
            var validcode = $('#validcode').val();
            if (!validcode) {
                code.innerText = "请输入验证码";
            } else {
                $.ajax({
                    url: "/telValidCodeValid",
                    type: "POST",
                    data: {'telephone': $('#newtelephone').val(), 'validcode': validcode},
                    dataType: 'json',
                    beforeSend: function () {

                    },
                    success: function (data) {

//
                        if (!data) {
                            code.innerText = '验证码错误';
                        } else {
                            code.innerText = "";
                        }


                    },
                    complete: function (data) {


                    }
                });//ajax

            }

        }
    </script>