<script type="text/javascript" src="/mobile/js/changeTelephone.js?201409242323"></script>

<div class="content" style="margin-bottom: 50px">

    <form style="margin-top: 10px" id="form_reg" action="<?= url_wrapper('/mobile_register') ?>" method="post">
        <div class="form-controller">
            <div class="item">
                <label class="label">昵称：</label>
                <input class="input-normal" type="text" name="nickname" placeholder=""
                       id="nickname" value="<?=isset($queries['nickname']) ? $queries['nickname'] : ''?>" >
            </div>

            <div class="error" id="nick"><?php if ($errors->first('nickname')) {
                    echo $errors->first('nickname');
                } ?></div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">密码：</label>
                <input class="input-normal" name="password" type="text" placeholder="密码需要在6-20位之间" id="password"
                       value="<?=isset($queries['password']) ? $queries['password'] : ''?>" >
            </div>

            <div class="error" id="pwd"><?php if ($errors->first('password')) {
                    echo $errors->first('password');
                } ?></div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">手机：</label>
                <input class="input-normal" type="text" name="telephone" id="telephone" placeholder=""
                       data-bind="value:telephone">
            </div>

            <div class="error" id="tele" data-bind="text:telephoneError"></div>
        </div>

        <div class="form-controller">
            <div class="notice" id="code">注：获取短信验证码时请收起输入法面板</div>
            <div class="item">
                <label class="label">验证码：</label>
                <input class="input-normal" style="width: 28%;" name="validcode" id="validcode"
                       type="text" value="<?= isset($queries['validcode']) ? $queries['validcode'] : '' ?>">
                <label>&nbsp;</label>
                <input class="btn btn-positive input-normal" type="button" id="tel_valid_code" style="width: 38%;height: 35px;font-size: 15px"
                       data-bind="click:getValidCode,value:validCodeText,enable:ttl()<=0">
            </div>

            <div class="error" id="code" data-bind="text:validcodeError"></div>
        </div>

        <input type="submit" name="ok2" id="ok2" style="width:90%; margin: 5px auto ;" class="btn btn-primary btn-block"
               value="注册">

        <a style="width: 80%; margin-left: 10%;float: left;margin-bottom: 18px"
           href="<?= url_wrapper('/get_password') ?>" data-ignore="push">
            <p>如果您长时间未收到短息，可直接拨打（4000 66 5189），由客服人员帮忙设置手机号</p></a>
    </form>

    <script>
        seajs.use('/mobile/js/changeTelephone', function (changeTelephone) {
            changeTelephone.init($('body')[0],
                <?= json_encode(array('errors' => $errors, 'queries' => $queries, 'validCode' => $validCode))?>);
        })
        ;
    </script>
</div>
