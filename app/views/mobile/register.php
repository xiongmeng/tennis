<div class="content">

    <form style="margin-top: 10px" id="form_reg" action="<?= url_wrapper('/mobile_register') ?>" method="post">
        <input type="hidden" name="app_id" value="<?= Input::get('app_id') ?>">
        <input type="hidden" name="app_user_id" value="<?= Input::get('app_user_id') ?>">

        <div class="form-controller">
            <div class="item">
                <label class="label">昵称</label>
                <input class="input-normal" type="text" name="nickname" placeholder=""
                       id="nickname" value="" onblur="namecheck();">
            </div>

            <div class="error" id="nick"><?php if ($errors->first('nickname')) {
                    echo $errors->first('nickname');
                } ?></div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">真实姓名</label>
                <input class="input-normal"
                       type="text" name="realname" id="realname" placeholder=""
                       value="" onBlur="realcheck();">
            </div>

            <div class="error"
               id="real"><?php if ($errors->first('realname')) {
                    echo $errors->first('realname');
                } ?></div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">密码</label>
                <input class="input-normal" name="password" type="password" placeholder="密码需要在6-20位之间" id="password"
                       value="" onBlur="pwdcheck();">
            </div>

            <div class="error" id="pwd"><?php if ($errors->first('password')) {
                    echo $errors->first('password');
                } ?></div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">确认密码</label>
                <input class="input-normal" type="password" name="password_confirmation" placeholder="请再次输入密码"
                       id="password_confirmation"
                       value="" onBlur="con_pwdcheck();">
            </div>

            <div class="error"
               id="con_pwd"><?php if ($errors->first('password_confirmation')) {
                    echo $errors->first('password_confirmation');
                } ?></div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">手机</label>
                <input class="input-normal" type="text" name="telephone" id="id_telephone" placeholder=""
                       value="" onBlur="telecheck();">
            </div>

            <div class="error" id="tele"><?php if ($errors->first('telephone')) {
                    echo $errors->first('telephone');
                } ?></div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">验证码</label>
                <input class="input-normal" style="width: 28%;" name="validcode" id="label8"
                       type="text" placeholder=""
                       value="" onBlur="codecheck();">
                <label>&nbsp;</label>
                <input class="btn btn-positive input-normal" type="button" id="tel_valid_code" style="width: 38%;"

                       value="获取短信验证码">
            </div>

            <div class="error" id="code"><?php if ($errors->first('validcode')) {
                    echo $errors->first('validcode');
                } ?></div>
        </div>

        <input type="button" name="ok2" id="ok2" style="width:90%; margin: 5px auto ;" class="btn btn-primary btn-block"
               value="注册">
    </form>


    <div style="text-align: center;padding: 10px">已经有网球通帐号？
        <a href="<?= url_wrapper('/mobile_bond') ?>" data-ignore="push">去绑定</a></div>

</div>
<script>
    function namecheck() {
        var nickname = $('#nickname').val();
        if (!nickname) {
            nick.innerText = "昵称不能为空";
        }
        else {
            $.ajax({
                url: "/nicknameValid",
                type: "POST",
                data: {'nickname': nickname},
                dataType: 'json',
                beforeSend: function () {

                },
                success: function (data) {

//
                    if (!data) {
                        nick.innerText = '昵称已经存在，换个昵称试试';
                    } else {
                        nick.innerText = "";
                    }


                },
                complete: function (data) {


                }
            });//ajax
        }
    }
    function realcheck() {
        var realname = $('#realname').val();
        if (!realname) {
            real.innerText = "请输入您的真实姓名";
        } else {
            real.innerText = "";
        }
    }
    function pwdcheck() {
        var password = $('#password').val();
        if (!password) {
            pwd.innerText = "请输入密码";
        } else {
            if (password.length < 6 || password.length > 20) {
                pwd.innerText = "密码需要在6-20位之间哦";
            } else {
                pwd.innerText = "";
            }
        }
    }
    function con_pwdcheck() {
        var password = $('#password').val();
        var password_confirmation = $('#password_confirmation').val();
        if (!password_confirmation) {
            con_pwd.innerText = "请再次确认密码";
        } else {
            if (password != password_confirmation) {
                con_pwd.innerText = "两次的密码不一致哦";
            } else {
                con_pwd.innerText = "";
            }
        }
    }
    function telecheck() {
        var telephone = $('#id_telephone').val();
        if (!telephone) {
            tele.innerText = "请输入手机号码";
        } else {
            if (telephone.length != 11) {
                tele.innerText = "请输入有效的手机号码";
            } else {
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
                            tele.innerText = '您的手机号已经注册了哦';
                        } else {
                            tele.innerText = "";
                        }


                    },
                    complete: function (data) {


                    }
                });//ajax
            }
        }
    }
    function codecheck() {
        var validcode = $('#label8').val();
        if (!validcode) {
            code.innerText = "请输入验证码";
        } else {
            $.ajax({
                url: "/telValidCodeValid",
                type: "POST",
                data: {'telephone': $('#id_telephone').val(), 'validcode': validcode},
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

    $('#ok2').click(function () {
        submit();
    });

    function submit() {
        if (!code.innerText && !nick.innerText && !real.innerText && !tele.innerText
            && !pwd.innerText && !con_pwd.innerText) {
            $('#form_reg').submit();
        }
        else {
            alert('您没有正确填写！请检查红色标记部分。');
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
            if ($('#id_telephone').val()) {
                oButton = this;
                $.ajax({
                    url: "/telValidCodeMake",
                    type: "POST",
                    data: {'telephone': $('#id_telephone').val()},
                    dataType: 'json',
                    beforeSend: function () {
                        oButton.disabled = true;
                    },
                    success: function (data) {
                        ajax_res = data;
//
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
                alert('请输入您的手机号码');
            }
        });//click
    });//function


</script>