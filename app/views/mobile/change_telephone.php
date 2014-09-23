<div class="content" style="margin-top: 20px">
    <form style="display: inline" id="form1" name="form1" action="<?= url_wrapper('/mobile_change_telephone') ?>"
          method="post">
        <!--        显示已绑定手机号-->
        <?php if ($user->telephone) { ?>
            <div class="form-controller">
                <div class="item">
                    <label class="label">旧手机：</label>
                    <label class="input-normal"><?= $user->telephone ?></label>
                </div>
            </div>
        <?php } ?>

        <div class="form-controller">
            <div class="item">
                <label class="label">新手机：</label>
                <input class="input-normal" type="text" name="telephone" id="telephone" placeholder=""
                       data-bind="value:telephone">
            </div>

            <div class="error" id="tele" data-bind="text:telephoneError"></div>
        </div>

        <div class="form-controller">
            <div class="item">
                <label class="label">验证码：</label>
                <input class="input-normal" style="width: 28%;" name="validcode" id="validcode"
                       type="text" value="<?= isset($queries['validcode']) ? $queries['validcode'] : '' ?>">
                <label>&nbsp;</label>
                <input class="btn btn-positive input-normal" type="button" id="tel_valid_code" style="width: 38%;"
                       data-bind="click:getValidCode,value:validCodeText,enable:ttl()<=0">
            </div>

            <div class="error" id="code" data-bind="text:validcodeError"></div>
        </div>
        <input type="submit" id="ok" name="ok" style="width:90%; margin: 5px auto ;" class="btn btn-primary btn-block"
               value="确定">

        <a style="width: 80%; margin-left: 10%;float: left;margin-bottom: 18px"
           href="<?= url_wrapper('/get_password') ?>" data-ignore="push">
            <p>如果您长时间未收到短息，可直接拨打（4000 66 5189），由客服人员帮忙绑定新的手机号</p></a>
    </form>

    <script>
        seajs.use('/mobile/js/changeTelephone', function (changeTelephone) {
            changeTelephone.init($('body')[0],
                <?= json_encode(array('errors' => $errors, 'queries' => $queries, 'validCode' => $validCode))?>);
        })
        ;
    </script>
</div>